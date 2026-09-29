<?= $this->extend('admin/layout') ?>
<?= $this->section('content') ?>
<div class="mb-4">
    <h2>Editar edição: <?= esc($work['title']) ?></h2>
    <a class="btn btn-sm btn-outline-secondary" href="<?= site_url('admin/fotos/' . $work['id'] . '/imagens/' . $image['id'] . '/edicoes') ?>">Voltar às edições</a>
</div>

<?php if (session('errors')): ?><div class="alert alert-danger"><ul class="mb-0"><?php foreach (session('errors') as $error): ?><li><?= esc($error) ?></li><?php endforeach ?></ul></div><?php endif ?>

<form method="post" action="<?= site_url('admin/fotos/' . $work['id'] . '/imagens/' . $image['id'] . '/edicoes/' . $option['id']) ?>" class="card bg-dark border-secondary">
    <div class="card-body row g-3">
        <input type="hidden" name="_method" value="PUT">
        <div class="col-md-4"><label class="form-label">Nome do tamanho</label><input required name="size_label" class="form-control bg-black text-white border-secondary" value="<?= old('size_label', $option['size_label']) ?>" placeholder="40 × 60 cm"></div>
        <div class="col-md-2"><label class="form-label">Largura (cm)</label><input type="number" step="0.01" min="0" name="width_cm" class="form-control bg-black text-white border-secondary" value="<?= old('width_cm', $option['width_cm']) ?>"></div>
        <div class="col-md-2"><label class="form-label">Altura (cm)</label><input type="number" step="0.01" min="0" name="height_cm" class="form-control bg-black text-white border-secondary" value="<?= old('height_cm', $option['height_cm']) ?>"></div>
        <div class="col-md-2"><label class="form-label">Preço (R$)</label><input required type="number" step="0.01" min="0.01" name="price" class="form-control bg-black text-white border-secondary" value="<?= old('price', number_format($option['price_cents'] / 100, 2, '.', '')) ?>"></div>
        <div class="col-md-2"><label class="form-label">Ordem</label><input type="number" name="display_order" class="form-control bg-black text-white border-secondary" value="<?= old('display_order', $option['display_order']) ?>"></div>
        <div class="col-12 form-check ms-2"><input class="form-check-input" type="checkbox" name="is_available" value="1" id="available" <?= old('is_available', $option['is_available']) ? 'checked' : '' ?>><label class="form-check-label" for="available">Disponível para venda</label></div>
        <div class="col-12"><button class="btn btn-success">Salvar alterações</button></div>
    </div>
</form>
<?= $this->endSection() ?>
