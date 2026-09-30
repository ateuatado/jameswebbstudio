<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddSocialMetadataToPhotoWorkImages extends Migration
{
    public function up(): void
    {
        $this->forge->addColumn('photo_work_images', [
            'title' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true, 'after' => 'alt_text'],
            'description' => ['type' => 'TEXT', 'null' => true, 'after' => 'title'],
            'social_image_path' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true, 'after' => 'image_path'],
        ]);
    }

    public function down(): void
    {
        $this->forge->dropColumn('photo_work_images', ['title', 'description', 'social_image_path']);
    }
}
