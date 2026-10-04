<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateThreadsProspectingTables extends Migration
{
    public function up(): void
    {
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'assigned_user_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => true],
            'threads_username' => ['type' => 'VARCHAR', 'constraint' => 120],
            'threads_post_url' => ['type' => 'VARCHAR', 'constraint' => 512],
            'original_text' => ['type' => 'TEXT'],
            'context_category' => ['type' => 'VARCHAR', 'constraint' => 80],
            'priority' => ['type' => 'ENUM', 'constraint' => ['high', 'medium', 'low'], 'default' => 'medium'],
            'city' => ['type' => 'VARCHAR', 'constraint' => 120, 'null' => true],
            'status' => ['type' => 'ENUM', 'constraint' => ['identified', 'drafting', 'ready', 'commented', 'direct_sent', 'replied', 'conversation', 'proposal_sent', 'scheduled', 'won', 'not_interested', 'do_not_contact', 'expired'], 'default' => 'identified'],
            'comment_copy' => ['type' => 'TEXT', 'null' => true],
            'direct_copy' => ['type' => 'TEXT', 'null' => true],
            'page_title' => ['type' => 'VARCHAR', 'constraint' => 180, 'null' => true],
            'page_intro' => ['type' => 'TEXT', 'null' => true],
            'offer_copy' => ['type' => 'TEXT', 'null' => true],
            'cta_label' => ['type' => 'VARCHAR', 'constraint' => 120, 'default' => 'Quero conversar'],
            'cta_url' => ['type' => 'VARCHAR', 'constraint' => 512, 'null' => true],
            'page_token' => ['type' => 'CHAR', 'constraint' => 64],
            'is_page_active' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1],
            'last_action_at' => ['type' => 'DATETIME', 'null' => true],
            'next_action_at' => ['type' => 'DATETIME', 'null' => true],
            'response_notes' => ['type' => 'TEXT', 'null' => true],
            'proposal_value_cents' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => true],
            'closed_reason' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('page_token');
        $this->forge->addKey(['threads_username', 'threads_post_url']);
        $this->forge->addKey('status');
        $this->forge->addForeignKey('assigned_user_id', 'users', 'id', 'CASCADE', 'SET NULL');
        $this->forge->createTable('threads_opportunities');

        $this->forge->addField([
            'id' => ['type' => 'BIGINT', 'constraint' => 20, 'unsigned' => true, 'auto_increment' => true],
            'opportunity_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'event_type' => ['type' => 'VARCHAR', 'constraint' => 40],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey(['opportunity_id', 'event_type']);
        $this->forge->addForeignKey('opportunity_id', 'threads_opportunities', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('threads_opportunity_events');
    }

    public function down(): void
    {
        $this->forge->dropTable('threads_opportunity_events', true);
        $this->forge->dropTable('threads_opportunities', true);
    }
}
