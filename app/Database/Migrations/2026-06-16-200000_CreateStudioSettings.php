<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateStudioSettings extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'setting_key' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'unique'     => true,
            ],
            'setting_value' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'label' => [
                'type'       => 'VARCHAR',
                'constraint' => 150,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('studio_settings');
    }

    public function down()
    {
        $this->forge->dropTable('studio_settings');
    }
}
