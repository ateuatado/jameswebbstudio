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
        <div class="col-12"><h5 class="mt-2">Materiais e produção</h5></div>
        <div class="col-md-4"><label class="form-label">Papel / impressão</label><input name="print_material" class="form-control bg-black text-white border-secondary" value="<?= old('print_material', $option['print_material']) ?>"></div>
        <div class="col-md-4"><label class="form-label">Moldura</label><input name="frame_material" class="form-control bg-black text-white border-secondary" value="<?= old('frame_material', $option['frame_material']) ?>"></div>
        <div class="col-md-4"><label class="form-label">Fundo</label><input name="backing_material" class="form-control bg-black text-white border-secondary" value="<?= old('backing_material', $option['backing_material']) ?>"></div>
        <div class="col-md-4"><label class="form-label">Proteção frontal</label><input name="glazing" class="form-control bg-black text-white border-secondary" value="<?= old('glazing', $option['glazing']) ?>"></div>
        <div class="col-md-2"><label class="form-label">Peso (g)</label><input type="number" min="0" name="weight_grams" class="form-control bg-black text-white border-secondary" value="<?= old('weight_grams', $option['weight_grams']) ?>"></div>
        <div class="col-md-6"><label class="form-label">Prazo de produção</label><input name="production_lead_time" class="form-control bg-black text-white border-secondary" value="<?= old('production_lead_time', $option['production_lead_time']) ?>"></div>
        <div class="col-12 form-check ms-2"><input class="form-check-input" type="checkbox" name="is_available" value="1" id="available" <?= old('is_available', $option['is_available']) ? 'checked' : '' ?>><label class="form-check-label" for="available">Disponível para venda</label></div>
        <div class="col-12"><button class="btn btn-success">Salvar alterações</button></div>
    </div>
</form>
<?= $this->endSection() ?>
