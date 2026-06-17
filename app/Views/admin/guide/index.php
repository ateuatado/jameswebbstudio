<?= $this->extend('admin/layout') ?>
<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 text-gold mb-0">Guia Pré-Ensaio</h1>
    <div class="d-flex gap-2">
        <a href="<?= site_url('admin/guide-sections/preview') ?>" target="_blank" class="btn btn-outline-light btn-sm" style="font-size:.75rem;letter-spacing:.08em;">📄 Pré-visualizar PDF</a>
        <a href="<?= site_url('admin/guide-sections/create') ?>" class="btn btn-terroso btn-sm">+ Nova Seção</a>
    </div>
</div>

<?php if (session()->has('message')): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert" style="font-size:.85rem;">
        <?= session('message') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<p class="text-muted mb-4" style="font-size:.85rem;">
    Organize as seções do guia que o cliente recebe antes do ensaio. Seções universais aparecem em todos os PDFs.
    Seções vinculadas a um nicho só aparecem quando o ensaio é daquele tipo.
</p>

<?php foreach ($grouped as $catKey => $sections): ?>
<div class="card bg-dark border-secondary mb-4">
    <div class="card-header d-flex justify-content-between align-items-center" style="border-bottom:1px solid rgba(197,160,89,.15);">
        <h5 class="mb-0" style="font-size:.85rem;letter-spacing:.1em;text-transform:uppercase;color:<?= $catKey === '' ? '#C5A059' : 'rgba(255,255,255,.6)' ?>;">
            <?= $catKey === '' ? '🌐 Universal (todos os ensaios)' : '🎯 ' . esc($catMap[$catKey] ?? 'Nicho') ?>
        </h5>
        <span class="badge bg-secondary"><?= count($sections) ?> seções</span>
    </div>
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
                        <a href="<?= site_url('admin/guide-sections/' . $s->id . '/edit') ?>" class="btn btn-outline-light btn-sm" style="font-size:.7rem;">Editar</a>
                        <form action="<?= site_url('admin/guide-sections/' . $s->id . '/delete') ?>" method="post" class="d-inline" onsubmit="return confirm('Remover esta seção?')">
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
<?php endforeach; ?>

<?php if (empty($grouped)): ?>
<div class="text-center text-muted py-5">
    <p>Nenhuma seção criada ainda.</p>
    <a href="<?= site_url('admin/guide-sections/create') ?>" class="btn btn-terroso">Criar primeira seção</a>
</div>
<?php endif; ?>

<?= $this->endSection() ?>
