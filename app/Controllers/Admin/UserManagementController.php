<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class UserManagementController extends BaseController
{
    /**
     * Lista todos os usuários com suas permissões atuais.
     * Apenas admin/superadmin podem acessar.
     */
    public function index()
    {
        $users    = auth()->getProvider()->findAll();
        $userData = [];

        foreach ($users as $user) {
            $userData[] = [
                'user'          => $user,
                'groups'        => $user->getGroups(),
                'search_global' => $user->can('search.global'),
            ];
        }

        return view('admin/usuarios/index', [
            'title'    => 'Gerenciamento de Usuários',
            'userData' => $userData,
        ]);
    }

    /**
     * Liga/desliga a permissão search.global para um usuário.
     */
    public function toggleSearchPermission($userId)
    {
        $provider = auth()->getProvider();
        $user     = $provider->findById($userId);

        if (!$user) {
            return redirect()->to('/admin/usuarios')->with('error', 'Usuário não encontrado.');
        }

        // Não permite alterar a permissão de admins/superadmins (eles sempre têm acesso)
        if ($user->inGroup('admin', 'superadmin')) {
            return redirect()->to('/admin/usuarios')->with('error', 'Administradores sempre têm acesso à busca global.');
        }

        if ($user->can('search.global')) {
            // Remove a permissão
            $user->revokePermission('search.global');
            $message = 'Busca Global desativada para ' . ($user->username ?? $user->email);
        } else {
            // Concede a permissão
            $user->addPermission('search.global');
            $message = 'Busca Global ativada para ' . ($user->username ?? $user->email);
        }

        return redirect()->to('/admin/usuarios')->with('message', $message);
    }
}
