<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddPublishedToHeroes extends Migration
{
    public function up(): void
    {
        $this->forge->addColumn('heroes', [
            'published' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'unsigned'   => true,
                'default'    => 1,
                'null'       => false,
                'after'      => 'cover_photo_id',
            ],
        ]);
    }

    public function down(): void
    {
        $this->forge->dropColumn('heroes', 'published');
    }
}
