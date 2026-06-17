<?php

namespace App\Controllers\Client;

use App\Controllers\BaseController;
use App\Models\ClientProjectModel;
use App\Models\ProjectPhotoModel;
use App\Models\PackageModel;
use App\Libraries\AwsS3Service;

class GaleriaController extends BaseController
{
    protected $projectModel;
    protected $photoModel;
    protected $packageModel;
    protected $s3Service;

    public function __construct()
    {
        $this->projectModel = new ClientProjectModel();
        $this->photoModel   = new ProjectPhotoModel();
        $this->packageModel = new PackageModel();
        $this->s3Service    = new AwsS3Service();
    }

    /**
     * Lista todos os projetos do cliente logado.
     */
    public function index()
    {
        $userId   = auth()->id();
        $projects = $this->projectModel->where('user_id', $userId)->findAll();

        return view('client/galeria/index', [
            'title'    => 'Minhas Galerias',
            'projects' => $projects,
        ]);
    }

    /**
     * Exibe a galeria de um projeto específico.
     * Sincroniza automaticamente com o S3 antes de renderizar.
     */
    public function view($id)
    {
        $userId  = auth()->id();
        $project = $this->projectModel->where('id', $id)->where('user_id', $userId)->first();

        if (!$project) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $package = $this->packageModel->find($project->package_id);

        // Sincroniza fotos do S3 (proxies) → banco de dados
        // Só sincroniza se o projeto estiver aberto ou em seleção
        $photos = [];
        $syncError = false;

        if (in_array($project->status, ['open', 'selecting'])) {
            try {
                $photos = $this->s3Service->syncProjectPhotos((int)$id, $this->photoModel);
            } catch (\Exception $e) {
                log_message('error', 'Erro ao sincronizar S3: ' . $e->getMessage());
                $syncError = true;
                // Fallback: carrega do banco sem presigned URLs
                $photos = $this->photoModel->where('project_id', $id)->orderBy('original_filename', 'asc')->findAll();
            }
        } else {
            // Projeto já finalizado: apenas carrega do banco
            $photos = $this->photoModel->where('project_id', $id)->orderBy('original_filename', 'asc')->findAll();
            foreach ($photos as &$photo) {
                $photo->presigned_url = $this->s3Service->getPresignedUrl($photo->proxy_url);
            }
            unset($photo);
        }

        return view('client/galeria/view', [
            'title'     => 'Galeria — Projeto #' . $project->id,
            'project'   => $project,
            'package'   => $package,
            'photos'    => $photos,
            'syncError' => $syncError,
        ]);
    }

