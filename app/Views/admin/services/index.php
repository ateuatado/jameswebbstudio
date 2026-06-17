<?= $this->extend('admin/layout') ?>

<?= $this->section('styles') ?>
<style>
    .phase-section { margin-bottom: 32px; }
    .phase-header {
        display: flex; align-items: center; gap: 12px;
        padding: 10px 16px; margin-bottom: 12px;
        background: rgba(255,255,255,.03);
        border-left: 3px solid #C5A059;
    }
    .phase-header h5 {
        margin: 0; font-size: .85rem; font-weight: 600;
        letter-spacing: .08em; text-transform: uppercase; color: #C5A059;
    }
    .phase-header .badge { font-size: .65rem; }
    .service-row {
        display: grid;
        grid-template-columns: 1fr 2fr 100px 80px;
        gap: 12px; align-items: center;
        padding: 10px 16px;
        border-bottom: 1px solid rgba(255,255,255,.05);
        transition: background .15s;
    }
    .service-row:hover { background: rgba(255,255,255,.03); }
    .service-name { font-weight: 500; color: #fff; font-size: .9rem; }
    .service-desc { color: rgba(255,255,255,.45); font-size: .8rem; }
    .service-price { color: #66bb6a; font-weight: 600; font-size: .85rem; text-align: right; }
    .service-actions { display: flex; gap: 6px; justify-content: flex-end; }
    .empty-phase { color: rgba(255,255,255,.2); font-size: .8rem; font-style: italic; padding: 8px 16px; }
    @media (max-width: 768px) {
        .service-row { grid-template-columns: 1fr; gap: 4px; }
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="text-info fw-bold mb-0">Serviços</h2>
    <a href="<?= site_url('admin/services/new') ?>" class="btn btn-primary">+ Novo Serviço</a>
</div>

<p class="text-muted small mb-4">Cadastre serviços individuais que compõem seus pacotes. Cada serviço pertence a uma fase do ensaio.</p>

<?php foreach ($grouped as $phase => $group): ?>
<div class="phase-section">
    <div class="phase-header">
        <h5><?= esc($group['label']) ?></h5>
        <span class="badge bg-secondary"><?= count($group['services']) ?></span>
    </div>

    <?php if (!empty($group['services'])): ?>
        <!-- Cabeçalho da tabela -->
        <div class="service-row" style="border-bottom: 1px solid rgba(255,255,255,.1);">
            <small class="text-muted text-uppercase" style="font-size:.6rem;letter-spacing:.12em;">Serviço</small>
            <small class="text-muted text-uppercase" style="font-size:.6rem;letter-spacing:.12em;">Descrição</small>
            <small class="text-muted text-uppercase text-end" style="font-size:.6rem;letter-spacing:.12em;">Valor</small>
            <small class="text-muted text-uppercase text-end" style="font-size:.6rem;letter-spacing:.12em;">Ações</small>
        </div>
        <?php foreach ($group['services'] as $s): ?>
        <div class="service-row">
            <div>
                <span class="service-name"><?= esc($s->name) ?></span>
                <?php if (!$s->is_active): ?>
                    <span class="badge bg-secondary ms-2" style="font-size:.55rem;">Inativo</span>
                <?php endif; ?>
            </div>
            <div class="service-desc"><?= esc($s->description) ?: '—' ?></div>
            <div class="service-price">
                <?= $s->price > 0 ? 'R$ ' . number_format($s->price, 0, ',', '.') : 'Incluso' ?>
            </div>
            <div class="service-actions">
                <a href="<?= site_url('admin/services/' . $s->id . '/edit') ?>" class="btn btn-sm btn-outline-light">Editar</a>
                <form action="<?= site_url('admin/services/' . $s->id . '/delete') ?>" method="post"
                      onsubmit="return confirm('Excluir este serviço?')">
                    <button type="submit" class="btn btn-sm btn-outline-danger">×</button>
                </form>
            </div>
        </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p class="empty-phase">Nenhum serviço cadastrado nesta fase.</p>
    <?php endif; ?>
</div>
<?php endforeach; ?>

<?= $this->endSection() ?>
