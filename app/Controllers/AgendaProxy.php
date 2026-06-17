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
     * Repassa o JSON de booking para a agenda.
     */
    public function book(): ResponseInterface
    {
        $body = $this->request->getBody();
        $url  = "{$this->agendaBase}/api/v1/book";

        return $this->proxyPost($url, $body);
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
