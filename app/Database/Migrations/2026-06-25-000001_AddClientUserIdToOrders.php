<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddClientUserIdToOrders extends Migration
{
    public function up(): void
    {
        // Adiciona client_user_id para vincular o pedido ao usuário do portal
        $this->forge->addColumn('orders', [
            'client_user_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
                'default'    => null,
                'after'      => 'hero_id',
            ],
        ]);

        // Índice para acelerar a query do portal
        $this->forge->addKey('client_user_id', false, false, 'idx_orders_client_user_id');
        $this->forge->processIndexes('orders');
    }

    public function down(): void
    {
        $this->forge->dropColumn('orders', 'client_user_id');
    }
}
