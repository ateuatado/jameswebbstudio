<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\PackageModel;
use App\Models\OrderModel;
use App\Models\Intention;

class PackageCheckout extends BaseController
{
    // ─────────────────────────────────────────────────────────────────────────
    // POST /comprar-ensaio
    // Cria Preference no MercadoPago, salva order local com status=pending
    // ─────────────────────────────────────────────────────────────────────────
    public function buy()
    {
        $packageId = (int) $this->request->getPost('package_id');
        $heroId    = (int) $this->request->getPost('hero_id');
        $name      = trim($this->request->getPost('name'));
        $email     = trim($this->request->getPost('email'));
        $phone     = trim($this->request->getPost('phone'));

        if (!$packageId || !$name || !$email) {
            return $this->response->setJSON(['success' => false, 'message' => 'Preencha nome e e-mail.']);
        }

        $packageModel = new PackageModel();
        $package = $packageModel->find($packageId);

        if (!$package || !$package->is_active) {
            return $this->response->setJSON(['success' => false, 'message' => 'Pacote não encontrado.']);
        }

        log_message('info', "Nova intenção de compra: {$name} <{$email}> ({$phone}) → Pacote #{$packageId} ({$package->name}) | Hero #{$heroId}");

        // ── Lê o token MP ────────────────────────────────────────────────────
        $token = getenv('MERCADOPAGO_ACCESS_TOKEN') ?: env('MERCADOPAGO_ACCESS_TOKEN');

        if (empty($token)) {
            log_message('error', 'MERCADOPAGO_ACCESS_TOKEN ausente no servidor');
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Configuração de pagamento ausente. Entre em contato.',
            ]);
        }

        // ── Cria Preference no MercadoPago ───────────────────────────────────
        try {
            \MercadoPago\MercadoPagoConfig::setAccessToken($token);
            $client = new \MercadoPago\Client\Preference\PreferenceClient();

            $nameParts = explode(' ', $name, 2);
            $firstName = $nameParts[0];
            $lastName  = $nameParts[1] ?? $nameParts[0];

            // ── Salva order local ANTES de criar preference (para ter o ID) ──
            $orderModel = new OrderModel();
            $orderModel->insert([
                'mp_preference_id' => '', // será atualizado logo abaixo
                'package_id'       => $packageId,
                'hero_id'          => $heroId ?: null,
                'buyer_name'       => $name,
                'buyer_email'      => $email,
                'buyer_phone'      => $phone,
                'amount'           => (float) $package->base_price,
                'status'           => 'pending',
                'cpf'              => trim($this->request->getPost('cpf') ?? ''),
                'rg'               => trim($this->request->getPost('rg') ?? ''),
                'marital_status'   => trim($this->request->getPost('marital_status') ?? ''),
                'address'          => trim($this->request->getPost('address') ?? ''),
                'city'             => trim($this->request->getPost('city') ?? ''),
                'state'            => trim($this->request->getPost('state') ?? ''),
                'zip_code'         => trim($this->request->getPost('zip_code') ?? ''),
                'accepted_terms_at'      => $this->request->getPost('accept_terms') ? date('Y-m-d H:i:s') : null,
                'image_usage_authorized' => $this->request->getPost('image_usage') ? 1 : 0,
            ]);
            $orderId = $orderModel->getInsertID();

            $preferenceData = [
                'items' => [[
                    'title'       => 'Ensaio Fotografico - ' . $package->name,
                    'quantity'    => 1,
                    'unit_price'  => (float) $package->base_price,
                    'currency_id' => 'BRL',
                ]],
                'payer' => [
                    'first_name' => $firstName,
                    'last_name'  => $lastName,
                    'email'      => $email,
                ],
                'back_urls' => [
                    'success' => site_url("ensaio/obrigado?order={$orderId}&pacote=" . urlencode($package->name) . "&nome=" . urlencode($name)),
                    'failure' => site_url("ensaio/falha"),
                    'pending' => site_url("ensaio/obrigado?order={$orderId}&pacote=" . urlencode($package->name) . "&nome=" . urlencode($name)),
                ],
                'auto_return'        => 'approved',
                'notification_url'   => site_url("mp/webhook"),
                'external_reference' => "PKG{$packageId}_HERO{$heroId}",
            ];

            $preference = $client->create($preferenceData);

            // Atualiza a order com o preference_id gerado
            $orderModel->update($orderId, ['mp_preference_id' => $preference->id]);

            return $this->response->setJSON([
                'success'      => true,
                'checkout_url' => $preference->init_point,
            ]);

        } catch (\MercadoPago\Exceptions\MPApiException $e) {
            $apiResponse = $e->getApiResponse();
            $statusCode  = $apiResponse ? $apiResponse->getStatusCode() : 0;
            $content     = $apiResponse ? $apiResponse->getContent() : [];
            log_message('error', 'Erro MP API ' . $statusCode . ': ' . json_encode($content));
            $cause   = $content['cause'][0] ?? null;
            $details = $cause
                ? ($cause['description'] ?? $cause['code'] ?? json_encode($cause))
                : ($content['message'] ?? json_encode($content));
            return $this->response->setJSON(['success' => false, 'message' => 'Erro MP ' . $statusCode . ': ' . $details]);

        } catch (\Exception $e) {
            log_message('error', 'Erro MP Geral: ' . $e->getMessage());
            return $this->response->setJSON(['success' => false, 'message' => 'Erro de conexão: ' . $e->getMessage()]);
        }
    }

    // ─────────────────────────────────────────────────────────────────────────
    // POST /quero-falar
    // Salva intenção de contato sem pagamento online
    // ─────────────────────────────────────────────────────────────────────────
    public function talkFirst()
    {
        $packageId = (int) $this->request->getPost('package_id');
        $heroId    = (int) $this->request->getPost('hero_id');
        $name      = trim($this->request->getPost('name'));
        $email     = trim($this->request->getPost('email'));
        $phone     = trim($this->request->getPost('phone'));

        if (!$name || !$email) {
            return $this->response->setJSON(['success' => false, 'message' => 'Preencha nome e e-mail.']);
        }

        $packageModel = new PackageModel();
        $package      = $packageModel->find($packageId);
        $packageName  = $package ? $package->name : 'Não especificado';

        log_message('info', "Intenção 'Falar Antes': {$name} <{$email}> ({$phone}) → Pacote #{$packageId} ({$packageName}) | Hero #{$heroId}");

        try {
            $intentModel = new Intention();
            $intentModel->insert([
                'hero_id'    => $heroId ?: null,
                'name'       => $name,
                'email'      => $email,
                'phone'      => $phone,
                'shoot_type' => $packageName,
                'address'    => '',
                'age'        => 0,
            ]);
        } catch (\Exception $e) {
            log_message('warning', 'Erro ao salvar intenção: ' . $e->getMessage());
        }

        // ── Envia e-mail de notificação ao admin ──
        try {
            $adminEmail = env('ADMIN_EMAIL') ?: getenv('ADMIN_EMAIL') ?: 'contato@marcosantofoto.com.br';
            $date = date('d/m/Y H:i');

            $subject = "💬 Novo lead — {$name} quer conversar";

            $message  = "<h2 style='color:#1a1a1a;font-family:sans-serif'>Novo interesse em conversar 💬</h2>";
            $message .= "<table style='font-family:sans-serif;font-size:14px;border-collapse:collapse;width:100%;max-width:500px'>";
            $message .= "<tr><td style='padding:8px;color:#666'>Nome</td><td style='padding:8px'><strong>{$name}</strong></td></tr>";
            $message .= "<tr style='background:#f9f9f9'><td style='padding:8px;color:#666'>E-mail</td><td style='padding:8px'>{$email}</td></tr>";
            $message .= "<tr><td style='padding:8px;color:#666'>Telefone</td><td style='padding:8px'>" . ($phone ?: '—') . "</td></tr>";
            $message .= "<tr style='background:#f9f9f9'><td style='padding:8px;color:#666'>Pacote de interesse</td><td style='padding:8px'><strong>{$packageName}</strong></td></tr>";
            $message .= "<tr><td style='padding:8px;color:#666'>Data</td><td style='padding:8px'>{$date}</td></tr>";
            $message .= "</table>";
            $message .= "<p style='font-family:sans-serif;font-size:13px;color:#333;margin-top:20px'>⚡ <strong>Ação:</strong> Entre em contato pelo WhatsApp o mais rápido possível.</p>";
            $message .= "<p style='font-family:sans-serif;font-size:12px;color:#999;margin-top:24px'>Marco Santo Foto — sistema automático</p>";

            $emailService = \Config\Services::email();
            $emailService->setTo($adminEmail);
            $emailService->setSubject($subject);
            $emailService->setMessage($message);

            if (!$emailService->send()) {
                log_message('error', 'Falha ao enviar e-mail de lead: ' . $emailService->printDebugger(['headers']));
            } else {
                log_message('info', "E-mail de lead enviado para {$adminEmail}");
            }
        } catch (\Exception $e) {
            log_message('error', 'Erro ao enviar e-mail de lead: ' . $e->getMessage());
        }

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Recebemos seu interesse! Entraremos em contato em breve.',
        ]);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // POST /mp/webhook
    // Recebe notificações assíncronas do MercadoPago
    // ─────────────────────────────────────────────────────────────────────────
    public function webhook()
    {
        // ── Valida assinatura do MercadoPago (soft mode — loga mas não bloqueia) ──
        $secret = env('MP_WEBHOOK_SECRET') ?: getenv('MP_WEBHOOK_SECRET');
        if (!empty($secret)) {
            $xSignature = $this->request->getHeaderLine('x-signature');
            $xRequestId = $this->request->getHeaderLine('x-request-id');

            if (!empty($xSignature)) {
                $ts = '';
                $v1 = '';
                foreach (explode(',', $xSignature) as $part) {
                    [$key, $val] = array_pad(explode('=', $part, 2), 2, '');
                    if (trim($key) === 'ts') $ts = trim($val);
                    if (trim($key) === 'v1') $v1 = trim($val);
                }

                // PHP converte pontos para underscore em query params (data.id → data_id)
                $qs = [];
                parse_str($this->request->getServer('QUERY_STRING') ?? '', $qs);
                $dataId = $qs['data.id'] ?? $qs['data_id'] ?? '';

                $manifest = "id:{$dataId};request-id:{$xRequestId};ts:{$ts}";
                $expected = hash_hmac('sha256', $manifest, $secret);

                if (!empty($v1) && !hash_equals($expected, $v1)) {
                    log_message('warning', "Webhook MP: assinatura divergente. manifest=[{$manifest}]");
                } else {
                    log_message('info', 'Webhook MP: assinatura válida.');
                }
            }
        }

        $body = $this->request->getBody();
        $data = json_decode($body, true) ?? [];

        log_message('info', 'MP Webhook recebido: ' . $body);

        // MP envia tipo "payment" quando um pagamento muda de status
        $type = $data['type'] ?? $this->request->getGet('type') ?? '';
        $id   = $data['data']['id'] ?? $this->request->getGet('id') ?? '';

        if ($type !== 'payment' || empty($id)) {
            return $this->response->setStatusCode(200)->setBody('ok');
        }

        $token = getenv('MERCADOPAGO_ACCESS_TOKEN') ?: env('MERCADOPAGO_ACCESS_TOKEN');
        if (empty($token)) {
            log_message('error', 'Webhook: token MP ausente');
            return $this->response->setStatusCode(200)->setBody('ok');
        }

        try {
            \MercadoPago\MercadoPagoConfig::setAccessToken($token);
            $paymentClient = new \MercadoPago\Client\Payment\PaymentClient();
            $payment       = $paymentClient->get((int) $id);

            // SDK v3 retorna objeto com propriedades dinâmicas — converte para array para acesso seguro
            $paymentArr = json_decode(json_encode($payment), true);
            log_message('debug', 'MP Payment payload: ' . json_encode($paymentArr));

            $mpStatus = $paymentArr['status']             ?? 'pending';
            $prefId   = $paymentArr['preference_id']      // SDK v2
                     ?? $paymentArr['preferenceId']       // SDK v3 camelCase
                     ?? null;
            $extRef   = $paymentArr['external_reference'] ?? '';

            // Mapeia status MP → nosso enum
            $statusMap = [
                'approved'      => 'approved',
                'pending'       => 'pending',
                'in_process'    => 'pending',
                'rejected'      => 'cancelled',
                'cancelled'     => 'cancelled',
                'refunded'      => 'refunded',
                'charged_back'  => 'refunded',
            ];
            $localStatus = $statusMap[$mpStatus] ?? 'pending';

            // Busca e atualiza a order local
            $orderModel = new OrderModel();
            $order = $prefId ? $orderModel->findByPreferenceId($prefId) : null;

            if (!$order && $extRef) {
                log_message('info', "Webhook: tentando busca por external_reference={$extRef}");
                $row = $orderModel->where('mp_preference_id', $extRef)->first();
                if ($row) {
                    $order = (object) $row;
                }
            }

            // Fallback: parseia external_reference no formato PKG{n}_HERO{n}
            if (!$order && preg_match('/^PKG(\d+)_HERO(\d+)$/', $extRef, $m)) {
                $row = $orderModel
                    ->where('package_id', (int) $m[1])
                    ->where('hero_id', (int) $m[2])
                    ->where('status', 'pending')
                    ->orderBy('created_at', 'DESC')
                    ->first();
                if ($row) {
                    $order = (object) $row;
                    log_message('info', "Webhook: order encontrada via ext_ref parsing: #{$order->id}");
                }
            }

            if ($order) {
                $updateData = [
                    'mp_payment_id' => (string) $id,
                    'status'        => $localStatus,
                    'mp_raw'        => json_encode($paymentArr),
                ];

                // Dispara ações apenas quando aprovado pela primeira vez
                if ($localStatus === 'approved' && $order->status !== 'approved') {
                    $this->sendNotificationEmail($order, $paymentArr);
                    // Gera token de agendamento e envia link ao cliente
                    $agendaLink = $this->generateAgendaToken($order);
                    $updateData['agenda_link'] = $agendaLink;
                    $this->sendClientBookingEmail($order, $agendaLink);
                }

                $orderModel->update($order->id, $updateData);

            } else {
                log_message('warning', "Webhook: order não encontrada. preference_id={$prefId} ext_ref={$extRef}");
                log_message('warning', 'Webhook payment keys: ' . implode(', ', array_keys($paymentArr ?? [])));
            }

        } catch (\Exception $e) {
            log_message('error', 'Webhook MP erro: ' . $e->getMessage());
        }

        // MP exige HTTP 200 para considerar o webhook entregue
        return $this->response->setStatusCode(200)->setBody('ok');
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Envia e-mail de notificação ao Marco quando pagamento é aprovado
    // ─────────────────────────────────────────────────────────────────────────
    private function sendNotificationEmail(object $order, array $payment): void
    {
        try {
            $adminEmail = env('ADMIN_EMAIL') ?: getenv('ADMIN_EMAIL') ?: 'contato@marcosantofoto.com.br';
            $amount     = 'R$ ' . number_format((float) $order->amount, 2, ',', '.');
            $date       = date('d/m/Y H:i');
            $mpId       = $payment['id'] ?? '—';

            $subject = "💰 Pagamento aprovado — {$order->buyer_name}";

            $message  = "<h2 style='color:#1a1a1a;font-family:sans-serif'>Novo pagamento aprovado ✅</h2>";
            $message .= "<table style='font-family:sans-serif;font-size:14px;border-collapse:collapse;width:100%;max-width:500px'>";
            $message .= "<tr><td style='padding:8px;color:#666'>Nome</td><td style='padding:8px'><strong>{$order->buyer_name}</strong></td></tr>";
            $message .= "<tr style='background:#f9f9f9'><td style='padding:8px;color:#666'>E-mail</td><td style='padding:8px'>{$order->buyer_email}</td></tr>";
            $message .= "<tr><td style='padding:8px;color:#666'>Telefone</td><td style='padding:8px'>{$order->buyer_phone}</td></tr>";
            $message .= "<tr style='background:#f9f9f9'><td style='padding:8px;color:#666'>Valor</td><td style='padding:8px'><strong style='color:#2e7d32'>{$amount}</strong></td></tr>";
            $message .= "<tr><td style='padding:8px;color:#666'>ID MercadoPago</td><td style='padding:8px'>{$mpId}</td></tr>";
            $message .= "<tr style='background:#f9f9f9'><td style='padding:8px;color:#666'>Data</td><td style='padding:8px'>{$date}</td></tr>";
            $message .= "</table>";
            $message .= "<p style='font-family:sans-serif;font-size:12px;color:#999;margin-top:24px'>Marco Santo Foto — sistema automático</p>";

            $emailService = \Config\Services::email();
            $emailService->setTo($adminEmail);
            $emailService->setSubject($subject);
            $emailService->setMessage($message);

            if (!$emailService->send()) {
                log_message('error', 'Falha ao enviar e-mail de notificação: ' . $emailService->printDebugger(['headers']));
            } else {
                log_message('info', "E-mail de notificação enviado para {$adminEmail}");
            }

        } catch (\Exception $e) {
            log_message('error', 'Erro ao enviar e-mail: ' . $e->getMessage());
        }
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Gera token de agendamento na agenda e retorna o link personalizado
    // ─────────────────────────────────────────────────────────────────────────
    private function generateAgendaToken(object $order): string
    {
        $agendaBase = rtrim(env('AGENDA_BASE_URL', 'https://agenda.marcosantofoto.com.br'), '/');
        $apiKey     = env('AGENDA_API_KEY', '');

        if (empty($apiKey)) {
            log_message('warning', '[AgendaToken] AGENDA_API_KEY não configurada. Link de agendamento não gerado.');
            return $agendaBase;
        }

        try {
            $curl = \Config\Services::curlrequest(['verify' => false, 'timeout' => 10]);
            $res  = $curl->post("{$agendaBase}/api/v1/access-tokens", [
                'headers' => [
                    'Authorization' => 'Bearer ' . $apiKey,
                    'Content-Type'  => 'application/json',
                    'Accept'        => 'application/json',
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
            if (!empty($body['link'])) {
                log_message('info', "[AgendaToken] Token gerado para order #{$order->id}: {$body['link']}");
                return $body['link'];
            }

            log_message('warning', '[AgendaToken] Resposta inesperada: ' . $res->getBody());
        } catch (\Throwable $e) {
            log_message('error', '[AgendaToken] Erro ao gerar token: ' . $e->getMessage());
        }

        return $agendaBase;
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Envia e-mail ao cliente com o link personalizado de agendamento
    // ─────────────────────────────────────────────────────────────────────────
    private function sendClientBookingEmail(object $order, string $agendaLink): void
    {
        try {
            $subject = '📸 Seu ensaio está confirmado — Agende sua data!';

            $message  = "<div style='font-family:Georgia,serif;max-width:600px;margin:0 auto;background:#0a0a0a;color:#fff;padding:40px;'>"
            . "<p style='font-size:.7rem;letter-spacing:.25em;text-transform:uppercase;color:#C5A059;margin:0 0 24px'>STUDIO MARCOSANTOFOTO</p>"
            . "<h2 style='font-family:Georgia,serif;font-size:2rem;font-weight:400;color:#fff;margin:0 0 24px;line-height:1.3'>"
            . "Olá, {$order->buyer_name}! 🎉</h2>"
            . "<p style='color:rgba(255,255,255,.7);line-height:1.8;margin:0 0 24px'>"
            . "Seu pagamento foi confirmado com sucesso. Agora é hora de escolher a data do seu ensaio fotográfico!</p>"
            . "<div style='border:1px solid rgba(197,160,89,.3);padding:24px;margin:32px 0;text-align:center;'>"
            . "<p style='font-size:.7rem;letter-spacing:.2em;text-transform:uppercase;color:rgba(197,160,89,.6);margin:0 0 12px'>LINK EXCLUSIVO DE AGENDAMENTO</p>"
            . "<a href='{$agendaLink}' style='display:inline-block;background:linear-gradient(135deg,#C5A059,#F5E27A);color:#000;text-decoration:none;padding:16px 36px;font-family:sans-serif;font-size:.75rem;font-weight:700;letter-spacing:.2em;text-transform:uppercase;margin:8px 0'>"
            . "ESCOLHER MINHA DATA →</a>"
            . "<p style='font-size:.75rem;color:rgba(255,255,255,.3);margin:12px 0 0'>Este link é válido por 90 dias e é pessoal.</p>"
            . "</div>"
            . "<p style='color:rgba(255,255,255,.5);font-size:.85rem;line-height:1.8'>"
            . "Em caso de dúvidas, responda este e-mail ou entre em contato pelo WhatsApp.</p>"
            . "<hr style='border:none;border-top:1px solid rgba(255,255,255,.08);margin:32px 0'>"
            . "<p style='font-size:.7rem;color:rgba(255,255,255,.25);text-align:center;letter-spacing:.1em'>STUDIO MARCOSANTOFOTO</p>"
            . "</div>";

            $emailService = \Config\Services::email();
            $emailService->setTo($order->buyer_email);
            $emailService->setSubject($subject);
            $emailService->setMessage($message);

            if (!$emailService->send()) {
                log_message('error', '[BookingEmail] Falha: ' . $emailService->printDebugger(['headers']));
            } else {
                log_message('info', "[BookingEmail] Email enviado para {$order->buyer_email} com link: {$agendaLink}");
            }
        } catch (\Exception $e) {
            log_message('error', '[BookingEmail] Erro: ' . $e->getMessage());
        }
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Páginas de retorno pós-pagamento (Sala de Espera)
    // ─────────────────────────────────────────────────────────────────────────
    public function thanks()
    {
        $orderId = (int) ($this->request->getGet('order') ?? 0);
        $pacote  = urldecode($this->request->getGet('pacote') ?? '');
        $nome    = urldecode($this->request->getGet('nome') ?? '');
        return view('package_thanks', [
            'orderId' => $orderId,
            'pacote'  => $pacote,
            'nome'    => $nome,
            'status'  => 'success',
        ]);
    }

    public function failure()
    {
        return view('package_thanks', ['status' => 'falha', 'pacote' => '', 'nome' => '', 'orderId' => 0]);
    }

    public function pending()
    {
        $orderId = (int) ($this->request->getGet('order') ?? 0);
        $pacote  = urldecode($this->request->getGet('pacote') ?? '');
        $nome    = urldecode($this->request->getGet('nome') ?? '');
        return view('package_thanks', [
            'orderId' => $orderId,
            'pacote'  => $pacote,
            'nome'    => $nome,
            'status'  => 'pendente',
        ]);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // AJAX endpoint: frontend polling para saber se o pagamento foi aprovado
    // GET /ensaio/status/{orderId}
    // ─────────────────────────────────────────────────────────────────────────
    public function orderStatus(int $orderId)
    {
        $orderModel = new OrderModel();
        $order = $orderModel->find($orderId);

        if (!$order) {
            return $this->response->setJSON(['status' => 'not_found']);
        }

        return $this->response->setJSON([
            'status'      => $order->status,
            'agenda_link' => $order->agenda_link ?? null,
        ]);
    }
}
