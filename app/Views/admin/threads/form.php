<?= $this->extend('admin/layout') ?>
<?php $item = $opportunity; $v = static function (string $field, $fallback = '') use ($item) { return old($field, $item ? ($item->{$field} ?? $fallback) : $fallback); }; ?>
<?= $this->section('content') ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div><h2 class="text-danger fw-bold mb-1"><?= esc($title) ?></h2><p class="text-muted mb-0"><?= $item ? 'Revise os materiais ou copie o link pronto.' : 'Preencha o essencial. O restante será preparado automaticamente.' ?></p></div>
    <a href="<?= site_url('admin/threads') ?>" class="btn btn-outline-secondary">Voltar</a>
</div>

<form method="post" action="<?= $item ? site_url('admin/threads/' . $item->id) : site_url('admin/threads') ?>">
<div class="row g-4">
<div class="col-lg-7">
<div class="card bg-dark border-secondary mb-4"><div class="card-header d-flex justify-content-between align-items-center"><span>Comece por aqui</span><span class="badge bg-danger">mínimo necessário</span></div><div class="card-body row g-3">
    <div class="col-md-6"><label class="form-label">Perfil do Threads *</label><input name="threads_username" class="form-control bg-dark text-white border-secondary" required value="<?= esc($v('threads_username')) ?>" placeholder="@usuario"></div>
    <div class="col-md-6"><label class="form-label">Categoria <span class="text-muted">(opcional)</span></label><select name="context_category" class="form-select bg-dark text-white border-secondary"><option value="outro">Outro contexto</option><?php foreach ($categories as $category): ?><option value="<?= esc($category) ?>" <?= $v('context_category', 'outro') === $category ? 'selected' : '' ?>><?= esc(ucfirst($category)) ?></option><?php endforeach ?></select></div>
    <div class="col-12"><label class="form-label">Link da publicação <span class="text-muted">(opcional)</span></label><input type="url" name="threads_post_url" class="form-control bg-dark text-white border-secondary" value="<?= esc($v('threads_post_url')) ?>" placeholder="Se deixar vazio, usaremos o perfil informado"></div>
    <div class="col-12"><label class="form-label">Texto da postagem *</label><textarea name="original_text" rows="6" class="form-control bg-dark text-white border-secondary" required placeholder="Cole aqui o texto que ela publicou"><?= esc($v('original_text')) ?></textarea><div class="form-text">Use somente o contexto declarado na publicação.</div></div>
    <div class="col-md-4"><label class="form-label">Prioridade</label><select name="priority" class="form-select bg-dark text-white border-secondary"><?php foreach (['high' => 'Alta', 'medium' => 'Média', 'low' => 'Baixa'] as $key => $label): ?><option value="<?= $key ?>" <?= $v('priority', 'medium') === $key ? 'selected' : '' ?>><?= $label ?></option><?php endforeach ?></select></div>
    <div class="col-md-4"><label class="form-label">Responsável</label><select name="assigned_user_id" class="form-select bg-dark text-white border-secondary"><option value="">Eu</option><?php foreach ($adminUsers as $user): ?><option value="<?= $user->id ?>" <?= (int) $v('assigned_user_id', auth()->id()) === (int) $user->id ? 'selected' : '' ?>><?= esc($user->display_name ?: $user->username) ?></option><?php endforeach ?></select></div>
    <div class="col-md-4"><label class="form-label">WhatsApp do atendimento</label><select name="whatsapp_owner" class="form-select bg-dark text-white border-secondary"><?php foreach ($whatsappContacts as $key => $contact): ?><option value="<?= esc($key) ?>" <?= $v('whatsapp_owner', 'marco') === $key ? 'selected' : '' ?>><?= esc($contact['label']) ?><?= $contact['number'] ? ' · ' . esc($contact['number']) : ' · não cadastrado' ?></option><?php endforeach ?></select></div>
</div></div>

<div class="card bg-dark border-secondary"><div class="card-header">Controle interno <span class="text-muted small">(opcional)</span></div><div class="card-body row g-3">
    <div class="col-md-6"><label class="form-label">Status</label><select name="status" class="form-select bg-dark text-white border-secondary"><?php foreach ($statuses as $key => $label): ?><option value="<?= esc($key) ?>" <?= $v('status', 'identified') === $key ? 'selected' : '' ?>><?= esc($label) ?></option><?php endforeach ?></select></div>
    <div class="col-md-6"><label class="form-label">Próxima ação</label><input type="datetime-local" name="next_action_at" class="form-control bg-dark text-white border-secondary" value="<?= $item && $item->next_action_at ? date('Y-m-d\\TH:i', strtotime($item->next_action_at)) : esc($v('next_action_at')) ?>"></div>
    <div class="col-12"><label class="form-label">Notas</label><textarea name="response_notes" rows="3" class="form-control bg-dark text-white border-secondary"><?= esc($v('response_notes')) ?></textarea></div>
    <div class="col-md-6"><label class="form-label">Valor da proposta (R$)</label><input name="proposal_value" class="form-control bg-dark text-white border-secondary" value="<?= $item && $item->proposal_value_cents !== null ? number_format($item->proposal_value_cents / 100, 2, ',', '.') : '' ?>"></div>
    <div class="col-md-6"><label class="form-label">Motivo de encerramento</label><input name="closed_reason" class="form-control bg-dark text-white border-secondary" value="<?= esc($v('closed_reason')) ?>"></div>
