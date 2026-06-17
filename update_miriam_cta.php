<?php
require 'vendor/autoload.php';
$app = require_once 'app/Config/Events.php';
$config = new \Config\App();
$db = \Config\Database::connect();

// Get Miriam ID
$hero = $db->table('heroes')->where('slug', 'miriam')->get()->getRow();
if ($hero) {
    echo "Miriam ID: " . $hero->id . "\n";
    $cta = $db->table('ctas')->where('hero_id', $hero->id)->get()->getRow();
    if ($cta) {
        echo "Updating existing CTA for Miriam...\n";
        $db->table('ctas')->where('id', $cta->id)->update([
            'type' => 'form',
            'title' => 'Transceda Seus Limites',
            'description' => "O ensaio Heroic não é apenas uma sessão de fotos. É a materialização do seu compromisso com a excelência.\n\nPreencha os detalhes abaixo para manifestar sua intenção e entraremos em contato para planejar sua jornada.",
            'button_text' => 'MANIFESTAR INTENÇÃO'
        ]);
    } else {
        echo "Creating new CTA for Miriam (Type: Form)...\n";
        $db->table('ctas')->insert([
            'hero_id' => $hero->id,
            'type' => 'form',
            'title' => 'Transceda Seus Limites',
            'description' => "O ensaio Heroic não é apenas uma sessão de fotos. É a materialização do seu compromisso com a excelência.\n\nPreencha os detalhes abaixo para manifestar sua intenção e entraremos em contato para planejar sua jornada.",
            'button_text' => 'MANIFESTAR INTENÇÃO'
        ]);
    }
} else {
    echo "Hero Miriam not found.\n";
}
