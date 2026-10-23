<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>
<main class="container" style="padding-top:130px;padding-bottom:80px;max-width:1100px">
<h1><?= esc($heading) ?></h1><p>Conheça as possibilidades para o seu projeto e solicite um orçamento.</p>
<?php foreach ($groups as $group): ?><section class="my-5"><h2><a class="text-gold" href="<?= site_url('servicos/' . $group['family']['slug']) ?>"><?= esc($group['family']['name']) ?></a></h2><p><?= nl2br(esc($group['family']['description'])) ?></p><div class="row g-4"><?php foreach ($group['pages'] as $p): ?><article class="col-md-6"><h3 class="h4"><a class="text-gold" href="<?= site_url($p['path']) ?>"><?= esc($p['data']['h1']) ?></a></h3><p><?= esc($p['data']['meta_description']) ?></p></article><?php endforeach; ?></div></section><?php endforeach; ?>
</main>
<?= $this->endSection() ?>
