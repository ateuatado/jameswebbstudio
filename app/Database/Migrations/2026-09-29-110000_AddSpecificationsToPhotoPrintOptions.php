<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddSpecificationsToPhotoPrintOptions extends Migration
{
    public function up(): void
    {
        $this->forge->addColumn('photo_print_options', [
            'print_material' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true, 'after' => 'price_cents'],
            'frame_material' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true, 'after' => 'print_material'],
            'backing_material' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true, 'after' => 'frame_material'],
            'glazing' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true, 'after' => 'backing_material'],
            'weight_grams' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => true, 'after' => 'glazing'],
            'production_lead_time' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true, 'after' => 'weight_grams'],
        ]);
    }

    public function down(): void
    {
        $this->forge->dropColumn('photo_print_options', ['print_material', 'frame_material', 'backing_material', 'glazing', 'weight_grams', 'production_lead_time']);
    }
}
