<?php

namespace App\Libraries;

use Aws\Rekognition\RekognitionClient;
use Aws\Exception\AwsException;

/**
 * Serviço de Reconhecimento Facial via AWS Rekognition.
 * Gerencia o cadastro e busca de rostos na coleção "jws-clients".
 */
class AwsRekognitionService
{
    protected RekognitionClient $client;
    protected string $bucket;
    protected string $collectionId = 'jws-clients';

    public function __construct()
    {
        $this->bucket = getenv('AWS_S3_BUCKET');

        $this->client = new RekognitionClient([
            'version'     => 'latest',
            'region'      => getenv('AWS_REGION') ?: 'us-east-2',
            'credentials' => [
                'key'    => getenv('AWS_ACCESS_KEY_ID'),
                'secret' => getenv('AWS_SECRET_ACCESS_KEY'),
            ],
        ]);
    }

    /**
     * Cadastra o rosto de um cliente na coleção usando uma foto do S3.
     * Remove qualquer rosto anterior desse usuário antes de indexar o novo.
     *
     * @param  string      $s3Key        Chave S3 da foto de referência (ex: proxies/slug_1/foto.jpg)
     * @param  int         $userId       ID do usuário no sistema
     * @param  string|null $oldFaceId    Face ID anterior para remover (se existir)
     * @return array ['success' => bool, 'face_id' => string|null, 'message' => string]
     */
    public function indexClientFace(string $s3Key, int $userId, ?string $oldFaceId = null): array
    {
        try {
            // Remove o rosto anterior se existir
            if ($oldFaceId) {
                $this->deleteFace($oldFaceId);
            }

            $result = $this->client->indexFaces([
                'CollectionId'        => $this->collectionId,
                'Image'               => [
                    'S3Object' => [
                        'Bucket' => $this->bucket,
                        'Name'   => $s3Key,
                    ],
                ],
                'ExternalImageId'     => "user_{$userId}",
                'DetectionAttributes' => ['DEFAULT'],
                'MaxFaces'            => 1,
                'QualityFilter'       => 'AUTO',
            ]);

            $faceRecords = $result->get('FaceRecords') ?? [];
            if (empty($faceRecords)) {
                return [
                    'success' => false,
                    'face_id' => null,
                    'message' => 'Nenhum rosto detectado na foto selecionada. Use uma foto com o rosto visível e bem iluminado.',
                ];
            }

            $faceId = $faceRecords[0]['Face']['FaceId'];
            log_message('info', "Rekognition: Rosto do usuário {$userId} cadastrado. FaceId: {$faceId}");

            return [
                'success' => true,
                'face_id' => $faceId,
                'message' => 'Rosto cadastrado com sucesso! Fotos futuras desse cliente serão reconhecidas automaticamente.',
            ];

        } catch (AwsException $e) {
            log_message('error', 'Rekognition IndexFaces Error: ' . $e->getMessage());
            return [
                'success' => false,
                'face_id' => null,
                'message' => 'Erro ao comunicar com o AWS Rekognition: ' . $e->getAwsErrorMessage(),
            ];
        }
    }

    /**
     * Remove um rosto da coleção pelo FaceId.
     */
    public function deleteFace(string $faceId): bool
    {
        try {
            $this->client->deleteFaces([
                'CollectionId' => $this->collectionId,
                'FaceIds'      => [$faceId],
            ]);
            return true;
        } catch (AwsException $e) {
            log_message('warning', 'Rekognition DeleteFaces Error: ' . $e->getMessage());
            return false;
        }
    }
}
