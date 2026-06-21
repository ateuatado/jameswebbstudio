<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddFaceRecognitionFields extends Migration
{
    public function up(): void
    {
        // Colunas na tabela project_photos: cliente identificado e confiança
        $this->forge->addColumn('project_photos', [
            'face_client_id' => [
                'type'    => 'INT',
                'null'    => true,
                'default' => null,
                'after'   => 'ai_tags',
                'comment' => 'ID do usuario (cliente) identificado pelo AWS Rekognition',
            ],
            'face_confidence' => [
                'type'       => 'DECIMAL',
                'constraint' => '5,2',
                'null'       => true,
                'default'    => null,
                'after'      => 'face_client_id',
                'comment'    => 'Percentual de confianca da identificacao (0-100)',
            ],
        ]);

        // Colunas na tabela users: armazena o face_id do Rekognition e URL da foto de referencia
        $this->forge->addColumn('users', [
            'rekognition_face_id' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
                'default'    => null,
                'after'      => 'username',
                'comment'    => 'Face ID gerado pelo AWS Rekognition ao cadastrar o rosto do cliente',
            ],
            'reference_photo_url' => [
                'type'       => 'VARCHAR',
                'constraint' => 500,
                'null'       => true,
                'default'    => null,
                'after'      => 'rekognition_face_id',
                'comment'    => 'URL da foto de referencia do cliente armazenada no S3',
            ],
        ]);
    }

    public function down(): void
    {
        $this->forge->dropColumn('project_photos', ['face_client_id', 'face_confidence']);
        $this->forge->dropColumn('users', ['rekognition_face_id', 'reference_photo_url']);
    }
}
