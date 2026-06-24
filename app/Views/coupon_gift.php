<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Um presente especial para você — James Webb Studio</title>
  <meta name="description" content="Você recebeu um ensaio fotográfico exclusivo como presente do James Webb Studio.">
  <meta name="robots" content="noindex">

  <meta property="og:title"       content="🎁 Um presente especial para você">
  <meta property="og:description" content="Ensaio fotográfico exclusivo — cortesia do James Webb Studio.">
  <meta property="og:image"       content="<?= base_url('assets/img/og-gift.png') ?>">
  <meta property="og:image:width"  content="1200">
  <meta property="og:image:height" content="630">
  <meta property="og:url"         content="<?= current_url() ?>">
  <meta property="og:type"        content="website">

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=EB+Garamond:ital,wght@0,400;0,500;1,400&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    :root {
      --gold: #C5A059; --gold-light: #F5E27A;
      --green: #6bcb77; --green-dark: #2e7d32;
      --bg: #000; --surface: #0a0a0a;
    }
    body { background: var(--bg); color: #fff; font-family: 'Inter', sans-serif; min-height: 100vh; overflow-x: hidden; }

    /* ── Partículas ── */
    .gift-bg-particles { position: fixed; inset: 0; z-index: 0; pointer-events: none; overflow: hidden; }
    .particle { position: absolute; width: 4px; height: 4px; border-radius: 50%; opacity: 0; animation: particleFall linear infinite; }
    @keyframes particleFall {
      0%   { opacity: 0; transform: translateY(-20px) rotate(0deg); }
      10%  { opacity: .6; }
      90%  { opacity: .2; }
      100% { opacity: 0; transform: translateY(110vh) rotate(720deg); }
    }

    /* ══════════════════════════════════
       HERO — foto full-width
    ══════════════════════════════════ */
    .hero {
      position: relative; width: 100%;
      min-height: 92vh; display: flex;
      flex-direction: column;
      align-items: center; justify-content: flex-end;
      padding-bottom: 60px;
      overflow: hidden;
    }
    .hero-img {
      position: absolute; inset: 0;
      background: url('<?= base_url('assets/img/ensaio.jpg') ?>') center center / cover no-repeat;
      transform: scale(1.04);
      animation: heroZoom 14s ease-in-out infinite alternate;
    }
    @keyframes heroZoom {
      from { transform: scale(1.04); }
      to   { transform: scale(1.00); }
    }
    /* Gradiente escuro na parte inferior para legibilidade */
    .hero-overlay {
      position: absolute; inset: 0;
      background: linear-gradient(
        to bottom,
        rgba(0,0,0,.25) 0%,
        rgba(0,0,0,.10) 30%,
        rgba(0,0,0,.55) 65%,
        rgba(0,0,0,.92) 100%
      );
    }
    .hero-content {
      position: relative; z-index: 2;
      display: flex; flex-direction: column;
      align-items: center; text-align: center;
      padding: 0 20px;
      animation: fadeUp .9s ease .3s both;
    }
    .hero-logo {
      position: absolute; top: 32px; left: 50%; transform: translateX(-50%);
      z-index: 3;
      animation: fadeDown .8s ease .1s both;
    }
    .hero-logo img { height: 34px; filter: brightness(0) invert(1); opacity: .75; }
    @keyframes fadeDown {
      from { opacity: 0; transform: translateX(-50%) translateY(-12px); }
      to   { opacity: 1; transform: translateX(-50%) translateY(0); }
    }

    .hero-eyebrow {
      font-size: .58rem; letter-spacing: .35em; text-transform: uppercase;
      color: rgba(107,203,119,.85); margin-bottom: 14px;
    }
    .hero-headline {
      font-family: 'EB Garamond', Georgia, serif;
      font-size: clamp(2.4rem, 8vw, 4.2rem); font-weight: 400;
      line-height: 1.15; margin-bottom: 10px;
    }
    .hero-headline em { font-style: italic; color: var(--gold); }
    .hero-sub {
      font-family: 'EB Garamond', Georgia, serif;
      font-size: clamp(.95rem, 2.5vw, 1.2rem); font-style: italic;
      color: rgba(255,255,255,.55); max-width: 460px; line-height: 1.65;
      margin-bottom: 36px;
    }
    .hero-scroll-hint {
      font-size: .62rem; letter-spacing: .18em; text-transform: uppercase;
      color: rgba(255,255,255,.3);
      display: flex; flex-direction: column; align-items: center; gap: 6px;
    }
    .hero-scroll-hint span { animation: bounce 1.8s ease-in-out infinite; display: block; font-size: 1rem; }
    @keyframes bounce {
      0%,100% { transform: translateY(0); }
      50%      { transform: translateY(7px); }
    }

    /* ══════════════════════════════════
       SEÇÃO DO CUPOM
    ══════════════════════════════════ */
    .gift-wrap {
      position: relative; z-index: 1;
      display: flex; flex-direction: column;
      align-items: center; padding: 64px 20px 80px;
      background: linear-gradient(to bottom, rgba(0,0,0,.0), #000 80px);
    }

    .coupon-banner {
      max-width: 560px; width: 100%;
      background: linear-gradient(135deg, rgba(107,203,119,.07), rgba(107,203,119,.02));
      border: 1px solid rgba(107,203,119,.3);
      border-radius: 4px; padding: 32px 36px; text-align: center;
      margin-bottom: 64px; position: relative;
      animation: fadeUp .8s ease .1s both;
    }
    .coupon-banner::before {
      content: ''; position: absolute; top: 0; left: 50%; transform: translateX(-50%);
      width: 50%; height: 1px;
      background: linear-gradient(90deg, transparent, rgba(107,203,119,.6), transparent);
    }
    .coupon-banner-title {
      font-size: .62rem; letter-spacing: .25em; text-transform: uppercase;
      color: var(--green); margin-bottom: 10px;
    }
    .coupon-banner-headline {
      font-family: 'EB Garamond', Georgia, serif;
      font-size: clamp(1.2rem, 4vw, 1.55rem); margin-bottom: 6px;
    }
    .coupon-banner-sub {
      font-size: .78rem; color: rgba(255,255,255,.38); margin-bottom: 22px;
    }
    .coupon-code-box { display: flex; align-items: center; justify-content: center; gap: 12px; flex-wrap: wrap; }
    .coupon-code {
      font-family: 'Inter', monospace; font-size: clamp(1.5rem, 6vw, 2.2rem);
      font-weight: 700; letter-spacing: .15em; color: var(--green);
      background: rgba(107,203,119,.08); border: 1px dashed rgba(107,203,119,.4);
      padding: 10px 22px; border-radius: 6px;
      text-shadow: 0 0 28px rgba(107,203,119,.3);
      user-select: all; cursor: copy;
    }
    .btn-copy {
      background: none; border: 1px solid rgba(107,203,119,.35);
      color: rgba(107,203,119,.7); font-family: 'Inter', sans-serif;
      font-size: .62rem; letter-spacing: .15em; text-transform: uppercase;
      padding: 8px 14px; border-radius: 4px; cursor: pointer;
      transition: all .2s; white-space: nowrap;
    }
    .btn-copy:hover, .btn-copy.copied { background: rgba(107,203,119,.12); color: var(--green); }
    .coupon-banner-hint {
      margin-top: 20px; font-size: .73rem;
      color: rgba(255,255,255,.28); line-height: 1.75;
    }
    .coupon-banner-hint strong { color: rgba(255,255,255,.5); }

    /* ══════════════════════════════════
       GRADE DE PACOTES
    ══════════════════════════════════ */
    .packages-section {
      max-width: 900px; width: 100%;
      animation: fadeUp .8s ease .2s both;
    }
    .packages-label {
      font-size: .58rem; letter-spacing: .3em; text-transform: uppercase;
      color: rgba(255,255,255,.2); text-align: center; margin-bottom: 28px;
    }
    .packages-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
      gap: 1.25rem;
    }

    /* Card */
    .pkg-card {
      background: rgba(255,255,255,.025);
      border: 1px solid rgba(255,255,255,.07);
      border-radius: 6px; overflow: hidden;
      display: flex; flex-direction: column;
      transition: border-color .3s, transform .3s, box-shadow .3s;
    }
    .pkg-card:hover {
      border-color: rgba(197,160,89,.4);
      transform: translateY(-5px);
      box-shadow: 0 16px 48px rgba(0,0,0,.6);
    }
    .pkg-featured-badge {
      background: linear-gradient(90deg, #C5A059, #F5E27A);
      color: #000; font-family: 'Inter', sans-serif;
      font-size: .55rem; font-weight: 700;
      letter-spacing: .2em; text-transform: uppercase;
      padding: 5px 0; text-align: center;
    }
    .pkg-card.featured { border-color: rgba(197,160,89,.25); }

    .pkg-card-top { padding: 28px 26px 0; flex: 1; }

    /* Nome */
    .pkg-name {
      font-family: 'EB Garamond', Georgia, serif;
      font-size: 1.45rem; font-weight: 400;
      color: #fff; margin-bottom: 12px;
    }

    /* Descrição */
    .pkg-description {
      font-family: 'Inter', sans-serif; font-size: .75rem;
      color: rgba(255,255,255,.42); line-height: 1.75;
      margin-bottom: 20px;
      border-left: 2px solid rgba(197,160,89,.2);
      padding-left: 12px;
    }

    /* Preço original — destaque dourado */
    .pkg-value-label {
      font-size: .55rem; letter-spacing: .2em; text-transform: uppercase;
      color: rgba(197,160,89,.45); margin-bottom: 4px;
    }
    .pkg-original-price {
      font-family: 'EB Garamond', Georgia, serif;
      font-size: clamp(2rem, 6vw, 2.8rem); font-weight: 400;
      color: var(--gold); line-height: 1;
      text-shadow: 0 0 24px rgba(197,160,89,.2);
      margin-bottom: 4px;
    }
    .pkg-free-label {
      display: inline-block;
      background: rgba(107,203,119,.1);
      border: 1px solid rgba(107,203,119,.25);
      color: var(--green); font-family: 'Inter', sans-serif;
      font-size: .6rem; font-weight: 600;
      letter-spacing: .18em; text-transform: uppercase;
      padding: 3px 10px; border-radius: 20px;
      margin-bottom: 20px;
    }

    /* Features */
    .pkg-divider { width: 32px; height: 1px; background: rgba(255,255,255,.07); margin: 0 0 14px; }
    .pkg-features {
      font-size: .72rem; color: rgba(255,255,255,.35);
      line-height: 2; list-style: none; margin-bottom: 24px;
    }
    .pkg-features li::before { content: '✓  '; color: rgba(197,160,89,.5); }

    /* Botão */
    .pkg-card-footer { padding: 0 26px 26px; }
    .btn-escolher {
      display: block; width: 100%; text-align: center;
      background: linear-gradient(135deg, var(--green-dark), var(--green));
      color: #fff; text-decoration: none;
      padding: 14px 20px; border-radius: 4px;
      font-family: 'Inter', sans-serif; font-size: .68rem;
      font-weight: 700; letter-spacing: .18em; text-transform: uppercase;
      transition: transform .2s, box-shadow .3s;
    }
    .btn-escolher:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 24px rgba(107,203,119,.25);
    }

    /* ── Rodapé ── */
    .gift-footer {
      margin-top: 56px; text-align: center;
      animation: fadeUp .8s ease .3s both;
    }
    .gift-footer p {
      font-size: .6rem; letter-spacing: .12em;
      text-transform: uppercase; color: rgba(255,255,255,.12);
    }
    .gift-footer-wa {
      display: inline-flex; align-items: center; gap: 6px;
      color: rgba(255,255,255,.22); font-size: .68rem;
      cursor: pointer; background: none; border: none;
      font-family: 'Inter', sans-serif; margin-top: 14px;
      transition: color .2s;
    }
    .gift-footer-wa:hover { color: rgba(255,255,255,.55); }

    /* ── Animações ── */
    @keyframes fadeUp {
      from { opacity: 0; transform: translateY(20px); }
      to   { opacity: 1; transform: translateY(0); }
    }

    /* ── Responsivo ── */
    @media (max-width: 520px) {
      .hero { min-height: 85vh; }
      .coupon-banner { padding: 24px 18px; }
      .packages-grid { grid-template-columns: 1fr; }
      .pkg-original-price { font-size: 2rem; }
    }
  </style>
</head>
<body>

<div class="gift-bg-particles" id="particles"></div>

<!-- ══ HERO COM FOTO ══ -->
<section class="hero">
  <div class="hero-img"></div>
  <div class="hero-overlay"></div>

  <!-- Logo no topo -->
  <div class="hero-logo">
    <img src="<?= base_url('assets/img/jws-logo-horizontal.png') ?>" alt="James Webb Studio">
  </div>

  <!-- Texto sobre a foto -->
  <div class="hero-content">
    <p class="hero-eyebrow">Presente Exclusivo</p>
    <h1 class="hero-headline">
      Um ensaio fotográfico<br>
      <em>inteiramente para você.</em>
    </h1>
    <p class="hero-sub">
      Você ganhou uma sessão completa — escolha o estilo que combina com você.
    </p>
    <div class="hero-scroll-hint">
      Escolha seu ensaio
      <span>↓</span>
    </div>
  </div>
</section>

<!-- ══ CUPOM + PACOTES ══ -->
<div class="gift-wrap">

  <!-- Banner do cupom -->
  <div class="coupon-banner">
    <p class="coupon-banner-title">✨ Sua cortesia está pronta</p>
    <p class="coupon-banner-headline">Você ganhou um ensaio fotográfico gratuito</p>
    <p class="coupon-banner-sub">Use este código na hora de finalizar seu pedido</p>

    <div class="coupon-code-box">
      <span class="coupon-code" id="couponCode" title="Clique para copiar"><?= esc($code) ?></span>
      <button class="btn-copy" id="btnCopy" onclick="copiarCodigo()">Copiar</button>
    </div>

    <p class="coupon-banner-hint">
      <strong>Como resgatar:</strong> escolha qualquer ensaio abaixo →<br>
      no checkout, cole o código acima e o valor zera automaticamente.
    </p>
  </div>

  <!-- Pacotes -->
  <div class="packages-section">
    <p class="packages-label">Escolha o ensaio que é a sua cara</p>

    <div class="packages-grid">
      <?php foreach ($packages as $pkg): ?>
        <?php $isFeatured = ($maxPackage && $pkg->id === $maxPackage->id); ?>
        <div class="pkg-card <?= $isFeatured ? 'featured' : '' ?>">

          <?php if ($isFeatured): ?>
            <div class="pkg-featured-badge">⭐ Mais completo</div>
          <?php endif ?>

          <div class="pkg-card-top">

            <!-- Nome -->
            <div class="pkg-name"><?= esc($pkg->name) ?></div>

            <!-- Descrição do ensaio -->
            <?php if (!empty($pkg->description)): ?>
              <div class="pkg-description"><?= esc(strip_tags($pkg->description)) ?></div>
            <?php endif ?>

            <!-- Valor original em dourado grande -->
            <?php if ($pkg->base_price > 0): ?>
              <div class="pkg-value-label">Valor do presente</div>
              <div class="pkg-original-price">R$&nbsp;<?= number_format($pkg->base_price, 0, ',', '.') ?></div>
            <?php endif ?>

            <!-- Tag gratuito -->
            <div class="pkg-free-label">100% Gratuito</div>

            <!-- Detalhes -->
            <?php
              $features = [];
              if (!empty($pkg->included_photos) && $pkg->included_photos > 0)
                  $features[] = (int)$pkg->included_photos . ' fotos tratadas incluídas';
              $features[] = 'Sessão completa de ensaio';
              $features[] = 'Experiência premium';
            ?>
            <div class="pkg-divider"></div>
            <ul class="pkg-features">
              <?php foreach ($features as $f): ?>
                <li><?= esc($f) ?></li>
              <?php endforeach ?>
            </ul>

          </div>

          <div class="pkg-card-footer">
            <a href="<?= site_url('investimento?cupom=' . esc($code)) ?>"
               class="btn-escolher"
               onclick="this.textContent='CARREGANDO...'; this.style.opacity='.7';">
              Escolher este ensaio →
            </a>
          </div>

        </div>
      <?php endforeach ?>
    </div>
  </div>

  <!-- Rodapé -->
  <div class="gift-footer">
    <p>James Webb Studio · Fotografia de Alta Qualidade</p>
    <p style="margin-top:6px;">Cupom de uso único e intransferível</p>
    <button class="gift-footer-wa" onclick="compartilharWhats()">
      <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/><path d="M12 0C5.373 0 0 5.373 0 12c0 2.136.562 4.14 1.537 5.876L.057 23.886l6.19-1.623A11.94 11.94 0 0012 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 21.818a9.8 9.8 0 01-5.032-1.387l-.36-.214-3.735.979 1-3.644-.234-.373A9.804 9.804 0 012.182 12C2.182 6.57 6.57 2.182 12 2.182c5.43 0 9.818 4.388 9.818 9.818 0 5.43-4.388 9.818-9.818 9.818z"/></svg>
      Compartilhar este presente via WhatsApp
    </button>
  </div>

</div><!-- /gift-wrap -->

<script>
  // Partículas
  const colors = ['#C5A059','#F5E27A','#6bcb77'];
  const container = document.getElementById('particles');
  for (let i = 0; i < 20; i++) {
    const p = document.createElement('div');
    p.className = 'particle';
    const sz = Math.random() * 3 + 2;
    p.style.cssText = `left:${Math.random()*100}%;width:${sz}px;height:${sz}px;background:${colors[Math.floor(Math.random()*colors.length)]};animation-duration:${7+Math.random()*10}s;animation-delay:${Math.random()*9}s;`;
    container.appendChild(p);
  }

  // Copiar código
  function copiarCodigo() {
    const code = document.getElementById('couponCode').textContent.trim();
    navigator.clipboard.writeText(code).then(() => {
      const btn = document.getElementById('btnCopy');
      btn.textContent = '✓ Copiado!';
      btn.classList.add('copied');
      setTimeout(() => { btn.textContent = 'Copiar'; btn.classList.remove('copied'); }, 2500);
    });
  }
  document.getElementById('couponCode').addEventListener('click', copiarCodigo);

  // WhatsApp
  function compartilharWhats() {
    const msg = `🎁 *Você tem um presente especial!*\n\nRecebeu um ensaio fotográfico exclusivo do James Webb Studio — completamente gratuito.\n\nClique para resgatar:\n<?= esc(current_url()) ?>`;
    window.open('https://wa.me/?text=' + encodeURIComponent(msg), '_blank');
  }
</script>
</body>
</html>
