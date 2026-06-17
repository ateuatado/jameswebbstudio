<?= $this->extend('admin/layout') ?>

<?= $this->section('styles') ?>
<style>
    /* ── Upload colapsável ── */
    .upload-toggle {
        background: rgba(255,255,255,.03);
        border: 1px dashed rgba(255,255,255,.15);
        padding: 16px 24px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        cursor: pointer;
        transition: all .2s;
        margin-bottom: 24px;
    }
    .upload-toggle:hover { border-color: rgba(255,255,255,.3); background: rgba(255,255,255,.05); }
    .upload-toggle .label { font-size: .75rem; letter-spacing: .15em; text-transform: uppercase; color: rgba(255,255,255,.5); }
    .upload-toggle .icon { font-size: 1.2rem; transition: transform .3s; }
    .upload-toggle.open .icon { transform: rotate(180deg); }
    .upload-panel { display: none; margin-bottom: 32px; }
    .upload-panel.show { display: block; }

    /* ── Grid de fotos ── */
    .photo-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; }
    @media (max-width: 991px) { .photo-grid { grid-template-columns: repeat(2, 1fr); } }
    @media (max-width: 575px) { .photo-grid { grid-template-columns: 1fr; } }

    /* ── Card de foto ── */
    .photo-item {
        background: #0d0d0d;
        border: 1px solid rgba(255,255,255,.08);
        overflow: hidden;
        transition: border-color .2s;
        position: relative;
    }
    .photo-item:hover { border-color: rgba(255,255,255,.18); }
    .photo-item.is-cover { border-color: rgba(197,160,89,.5); }
    .photo-item .cover-badge {
        position: absolute; top: 8px; left: 8px; z-index: 2;
        background: linear-gradient(135deg, #C5A059, #F5E27A);
        color: #000; font-size: .55rem; font-weight: 700;
        letter-spacing: .12em; text-transform: uppercase;
        padding: 3px 10px;
    }
    .photo-item .img-wrap {
        width: 100%; height: 220px; overflow: hidden;
        background: #000; cursor: pointer; position: relative;
    }
    .photo-item .img-wrap img {
        width: 100%; height: 100%; object-fit: cover;
        transition: transform .3s, opacity .3s;
    }
    .photo-item:hover .img-wrap img { transform: scale(1.03); }

    /* ── Controles ── */
    .photo-controls { padding: 12px; }
    .caption-field {
        width: 100%; background: #000; border: 1px solid rgba(255,255,255,.1);
        color: #fff; font-size: .78rem; padding: 8px 10px;
        resize: vertical; min-height: 56px; max-height: 140px;
        font-family: 'EB Garamond', Georgia, serif;
        line-height: 1.5; outline: none;
        transition: border-color .2s;
    }
    .caption-field:focus { border-color: rgba(197,160,89,.4); }
    .caption-field::placeholder { color: rgba(255,255,255,.2); font-style: italic; }

    .controls-row {
        display: flex; align-items: center; gap: 8px;
        margin-top: 8px; flex-wrap: wrap;
    }
    .order-input {
        width: 52px; background: #000; border: 1px solid rgba(255,255,255,.1);
        color: #fff; font-size: .75rem; padding: 5px 8px; text-align: center;
        outline: none;
    }
    .order-input:focus { border-color: rgba(197,160,89,.4); }
    .order-label { font-size: .6rem; letter-spacing: .12em; text-transform: uppercase; color: rgba(255,255,255,.3); }

    .btn-save-photo {
        background: transparent; border: 1px solid rgba(46,125,50,.4);
        color: #66bb6a; font-size: .6rem; letter-spacing: .1em;
        text-transform: uppercase; padding: 5px 14px; cursor: pointer;
        transition: all .2s; margin-left: auto;
    }
    .btn-save-photo:hover { background: rgba(46,125,50,.15); border-color: #66bb6a; }
    .btn-save-photo.saving { opacity: .5; pointer-events: none; }

    .feedback-msg { font-size: .7rem; margin-top: 6px; min-height: 18px; }

    /* ── Footer do card ── */
    .photo-footer {
        display: flex; gap: 6px; padding: 0 12px 12px;
    }
    .btn-cover {
        flex: 1; background: transparent; border: 1px solid rgba(197,160,89,.3);
        color: rgba(197,160,89,.7); font-size: .58rem; letter-spacing: .1em;
        text-transform: uppercase; padding: 6px 0; cursor: pointer;
        transition: all .2s; text-align: center; text-decoration: none;
    }
    .btn-cover:hover { background: rgba(197,160,89,.1); color: #C5A059; border-color: #C5A059; }
    .btn-cover.active { background: rgba(197,160,89,.15); color: #C5A059; cursor: default; }
    .btn-delete-photo {
        background: transparent; border: 1px solid rgba(220,53,69,.3);
        color: rgba(220,53,69,.6); font-size: .75rem; padding: 4px 10px;
        cursor: pointer; transition: all .2s; line-height: 1;
    }
    .btn-delete-photo:hover { background: rgba(220,53,69,.1); color: #dc3545; border-color: #dc3545; }

    /* ── Contadores ── */
    .gallery-stats {
        display: flex; gap: 24px; margin-bottom: 24px;
        padding: 12px 0; border-bottom: 1px solid rgba(255,255,255,.06);
    }
    .gallery-stats .stat { text-align: center; }
    .gallery-stats .stat-value { font-size: 1.4rem; font-weight: 600; color: #fff; }
    .gallery-stats .stat-label { font-size: .6rem; letter-spacing: .15em; text-transform: uppercase; color: rgba(255,255,255,.3); }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<!-- ── Header ── -->
<div class="d-flex align-items-center justify-content-between mb-3">
    <div>
        <a href="<?= site_url('admin/heroes') ?>" class="text-muted text-decoration-none small">&larr; Voltar para Heróis</a>
        <h2 class="text-info fw-bold text-uppercase mb-0 mt-1">Galeria: <?= esc($hero['name']) ?></h2>
    </div>
    <?php if (!empty($hero['cover_photo_id'])): ?>
        <span class="badge bg-warning text-dark">&#9733; Capa definida</span>
    <?php else: ?>
        <span class="badge bg-secondary">Sem capa</span>
    <?php endif; ?>
</div>

<!-- ── Stats ── -->
<div class="gallery-stats">
    <div class="stat">
        <div class="stat-value"><?= count($photos) ?></div>
        <div class="stat-label">Fotos</div>
    </div>
    <div class="stat">
        <div class="stat-value"><?= count(array_filter($photos, fn($p) => !empty($p['caption']))) ?></div>
        <div class="stat-label">Com legenda</div>
    </div>
    <div class="stat">
        <div class="stat-value"><?= !empty($hero['cover_photo_id']) ? '✓' : '—' ?></div>
        <div class="stat-label">Capa</div>
    </div>
</div>

<?php if (session()->has('message')): ?>
    <div class="alert alert-success alert-dismissible fade show py-2" role="alert">
        <?= session('message') ?>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<!-- ── Upload colapsável ── -->
<div class="upload-toggle" id="uploadToggle" onclick="toggleUpload()">
    <span class="label">➕ Adicionar nova foto</span>
    <span class="icon">▼</span>
</div>

<div class="upload-panel" id="uploadPanel">
    <div class="card bg-dark text-white border-secondary">
        <div class="card-body">
            <form action="<?= site_url('admin/heroes/' . $hero['id'] . '/photos') ?>" method="post" enctype="multipart/form-data">
                <div class="row g-3 align-items-end">
                    <div class="col-md-4">
                        <label class="form-label small text-muted">Arquivo de Imagem</label>
                        <input type="file" class="form-control form-control-sm bg-black text-white border-secondary" name="photo" accept="image/*" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small text-muted">Legenda</label>
                        <input type="text" class="form-control form-control-sm bg-black text-white border-secondary" name="caption" placeholder="A história por trás desta foto...">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small text-muted">Ordem</label>
                        <input type="number" class="form-control form-control-sm bg-black text-white border-secondary" name="display_order" value="<?= count($photos) ?>">
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary btn-sm w-100">Upload</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ── Grid de fotos ── -->
<div class="photo-grid">
    <?php if (!empty($photos)): ?>
        <?php foreach ($photos as $photo): ?>
        <?php $isCover = ($photo['id'] == ($hero['cover_photo_id'] ?? null)); ?>
        <div class="photo-item <?= $isCover ? 'is-cover' : '' ?>" id="photo-card-<?= $photo['id'] ?>">
            <?php if ($isCover): ?>
                <div class="cover-badge">★ Capa</div>
            <?php endif; ?>

            <div class="img-wrap">
                <img src="<?= base_url($photo['image_path']) ?>" alt="Foto" loading="lazy">
            </div>

            <div class="photo-controls">
                <textarea class="caption-field"
                          id="caption-<?= $photo['id'] ?>"
                          placeholder="Escreva a legenda narrativa..."><?= esc($photo['caption']) ?></textarea>

                <div class="controls-row">
                    <span class="order-label">Ordem</span>
                    <input type="number" class="order-input" id="order-<?= $photo['id'] ?>" value="<?= $photo['display_order'] ?>">
                    <button type="button" class="btn-save-photo" onclick="savePhoto(<?= $photo['id'] ?>)">✓ Salvar</button>
                </div>
                <div class="feedback-msg" id="feedback-<?= $photo['id'] ?>"></div>
            </div>

            <div class="photo-footer">
                <?php if (!$isCover): ?>
                    <form action="<?= site_url("admin/heroes/{$hero['id']}/photos/{$photo['id']}/cover") ?>" method="post" style="flex:1;">
                        <button type="submit" class="btn-cover" style="width:100%;">★ Definir Capa</button>
                    </form>
                <?php else: ?>
                    <span class="btn-cover active" style="flex:1;">★ Capa Atual</span>
                <?php endif; ?>
                <form action="<?= site_url('admin/heroes/photos/' . $photo['id'] . '/delete') ?>" method="post"
                      onsubmit="return confirm('Excluir esta foto permanentemente?')">
                    <button type="submit" class="btn-delete-photo" title="Excluir">🗑</button>
                </form>
            </div>
        </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div style="grid-column: 1/-1; text-align:center; padding:60px 0; color:rgba(255,255,255,.3);">
            <p style="font-size:2rem; margin-bottom:8px;">📷</p>
            <p>Nenhuma foto enviada ainda. Clique em "Adicionar nova foto" acima.</p>
        </div>
    <?php endif; ?>
</div>

<!-- ── Scripts ── -->
<script>
function toggleUpload() {
    const panel  = document.getElementById('uploadPanel');
    const toggle = document.getElementById('uploadToggle');
    panel.classList.toggle('show');
    toggle.classList.toggle('open');
}

function savePhoto(photoId) {
    const caption = document.getElementById('caption-' + photoId).value;
    const order   = document.getElementById('order-' + photoId).value;
    const fb      = document.getElementById('feedback-' + photoId);
    const btn     = event.target.closest('.btn-save-photo');

    btn.classList.add('saving');
    btn.textContent = '...';

    const formData = new FormData();
    formData.append('caption', caption);
    formData.append('display_order', order);

    fetch('<?= site_url("admin/heroes/photos") ?>/' + photoId + '/update', {
        method: 'POST',
        body: formData,
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            fb.className = 'feedback-msg text-success';
            fb.textContent = '✓ Salvo com sucesso';
        } else {
            fb.className = 'feedback-msg text-danger';
            fb.textContent = '✗ ' + (data.message || 'Erro');
        }
        btn.classList.remove('saving');
        btn.textContent = '✓ Salvar';
        setTimeout(() => { fb.textContent = ''; }, 2500);
    })
    .catch(() => {
        fb.className = 'feedback-msg text-danger';
        fb.textContent = '✗ Erro de conexão';
        btn.classList.remove('saving');
        btn.textContent = '✓ Salvar';
        setTimeout(() => { fb.textContent = ''; }, 2500);
    });
}
</script>
<?= $this->endSection() ?>
