<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateServiceContextDrafts extends Migration
{
    public function up(): void
    {
        $this->forge->addField([
            'id' => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'batch_id' => ['type' => 'INT', 'unsigned' => true],
            'seed_facts' => ['type' => 'TEXT'],
            'facts' => ['type' => 'TEXT', 'null' => true],
            'warnings' => ['type' => 'TEXT', 'null' => true],
            'approved_context_id' => ['type' => 'INT', 'unsigned' => true, 'default' => 0],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('batch_id');
        $this->forge->createTable('service_content_context_drafts', true);
    }

    public function down(): void { $this->forge->dropTable('service_content_context_drafts', true); }
}
