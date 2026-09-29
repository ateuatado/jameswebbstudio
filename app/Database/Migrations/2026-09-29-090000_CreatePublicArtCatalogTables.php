<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePublicArtCatalogTables extends Migration
{
    public function up(): void
    {
        $this->forge->addField([
            'id'                => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'title'             => ['type' => 'VARCHAR', 'constraint' => 255],
            'slug'              => ['type' => 'VARCHAR', 'constraint' => 255],
            'short_description' => ['type' => 'TEXT', 'null' => true],
            'story'             => ['type' => 'TEXT', 'null' => true],
            'work_date'         => ['type' => 'DATE', 'null' => true],
            'location'          => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'technique'         => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'collection_name'   => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'credits'           => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'is_published'      => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0],
            'published_at'      => ['type' => 'DATETIME', 'null' => true],
            'display_order'     => ['type' => 'INT', 'constraint' => 11, 'default' => 0],
            'created_at'        => ['type' => 'DATETIME', 'null' => true],
            'updated_at'        => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('slug');
        $this->forge->addKey(['is_published', 'display_order']);
        $this->forge->createTable('photo_works', true);

        $this->forge->addField([
            'id'            => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'photo_work_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'image_path'    => ['type' => 'VARCHAR', 'constraint' => 255],
            'alt_text'      => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'is_cover'      => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0],
            'display_order' => ['type' => 'INT', 'constraint' => 11, 'default' => 0],
            'created_at'    => ['type' => 'DATETIME', 'null' => true],
            'updated_at'    => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey(['photo_work_id', 'display_order']);
        $this->forge->addForeignKey('photo_work_id', 'photo_works', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('photo_work_images', true);

    }

    public function down(): void
    {
        $this->forge->dropTable('photo_work_images', true);
        $this->forge->dropTable('photo_works', true);
    }
}
