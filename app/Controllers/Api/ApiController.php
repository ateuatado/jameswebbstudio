<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\ProjectPhotoModel;

class ApiController extends BaseController
{
    /**
     * Recebe metadados (tags e descricao da IA) e atualiza a foto correspondente.
     */
    public function saveMetadata()
    {
        // 1. Valida o Token de Seguranca
        $token = $this->request->getHeaderLine('X-Hero-Token');
        $expectedToken = getenv('HERO_API_TOKEN') ?: env('HERO_API_TOKEN');

        if (empty($token) || empty($expectedToken) || $token !== $expectedToken) {
            return $this->response->setStatusCode(401)->setJSON([
                'success' => false,
                'message' => 'Não autorizado.',
            ]);
        }

        // 2. Obtem e valida o JSON enviado
        $json = $this->request->getJSON();
        if (!$json || empty($json->s3_key)) {
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'message' => 'Dados inválidos. s3_key é obrigatório.',
            ]);
        }

        $s3Key         = $json->s3_key;
        $aiDescription = $json->ai_description ?? '';
        $aiTags        = $json->ai_tags ?? '';

        // Se as tags vierem como array, formata para string separada por virgula
        if (is_array($aiTags)) {
            $aiTags = implode(', ', array_map('trim', $aiTags));
        }

        // 3. Busca a foto pelo proxy_url (que armazena a S3 Key)
        $photoModel = new ProjectPhotoModel();
        $photo = $photoModel->where('proxy_url', $s3Key)->first();

        if (!$photo) {
            // Tenta também buscar removendo ou adicionando proxies/ se necessário, mas o correto e ser exato
            // Vamos logar para debug se nao encontrar
            log_message('warning', "ApiController: Foto não encontrada para a chave S3: {$s3Key}");
            return $this->response->setStatusCode(404)->setJSON([
                'success' => false,
                'message' => 'Foto não encontrada no banco de dados.',
            ]);
        }

        // 4. Atualiza os campos de IA na foto
        $photoModel->update($photo->id, [
            'ai_description' => $aiDescription,
            'ai_tags'        => $aiTags,
        ]);

        log_message('info', "ApiController: Metadados salvos para a foto ID {$photo->id} (S3 Key: {$s3Key})");

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Metadados da IA salvos com sucesso.',
        ]);
    }
}
