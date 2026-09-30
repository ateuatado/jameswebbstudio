<?php

namespace App\Controllers;

use CodeIgniter\Shield\Entities\User;
use CodeIgniter\Shield\Models\UserIdentityModel;

class GoogleAuthController extends BaseController
{
    private const IDENTITY_TYPE = 'google';

    public function start()
    {
        $clientId = $this->setting('GOOGLE_CLIENT_ID', 'google.clientId');
        $redirectUri = $this->redirectUri();
        if (! $clientId || ! $redirectUri) return redirect()->to(site_url('login'))->with('error', 'O login Google ainda não foi configurado neste ambiente.');
        $returnUrl = config('Auth')->safeReturnUrl($this->request->getGet('redirect'));
        if ($returnUrl) session()->setTempdata('beforeLoginUrl', $returnUrl, 3600);
        $state = bin2hex(random_bytes(32));
        session()->set('google_oauth_state', $state);
        return redirect()->to('https://accounts.google.com/o/oauth2/v2/auth?' . http_build_query([
            'client_id' => $clientId, 'redirect_uri' => $redirectUri, 'response_type' => 'code',
            'scope' => 'openid email profile', 'state' => $state, 'access_type' => 'online', 'prompt' => 'select_account',
        ]));
    }

    public function callback()
    {
        $state = (string) $this->request->getGet('state');
        $expectedState = (string) session()->get('google_oauth_state');
        session()->remove('google_oauth_state');
        if ($state === '' || $expectedState === '' || ! hash_equals($expectedState, $state)) {
            log_message('warning', 'Google OAuth state mismatch: received_length={received}, expected_length={expected}, host={host}, uri={uri}', [
                'received' => strlen($state),
                'expected' => strlen($expectedState),
                'host' => (string) $this->request->getUri()->getHost(),
                'uri' => (string) $this->redirectUri(),
            ]);
            return redirect()->to(site_url('login'))->with('error', 'Não foi possível validar o login Google. Tente novamente.');
        }
        if ($this->request->getGet('error')) return redirect()->to(site_url('login'))->with('error', 'O login Google foi cancelado.');
        $code = (string) $this->request->getGet('code');
        $clientId = $this->setting('GOOGLE_CLIENT_ID', 'google.clientId');
        $clientSecret = $this->setting('GOOGLE_CLIENT_SECRET', 'google.clientSecret');
        if ($code === '' || ! $clientId || ! $clientSecret || ! $this->redirectUri()) return redirect()->to(site_url('login'))->with('error', 'As credenciais do login Google estão incompletas.');

        try {
            $http = service('curlrequest', ['http_errors' => false, 'timeout' => 15]);
            $tokenResponse = $http->post('https://oauth2.googleapis.com/token', ['form_params' => [
                'code' => $code, 'client_id' => $clientId, 'client_secret' => $clientSecret,
                'redirect_uri' => $this->redirectUri(), 'grant_type' => 'authorization_code',
            ]]);
            $tokens = json_decode($tokenResponse->getBody(), true);
            if ($tokenResponse->getStatusCode() >= 400 || empty($tokens['access_token'])) throw new \RuntimeException('token exchange failed');
            $profileResponse = $http->get('https://openidconnect.googleapis.com/v1/userinfo', ['headers' => ['Authorization' => 'Bearer ' . $tokens['access_token']]]);
            $profile = json_decode($profileResponse->getBody(), true);
            if ($profileResponse->getStatusCode() >= 400 || empty($profile['sub']) || empty($profile['email']) || ($profile['email_verified'] ?? false) !== true) throw new \RuntimeException('invalid Google profile');
            auth()->login($this->findOrCreateUser($profile), true);
            return redirect()->to(config('Auth')->loginRedirect());
        } catch (\Throwable $e) {
            log_message('error', 'Google OAuth failed: {message}', ['message' => $e->getMessage()]);
            return redirect()->to(site_url('login'))->with('error', 'Não foi possível concluir o login Google.');
        }
    }

    private function findOrCreateUser(array $profile): User
    {
        $identityModel = model(UserIdentityModel::class);
        $identity = $identityModel->where('type', self::IDENTITY_TYPE)->where('secret', $profile['sub'])->first();
        $users = model(setting('Auth.userProvider'));
        if ($identity) return $users->findById($identity->user_id);
        $user = $users->findByCredentials(['email' => strtolower($profile['email'])]);
        if (! $user) {
            $base = trim((string) preg_replace('/[^a-z0-9]+/i', '-', strtolower((string) ($profile['name'] ?? strstr($profile['email'], '@', true)))), '-');
            $base = $base ?: 'google-user'; $username = $base; $suffix = 1;
            while ($users->where('username', $username)->first()) $username = $base . '-' . $suffix++;
            $user = $users->createNewUser(['username' => $username, 'active' => true]);
            $users->save($user); $user = $users->findById($users->getInsertID()); $users->addToDefaultGroup($user);
        }
        $identityModel->insert([
            'user_id' => $user->id, 'type' => self::IDENTITY_TYPE,
            'name' => $profile['name'] ?? $profile['email'], 'secret' => $profile['sub'],
            'extra' => json_encode(['email' => strtolower($profile['email']), 'picture' => $profile['picture'] ?? null], JSON_UNESCAPED_SLASHES),
        ]);
        return $user;
    }

    private function redirectUri(): ?string
    {
        return $this->setting('GOOGLE_REDIRECT_URI', 'google.redirectUri') ?: site_url('login/google/callback');
    }

    private function setting(string $primary, string $fallback): ?string
    {
        $value = env($primary) ?: env($fallback);
        return $value ? trim((string) $value) : null;
    }
}