    /**
     * Salva a seleção de fotos do cliente (AJAX).
     */
    public function saveSelection($id)
    {
        $userId  = auth()->id();
        $project = $this->projectModel->where('id', $id)->where('user_id', $userId)->first();

        if (!$project || !in_array($project->status, ['open', 'selecting'])) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Projeto inválido ou já finalizado.',
            ]);
        }

        // Marca projeto como "em seleção" na primeira vez que o cliente interage
        if ($project->status === 'open') {
            $this->projectModel->update($id, ['status' => 'selecting']);
        }

        $selectedIds = $this->request->getJSON()->selected_photos ?? [];

        // Reseta seleções anteriores
        $this->photoModel->where('project_id', $id)->set(['status' => 'pending'])->update();

        // Aplica novas seleções
        if (!empty($selectedIds)) {
            $this->photoModel->whereIn('id', $selectedIds)->set(['status' => 'selected'])->update();
        }

        return $this->response->setJSON(['success' => true]);
    }

    /**
     * Polling de fotos em tempo real (AJAX GET /client/galeria/{id}/poll)
     */
    public function pollPhotos($id)
    {
        $userId  = auth()->id();
        $project = $this->projectModel->where('id', $id)->where('user_id', $userId)->first();

        if (!$project) {
            return $this->response->setJSON(['success' => false, 'message' => 'Projeto não encontrado.']);
        }

        $photos = [];
        if (in_array($project->status, ['open', 'selecting'])) {
            try {
                // Sincroniza em tempo real com o S3
                $photos = $this->s3Service->syncProjectPhotos((int)$id, $this->photoModel);
            } catch (\Exception $e) {
                log_message('error', 'Erro ao sincronizar S3 no Poll: ' . $e->getMessage());
                $photos = $this->photoModel->where('project_id', $id)->orderBy('original_filename', 'asc')->findAll();
                foreach ($photos as &$photo) {
                    $photo->presigned_url = $this->s3Service->getPresignedUrl($photo->proxy_url);
                }
                unset($photo);
            }
        } else {
            // Se já finalizado, apenas carrega
            $photos = $this->photoModel->where('project_id', $id)->orderBy('original_filename', 'asc')->findAll();
            foreach ($photos as &$photo) {
                $photo->presigned_url = $this->s3Service->getPresignedUrl($photo->proxy_url);
            }
            unset($photo);
        }

        return $this->response->setJSON([
            'success' => true,
            'photos'  => $photos,
            'status'  => $project->status,
        ]);
    }

    /**
     * AJAX POST /client/galeria/{projectId}/photo/{photoId}/status
     */
    public function togglePhotoStatus($projectId, $photoId)
    {
        $userId  = auth()->id();
        $project = $this->projectModel->where('id', $projectId)->where('user_id', $userId)->first();

        if (!$project || !in_array($project->status, ['open', 'selecting'])) {
            return $this->response->setJSON(['success' => false, 'message' => 'Projeto inválido ou finalizado.']);
        }

        $photo = $this->photoModel->where('id', $photoId)->where('project_id', $projectId)->first();
        if (!$photo) {
            return $this->response->setJSON(['success' => false, 'message' => 'Foto não encontrada.']);
        }

        // Transiciona status do projeto para 'selecting'
        if ($project->status === 'open') {
            $this->projectModel->update($projectId, ['status' => 'selecting']);
        }

        $newStatus = $photo->status === 'selected' ? 'pending' : 'selected';
        $this->photoModel->update($photoId, ['status' => $newStatus]);

        return $this->response->setJSON([
            'success' => true,
            'status'  => $newStatus,
        ]);
    }

    /**
     * AJAX POST /client/galeria/{projectId}/photo/{photoId}/love
     */
    public function togglePhotoLove($projectId, $photoId)
    {
        $userId  = auth()->id();
        $project = $this->projectModel->where('id', $projectId)->where('user_id', $userId)->first();

        if (!$project || !in_array($project->status, ['open', 'selecting'])) {
            return $this->response->setJSON(['success' => false, 'message' => 'Projeto inválido ou finalizado.']);
        }

        $photo = $this->photoModel->where('id', $photoId)->where('project_id', $projectId)->first();
        if (!$photo) {
            return $this->response->setJSON(['success' => false, 'message' => 'Foto não encontrada.']);
        }

        // Transiciona status do projeto para 'selecting'
        if ($project->status === 'open') {
            $this->projectModel->update($projectId, ['status' => 'selecting']);
        }

        $newLove = $photo->is_loved == 1 ? 0 : 1;
        $this->photoModel->update($photoId, ['is_loved' => $newLove]);

        return $this->response->setJSON([
            'success' => true,
            'is_loved'=> $newLove,
        ]);
    }

    /**
     * AJAX POST /client/galeria/{projectId}/photo/{photoId}/rate
     */
    public function ratePhoto($projectId, $photoId)
    {
        $userId  = auth()->id();
        $project = $this->projectModel->where('id', $projectId)->where('user_id', $userId)->first();

        if (!$project || !in_array($project->status, ['open', 'selecting'])) {
            return $this->response->setJSON(['success' => false, 'message' => 'Projeto inválido ou finalizado.']);
        }

        $photo = $this->photoModel->where('id', $photoId)->where('project_id', $projectId)->first();
        if (!$photo) {
            return $this->response->setJSON(['success' => false, 'message' => 'Foto não encontrada.']);
        }

        // Transiciona status do projeto para 'selecting'
        if ($project->status === 'open') {
            $this->projectModel->update($projectId, ['status' => 'selecting']);
        }

        $rating = (int) ($this->request->getJSON()->rating ?? 0);
        if ($rating < 0 || $rating > 5) {
            return $this->response->setJSON(['success' => false, 'message' => 'Classificação inválida.']);
        }

        $this->photoModel->update($photoId, ['rating' => $rating]);

        return $this->response->setJSON([
            'success' => true,
            'rating'  => $rating,
        ]);
    }

    /**
     * Checkout: calcula extras e redireciona para MercadoPago.
     */
    public function checkout($id)
    {
        $userId  = auth()->id();
        $project = $this->projectModel->where('id', $id)->where('user_id', $userId)->first();

        if (!$project) {
            return redirect()->to('/client/galeria')->with('errors', ['Projeto não encontrado.']);
        }

        $package            = $this->packageModel->find($project->package_id);
        $selectedCount      = $this->photoModel->where('project_id', $id)->where('status', 'selected')->countAllResults();
        $extraPhotos        = max(0, $selectedCount - $package->included_photos);

        if ($extraPhotos === 0) {
            $this->projectModel->update($id, ['status' => 'completed']);
            return redirect()->to("/client/galeria/{$id}")
                ->with('message', 'Seleção finalizada com sucesso! Nenhuma cobrança extra.');
        }

        $totalExtraCost = $extraPhotos * $package->extra_photo_price;

        // Integração MercadoPago
        \MercadoPago\MercadoPagoConfig::setAccessToken(getenv('MERCADOPAGO_ACCESS_TOKEN'));

        $client = new \MercadoPago\Client\Preference\PreferenceClient();

        $item             = new \MercadoPago\Resources\Preference\Item();
        $item->title      = "Fotos Extras — Projeto #{$project->id}";
        $item->quantity   = $extraPhotos;
        $item->unit_price = (float) $package->extra_photo_price;

        $preferenceData = [
            'items'      => [$item],
            'back_urls'  => [
                'success' => site_url("client/galeria/{$id}?status=success"),
                'failure' => site_url("client/galeria/{$id}?status=failure"),
                'pending' => site_url("client/galeria/{$id}?status=pending"),
            ],
            'auto_return'        => 'approved',
            'external_reference' => 'PROJ_' . $project->id,
        ];

        try {
            $preference = $client->create($preferenceData);
            return redirect()->to($preference->init_point);
        } catch (\Exception $e) {
            log_message('error', 'Erro no MercadoPago: ' . $e->getMessage());
            return redirect()->back()->with('errors', ['Erro ao conectar com o MercadoPago. Tente novamente.']);
        }
    }
}
