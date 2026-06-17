<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use App\Models\OrderModel;

/**
 * Reprocessa um pagamento MercadoPago manualmente.
 * Útil quando o webhook falhou e não foi reexecutado.
 *
 * Uso:
 *   php spark mp:reprocess {payment_id}
 *   php spark mp:reprocess --pending       (lista pedidos pendentes e pergunta qual processar)
 */
class MpReprocess extends BaseCommand
{
    protected $group       = 'app';
    protected $name        = 'mp:reprocess';
    protected $description = 'Reprocessa um pagamento MercadoPago (útil após falha de webhook)';
    protected $usage       = 'mp:reprocess [payment_id] [--pending]';
    protected $options     = [
        '--pending' => 'Lista pedidos pendentes do banco',
    ];

    public function run(array $params): void
    {
        // Mostra pedidos pendentes
        if (CLI::getOption('pending') || empty($params[0])) {
            $orderModel = new OrderModel();
            $pending = $orderModel->where('status', 'pending')
                                  ->orderBy('created_at', 'DESC')
                                  ->findAll(10);

            if (empty($pending)) {
                CLI::write('Nenhum pedido pendente encontrado.', 'yellow');
                return;
            }

            CLI::write("\nPedidos pendentes:", 'yellow');
            CLI::write(str_pad('ID', 6) . str_pad('Nome', 25) . str_pad('Email', 30) . str_pad('Valor', 10) . 'Preference ID', 'green');
            CLI::write(str_repeat('-', 100));
            foreach ($pending as $o) {
                $o = is_array($o) ? (object) $o : $o;
                CLI::write(
                    str_pad($o->id, 6) .
                    str_pad($o->buyer_name, 25) .
                    str_pad($o->buyer_email, 30) .
                    str_pad('R$ ' . number_format($o->amount, 2, ',', '.'), 10) .
                    ($o->mp_preference_id ?? '—')
                );
            }
            CLI::write('');

            $paymentId = CLI::prompt('ID do pagamento MP para reprocessar (ou Enter para cancelar)');
            if (empty($paymentId)) return;
        } else {
            $paymentId = $params[0];
        }

        CLI::write("Buscando pagamento MP #{$paymentId}...", 'yellow');

        $token = env('MERCADOPAGO_ACCESS_TOKEN') ?: getenv('MERCADOPAGO_ACCESS_TOKEN');
        if (empty($token)) {
            CLI::write('MERCADOPAGO_ACCESS_TOKEN não configurado.', 'red');
            return;
        }

        try {
            \MercadoPago\MercadoPagoConfig::setAccessToken($token);
            $client  = new \MercadoPago\Client\Payment\PaymentClient();
            $payment = $client->get((int) $paymentId);

            $paymentArr = json_decode(json_encode($payment), true);
            $mpStatus   = $paymentArr['status'] ?? '?';
            $prefId     = $paymentArr['preference_id'] ?? $paymentArr['preferenceId'] ?? null;
            $extRef     = $paymentArr['external_reference'] ?? '';
            $buyerEmail = $paymentArr['payer']['email'] ?? '?';

            CLI::write("Status MP: {$mpStatus}", 'cyan');
            CLI::write("Preference ID: " . ($prefId ?? 'null'));
            CLI::write("External ref: {$extRef}");
            CLI::write("Comprador: {$buyerEmail}");

            if ($mpStatus !== 'approved') {
                CLI::write("Pagamento não está aprovado (status: {$mpStatus}). Abortando.", 'red');
                return;
            }

            // Busca order local
            $orderModel = new OrderModel();
            $order = $prefId ? $orderModel->findByPreferenceId($prefId) : null;
            if (!$order && $extRef) {
                $row = $orderModel->where('mp_preference_id', $extRef)->first();
                $order = $row ? (is_array($row) ? (object)$row : $row) : null;
            }

            // Fallback: parseia PKG{n}_HERO{n} do external_reference
            if (!$order && preg_match('/^PKG(\d+)_HERO(\d+)$/', $extRef, $m)) {
                CLI::write("Buscando por package_id={$m[1]} hero_id={$m[2]}...", 'yellow');
                $row = $orderModel
                    ->where('package_id', (int) $m[1])
                    ->where('hero_id',    (int) $m[2])
                    ->where('status',     'pending')
                    ->orderBy('created_at', 'DESC')
                    ->first();
                $order = $row ? (is_array($row) ? (object)$row : $row) : null;
            }

            if (!$order) {
                CLI::write("Order local não encontrada para preference_id={$prefId}", 'red');
                CLI::write('Chaves do pagamento: ' . implode(', ', array_keys($paymentArr)));
                return;
            }

            CLI::write("Order encontrada: #{$order->id} — {$order->buyer_name} <{$order->buyer_email}>", 'green');

            if ($order->status === 'approved') {
                CLI::write('Order já está aprovada. Use --force para reprocessar mesmo assim.', 'yellow');
                return;
            }

            // Atualiza e dispara ações
            $orderModel->update($order->id, [
                'mp_payment_id' => (string) $paymentId,
                'status'        => 'approved',
                'mp_raw'        => json_encode($paymentArr),
            ]);

            CLI::write('Order atualizada para approved ✅', 'green');

            // Gera token agenda
            CLI::write('Gerando token de agendamento...', 'yellow');
            $agendaLink = $this->generateAgendaToken($order);
            CLI::write("Link agenda: {$agendaLink}", 'cyan');

            // Envia email ao cliente
            CLI::write('Enviando email ao cliente...', 'yellow');
            $this->sendClientBookingEmail($order, $agendaLink);

            // Notifica admin
            CLI::write('Notificando admin...', 'yellow');
            $this->sendAdminEmail($order, $paymentArr);

            CLI::write("\n✅ Reprocessamento concluído!", 'green');

        } catch (\Exception $e) {
            CLI::write('Erro: ' . $e->getMessage(), 'red');
        }
    }

