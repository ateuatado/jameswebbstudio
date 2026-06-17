<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\OrderModel;
use App\Models\PackageModel;

class OrderController extends BaseController
{
    public function index()
    {
        $orderModel = new OrderModel();

        $status = $this->request->getGet('status') ?? '';

        $builder = $orderModel->orderBy('created_at', 'DESC');
        if ($status && in_array($status, ['pending', 'approved', 'cancelled', 'refunded'])) {
            $builder->where('status', $status);
        }

        $orders  = $builder->paginate(20);
        $pager   = $orderModel->pager;
        $summary = (new OrderModel())->summary();

        return view('admin/orders/index', [
            'orders'  => $orders,
            'pager'   => $pager,
            'summary' => $summary,
            'filter'  => $status,
            'title'   => 'Pedidos',
        ]);
    }

    public function show($id)
    {
        $orderModel = new OrderModel();
        $order = $orderModel->find($id);

        if (!$order) {
            return redirect()->to('/admin/orders')->with('error', 'Pedido não encontrado.');
        }

        $package = null;
        if ($order->package_id) {
            $package = (new PackageModel())->find($order->package_id);
        }

        return view('admin/orders/show', [
            'order'   => $order,
            'package' => $package,
            'title'   => 'Pedido #' . $id,
        ]);
    }

    // ── Teste de e-mail — acesse /admin/orders/testar-email ──────────────────
    public function testEmail()
    {
        $adminEmail = env('ADMIN_EMAIL') ?: getenv('ADMIN_EMAIL') ?: 'contato@marcosantofoto.com.br';

        $subject = '✅ Teste de e-mail — Marco Santo Foto';
        $message = "
            <h2 style='font-family:sans-serif;color:#1a1a1a'>E-mail de teste ✅</h2>
            <p style='font-family:sans-serif;font-size:14px;color:#333'>
                Se você recebeu este e-mail, o AWS SES está configurado corretamente.<br><br>
                <strong>Remetente:</strong> contato@marcosantofoto.com.br<br>
                <strong>Destinatário:</strong> {$adminEmail}<br>
                <strong>Horário:</strong> " . date('d/m/Y H:i:s') . "
            </p>
            <p style='font-family:sans-serif;font-size:12px;color:#999;margin-top:24px'>
                Marco Santo Foto — sistema automático
            </p>
        ";

        try {
            $emailService = \Config\Services::email();
            $emailService->setTo($adminEmail);
            $emailService->setSubject($subject);
            $emailService->setMessage($message);

            if ($emailService->send()) {
                $result = "<div style='font-family:sans-serif;padding:20px;background:#d4edda;color:#155724;border-radius:8px'>
                    <strong>✅ E-mail enviado com sucesso!</strong><br>
                    Verifique a caixa de entrada de <strong>{$adminEmail}</strong>
                </div>";
            } else {
                $debug  = $emailService->printDebugger(['headers', 'subject', 'body']);
                $result = "<div style='font-family:sans-serif;padding:20px;background:#f8d7da;color:#721c24;border-radius:8px'>
                    <strong>❌ Falha ao enviar e-mail.</strong><br>
                    <pre style='font-size:12px;margin-top:12px'>" . esc($debug) . "</pre>
                </div>";
            }
        } catch (\Exception $e) {
            $result = "<div style='font-family:sans-serif;padding:20px;background:#f8d7da;color:#721c24;border-radius:8px'>
                <strong>❌ Erro:</strong> " . esc($e->getMessage()) . "
            </div>";
        }

        return "<!DOCTYPE html><html><body style='padding:30px'>{$result}
            <p style='margin-top:20px'><a href='/admin/orders' style='font-family:sans-serif'>← Voltar para Pedidos</a></p>
        </body></html>";
    }

    /**
     * Salva dados contratuais do cliente (CPF, endereço, etc.)
     */
    public function updateContract($id)
    {
        $orderModel = new OrderModel();
        $order = $orderModel->find($id);
        if (!$order) return redirect()->to('/admin/orders');

        $data = [
            'cpf'            => trim($this->request->getPost('cpf')),
            'rg'             => trim($this->request->getPost('rg')),
            'marital_status' => trim($this->request->getPost('marital_status')),
            'address'        => trim($this->request->getPost('address')),
            'city'           => trim($this->request->getPost('city')),
            'state'          => trim($this->request->getPost('state')),
            'zip_code'       => trim($this->request->getPost('zip_code')),
        ];

        $orderModel->update($id, $data);
        return redirect()->to('/admin/orders/' . $id)->with('message', 'Dados contratuais salvos.');
    }

    /**
     * Gera e faz download do contrato em PDF.
     */
    public function generateContract($id)
    {
        $orderModel = new OrderModel();
        $order = $orderModel->find($id);
        if (!$order) return redirect()->to('/admin/orders');

        $generator = new \App\Libraries\ContractGenerator();
        $pdf = $generator->generate($order);

        $filename = 'Contrato-' . str_pad($id, 6, '0', STR_PAD_LEFT) . '.pdf';

        return $this->response
                     ->setHeader('Content-Type', 'application/pdf')
                     ->setHeader('Content-Disposition', 'inline; filename="' . $filename . '"')
                     ->setBody($pdf);
    }
}

