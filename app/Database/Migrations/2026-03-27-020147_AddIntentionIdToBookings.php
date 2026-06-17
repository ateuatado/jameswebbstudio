<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddIntentionIdToBookings extends Migration
{
    public function up()
    {
        $this->forge->addColumn('bookings', [
            'intention_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
                'after'      => 'availability_id'
            ]
        ]);

        $this->db->query("ALTER TABLE bookings ADD CONSTRAINT fk_intention_id FOREIGN KEY (intention_id) REFERENCES intentions(id) ON DELETE SET NULL");
    }

    public function down()
    {
        $this->db->query("ALTER TABLE bookings DROP FOREIGN KEY fk_intention_id");
        $this->forge->dropColumn('bookings', 'intention_id');
    }
}
