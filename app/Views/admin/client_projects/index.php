<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><?= esc($title) ?></h2>
    <a href="<?= site_url('admin/client-projects/new') ?>" class="btn btn-primary">Novo Projeto</a>
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
                    <th>Ensaio / Evento</th>
                    <th>Cliente</th>
                    <th>Pacote</th>
                    <th>Status</th>
                    <th class="text-end">Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($projects)): ?>
                    <?php foreach ($projects as $proj): ?>
                        <tr>
                            <td><?= esc($proj->id) ?></td>
                            <td><strong><?= esc($proj->name ?? 'Ensaio sem nome') ?></strong></td>
                            <td><?= esc($proj->user_name) ?></td>
                            <td><?= esc($proj->package_name) ?></td>
                            <td>
                                <span class="badge bg-<?= $proj->status === 'completed' ? 'success' : ($proj->status === 'paid' ? 'info' : 'warning') ?>">
                                    <?= esc(strtoupper($proj->status)) ?>
                                </span>
                            </td>
                            <td class="text-end">
                                <a href="<?= site_url('admin/client-projects/' . $proj->id . '/download-bat') ?>" class="btn btn-sm btn-outline-warning" title="Baixar Script de Conexão (.bat) para o Estúdio"><i class="fas fa-file-download me-1"></i> Conectar</a>
                                <a href="<?= site_url('admin/client-projects/' . $proj->id . '/photos') ?>" class="btn btn-sm btn-outline-info">Ver Fotos</a>
                                <a href="<?= site_url('admin/client-projects/' . $proj->id . '/edit') ?>" class="btn btn-sm btn-outline-light">Editar</a>
                                <form action="<?= site_url('admin/client-projects/' . $proj->id) ?>" method="post" class="d-inline" onsubmit="return confirm('Excluir este projeto?');">
                                    <input type="hidden" name="_method" value="DELETE">
                                    <button type="submit" class="btn btn-sm btn-outline-danger">Excluir</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="text-center text-muted">Nenhum projeto cadastrado.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?= $this->endSection() ?>
