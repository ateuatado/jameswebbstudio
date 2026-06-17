<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddFieldsToPackages extends Migration
{
    public function up()
    {
        $this->forge->addColumn('packages', [
            'description' => [
                'type' => 'TEXT',
                'null' => true,
                'after' => 'base_price'
            ],
            'internal_notes' => [
                'type' => 'TEXT',
                'null' => true,
                'after' => 'description'
            ],
            'is_active' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'unsigned'   => true,
                'default'    => 1,
                'null'       => false,
                'after'      => 'extra_photo_price'
            ],
            'is_preferred' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'unsigned'   => true,
                'default'    => 0,
                'null'       => false,
                'after'      => 'is_active'
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('packages', ['description', 'internal_notes', 'is_active', 'is_preferred']);
    }
}
