<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\ClientProjectModel;
use App\Models\PackageModel;
use App\Models\ProjectPhotoModel;
use App\Libraries\AwsS3Service;

class ClientProjectController extends BaseController
{
    protected $projectModel;
    protected $packageModel;

    public function __construct()
    {
        $this->projectModel = new ClientProjectModel();
        $this->packageModel = new PackageModel();
    }

    public function index()
    {
        // For simplicity, we just fetch all projects. In a real scenario, we'd join with users and packages to get names.
        // Let's do a raw query or loop to inject names to avoid complex joins for now.
        $projects = $this->projectModel->findAll();
        $packages = $this->packageModel->findAll();
        
        $packageMap = [];
        foreach($packages as $p) {
            $packageMap[$p->id] = $p->name;
        }

        $users = auth()->getProvider()->findAll();
        $userMap = [];
        foreach($users as $u) {
            $userMap[$u->id] = $u->username ?? $u->email ?? 'User ' . $u->id;
        }

        foreach($projects as &$proj) {
            $proj->user_name = $userMap[$proj->user_id] ?? 'Desconhecido';
            $proj->package_name = $packageMap[$proj->package_id] ?? 'Desconhecido';
        }

        $data = [
            'title'    => 'Projetos de Clientes',
            'projects' => $projects
        ];
        return view('admin/client_projects/index', $data);
    }

    public function new()
    {
        $data = [
            'title'    => 'Novo Projeto',
            'users'    => auth()->getProvider()->findAll(),
            'packages' => $this->packageModel->findAll()
        ];
        return view('admin/client_projects/form', $data);
    }

    public function create()
    {
        $data = $this->request->getPost();
        
        if ($this->projectModel->save($data)) {
            return redirect()->to('/admin/client-projects')->with('message', 'Projeto criado com sucesso.');
        }

        return redirect()->back()->withInput()->with('errors', $this->projectModel->errors());
    }

    public function edit($id = null)
    {
        $project = $this->projectModel->find($id);
        if (!$project) throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();

        $data = [
            'title'    => 'Editar Projeto',
            'project'  => $project,
            'users'    => auth()->getProvider()->findAll(),
            'packages' => $this->packageModel->findAll()
        ];
        return view('admin/client_projects/form', $data);
    }

    public function update($id = null)
    {
        $data = $this->request->getPost();
        $data['id'] = $id;

        if ($this->projectModel->save($data)) {
            return redirect()->to('/admin/client-projects')->with('message', 'Projeto atualizado com sucesso.');
        }

        return redirect()->back()->withInput()->with('errors', $this->projectModel->errors());
    }

    public function delete($id = null)
    {
        $this->projectModel->delete($id);
        return redirect()->to('/admin/client-projects')->with('message', 'Projeto removido com sucesso.');
    }

    public function photos($id)
    {
        $project = $this->projectModel->find($id);
        if (!$project) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $package    = $this->packageModel->find($project->package_id);
        $photoModel = new ProjectPhotoModel();

        // Lista TODAS as fotos do projeto, não apenas as selecionadas, para acompanhar as interações
        $photos = $photoModel->where('project_id', $id)
                             ->orderBy('original_filename', 'asc')
                             ->findAll();

        // Gera presigned URLs para exibição no admin
        $s3 = new AwsS3Service();
        foreach ($photos as &$photo) {
            $photo->presigned_url = $s3->getPresignedUrl($photo->proxy_url);
        }
        unset($photo);

        return view('admin/client_projects/photos', [
            'title'   => 'Acompanhamento — ' . esc($project->name),
            'project' => $project,
            'package' => $package,
            'photos'  => $photos,
        ]);
    }

    /**
     * Polling de acompanhamento em tempo real para o fotógrafo (AJAX GET /admin/client-projects/{id}/poll)
     */
    public function pollInteractions($id)
    {
        $project = $this->projectModel->find($id);
        if (!$project) {
            return $this->response->setJSON(['success' => false, 'message' => 'Projeto não encontrado.']);
        }

        $photoModel = new ProjectPhotoModel();
        $s3         = new AwsS3Service();

        // Força sincronização rápida com S3 para ver se novas fotos originais foram convertidas pela Lambda
        try {
            $photos = $s3->syncProjectPhotos((int)$id, $photoModel);
        } catch (\Exception $e) {
            log_message('error', 'Admin S3 Sync Error in Poll: ' . $e->getMessage());
            $photos = $photoModel->where('project_id', $id)->orderBy('original_filename', 'asc')->findAll();
            foreach ($photos as &$photo) {
                $photo->presigned_url = $s3->getPresignedUrl($photo->proxy_url);
            }
            unset($photo);
        }

        $totalPhotos   = count($photos);
        $selectedCount = 0;
        $lovedCount    = 0;
        $ratedCount    = 0;

        foreach ($photos as $photo) {
            if ($photo->status === 'selected') {
                $selectedCount++;
            }
            if ($photo->is_loved == 1) {
                $lovedCount++;
            }
            if ($photo->rating > 0) {
                $ratedCount++;
            }
        }

        return $this->response->setJSON([
            'success' => true,
            'photos'  => $photos,
            'stats'   => [
                'total'    => $totalPhotos,
                'selected' => $selectedCount,
                'loved'    => $lovedCount,
                'rated'    => $ratedCount,
            ]
        ]);
    }

