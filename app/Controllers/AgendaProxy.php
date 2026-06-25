<?php

namespace App\Controllers;

use CodeIgniter\HTTP\ResponseInterface;

/**
 * AgendaProxy
 * Encaminha requests da API da agenda no lado do servidor,
 * eliminando qualquer problema de CORS ou SSL cross-origin no browser.
 */
class AgendaProxy extends BaseController
{
    private string $agendaBase;

    public function __construct()
    {
        $this->agendaBase = rtrim(env('AGENDA_BASE_URL', 'https://agenda.test'), '/');
    }

    /**
     * GET /agenda-api/availability
     * Repassa ?year=X&month=X para a agenda.
     */
    public function availability(): ResponseInterface
    {
        $year  = $this->request->getGet('year')  ?? date('Y');
        $month = $this->request->getGet('month') ?? date('m');

        $url = "{$this->agendaBase}/api/v1/availability?year={$year}&month={$month}";

        return $this->proxyGet($url);
    }

    /**
     * POST /agenda-api/book
     * Repassa o JSON de booking para a agenda e, em caso de sucesso,
     * grava scheduled_at e agenda_booking_id no pedido correspondente.
     */
    public function book(): ResponseInterface
    {
        $rawBody  = $this->request->getBody();
        $url      = "{$this->agendaBase}/api/v1/book";
        $response = $this->proxyPost($url, $rawBody);

        // Captura a data do agendamento sem bloquear a resposta ao cliente
        $this->captureBookingDate($rawBody, $response->getBody());

        return $response;
    }

    /**
     * Extrai data e ID do booking da resposta da agenda e grava no pedido.
     * Tolerante a falhas — nunca interrompe o fluxo do cliente.
     */
    private function captureBookingDate(string $requestBody, string $responseBody): void
    {
        try {
            $req = json_decode($requestBody,  true) ?? [];
            $res = json_decode($responseBody, true) ?? [];

            // Log completo para depuração no primeiro agendamento real
            log_message('info', '[AgendaProxy] book request: '  . $requestBody);
            log_message('info', '[AgendaProxy] book response: ' . $responseBody);

            // Verifica se houve sucesso
            if (empty($res['success']) && empty($res['booking']) && empty($res['id'])) {
                return;
            }

            // Extrai dados — tenta vários formatos possíveis
            $bookingData  = $res['booking'] ?? $res['data'] ?? $res;
            $bookingId    = $bookingData['id']           ?? $bookingData['booking_id'] ?? null;
            $scheduledRaw = $bookingData['scheduled_at'] ?? $bookingData['datetime']
                          ?? $bookingData['date']        ?? $bookingData['starts_at']  ?? null;

            if (empty($scheduledRaw)) {
                log_message('warning', '[AgendaProxy] Booking ok mas sem campo de data.');
                return;
            }

            $scheduledAt = date('Y-m-d H:i:s', strtotime($scheduledRaw));

            // Identifica o pedido pelo order_id (no request ou response) ou pelo e-mail
            $orderId = $req['order_id']        ?? $bookingData['order_id']      ?? null;
            $email   = $req['email']           ?? $req['customer_email']
                     ?? $bookingData['customer_email'] ?? null;

            $orderModel = new \App\Models\OrderModel();

            if ($orderId) {
                $order = $orderModel->find((int)$orderId);
            } elseif ($email) {
                $order = $orderModel
                    ->where('buyer_email', strtolower(trim($email)))
                    ->where('status', 'approved')
                    ->orderBy('created_at', 'DESC')
                    ->first();
            } else {
                log_message('warning', '[AgendaProxy] Pedido não identificado no booking.');
                return;
            }

            if (!$order) {
                log_message('warning', "[AgendaProxy] Pedido não encontrado. order_id={$orderId} email={$email}");
                return;
            }

            $orderModel->update($order->id, [
                'scheduled_at'      => $scheduledAt,
                'agenda_booking_id' => $bookingId,
            ]);

            log_message('info', "[AgendaProxy] Ensaio agendado — order #{$order->id} em {$scheduledAt} (booking_id={$bookingId})");

        } catch (\Throwable $e) {
            log_message('error', '[AgendaProxy] Erro ao capturar booking: ' . $e->getMessage());
        }
    }

    // ------------------------------------------------------------------
    // Helpers
    // ------------------------------------------------------------------

    private function proxyGet(string $url): ResponseInterface
    {
        $curl = \Config\Services::curlrequest(['verify' => false]);

        try {
            $res  = $curl->get($url, ['headers' => ['Accept' => 'application/json']]);
            $data = $res->getBody();
        } catch (\Throwable $e) {
            log_message('error', '[AgendaProxy] GET failed: ' . $e->getMessage());
            $data = json_encode(['success' => false, 'message' => 'Agenda indisponível.', 'data' => []]);
        }

        return $this->response
            ->setStatusCode(200)
            ->setHeader('Content-Type', 'application/json')
            ->setBody($data);
    }

    private function proxyPost(string $url, string $body): ResponseInterface
    {
        $curl = \Config\Services::curlrequest(['verify' => false]);

        try {
            $res  = $curl->post($url, [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Accept'       => 'application/json',
                ],
                'body' => $body,
            ]);
            $status = $res->getStatusCode();
            $data   = $res->getBody();
        } catch (\Throwable $e) {
            log_message('error', '[AgendaProxy] POST failed: ' . $e->getMessage());
            $status = 500;
            $data   = json_encode(['success' => false, 'message' => 'Agenda indisponível.']);
        }

        return $this->response
            ->setStatusCode($status)
            ->setHeader('Content-Type', 'application/json')
            ->setBody($data);
    }
}
