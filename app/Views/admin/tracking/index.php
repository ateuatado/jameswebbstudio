<?php $this->extend('admin/layout') ?>
<?php $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>🔗 Links Rastreados</h2>
    <div class="d-flex gap-2">
        <a href="<?= site_url('admin/tracking/dashboard') ?>" class="btn btn-outline-info btn-sm">📊 Dashboard</a>
        <a href="<?= site_url('admin/tracking/create') ?>" class="btn btn-success btn-sm">+ Novo Link</a>
    </div>
</div>

<div class="table-responsive">
    <table class="table table-dark table-hover align-middle">
        <thead>
            <tr>
                <th>Slug / URL Curta</th>
                <th>Destino</th>
                <th>Source</th>
                <th>Medium</th>
                <th>Campanha</th>
                <th class="text-center">Hits</th>
                <th class="text-center">Ativo</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
        <?php if (empty($links)): ?>
            <tr><td colspan="8" class="text-center text-muted py-4">Nenhum link criado ainda.</td></tr>
        <?php else: ?>
            <?php foreach ($links as $link): ?>
            <tr>
                <td>
                    <code><?= site_url('r/' . esc($link->slug)) ?></code>
                    <button class="btn btn-link btn-sm p-0 ms-1 copy-btn" data-url="<?= site_url('r/' . esc($link->slug)) ?>" title="Copiar">
                        📋
                    </button>
                </td>
                <td class="text-truncate" style="max-width:180px">
                    <a href="<?= esc($link->destination_url) ?>" target="_blank" class="text-info"><?= esc($link->destination_url) ?></a>
                </td>
                <td><?= esc($link->utm_source ?? '—') ?></td>
                <td><?= esc($link->utm_medium ?? '—') ?></td>
                <td><?= esc($link->utm_campaign ?? '—') ?></td>
                <td class="text-center">
                    <span class="badge bg-secondary"><?= (int) $link->hit_count ?></span>
                </td>
                <td class="text-center">
                    <form method="post" action="<?= site_url('admin/tracking/' . $link->id . '/toggle') ?>">
                        <?= csrf_field() ?>
                        <button type="submit" class="btn btn-sm <?= $link->is_active ? 'btn-success' : 'btn-outline-secondary' ?>">
                            <?= $link->is_active ? 'Ativo' : 'Inativo' ?>
                        </button>
                    </form>
                </td>
                <td>
                    <a href="<?= site_url('admin/tracking/' . $link->id . '/edit') ?>" class="btn btn-outline-warning btn-sm">Editar</a>
                    <form method="post" action="<?= site_url('admin/tracking/' . $link->id . '/delete') ?>" class="d-inline"
                          onsubmit="return confirm('Excluir este link e todos os seus registros?')">
                        <?= csrf_field() ?>
                        <button type="submit" class="btn btn-outline-danger btn-sm">Excluir</button>
                    </form>
                </td>
            </tr>
            <?php endforeach ?>
        <?php endif ?>
        </tbody>
    </table>
</div>

<?php $this->endSection() ?>

<?php $this->section('scripts') ?>
<script>
document.querySelectorAll('.copy-btn').forEach(btn => {
    btn.addEventListener('click', () => {
        navigator.clipboard.writeText(btn.dataset.url).then(() => {
            btn.textContent = '✅';
            setTimeout(() => btn.textContent = '📋', 1500);
        });
    });
});
</script>
<?php $this->endSection() ?>
