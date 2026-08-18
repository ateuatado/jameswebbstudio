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

        if ($user->inGroup('admin', 'superadmin')) {
            return redirect()->to('/admin/usuarios')->with('error', 'Administradores sempre têm acesso à busca global.');
        }

        if ($user->can('search.global')) {
            $user->revokePermission('search.global');
            $message = 'Busca Global desativada para ' . ($user->username ?? $user->email);
        } else {
            $user->addPermission('search.global');
            $message = 'Busca Global ativada para ' . ($user->username ?? $user->email);
        }

        return redirect()->to('/admin/usuarios')->with('message', $message);
    }

    /**
     * Cadastra o rosto de um cliente no Rekognition usando uma foto de um ensaio.
     * POST /admin/usuarios/{userId}/cadastrar-rosto
     * Body JSON: { "photo_id": 123 }
     */
    public function registerFace($userId)
    {
        $provider = auth()->getProvider();
        $user     = $provider->findById($userId);

        if (!$user) {
            return $this->response->setStatusCode(404)->setJSON(['success' => false, 'message' => 'Usuário não encontrado.']);
        }

        $json    = $this->request->getJSON();
        $photoId = (int) ($json->photo_id ?? 0);

        if (!$photoId) {
            return $this->response->setStatusCode(400)->setJSON(['success' => false, 'message' => 'photo_id é obrigatório.']);
        }

        $photoModel = new \App\Models\ProjectPhotoModel();
        $photo      = $photoModel->find($photoId);

        if (!$photo) {
            return $this->response->setStatusCode(404)->setJSON(['success' => false, 'message' => 'Foto não encontrada.']);
        }

        // Usa a foto proxy do S3 como referência
        $s3Key     = $photo->proxy_url;
        $oldFaceId = $user->rekognition_face_id ?? null;

        $rek    = new \App\Libraries\AwsRekognitionService();
        $result = $rek->indexClientFace($s3Key, (int) $userId, $oldFaceId);

        if ($result['success']) {
            // Salva o novo face_id no perfil do usuário
            $db = \Config\Database::connect();
            $db->table('users')->where('id', $userId)->update([
                'rekognition_face_id' => $result['face_id'],
                'reference_photo_url' => $s3Key,
            ]);
        }

        return $this->response->setJSON([
            'success' => $result['success'],
            'message' => $result['message'],
            'face_id' => $result['face_id'] ?? null,
        ]);
    }

    /**
     * Salva display_name e nicknames do usuário.
     * POST /admin/usuarios/{userId}/update-profile
     */
    public function updateProfile($userId)
    {
        $db = \Config\Database::connect();
        $db->table('users')->where('id', $userId)->update([
            'display_name' => trim($this->request->getPost('display_name') ?? ''),
            'nicknames'    => trim($this->request->getPost('nicknames') ?? ''),
        ]);

        return redirect()->to('/admin/usuarios')->with('message', 'Perfil atualizado com sucesso!');
    }

    /**
     * Promove um usuário ao grupo 'admin'.
     * POST /admin/usuarios/{userId}/promote
     */
    public function promote($userId)
    {
        $provider = auth()->getProvider();
        $user     = $provider->findById($userId);

        if (!$user) {
            return redirect()->to('/admin/usuarios')->with('error', 'Usuário não encontrado.');
        }

        if ($user->inGroup('superadmin')) {
            return redirect()->to('/admin/usuarios')->with('error', 'Superadmins não podem ser alterados aqui.');
        }

        if ($user->inGroup('admin')) {
            return redirect()->to('/admin/usuarios')->with('error', 'Usuário já é administrador.');
        }

        $user->addGroup('admin');

        return redirect()->to('/admin/usuarios')
            ->with('message', '✅ ' . ($user->email) . ' agora é administrador.');
    }

    /**
     * Remove um usuário do grupo 'admin'.
     * POST /admin/usuarios/{userId}/demote
     */
    public function demote($userId)
    {
        $provider = auth()->getProvider();
        $user     = $provider->findById($userId);

        if (!$user) {
            return redirect()->to('/admin/usuarios')->with('error', 'Usuário não encontrado.');
        }

        if ($user->inGroup('superadmin')) {
            return redirect()->to('/admin/usuarios')->with('error', 'Superadmins não podem ser rebaixados aqui.');
        }

        // Impede auto-rebaixamento
        if ($user->id === auth()->id()) {
            return redirect()->to('/admin/usuarios')->with('error', 'Você não pode remover seus próprios privilégios de admin.');
        }

        $user->removeGroup('admin');

        return redirect()->to('/admin/usuarios')
            ->with('message', '⚠️ ' . ($user->email) . ' removido do grupo admin.');
    }
}

