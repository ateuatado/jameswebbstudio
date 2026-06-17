<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateProjectPhotosTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'project_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'original_filename' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'proxy_url' => [
                'type'       => 'VARCHAR',
                'constraint' => '500',
            ],
            'final_url' => [
                'type'       => 'VARCHAR',
                'constraint' => '500',
                'null'       => true,
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['pending', 'selected', 'delivered'],
                'default'    => 'pending',
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('project_id', 'client_projects', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('project_photos');
    }

    public function down()
    {
        $this->forge->dropTable('project_photos');
    }
}
