<?php

namespace App\Controllers\Portal;

use App\Controllers\BaseController;
use App\Libraries\AwsS3Service;

/**
 * Portal de Busca Global para usuários externos autorizados (ex: agências, fotógrafos parceiros).
 * Requer permissão search.global — concedida pelo admin na tela de Usuários.
 */
class SearchController extends BaseController
{
    public function index()
    {
        $user = auth()->user();

        if (!$user->can('search.global') && !$user->inGroup('admin', 'superadmin')) {
            return redirect()->to('/')->with('error', 'Você não tem acesso à busca global.');
        }

        $q       = trim($this->request->getGet('q') ?? '');
        $page    = (int) ($this->request->getGet('page') ?? 1);
        $perPage = 48;

        $results    = [];
        $total      = 0;
        $totalPages = 0;

        if (!empty($q)) {
            $db = \Config\Database::connect();

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
                ->select('pp.id, pp.project_id, pp.original_filename, pp.proxy_url, pp.status, pp.ai_description, pp.ai_tags, cp.name as project_name')
                ->join('client_projects cp', 'cp.id = pp.project_id')
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

        return view('portal/search/index', [
            'q'          => $q,
            'results'    => $results,
            'total'      => $total,
            'page'       => $page,
            'totalPages' => $totalPages,
        ]);
    }
}
