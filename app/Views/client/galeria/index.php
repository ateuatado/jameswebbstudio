<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="container" style="margin-top: 100px; min-height: 70vh;">
    <h2 class="text-gold text-uppercase mb-4 brand-font text-center">Minhas Galerias</h2>
    
    <div class="row row-cols-1 row-cols-md-2 g-4 justify-content-center">
        <?php if (!empty($projects)): ?>
            <?php foreach ($projects as $proj): ?>
                <div class="col">
                    <div class="card h-100 bg-black border border-secondary shadow-lg hover-zoom" style="transition: transform 0.3s ease;">
                        <div class="card-body p-4 text-center">
                            <h3 class="brand-font text-white mb-3"><?= esc($proj->name ?? 'Projeto #' . $proj->id) ?></h3>
                            <p class="text-uppercase small mb-2" style="color: var(--mst-gold); letter-spacing: 2px;">
                                Status: <?= esc($proj->status) ?>
                            </p>
                            <p class="text-muted small">
                                Acesse para selecionar ou visualizar suas fotos.
                            </p>
                        </div>
                        <div class="card-footer border-0 bg-transparent text-center pb-4">
                            <a href="<?= site_url('client/galeria/' . $proj->id) ?>" class="btn btn-terroso px-5">Acessar Galeria</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12 text-center text-muted mt-5">
                <p>Nenhuma galeria disponível no momento.</p>
            </div>
        <?php endif; ?>
    </div>
</div>
<?= $this->endSection() ?>
