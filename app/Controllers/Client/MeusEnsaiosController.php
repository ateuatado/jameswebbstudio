<?php

namespace App\Controllers\Client;

use App\Controllers\BaseController;
use App\Models\ClientProjectModel;
use App\Models\OrderModel;
use App\Models\PackageModel;

class MeusEnsaiosController extends BaseController
{
    /**
     * Dashboard unificado do cliente: compras + galerias.
     */
    public function index()
    {
        $user    = auth()->user();
        $userId  = auth()->id();
        $email   = $user->email;

        // ── Compras / Agendamentos (orders aprovadas do e-mail do usuário) ──
        $orderModel = new OrderModel();
        $orders = $orderModel
            ->where('buyer_email', $email)
            ->whereIn('status', ['approved', 'pending'])
            ->orderBy('created_at', 'DESC')
            ->findAll();

        // Carrega nomes dos pacotes
        $packageModel = new PackageModel();
        foreach ($orders as &$order) {
            $order->package = $order->package_id ? $packageModel->find($order->package_id) : null;
        }
        unset($order);

        // ── Galerias de fotos (projetos vinculados ao user_id) ──
        $projectModel = new ClientProjectModel();
        $projects = $projectModel->where('user_id', $userId)->findAll();

        foreach ($projects as &$proj) {
            $proj->package = $proj->package_id ? $packageModel->find($proj->package_id) : null;
        }
        unset($proj);

        return view('client/meus_ensaios', [
            'title'    => 'Meus Ensaios',
            'orders'   => $orders,
            'projects' => $projects,
        ]);
    }

    /**
     * Gera e faz download do Guia Pré-Ensaio personalizado.
     */
    public function downloadGuide($orderId)
    {
        $user  = auth()->user();
        $email = $user->email;

        $orderModel = new OrderModel();
        $order = $orderModel->find($orderId);

        if (!$order || $order->buyer_email !== $email) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        // Descobre a categoria do pacote
        $categoryId = null;
        if ($order->package_id) {
            $pkg = (new PackageModel())->find($order->package_id);
            $categoryId = $pkg ? ($pkg->category_id ?? null) : null;
        }

        $shootDate = $order->created_at
            ? date('d/m/Y', strtotime($order->created_at))
            : date('d/m/Y');

        $generator = new \App\Libraries\GuideGenerator();
        $pdf = $generator->generate(
            $order->buyer_name,
            $order->buyer_email,
            $categoryId,
            $shootDate
        );

        $filename = 'Guia-Pre-Ensaio-' . url_title($order->buyer_name, '-', true) . '.pdf';

        return $this->response
                     ->setHeader('Content-Type', 'application/pdf')
                     ->setHeader('Content-Disposition', 'attachment; filename="' . $filename . '"')
                     ->setBody($pdf);
    }

    /**
     * Gera e faz download do contrato personalizado.
     */
    public function downloadContract($orderId)
    {
        $user  = auth()->user();
        $email = $user->email;

        $orderModel = new OrderModel();
        $order = $orderModel->find($orderId);

        if (!$order || $order->buyer_email !== $email) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $generator = new \App\Libraries\ContractGenerator();
        $pdf = $generator->generate($order);

        $filename = 'Contrato-' . str_pad($orderId, 6, '0', STR_PAD_LEFT) . '.pdf';

        return $this->response
                     ->setHeader('Content-Type', 'application/pdf')
                     ->setHeader('Content-Disposition', 'inline; filename="' . $filename . '"')
                     ->setBody($pdf);
    }
}
