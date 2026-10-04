<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddWhatsAppContactsToThreads extends Migration
{
    public function up(): void
    {
        $this->forge->addColumn('threads_opportunities', [
            'whatsapp_owner' => ['type' => 'VARCHAR', 'constraint' => 30, 'null' => true, 'after' => 'cta_url'],
            'whatsapp_number' => ['type' => 'VARCHAR', 'constraint' => 30, 'null' => true, 'after' => 'whatsapp_owner'],
        ]);

        $db = $this->db;
        $settings = [
            ['studio_whatsapp_wife', 'WhatsApp da esposa', ''],
        ];
        foreach ($settings as [$key, $label, $value]) {
            $exists = $db->table('studio_settings')->where('setting_key', $key)->countAllResults();
            if (!$exists) $db->table('studio_settings')->insert(['setting_key' => $key, 'label' => $label, 'setting_value' => $value]);
        }
    }

    public function down(): void
    {
        $this->db->table('studio_settings')->where('setting_key', 'studio_whatsapp_wife')->delete();
        $this->forge->dropColumn('threads_opportunities', ['whatsapp_owner', 'whatsapp_number']);
    }
}
