<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTrackingHitsTable extends Migration
{
    public function up(): void
    {
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'tracking_link_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => false],
            'ip_address' => ['type' => 'VARCHAR', 'constraint' => 45, 'null' => true],
            'country' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'region' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'city' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'device_type' => ['type' => 'VARCHAR', 'constraint' => 20, 'null' => true],
            'os' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'browser' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'referer' => ['type' => 'VARCHAR', 'constraint' => 512, 'null' => true],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('tracking_link_id');
        $this->forge->addKey('created_at');
        $this->forge->createTable('tracking_hits');
    }

    public function down(): void
    {
        $this->forge->dropTable('tracking_hits', true);
    }
}
