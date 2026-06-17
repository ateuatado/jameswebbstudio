<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddAgendaLinkToOrders extends Migration
{
    public function up()
    {
        $this->forge->addColumn('orders', [
            'agenda_link' => [
                'type'       => 'VARCHAR',
                'constraint' => 512,
                'null'       => true,
                'default'    => null,
                'after'      => 'status',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('orders', 'agenda_link');
    }
}
