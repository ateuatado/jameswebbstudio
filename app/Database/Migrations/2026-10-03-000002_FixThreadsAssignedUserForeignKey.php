<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class FixThreadsAssignedUserForeignKey extends Migration
{
    public function up(): void
    {
        $this->db->query('ALTER TABLE threads_opportunities DROP FOREIGN KEY threads_opportunities_assigned_user_id_foreign');
        $this->db->query('ALTER TABLE threads_opportunities ADD CONSTRAINT threads_opportunities_assigned_user_id_foreign FOREIGN KEY (assigned_user_id) REFERENCES users (id) ON UPDATE CASCADE ON DELETE SET NULL');
    }

    public function down(): void
    {
        $this->db->query('ALTER TABLE threads_opportunities DROP FOREIGN KEY threads_opportunities_assigned_user_id_foreign');
        $this->db->query('ALTER TABLE threads_opportunities ADD CONSTRAINT threads_opportunities_assigned_user_id_foreign FOREIGN KEY (assigned_user_id) REFERENCES users (id) ON UPDATE CASCADE ON DELETE SET NULL');
    }
}
