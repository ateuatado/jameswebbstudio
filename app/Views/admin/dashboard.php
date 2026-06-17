<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-12">
        <h2 class="mb-4">Visão Geral</h2>
        <div class="card bg-dark text-white border-secondary">
            <div class="card-body">
                <h5 class="card-title text-uppercase fw-bold text-info">Bem-vindo ao Painel JWS</h5>
                <p class="card-text text-light">Aqui você constrói as páginas e gerencia as estrelas e ensaios do seu estúdio fotográfico.</p>
                <hr class="border-secondary">
                <a href="<?= site_url('admin/heroes') ?>" class="btn btn-outline-light">Gerenciar Estrelas</a>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
