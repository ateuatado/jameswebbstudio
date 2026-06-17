<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>
<div class="mb-4">
    <h2><?= esc($title) ?></h2>
    <a href="<?= site_url('admin/categories') ?>" class="btn btn-sm btn-outline-secondary">Voltar aos Nichos</a>
</div>

<?php if (session()->getFlashdata('errors')): ?>
    <div class="alert alert-danger">
        <ul class="mb-0">
            <?php foreach (session()->getFlashdata('errors') as $error): ?>
                <li><?= esc($error) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<div class="card bg-dark text-white border-secondary">
    <div class="card-body">
        <?php $isEdit = isset($category); ?>
        <form action="<?= $isEdit ? site_url('admin/categories/' . $category->id) : site_url('admin/categories') ?>" method="post">
            <?php if ($isEdit): ?>
                <input type="hidden" name="_method" value="PUT">
            <?php endif; ?>
            
            <div class="mb-3">
                <label class="form-label">Nome do Nicho / Categoria</label>
                <input type="text" name="name" class="form-control bg-dark text-white border-secondary" placeholder="Ex: Gestante, PET, Beleza Feminina" value="<?= old('name', $category->name ?? '') ?>" required>
                <small class="text-muted">Nome comercial amigável.</small>
            </div>

            <div class="mb-3">
                <label class="form-label">Slug URL (Opcional)</label>
                <input type="text" name="slug" class="form-control bg-dark text-white border-secondary" placeholder="Ex: beleza-feminina" value="<?= old('slug', $category->slug ?? '') ?>">
                <small class="text-muted">Caminho da URL de portfólio (ex: hero.test/nicho/beleza-feminina). Deixe em branco para gerar automaticamente do nome.</small>
            </div>

            <div class="mb-3">
                <label class="form-label">Descrição do Nicho (Visível para o cliente no site)</label>
                <textarea name="description" class="form-control bg-dark text-white border-secondary" rows="4" placeholder="Descreva o propósito deste nicho de fotografia comercial..."><?= old('description', $category->description ?? '') ?></textarea>
            </div>

            <div class="mb-4 form-check">
                <input type="checkbox" name="is_active" class="form-check-input" id="is_active" value="1" <?= old('is_active', $category->is_active ?? 1) == 1 ? 'checked' : '' ?>>
                <label class="form-check-label text-white-50" for="is_active">Status da Publicação (Nicho ativo e utilizável)</label>
            </div>

            <button type="submit" class="btn btn-primary">Salvar Nicho de Fotografia</button>
        </form>
    </div>
</div>
<?= $this->endSection() ?>
