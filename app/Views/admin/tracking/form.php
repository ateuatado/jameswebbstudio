<?php $this->extend('admin/layout') ?>
<?php $this->section('content') ?>

<?php $isEdit = isset($link) && $link !== null; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><?= $isEdit ? '✏️ Editar Link' : '➕ Novo Link Rastreado' ?></h2>
    <a href="<?= site_url('admin/tracking') ?>" class="btn btn-outline-secondary btn-sm">← Voltar</a>
</div>

<?php
$action = $isEdit
    ? site_url('admin/tracking/' . $link->id . '/update')
    : site_url('admin/tracking/store');
?>

<form method="post" action="<?= $action ?>">
    <?= csrf_field() ?>

    <div class="row g-3">
        <!-- Slug -->
        <div class="col-md-4">
            <label class="form-label">Slug <span class="text-danger">*</span></label>
            <input type="text" name="slug" id="slug" class="form-control"
                   value="<?= esc(old('slug', $link->slug ?? '')) ?>"
                   pattern="[a-z0-9\-]+" required placeholder="ex: ig-bio">
            <div class="form-text">Apenas letras minúsculas, números e hífens.</div>
        </div>

        <!-- Preview da URL gerada -->
        <div class="col-md-8">
            <label class="form-label">URL Curta Gerada</label>
            <div class="input-group">
                <span class="input-group-text bg-dark text-muted"><?= site_url('r/') ?></span>
                <input type="text" id="url-preview" class="form-control" readonly placeholder="preencha o slug">
                <button type="button" class="btn btn-outline-secondary" id="copy-url" title="Copiar">📋</button>
            </div>
        </div>

        <!-- Destination URL -->
        <div class="col-12">
            <label class="form-label">URL de Destino <span class="text-danger">*</span></label>
            <input type="url" name="destination_url" class="form-control"
                   value="<?= esc(old('destination_url', $link->destination_url ?? '')) ?>"
                   required placeholder="https://jameswebbstudio.com.br/investimento">
        </div>

        <!-- UTM Source -->
        <div class="col-md-3">
            <label class="form-label">UTM Source</label>
            <?php
            $sources   = ['instagram', 'facebook', 'tiktok', 'twitter', 'youtube', 'whatsapp', 'google', 'email'];
            $curSource = old('utm_source', $link->utm_source ?? '');
            $isCustom  = $curSource && !in_array($curSource, $sources);
            ?>
            <select name="_utm_source_select" id="utm_source_select" class="form-select">
                <option value="">— Selecione —</option>
                <?php foreach ($sources as $s): ?>
                    <option value="<?= $s ?>" <?= (!$isCustom && $curSource === $s) ? 'selected' : '' ?>><?= ucfirst($s) ?></option>
                <?php endforeach ?>
                <option value="_custom" <?= $isCustom ? 'selected' : '' ?>>Outro (campo livre)</option>
            </select>
            <input type="text" name="utm_source" id="utm_source_custom"
                   class="form-control mt-1 <?= $isCustom ? '' : 'd-none' ?>"
                   value="<?= $isCustom ? esc($curSource) : '' ?>" placeholder="Ex: linkedin">
        </div>

        <!-- UTM Medium -->
        <div class="col-md-3">
            <label class="form-label">UTM Medium</label>
            <input type="text" name="utm_medium" class="form-control"
                   value="<?= esc(old('utm_medium', $link->utm_medium ?? '')) ?>" placeholder="bio, stories, post, cpc">
        </div>

        <!-- UTM Campaign -->
        <div class="col-md-3">
            <label class="form-label">UTM Campaign</label>
            <input type="text" name="utm_campaign" class="form-control"
                   value="<?= esc(old('utm_campaign', $link->utm_campaign ?? '')) ?>" placeholder="agosto2026">
        </div>

        <!-- UTM Content -->
        <div class="col-md-3">
            <label class="form-label">UTM Content</label>
            <input type="text" name="utm_content" class="form-control"
                   value="<?= esc(old('utm_content', $link->utm_content ?? '')) ?>" placeholder="post-carousel-01">
        </div>

        <!-- Ativo -->
        <div class="col-12">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="is_active" value="1" id="is_active"
                    <?= old('is_active', $link->is_active ?? 1) ? 'checked' : '' ?>>
                <label class="form-check-label" for="is_active">Link ativo</label>
            </div>
        </div>

        <div class="col-12">
            <button type="submit" class="btn btn-success"><?= $isEdit ? 'Salvar Alterações' : 'Criar Link' ?></button>
            <a href="<?= site_url('admin/tracking') ?>" class="btn btn-outline-secondary ms-2">Cancelar</a>
        </div>
    </div>
</form>

<?php $this->endSection() ?>

<?php $this->section('scripts') ?>
<script>
const baseUrl   = <?= json_encode(site_url('r/')) ?>;
const slugInput = document.getElementById('slug');
const preview   = document.getElementById('url-preview');
const copyBtn   = document.getElementById('copy-url');

function updatePreview() {
    preview.value = slugInput.value ? baseUrl + slugInput.value : '';
}
slugInput.addEventListener('input', updatePreview);
updatePreview();

copyBtn.addEventListener('click', () => {
    if (!preview.value) return;
    navigator.clipboard.writeText(preview.value).then(() => {
        copyBtn.textContent = '✅';
        setTimeout(() => copyBtn.textContent = '📋', 1500);
    });
});

// UTM Source toggle
const sourceSelect = document.getElementById('utm_source_select');
const sourceCustom = document.getElementById('utm_source_custom');

sourceSelect.addEventListener('change', () => {
    if (sourceSelect.value === '_custom') {
        sourceCustom.classList.remove('d-none');
        sourceCustom.setAttribute('name', 'utm_source');
        sourceSelect.setAttribute('name', '_utm_source_select');
    } else {
        sourceCustom.classList.add('d-none');
        sourceCustom.setAttribute('name', '_utm_source_custom');
        sourceSelect.setAttribute('name', 'utm_source');
    }
});

// Init on page load
if (sourceSelect.value === '_custom') {
    sourceCustom.classList.remove('d-none');
    sourceCustom.setAttribute('name', 'utm_source');
    sourceSelect.setAttribute('name', '_utm_source_select');
} else {
    sourceSelect.setAttribute('name', 'utm_source');
}
</script>
<?php $this->endSection() ?>
