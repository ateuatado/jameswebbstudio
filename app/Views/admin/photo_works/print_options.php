<?= $this->extend('admin/layout') ?>
<?= $this->section('styles') ?>
<link rel="stylesheet" href="<?= base_url('assets/css/admin-photo-gallery.css') ?>">
<?= $this->endSection() ?>
<?= $this->section('content') ?>
<div class="mb-4">
    <h2>Edições à venda: <?= esc($work['title']) ?></h2>
    <p class="text-muted">Configure os tamanhos e preços desta fotografia. A imagem continua sendo parte da galeria.</p>
    <a class="btn btn-sm btn-outline-secondary" href="<?= site_url('admin/fotos/' . $work['id'] . '/imagens') ?>">Voltar às fotografias</a>
</div>

<?php if (session('errors')): ?><div class="alert alert-danger"><ul class="mb-0"><?php foreach (session('errors') as $error): ?><li><?= esc($error) ?></li><?php endforeach ?></ul></div><?php endif ?>

<form method="post" class="card bg-dark border-secondary mb-4">
    <div class="card-body row g-3">
        <div class="col-12"><img src="<?= base_url($image['image_path']) ?>" alt="<?= esc($image['alt_text']) ?>" class="img-fluid rounded admin-print-preview"></div>
        <div class="col-12 form-check ms-2"><input class="form-check-input" type="checkbox" name="is_for_sale" value="1" id="is-for-sale" <?= $image['is_for_sale'] ? 'checked' : '' ?>><label class="form-check-label" for="is-for-sale">Disponibilizar esta fotografia para venda</label></div>
        <div class="col-12 form-check ms-2"><input class="form-check-input" type="checkbox" name="accepts_custom_sizes" value="1" id="custom-sizes" <?= $image['accepts_custom_sizes'] ? 'checked' : '' ?>><label class="form-check-label" for="custom-sizes">Aceitar pedido de tamanho personalizado</label></div>
        <div class="col-12"><label class="form-label">Orientação para tamanho personalizado</label><input name="custom_size_note" class="form-control bg-black text-white border-secondary" value="<?= old('custom_size_note', $image['custom_size_note'] ?? '') ?>" placeholder="Ex.: Outros tamanhos sob consulta, conforme proporção da imagem."></div>
        <div class="col-12"><hr><h5>Adicionar tamanho padrão</h5></div>
        <div class="col-md-3"><label class="form-label">Nome</label><input name="size_label" class="form-control bg-black text-white border-secondary" placeholder="40 × 60 cm"></div>
        <div class="col-md-2"><label class="form-label">Largura (cm)</label><input type="number" step="0.01" min="0" name="width_cm" class="form-control bg-black text-white border-secondary"></div>
        <div class="col-md-2"><label class="form-label">Altura (cm)</label><input type="number" step="0.01" min="0" name="height_cm" class="form-control bg-black text-white border-secondary"></div>
        <div class="col-md-2"><label class="form-label">Preço (R$)</label><input type="number" step="0.01" min="0" name="price" class="form-control bg-black text-white border-secondary"></div>
        <div class="col-md-1"><label class="form-label">Ordem</label><input type="number" name="display_order" value="0" class="form-control bg-black text-white border-secondary"></div>
        <div class="col-md-2 form-check align-self-end ms-2"><input class="form-check-input" type="checkbox" name="is_available" value="1" id="available" checked><label class="form-check-label" for="available">Disponível</label></div>
        <div class="col-12"><button class="btn btn-success">Salvar configurações</button></div>
    </div>
</form>

<div class="card bg-dark border-secondary"><div class="table-responsive"><table class="table table-dark align-middle mb-0"><thead><tr><th>Tamanho</th><th>Dimensões</th><th>Preço</th><th>Disponibilidade</th><th class="text-end">Ação</th></tr></thead><tbody>
<?php foreach ($options as $option): ?><tr><td><?= esc($option['size_label']) ?></td><td><?= $option['width_cm'] && $option['height_cm'] ? esc($option['width_cm'] . ' × ' . $option['height_cm'] . ' cm') : '—' ?></td><td>R$ <?= number_format($option['price_cents'] / 100, 2, ',', '.') ?></td><td><?= $option['is_available'] ? 'Disponível' : 'Indisponível' ?></td><td class="text-end"><a class="btn btn-sm btn-outline-light" href="<?= site_url('admin/fotos/' . $work['id'] . '/imagens/' . $image['id'] . '/edicoes/' . $option['id'] . '/edit') ?>">Editar</a> <form class="d-inline" method="post" action="<?= site_url('admin/fotos/' . $work['id'] . '/imagens/' . $image['id'] . '/edicoes/' . $option['id'] . '/excluir') ?>" onsubmit="return confirm('Remover este tamanho?')"><button class="btn btn-sm btn-outline-danger">Remover</button></form></td></tr><?php endforeach ?>
<?php if (!$options): ?><tr><td colspan="4" class="text-center text-muted py-4">Nenhum tamanho cadastrado.</td></tr><?php endif ?></tbody></table></div></div>
<?= $this->endSection() ?>
