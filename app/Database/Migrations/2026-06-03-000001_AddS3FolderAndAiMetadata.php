<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddS3FolderAndAiMetadata extends Migration
{
    public function up()
    {
        // Adiciona s3_folder na tabela client_projects
        $this->forge->addColumn('client_projects', [
            's3_folder' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
                'after'      => 'package_id',
            ],
        ]);

        // Adiciona ai_description e ai_tags na tabela project_photos
        $this->forge->addColumn('project_photos', [
            'ai_description' => [
                'type' => 'TEXT',
                'null' => true,
                'after' => 'rating',
            ],
            'ai_tags' => [
                'type' => 'TEXT',
                'null' => true,
                'after' => 'ai_description',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('client_projects', 's3_folder');
        $this->forge->dropColumn('project_photos', ['ai_description', 'ai_tags']);
    }
}
