<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddFieldsToProjectsAndPhotos extends Migration
{
    public function up()
    {
        // 1. Adiciona coluna 'name' na tabela 'client_projects'
        $this->forge->addColumn('client_projects', [
            'name' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => false,
                'after'      => 'id', // Tenta posicionar após o ID
            ],
        ]);

        // 2. Adiciona colunas 'is_loved' e 'rating' na tabela 'project_photos'
        $this->forge->addColumn('project_photos', [
            'is_loved' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'unsigned'   => true,
                'default'    => 0,
                'null'       => false,
                'after'      => 'status',
            ],
            'rating' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'unsigned'   => true,
                'default'    => 0,
                'null'       => false,
                'after'      => 'is_loved',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('client_projects', 'name');
        $this->forge->dropColumn('project_photos', ['is_loved', 'rating']);
    }
}
