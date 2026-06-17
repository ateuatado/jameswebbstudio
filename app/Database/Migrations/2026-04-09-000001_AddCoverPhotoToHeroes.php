<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddCoverPhotoToHeroes extends Migration
{
    public function up(): void
    {
        $this->forge->addColumn('heroes', [
            'cover_photo_id' => [
                'type'       => 'INT',
                'unsigned'   => true,
                'null'       => true,
                'default'    => null,
                'after'      => 'slug',
            ],
        ]);
    }

    public function down(): void
    {
        $this->forge->dropColumn('heroes', 'cover_photo_id');
    }
}
