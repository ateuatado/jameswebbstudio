<?= $this->extend(config('Auth')->views['layout']) ?>

<?= $this->section('title') ?>Entrar — James Webb Studio<?= $this->endSection() ?>

<?= $this->section('main') ?>

<div class="container d-flex justify-content-center p-5">
    <div class="card col-12 col-md-5 shadow-sm">
        <div class="card-body">
            <h5 class="card-title mb-4">Entrar no Portal</h5>

            <?php if (session('error') !== null) : ?>
                <div class="alert alert-danger" role="alert"><?= esc(session('error')) ?></div>
            <?php elseif (session('errors') !== null) : ?>
                <div class="alert alert-danger" role="alert">
                    <?php if (is_array(session('errors'))) : ?>
                        <?php foreach (session('errors') as $error) : ?>
                            <?= esc($error) ?><br>
                        <?php endforeach ?>
                    <?php else : ?>
                        <?= esc(session('errors')) ?>
                    <?php endif ?>
                </div>
            <?php endif ?>

            <?php if (session('message') !== null) : ?>
                <div class="alert alert-success" role="alert"><?= esc(session('message')) ?></div>
            <?php endif ?>

            <form action="<?= url_to('login') ?>" method="post">
                <?= csrf_field() ?>

                <!-- E-mail -->
                <div class="form-floating mb-3">
                    <input type="email" class="form-control" id="loginEmail" name="email"
                           inputmode="email" autocomplete="email" placeholder="E-mail"
                           value="<?= old('email') ?>" required>
                    <label for="loginEmail">E-mail</label>
                </div>

                <!-- Senha com toggle -->
                <div class="form-floating mb-3 position-relative">
                    <input type="password" class="form-control pe-5" id="loginPassword" name="password"
                           autocomplete="current-password" placeholder="Senha" required>
                    <label for="loginPassword">Senha</label>
                    <button type="button" class="btn btn-link position-absolute top-50 end-0 translate-middle-y me-2 p-0 text-secondary"
                            onclick="togglePwd('loginPassword', this)" title="Mostrar/ocultar senha"
                            style="z-index:10;border:none;background:none;font-size:1.1rem;">
                        👁️
                    </button>
                </div>

                <!-- Lembrar-me -->
                <?php if (setting('Auth.sessionConfig')['allowRemembering']): ?>
                    <div class="form-check mb-3">
                        <label class="form-check-label">
                            <input type="checkbox" name="remember" class="form-check-input"
                                   <?php if (old('remember')): ?> checked<?php endif ?>>
                            Lembrar-me neste dispositivo
                        </label>
                    </div>
                <?php endif; ?>

                <div class="d-grid col-12 col-md-8 mx-auto mt-3 mb-2">
                    <button type="submit" class="btn btn-primary btn-block">Entrar</button>
                </div>

                <?php if (setting('Auth.allowMagicLinkLogins')) : ?>
                    <p class="text-center small">
                        Esqueceu a senha? <a href="<?= url_to('magic-link') ?>">Acesse por link mágico</a>
                    </p>
                <?php endif ?>

                <?php if (setting('Auth.allowRegistration')) : ?>
                    <p class="text-center small">
                        Não tem conta? <a href="<?= url_to('register') ?>">Criar conta</a>
                    </p>
                <?php endif ?>

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
