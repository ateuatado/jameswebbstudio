<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateCtasTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'          => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'hero_id'     => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'title'       => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'description' => ['type' => 'TEXT', 'null' => true],
            'button_text' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'button_url'  => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'created_at'  => ['type' => 'DATETIME', 'null' => true],
            'updated_at'  => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('hero_id', 'heroes', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('ctas', true);
    }

    public function down()
    {
        $this->forge->dropTable('ctas');
    }
}
