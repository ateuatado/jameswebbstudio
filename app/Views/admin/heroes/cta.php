<?= $this->extend('admin/layout') ?>
<?= $this->section('content') ?>

<div class="mb-4 d-flex justify-content-between align-items-center">
    <a href="<?= site_url('admin/heroes') ?>" class="btn btn-outline-secondary">&larr; Voltar</a>
    <a href="<?= site_url($hero['slug'] . '/agendar') ?>" class="btn btn-outline-info" target="_blank">Ver Landing →</a>
</div>

<h2 class="text-info fw-bold text-uppercase mb-1">Landing Page — <?= esc($hero['name']) ?></h2>
<p class="text-muted small mb-4">A URL pública será: <code><?= site_url($hero['slug'] . '/agendar') ?></code></p>

<?php if (session()->has('message')): ?>
    <div class="alert alert-success alert-dismissible fade show">
        <?= session('message') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<!-- ============================================================
     METADADOS DA PÁGINA
     ============================================================ -->
<div class="card bg-dark text-white border-secondary mb-4">
    <div class="card-header border-secondary text-warning fw-bold">Metadados da Página</div>
    <div class="card-body">
        <form action="<?= site_url('admin/heroes/' . $hero['id'] . '/cta') ?>" method="post">
            <div class="mb-3">
                <label class="form-label">Título da Página (meta title / SEO)</label>
                <input type="text" class="form-control bg-black text-white border-secondary" name="title"
                       value="<?= esc($cta['title'] ?? '') ?>" placeholder="Ex: Marcos | Ensaio de Alta Performance">
            </div>
            <div class="mb-3">
                <label class="form-label">Descrição (meta description)</label>
                <textarea class="form-control bg-black text-white border-secondary" name="description" rows="2"><?= esc($cta['description'] ?? '') ?></textarea>
            </div>
            <button type="submit" class="btn btn-warning text-dark fw-bold px-4">Salvar Metadados</button>
        </form>
    </div>
</div>

<!-- ============================================================
     BLOCK BUILDER
     ============================================================ -->
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="text-white mb-0">Blocos de Conteúdo</h5>
    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addBlockModal">+ Adicionar Bloco</button>
</div>

<div id="blocks-sortable" class="d-flex flex-column gap-3 mb-5">
<?php if (!empty($blocks)): ?>
    <?php foreach ($blocks as $block): ?>
    <div class="card bg-dark border-secondary block-item" data-block-id="<?= $block['id'] ?>">
        <div class="card-body d-flex align-items-start gap-3">
            <span class="text-muted fs-4 drag-handle" style="cursor:grab;" title="Arrastar">⠿</span>
            <div class="flex-fill">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="badge bg-secondary text-uppercase"><?= $block['type'] ?></span>
                    <div class="d-flex gap-2">
                        <button class="btn btn-sm btn-outline-light"
                                data-bs-toggle="modal"
                                data-bs-target="#editBlockModal"
                                data-block-id="<?= $block['id'] ?>"
                                data-block-type="<?= $block['type'] ?>"
                                data-block-content="<?= htmlspecialchars(json_encode($block['content']), ENT_QUOTES) ?>">
                            Editar
                        </button>
                        <form action="<?= site_url('admin/heroes/' . $hero['id'] . '/cta/blocks/' . $block['id'] . '/delete') ?>" method="post"
                              onsubmit="return confirm('Remover este bloco?')">
                            <button type="submit" class="btn btn-sm btn-outline-danger">Del</button>
                        </form>
                    </div>
                </div>
                <!-- Preview por tipo -->
                <?php $c = $block['content']; ?>
                <?php if ($block['type'] === 'headline'): ?>
                    <strong class="text-warning"><?= esc($c['title'] ?? '') ?></strong>
                    <p class="text-muted small mb-0"><?= esc($c['subtitle'] ?? '') ?></p>
                <?php elseif ($block['type'] === 'text'): ?>
                    <p class="text-muted small mb-0" style="max-height:60px;overflow:hidden;"><?= strip_tags($c['content'] ?? '') ?></p>
                <?php elseif ($block['type'] === 'image'): ?>
                    <?php if (!empty($c['image_path'])): ?>
                        <img src="<?= base_url($c['image_path']) ?>" style="height:60px;object-fit:cover;border-radius:4px;">
                    <?php endif; ?>
                    <span class="text-muted small"><?= esc($c['caption'] ?? '') ?></span>
                <?php elseif ($block['type'] === 'video_embed'): ?>
                    <span class="text-muted small">🎬 <?= esc($c['url'] ?? '') ?></span>
                <?php elseif ($block['type'] === 'testimony'): ?>
                    <em class="text-muted small">"<?= esc(mb_substr($c['quote'] ?? '', 0, 80)) ?>..."</em>
                    <span class="text-muted small"> — <?= esc($c['author'] ?? '') ?></span>
                <?php elseif ($block['type'] === 'process'): ?>
                    <span class="text-muted small"><?= count($c['steps'] ?? []) ?> etapa(s)</span>
                <?php elseif ($block['type'] === 'cta_button'): ?>
                    <span class="badge bg-warning text-dark"><?= esc($c['text'] ?? 'Botão CTA') ?></span>
                <?php elseif ($block['type'] === 'spacer'): ?>
                    <span class="text-muted small">Espaço: <?= esc($c['height'] ?? 'md') ?></span>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
