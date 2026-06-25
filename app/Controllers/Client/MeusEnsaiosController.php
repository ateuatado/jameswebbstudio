<?php

namespace App\Controllers\Client;

use App\Controllers\BaseController;
use App\Models\ClientProjectModel;
use App\Models\OrderModel;
use App\Models\PackageModel;
use App\Libraries\AwsS3Service;

class MeusEnsaiosController extends BaseController
{
    /**
     * Dashboard unificado do cliente: abas Ensaios, Galeria, Busca, Perfil.
     */
    public function index()
    {
        // Guard de segurança: garante usuário autenticado (defesa em profundidade além do filtro de rota)
        if (!auth()->loggedIn()) {
            return redirect()->to(site_url('login'));
        }

        $user    = auth()->user();
        $userId  = auth()->id();
        $email   = $user->email;
        $tab     = $this->request->getGet('tab') ?? 'ensaios';

        // ── Compras / Agendamentos ──
        // Filtra por client_user_id (pedidos vinculados a esta conta).
        // Fallback: buyer_email para pedidos antigos que ainda não têm client_user_id.
        $orderModel = new OrderModel();
        $orders = $orderModel
            ->groupStart()
                ->where('client_user_id', $userId)
                ->orGroupStart()
                    ->where('buyer_email', $email)
                    ->where('client_user_id IS NULL', null, false)
                ->groupEnd()
            ->groupEnd()
            ->whereIn('status', ['approved', 'pending'])
            ->orderBy('created_at', 'DESC')
            ->findAll();

        $packageModel = new PackageModel();
        $db = \Config\Database::connect();
        foreach ($orders as &$order) {
            $order->package = $order->package_id ? $packageModel->find($order->package_id) : null;
            // Carrega código do cupom se houver
            $order->coupon_code = null;
            if ($order->coupon_id) {
                $cpRow = $db->table('coupons')->select('code')->where('id', $order->coupon_id)->get()->getRow();
                $order->coupon_code = $cpRow ? $cpRow->code : null;
            }
            // Calcula preço original (antes do desconto)
            // Guarda contra divisão por zero quando discount_percent = 100 (cupom cortesia)
            if ($order->discount_percent > 0 && $order->discount_percent < 100 && $order->package) {
                $order->original_price = round($order->amount / (1 - $order->discount_percent / 100), 2);
            } elseif ($order->discount_percent >= 100 && $order->package) {
                // Cupom 100% — preço original vem direto do pacote
                $order->original_price = (float) ($order->package->price ?? 0) ?: null;
            } else {
                $order->original_price = null;
            }

        }
        unset($order);

        // ── Projetos do cliente (Galeria) ──
        $projectModel = new ClientProjectModel();
        $projects = $projectModel->where('user_id', $userId)->orderBy('created_at', 'DESC')->findAll();

        foreach ($projects as &$proj) {
            $proj->package = $proj->package_id ? $packageModel->find($proj->package_id) : null;
            // Conta fotos do projeto
            $db = \Config\Database::connect();
            $proj->photo_count = $db->table('project_photos')
                ->where('project_id', $proj->id)
                ->countAllResults();
        }
        unset($proj);

        // ── Perfil ──
        $db    = \Config\Database::connect();
        $extra = $db->table('users')
            ->select('display_name, nicknames, nome_completo, cpf, rg, endereco_cep, endereco_logradouro, endereco_numero, endereco_complemento, endereco_cidade, endereco_estado')
            ->where('id', $userId)
            ->get()->getRow();

        return view('client/meus_ensaios', [
            'title'               => 'Meus Ensaios',
            'tab'                 => $tab,
            'orders'              => $orders,
            'projects'            => $projects,
            'displayName'         => $extra->display_name        ?? '',
            'nicknames'           => $extra->nicknames            ?? '',
            'nomeCompleto'        => $extra->nome_completo        ?? '',
            'cpf'                 => $extra->cpf                  ?? '',
            'rg'                  => $extra->rg                   ?? '',
            'enderecoCep'         => $extra->endereco_cep         ?? '',
            'enderecoLogradouro'  => $extra->endereco_logradouro  ?? '',
            'enderecoNumero'      => $extra->endereco_numero      ?? '',
            'enderecoComplemento' => $extra->endereco_complemento ?? '',
            'enderecoCidade'      => $extra->endereco_cidade      ?? '',
            'enderecoEstado'      => $extra->endereco_estado      ?? '',
        ]);
    }

