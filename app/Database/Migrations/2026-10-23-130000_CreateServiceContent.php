<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateServiceContent extends Migration
{
    public function up(): void
    {
        $id = ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true];
        $integer = ['type' => 'INT', 'unsigned' => true, 'default' => 0];
        $text = ['type' => 'TEXT', 'null' => true];
        $date = ['type' => 'DATETIME', 'null' => true];
        $tables = [
            'service_content_contexts' => ['id' => $id, 'facts' => $text, 'whatsapp' => ['type' => 'VARCHAR', 'constraint' => 18], 'created_by' => $integer, 'created_at' => $date],
            'service_content_families' => ['id' => $id, 'name' => ['type' => 'VARCHAR', 'constraint' => 150], 'slug' => ['type' => 'VARCHAR', 'constraint' => 150], 'description' => $text, 'created_at' => $date],
            'service_content_pages' => ['id' => $id, 'family_id' => $integer, 'topic' => ['type' => 'VARCHAR', 'constraint' => 200], 'slug' => ['type' => 'VARCHAR', 'constraint' => 150], 'status' => ['type' => 'VARCHAR', 'constraint' => 20, 'default' => 'draft'], 'current_revision_id' => $integer, 'published_revision_id' => $integer, 'lock_version' => $integer, 'ever_published' => $integer, 'created_by' => $integer, 'created_at' => $date, 'updated_at' => $date, 'published_at' => $date],
            'service_content_revisions' => ['id' => $id, 'page_id' => $integer, 'context_id' => $integer, 'content' => $text, 'source' => ['type' => 'VARCHAR', 'constraint' => 20], 'model' => ['type' => 'VARCHAR', 'constraint' => 80, 'default' => ''], 'schema_version' => ['type' => 'VARCHAR', 'constraint' => 10, 'default' => '1'], 'created_by' => $integer, 'created_at' => $date],
            'service_content_batches' => ['id' => $id, 'request_key' => ['type' => 'VARCHAR', 'constraint' => 64], 'created_by' => $integer, 'context_id' => $integer, 'status' => ['type' => 'VARCHAR', 'constraint' => 20, 'default' => 'queued'], 'token_limit' => $integer, 'reserved_tokens' => $integer, 'used_tokens' => $integer, 'created_at' => $date],
            'service_content_jobs' => ['id' => $id, 'batch_id' => $integer, 'page_id' => $integer, 'base_version' => $integer, 'state' => ['type' => 'VARCHAR', 'constraint' => 20, 'default' => 'queued'], 'attempts' => $integer, 'lease_token' => ['type' => 'VARCHAR', 'constraint' => 64, 'null' => true], 'lease_expires_at' => $date, 'available_at' => $date, 'revision_id' => $integer, 'error' => $text, 'input_tokens' => $integer, 'output_tokens' => $integer, 'created_at' => $date],
            'service_content_audit' => ['id' => $id, 'page_id' => $integer, 'actor_id' => $integer, 'action' => ['type' => 'VARCHAR', 'constraint' => 50], 'revision_id' => $integer, 'created_at' => $date],
            'service_content_redirects' => ['id' => $id, 'old_path' => ['type' => 'VARCHAR', 'constraint' => 320], 'page_id' => $integer],
            'service_content_budgets' => ['id' => $id, 'budget_day' => ['type' => 'VARCHAR', 'constraint' => 10], 'reserved_tokens' => $integer],
        ];
        $tables['service_content_batches']['reserved_microusd'] = $integer;
        $tables['service_content_batches']['used_microusd'] = $integer;
        $tables['service_content_budgets']['reserved_microusd'] = $integer;
        foreach ($tables as $name => $fields) {
            $this->forge->addField($fields);
            $this->forge->addKey('id', true);
            if ($name === 'service_content_families') $this->forge->addUniqueKey('slug');
            if ($name === 'service_content_pages') $this->forge->addUniqueKey(['family_id', 'slug']);
            if ($name === 'service_content_batches') $this->forge->addUniqueKey('request_key');
            if ($name === 'service_content_redirects') $this->forge->addUniqueKey('old_path');
            if ($name === 'service_content_budgets') $this->forge->addUniqueKey('budget_day');
            if (isset($fields['page_id'])) $this->forge->addKey('page_id');
            if ($name === 'service_content_jobs') $this->forge->addKey(['state', 'available_at']);
            $this->forge->createTable($name, true);
        }
    }

    public function down(): void
    {
        foreach (['budgets', 'redirects', 'audit', 'jobs', 'batches', 'revisions', 'pages', 'families', 'contexts'] as $suffix) {
            $this->forge->dropTable('service_content_' . $suffix, true);
        }
    }
}