<?php else: ?>
    <div class="text-center text-muted py-5 border border-secondary rounded">
        <p>Nenhum bloco ainda. Clique em <strong>+ Adicionar Bloco</strong> para começar.</p>
    </div>
<?php endif; ?>
</div>

<!-- ============================================================
     MODAL — Adicionar Bloco
     ============================================================ -->
<div class="modal fade" id="addBlockModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content bg-dark text-white border-secondary">
            <div class="modal-header border-secondary">
                <h5 class="modal-title">Adicionar Bloco</h5>
                <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?= site_url('admin/heroes/' . $hero['id'] . '/cta/blocks') ?>" method="post" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="mb-4">
                        <label class="form-label fw-bold">Tipo de Bloco</label>
                        <select class="form-select bg-black text-white border-secondary" id="addBlockType" name="type" onchange="showBlockFields('add', this.value)">
                            <option value="">— Selecione —</option>
                            <option value="headline">🎯 Headline — abertura emocional</option>
                            <option value="text">📝 Texto — parágrafo de copy</option>
                            <option value="image">🖼 Imagem — foto isolada</option>
                            <option value="video_embed">🎬 Vídeo — embed YouTube/Vimeo</option>
                            <option value="testimony">💬 Depoimento — citação de atleta</option>
                            <option value="process">🔄 Processo — etapas visuais</option>
                            <option value="cta_button">🎯 Botão CTA — âncora para agendar</option>
                            <option value="spacer">↕ Espaçador</option>
                        </select>
                    </div>
                    <div id="add-block-fields"></div>
                </div>
                <div class="modal-footer border-secondary">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success">Adicionar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ============================================================
     MODAL — Editar Bloco
     ============================================================ -->
<div class="modal fade" id="editBlockModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content bg-dark text-white border-secondary">
            <div class="modal-header border-secondary">
                <h5 class="modal-title">Editar Bloco</h5>
                <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="editBlockForm" method="post" enctype="multipart/form-data">
                <div class="modal-body" id="edit-block-fields"></div>
                <div class="modal-footer border-secondary">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-warning text-dark fw-bold">Salvar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ============================================================
     SortableJS + Block field templates
     ============================================================ -->
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>
<script>
const HERO_ID = <?= (int)$hero['id'] ?>;
const BASE    = '<?= site_url('admin/heroes/') ?>';

// --- Drag-to-reorder ---
const sortEl = document.getElementById('blocks-sortable');
if (sortEl) {
    Sortable.create(sortEl, {
        handle: '.drag-handle',
        animation: 150,
        onEnd() {
            const order = [...sortEl.querySelectorAll('.block-item')].map(el => el.dataset.blockId);
            fetch(`${BASE}${HERO_ID}/cta/blocks/order`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                body: JSON.stringify({ order })
            });
        }
    });
}

