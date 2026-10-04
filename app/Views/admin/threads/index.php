<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>
<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
    <div><h2 class="text-danger fw-bold mb-1">Prospecção no Threads</h2><p class="text-muted mb-0">Organize oportunidades, prepare as mensagens e acompanhe o funil.</p></div>
    <a href="<?= site_url('admin/threads/new') ?>" class="btn btn-danger">+ Nova oportunidade</a>
</div>

<div class="row g-2 mb-4">
    <div class="col-6 col-md-3"><div class="card bg-dark border-secondary h-100"><div class="card-body"><div class="small text-muted">Total</div><div class="fs-3 fw-bold"><?= array_sum($counts) ?></div></div></div></div>
    <?php foreach (['ready' => 'Prontas', 'direct_sent' => 'Em conversa', 'scheduled' => 'Agendadas'] as $key => $label): ?>
    <div class="col-6 col-md-3"><div class="card bg-dark border-secondary h-100"><div class="card-body"><div class="small text-muted"><?= esc($label) ?></div><div class="fs-3 fw-bold text-<?= $key === 'scheduled' ? 'success' : 'warning' ?>"><?= $counts[$key] ?? 0 ?></div></div></div></div>
    <?php endforeach ?>
</div>

<form class="card bg-dark border-secondary mb-4" method="get">
    <div class="card-body row g-2 align-items-end">
        <div class="col-md-4"><label class="form-label small">Status</label><select name="status" class="form-select bg-dark text-white border-secondary"><option value="">Todos</option><?php foreach ($statuses as $key => $label): ?><option value="<?= esc($key) ?>" <?= $selectedStatus === $key ? 'selected' : '' ?>><?= esc($label) ?></option><?php endforeach ?></select></div>
        <div class="col-md-4"><label class="form-label small">Responsável</label><select name="assigned_user_id" class="form-select bg-dark text-white border-secondary"><option value="">Todos</option><?php foreach ($adminUsers as $user): ?><option value="<?= $user->id ?>" <?= (int) $selectedAssigned === (int) $user->id ? 'selected' : '' ?>><?= esc($user->display_name ?: $user->username) ?></option><?php endforeach ?></select></div>
        <div class="col-md-2"><button class="btn btn-outline-light w-100">Filtrar</button></div>
        <div class="col-md-2"><a href="<?= site_url('admin/threads') ?>" class="btn btn-outline-secondary w-100">Limpar</a></div>
    </div>
</form>

<div class="card bg-dark border-secondary"><div class="table-responsive"><table class="table table-dark table-hover align-middle mb-0">
    <thead><tr><th>Oportunidade</th><th>Contexto</th><th>Responsável</th><th>Status</th><th>Página</th><th class="text-end">Ações</th></tr></thead>
    <tbody>
    <?php if (!$opportunities): ?><tr><td colspan="6" class="text-center text-muted py-5">Nenhuma oportunidade cadastrada.</td></tr><?php endif ?>
    <?php foreach ($opportunities as $item): ?>
        <tr>
            <td><strong>@<?= esc(ltrim($item->threads_username, '@')) ?></strong><br><a href="<?= esc($item->threads_post_url) ?>" target="_blank" class="small text-muted">Abrir publicação ↗</a><br><small class="text-muted"><?= date('d/m/Y H:i', strtotime($item->created_at)) ?></small></td>
            <td><span class="badge bg-secondary"><?= esc($item->context_category) ?></span><br><span class="badge text-bg-<?= $item->priority === 'high' ? 'danger' : ($item->priority === 'low' ? 'secondary' : 'warning') ?> mt-1"><?= esc($item->priority) ?></span></td>
            <td><?= esc($item->assigned_display_name ?: $item->assigned_username ?: '—') ?></td>
            <td><form method="post" action="<?= site_url('admin/threads/' . $item->id . '/status') ?>"><select name="status" class="form-select form-select-sm bg-dark text-white border-secondary" onchange="this.form.submit()"><?php foreach ($statuses as $key => $label): ?><option value="<?= esc($key) ?>" <?= $item->status === $key ? 'selected' : '' ?>><?= esc($label) ?></option><?php endforeach ?></select></form></td>
            <td><span class="small text-info"><?= (int) $item->page_views ?> visita(s)</span><br><a href="<?= site_url('convite/' . $item->page_token) ?>" target="_blank" class="small">Abrir página ↗</a></td>
            <td class="text-end"><a href="<?= site_url('admin/threads/' . $item->id . '/edit') ?>" class="btn btn-sm btn-outline-light">Editar</a></td>
        </tr>
    <?php endforeach ?>
    </tbody>
</table></div></div>
<?= $this->endSection() ?>
