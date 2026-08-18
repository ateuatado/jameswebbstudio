<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-12">
        <h2 class="mb-4">Visão Geral</h2>
        <div class="card bg-dark text-white border-secondary mb-3">
            <div class="card-body">
                <h5 class="card-title text-uppercase fw-bold text-info">Bem-vindo ao Painel JWS</h5>
                <p class="card-text text-light">Aqui você constrói as páginas e gerencia as estrelas e ensaios do seu estúdio fotográfico.</p>
                <hr class="border-secondary">
                <a href="<?= site_url('admin/heroes') ?>" class="btn btn-outline-light">Gerenciar Estrelas</a>
            </div>
        </div>

        <div class="row g-3">
            <div class="col-md-4">
                <div class="card bg-dark border-warning h-100">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title text-warning">🔗 Rastreamento de Visitas</h5>
                        <p class="card-text text-muted small">Crie links curtos rastreados para suas redes sociais e veja de onde vêm seus visitantes.</p>
                        <div class="mt-auto d-flex gap-2">
                            <a href="<?= site_url('admin/tracking') ?>" class="btn btn-outline-warning btn-sm">Gerenciar Links</a>
                            <a href="<?= site_url('admin/tracking/dashboard') ?>" class="btn btn-warning btn-sm">📊 Dashboard</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-dark border-info h-100">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title text-info">🛒 Pedidos</h5>
                        <p class="card-text text-muted small">Visualize e gerencie todos os pedidos realizados pelo site.</p>
                        <div class="mt-auto">
                            <a href="<?= site_url('admin/orders') ?>" class="btn btn-outline-info btn-sm">Ver Pedidos</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-dark border-secondary h-100">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title text-light">🎟️ Cupons</h5>
                        <p class="card-text text-muted small">Gere e distribua cupons de desconto para campanhas e portfólio.</p>
                        <div class="mt-auto">
                            <a href="<?= site_url('admin/coupons') ?>" class="btn btn-outline-secondary btn-sm">Gerenciar Cupons</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

