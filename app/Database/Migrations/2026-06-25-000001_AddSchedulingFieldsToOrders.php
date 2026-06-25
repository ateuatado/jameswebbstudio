<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddSchedulingFieldsToOrders extends Migration
{
    public function up(): void
    {
        // Data confirmada do ensaio (gravada via proxy quando cliente agenda)
        $this->forge->addColumn('orders', [
            'scheduled_at' => [
                'type'       => 'DATETIME',
                'null'       => true,
                'default'    => null,
                'after'      => 'agenda_link',
            ],
            // ID do agendamento no sistema externo de agenda
            'agenda_booking_id' => [
                'type'       => 'VARCHAR',
                'constraint' => 120,
                'null'       => true,
                'default'    => null,
                'after'      => 'scheduled_at',
            ],
        ]);
    }

    public function down(): void
    {
        $this->forge->dropColumn('orders', ['scheduled_at', 'agenda_booking_id']);
    }
}
