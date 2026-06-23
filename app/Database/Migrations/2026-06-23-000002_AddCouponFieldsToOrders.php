<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddCouponFieldsToOrders extends Migration
{
    public function up(): void
    {
        $this->forge->addColumn('orders', [
            'coupon_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
                'after'      => 'hero_id',
            ],
            'discount_percent' => [
                'type'       => 'TINYINT',
                'constraint' => 3,
                'unsigned'   => true,
                'default'    => 0,
                'null'       => false,
                'after'      => 'coupon_id',
            ],
        ]);
    }

    public function down(): void
    {
        $this->forge->dropColumn('orders', ['coupon_id', 'discount_percent']);
    }
}
