<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>
<style>
    .user-card {
        background: #181818;
        border: 1px solid rgba(255,255,255,0.07);
        border-radius: 12px;
        padding: 1.25rem;
        transition: border-color 0.3s;
    }
    .user-card:hover { border-color: rgba(197,160,89,0.3); }
    .user-avatar {
        width: 48px; height: 48px;
        background: rgba(197,160,89,0.15);
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        color: #c5a059; font-size: 1.3rem; font-weight: 700; flex-shrink: 0;
    }
    .toggle-search {
        width: 52px; height: 28px; position: relative; flex-shrink: 0;
    }
    .toggle-search input { opacity: 0; width: 0; height: 0; }
    .toggle-slider {
        position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0;
        background: #333; border-radius: 28px; transition: 0.3s;
    }
    .toggle-slider:before {
        content: ""; position: absolute;
        height: 20px; width: 20px; left: 4px; bottom: 4px;
        background: white; border-radius: 50%; transition: 0.3s;
    }
    .toggle-search input:checked + .toggle-slider { background: #c5a059; }
    .toggle-search input:checked + .toggle-slider:before { transform: translateX(24px); }
    .nickname-tag {
        display: inline-block;
        background: rgba(197,160,89,0.1);
        color: #c5a059;
        border: 1px solid rgba(197,160,89,0.2);
        border-radius: 20px;
        padding: 1px 8px;
        font-size: 0.65rem;
        font-family: 'Outfit', sans-serif;
    }
    .btn-edit-user {
        background: rgba(255,255,255,0.05);
        border: 1px solid rgba(255,255,255,0.1);
        color: #aaa;
        border-radius: 8px;
        padding: 4px 10px;
        font-size: 0.72rem;
        transition: all 0.2s;
        cursor: pointer;
    }
    .btn-edit-user:hover { border-color: rgba(197,160,89,0.4); color: #c5a059; }
</style>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="text-white mb-1" style="font-family:'Outfit',sans-serif;">👥 Usuários & Permissões</h2>
        <p class="text-muted small mb-0">Gerencie permissões, nomes e apelidos dos clientes.</p>
    </div>
</div>

<div class="card bg-dark border-secondary mb-4">
    <div class="card-body p-3">
        <div class="d-flex align-items-center gap-2">
            <i class="fas fa-info-circle text-info"></i>
            <small class="text-muted">
                <strong class="text-white">Busca Global</strong> permite que o usuário pesquise fotos por tag da IA em <strong>todos os ensaios</strong> do estúdio.
                Administradores sempre têm acesso independente desta configuração.
                Ideal para agências ou colaboradores autorizados.
            </small>
        </div>
    </div>
</div>

<?php if (session()->getFlashdata('message')): ?>
    <div class="alert alert-success alert-dismissible fade show mb-3" style="font-size:0.85rem;">
        <?= esc(session()->getFlashdata('message')) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif ?>

<div class="d-flex flex-column gap-3">
    <?php foreach ($userData as $entry): ?>
        <?php
            $user    = $entry['user'];
            $groups  = $entry['groups'];
            $db      = \Config\Database::connect();
            $extra   = $db->table('users')->select('display_name, nicknames, rekognition_face_id')->where('id', $user->id)->get()->getRow();
            $displayName = $extra->display_name ?? null;
            $nicknames   = $extra->nicknames ?? '';
            $hasFace     = !empty($extra->rekognition_face_id);
            $initials    = strtoupper(substr($displayName ?? $user->username ?? $user->email ?? 'U', 0, 1));
        ?>
        <div class="user-card">
            <div class="d-flex align-items-start gap-3">
                <!-- Avatar -->
                <div class="user-avatar"><?= $initials ?></div>

                <!-- Info principal -->
                <div class="flex-grow-1 min-width-0">
                    <div class="d-flex align-items-center gap-2 flex-wrap">
                        <span class="fw-bold text-white" style="font-family:'Outfit',sans-serif;">
                            <?= esc($displayName ?? $user->username ?? '—') ?>
                        </span>
                        <?php if ($hasFace): ?>
                            <span class="badge" style="background:rgba(40,167,69,0.2);color:#4dbd74;border:1px solid rgba(40,167,69,0.3);font-size:0.6rem;">
                                <i class="fas fa-user-check me-1"></i>Rosto cadastrado
                            </span>
                        <?php endif ?>
                    </div>
                    <div class="text-muted small"><?= esc($user->email) ?></div>

                    <!-- Apelidos -->
                    <?php if (!empty($nicknames)): ?>
                        <div class="mt-1 d-flex gap-1 flex-wrap">
                            <?php foreach (explode(',', $nicknames) as $nick): ?>
                                <?php $nick = trim($nick); if (!$nick) continue; ?>
                                <span class="nickname-tag">🏷 <?= esc($nick) ?></span>
                            <?php endforeach ?>
                        </div>
                    <?php endif ?>

                    <!-- Grupos -->
                    <div class="mt-1 d-flex gap-1 flex-wrap">
                        <?php foreach ($groups as $group): ?>
                            <span class="badge" style="background:rgba(197,160,89,0.15);color:#c5a059;font-size:0.6rem;"><?= esc($group) ?></span>
                        <?php endforeach ?>
                    </div>
                </div>

                <!-- Controles -->
                <div class="d-flex flex-column align-items-end gap-2" style="min-width:120px;">
                    <!-- Toggle Busca Global -->
                    <div class="text-center">
                        <div class="text-muted small mb-1" style="font-size:0.7rem;">Busca Global</div>
                        <?php if (in_array('admin', $groups) || in_array('superadmin', $groups)): ?>
                            <span class="badge bg-success" style="font-size:0.65rem;">✓ Sempre ativo</span>
                        <?php else: ?>
                            <form method="post" action="<?= site_url('admin/usuarios/' . $user->id . '/toggle-search') ?>">
                                <?= csrf_field() ?>
                                <label class="toggle-search">
                                    <input type="checkbox" onchange="this.form.submit()" <?= $entry['search_global'] ? 'checked' : '' ?>>
                                    <span class="toggle-slider"></span>
                                </label>
                            </form>
                        <?php endif ?>
                    </div>

                    <!-- Botão Editar Nome/Apelidos -->
                    <button class="btn-edit-user" onclick="openEditModal(<?= $user->id ?>, '<?= esc(addslashes($displayName ?? '')) ?>', '<?= esc(addslashes($nicknames)) ?>')">
                        <i class="fas fa-pen me-1"></i>Nome & Apelidos
                    </button>

                    <!-- Promover / Rebaixar Admin -->
                    <?php if (!in_array('superadmin', $groups)): ?>
                        <?php if (in_array('admin', $groups)): ?>
                            <?php if ($user->id !== auth()->id()): ?>
                            <form method="post" action="<?= site_url('admin/usuarios/' . $user->id . '/demote') ?>"
                                  onsubmit="return confirm('Remover privilégios de admin de <?= esc(addslashes($user->email)) ?>?')">
                                <?= csrf_field() ?>
                                <button type="submit" class="btn-edit-user" style="color:#dc3545;border-color:rgba(220,53,69,0.3);">
                                    <i class="fas fa-user-minus me-1"></i>Remover Admin
                                </button>
                            </form>
                            <?php endif ?>
                        <?php else: ?>
                            <form method="post" action="<?= site_url('admin/usuarios/' . $user->id . '/promote') ?>"
                                  onsubmit="return confirm('Promover <?= esc(addslashes($user->email)) ?> a administrador?')">
                                <?= csrf_field() ?>
                                <button type="submit" class="btn-edit-user" style="color:#c5a059;border-color:rgba(197,160,89,0.3);">
                                    <i class="fas fa-user-shield me-1"></i>Tornar Admin
                                </button>
                            </form>
                        <?php endif ?>
                    <?php endif ?>
                </div>
            </div>
        </div>
    <?php endforeach ?>
</div>

<!-- Modal: Editar Nome e Apelidos -->
<div class="modal fade" id="editUserModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="background:#111;border:1px solid rgba(197,160,89,0.3);">
            <div class="modal-header" style="border-bottom:1px solid rgba(255,255,255,0.07);">
                <h5 class="modal-title text-white" style="font-family:'Outfit',sans-serif;">
                    <i class="fas fa-pen me-2 text-gold"></i>Nome & Apelidos do Cliente
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="editUserForm" method="post">
                <?= csrf_field() ?>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label text-white" style="font-size:0.85rem;">
                            Nome Principal <span class="text-muted">(como aparece no sistema)</span>
                        </label>
                        <input type="text" name="display_name" id="editDisplayName"
                               class="form-control" style="background:#1a1a1a;border:1px solid rgba(197,160,89,0.3);color:#fff;"
                               placeholder="Ex: Isabelly Ferreira">
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-white" style="font-size:0.85rem;">
                            Apelidos <span class="text-muted">(separados por vírgula)</span>
                        </label>
                        <input type="text" name="nicknames" id="editNicknames"
                               class="form-control" style="background:#1a1a1a;border:1px solid rgba(197,160,89,0.3);color:#fff;"
                               placeholder="Ex: isa, isabel, bell, bela, ciganabel">
                        <div class="form-text text-muted" style="font-size:0.75rem;">
                            <i class="fas fa-lightbulb me-1 text-gold"></i>
                            Qualquer apelido funciona para identificar a pessoa na busca.
                        </div>
                    </div>
                    <!-- Preview de apelidos -->
                    <div id="nicknamePreview" class="d-flex flex-wrap gap-1 mt-1"></div>
                </div>
                <div class="modal-footer" style="border-top:1px solid rgba(255,255,255,0.07);">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-gold">
                        <i class="fas fa-save me-1"></i>Salvar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
function openEditModal(userId, displayName, nicknames) {
    document.getElementById('editDisplayName').value = displayName;
    document.getElementById('editNicknames').value   = nicknames;
    document.getElementById('editUserForm').action   = `/admin/usuarios/${userId}/update-profile`;
    updateNicknamePreview(nicknames);
    new bootstrap.Modal(document.getElementById('editUserModal')).show();
}

document.getElementById('editNicknames').addEventListener('input', function() {
    updateNicknamePreview(this.value);
});

function updateNicknamePreview(val) {
    const preview = document.getElementById('nicknamePreview');
    preview.innerHTML = '';
    val.split(',').forEach(n => {
        n = n.trim();
        if (n) {
            const span = document.createElement('span');
            span.className = 'nickname-tag';
            span.textContent = '🏷 ' + n;
            preview.appendChild(span);
        }
    });
}
</script>
<?= $this->endSection() ?>
