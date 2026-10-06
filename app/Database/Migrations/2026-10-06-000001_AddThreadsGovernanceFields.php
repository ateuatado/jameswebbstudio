<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddThreadsGovernanceFields extends Migration
{
    public function up(): void
    {
        $this->forge->addColumn('threads_opportunities', [
            'do_not_contact_at' => ['type' => 'DATETIME', 'null' => true, 'after' => 'closed_reason'],
        ]);
        $this->forge->addColumn('threads_opportunity_events', [
            'actor_user_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => true, 'after' => 'event_type'],
            'details' => ['type' => 'TEXT', 'null' => true, 'after' => 'actor_user_id'],
        ]);
        $this->db->query('ALTER TABLE threads_opportunity_events ADD CONSTRAINT threads_events_actor_user_id_foreign FOREIGN KEY (actor_user_id) REFERENCES users (id) ON UPDATE CASCADE ON DELETE SET NULL');
    }

    public function down(): void
    {
        $this->db->query('ALTER TABLE threads_opportunity_events DROP FOREIGN KEY threads_events_actor_user_id_foreign');
        $this->forge->dropColumn('threads_opportunity_events', ['actor_user_id', 'details']);
        $this->forge->dropColumn('threads_opportunities', ['do_not_contact_at']);
    }
}