// --- Templates de campos por tipo ---
function blockFields(type, c = {}) {
    const inp = (name, label, val='', ph='') =>
        `<div class="mb-3"><label class="form-label">${label}</label>
         <input type="text" class="form-control bg-black text-white border-secondary" name="${name}" value="${esc(val)}" placeholder="${ph}"></div>`;

    const ta = (name, label, val='', rows=4) =>
        `<div class="mb-3"><label class="form-label">${label}</label>
         <textarea class="form-control bg-black text-white border-secondary" name="${name}" rows="${rows}">${esc(val)}</textarea></div>`;

    const imgPrev = (path) => path
        ? `<div class="mb-2"><img src="${BASE.replace('admin/heroes/','') + '../..' }/${path}" style="height:80px;object-fit:cover;border-radius:4px;"></div>` : '';

    const imgField = (existing='') =>
        `${imgPrev(existing)}
         <input type="hidden" name="image_path_existing" value="${esc(existing)}">
         <div class="mb-3"><label class="form-label">Imagem ${existing ? '(deixe vazio para manter atual)' : ''}</label>
         <input type="file" class="form-control bg-black text-white border-secondary" name="block_image" accept="image/*"></div>`;

    if (type === 'headline') return `
        ${inp('title',    'Título principal', c.title, 'Você treina como poucos...')}
        ${inp('subtitle', 'Subtítulo',        c.subtitle, 'Merece ser visto assim.')}
        ${imgField(c.image_path)}`;

    if (type === 'text') return `
        ${ta('content', 'Texto (HTML simples permitido: <b>, <em>, <br>)', c.content, 6)}
        <div class="mb-3"><label class="form-label">Alinhamento</label>
        <select class="form-select bg-black text-white border-secondary" name="align">
            <option value="left"   ${c.align==='left'   ? 'selected':''}>Esquerda</option>
            <option value="center" ${c.align==='center' ? 'selected':''}>Centro</option>
        </select></div>`;

    if (type === 'image') return `
        ${imgField(c.image_path)}
        ${inp('caption', 'Legenda (opcional)', c.caption)}
        <div class="mb-3"><label class="form-label">Tamanho</label>
        <select class="form-select bg-black text-white border-secondary" name="size">
            <option value="contained" ${c.size==='contained'?'selected':''}>Contida (max 900px)</option>
            <option value="full"      ${c.size==='full'     ?'selected':''}>Full-width</option>
        </select></div>`;

    if (type === 'video_embed') return `
        ${inp('url',   'URL do YouTube ou Vimeo', c.url, 'https://youtube.com/watch?v=...')}
        ${inp('title', 'Título do vídeo',         c.title)}`;

    if (type === 'testimony') return `
        ${ta('quote',  'Citação do atleta', c.quote, 3)}
        ${inp('author', 'Nome do atleta',  c.author)}
        ${inp('sport',  'Esporte / contexto', c.sport)}
        ${imgField(c.image_path)}`;

    if (type === 'process') {
        const steps = c.steps && c.steps.length ? c.steps : [
            {number:'01',title:'',desc:''},{number:'02',title:'',desc:''},{number:'03',title:'',desc:''}
        ];
        return steps.map((s,i) => `
            <div class="border border-secondary rounded p-3 mb-3">
                <label class="form-label fw-bold text-muted">Etapa ${i+1}</label>
                ${inp('step_number[]', 'Número / Ícone', s.number)}
                ${inp('step_title[]',  'Título',         s.title)}
                ${ta('step_desc[]',    'Descrição',      s.desc, 2)}
            </div>`).join('');
    }

    if (type === 'cta_button') return `
        ${inp('text', 'Texto do botão', c.text || 'Quero meu ensaio', 'Quero meu ensaio')}
        <div class="form-check mb-3">
            <input type="checkbox" class="form-check-input" name="scroll_to_agenda" value="1" id="scrollCheck" ${c.scroll_to_agenda?'checked':''}>
            <label class="form-check-label" for="scrollCheck">Rolar para o widget de agendamento</label>
        </div>`;

    if (type === 'spacer') return `
        <div class="mb-3"><label class="form-label">Altura do espaço</label>
        <select class="form-select bg-black text-white border-secondary" name="height">
            <option value="sm" ${c.height==='sm'?'selected':''}>Pequeno (40px)</option>
            <option value="md" ${c.height==='md'?'selected':''}>Médio (80px)</option>
            <option value="lg" ${c.height==='lg'?'selected':''}>Grande (160px)</option>
        </select></div>`;

    return '<p class="text-muted">Selecione um tipo acima.</p>';
}

function esc(v) { return (v||'').toString().replace(/"/g,'&quot;').replace(/</g,'&lt;'); }

// --- Modal Adicionar ---
function showBlockFields(ctx, type) {
    const el = document.getElementById(`${ctx}-block-fields`);
    if (el) el.innerHTML = blockFields(type);
}

// --- Modal Editar ---
document.getElementById('editBlockModal').addEventListener('show.bs.modal', function(e) {
    const btn     = e.relatedTarget;
    const blockId = btn.dataset.blockId;
    const type    = btn.dataset.blockType;
    const content = JSON.parse(btn.dataset.blockContent || '{}');

    document.getElementById('editBlockForm').action = `${BASE}${HERO_ID}/cta/blocks/${blockId}`;
    document.getElementById('edit-block-fields').innerHTML =
        `<div class="mb-3"><span class="badge bg-secondary text-uppercase">${type}</span></div>` +
        blockFields(type, content);
});
</script>

<?= $this->endSection() ?>
