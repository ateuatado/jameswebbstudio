<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>
<style>
    .user-card {
        background: #181818;
        border: 1px solid rgba(255,255,255,0.07);
        border-radius: 12px;
        padding: 1.25rem;
        display: flex;
        align-items: center;
        gap: 1rem;
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
</style>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="text-white mb-1" style="font-family:'Outfit',sans-serif;">👥 Usuários & Permissões</h2>
        <p class="text-muted small mb-0">Gerencie quem pode usar a Busca Global de Fotos.</p>
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

<div class="d-flex flex-column gap-3">
    <?php foreach ($userData as $entry): ?>
        <?php $user = $entry['user']; $groups = $entry['groups']; ?>
        <div class="user-card">
            <div class="user-avatar">
                <?= strtoupper(substr($user->username ?? $user->email ?? 'U', 0, 1)) ?>
            </div>
            <div class="flex-grow-1">
                <div class="fw-bold text-white"><?= esc($user->username ?? '—') ?></div>
                <div class="text-muted small"><?= esc($user->email) ?></div>
                <div class="mt-1 d-flex gap-1 flex-wrap">
                    <?php foreach ($groups as $group): ?>
                        <span class="badge" style="background:rgba(197,160,89,0.15);color:#c5a059;font-size:0.65rem;"><?= esc($group) ?></span>
                    <?php endforeach ?>
                </div>
            </div>
            <div class="text-center" style="min-width:80px;">
                <div class="text-muted small mb-1">Busca Global</div>
                <?php if (in_array('admin', $groups) || in_array('superadmin', $groups)): ?>
                    <span class="badge bg-success" style="font-size:0.7rem;">✓ Sempre ativo</span>
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
        </div>
    <?php endforeach ?>
</div>

<?= $this->endSection() ?>
