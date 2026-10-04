<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddParsedThreadSourceFields extends Migration
{
    public function up(): void
    {
        $this->forge->addColumn('threads_opportunities', [
            'source_text' => ['type' => 'TEXT', 'null' => true, 'after' => 'threads_post_url'],
            'published_relative' => ['type' => 'VARCHAR', 'constraint' => 40, 'null' => true, 'after' => 'source_text'],
            'hashtags' => ['type' => 'TEXT', 'null' => true, 'after' => 'published_relative'],
        ]);
    }

    public function down(): void
    {
        $this->forge->dropColumn('threads_opportunities', ['source_text', 'published_relative', 'hashtags']);
    }
}
