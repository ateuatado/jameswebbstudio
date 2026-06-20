<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Libraries\AwsS3Service;

class PhotoSearchController extends BaseController
{
    /**
     * Busca global de fotos por tag/descrição IA em TODOS os ensaios.
     * Acesso: admin/superadmin sempre; outros usuários somente se tiverem a permissão search.global
     */
    public function index()
    {
        $user = auth()->user();

        // Verifica permissão: admin/superadmin sempre passa, demais precisam de search.global
        if (!$user->inGroup('admin', 'superadmin') && !$user->can('search.global')) {
            return redirect()->to('/admin')->with('error', 'Você não tem permissão para acessar a Busca Global.');
        }

        $q       = trim($this->request->getGet('q') ?? '');
        $page    = (int) ($this->request->getGet('page') ?? 1);
        $perPage = 48;

        $results    = [];
        $total      = 0;
        $totalPages = 0;

        if (!empty($q)) {
            $db = \Config\Database::connect();

            $like = '%' . $q . '%';

            // Conta total para paginação
            $total = $db->table('project_photos pp')
                ->join('client_projects cp', 'cp.id = pp.project_id')
                ->groupStart()
                    ->like('pp.ai_tags', $q)
                    ->orLike('pp.ai_description', $q)
                    ->orLike('pp.original_filename', $q)
                ->groupEnd()
                ->countAllResults();

            $totalPages = max(1, (int) ceil($total / $perPage));
            $offset     = ($page - 1) * $perPage;

            $rows = $db->table('project_photos pp')
                ->select('pp.id, pp.project_id, pp.original_filename, pp.proxy_url, pp.status, pp.is_loved, pp.rating, pp.ai_description, pp.ai_tags, cp.name as project_name, cp.s3_folder')
                ->join('client_projects cp', 'cp.id = pp.project_id')
                ->groupStart()
                    ->like('pp.ai_tags', $q)
                    ->orLike('pp.ai_description', $q)
                    ->orLike('pp.original_filename', $q)
                ->groupEnd()
                ->orderBy('cp.name', 'ASC')
                ->orderBy('pp.original_filename', 'ASC')
                ->limit($perPage, $offset)
                ->get()
                ->getResultObject();

            // Gera presigned URLs
            $s3 = new AwsS3Service();
            foreach ($rows as &$row) {
                $row->presigned_url = $s3->getPresignedUrl($row->proxy_url);
            }
            unset($row);

            $results = $rows;
        }

        return view('admin/photo_search/index', [
            'title'      => 'Busca Global de Fotos',
            'q'          => $q,
            'results'    => $results,
            'total'      => $total,
            'page'       => $page,
            'totalPages' => $totalPages,
            'perPage'    => $perPage,
        ]);
    }
}
