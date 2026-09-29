<?= $this->extend('layout/main') ?>
<?= $this->section('styles') ?>
<link rel="stylesheet" href="<?= base_url('assets/css/photo-gallery.css') ?>">
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<main class="work-page">
    <a class="text-gold text-decoration-none small" href="<?= site_url('fotos') ?>">← Voltar às fotografias</a>

    <?php if ($currentImage): ?>
        <div class="work-stage">
            <div class="work-frame">
                <img class="work-main" src="<?= base_url($currentImage['image_path']) ?>" alt="<?= esc($currentImage['alt_text'] ?: $work['title']) ?>">
            </div>
            <?php if ($totalImages > 1): ?>
                <nav class="photo-navigator" aria-label="Navegação entre fotografias">
                    <?php if ($previousImage): ?>
                        <a data-gallery-previous href="<?= site_url('fotos/' . $work['slug']) . '?imagem=' . (int) $previousImage['id'] ?>" aria-label="Fotografia anterior">←</a>
                    <?php else: ?>
                        <span aria-hidden="true">←</span>
                    <?php endif ?>
                    <?php if ($nextImage): ?>
                        <a data-gallery-next href="<?= site_url('fotos/' . $work['slug']) . '?imagem=' . (int) $nextImage['id'] ?>" aria-label="Próxima fotografia">→</a>
                    <?php else: ?>
                        <span aria-hidden="true">→</span>
                    <?php endif ?>
                </nav>
            <?php endif ?>
        </div>
        <?php if ($totalImages > 1): ?>
            <p class="photo-position">Fotografia <?= $currentIndex + 1 ?> de <?= $totalImages ?></p>
        <?php endif ?>
        <?php if (!empty($currentImage['is_for_sale'])): ?>
            <div class="photo-commerce-actions" aria-label="Ações de compra">
                <?php if ($printOptions): ?><a class="photo-buy-button" data-photo-buy target="_blank" rel="noopener">Comprar esta edição</a><?php endif ?>
                <a class="photo-quote-button" data-photo-quote target="_blank" rel="noopener">Pedir orçamento</a>
            </div>
        <?php endif ?>
        <section class="photo-share-control" aria-label="Compartilhar fotografia">
            <button class="photo-share-trigger" type="button" data-share-trigger aria-expanded="false" aria-controls="photo-share-options">Compartilhar fotografia</button>
            <div class="photo-share-options" id="photo-share-options" data-share-options hidden>
                <a data-share-whatsapp target="_blank" rel="noopener">WhatsApp</a>
                <a data-share-facebook target="_blank" rel="noopener">Facebook</a>
                <a data-share-x target="_blank" rel="noopener">X</a>
                <button type="button" data-share-native>Mais apps</button>
                <button type="button" data-share-copy>Copiar link</button>
            </div>
            <p class="photo-share-feedback" data-share-feedback aria-live="polite"></p>
        </section>
    <?php endif ?>

    <div class="work-layout">
        <article>
            <h1><?= esc($work['title']) ?></h1>
            <p class="lead text-white-50"><?= esc($work['short_description']) ?></p>
            <div class="work-story"><?= esc($work['story']) ?></div>
        </article>
        <aside class="work-meta">
            <dl>
                <?php if ($work['work_date']): ?><dt>Data</dt><dd><?= esc(date('d/m/Y', strtotime($work['work_date']))) ?></dd><?php endif ?>
                <?php if ($work['location']): ?><dt>Local</dt><dd><?= esc($work['location']) ?></dd><?php endif ?>
                <?php if ($work['technique']): ?><dt>Técnica</dt><dd><?= esc($work['technique']) ?></dd><?php endif ?>
                <?php if ($work['collection_name']): ?><dt>Coleção</dt><dd><?= esc($work['collection_name']) ?></dd><?php endif ?>
                <?php if ($work['credits']): ?><dt>Créditos</dt><dd><?= esc($work['credits']) ?></dd><?php endif ?>
            </dl>
            <section class="frame-theme-control" aria-labelledby="frame-theme-label">
                <p id="frame-theme-label">Cor da moldura</p>
                <div class="frame-theme-options">
                    <button type="button" data-frame-theme="graphite" aria-pressed="true">Cinza</button>
                    <button type="button" data-frame-theme="black" aria-pressed="false">Preto</button>
                    <button type="button" data-frame-theme="brown" aria-pressed="false">Marrom</button>
                    <button type="button" data-frame-theme="white" aria-pressed="false">Branco</button>
                </div>
            </section>
            <?php if (!empty($currentImage['is_for_sale'])): ?>
                <section class="photo-sale-control" aria-labelledby="photo-sale-label">
                    <p id="photo-sale-label">Do pixel ao papel</p>
                    <h2>Leve esta fotografia para a sua parede</h2>
                    <?php if ($printOptions): ?>
                        <label for="print-option">Escolha o tamanho</label>
                        <select id="print-option" data-print-option>
                            <?php foreach ($printOptions as $option): ?>
                                <option value="<?= (int) $option['price_cents'] ?>" data-label="<?= esc($option['size_label'], 'attr') ?>" data-print-material="<?= esc($option['print_material'] ?? '', 'attr') ?>" data-frame-material="<?= esc($option['frame_material'] ?? '', 'attr') ?>" data-backing-material="<?= esc($option['backing_material'] ?? '', 'attr') ?>" data-glazing="<?= esc($option['glazing'] ?? '', 'attr') ?>" data-weight="<?= esc($option['weight_grams'] ?? '', 'attr') ?>" data-lead-time="<?= esc($option['production_lead_time'] ?? '', 'attr') ?>"><?= esc($option['size_label']) ?> — R$ <?= number_format($option['price_cents'] / 100, 2, ',', '.') ?></option>
                            <?php endforeach ?>
                        </select>
                        <p class="photo-sale-price">A partir de <strong data-print-price>R$ <?= number_format($printOptions[0]['price_cents'] / 100, 2, ',', '.') ?></strong></p>
                        <dl class="photo-print-specifications" data-print-specifications>
                            <div data-spec-row="print-material"><dt>Impressão</dt><dd data-spec="print-material"></dd></div>
                            <div data-spec-row="frame-material"><dt>Moldura</dt><dd data-spec="frame-material"></dd></div>
                            <div data-spec-row="backing-material"><dt>Fundo</dt><dd data-spec="backing-material"></dd></div>
                            <div data-spec-row="glazing"><dt>Proteção</dt><dd data-spec="glazing"></dd></div>
                            <div data-spec-row="weight"><dt>Peso</dt><dd data-spec="weight"></dd></div>
                            <div data-spec-row="lead-time"><dt>Produção</dt><dd data-spec="lead-time"></dd></div>
                        </dl>
                    <?php endif ?>
                    <a class="photo-sale-button" data-print-interest href="mailto:contato@jameswebbstudio.com.br">Quero esta edição</a>
                    <?php if (!empty($currentImage['accepts_custom_sizes'])): ?>
                        <p class="photo-sale-custom"><?= esc($currentImage['custom_size_note'] ?: 'Outros tamanhos sob consulta, respeitando a proporção original da fotografia.') ?></p>
                    <?php endif ?>
                </section>
            <?php endif ?>
        </aside>
    </div>
</main>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="<?= base_url('assets/js/photo-gallery.js') ?>" defer></script>
<?= $this->endSection() ?>
