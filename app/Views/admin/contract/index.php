<?= $this->extend('admin/layout') ?>
<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 text-gold mb-0">Contrato — Cláusulas</h1>
    <div class="d-flex gap-2">
        <a href="<?= site_url('admin/contract-sections/preview') ?>" target="_blank" class="btn btn-outline-light btn-sm" style="font-size:.75rem;letter-spacing:.08em;">📄 Pré-visualizar PDF</a>
        <a href="<?= site_url('admin/contract-sections/create') ?>" class="btn btn-terroso btn-sm">+ Nova Cláusula</a>
    </div>
</div>

<?php if (session()->has('message')): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert" style="font-size:.85rem;">
        <?= session('message') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<p class="text-muted mb-4" style="font-size:.85rem;">
    Gerencie as cláusulas do contrato de prestação de serviços fotográficos. Use placeholders como <code>{nome_cliente}</code> para dados dinâmicos.
</p>

<?php if (!empty($sections)): ?>
<div class="card bg-dark border-secondary">
    <div class="card-body p-0">
        <table class="table table-dark table-hover mb-0" style="font-size:.85rem;">
            <thead>
                <tr style="font-size:.7rem;letter-spacing:.1em;text-transform:uppercase;color:rgba(255,255,255,.4);">
                    <th style="width:60px;">Ordem</th>
                    <th>Título</th>
                    <th style="width:50%;">Conteúdo (prévia)</th>
                    <th style="width:80px;">Status</th>
                    <th style="width:120px;">Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($sections as $s): ?>
                <tr style="<?= !$s->is_active ? 'opacity:.4;' : '' ?>">
                    <td class="text-center text-muted"><?= $s->display_order ?></td>
                    <td><strong><?= esc($s->title) ?></strong></td>
                    <td>
                        <span style="color:rgba(255,255,255,.45);font-size:.8rem;">
                            <?= esc(mb_substr($s->content, 0, 120)) ?><?= mb_strlen($s->content) > 120 ? '…' : '' ?>
                        </span>
                    </td>
                    <td>
                        <?php if ($s->is_active): ?>
                            <span class="badge bg-success" style="font-size:.65rem;">Ativo</span>
                        <?php else: ?>
                            <span class="badge bg-secondary" style="font-size:.65rem;">Inativo</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <a href="<?= site_url('admin/contract-sections/' . $s->id . '/edit') ?>" class="btn btn-outline-light btn-sm" style="font-size:.7rem;">Editar</a>
                        <form action="<?= site_url('admin/contract-sections/' . $s->id . '/delete') ?>" method="post" class="d-inline" onsubmit="return confirm('Remover esta cláusula?')">
                            <?= csrf_field() ?>
                            <button class="btn btn-outline-danger btn-sm" style="font-size:.7rem;">✕</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php else: ?>
<div class="text-center text-muted py-5">
    <p>Nenhuma cláusula criada ainda.</p>
    <a href="<?= site_url('admin/contract-sections/create') ?>" class="btn btn-terroso">Criar primeira cláusula</a>
</div>
<?php endif; ?>

<?= $this->endSection() ?>