    /**
     * Sincronização manual S3 → banco (POST /admin/client-projects/{id}/sync-s3)
     * Útil para o fotógrafo forçar a listagem das fotos de um projeto.
     */
    public function syncS3($id)
    {
        $project = $this->projectModel->find($id);
        if (!$project) {
            return $this->response->setJSON(['success' => false, 'message' => 'Projeto não encontrado.']);
        }

        try {
            $photoModel = new ProjectPhotoModel();
            $s3         = new AwsS3Service();
            $photos     = $s3->syncProjectPhotos((int)$id, $photoModel);

            return $this->response->setJSON([
                'success' => true,
                'message' => count($photos) . ' foto(s) sincronizadas com sucesso.',
                'count'   => count($photos),
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Admin S3 Sync Error: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Erro ao sincronizar: ' . $e->getMessage(),
            ]);
        }
    }

    /**
     * Gera e envia para download o script .bat de conexão automática do rclone para o estúdio.
     */
    public function downloadBat($id)
    {
        $project = $this->projectModel->find($id);
        if (!$project) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $bucket    = trim(env('AWS_S3_BUCKET') ?? getenv('AWS_S3_BUCKET') ?? '', '"\' ');
        $accessKey = trim(env('AWS_ACCESS_KEY_ID') ?? getenv('AWS_ACCESS_KEY_ID') ?? '', '"\' ');
        $secretKey = trim(env('AWS_SECRET_ACCESS_KEY') ?? getenv('AWS_SECRET_ACCESS_KEY') ?? '', '"\' ');
        $region    = trim(env('AWS_REGION') ?? getenv('AWS_REGION') ?? 'us-east-2', '"\' ');

        // Fallback robust para superglobais se as funções retornarem vazio
        if (empty($accessKey) || empty($secretKey)) {
            $accessKey = trim($_ENV['AWS_ACCESS_KEY_ID'] ?? $_SERVER['AWS_ACCESS_KEY_ID'] ?? '', '"\' ');
            $secretKey = trim($_ENV['AWS_SECRET_ACCESS_KEY'] ?? $_SERVER['AWS_SECRET_ACCESS_KEY'] ?? '', '"\' ');
            $bucket    = trim($_ENV['AWS_S3_BUCKET'] ?? $_SERVER['AWS_S3_BUCKET'] ?? '', '"\' ');
            $region    = trim($_ENV['AWS_REGION'] ?? $_SERVER['AWS_REGION'] ?? 'us-east-2', '"\' ');
        }

        // Limpa caracteres especiais do nome para gerar o nome do arquivo
        $cleanName = mb_ereg_replace("([^\w\s\d\-_~,;\[\]\(\).])", '', $project->name);
        $cleanName = mb_ereg_replace("([\.]{2,})", '', $cleanName);
        $cleanName = str_replace(' ', '_', $cleanName);
        $filename  = "conectar_ensaio_" . $id . "_" . strtolower($cleanName) . ".bat";

        // Nome da pasta amigavel no S3 (com fallback para o ID se for projeto antigo)
        $folder = (!empty($project->s3_folder)) ? $project->s3_folder : $id;

        // Conteúdo do BAT com CRLF do Windows
        $content  = "@echo off\r\n";
        $content .= ":: ==========================================\r\n";
        $content .= ":: SCRIPT DE CONEXAO AUTOMATICA - ESTUDIO HERO\r\n";
        $content .= ":: Ensaio: " . esc($project->name) . "\r\n";
        $content .= ":: ID do Ensaio: " . $id . "\r\n";
        $content .= ":: ==========================================\r\n";
        $content .= "title Conectar Ensaio - " . esc($project->name) . "\r\n";
        $content .= "color 06\r\n\r\n";
        $content .= "echo ========================================================\r\n";
        $content .= "echo         ESTUDIO MARCO SANTO FOTO - HERO\r\n";
        $content .= "echo ========================================================\r\n";
        $content .= "echo  Conectando ao S3 de forma 100% transparente...\r\n";
        $content .= "echo  Ensaio: " . esc($project->name) . " (ID: " . $id . ")\r\n";
        $content .= "echo  Diretorio S3: originals/" . $folder . "\r\n";
        $content .= "echo ========================================================\r\n";
        $content .= "echo.\r\n\r\n";
        $content .= ":: 1. Verificar se rclone esta no PATH\r\n";
        $content .= "where rclone >nul 2>nul\r\n";
        $content .= "if %ERRORLEVEL% equ 0 (\r\n";
        $content .= "    set RCLONE_BIN=rclone\r\n";
        $content .= "    goto :rclone_found\r\n";
        $content .= ")\r\n\r\n";
        $content .= ":: 2. Verificar em caminhos comuns\r\n";
        $content .= "if exist \"C:\\rclone\\rclone.exe\" (\r\n";
        $content .= "    set RCLONE_BIN=\"C:\\rclone\\rclone.exe\"\r\n";
        $content .= "    goto :rclone_found\r\n";
        $content .= ")\r\n\r\n";
        $content .= "if exist \"%USERPROFILE%\\rclone\\rclone.exe\" (\r\n";
        $content .= "    set RCLONE_BIN=\"%USERPROFILE%\\rclone\\rclone.exe\"\r\n";
        $content .= "    goto :rclone_found\r\n";
        $content .= ")\r\n\r\n";
        $content .= "if exist \"rclone.exe\" (\r\n";
        $content .= "    set RCLONE_BIN=\"rclone.exe\"\r\n";
        $content .= "    goto :rclone_found\r\n";
        $content .= ")\r\n\r\n";
        $content .= ":: Se nao encontrou rclone\r\n";
        $content .= "color 0C\r\n";
        $content .= "echo [ERRO] O rclone.exe nao foi localizado no seu computador.\r\n";
        $content .= "echo.\r\n";
        $content .= "echo Para que a conexao funcione:\r\n";
        $content .= "echo 1. Baixe o Rclone para Windows em: https://rclone.org/downloads/\r\n";
        $content .= "echo 2. Extraia o arquivo \"rclone.exe\" para uma pasta de sua escolha.\r\n";
        $content .= "echo 3. Recomendacao: Coloque na pasta C:\\rclone\\ ou execute este script na mesma pasta do rclone.exe.\r\n";
        $content .= "echo.\r\n";
        $content .= "echo Alem disso, certifique-se de ter o WinFsp instalado:\r\n";
        $content .= "echo https://winfsp.dev/rel/\r\n";
        $content .= "echo.\r\n";
        $content .= "pause\r\n";
        $content .= "exit\r\n\r\n";
        $content .= ":rclone_found\r\n";
        $content .= "echo [+] Executavel do Rclone encontrado: %RCLONE_BIN%\r\n";
        $content .= "echo [+] Verificando se o Disco S: ja esta em uso...\r\n";
        $content .= "if exist S:\\ (\r\n";
        $content .= "    echo [!] O Disco S: ja parece estar montado ou em uso.\r\n";
        $content .= "    echo [!] Tentando desmontar conexoes anteriores...\r\n";
        $content .= "    taskkill /f /im rclone.exe >nul 2>nul\r\n";
        $content .= "    net use S: /delete /y >nul 2>nul\r\n";
        $content .= "    subst S: /D >nul 2>nul\r\n";
        $content .= "    echo [!] Aguardando o Windows liberar o Disco S:...\r\n";
        $content .= "    timeout /t 3 /nobreak >nul\r\n";
        $content .= ")\r\n\r\n";
        $content .= "echo [+] Montando o Disco S: apontando diretamente para o ensaio...\r\n";
        $content .= "echo.\r\n";
        $content .= "echo --------------------------------------------------------\r\n";
        $content .= "echo   SUCESSO! O Disco S: foi iniciado.\r\n";
        $content .= "echo   Mantenha esta janela ABERTA durante as fotos.\r\n";
        $content .= "echo   Tethering ativo! Salve as fotos direto na unidade S:\r\n";
        $content .= "echo --------------------------------------------------------\r\n";
        $content .= "echo.\r\n\r\n";
        $content .= ":: Monta o S3 usando flags de linha de comando diretas para garantir que a sessao do WinFsp herde as credenciais\r\n";
        $content .= "%RCLONE_BIN% mount :s3:" . $bucket . "/originals/" . $folder . "/ S: --s3-provider \"AWS\" --s3-access-key-id \"" . $accessKey . "\" --s3-secret-access-key \"" . $secretKey . "\" --s3-region \"" . $region . "\" --s3-no-head --s3-no-head-object --s3-no-check-bucket --vfs-cache-mode full --network-mode=false\r\n\r\n";
        $content .= "if %ERRORLEVEL% neq 0 (\r\n";
        $content .= "    color 0C\r\n";
        $content .= "    echo.\r\n";
        $content .= "    echo [ERRO] Houve um problema ao montar o Disco S.\r\n";
        $content .= "    echo Possiveis causas:\r\n";
        $content .= "    echo 1. O WinFsp nao esta instalado. Instale de: https://winfsp.dev/rel/\r\n";
        $content .= "    echo 2. O Disco S: ja esta sendo usado por outro recurso.\r\n";
        $content .= "    echo 3. Sem conexao com a internet.\r\n";
        $content .= "    echo.\r\n";
        $content .= "    pause\r\n";
        $content .= ")\r\n";

        return $this->response->setHeader('Content-Type', 'application/octet-stream')
                                ->setHeader('Content-Disposition', 'attachment; filename="' . $filename . '"')
                                ->setHeader('Expires', '0')
                                ->setHeader('Cache-Control', 'must-revalidate, post-check=0, pre-check=0')
                                ->setHeader('Pragma', 'public')
                                ->setBody($content);
    }
}
