<?= $this->extend('layout/main') ?>

<?= $this->section('styles') ?>
<style>
/* ============================================================
   LANDING PAGE DE COPY — /{slug}/agendar
   ============================================================ */

/* --- Blocos genéricos --- */
.lp-section { padding: 72px 0; }
.lp-section + .lp-section { padding-top: 0; }

/* --- Headline block --- */
.lp-headline {
    position: relative;
    min-height: 80vh;
    display: flex;
    align-items: center;
    justify-content: center;
    text-align: center;
    padding: 100px 24px 80px;
    background-color: #000;
    overflow: hidden;
}
.lp-headline-bg {
    position: absolute;
    inset: 0;
    object-fit: cover;
    width: 100%;
    height: 100%;
    opacity: 0.25;
    filter: grayscale(30%);
}
.lp-headline-overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(to bottom, rgba(0,0,0,0.3) 0%, rgba(0,0,0,0.75) 100%);
}
.lp-headline-content { position: relative; z-index: 2; max-width: 820px; }
.lp-headline-content h1 {
    font-family: 'EB Garamond', Georgia, serif;
    font-size: clamp(2.4rem, 7vw, 5rem);
    font-weight: 500;
    color: #C5A059;
    line-height: 1.2;
    margin-bottom: 24px;
}
.lp-headline-content p {
    font-size: clamp(1rem, 2.5vw, 1.4rem);
    color: rgba(255,255,255,0.75);
    font-family: 'EB Garamond', Georgia, serif;
    font-style: italic;
    max-width: 640px;
    margin: 0 auto;
}

