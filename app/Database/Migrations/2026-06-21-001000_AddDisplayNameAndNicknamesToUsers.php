<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddDisplayNameAndNicknamesToUsers extends Migration
{
    public function up(): void
    {
        $this->forge->addColumn('users', [
            'display_name' => [
                'type'       => 'VARCHAR',
                'constraint' => 150,
                'null'       => true,
                'default'    => null,
                'after'      => 'username',
                'comment'    => 'Nome completo ou apelido principal exibido no sistema',
            ],
            'nicknames' => [
                'type'       => 'VARCHAR',
                'constraint' => 500,
                'null'       => true,
                'default'    => null,
                'after'      => 'display_name',
                'comment'    => 'Apelidos separados por virgula: isa, isabel, bell, bela',
            ],
        ]);
    }

    public function down(): void
    {
        $this->forge->dropColumn('users', ['display_name', 'nicknames']);
    }
}
