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

        $s3Key          = $json->s3_key;
        $aiDescription  = $json->ai_description ?? '';
        $aiTags         = $json->ai_tags ?? '';
        $faceClientId   = $json->face_client_id ?? null;
        $faceConfidence = isset($json->face_confidence) ? (float) $json->face_confidence : null;

        // Se as tags vierem como array, formata para string separada por virgula
        if (is_array($aiTags)) {
            $aiTags = implode(', ', array_map('trim', $aiTags));
        }

        // face_client_id "unknown" significa rosto detectado mas não cadastrado
        // null significa que não havia rosto na foto
        $faceClientIdInt = null;
        if (is_numeric($faceClientId)) {
            $faceClientIdInt = (int) $faceClientId;
        }

        // 3. Busca a foto pelo proxy_url (que armazena a S3 Key)
        $photoModel = new ProjectPhotoModel();
        $photo = $photoModel->where('proxy_url', $s3Key)->first();

        if (!$photo) {
            // Pode ser que o webhook chegou antes do fotógrafo abrir o painel para sincronizar.
            // Nesse caso, nós mesmos inserimos a foto no banco!
            $parts = explode('/', $s3Key);
            if (count($parts) >= 3) {
                $folderName = $parts[1];
                $filename = basename($s3Key);
                
                $projectModel = new \App\Models\ClientProjectModel();
                $project = $projectModel->where('s3_folder', $folderName)->orWhere('id', (int)$folderName)->first();
                
                if ($project) {
                    $photoModel->insert([
                        'project_id'        => $project->id,
                        'original_filename' => $filename,
                        'proxy_url'         => $s3Key,
                        'status'            => 'pending',
                        'is_loved'          => false,
                        'rating'            => 0,
                        'ai_description'    => $aiDescription,
                        'ai_tags'           => $aiTags,
                        'face_client_id'    => $faceClientIdInt,
                        'face_confidence'   => $faceConfidence,
                    ]);
                    log_message('info', "ApiController: Foto inserida pelo webhook S3 Key: {$s3Key}" . ($faceClientIdInt ? " | Cliente ID: {$faceClientIdInt}" : ''));
                    return $this->response->setJSON(['success' => true, 'message' => 'Criado e tagueado com sucesso.']);
                }
            }

            log_message('warning', "ApiController: Foto e Projeto não encontrados para a chave S3: {$s3Key}");
            return $this->response->setStatusCode(404)->setJSON([
                'success' => false,
                'message' => 'Foto não encontrada no banco de dados.',
            ]);
        }

        // 4. Atualiza os campos de IA + reconhecimento facial na foto existente
        $updateData = [
            'ai_description' => $aiDescription,
            'ai_tags'        => $aiTags,
        ];
        if ($faceClientIdInt !== null) {
            $updateData['face_client_id']  = $faceClientIdInt;
            $updateData['face_confidence'] = $faceConfidence;
        }
        $photoModel->update($photo->id, $updateData);

        log_message('info', "ApiController: Metadados salvos para a foto ID {$photo->id} (S3 Key: {$s3Key})");

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Metadados da IA salvos com sucesso.',
        ]);
    }
}