    /**
     * Página de agendamento do cliente para um pedido específico.
     * GET /client/agendar/{orderId}
     */
    public function agendar(int $orderId)
    {
        $user  = auth()->user();
        $email = $user->email;

        $orderModel = new OrderModel();
        $order = $orderModel->find($orderId);

        // Verifica que o pedido existe, pertence ao cliente e está aprovado
        if (!$order || strtolower($order->buyer_email) !== strtolower($email) || $order->status !== 'approved') {
            return redirect()->to(site_url('client/meus-ensaios'))->with('error', 'Pedido não encontrado.');
        }

        $packageModel = new PackageModel();
        $package = $order->package_id ? $packageModel->find($order->package_id) : null;

        return view('client/agendar', [
            'title'              => 'Agendar Ensaio',
            'agendaPrefillName'  => $order->buyer_name,
            'agendaPrefillEmail' => $order->buyer_email,
            'agendaPrefillPhone' => $order->buyer_phone ?? '',
            'agendaOrderId'      => $order->id,
            'packageName'        => $package ? $package->name : 'Ensaio Fotográfico',
        ]);
    }

    /**
     * Salva os dados de perfil/contrato do cliente logado.
     * POST /client/perfil/salvar
     */
    public function updatePerfil()
    {
        $userId = auth()->id();

        $db = \Config\Database::connect();
        $db->table('users')->where('id', $userId)->update([
            'display_name'         => trim($this->request->getPost('display_name')         ?? ''),
            'nicknames'            => trim($this->request->getPost('nicknames')            ?? ''),
            'nome_completo'        => trim($this->request->getPost('nome_completo')        ?? ''),
            'cpf'                  => trim($this->request->getPost('cpf')                  ?? ''),
            'rg'                   => trim($this->request->getPost('rg')                   ?? ''),
            'endereco_cep'         => trim($this->request->getPost('endereco_cep')         ?? ''),
            'endereco_logradouro'  => trim($this->request->getPost('endereco_logradouro')  ?? ''),
            'endereco_numero'      => trim($this->request->getPost('endereco_numero')      ?? ''),
            'endereco_complemento' => trim($this->request->getPost('endereco_complemento') ?? ''),
            'endereco_cidade'      => trim($this->request->getPost('endereco_cidade')      ?? ''),
            'endereco_estado'      => trim($this->request->getPost('endereco_estado')      ?? ''),
        ]);

        return redirect()->to(site_url('client/meus-ensaios?tab=perfil'))
                         ->with('perfil_ok', true);
    }

    /**
     * Busca de fotos restrita aos projetos do cliente logado.
     * GET /client/buscar?q=sorrindo&page=1
     */
    public function buscar()
    {
        $userId  = auth()->id();
        $q       = trim($this->request->getGet('q') ?? '');
        $page    = max(1, (int) ($this->request->getGet('page') ?? 1));
        $perPage = 36;

        $results    = [];
        $total      = 0;
        $totalPages = 0;

        // IDs dos projetos do cliente
        $db         = \Config\Database::connect();
        $projectIds = $db->table('client_projects')
            ->select('id')
            ->where('user_id', $userId)
            ->get()
            ->getResultArray();
        $projectIds = array_column($projectIds, 'id');

        if (!empty($q) && !empty($projectIds)) {
            $total = $db->table('project_photos pp')
                ->join('client_projects cp', 'cp.id = pp.project_id')
                ->whereIn('pp.project_id', $projectIds)
                ->groupStart()
                    ->like('pp.ai_tags', $q)
                    ->orLike('pp.ai_description', $q)
                    ->orLike('pp.original_filename', $q)
                ->groupEnd()
                ->countAllResults();

            $totalPages = max(1, (int) ceil($total / $perPage));
            $offset     = ($page - 1) * $perPage;

            $rows = $db->table('project_photos pp')
                ->select('pp.id, pp.project_id, pp.original_filename, pp.proxy_url, pp.ai_tags, pp.ai_description, cp.name as project_name')
                ->join('client_projects cp', 'cp.id = pp.project_id')
                ->whereIn('pp.project_id', $projectIds)
                ->groupStart()
                    ->like('pp.ai_tags', $q)
                    ->orLike('pp.ai_description', $q)
                    ->orLike('pp.original_filename', $q)
                ->groupEnd()
                ->orderBy('cp.name', 'ASC')
                ->limit($perPage, $offset)
                ->get()
                ->getResultObject();

            $s3 = new AwsS3Service();
            foreach ($rows as &$row) {
                $row->presigned_url = $s3->getPresignedUrl($row->proxy_url);
            }
            unset($row);
            $results = $rows;
        }

        return $this->response->setJSON([
            'q'          => $q,
            'results'    => $results,
            'total'      => $total,
            'page'       => $page,
            'totalPages' => $totalPages,
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
