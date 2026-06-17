<?= $this->extend('layout/main') ?>

<?= $this->section('styles') ?>
<!-- Swiper CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
<style>
/* ── Estilos dos Blocos de Conteúdo (reutilizados da Landing Page) ────── */
.lp-section { padding: 72px 0; }
.lp-section + .lp-section { padding-top: 0; }
.lp-headline {
    position: relative; min-height: 80vh;
    display: flex; align-items: center; justify-content: center;
    text-align: center; padding: 100px 24px 80px;
    background-color: #000; overflow: hidden;
}
.lp-headline-bg { position:absolute;inset:0;object-fit:cover;width:100%;height:100%;opacity:.5; }
.lp-headline-overlay { position:absolute;inset:0;background:linear-gradient(to bottom,rgba(0,0,0,.15) 0%,rgba(0,0,0,.55) 50%,rgba(0,0,0,.85) 100%); }
.lp-headline-content { position:relative;z-index:2;max-width:820px; }
.lp-headline-content h2 { font-family:'EB Garamond',Georgia,serif;font-size:clamp(2.4rem,7vw,5rem);font-weight:500;color:#C5A059;line-height:1.2;margin-bottom:24px; }
.lp-headline-content p { font-size:clamp(1rem,2.5vw,1.4rem);color:rgba(255,255,255,.75);font-family:'EB Garamond',Georgia,serif;font-style:italic;max-width:640px;margin:0 auto; }
.lp-text-block { background:#000;padding:60px 0; }
.lp-body { font-family:'EB Garamond',Georgia,serif;font-size:clamp(1.05rem,2vw,1.3rem);color:rgba(255,255,255,.82);line-height:1.85;max-width:720px; }
.lp-body.text-center { margin:0 auto; }
.lp-image-block { background:#000;padding:40px 0; }
.lp-image-contained img { max-width:900px;width:100%;height:auto;display:block;margin:0 auto; }
.lp-image-full img { width:100%;height:auto;display:block; }
.lp-image-caption { text-align:center;font-family:'EB Garamond',Georgia,serif;font-style:italic;font-size:.95rem;color:rgba(255,255,255,.4);margin-top:12px; }
.lp-video-block { background:#000;padding:60px 0; }
.lp-video-wrap { position:relative;padding-bottom:56.25%;height:0;max-width:900px;margin:0 auto; }
.lp-video-wrap iframe { position:absolute;inset:0;width:100%;height:100%;border:none; }
.lp-video-title { text-align:center;font-family:'EB Garamond',Georgia,serif;font-style:italic;color:rgba(255,255,255,.4);margin-top:16px;font-size:.95rem; }
.lp-testimony { background:#070707;padding:72px 0;border-top:1px solid rgba(197,160,89,.1);border-bottom:1px solid rgba(197,160,89,.1); }
.lp-testimony-inner { max-width:760px;margin:0 auto;text-align:center; }
.lp-testimony-quote { font-family:'EB Garamond',Georgia,serif;font-size:clamp(1.3rem,3vw,2rem);font-style:italic;color:rgba(255,255,255,.85);line-height:1.6;margin-bottom:28px; }
.lp-testimony-quote::before{content:'\201C';color:#C5A059;}.lp-testimony-quote::after{content:'\201D';color:#C5A059;}
.lp-testimony-author { font-family:'Inter',sans-serif;font-size:.75rem;letter-spacing:.2em;text-transform:uppercase;color:rgba(255,255,255,.4); }
.lp-testimony-photo { width:72px;height:72px;border-radius:50%;object-fit:cover;margin:0 auto 20px;border:1px solid rgba(197,160,89,.3);display:block; }
.lp-process { background:#000;padding:72px 0; }
.lp-process-step { text-align:center;padding:0 24px; }
.lp-process-number { font-family:'EB Garamond',Georgia,serif;font-size:3rem;color:#C5A059;opacity:.5;line-height:1;margin-bottom:12px; }
.lp-process-title { font-family:'EB Garamond',Georgia,serif;font-size:1.3rem;color:#fff;margin-bottom:8px; }
.lp-process-desc { font-size:.9rem;color:rgba(255,255,255,.45);line-height:1.6; }
.lp-cta-btn-block { background:#000;padding:72px 24px;text-align:center; }
.lp-divider { width:48px;height:1px;background:rgba(197,160,89,.4);margin:0 auto; }
    /* ── Variáveis da Moldura Dourada ─────────────────────────────────── */
    :root {
        --gold-light:  #F5E27A; /* Ouro claro — topo iluminado */
        --gold-mid:    #C9A84C; /* Ouro médio — laterais */
        --gold-dark:   #8B6914; /* Ouro escuro — base sombreada */
        --gold-glow:   rgba(197, 160, 89, 0.35);
    }

    .swiper {
        width: 100%;
        background: #050505;
        padding-bottom: 44px;
    }
    .swiper-slide {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        position: relative;
        padding: 88px 5vw 48px 5vw;
        box-sizing: border-box;
        background-color: #050505;
    }

    /* ── Moldura Dourada Metálica ──────────────────────────────────────── */
    .swiper-slide img {
        max-width: 100%;
        max-height: 72vh;
        width: auto;
        height: auto;
        object-fit: contain;
        cursor: pointer;

        /* Moldura dourada fina com gradiente metálico nas 4 faces */
        border-style: solid;
        border-width: clamp(8px, 1.2vw, 16px);

        border-top-color:    var(--gold-light);
        border-right-color:  var(--gold-dark);
        border-bottom-color: var(--gold-dark);
        border-left-color:   var(--gold-light);

        border-radius: 1px;

        /* Brilho externo dourado + sombra profunda */
        box-shadow:
            0 0 0 1px var(--gold-mid),               /* linha fina ao redor da moldura */
            0 0 18px 2px var(--gold-glow),            /* halo dourado suave */
            0 25px 55px rgba(0, 0, 0, 0.65);          /* sombra de profundidade */

        transition: box-shadow 0.4s ease;
    }
    .swiper-slide.swiper-slide-active img {
        box-shadow:
            0 0 0 1px var(--gold-mid),
            0 0 28px 6px var(--gold-glow),
            0 30px 70px rgba(0, 0, 0, 0.75);
    }

    /* ── Legenda ───────────────────────────────────────────────────────── */
    .photo-caption {
        position: relative;
        width: 100%;
        padding: 0.9rem 1rem 0 1rem;
        background: transparent;
        color: #fff;
        text-align: center;
        z-index: 10;
    }
    .photo-caption p {
        font-size: clamp(0.85rem, 1.5vw, 1.1rem);
        font-family: 'Cinzel', serif;
        max-width: 780px;
        margin: 0 auto;
        line-height: 1.6;
        font-weight: 300;
        letter-spacing: 0.08em;
        color: rgba(255, 255, 255, 0.72);
        text-shadow: 1px 1px 8px rgba(0,0,0,0.9);
    }
    .cta-section {
        background-color: #050505;
        padding: 60px 0 120px 0;
        position: relative;
        overflow: hidden;
    }
    .cta-section::before {
        content: '';
        position: absolute;
        top: 0;
        left: 50%;
        transform: translateX(-50%);
        width: 150vw;
        height: 100%;
        background: radial-gradient(circle at center, rgba(255, 255, 255, 0.05) 0%, transparent 70%);
        pointer-events: none;
    }
    .cta-card {
        max-width: 900px;
        padding: 4rem 3rem;
        background: rgba(255, 255, 255, 0.02);
        backdrop-filter: blur(15px);
        -webkit-backdrop-filter: blur(15px);
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 4px;
        box-shadow: 0 40px 100px rgba(0,0,0,0.5);
        text-align: center;
        position: relative;
        z-index: 10;
        transition: transform 0.3s ease, border-color 0.3s ease;
    }
    .cta-card:hover {
        border-color: rgba(255, 255, 255, 0.15);
    }
    .cta-eyebrow {
        display: block;
        text-uppercase: uppercase;
        font-size: 0.85rem;
        letter-spacing: 5px;
        color: #888;
        margin-bottom: 1.5rem;
        font-weight: 300;
    }
    .cta-title {
        font-size: clamp(2rem, 4vw, 3.5rem);
        margin-bottom: 2rem;
        color: #fff;
        line-height: 1.1;
    }
    .cta-copy {
        font-size: 1.25rem;
        color: rgba(255, 255, 255, 0.7);
        line-height: 1.8;
        max-width: 700px;
        margin: 0 auto;
        font-weight: 300;
    }
    .btn-hero-premium {
        position: relative;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 1.2rem 3.5rem;
        background: #fff;
        color: #000;
        text-decoration: none;
        text-transform: uppercase;
        font-weight: 700;
        font-family: inherit;
        letter-spacing: 3px;
        font-size: 0.9rem;
        border: none;
        border-radius: 0;
        overflow: hidden;
        transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
        cursor: pointer;
    }
    .btn-hero-premium:hover {
        background: #000;
        color: #fff;
        transform: translateY(-5px);
        box-shadow: 0 15px 30px rgba(0,0,0,0.4), 0 0 20px rgba(255,255,255,0.1);
    }
    .btn-shine {
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(120deg, transparent, rgba(255,255,255,0.3), transparent);
        transition: all 0.6s;
    }
    .btn-hero-premium:hover .btn-shine {
        left: 100%;
    }
    .glass-modal {
        background: rgba(10, 10, 10, 0.9) !important;
        backdrop-filter: blur(20px);
        border: 1px solid rgba(255,255,255,0.1);
    }
    .modal-content input, .modal-content textarea {
        transition: border-color 0.3s ease, box-shadow 0.3s ease;
    }
    .modal-content input:focus, .modal-content textarea:focus {
        border-color: #fff !important;
        box-shadow: 0 0 15px rgba(255,255,255,0.1);
        background-color: #000 !important;
    }
/* ── Package Cards ─────────────────────────────────────────────────── */
.pkg-card{background:#0a0a0a;border:1px solid rgba(255,255,255,.1);padding:36px 28px 28px;position:relative;transition:border-color .3s,transform .3s;height:100%;display:flex;flex-direction:column;}
.pkg-card:hover{border-color:rgba(197,160,89,.35);transform:translateY(-4px);}
.pkg-preferred{border-color:rgba(197,160,89,.55)!important;background:linear-gradient(160deg,#0e0c07 0%,#0a0a0a 100%);}
.pkg-badge{position:absolute;top:-1px;left:50%;transform:translateX(-50%);background:linear-gradient(90deg,#C5A059,#F5E27A);color:#000;font-family:'Inter',sans-serif;font-size:.6rem;font-weight:700;letter-spacing:.15em;padding:4px 16px;white-space:nowrap;}
.pkg-name{font-family:'Inter',sans-serif;font-size:.7rem;letter-spacing:.2em;text-transform:uppercase;color:rgba(255,255,255,.4);margin-bottom:16px;margin-top:12px;}
.pkg-price{font-family:'EB Garamond',Georgia,serif;font-size:clamp(2.4rem,5vw,3.4rem);color:#fff;font-weight:400;line-height:1;margin-bottom:4px;}
.pkg-currency{font-size:1.1rem;color:#C5A059;vertical-align:super;margin-right:4px;}
.pkg-photos{font-family:'Inter',sans-serif;font-size:.78rem;color:#C5A059;letter-spacing:.08em;margin-bottom:16px;}
.pkg-desc{font-family:'EB Garamond',Georgia,serif;font-size:.95rem;color:rgba(255,255,255,.45);line-height:1.6;margin-bottom:16px;flex:1;}
.pkg-extra{font-family:'Inter',sans-serif;font-size:.7rem;color:rgba(255,255,255,.3);margin-bottom:24px;letter-spacing:.05em;}
.pkg-btn-buy{width:100%;background:linear-gradient(135deg,#C5A059,#F5E27A,#C5A059);background-size:200%;color:#000;border:none;padding:14px;font-family:'Inter',sans-serif;font-size:.68rem;font-weight:700;letter-spacing:.18em;text-transform:uppercase;cursor:pointer;transition:background-position .4s;margin-bottom:10px;}
.pkg-btn-buy:hover{background-position:100%;}
.pkg-btn-talk{width:100%;background:transparent;border:none;color:rgba(255,255,255,.3);font-family:'Inter',sans-serif;font-size:.68rem;letter-spacing:.05em;cursor:pointer;padding:8px;transition:color .2s;text-decoration:underline;text-underline-offset:3px;}
.pkg-btn-talk:hover{color:rgba(197,160,89,.7);}
.pkg-card-alt{opacity:.85;}
.pkg-expand-btn{background:transparent;border:1px solid rgba(197,160,89,.25);color:rgba(255,255,255,.5);font-family:'Inter',sans-serif;font-size:.72rem;letter-spacing:.15em;text-transform:uppercase;padding:12px 32px;cursor:pointer;transition:all .2s;}
.pkg-expand-btn:hover{border-color:rgba(197,160,89,.5);color:#C5A059;}
.pkg-expand-icon{margin-left:8px;display:inline-block;transition:transform .3s;}
.pkg-expand-icon.open{transform:rotate(180deg);}

/* ── Service Checklist inside Package Cards ── */
.pkg-services { margin: 12px 0 20px; padding: 0; list-style: none; flex: 1; }
.pkg-services li {
    display: flex; align-items: flex-start; gap: 8px;
    padding: 5px 0; font-family: 'Inter', sans-serif;
    font-size: .72rem; color: rgba(255,255,255,.5);
    line-height: 1.35; position: relative;
    border-bottom: 1px solid rgba(255,255,255,.03);
}
.pkg-services li:last-child { border-bottom: none; }
.pkg-services .svc-check {
    color: #C5A059; font-size: .65rem;
    flex-shrink: 0; margin-top: 1px;
}
.pkg-services .svc-label { flex: 1; }
.pkg-services li:hover { color: rgba(255,255,255,.8); }
.pkg-services .svc-tooltip {
    display: none; position: absolute;
    bottom: 100%; left: 20px; right: 0;
    background: #1a1a1a; border: 1px solid rgba(197,160,89,.25);
    color: rgba(255,255,255,.65); padding: 8px 12px;
    font-size: .65rem; line-height: 1.45; z-index: 10;
    pointer-events: none;
}
.pkg-services li:hover .svc-tooltip { display: block; }
.pkg-phase-label {
    font-size: .55rem; letter-spacing: .12em; text-transform: uppercase;
    color: rgba(197,160,89,.45); margin-top: 8px; padding-top: 6px;
    border-top: 1px solid rgba(197,160,89,.08);
}
.pkg-phase-label:first-child { margin-top: 0; border-top: none; padding-top: 0; }

/* ── Page Container ── */
.hero-page-container { max-width: 1400px; margin: 0 auto; background: #000; }

/* ── Lightbox Fullscreen ── */
.lightbox-overlay {
    position: fixed; inset: 0; z-index: 9999;
    background: rgba(0,0,0,.96);
    display: none; align-items: center; justify-content: center;
    cursor: zoom-out;
    animation: lbFadeIn .3s ease;
}
.lightbox-overlay.active { display: flex; }
@keyframes lbFadeIn { from { opacity: 0; } to { opacity: 1; } }
.lightbox-overlay img {
    max-width: 95vw; max-height: 92vh;
    width: auto; height: auto;
    object-fit: contain;
    border: none; box-shadow: none;
    cursor: default;
    animation: lbZoomIn .3s ease;
}
@keyframes lbZoomIn { from { transform: scale(.92); opacity: 0; } to { transform: scale(1); opacity: 1; } }
.lightbox-close {
    position: absolute; top: 20px; right: 24px;
    background: none; border: none;
    color: rgba(255,255,255,.5); font-size: 2rem;
    cursor: pointer; transition: color .2s;
    z-index: 10;
}
.lightbox-close:hover { color: #fff; }
.lightbox-nav {
    position: absolute; top: 50%; transform: translateY(-50%);
    background: none; border: none;
    color: rgba(255,255,255,.35); font-size: 2.5rem;
    cursor: pointer; transition: color .2s;
    padding: 20px; z-index: 10;
}
.lightbox-nav:hover { color: rgba(255,255,255,.8); }
.lightbox-nav.prev { left: 8px; }
.lightbox-nav.next { right: 8px; }
.lightbox-counter {
    position: absolute; bottom: 20px; left: 50%;
    transform: translateX(-50%);
    font-family: 'Inter', sans-serif;
    font-size: .7rem; letter-spacing: .15em;
    color: rgba(255,255,255,.3);
}
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="hero-page-container">
<?php if(!empty($photos)): ?>
<div class="swiper mySwiper">
    <div class="swiper-wrapper">
        <?php foreach($photos as $photo): ?>
        <div class="swiper-slide">
            <img src="<?= base_url($photo['image_path']) ?>" alt="<?= esc($hero['name']) ?>"
                 onclick="openLightbox(this.src)" title="Clique para ver em tela cheia">
            <?php if(!empty($photo['caption'])): ?>
            <div class="photo-caption">
                <p><?= nl2br(esc($photo['caption'])) ?></p>
            </div>
            <?php endif; ?>
        </div>
        <?php endforeach; ?>
    </div>
    <div class="swiper-button-next text-white opacity-50"></div>
    <div class="swiper-button-prev text-white opacity-50"></div>
    <div class="swiper-pagination"></div>
</div>
<?php else: ?>
<div class="vh-100 d-flex align-items-center justify-content-center text-center pt-5">
    <div class="container pt-5">
        <h1 class="display-3 text-uppercase brand-font"><?= esc($hero['name']) ?></h1>
        <p class="lead opacity-75">Série especial: <?= esc($hero['sport']) ?></p>
        <p class="text-muted mt-5">Nenhuma foto épica adicionada ainda.</p>
    </div>
</div>
<?php endif; ?>

<?php if(!empty($blocks)): ?>
<?php foreach ($blocks as $block):
    $c    = $block['content'];
    $type = $block['type'];
?>

<?php if ($type === 'headline'): ?>
    <!-- ── HEADLINE ── -->
    <section class="lp-headline">
        <?php if (!empty($c['image_path'])): ?>
            <img src="<?= base_url($c['image_path']) ?>" class="lp-headline-bg" alt="">
        <?php endif; ?>
        <div class="lp-headline-overlay"></div>
        <div class="lp-headline-content">
            <?php if (!empty($c['title'])): ?>
                <h2><?= esc($c['title']) ?></h2>
            <?php endif; ?>
            <?php if (!empty($c['subtitle'])): ?>
                <p><?= esc($c['subtitle']) ?></p>
            <?php endif; ?>
        </div>
    </section>

<?php elseif ($type === 'text'): ?>
    <!-- ── TEXT ── -->
    <div class="lp-text-block">
        <div class="container px-4">
            <div class="lp-body <?= $c['align'] === 'center' ? 'text-center' : '' ?>">
                <?= $c['content'] ?>
            </div>
        </div>
    </div>

<?php elseif ($type === 'image'): ?>
    <!-- ── IMAGE ── -->
    <?php if (!empty($c['image_path'])): ?>
    <div class="lp-image-block">
        <div class="<?= $c['size'] === 'full' ? 'lp-image-full' : 'lp-image-contained px-4' ?>">
            <img src="<?= base_url($c['image_path']) ?>" alt="<?= esc($c['caption'] ?? '') ?>"
                 style="filter:drop-shadow(0 20px 40px rgba(0,0,0,0.6));">
            <?php if (!empty($c['caption'])): ?>
                <p class="lp-image-caption"><?= esc($c['caption']) ?></p>
            <?php endif; ?>
        </div>
    </div>
    <?php endif; ?>

<?php elseif ($type === 'video_embed'): ?>
    <!-- ── VIDEO ── -->
    <?php if (!empty($c['url'])): ?>
    <div class="lp-video-block">
        <div class="container px-4">
            <div class="lp-video-wrap">
                <?php
                    $url = $c['url'];
                    if (preg_match('/youtube\.com\/watch\?v=([\w-]+)/', $url, $m) ||
                        preg_match('/youtu\.be\/([\w-]+)/', $url, $m)) {
                        $embedUrl = 'https://www.youtube.com/embed/' . $m[1] . '?rel=0';
                    } elseif (preg_match('/vimeo\.com\/(\d+)/', $url, $m)) {
                        $embedUrl = 'https://player.vimeo.com/video/' . $m[1];
                    } else {
                        $embedUrl = $url;
                    }
                ?>
                <iframe src="<?= esc($embedUrl) ?>" allowfullscreen loading="lazy"
                        title="<?= esc($c['title'] ?? '') ?>"></iframe>
            </div>
            <?php if (!empty($c['title'])): ?>
                <p class="lp-video-title"><?= esc($c['title']) ?></p>
            <?php endif; ?>
        </div>
    </div>
    <?php endif; ?>

<?php elseif ($type === 'testimony'): ?>
    <!-- ── TESTIMONY ── -->
    <section class="lp-testimony">
        <div class="lp-testimony-inner px-4">
            <?php if (!empty($c['image_path'])): ?>
                <img src="<?= base_url($c['image_path']) ?>" class="lp-testimony-photo" alt="<?= esc($c['author'] ?? '') ?>">
            <?php endif; ?>
            <?php if (!empty($c['quote'])): ?>
                <p class="lp-testimony-quote"><?= esc($c['quote']) ?></p>
            <?php endif; ?>
            <p class="lp-testimony-author">
                <?= esc($c['author'] ?? '') ?>
                <?php if (!empty($c['sport'])): ?> &nbsp;·&nbsp; <?= esc($c['sport']) ?><?php endif; ?>
            </p>
        </div>
    </section>

<?php elseif ($type === 'process'): ?>
    <!-- ── PROCESS ── -->
    <?php if (!empty($c['steps'])): ?>
    <section class="lp-process">
        <div class="container px-4">
            <div class="row justify-content-center g-4">
                <?php foreach ($c['steps'] as $step): ?>
                <div class="col-md-4">
                    <div class="lp-process-step">
                        <div class="lp-process-number"><?= esc($step['number']) ?></div>
                        <div class="lp-process-title"><?= esc($step['title']) ?></div>
                        <div class="lp-process-desc"><?= esc($step['desc']) ?></div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <?php endif; ?>

<?php elseif ($type === 'cta_button'): ?>
    <!-- ── CTA BUTTON ── -->
    <div class="lp-cta-btn-block">
        <div class="lp-divider mb-5"></div>
        <button type="button" class="btn-hero-premium"
                onclick="document.getElementById('packages').scrollIntoView({behavior:'smooth'})">
            <?= esc($c['text'] ?? 'Quero meu ensaio') ?>
            <span class="btn-shine"></span>
        </button>
        <div class="lp-divider mt-5"></div>
    </div>

<?php elseif ($type === 'spacer'): ?>
    <!-- ── SPACER ── -->
    <?php $h = ['sm'=>'40px','md'=>'80px','lg'=>'160px'][$c['height'] ?? 'md'] ?? '80px'; ?>
    <div style="height:<?= $h ?>; background:#000;"></div>

<?php endif; ?>
<?php endforeach; ?>
<?php endif; ?>

<!-- ══ SEÇÃO DE PACOTES ══════════════════════════════════════════════ -->
<section id="packages" style="background:#000;padding:80px 0 60px;">
  <div class="container px-4">
    <div class="text-center mb-5">
      <div class="lp-divider mb-4"></div>
      <p style="font-family:'Inter',sans-serif;font-size:.7rem;letter-spacing:.25em;text-transform:uppercase;color:rgba(197,160,89,.6);margin-bottom:12px;">INVISTA NO SEU REGISTRO</p>
      <h2 style="font-family:'EB Garamond',Georgia,serif;font-size:clamp(2rem,5vw,3.2rem);color:#fff;font-weight:400;margin:0;">Pacotes de <?= esc($heroCatName) ?></h2>
    </div>

    <?php if (!empty($heroPackages)): ?>
    <div class="row justify-content-center g-4 mb-5">
      <?php foreach ($heroPackages as $pkg): ?>
      <div class="col-md-4">
        <div class="pkg-card <?= $pkg->is_preferred ? 'pkg-preferred' : '' ?>">
          <?php if ($pkg->is_preferred): ?><div class="pkg-badge">MAIS ESCOLHIDO</div><?php endif; ?>
          <div class="pkg-name"><?= esc($pkg->name) ?></div>
          <div class="pkg-price"><span class="pkg-currency">R$</span><?= number_format($pkg->base_price, 0, ',', '.') ?></div>
          <div class="pkg-photos"><?= (int)$pkg->included_photos ?> fotos tratadas</div>

          <?php if (!empty($pkg->services)): ?>
          <ul class="pkg-services">
            <?php
              $currentPhase = '';
              $phaseLabels = \App\Models\ServiceModel::PHASE_LABELS;
            ?>
            <?php foreach ($pkg->services as $svc): ?>
              <?php if ($svc->phase !== $currentPhase): ?>
                <?php $currentPhase = $svc->phase; ?>
                <li class="pkg-phase-label"><?= esc($phaseLabels[$currentPhase] ?? $currentPhase) ?></li>
              <?php endif; ?>
              <li>
                <span class="svc-check">✓</span>
                <span class="svc-label"><?= esc($svc->name) ?></span>
                <?php if (!empty($svc->description)): ?>
                  <span class="svc-tooltip"><?= esc($svc->description) ?></span>
                <?php endif; ?>
              </li>
            <?php endforeach; ?>
          </ul>
          <?php endif; ?>

          <?php if ($pkg->extra_photo_price > 0): ?><div class="pkg-extra">+ fotos por R$ <?= number_format($pkg->extra_photo_price, 0, ',', '.') ?> cada</div><?php endif; ?>
          <button class="pkg-btn-buy" onclick="openCheckout(<?= $pkg->id ?>,'<?= esc($pkg->name) ?>',<?= $pkg->base_price ?>,<?= $hero['id'] ?>)">ESCOLHER ESTE PACOTE</button>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
    <?php else: ?>
    <p class="text-center text-muted mb-5" style="font-size:.85rem;">Entre em contato para conhecer nossas opções.</p>
    <?php endif; ?>

    <?php if (!empty($otherPackages)): ?>
    <div class="text-center mb-4">
      <button class="pkg-expand-btn" onclick="toggleOtherPkgs(this)">Ver todos os tipos de ensaio <span class="pkg-expand-icon">↓</span></button>
    </div>
    <div id="otherPackages" style="display:none;">
      <?php foreach ($otherPackages as $catName => $pkgs): ?>
      <div class="mb-5">
        <h3 style="font-family:'EB Garamond',Georgia,serif;font-size:1.4rem;color:rgba(197,160,89,.7);text-align:center;letter-spacing:.1em;text-transform:uppercase;margin-bottom:24px;border-bottom:1px solid rgba(197,160,89,.1);padding-bottom:16px;"><?= esc($catName) ?></h3>
        <div class="row justify-content-center g-3">
          <?php foreach ($pkgs as $pkg): ?>
          <div class="col-md-4">
            <div class="pkg-card pkg-card-alt <?= $pkg->is_preferred ? 'pkg-preferred' : '' ?>">
              <?php if ($pkg->is_preferred): ?><div class="pkg-badge">MAIS ESCOLHIDO</div><?php endif; ?>
              <div class="pkg-name"><?= esc($pkg->name) ?></div>
              <div class="pkg-price"><span class="pkg-currency">R$</span><?= number_format($pkg->base_price, 0, ',', '.') ?></div>
              <div class="pkg-photos"><?= (int)$pkg->included_photos ?> fotos tratadas</div>

              <?php if (!empty($pkg->services)): ?>
              <ul class="pkg-services">
                <?php
                  $currentPhase = '';
                  $phaseLabels = \App\Models\ServiceModel::PHASE_LABELS;
                ?>
                <?php foreach ($pkg->services as $svc): ?>
                  <?php if ($svc->phase !== $currentPhase): ?>
                    <?php $currentPhase = $svc->phase; ?>
                    <li class="pkg-phase-label"><?= esc($phaseLabels[$currentPhase] ?? $currentPhase) ?></li>
                  <?php endif; ?>
                  <li>
                    <span class="svc-check">✓</span>
                    <span class="svc-label"><?= esc($svc->name) ?></span>
                    <?php if (!empty($svc->description)): ?>
                      <span class="svc-tooltip"><?= esc($svc->description) ?></span>
                    <?php endif; ?>
                  </li>
                <?php endforeach; ?>
              </ul>
              <?php endif; ?>

              <button class="pkg-btn-buy" onclick="openCheckout(<?= $pkg->id ?>,'<?= esc($pkg->name) ?>',<?= $pkg->base_price ?>,<?= $hero['id'] ?>)">ESCOLHER</button>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <!-- Faixa "Ainda tem dúvidas?" — rede de segurança para quem hesitou -->
    <div style="border-top:1px solid rgba(255,255,255,.06);margin-top:60px;padding-top:48px;text-align:center;">
      <p style="font-family:'EB Garamond',Georgia,serif;font-style:italic;font-size:clamp(1.1rem,2.5vw,1.4rem);color:rgba(255,255,255,.4);margin-bottom:20px;">
        Ainda tem d&uacute;vidas antes de escolher?
      </p>
      <button onclick="openTalk(0,'Geral',<?= $hero['id'] ?>)"
              style="background:transparent;border:1px solid rgba(255,255,255,.15);color:rgba(255,255,255,.45);padding:12px 36px;font-family:'Inter',sans-serif;font-size:.68rem;letter-spacing:.15em;text-transform:uppercase;cursor:pointer;transition:all .25s;"
              onmouseover="this.style.borderColor='rgba(197,160,89,.4)';this.style.color='rgba(197,160,89,.8)'"
              onmouseout="this.style.borderColor='rgba(255,255,255,.15)';this.style.color='rgba(255,255,255,.45)'">
        Conversar antes de comprar
      </button>
    </div>

  </div>
</section>

<!-- ══ MODAL CHECKOUT ════════════════════════════════════════════════ -->
<div class="modal fade" id="checkoutModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" style="max-width:520px;">
    <div class="modal-content" style="background:#0a0a0a;border:1px solid rgba(197,160,89,.25);border-radius:0;">
      <div class="modal-header" style="border-bottom:1px solid rgba(197,160,89,.12);padding:24px 28px 20px;">
        <div>
          <p style="font-family:'Inter',sans-serif;font-size:.65rem;letter-spacing:.2em;text-transform:uppercase;color:rgba(197,160,89,.6);margin:0 0 4px;">PACOTE SELECIONADO</p>
          <h5 id="checkoutPkgName" style="font-family:'EB Garamond',Georgia,serif;font-size:1.6rem;color:#fff;margin:0;font-weight:400;"></h5>
          <p id="checkoutPkgPrice" style="font-family:'Inter',sans-serif;font-size:.9rem;color:#C5A059;margin:4px 0 0;font-weight:500;"></p>
        </div>
        <button type="button" class="btn-close btn-close-white ms-auto" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body" style="padding:28px;">
        <form id="checkoutForm">
          <?= csrf_field() ?>
          <input type="hidden" id="chk_package_id" name="package_id">
          <input type="hidden" id="chk_hero_id" name="hero_id">
          <div class="mb-3">
            <label style="font-family:'Inter',sans-serif;font-size:.65rem;letter-spacing:.18em;text-transform:uppercase;color:rgba(255,255,255,.45);display:block;margin-bottom:8px;">SEU NOME</label>
            <input type="text" name="name" required placeholder="Nome completo" style="width:100%;background:#000;border:1px solid rgba(255,255,255,.12);color:#fff;padding:12px 16px;font-size:.95rem;outline:none;">
          </div>
          <div class="mb-3">
            <label style="font-family:'Inter',sans-serif;font-size:.65rem;letter-spacing:.18em;text-transform:uppercase;color:rgba(255,255,255,.45);display:block;margin-bottom:8px;">E-MAIL</label>
            <input type="email" name="email" required placeholder="seu@email.com" style="width:100%;background:#000;border:1px solid rgba(255,255,255,.12);color:#fff;padding:12px 16px;font-size:.95rem;outline:none;">
          </div>
          <div class="mb-3">
            <label style="font-family:'Inter',sans-serif;font-size:.65rem;letter-spacing:.18em;text-transform:uppercase;color:rgba(255,255,255,.45);display:block;margin-bottom:8px;">WHATSAPP</label>
            <input type="tel" name="phone" placeholder="(00) 00000-0000" style="width:100%;background:#000;border:1px solid rgba(255,255,255,.12);color:#fff;padding:12px 16px;font-size:.95rem;outline:none;">
          </div>

          <!-- Dados para o contrato -->
          <div style="border-top:1px solid rgba(197,160,89,.12);padding-top:16px;margin-bottom:4px;">
            <p style="font-family:'Inter',sans-serif;font-size:.6rem;letter-spacing:.15em;text-transform:uppercase;color:rgba(197,160,89,.5);margin:0 0 12px;">DADOS PARA O CONTRATO</p>
          </div>
          <div style="display:flex;gap:10px;margin-bottom:12px;">
            <div style="flex:1;">
              <label style="font-family:'Inter',sans-serif;font-size:.65rem;letter-spacing:.18em;text-transform:uppercase;color:rgba(255,255,255,.45);display:block;margin-bottom:8px;">CPF</label>
              <input type="text" name="cpf" required placeholder="000.000.000-00" maxlength="14"
                     oninput="let v=this.value.replace(/\D/g,'');if(v.length>3)v=v.slice(0,3)+'.'+v.slice(3);if(v.length>7)v=v.slice(0,7)+'.'+v.slice(7);if(v.length>11)v=v.slice(0,11)+'-'+v.slice(11);this.value=v.slice(0,14);"
                     style="width:100%;background:#000;border:1px solid rgba(255,255,255,.12);color:#fff;padding:12px 16px;font-size:.95rem;outline:none;font-variant-numeric:tabular-nums;">
            </div>
            <div style="flex:1;">
              <label style="font-family:'Inter',sans-serif;font-size:.65rem;letter-spacing:.18em;text-transform:uppercase;color:rgba(255,255,255,.45);display:block;margin-bottom:8px;">RG</label>
              <input type="text" name="rg" placeholder="00.000.000-0" style="width:100%;background:#000;border:1px solid rgba(255,255,255,.12);color:#fff;padding:12px 16px;font-size:.95rem;outline:none;">
            </div>
            <div style="flex:0 0 130px;">
              <label style="font-family:'Inter',sans-serif;font-size:.65rem;letter-spacing:.18em;text-transform:uppercase;color:rgba(255,255,255,.45);display:block;margin-bottom:8px;">ESTADO CIVIL</label>
              <select name="marital_status" style="width:100%;background:#000;border:1px solid rgba(255,255,255,.12);color:rgba(255,255,255,.7);padding:12px 10px;font-size:.85rem;outline:none;appearance:auto;">
                <option value="">—</option>
                <option value="Solteiro(a)">Solteiro(a)</option>
                <option value="Casado(a)">Casado(a)</option>
                <option value="Divorciado(a)">Divorciado(a)</option>
                <option value="Viúvo(a)">Viúvo(a)</option>
                <option value="União Estável">União Estável</option>
              </select>
            </div>
          </div>
          <div style="display:flex;gap:10px;margin-bottom:12px;">
            <div style="flex:0 0 120px;">
              <label style="font-family:'Inter',sans-serif;font-size:.65rem;letter-spacing:.18em;text-transform:uppercase;color:rgba(255,255,255,.45);display:block;margin-bottom:8px;">CEP</label>
              <input type="text" name="zip_code" id="heroZip" placeholder="00000-000" maxlength="9"
                     oninput="let v=this.value.replace(/\D/g,'');if(v.length>5)v=v.slice(0,5)+'-'+v.slice(5);this.value=v.slice(0,9);if(v.replace('-','').length===8)fetchCep(v.replace('-',''),'hero');"
                     style="width:100%;background:#000;border:1px solid rgba(255,255,255,.12);color:#fff;padding:12px 16px;font-size:.95rem;outline:none;font-variant-numeric:tabular-nums;">
            </div>
            <div style="flex:1;">
              <label style="font-family:'Inter',sans-serif;font-size:.65rem;letter-spacing:.18em;text-transform:uppercase;color:rgba(255,255,255,.45);display:block;margin-bottom:8px;">ENDEREÇO</label>
              <input type="text" name="address" id="heroAddr" placeholder="Rua, nº, complemento" style="width:100%;background:#000;border:1px solid rgba(255,255,255,.12);color:#fff;padding:12px 16px;font-size:.95rem;outline:none;">
            </div>
          </div>
          <div style="display:flex;gap:10px;margin-bottom:16px;">
            <div style="flex:1;">
              <label style="font-family:'Inter',sans-serif;font-size:.65rem;letter-spacing:.18em;text-transform:uppercase;color:rgba(255,255,255,.45);display:block;margin-bottom:8px;">BAIRRO</label>
              <input type="text" name="neighborhood" id="heroNeigh" placeholder="Bairro" style="width:100%;background:#000;border:1px solid rgba(255,255,255,.12);color:#fff;padding:12px 16px;font-size:.95rem;outline:none;">
            </div>
            <div style="flex:1;">
              <label style="font-family:'Inter',sans-serif;font-size:.65rem;letter-spacing:.18em;text-transform:uppercase;color:rgba(255,255,255,.45);display:block;margin-bottom:8px;">CIDADE</label>
              <input type="text" name="city" id="heroCity" placeholder="Cidade" style="width:100%;background:#000;border:1px solid rgba(255,255,255,.12);color:#fff;padding:12px 16px;font-size:.95rem;outline:none;">
            </div>
            <div style="flex:0 0 60px;">
              <label style="font-family:'Inter',sans-serif;font-size:.65rem;letter-spacing:.18em;text-transform:uppercase;color:rgba(255,255,255,.45);display:block;margin-bottom:8px;">UF</label>
              <input type="text" name="state" id="heroState" placeholder="SP" maxlength="2" style="width:100%;background:#000;border:1px solid rgba(255,255,255,.12);color:#fff;padding:12px 16px;font-size:.95rem;outline:none;text-transform:uppercase;">
            </div>
          </div>

          <!-- Termos do contrato -->
          <div style="margin-bottom:16px;">
            <label style="display:flex;align-items:flex-start;gap:10px;cursor:pointer;font-family:'Inter',sans-serif;font-size:.78rem;color:rgba(255,255,255,.55);line-height:1.5;">
              <input type="checkbox" name="accept_terms" id="chk_accept_terms" required style="margin-top:3px;accent-color:#C5A059;min-width:16px;">
              Li e aceito os <a href="#" onclick="event.preventDefault();document.getElementById('termsBox').style.display=document.getElementById('termsBox').style.display==='none'?'block':'none'" style="color:#C5A059;text-decoration:underline;">termos do contrato</a> de prestação de serviços fotográficos.
            </label>
            <div id="termsBox" style="display:none;max-height:150px;overflow-y:auto;background:rgba(255,255,255,.03);border:1px solid rgba(255,255,255,.08);padding:14px;margin-top:8px;font-family:'Inter',sans-serif;font-size:.72rem;color:rgba(255,255,255,.4);line-height:1.6;">
              Ao contratar este serviço, você concorda com as condições de prestação de serviços fotográficos, incluindo: prazo de entrega de até 15 dias úteis; direitos autorais das imagens pertencem ao fotógrafo (Lei 9.610/98); licença de uso pessoal e profissional concedida ao contratante; arquivos RAW não fazem parte da entrega; política de cancelamento com reembolso integral se comunicado com mais de 7 dias de antecedência, retenção de 50% se menos de 7 dias; não comparecimento sem aviso de 24h configura no-show sem direito a reembolso; dados tratados conforme LGPD (Lei 13.709/18); foro da Comarca de São Paulo/SP.
            </div>
          </div>
          <div style="margin-bottom:20px;">
            <label style="display:flex;align-items:flex-start;gap:10px;cursor:pointer;font-family:'Inter',sans-serif;font-size:.78rem;color:rgba(255,255,255,.55);line-height:1.5;">
              <input type="checkbox" name="image_usage" id="chk_image_usage" style="margin-top:3px;accent-color:#C5A059;min-width:16px;">
              Autorizo o uso das minhas imagens para portfólio e divulgação do fotógrafo.
            </label>
          </div>

          <button type="submit" id="checkoutSubmitBtn" style="width:100%;background:linear-gradient(135deg,#C5A059,#F5E27A,#C5A059);background-size:200%;color:#000;border:none;padding:16px;font-family:'Inter',sans-serif;font-size:.75rem;font-weight:600;letter-spacing:.2em;text-transform:uppercase;cursor:pointer;transition:background-position .4s;">PAGAR COM PIX OU CARTÃO →</button>
          <p id="checkoutError" style="display:none;color:#ff6b6b;font-size:.8rem;text-align:center;margin-top:12px;"></p>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- ══ MODAL FALAR ANTES ══════════════════════════════════════════════ -->
<div class="modal fade" id="talkModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" style="max-width:480px;">
    <div class="modal-content" style="background:#0a0a0a;border:1px solid rgba(255,255,255,.1);border-radius:0;">
      <div class="modal-header" style="border-bottom:1px solid rgba(255,255,255,.08);padding:24px 28px 20px;">
        <div>
          <p style="font-family:'Inter',sans-serif;font-size:.65rem;letter-spacing:.2em;text-transform:uppercase;color:rgba(255,255,255,.35);margin:0 0 4px;">INTERESSE EM</p>
          <h5 id="talkPkgName" style="font-family:'EB Garamond',Georgia,serif;font-size:1.5rem;color:#fff;margin:0;font-weight:400;"></h5>
        </div>
        <button type="button" class="btn-close btn-close-white ms-auto" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body" style="padding:28px;">
        <p style="font-family:'EB Garamond',Georgia,serif;font-style:italic;color:rgba(255,255,255,.5);font-size:1rem;margin-bottom:24px;">Deixe seu contato e entro em conversa para responder suas dúvidas antes de qualquer compromisso.</p>
        <form id="talkForm">
          <?= csrf_field() ?>
          <input type="hidden" id="talk_package_id" name="package_id">
          <input type="hidden" id="talk_hero_id" name="hero_id">
          <div class="mb-3"><input type="text" name="name" required placeholder="Nome completo" style="width:100%;background:#000;border:1px solid rgba(255,255,255,.12);color:#fff;padding:12px 16px;font-size:.95rem;outline:none;"></div>
          <div class="mb-3"><input type="email" name="email" required placeholder="E-mail" style="width:100%;background:#000;border:1px solid rgba(255,255,255,.12);color:#fff;padding:12px 16px;font-size:.95rem;outline:none;"></div>
          <div class="mb-4"><input type="tel" name="phone" placeholder="WhatsApp / Celular" style="width:100%;background:#000;border:1px solid rgba(255,255,255,.12);color:#fff;padding:12px 16px;font-size:.95rem;outline:none;"></div>
          <button type="submit" style="width:100%;background:transparent;border:1px solid rgba(197,160,89,.5);color:#C5A059;padding:14px;font-family:'Inter',sans-serif;font-size:.72rem;font-weight:500;letter-spacing:.18em;text-transform:uppercase;cursor:pointer;">ENVIAR MEU CONTATO</button>
          <p id="talkSuccess" style="display:none;color:#6bcb77;font-size:.85rem;text-align:center;margin-top:16px;"></p>
        </form>
      </div>
    </div>
  </div>
</div>

</div><!-- /.hero-page-container -->

<!-- ══ LIGHTBOX FULLSCREEN ══ -->
<div class="lightbox-overlay" id="lightbox" onclick="closeLightbox(event)">
    <button class="lightbox-close" onclick="closeLightbox(event)" title="Fechar">&times;</button>
    <button class="lightbox-nav prev" onclick="navLightbox(-1, event)">&lsaquo;</button>
    <img id="lightboxImg" src="" alt="Foto em tela cheia">
    <button class="lightbox-nav next" onclick="navLightbox(1, event)">&rsaquo;</button>
    <div class="lightbox-counter" id="lightboxCounter"></div>
</div>

<?= $this->endSection() ?>


<?= $this->section('scripts') ?>
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script>
    // ── ViaCEP auto-fill ──
    function fetchCep(cep, prefix) {
        fetch('https://viacep.com.br/ws/' + cep + '/json/')
            .then(r => r.json())
            .then(d => {
                if (!d.erro) {
                    const addr = document.getElementById(prefix + 'Addr');
                    const neigh = document.getElementById(prefix + 'Neigh');
                    const city = document.getElementById(prefix + 'City');
                    const state = document.getElementById(prefix + 'State');
                    if (addr && !addr.value) addr.value = d.logradouro || '';
                    if (neigh) neigh.value = d.bairro || '';
                    if (city) city.value = d.localidade || '';
                    if (state) state.value = d.uf || '';
                }
            }).catch(() => {});
    }
</script>
<script>
    var swiper = new Swiper(".mySwiper", {
        loop: true, speed: 700,
        navigation: { nextEl: ".swiper-button-next", prevEl: ".swiper-button-prev" },
        pagination: { el: ".swiper-pagination", clickable: true },
        keyboard: { enabled: true }, a11y: true,
    });

    // ── Lightbox ──
    const lbPhotos = <?= json_encode(array_map(fn($p) => base_url($p['image_path']), $photos)) ?>;
    let lbIndex = 0;

    function openLightbox(src) {
        lbIndex = lbPhotos.indexOf(src);
        if (lbIndex === -1) lbIndex = 0;
        showLightboxPhoto();
        document.getElementById('lightbox').classList.add('active');
        document.body.style.overflow = 'hidden';
    }
    function closeLightbox(e) {
        if (e.target.tagName === 'IMG') return;
        document.getElementById('lightbox').classList.remove('active');
        document.body.style.overflow = '';
    }
    function navLightbox(dir, e) {
        e.stopPropagation();
        lbIndex = (lbIndex + dir + lbPhotos.length) % lbPhotos.length;
        showLightboxPhoto();
    }
    function showLightboxPhoto() {
        document.getElementById('lightboxImg').src = lbPhotos[lbIndex];
        document.getElementById('lightboxCounter').textContent = (lbIndex + 1) + ' / ' + lbPhotos.length;
    }
    document.addEventListener('keydown', function(e) {
        const lb = document.getElementById('lightbox');
        if (!lb.classList.contains('active')) return;
        if (e.key === 'Escape') { lb.classList.remove('active'); document.body.style.overflow = ''; }
        if (e.key === 'ArrowRight') navLightbox(1, e);
        if (e.key === 'ArrowLeft') navLightbox(-1, e);
    });

    function toggleOtherPkgs(btn) {
        const el = document.getElementById('otherPackages');
        const icon = btn.querySelector('.pkg-expand-icon');
        const visible = el.style.display !== 'none';
        el.style.display = visible ? 'none' : 'block';
        icon.classList.toggle('open', !visible);
        if (!visible) el.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }

    function openCheckout(pkgId, pkgName, price, heroId) {
        document.getElementById('checkoutPkgName').textContent  = pkgName;
        document.getElementById('checkoutPkgPrice').textContent = 'R\u00a0' + Number(price).toLocaleString('pt-BR', {minimumFractionDigits:0});
        document.getElementById('chk_package_id').value = pkgId;
        document.getElementById('chk_hero_id').value    = heroId;
        document.getElementById('checkoutError').style.display = 'none';
        new bootstrap.Modal(document.getElementById('checkoutModal')).show();
    }

    function openTalk(pkgId, pkgName, heroId) {
        document.getElementById('talkPkgName').textContent = pkgName;
        document.getElementById('talk_package_id').value   = pkgId;
        document.getElementById('talk_hero_id').value      = heroId;
        document.getElementById('talkSuccess').style.display = 'none';
        new bootstrap.Modal(document.getElementById('talkModal')).show();
    }

    document.getElementById('checkoutForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const btn = document.getElementById('checkoutSubmitBtn');
        const errEl = document.getElementById('checkoutError');
        btn.textContent = 'PROCESSANDO...';
        btn.disabled = true;
        fetch('<?= base_url('comprar-ensaio') ?>', {
            method: 'POST', body: new FormData(this), headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(r => r.json())
        .then(data => {
            if (data.success && data.checkout_url) {
                window.location.href = data.checkout_url;
            } else {
                errEl.textContent = data.message || 'Erro ao processar. Tente novamente.';
                errEl.style.display = 'block';
                btn.textContent = 'PAGAR COM PIX OU CARTÃO →';
                btn.disabled = false;
            }
        })
        .catch(() => {
            errEl.textContent = 'Erro de conexão. Tente novamente.';
            errEl.style.display = 'block';
            btn.textContent = 'PAGAR COM PIX OU CARTÃO →';
            btn.disabled = false;
        });
    });

    document.getElementById('talkForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const btn = this.querySelector('button[type="submit"]');
        btn.textContent = 'ENVIANDO...';
        btn.disabled = true;
        fetch('<?= base_url('quero-falar') ?>', {
            method: 'POST', body: new FormData(this), headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(r => r.json())
        .then(data => {
            const msg = document.getElementById('talkSuccess');
            msg.textContent = data.message || 'Recebemos seu contato! Em breve entraremos em contato.';
            msg.style.display = 'block';
            this.querySelectorAll('input[type="text"],input[type="email"],input[type="tel"]').forEach(i => i.value = '');
            btn.textContent = 'ENVIAR MEU CONTATO';
            btn.disabled = false;
        })
        .catch(() => { btn.textContent = 'ENVIAR MEU CONTATO'; btn.disabled = false; });
    });
</script>
<?= $this->endSection() ?>
