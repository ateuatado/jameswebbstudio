<?php

namespace App\Libraries;

use Aws\S3\S3Client;
use Aws\Exception\AwsException;

class AwsS3Service
{
    protected $client;
    protected $bucket;

    public function __construct()
    {
        $this->bucket = getenv('AWS_S3_BUCKET');

        $this->client = new S3Client([
            'version'     => 'latest',
            'region'      => getenv('AWS_REGION') ?: 'us-east-1',
            'credentials' => [
                'key'    => getenv('AWS_ACCESS_KEY_ID'),
                'secret' => getenv('AWS_SECRET_ACCESS_KEY'),
            ],
        ]);
    }

    /**
     * Gera uma presigned URL para leitura (exibição no browser)
     */
    public function getPresignedUrl(string $key, int $minutes = 120): ?string
    {
        try {
            $cmd     = $this->client->getCommand('GetObject', [
                'Bucket' => $this->bucket,
                'Key'    => $key,
            ]);
            $request = $this->client->createPresignedRequest($cmd, "+{$minutes} minutes");
            return (string) $request->getUri();
        } catch (AwsException $e) {
            log_message('error', 'S3 Presigned URL Error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Gera uma presigned URL para upload direto do browser (PUT)
     */
    public function getPresignedUploadUrl(string $key, int $minutes = 60): ?string
    {
        try {
            $cmd     = $this->client->getCommand('PutObject', [
                'Bucket' => $this->bucket,
                'Key'    => $key,
            ]);
            $request = $this->client->createPresignedRequest($cmd, "+{$minutes} minutes");
            return (string) $request->getUri();
        } catch (AwsException $e) {
            log_message('error', 'S3 Upload URL Error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Lista todos os arquivos de um prefixo (pasta) no bucket.
     * Retorna array de S3 keys.
     */
    public function listFiles(string $prefix): array
    {
        try {
            $paginator = $this->client->getPaginator('ListObjectsV2', [
                'Bucket' => $this->bucket,
                'Prefix' => $prefix,
            ]);

            $files = [];
            foreach ($paginator as $result) {
                foreach ($result['Contents'] ?? [] as $object) {
                    // Ignora entradas que representam apenas a "pasta"
                    if ($object['Key'] !== $prefix && $object['Key'] !== rtrim($prefix, '/') . '/') {
                        $files[] = $object['Key'];
                    }
                }
            }
            return $files;
        } catch (AwsException $e) {
            log_message('error', 'S3 List Files Error: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Sincroniza as fotos proxy do S3 com a tabela project_photos do banco.
     *
     * Fluxo:
     *   1. Lista todos os arquivos em proxies/{projectId}/
     *   2. Para cada arquivo no S3 que NÃO existe no banco → insere (status: pending)
     *   3. Retorna a lista atualizada de fotos (com presigned URLs) diretamente
     *
     * @param  int                    $projectId
     * @param  \App\Models\ProjectPhotoModel $photoModel
     * @param  int                    $urlMinutes  Validade das presigned URLs em minutos
     * @return array                  Lista de arrays com os dados das fotos + presigned_url
     */
    public function syncProjectPhotos(int $projectId, \App\Models\ProjectPhotoModel $photoModel, int $urlMinutes = 120): array
    {
        // Busca a pasta amigavel no S3 (s3_folder) no banco de dados
        $db = \Config\Database::connect();
        $project = $db->table('client_projects')->where('id', $projectId)->get()->getRow();
        
        // Mantem compatibilidade com projetos antigos: se nao houver s3_folder, usa o ID
        $folder = (!empty($project->s3_folder)) ? $project->s3_folder : $projectId;

        $prefix  = "proxies/{$folder}/";
        $s3Keys  = $this->listFiles($prefix);

        // Fotos já registradas no banco (indexadas por original_filename)
        $existing = $photoModel->where('project_id', $projectId)->findAll();
        $existingByFilename = [];
        foreach ($existing as $photo) {
            $existingByFilename[$photo->original_filename] = $photo;
        }

        // Insere no banco as fotos que estão no S3 mas ainda não foram registradas
        foreach ($s3Keys as $key) {
            $filename = basename($key);
            if (!isset($existingByFilename[$filename])) {
                $photoModel->insert([
                    'project_id'        => $projectId,
                    'original_filename' => $filename,
                    'proxy_url'         => $key,   // Guardamos a S3 key, não a URL assinada
                    'status'            => 'pending',
                ]);
            }
        }

        // Busca lista atualizada e gera presigned URLs para cada foto
        $photos = $photoModel->where('project_id', $projectId)->orderBy('original_filename', 'asc')->findAll();

        foreach ($photos as &$photo) {
            $photo->presigned_url = $this->getPresignedUrl($photo->proxy_url, $urlMinutes);
        }
        unset($photo);

        return $photos;
    }

    /**
     * Verifica se um objeto existe no bucket.
     */
    public function exists(string $key): bool
    {
        try {
            $this->client->headObject([
                'Bucket' => $this->bucket,
                'Key'    => $key,
            ]);
            return true;
        } catch (AwsException $e) {
            return false;
        }
    }

    /**
     * Deleta um objeto do bucket.
     */
    public function delete(string $key): bool
    {
        try {
            $this->client->deleteObject([
                'Bucket' => $this->bucket,
                'Key'    => $key,
            ]);
            return true;
        } catch (AwsException $e) {
            log_message('error', 'S3 Delete Error: ' . $e->getMessage());
            return false;
        }
    }
}
