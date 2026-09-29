<?= $this->extend('layout/main') ?>
<?= $this->section('styles') ?>
<link rel="stylesheet" href="<?= base_url('assets/css/photo-gallery.css') ?>?v=<?= filemtime(FCPATH . 'assets/css/photo-gallery.css') ?>">
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<main class="art-page">
    <header class="art-heading">
        <p>James Webb Studio</p>
        <h1>Fotografias Autorais</h1>
        <p>Imagens, histórias e fragmentos de tempo preservados em luz.</p>
    </header>
    <nav class="filters" aria-label="Filtrar por coleção">
        <a class="<?= $selectedCollection === '' ? 'active' : '' ?>" href="<?= site_url('fotos') ?>">Todas</a>
        <?php foreach ($collections as $collection): ?>
            <a class="<?= $selectedCollection === $collection['collection_name'] ? 'active' : '' ?>" href="<?= site_url('fotos') . '?colecao=' . urlencode($collection['collection_name']) ?>"><?= esc($collection['collection_name']) ?></a>
        <?php endforeach ?>
    </nav>
    <section class="art-grid" aria-label="Galerias de fotografias">
        <?php foreach ($works as $work): ?>
            <a class="art-card" href="<?= site_url('fotos/' . $work['slug']) ?>">
                <?php if (!empty($work['cover_image'])): ?>
                    <img src="<?= base_url($work['cover_image']) ?>" alt="<?= esc(($work['cover_alt'] ?? '') ?: $work['title']) ?>" loading="lazy">
                <?php endif ?>
                <div class="art-card-info">
                    <h2><?= esc($work['title']) ?></h2>
                    <small><?= esc($work['work_date'] ? date('Y', strtotime($work['work_date'])) : ($work['collection_name'] ?? '')) ?></small>
                    <span class="gallery-photo-count"><?= $work['photo_count'] ?> <?= $work['photo_count'] === 1 ? 'fotografia' : 'fotografias' ?></span>
                </div>
            </a>
        <?php endforeach ?>
    </section>
    <?php if (!$works): ?>
        <p class="text-center text-muted py-5">O acervo será publicado em breve.</p>
    <?php endif ?>
</main>
<?= $this->endSection() ?>
