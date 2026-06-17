<!DOCTYPE html>
<html lang="pt-br" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel Admin - JWS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #121212; color: #f8f9fa; }
    </style>
    <?= $this->renderSection('styles') ?>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-black border-bottom border-dark shadow-sm">
        <div class="container">
            <a class="navbar-brand text-uppercase fw-bold" href="<?= site_url('admin') ?>">JWS Admin</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navAdmin">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navAdmin">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="<?= site_url('admin/heroes') ?>">Estrelas</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= site_url('admin/intentions') ?>">Leads</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-warning fw-bold" href="<?= site_url('admin/orders') ?>">Pedidos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= site_url('admin/bookings') ?>">Agendas</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-info" href="<?= site_url('admin/categories') ?>">Nichos/Categorias</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-info" href="<?= site_url('admin/packages') ?>">Pacotes</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-info" href="<?= site_url('admin/services') ?>">Serviços</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-info" href="<?= site_url('admin/guide-sections') ?>">Guia Pré-Ensaio</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-info" href="<?= site_url('admin/contract-sections') ?>">Contrato</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-info" href="<?= site_url('admin/client-projects') ?>">Projetos de Clientes</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-info" href="<?= site_url('admin/studio') ?>">Estúdio</a>
                    </li>
                </ul>
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="<?= site_url('/') ?>" target="_blank">Ver Site</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-danger" href="<?= site_url('logout') ?>">Sair</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <?php if (session()->has('message')) : ?>
            <div class="alert alert-success"><?= session('message') ?></div>
        <?php endif ?>
        <?php if (session()->has('error')) : ?>
            <div class="alert alert-danger"><?= session('error') ?></div>
        <?php endif ?>

        <?= $this->renderSection('content') ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <?= $this->renderSection('scripts') ?>
</body>
</html>
