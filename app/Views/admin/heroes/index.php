<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="text-info fw-bold text-uppercase">Estrelas</h2>
    <a href="<?= site_url('admin/heroes/new') ?>" class="btn btn-primary">Cadastrar Novo</a>
</div>

<?php if (session()->has('message')): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?= session('message') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<div class="card bg-dark text-white border-secondary">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-dark table-hover align-middle">
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>Esporte</th>
                        <th>URL</th>
                        <th>Status</th>
                        <th class="text-end">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($heroes)): ?>
                        <?php foreach ($heroes as $hero): ?>
                        <tr>
                            <td class="fw-bold">
                                <?= esc($hero['name']) ?>
                                <span class="badge bg-secondary d-block mt-1" style="font-size: 0.65rem; font-weight: normal; width: max-content; opacity: 0.85;"><?= esc($hero['category_name'] ?? 'Geral') ?></span>
                            </td>
                            <td><?= esc($hero['sport'] ?: 'Nenhum') ?></td>
                            <td>
                                <?php if ($hero['published']): ?>
                                    <a href="<?= site_url($hero['slug']) ?>" class="text-info" target="_blank">/<?= esc($hero['slug']) ?></a>
                                <?php else: ?>
                                    <span class="text-muted">/<?= esc($hero['slug']) ?></span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($hero['published']): ?>
                                    <span class="badge bg-success">● Publicado</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">○ Rascunho</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-end">
                                <div class="d-flex justify-content-end gap-1 flex-wrap">
                                    <a href="<?= site_url('admin/heroes/' . $hero['id'] . '/photos') ?>" class="btn btn-sm btn-outline-info">Galeria</a>
                                    <a href="<?= site_url('admin/heroes/' . $hero['id'] . '/cta') ?>" class="btn btn-sm btn-outline-warning">CTA</a>
                                    <a href="<?= site_url('admin/heroes/' . $hero['id'] . '/edit') ?>" class="btn btn-sm btn-outline-light">Editar</a>

                                    <!-- Publicar / Despublicar -->
                                    <?php if (!$hero['published']): ?>
                                        <form action="<?= site_url('admin/heroes/' . $hero['id'] . '/publish') ?>" method="post" class="d-inline">
                                            <button type="submit" class="btn btn-sm btn-success">▶ Publicar</button>
                                        </form>
                                    <?php else: ?>
                                        <button type="button"
                                                class="btn btn-sm btn-outline-warning"
                                                data-bs-toggle="modal"
                                                data-bs-target="#unpublishModal"
                                                data-hero-id="<?= $hero['id'] ?>"
                                                data-hero-name="<?= esc($hero['name']) ?>">
                                            ⏸ Despublicar
                                        </button>
                                    <?php endif; ?>

                                    <form action="<?= site_url('admin/heroes/' . $hero['id']) ?>" method="post" class="d-inline"
                                          onsubmit="return confirm('Excluir esta estrela e todas suas fotos?')">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <button type="submit" class="btn btn-sm btn-outline-danger">Del</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">Nenhuma estrela cadastrada ainda.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- ============================================================
     MODAL — Motivo da Despublicação
     ============================================================ -->
<div class="modal fade" id="unpublishModal" tabindex="-1" aria-labelledby="unpublishModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-dark text-white border-secondary">
            <div class="modal-header border-secondary">
                <h5 class="modal-title" id="unpublishModalLabel">⏸ Despublicar Ensaio</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="unpublishForm" method="post" action="">
                <div class="modal-body">
                    <p class="text-muted mb-3">
                        Você está prestes a retirar o ensaio de <strong id="unpublishHeroName" class="text-warning"></strong> do ar.
                        <br>O conteúdo ficará invisível para visitantes imediatamente.
                    </p>
                    <div class="mb-3">
                        <label for="unpublishReason" class="form-label">Motivo <span class="text-muted small">(ficará registrado no log)</span></label>
                        <textarea class="form-control bg-black text-white border-secondary"
                                  id="unpublishReason"
                                  name="reason"
                                  rows="4"
                                  placeholder="Ex: Aguardando revisão de imagens, solicitação do atleta, atualização de conteúdo..."
                                  required></textarea>
                    </div>
                </div>
                <div class="modal-footer border-secondary">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-warning text-dark fw-bold">Confirmar Despublicação</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Injeta o heroId e nome no modal antes de abrir
document.getElementById('unpublishModal').addEventListener('show.bs.modal', function (e) {
    const btn     = e.relatedTarget;
    const heroId  = btn.dataset.heroId;
    const heroName= btn.dataset.heroName;

    document.getElementById('unpublishHeroName').textContent = heroName;
    document.getElementById('unpublishForm').action = `<?= site_url('admin/heroes/') ?>${heroId}/unpublish`;
    document.getElementById('unpublishReason').value = '';
});
</script>

<?= $this->endSection() ?>
