<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>
<div class="mb-4">
    <a href="<?= site_url('admin/heroes') ?>" class="btn btn-outline-secondary">&larr; Voltar para Estrelas</a>
</div>

<div class="card bg-dark text-white border-secondary">
    <div class="card-header border-secondary text-info fw-bold text-uppercase">
        <?= isset($hero) ? 'Editar Estrela' : 'Nova Estrela' ?>
    </div>
    <div class="card-body">
        <?php if(session('errors')): ?>
            <div class="alert alert-danger">
                <ul class="mb-0">
                    <?php foreach(session('errors') as $error): ?>
                        <li><?= esc($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form action="<?= isset($hero) ? site_url('admin/heroes/' . $hero['id']) : site_url('admin/heroes') ?>" method="post">
            <?php if(isset($hero)): ?>
                <input type="hidden" name="_method" value="PUT">
            <?php endif; ?>

            <div class="mb-3">
                <label for="name" class="form-label">Nome *</label>
                <input type="text" class="form-control bg-black text-white border-secondary" id="name" name="name" 
                       value="<?= old('name', $hero['name'] ?? '') ?>" required>
                <div class="form-text text-muted">O nome ou título principal (Ex: Karina).</div>
            </div>

            <div class="mb-3">
                <label class="form-label">Categoria / Nicho de Fotografia</label>
                <select name="category_id" class="form-select bg-black text-white border-secondary">
                    <option value="">Nenhum (Nicho Geral)</option>
                    <?php foreach ($categories as $cat): ?>
                        <?php $selected = (isset($hero) && ($hero['category_id'] ?? 0) == $cat->id) ? 'selected' : ''; ?>
                        <option value="<?= esc($cat->id) ?>" <?= $selected ?>><?= esc($cat->name) ?></option>
                    <?php endforeach; ?>
                </select>
                <div class="form-text text-muted">Classifique sob o nicho correspondente.</div>
            </div>

            <div class="mb-3">
                <label for="sport" class="form-label">Esporte / Sub-nicho (Opcional)</label>
                <input type="text" class="form-control bg-black text-white border-secondary" id="sport" name="sport" 
                       value="<?= old('sport', $hero['sport'] ?? '') ?>">
                <div class="form-text text-muted">Ex: Ciclismo, Estúdio Gestante, PET Exótico, etc.</div>
            </div>

            <div class="mb-4">
                <label for="slug" class="form-label">URL Customizada (Slug) *</label>
                <input type="text" class="form-control bg-black text-info border-secondary" id="slug" name="slug" 
                       value="<?= old('slug', $hero['slug'] ?? '') ?>" required>
                <div class="form-text text-muted">A url em que a página ficará disponível. Ex: karina-body-building</div>
            </div>

            <button type="submit" class="btn btn-primary fw-bold px-4">Salvar Estrela</button>
        </form>
    </div>
</div>

<?= $this->endSection() ?>
<?= $this->section('scripts') ?>
<script>
    // Gerador de slug amigável em tempo real (opcional)
    const nameInput = document.getElementById('name');
    const slugInput = document.getElementById('slug');
    <?php if(!isset($hero)): ?>
    nameInput.addEventListener('input', function() {
        let title = this.value.toLowerCase().trim();
        let slug = title.normalize('NFD').replace(/[\u0300-\u036f]/g, '').replace(/[^a-z0-9\s-]/g, '').replace(/\s+/g, '-').replace(/-+/g, '-');
        slugInput.value = slug;
    });
    <?php endif; ?>
</script>
<?= $this->endSection() ?>
