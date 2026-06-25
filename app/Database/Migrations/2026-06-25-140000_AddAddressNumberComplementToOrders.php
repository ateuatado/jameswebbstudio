<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddAddressNumberComplementToOrders extends Migration
{
    public function up(): void
    {
        // address_number: número do logradouro (ex: "277", "1500 A")
        $this->forge->addColumn('orders', [
            'address_number' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'null'       => true,
                'default'    => null,
                'after'      => 'address',
            ],
        ]);

        // address_complement: complemento (ex: "Apto 42", "Bloco B")
        $this->forge->addColumn('orders', [
            'address_complement' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
                'default'    => null,
                'after'      => 'address_number',
            ],
        ]);
    }

    public function down(): void
    {
        $this->forge->dropColumn('orders', ['address_number', 'address_complement']);
    }
}