</div></div>
</div>

<div class="col-lg-5">
<?php if (!$item): ?>
<div class="card bg-dark border-danger mb-4"><div class="card-body"><h4 class="text-danger">Tudo pronto para começar</h4><p class="text-muted mb-0">Ao clicar no botão, o sistema cria a página, gera o link privado e prepara automaticamente o comentário e o direct.</p></div></div>
<?php else: ?>
<div class="card bg-dark border-secondary mb-4"><div class="card-header">Materiais gerados</div><div class="card-body">
    <label class="form-label">Comentário público</label><textarea id="comment_copy" name="comment_copy" rows="5" class="form-control bg-dark text-white border-secondary mb-2"><?= esc($v('comment_copy')) ?></textarea><button type="button" class="btn btn-sm btn-outline-light mb-3" onclick="copyField('comment_copy')">Copiar comentário</button>
    <label class="form-label">Mensagem do direct</label><textarea id="direct_copy" name="direct_copy" rows="5" class="form-control bg-dark text-white border-secondary mb-2"><?= esc($v('direct_copy')) ?></textarea><button type="button" class="btn btn-sm btn-outline-light" onclick="copyField('direct_copy')">Copiar direct</button>
</div></div>
<div class="card bg-dark border-secondary mb-4"><div class="card-header">Página personalizada</div><div class="card-body">
    <label class="form-label">Título</label><input name="page_title" class="form-control bg-dark text-white border-secondary mb-3" value="<?= esc($v('page_title')) ?>">
    <label class="form-label">Abertura</label><textarea name="page_intro" rows="4" class="form-control bg-dark text-white border-secondary mb-3"><?= esc($v('page_intro')) ?></textarea>
    <label class="form-label">Oferta/experiência</label><textarea name="offer_copy" rows="4" class="form-control bg-dark text-white border-secondary mb-3"><?= esc($v('offer_copy')) ?></textarea>
    <div class="row g-2"><div class="col-md-6"><label class="form-label">Botão</label><input name="cta_label" class="form-control bg-dark text-white border-secondary" value="<?= esc($v('cta_label', 'Conversar pelo WhatsApp')) ?>"></div><div class="col-md-6"><label class="form-label">Link gerado</label><input type="url" name="cta_url" class="form-control bg-dark text-white border-secondary" value="<?= esc($v('cta_url')) ?>" readonly></div></div>
    <input type="hidden" name="is_page_active" value="0"><div class="form-check mt-3"><input class="form-check-input" type="checkbox" name="is_page_active" value="1" id="page_active" <?= $v('is_page_active', 1) ? 'checked' : '' ?>><label class="form-check-label" for="page_active">Link da página ativo</label></div>
</div></div>
<?php endif ?>
<button class="btn btn-danger btn-lg w-100"><?= $item ? 'Salvar alterações' : '✨ Criar página e gerar link' ?></button>
</div></div>
</form>
<?php if ($item): ?><div class="card bg-dark border-info mt-4"><div class="card-body d-flex flex-wrap align-items-center justify-content-between gap-3"><div><strong>Link para enviar no direct</strong><br><code id="private_link"><?= esc(site_url('convite/' . $item->page_token)) ?></code></div><div class="d-flex gap-2\"><button class="btn btn-sm btn-outline-light" type="button" onclick="copyField('private_link')">Copiar link</button><a href="<?= site_url('convite/' . $item->page_token) ?>" target="_blank" class="btn btn-sm btn-outline-info">Abrir página</a><form method="post" action="<?= site_url('admin/threads/' . $item->id . '/regenerate') ?>" onsubmit="return confirm('Regenerar o link? O anterior deixará de funcionar.')\"><button class="btn btn-sm btn-outline-warning">Regenerar</button></form></div></div></div><?php endif ?>
<script>function copyField(id){const e=document.getElementById(id);const text=e.value ?? e.textContent;navigator.clipboard.writeText(text).then(()=>alert('Copiado.'));}</script>
<?= $this->endSection() ?>
