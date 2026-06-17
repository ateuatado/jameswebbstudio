<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><?= esc($title) ?></h2>
    <a href="<?= site_url('admin/packages/new') ?>" class="btn btn-primary">Novo Pacote</a>
</div>

<?php if (session()->getFlashdata('message')): ?>
    <div class="alert alert-success"><?= session()->getFlashdata('message') ?></div>
<?php endif; ?>

<div class="card bg-dark text-white border-secondary">
    <div class="card-body">
        <table class="table table-dark table-striped table-hover mb-0">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome do Pacote</th>
                    <th>Preço Base</th>
                    <th>Fotos Inclusas</th>
                    <th>Valor Foto Extra</th>
                    <th>Status</th>
                    <th class="text-end">Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($packages)): ?>
                    <?php foreach ($packages as $pkg): ?>
                        <tr>
                            <td><?= esc($pkg->id) ?></td>
                            <td>
                                <strong class="d-block mb-1"><?= esc($pkg->name) ?></strong>
                                <span class="badge bg-secondary" style="font-size: 0.65rem; opacity: 0.85;"><?= esc($pkg->category_name ?? 'Geral') ?></span>
                                <?php if (isset($pkg->is_preferred) && $pkg->is_preferred == 1): ?>
                                    <span class="badge text-dark ms-1" style="background: var(--mst-gold, #c5a059); font-size: 0.65rem;"><i class="fas fa-star"></i> Destaque</span>
                                <?php endif; ?>
                            </td>
                            <td>R$ <?= number_format($pkg->base_price, 2, ',', '.') ?></td>
                            <td><?= esc($pkg->included_photos) ?></td>
                            <td>R$ <?= number_format($pkg->extra_photo_price, 2, ',', '.') ?></td>
                            <td>
                                <span class="badge bg-<?= (isset($pkg->is_active) && $pkg->is_active == 1) ? 'success' : 'danger' ?>">
                                    <?= (isset($pkg->is_active) && $pkg->is_active == 1) ? 'Publicado' : 'Rascunho' ?>
                                </span>
                            </td>
                            <td class="text-end">
                                <a href="<?= site_url('admin/packages/' . $pkg->id . '/edit') ?>" class="btn btn-sm btn-outline-light">Editar</a>
                                <form action="<?= site_url('admin/packages/' . $pkg->id) ?>" method="post" class="d-inline" onsubmit="return confirm('Excluir este pacote?');">
                                    <input type="hidden" name="_method" value="DELETE">
                                    <button type="submit" class="btn btn-sm btn-outline-danger">Excluir</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="text-center text-muted">Nenhum pacote cadastrado.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?= $this->endSection() ?>
