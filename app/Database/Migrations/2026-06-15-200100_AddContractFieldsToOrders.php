<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddContractFieldsToOrders extends Migration
{
    public function up()
    {
        $this->forge->addColumn('orders', [
            'cpf' => [
                'type'       => 'VARCHAR',
                'constraint' => 14,
                'null'       => true,
                'after'      => 'buyer_phone',
            ],
            'marital_status' => [
                'type'       => 'VARCHAR',
                'constraint' => 30,
                'null'       => true,
                'after'      => 'cpf',
            ],
            'address' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
                'after'      => 'marital_status',
            ],
            'city' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
                'after'      => 'address',
            ],
            'state' => [
                'type'       => 'VARCHAR',
                'constraint' => 2,
                'null'       => true,
                'after'      => 'city',
            ],
            'zip_code' => [
                'type'       => 'VARCHAR',
                'constraint' => 10,
                'null'       => true,
                'after'      => 'state',
            ],
            'image_usage_authorized' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'null'       => true,
                'after'      => 'zip_code',
            ],
            'accepted_terms_at' => [
                'type' => 'DATETIME',
                'null' => true,
                'after' => 'image_usage_authorized',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('orders', [
            'cpf',
            'marital_status',
            'address',
            'city',
            'state',
            'zip_code',
            'image_usage_authorized',
            'accepted_terms_at',
        ]);
    }
}
