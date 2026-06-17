<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateCategoriesAndAddRelations extends Migration
{
    public function up()
    {
        // 1. Criar a tabela 'categories'
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'name' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => false,
            ],
            'slug' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => false,
            ],
            'description' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'is_active' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'unsigned'   => true,
                'default'    => 1,
                'null'       => false,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('slug');
        $this->forge->createTable('categories');

        // 2. Adicionar 'category_id' na tabela 'packages'
        $this->forge->addColumn('packages', [
            'category_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
                'after'      => 'base_price',
            ],
        ]);
        // Chave estrangeira
        $this->db->query("ALTER TABLE packages ADD CONSTRAINT fk_packages_category_id FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL ON UPDATE CASCADE");

        // 3. Adicionar 'category_id' na tabela 'heroes'
        $this->forge->addColumn('heroes', [
            'category_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
                'after'      => 'sport',
            ],
        ]);
        // Chave estrangeira
        $this->db->query("ALTER TABLE heroes ADD CONSTRAINT fk_heroes_category_id FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL ON UPDATE CASCADE");
    }

    public function down()
    {
        // 1. Remover FKs
        $this->db->query("ALTER TABLE packages DROP FOREIGN KEY fk_packages_category_id");
        $this->db->query("ALTER TABLE heroes DROP FOREIGN KEY fk_heroes_category_id");

        // 2. Remover colunas
        $this->forge->dropColumn('packages', 'category_id');
        $this->forge->dropColumn('heroes', 'category_id');

        // 3. Dropar tabela
        $this->forge->dropTable('categories');
    }
}
