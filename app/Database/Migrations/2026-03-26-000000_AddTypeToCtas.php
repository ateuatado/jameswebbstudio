<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddTypeToCtas extends Migration
{
    public function up()
    {
        $fields = [
            'type' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'default'    => 'link',
                'after'      => 'hero_id',
            ],
        ];
        $this->forge->addColumn('ctas', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('ctas', 'type');
    }
}
