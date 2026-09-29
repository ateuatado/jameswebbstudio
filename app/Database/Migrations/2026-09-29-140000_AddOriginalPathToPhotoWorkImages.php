<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddOriginalPathToPhotoWorkImages extends Migration
{
    public function up(): void
    {
        $this->forge->addColumn('photo_work_images', [
            'original_path' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true, 'after' => 'image_path'],
        ]);
    }

    public function down(): void
    {
        $this->forge->dropColumn('photo_work_images', 'original_path');
    }
}
