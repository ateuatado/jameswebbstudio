<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddPhotoPrintOptions extends Migration
{
    public function up(): void
    {
        $this->forge->addColumn('photo_work_images', [
            'is_for_sale' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0, 'after' => 'is_cover'],
            'accepts_custom_sizes' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0, 'after' => 'is_for_sale'],
            'custom_size_note' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true, 'after' => 'accepts_custom_sizes'],
        ]);

        $this->forge->addField([
            'id'                  => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'photo_work_image_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'size_label'          => ['type' => 'VARCHAR', 'constraint' => 100],
            'width_cm'            => ['type' => 'DECIMAL', 'constraint' => '10,2', 'null' => true],
            'height_cm'           => ['type' => 'DECIMAL', 'constraint' => '10,2', 'null' => true],
            'price_cents'         => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'is_available'        => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1],
            'display_order'       => ['type' => 'INT', 'constraint' => 11, 'default' => 0],
            'created_at'          => ['type' => 'DATETIME', 'null' => true],
            'updated_at'          => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey(['photo_work_image_id', 'is_available', 'display_order']);
        $this->forge->addForeignKey('photo_work_image_id', 'photo_work_images', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('photo_print_options', true);
    }

    public function down(): void
    {
        $this->forge->dropTable('photo_print_options', true);
        $this->forge->dropColumn('photo_work_images', ['is_for_sale', 'accepts_custom_sizes', 'custom_size_note']);
    }
}
