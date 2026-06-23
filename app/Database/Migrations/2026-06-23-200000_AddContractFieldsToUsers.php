<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddContractFieldsToUsers extends Migration
{
    public function up()
    {
        $fields = [
            'nome_completo' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
                'after'      => 'display_name',
            ],
            'cpf' => [
                'type'       => 'VARCHAR',
                'constraint' => 18,
                'null'       => true,
                'after'      => 'nome_completo',
            ],
            'rg' => [
                'type'       => 'VARCHAR',
                'constraint' => 30,
                'null'       => true,
                'after'      => 'cpf',
            ],
            'endereco_cep' => [
                'type'       => 'VARCHAR',
                'constraint' => 9,
                'null'       => true,
                'after'      => 'rg',
            ],
            'endereco_logradouro' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
                'after'      => 'endereco_cep',
            ],
            'endereco_numero' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'null'       => true,
                'after'      => 'endereco_logradouro',
            ],
            'endereco_complemento' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
                'after'      => 'endereco_numero',
            ],
            'endereco_cidade' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
                'after'      => 'endereco_complemento',
            ],
            'endereco_estado' => [
                'type'       => 'VARCHAR',
                'constraint' => 2,
                'null'       => true,
                'after'      => 'endereco_cidade',
            ],
        ];

        $this->forge->addColumn('users', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('users', [
            'nome_completo',
            'cpf',
            'rg',
            'endereco_cep',
            'endereco_logradouro',
            'endereco_numero',
            'endereco_complemento',
            'endereco_cidade',
            'endereco_estado',
        ]);
    }
}
