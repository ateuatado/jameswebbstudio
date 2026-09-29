<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class DropLegacyFrameCatalog extends Migration
{
    public function up(): void
    {
        $this->forge->dropTable('frame_images', true);
        $this->forge->dropTable('frames', true);
    }

    public function down(): void
    {
        // The old independent catalog is intentionally not restored.
    }
}
