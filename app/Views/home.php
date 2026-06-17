<?= $this->extend('layout/main') ?>

<?= $this->section('styles') ?>
<style>
    /* ── Hero Section ── */
    .home-hero {
        min-height: 85vh;
        display: flex;
        align-items: center;
        justify-content: center;
        text-align: center;
        padding: 120px 24px 80px;
        background: #000;
        position: relative;
        overflow: hidden;
    }
    .home-hero::before {
        content: '';
        position: absolute;
        bottom: 0; left: 0; right: 0;
        height: 200px;
        background: linear-gradient(to top, #000, transparent);
        z-index: 1;
    }
    .home-hero-content { position: relative; z-index: 2; max-width: 860px; }
    .home-hero-eyebrow {
        font-family: 'Inter', sans-serif;
        font-size: .6rem; letter-spacing: .35em;
        text-transform: uppercase; color: #C5A059;
        margin-bottom: 24px; display: block;
    }
    .home-hero-title {
        font-family: 'EB Garamond', Georgia, serif;
        font-size: clamp(2.2rem, 6vw, 4.5rem);
        font-weight: 400; color: #fff;
        line-height: 1.15; margin-bottom: 20px;
    }
    .home-hero-title em {
        font-style: italic; color: #C5A059;
    }
    .home-hero-sub {
        font-family: 'EB Garamond', Georgia, serif;
        font-size: clamp(1rem, 2.2vw, 1.3rem);
        color: rgba(255,255,255,.5); font-style: italic;
        max-width: 600px; margin: 0 auto 40px;
        line-height: 1.6;
    }
    .home-hero-cta {
        display: inline-block;
        padding: 16px 48px;
        border: 1px solid rgba(197,160,89,.5);
        color: #C5A059;
        font-family: 'Inter', sans-serif;
        font-size: .68rem; letter-spacing: .2em;
        text-transform: uppercase; text-decoration: none;
        transition: all .3s;
    }
    .home-hero-cta:hover {
        background: rgba(197,160,89,.1);
        border-color: #C5A059; color: #C5A059;
        transform: translateY(-2px);
    }

    /* ── Section Title ── */
    .section-title-block {
        text-align: center;
        padding: 80px 0 48px;
        background: #000;
    }
    .section-title-block .divider {
        width: 48px; height: 1px;
        background: rgba(197,160,89,.4);
        margin: 0 auto 20px;
    }
    .section-title-block h2 {
        font-family: 'EB Garamond', Georgia, serif;
        font-size: clamp(1.6rem, 4vw, 2.6rem);
        font-weight: 400; color: #fff; margin: 0 0 8px;
    }
    .section-title-block p {
        font-family: 'Inter', sans-serif;
        font-size: .7rem; letter-spacing: .2em;
        text-transform: uppercase;
        color: rgba(255,255,255,.25); margin: 0;
    }

    /* ── Page Container ── */
    .home-container {
        max-width: 1400px;
        margin: 0 auto;
        background: #000;
    }

    /* ── Portfolio Grid ── */
    .portfolio-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(340px, 1fr));
        gap: 2px;
        padding: 0 2px 2px;
    }
    @media (max-width: 575px) {
        .portfolio-grid { grid-template-columns: 1fr; }
    }

    /* ── Hero Card ── */
    .hero-card {
        position: relative; overflow: hidden;
        aspect-ratio: 3 / 4;
        background: #0a0a0a;
        cursor: pointer;
        text-decoration: none;
        display: block;
    }
    .hero-card img {
        width: 100%; height: 100%;
        object-fit: cover;
        transition: transform .6s cubic-bezier(.25,.46,.45,.94), opacity .4s;
        opacity: .85;
    }
    .hero-card:hover img {
        transform: scale(1.05);
        opacity: 1;
    }

    /* Overlay gradiente */
    .hero-card-overlay {
        position: absolute; inset: 0;
        background: linear-gradient(
            to top,
            rgba(0,0,0,.9) 0%,
            rgba(0,0,0,.4) 35%,
            rgba(0,0,0,.05) 60%,
            transparent 100%
        );
        transition: background .3s;
    }
    .hero-card:hover .hero-card-overlay {
        background: linear-gradient(
            to top,
            rgba(0,0,0,.85) 0%,
            rgba(0,0,0,.2) 40%,
            transparent 100%
        );
    }

    /* Category badge */
    .hero-card-category {
        position: absolute; top: 16px; left: 16px; z-index: 3;
        font-family: 'Inter', sans-serif;
        font-size: .55rem; font-weight: 600;
        letter-spacing: .18em; text-transform: uppercase;
        color: #000;
        background: linear-gradient(135deg, #C5A059, #F5E27A);
        padding: 5px 14px;
    }

    /* Info na base */
    .hero-card-info {
        position: absolute; bottom: 0; left: 0; right: 0;
        padding: 24px; z-index: 3;
    }
    .hero-card-name {
        font-family: 'EB Garamond', Georgia, serif;
        font-size: clamp(1.5rem, 3vw, 2rem);
        font-weight: 400; color: #fff;
        margin: 0 0 4px; line-height: 1.2;
    }
    .hero-card-sport {
        font-family: 'Inter', sans-serif;
        font-size: .65rem; letter-spacing: .15em;
        text-transform: uppercase;
        color: rgba(255,255,255,.45);
        margin: 0 0 16px;
    }
    .hero-card-cta {
        font-family: 'Inter', sans-serif;
        font-size: .6rem; letter-spacing: .2em;
        text-transform: uppercase;
        color: #C5A059;
        opacity: 0; transform: translateY(8px);
        transition: all .3s;
    }
    .hero-card:hover .hero-card-cta {
        opacity: 1; transform: translateY(0);
    }

    /* Quando não tem foto */
    .hero-card-empty {
        display: flex; align-items: center; justify-content: center;
        background: #0a0a0a;
    }
    .hero-card-empty .placeholder-icon {
        font-size: 2rem; opacity: .15;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="home-container">

<!-- ── Hero Section ── -->
<section class="home-hero">
    <div class="home-hero-content">
        <span class="home-hero-eyebrow">James Webb Studio</span>
        <h1 class="home-hero-title">A imagem que <em>revela</em> quem você é</h1>
        <p class="home-hero-sub">
            Ensaios fotográficos com luz dramática e direção artística para profissionais, atletas e artistas que precisam de uma imagem à altura da sua história.
        </p>
        <a href="#ensaios" class="home-hero-cta">Ver ensaios realizados</a>
    </div>
</section>

<!-- ── Ensaios Realizados ── -->
<div class="section-title-block" id="ensaios">
    <div class="divider"></div>
    <h2>Ensaios Realizados</h2>
    <p>Cada ensaio é uma história. Encontre a sua.</p>
</div>

<section class="portfolio-grid">
    <?php if(!empty($heroes)): ?>
        <?php foreach($heroes as $hero): ?>
            <a href="<?= site_url($hero['slug']) ?>" class="hero-card">
                <?php if(!empty($hero['cover_image'])): ?>
                    <img src="<?= base_url($hero['cover_image']) ?>" alt="<?= esc($hero['name']) ?>" loading="lazy">
                <?php else: ?>
                    <div class="hero-card-empty">
                        <span class="placeholder-icon">📷</span>
                    </div>
                <?php endif; ?>

                <div class="hero-card-overlay"></div>

                <?php if(!empty($hero['category_name'])): ?>
                    <div class="hero-card-category"><?= esc($hero['category_name']) ?></div>
                <?php endif; ?>

                <div class="hero-card-info">
                    <h3 class="hero-card-name"><?= esc($hero['name']) ?></h3>
                    <?php if(!empty($hero['sport'])): ?>
                        <p class="hero-card-sport"><?= esc($hero['sport']) ?></p>
                    <?php endif; ?>
                    <span class="hero-card-cta">Ver ensaio completo →</span>
                </div>
            </a>
        <?php endforeach; ?>
    <?php else: ?>
        <div style="grid-column:1/-1; text-align:center; padding:80px 24px; color:rgba(255,255,255,.3);">
            <p style="font-size:1rem; font-family:'EB Garamond',serif; font-style:italic;">Em breve, novas histórias.</p>
        </div>
    <?php endif; ?>
</section>

</div><!-- /.home-container -->
<?= $this->endSection() ?>
