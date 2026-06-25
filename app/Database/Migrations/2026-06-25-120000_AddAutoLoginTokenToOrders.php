<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddAutoLoginTokenToOrders extends Migration
{
    public function up(): void
    {
        $this->forge->addColumn('orders', [
            'auto_login_token' => [
                'type'       => 'VARCHAR',
                'constraint' => 80,
                'null'       => true,
                'default'    => null,
                'after'      => 'agenda_link',
            ],
            'auto_login_expires' => [
                'type'    => 'DATETIME',
                'null'    => true,
                'default' => null,
                'after'   => 'auto_login_token',
            ],
        ]);
    }

    public function down(): void
    {
        $this->forge->dropColumn('orders', ['auto_login_token', 'auto_login_expires']);
    }
}