/* --- Text block --- */
.lp-text-block { background: #000; padding: 60px 0; }
.lp-text-block .lp-body {
    font-family: 'EB Garamond', Georgia, serif;
    font-size: clamp(1.05rem, 2vw, 1.3rem);
    color: rgba(255,255,255,0.82);
    line-height: 1.85;
    max-width: 720px;
}
.lp-text-block .lp-body.text-center { margin: 0 auto; }

/* --- Image block --- */
.lp-image-block { background: #000; padding: 40px 0; }
.lp-image-contained img { max-width: 900px; width: 100%; height: auto; display: block; margin: 0 auto; }
.lp-image-full img { width: 100%; height: auto; display: block; }
.lp-image-caption {
    text-align: center;
    font-family: 'EB Garamond', Georgia, serif;
    font-style: italic;
    font-size: 0.95rem;
    color: rgba(255,255,255,0.4);
    margin-top: 12px;
}

/* --- Video block --- */
.lp-video-block { background: #000; padding: 60px 0; }
.lp-video-wrap {
    position: relative;
    padding-bottom: 56.25%;
    height: 0;
    max-width: 900px;
    margin: 0 auto;
}
.lp-video-wrap iframe {
    position: absolute;
    inset: 0;
    width: 100%;
    height: 100%;
    border: none;
}
.lp-video-title {
    text-align: center;
    font-family: 'EB Garamond', Georgia, serif;
    font-style: italic;
    color: rgba(255,255,255,0.4);
    margin-top: 16px;
    font-size: 0.95rem;
}

/* --- Testimony block --- */
.lp-testimony { background: #070707; padding: 72px 0; border-top: 1px solid rgba(197,160,89,0.1); border-bottom: 1px solid rgba(197,160,89,0.1); }
.lp-testimony-inner { max-width: 760px; margin: 0 auto; text-align: center; }
.lp-testimony-quote {
    font-family: 'EB Garamond', Georgia, serif;
    font-size: clamp(1.3rem, 3vw, 2rem);
    font-style: italic;
    color: rgba(255,255,255,0.85);
    line-height: 1.6;
    margin-bottom: 28px;
}
.lp-testimony-quote::before { content: '\201C'; color: #C5A059; }
.lp-testimony-quote::after  { content: '\201D'; color: #C5A059; }
.lp-testimony-author {
    font-family: 'Inter', sans-serif;
    font-size: 0.75rem;
    letter-spacing: 0.2em;
    text-transform: uppercase;
    color: rgba(255,255,255,0.4);
}
.lp-testimony-photo {
    width: 72px; height: 72px;
    border-radius: 50%;
    object-fit: cover;
    margin: 0 auto 20px;
    border: 1px solid rgba(197,160,89,0.3);
    display: block;
}

/* --- Process block --- */
.lp-process { background: #000; padding: 72px 0; }
.lp-process-step { text-align: center; padding: 0 24px; }
.lp-process-number {
    font-family: 'EB Garamond', Georgia, serif;
    font-size: 3rem;
    color: #C5A059;
    opacity: 0.5;
    line-height: 1;
    margin-bottom: 12px;
}
.lp-process-title {
    font-family: 'EB Garamond', Georgia, serif;
    font-size: 1.3rem;
    color: #fff;
    margin-bottom: 8px;
}
.lp-process-desc {
    font-size: 0.9rem;
    color: rgba(255,255,255,0.45);
    line-height: 1.6;
}

/* --- CTA Button block --- */
.lp-cta-btn-block { background: #000; padding: 72px 24px; text-align: center; }

/* --- Divisor dourado --- */
.lp-divider {
    width: 48px;
    height: 1px;
    background: rgba(197,160,89,0.4);
    margin: 0 auto;
}

/* --- Breadcrumb de volta --- */
.lp-back {
    padding: 20px 24px;
    background: #000;
}
.lp-back a {
    font-family: 'Inter', sans-serif;
    font-size: 0.72rem;
    letter-spacing: 0.18em;
    text-transform: uppercase;
    color: rgba(255,255,255,0.4);
    text-decoration: none;
    transition: color 0.2s;
}
.lp-back a:hover { color: #C5A059; }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<!-- Breadcrumb -->
<div class="lp-back">
    <a href="<?= site_url($hero['slug']) ?>">&larr; Ver portfólio de <?= esc($hero['name']) ?></a>
</div>

<?php if (empty($blocks)): ?>
<!-- Estado vazio -->
<div class="vh-100 d-flex align-items-center justify-content-center text-center px-4">
    <div>
        <h1 class="text-gold" style="font-family:'EB Garamond',serif;"><?= esc($hero['name']) ?></h1>
        <p class="text-muted mt-3">A página de agendamento ainda não foi configurada.</p>
    </div>
</div>

<?php else: ?>

<?php foreach ($blocks as $block):
    $c = $block['content'];
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
                <h1><?= esc($c['title']) ?></h1>
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
                <?= $c['content'] /* HTML confiável inserido pelo admin */ ?>
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
                    // Converte URL normal para embed
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
        <?php $scrollTo = !empty($c['scroll_to_agenda']); ?>
        <a href="<?= $scrollTo ? '#agenda-widget' : '#agenda-widget' ?>"
           class="btn btn-terroso btn-lg px-5 py-3"
           <?= $scrollTo ? 'id="lp-cta-btn"' : '' ?>>
            <?= esc($c['text'] ?? 'Quero meu ensaio') ?>
        </a>
        <div class="lp-divider mt-5"></div>
    </div>

<?php elseif ($type === 'spacer'): ?>
    <!-- ── SPACER ── -->
    <?php $h = ['sm'=>'40px','md'=>'80px','lg'=>'160px'][$c['height'] ?? 'md'] ?? '80px'; ?>
    <div style="height:<?= $h ?>; background:#000;"></div>

<?php endif; ?>
<?php endforeach; ?>
<?php endif; ?>

<!-- Widget de Agendamento sempre ao final -->
<?= $this->include('partials/agenda_widget') ?>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
// Smooth scroll para botões CTA
document.querySelectorAll('a[href="#agenda-widget"]').forEach(a => {
    a.addEventListener('click', e => {
        e.preventDefault();
        document.getElementById('agenda-widget')?.scrollIntoView({ behavior: 'smooth' });
    });
});
</script>
<?= $this->endSection() ?>
