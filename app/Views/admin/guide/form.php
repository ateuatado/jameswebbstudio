<?= $this->extend('admin/layout') ?>
<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 text-gold mb-0"><?= isset($section) && $section ? 'Editar Seção' : 'Nova Seção' ?></h1>
    <a href="<?= site_url('admin/guide-sections') ?>" class="btn btn-outline-light btn-sm" style="font-size:.75rem;">← Voltar</a>
</div>

<?php if (session()->has('errors')): ?>
    <div class="alert alert-danger" style="font-size:.85rem;">
        <ul class="mb-0">
            <?php foreach (session('errors') as $e): ?>
                <li><?= esc($e) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<form action="<?= isset($section) && $section ? site_url('admin/guide-sections/' . $section->id . '/update') : site_url('admin/guide-sections/store') ?>" method="post">
    <?= csrf_field() ?>

    <div class="card bg-dark border-secondary">
        <div class="card-body">

            <div class="row mb-3">
                <div class="col-md-8">
                    <label class="form-label text-gold" style="font-size:.8rem;letter-spacing:.08em;">Título da Seção</label>
                    <input type="text" name="title" class="form-control bg-black text-white border-secondary"
                           value="<?= esc(old('title', $section->title ?? '')) ?>"
                           placeholder="Ex: Figurino — A regra de ouro" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label text-gold" style="font-size:.8rem;letter-spacing:.08em;">Nicho / Categoria</label>
                    <select name="category_id" class="form-select bg-black text-white border-secondary">
                        <option value="">🌐 Universal (todos os ensaios)</option>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?= $cat->id ?>" <?= old('category_id', $section->category_id ?? '') == $cat->id ? 'selected' : '' ?>>
                                🎯 <?= esc($cat->name) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label text-gold" style="font-size:.8rem;letter-spacing:.08em;">Conteúdo</label>
                <textarea name="content" class="form-control bg-black text-white border-secondary" rows="14"
                          placeholder="Escreva o conteúdo da seção..." required
                          style="line-height:1.7;font-size:.9rem;"><?= esc(old('content', $section->content ?? '')) ?></textarea>
                <small class="text-muted">Use quebras de linha normais. Use ✅ ❌ • para listas. O texto aparece como escrito no PDF.</small>
            </div>

            <div class="row mb-3">
                <div class="col-md-3">
                    <label class="form-label text-gold" style="font-size:.8rem;letter-spacing:.08em;">Ordem de exibição</label>
                    <input type="number" name="display_order" class="form-control bg-black text-white border-secondary"
                           value="<?= esc(old('display_order', $section->display_order ?? 0)) ?>" min="0">
                    <small class="text-muted">Menor número = aparece primeiro.</small>
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <div class="form-check mb-3">
                        <input type="checkbox" name="is_active" class="form-check-input" id="is_active" value="1"
                               <?= old('is_active', $section->is_active ?? 1) ? 'checked' : '' ?>>
                        <label class="form-check-label text-white" for="is_active">Ativo</label>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <div class="mt-3 d-flex gap-2">
        <button type="submit" class="btn btn-terroso">
            <?= isset($section) && $section ? 'Salvar Alterações' : 'Criar Seção' ?>
        </button>
        <a href="<?= site_url('admin/guide-sections') ?>" class="btn btn-outline-light">Cancelar</a>
    </div>
</form>

<?= $this->endSection() ?>
