<div class="card bg-dark text-white border-secondary mb-4">
    <div class="card-header border-secondary text-info fw-bold text-uppercase d-flex justify-content-between align-items-center">
        <span><?= esc($title) ?>: <?= esc($hero['name']) ?></span>
        <div class="btn-group btn-group-sm">
            <a href="<?= site_url('admin/heroes/' . $hero['id'] . '/edit') ?>" class="btn <?= $active === 'details' ? 'btn-info' : 'btn-outline-info' ?>">Dados</a>
            <a href="<?= site_url('admin/heroes/' . $hero['id'] . '/photos') ?>" class="btn <?= $active === 'photos' ? 'btn-info' : 'btn-outline-info' ?>">Galeria</a>
            <a href="<?= site_url('admin/heroes/' . $hero['id'] . '/cta') ?>" class="btn <?= $active === 'cta' ? 'btn-warning' : 'btn-outline-warning' ?>">CTA</a>
            <a href="<?= site_url('admin/heroes/' . $hero['id'] . '/schedule') ?>" class="btn <?= $active === 'schedule' ? 'btn-success' : 'btn-outline-success' ?>">Agenda</a>
            <a href="<?= site_url($hero['slug']) ?>?agenda=1" target="_blank" class="btn btn-outline-light">Ver Link Público</a>
        </div>
    </div>
</div>
