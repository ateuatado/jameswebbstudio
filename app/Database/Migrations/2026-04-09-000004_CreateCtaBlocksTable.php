<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateCtaBlocksTable extends Migration
{
    public function up(): void
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'cta_id' => [
                'type'     => 'INT',
                'unsigned' => true,
                'null'     => false,
            ],
            'type' => [
                'type'       => 'ENUM',
                'constraint' => ['headline','text','image','video_embed','testimony','process','cta_button','spacer'],
                'null'       => false,
            ],
            'content' => [
                'type' => 'JSON',
                'null' => true,
            ],
            'display_order' => [
                'type'    => 'INT',
                'default' => 0,
            ],
            'created_at' => ['type' => 'DATETIME', 'null' => false],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('cta_id');
        $this->forge->createTable('cta_blocks');
    }

    public function down(): void
    {
        $this->forge->dropTable('cta_blocks');
    }
}
