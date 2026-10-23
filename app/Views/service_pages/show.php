<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>
<main class="container" style="padding-top:130px;padding-bottom:80px;max-width:1050px">
<?php if ($preview): ?><div class="alert alert-warning">Prévia de rascunho — ainda não publicada.</div><?php endif; ?>
<nav aria-label="Caminho da página"><ol class="breadcrumb"><li class="breadcrumb-item"><a class="text-gold" href="<?= site_url('/') ?>">Início</a></li><li class="breadcrumb-item"><a class="text-gold" href="<?= site_url('servicos') ?>">Serviços</a></li><li class="breadcrumb-item"><a class="text-gold" href="<?= site_url('servicos/' . $family['slug']) ?>"><?= esc($family['name']) ?></a></li></ol></nav>
<h1 class="display-4 text-gold"><?= esc($content['h1']) ?></h1><p class="lead mt-4"><?= nl2br(esc($content['introduction'])) ?></p>
<?php if (! $preview): ?>
<script type="application/ld+json"><?= json_encode(['@context' => 'https://schema.org', '@type' => 'Service', 'name' => $content['h1'], 'description' => $content['meta_description'], 'url' => $canonicalUrl, 'provider' => ['@id' => 'https://jameswebbstudio.com.br/#business']], JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?></script>
<?php endif; ?>
<a class="btn btn-terroso my-4" href="<?= esc($whatsapp) ?>" rel="noopener" target="_blank">Conversar sobre meu projeto no WhatsApp</a>
<?php foreach ($content['sections'] as $section): ?><section class="my-5"><h2><?= esc($section['heading']) ?></h2><p style="line-height:1.9"><?= nl2br(esc($section['body'])) ?></p></section><?php endforeach; ?>
<?php if ($photos): ?><section class="my-5"><h2>Portfólio</h2><div class="row g-4"><?php foreach ($photos as $photo): $cover = (new \App\Libraries\ResponsiveCover())->describe($photo['image_path']); $variants = $cover['variants'] ?? []; $srcset = []; foreach ($variants as $w => $v) $srcset[] = base_url($v) . ' ' . $w . 'w'; ?><figure class="col-md-6"><img class="img-fluid" src="<?= esc(base_url($variants ? reset($variants) : $photo['image_path'])) ?>" alt="<?= esc($photo['caption'] ?: 'Ensaio de ' . $photo['hero_name']) ?>" loading="lazy" decoding="async" <?php if ($cover): ?>width="<?= (int) $cover['width'] ?>" height="<?= (int) $cover['height'] ?>"<?php endif; ?> <?php if ($srcset): ?>srcset="<?= esc(implode(', ', $srcset)) ?>" sizes="(max-width:767px) 100vw, 500px"<?php endif; ?>></figure><?php endforeach; ?></div></section><?php endif; ?>
<?php if ($content['faq']): ?><section class="my-5"><h2>Perguntas frequentes</h2><?php foreach ($content['faq'] as $faq): ?><details class="py-3 border-bottom"><summary><?= esc($faq['question']) ?></summary><p class="mt-3"><?= nl2br(esc($faq['answer'])) ?></p></details><?php endforeach; ?></section><?php endif; ?>
<section class="my-5"><h2>Vamos conversar sobre o seu projeto?</h2><a class="btn btn-terroso mt-3" href="<?= esc($whatsapp) ?>" rel="noopener" target="_blank">Solicitar orçamento pelo WhatsApp</a></section>
</main>
<?= $this->endSection() ?>
