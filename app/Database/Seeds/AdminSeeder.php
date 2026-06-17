<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

/**
 * Cria o usuário administrador padrão do sistema.
 *
 * Uso:
 *   php spark db:seed AdminSeeder
 */
class AdminSeeder extends Seeder
{
    public function run(): void
    {
        // Credenciais do admin
        $email    = 'marcosantofoto@gmail.com';
        $username = 'marcosantofoto';
        $password = 'Lula#Eleito26';
        $group    = 'admin';

        /** @var \CodeIgniter\Shield\Models\UserModel $users */
        $users = auth()->getProvider();

        // Não recria se já existir
        if ($users->findByCredentials(['email' => $email])) {
            echo "Usuário {$email} já existe. Nada foi alterado.\n";
            return;
        }

        $user = new \CodeIgniter\Shield\Entities\User([
            'username' => $username,
            'email'    => $email,
            'password' => $password,
            'active'   => 1,
        ]);

        $users->save($user);

        // Adiciona ao grupo admin
        $saved = $users->findByCredentials(['email' => $email]);
        $saved->addGroup($group);

        echo "✅ Admin criado com sucesso!\n";
        echo "   Email   : {$email}\n";
        echo "   Username: {$username}\n";
        echo "   Grupo   : {$group}\n";
    }
}
