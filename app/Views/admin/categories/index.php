<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><?= esc($title) ?></h2>
    <a href="<?= site_url('admin/categories/new') ?>" class="btn btn-primary">Novo Nicho / Categoria</a>
</div>

<div class="card bg-dark text-white border-secondary">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-dark table-striped table-hover mb-0">
                <thead>
                    <tr>
                        <th style="width: 80px;">ID</th>
                        <th>Nome do Nicho</th>
                        <th>Slug URL</th>
                        <th>Descrição</th>
                        <th style="width: 150px;">Status</th>
                        <th class="text-end" style="width: 200px;">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($categories)): ?>
                        <?php foreach ($categories as $cat): ?>
                            <tr>
                                <td><?= esc($cat->id) ?></td>
                                <td><strong><?= esc($cat->name) ?></strong></td>
                                <td><code class="text-white-50"><?= esc($cat->slug) ?></code></td>
                                <td class="text-muted small">
                                    <?= $cat->description ? esc(character_limiter($cat->description, 60)) : '<em>Sem descrição cadastrada</em>' ?>
                                </td>
                                <td>
                                    <span class="badge bg-<?= $cat->is_active == 1 ? 'success' : 'danger' ?>">
                                        <?= $cat->is_active == 1 ? 'Publicado' : 'Rascunho' ?>
                                    </span>
                                </td>
                                <td class="text-end">
                                    <a href="<?= site_url('admin/categories/' . $cat->id . '/edit') ?>" class="btn btn-sm btn-outline-light">Editar</a>
                                    <form action="<?= site_url('admin/categories/' . $cat->id) ?>" method="post" class="d-inline" onsubmit="return confirm('Excluir este nicho? Todos os pacotes e landing pages associados a ele ficarão sem categoria vinculada.');">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <button type="submit" class="btn btn-sm btn-outline-danger">Excluir</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">Nenhum nicho de fotografia cadastrado ainda.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
