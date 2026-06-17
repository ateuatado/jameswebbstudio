<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateHeroPublicationLogsTable extends Migration
{
    public function up(): void
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'hero_id' => [
                'type'     => 'INT',
                'unsigned' => true,
                'null'     => false,
            ],
            'action' => [
                'type'       => 'ENUM',
                'constraint' => ['published', 'unpublished'],
                'null'       => false,
            ],
            'reason' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'performed_by' => [
                'type'     => 'INT',
                'unsigned' => true,
                'null'     => true,
                'comment'  => 'user.id do Shield',
            ],
            'performed_by_name' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => false,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('hero_id');
        $this->forge->createTable('hero_publication_logs');
    }

    public function down(): void
    {
        $this->forge->dropTable('hero_publication_logs');
    }
}
