<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePhotoEngagementTables extends Migration
{
    public function up(): void
    {
        $this->forge->addField([
            'id'                  => ['type' => 'BIGINT', 'constraint' => 20, 'unsigned' => true, 'auto_increment' => true],
            'photo_work_image_id'  => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'user_id'             => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => true],
            'visitor_token'       => ['type' => 'VARCHAR', 'constraint' => 64, 'null' => true],
            'viewed_at'           => ['type' => 'DATETIME'],
            'created_at'          => ['type' => 'DATETIME', 'null' => true],
            'updated_at'          => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey(['photo_work_image_id', 'viewed_at']);
        $this->forge->addKey(['user_id', 'viewed_at']);
        $this->forge->addKey(['visitor_token', 'viewed_at']);
        $this->forge->addForeignKey('photo_work_image_id', 'photo_work_images', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('user_id', 'users', 'id', 'SET NULL', 'CASCADE');
        $this->forge->createTable('photo_image_views', true);

        $this->forge->addField([
            'id'                  => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'photo_work_image_id'  => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'user_id'             => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'body'                => ['type' => 'TEXT'],
            'is_published'        => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1],
            'created_at'          => ['type' => 'DATETIME', 'null' => true],
            'updated_at'          => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey(['photo_work_image_id', 'is_published', 'created_at']);
        $this->forge->addForeignKey('photo_work_image_id', 'photo_work_images', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('photo_comments', true);
    }

    public function down(): void
    {
        $this->forge->dropTable('photo_comments', true);
        $this->forge->dropTable('photo_image_views', true);
    }
}
