<?php

declare(strict_types=1);

/**
 * Traduções pt-BR do Shield — James Webb Studio
 * Override do vendor: app/Language tem prioridade.
 */

return [
    // Exceptions
    'unknownAuthenticator'  => '{0} não é um autenticador válido.',
    'unknownUserProvider'   => 'Não foi possível determinar o provedor de usuário.',
    'invalidUser'           => 'Não foi possível localizar o usuário especificado.',
    'bannedUser'            => 'Não é possível fazer login — sua conta está suspensa.',
    'logOutBannedUser'      => 'Você foi desconectado porque sua conta foi suspensa.',
    'badAttempt'            => 'Não foi possível fazer login. Verifique suas credenciais.',
    'noPassword'            => 'Não é possível validar um usuário sem senha.',
    'invalidPassword'       => 'Senha incorreta. Tente novamente.',
    'noToken'               => 'Toda requisição deve incluir um token no cabeçalho {0}.',
    'badToken'              => 'O token de acesso é inválido.',
    'oldToken'              => 'O token de acesso expirou.',
    'noUserEntity'          => 'A entidade de usuário deve ser fornecida para validação de senha.',
    'invalidEmail'          => 'Não foi possível verificar se o e-mail "{0}" corresponde ao e-mail cadastrado.',
    'unableSendEmailToUser' => 'Houve um problema ao enviar o e-mail para {0}. Tente novamente ou entre em contato com o estúdio.',
    'throttled'             => 'Muitas tentativas. Aguarde {0} segundos e tente novamente.',
    'notEnoughPrivilege'    => 'Você não tem permissão para realizar esta ação.',

    // JWT
    'invalidJWT'     => 'Token inválido.',
    'expiredJWT'     => 'Token expirado.',
    'beforeValidJWT' => 'Token ainda não disponível.',

    // Campos
    'email'           => 'E-mail',
    'username'        => 'Nome de usuário',
    'password'        => 'Senha',
    'passwordConfirm' => 'Confirme a senha',
    'haveAccount'     => 'Já tem uma conta?',
    'token'           => 'Token',

    // Botões
    'confirm' => 'Confirmar',
    'send'    => 'Enviar',

    // Registro
    'register'         => 'Criar conta',
    'registerDisabled' => 'O cadastro não está disponível no momento.',
    'registerSuccess'  => 'Bem-vindo!',

    // Login
    'login'              => 'Entrar',
    'needAccount'        => 'Ainda não tem uma conta?',
    'rememberMe'         => 'Lembrar de mim',
    'forgotPassword'     => 'Esqueceu sua senha?',
    'useMagicLink'       => 'Entrar com link por e-mail',
    'magicLinkSubject'   => 'Seu link de acesso — James Webb Studio',
    'magicTokenNotFound' => 'Não foi possível verificar o link. Solicite um novo.',
    'magicLinkExpired'   => 'O link expirou. Por favor, solicite um novo.',
    'checkYourEmail'     => 'Verifique seu e-mail!',
    'magicLinkDetails'   => 'Enviamos um link de acesso para o seu e-mail. Ele é válido por {0} minutos.',
    'magicLinkDisabled'  => 'O login por link não está disponível no momento.',
    'successLogout'      => 'Você saiu com sucesso.',
    'backToLogin'        => 'Voltar ao login',

    // Senhas
    'errorPasswordLength'       => 'A senha deve ter pelo menos {0, number} caracteres.',
    'suggestPasswordLength'     => 'Frases longas (até 255 caracteres) são mais seguras e fáceis de lembrar.',
    'errorPasswordCommon'       => 'Esta senha é muito comum. Escolha outra.',
    'suggestPasswordCommon'     => 'Sua senha foi verificada contra uma lista de mais de 65 mil senhas vulneráveis.',
    'errorPasswordPersonal'     => 'A senha não pode conter suas informações pessoais.',
    'suggestPasswordPersonal'   => 'Evite usar variações do seu e-mail ou nome como senha.',
    'errorPasswordTooSimilar'   => 'A senha é muito parecida com o nome de usuário.',
    'suggestPasswordTooSimilar' => 'Não use partes do seu nome de usuário na senha.',
    'errorPasswordPwned'        => 'A senha "{0}" foi exposta em vazamentos de dados e aparece {1, number} vezes em {2}.',
    'suggestPasswordPwned'      => '"{0}" nunca deve ser usada como senha. Se estiver usando em algum lugar, altere imediatamente.',
    'errorPasswordEmpty'        => 'A senha é obrigatória.',
    'errorPasswordTooLongBytes' => 'A senha não pode ultrapassar {param} bytes.',
    'passwordChangeSuccess'     => 'Senha alterada com sucesso.',
    'userDoesNotExist'          => 'Não foi possível alterar a senha. Usuário não encontrado.',
    'resetTokenExpired'         => 'O link de redefinição de senha expirou. Solicite um novo.',

    // E-mails globais
    'emailInfo'      => 'Informações sobre o acesso:',
    'emailIpAddress' => 'Endereço IP:',
    'emailDevice'    => 'Dispositivo:',
    'emailDate'      => 'Data:',

    // 2FA
    'email2FATitle'       => 'Verificação em duas etapas',
    'confirmEmailAddress' => 'Confirme seu endereço de e-mail.',
    'emailEnterCode'      => 'Confirme seu e-mail',
    'emailConfirmCode'    => 'Digite o código de 6 dígitos enviado para o seu e-mail.',
    'email2FASubject'     => 'Seu código de autenticação — James Webb Studio',
    'email2FAMailBody'    => 'Seu código de autenticação é:',
    'invalid2FAToken'     => 'Código incorreto. Tente novamente.',
    'need2FA'             => 'É necessário concluir a verificação em duas etapas.',
    'needVerification'    => 'Verifique seu e-mail para ativar sua conta.',

    // Ativação
    'emailActivateTitle'    => 'Ativação de conta',
    'emailActivateBody'     => 'Enviamos um código para confirmar seu endereço de e-mail. Copie e cole abaixo.',
    'emailActivateSubject'  => 'Ative sua conta — James Webb Studio',
    'emailActivateMailBody' => 'Use o código abaixo para ativar sua conta.',
    'invalidActivateToken'  => 'Código incorreto.',
    'needActivate'          => 'Confirme o código enviado para seu e-mail para concluir o cadastro.',
    'activationBlocked'     => 'Ative sua conta antes de fazer o login.',

    // Grupos
    'unknownGroup' => '{0} não é um grupo válido.',
    'missingTitle' => 'Os grupos precisam ter um título.',

    // Permissões
    'unknownPermission' => '{0} não é uma permissão válida.',
];
