<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddBasePriceToPackages extends Migration
{
    public function up()
    {
        $this->forge->addColumn('packages', [
            'base_price' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
                'null'       => false,
                'default'    => '0.00',
                'after'      => 'name'
            ]
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('packages', 'base_price');
    }
}
