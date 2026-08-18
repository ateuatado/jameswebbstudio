<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTrackingLinksTable extends Migration
{
    public function up(): void
    {
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'slug' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => false],
            'destination_url' => ['type' => 'VARCHAR', 'constraint' => 512, 'null' => false],
            'utm_source' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'utm_medium' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'utm_campaign' => ['type' => 'VARCHAR', 'constraint' => 191, 'null' => true],
            'utm_content' => ['type' => 'VARCHAR', 'constraint' => 191, 'null' => true],
            'is_active' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('slug');
        $this->forge->createTable('tracking_links');
    }

    public function down(): void
    {
        $this->forge->dropTable('tracking_links', true);
    }
}
