<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddRgToOrders extends Migration
{
    public function up()
    {
        $this->forge->addColumn('orders', [
            'rg' => [
                'type'       => 'VARCHAR',
                'constraint' => 30,
                'null'       => true,
                'default'    => null,
                'after'      => 'cpf',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('orders', 'rg');
    }
}
