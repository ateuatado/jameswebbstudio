<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class StudioSettingSeeder extends Seeder
{
    public function run()
    {
        $db = \Config\Database::connect();

        $settings = [
            ['setting_key' => 'studio_name',        'label' => 'Nome do Estúdio / Fotógrafo',  'setting_value' => 'Marco Santo Fotografia'],
            ['setting_key' => 'owner_full_name',     'label' => 'Nome Civil Completo',           'setting_value' => 'Marcos Vieira dos Santos'],
            ['setting_key' => 'owner_cpf',           'label' => 'CPF',                           'setting_value' => '132.013.478-50'],
            ['setting_key' => 'owner_marital_status', 'label' => 'Estado Civil',                 'setting_value' => 'Casado'],
            ['setting_key' => 'studio_address',      'label' => 'Endereço Completo',             'setting_value' => 'Rua Domingos Rodrigues, 242, Sala 31'],
            ['setting_key' => 'studio_neighborhood', 'label' => 'Bairro',                        'setting_value' => 'Lapa'],
            ['setting_key' => 'studio_city',         'label' => 'Cidade',                        'setting_value' => 'São Paulo'],
            ['setting_key' => 'studio_state',        'label' => 'UF',                            'setting_value' => 'SP'],
            ['setting_key' => 'studio_zip',          'label' => 'CEP',                           'setting_value' => ''],
            ['setting_key' => 'studio_phone',        'label' => 'Telefone / WhatsApp',           'setting_value' => ''],
            ['setting_key' => 'studio_email',        'label' => 'E-mail',                        'setting_value' => 'contato@marcosantofoto.com.br'],
            ['setting_key' => 'studio_cnpj',         'label' => 'CNPJ (se houver)',              'setting_value' => ''],
        ];

        foreach ($settings as $s) {
            $db->table('studio_settings')->insert($s);
        }
    }
}