    private function generateAgendaToken(object $order): string
    {
        $agendaBase = rtrim(env('AGENDA_BASE_URL', 'https://agenda.marcosantofoto.com.br'), '/');
        $apiKey     = env('AGENDA_API_KEY', '');

        if (empty($apiKey)) {
            CLI::write('AGENDA_API_KEY não configurada!', 'red');
            return $agendaBase;
        }

        $curl = \Config\Services::curlrequest(['verify' => false, 'timeout' => 10]);
        $res  = $curl->post("{$agendaBase}/api/v1/access-tokens", [
            'headers' => [
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type'  => 'application/json',
            ],
            'json' => [
                'order_id'       => (string) $order->id,
                'customer_email' => $order->buyer_email,
                'customer_name'  => $order->buyer_name,
                'customer_phone' => $order->buyer_phone ?? '',
                'expires_days'   => 90,
            ],
        ]);

        $body = json_decode($res->getBody(), true);
        return $body['link'] ?? $agendaBase;
    }

    private function sendClientBookingEmail(object $order, string $agendaLink): void
    {
        $email = service('email');
        $email->setTo($order->buyer_email);
        $email->setSubject('📸 Seu ensaio está confirmado — Agende sua data!');
        $email->setMessage(
            "<div style='font-family:Georgia,serif;max-width:600px;margin:0 auto;background:#0a0a0a;color:#fff;padding:40px;'>"
            . "<p style='font-size:.7rem;letter-spacing:.25em;text-transform:uppercase;color:#C5A059;margin:0 0 24px'>STUDIO MARCOSANTOFOTO</p>"
            . "<h2 style='font-family:Georgia,serif;font-size:2rem;font-weight:400;color:#fff;margin:0 0 24px'>Olá, {$order->buyer_name}! 🎉</h2>"
            . "<p style='color:rgba(255,255,255,.7);line-height:1.8'>Seu pagamento foi confirmado. Escolha a data do seu ensaio:</p>"
            . "<div style='text-align:center;margin:32px 0'>"
            . "<a href='{$agendaLink}' style='display:inline-block;background:linear-gradient(135deg,#C5A059,#F5E27A);color:#000;text-decoration:none;padding:16px 36px;font-family:sans-serif;font-size:.75rem;font-weight:700;letter-spacing:.2em;text-transform:uppercase'>ESCOLHER MINHA DATA →</a>"
            . "<p style='font-size:.75rem;color:rgba(255,255,255,.3);margin-top:12px'>Válido por 90 dias.</p>"
            . "</div></div>"
        );

        if ($email->send()) {
            CLI::write("✅ Email enviado para {$order->buyer_email}", 'green');
        } else {
            CLI::write('❌ Falha ao enviar email: ' . $email->printDebugger(['headers']), 'red');
        }
    }

    private function sendAdminEmail(object $order, array $payment): void
    {
        $adminEmail = env('ADMIN_EMAIL') ?: 'contato@marcosantofoto.com.br';
        $email = service('email');
        $email->setTo($adminEmail);
        $email->setSubject("💰 [REPROCESSADO] Pagamento aprovado — {$order->buyer_name}");
        $email->setMessage("<p>Pagamento reprocessado manualmente via spark mp:reprocess</p><pre>" . json_encode($payment, JSON_PRETTY_PRINT) . "</pre>");
        $email->send();
    }
}
