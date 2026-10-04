<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddThreadsAssignedUserForeignKey extends Migration
{
    private function exists(): bool
    {
        return (bool) $this->db->query("SELECT CONSTRAINT_NAME FROM information_schema.REFERENTIAL_CONSTRAINTS WHERE CONSTRAINT_SCHEMA = DATABASE() AND TABLE_NAME = 'threads_opportunities' AND CONSTRAINT_NAME = 'threads_opportunities_assigned_user_id_foreign'")->getRow();
    }

    public function up(): void
    {
        if (!$this->exists()) {
            $this->db->query('ALTER TABLE threads_opportunities ADD CONSTRAINT threads_opportunities_assigned_user_id_foreign FOREIGN KEY (assigned_user_id) REFERENCES users (id) ON UPDATE CASCADE ON DELETE SET NULL');
        }
    }

    public function down(): void
    {
        if ($this->exists()) {
            $this->db->query('ALTER TABLE threads_opportunities DROP FOREIGN KEY threads_opportunities_assigned_user_id_foreign');
        }
    }
}
