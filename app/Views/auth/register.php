<?= $this->extend(config('Auth')->views['layout']) ?>

<?= $this->section('title') ?>Criar Conta — James Webb Studio<?= $this->endSection() ?>

<?= $this->section('main') ?>

<div class="container d-flex justify-content-center p-5">
    <div class="card col-12 col-md-5 shadow-sm">
        <div class="card-body">
            <h5 class="card-title mb-4">Criar conta</h5>

            <?php
            // Detecta se o erro é de e-mail duplicado e mostra mensagem amigável
            $errors = session('errors') ?? [];
            $emailJaExiste = false;
            if (is_array($errors)) {
                foreach ($errors as $err) {
                    if (stripos($err, 'unique') !== false || stripos($err, 'já está cadastrado') !== false) {
                        $emailJaExiste = true;
                        break;
                    }
                }
            }
            ?>

            <?php if ($emailJaExiste) : ?>
                <div class="alert alert-warning" role="alert">
                    <strong>Este e-mail já possui uma conta no sistema.</strong><br>
                    Se você recebeu um link de cortesia ou fez uma compra, sua conta já foi criada automaticamente.<br>
                    <a href="<?= url_to('login') ?>" class="btn btn-sm btn-primary mt-2">Entrar com este e-mail →</a>
                </div>
            <?php elseif (session('error') !== null) : ?>
                <div class="alert alert-danger" role="alert"><?= esc(session('error')) ?></div>
            <?php elseif (!empty($errors)) : ?>
                <div class="alert alert-danger" role="alert">
                    <?php foreach ($errors as $error) : ?>
                        <?= esc($error) ?><br>
                    <?php endforeach ?>
                </div>
            <?php endif ?>

            <form action="<?= url_to('register') ?>" method="post">
                <?= csrf_field() ?>

                <!-- E-mail -->
                <div class="form-floating mb-2">
                    <input type="email" class="form-control" id="regEmail" name="email"
                           inputmode="email" autocomplete="email" placeholder="E-mail"
                           value="<?= old('email') ?>" required>
                    <label for="regEmail">E-mail</label>
                </div>

                <!-- Nome de usuário -->
                <div class="form-floating mb-4">
                    <input type="text" class="form-control" id="regUsername" name="username"
                           inputmode="text" autocomplete="username" placeholder="Nome de usuário"
                           value="<?= old('username') ?>" required>
                    <label for="regUsername">Nome de usuário</label>
                </div>

                <!-- Senha com toggle -->
                <div class="form-floating mb-2 position-relative">
                    <input type="password" class="form-control pe-5" id="regPassword" name="password"
                           autocomplete="new-password" placeholder="Senha" required>
                    <label for="regPassword">Senha</label>
                    <button type="button" class="btn btn-link position-absolute top-50 end-0 translate-middle-y me-2 p-0 text-secondary"
                            onclick="togglePwd('regPassword', this)" title="Mostrar/ocultar senha"
                            style="z-index:10;border:none;background:none;font-size:1.1rem;">
                        👁️
                    </button>
                </div>

                <!-- Confirmar senha com toggle -->
                <div class="form-floating mb-5 position-relative">
                    <input type="password" class="form-control pe-5" id="regPasswordConfirm" name="password_confirm"
                           autocomplete="new-password" placeholder="Confirme a senha" required>
                    <label for="regPasswordConfirm">Confirme a senha</label>
                    <button type="button" class="btn btn-link position-absolute top-50 end-0 translate-middle-y me-2 p-0 text-secondary"
                            onclick="togglePwd('regPasswordConfirm', this)" title="Mostrar/ocultar senha"
                            style="z-index:10;border:none;background:none;font-size:1.1rem;">
                        👁️
                    </button>
                </div>

                <div class="d-grid col-12 col-md-8 mx-auto m-3">
                    <button type="submit" class="btn btn-primary btn-block">Criar conta</button>
                </div>

                <p class="text-center small">
                    Já tem uma conta? <a href="<?= url_to('login') ?>">Entrar</a>
                </p>

            </form>
        </div>
    </div>
</div>

<script>
function togglePwd(inputId, btn) {
    const inp = document.getElementById(inputId);
    if (inp.type === 'password') {
        inp.type = 'text';
        btn.textContent = '🙈';
        btn.title = 'Ocultar senha';
    } else {
        inp.type = 'password';
        btn.textContent = '👁️';
        btn.title = 'Mostrar senha';
    }
}
</script>

<?= $this->endSection() ?>
