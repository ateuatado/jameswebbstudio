<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Um presente especial para você — James Webb Studio</title>
  <meta name="description" content="Você recebeu um ensaio fotográfico exclusivo como presente do James Webb Studio.">
  <meta name="robots" content="noindex"> <!-- página privada, não indexar -->

  <!-- Open Graph para preview no WhatsApp -->
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
      --gold: #C5A059;
      --gold-light: #F5E27A;
      --green: #6bcb77;
      --green-dark: #2e7d32;
      --bg: #000;
      --surface: #0a0a0a;
    }

    body {
      background: var(--bg);
      color: #fff;
      font-family: 'Inter', sans-serif;
      min-height: 100vh;
      overflow-x: hidden;
    }

    /* ── Fundo animado ── */
    .gift-bg {
      position: fixed;
      inset: 0;
      z-index: 0;
      background:
        radial-gradient(ellipse 80% 60% at 50% 0%, rgba(107,203,119,.08) 0%, transparent 70%),
        radial-gradient(ellipse 60% 40% at 20% 80%, rgba(197,160,89,.06) 0%, transparent 60%),
        #000;
    }
    .gift-bg-particles {
      position: fixed;
      inset: 0;
      z-index: 0;
      pointer-events: none;
      overflow: hidden;
    }
    .particle {
      position: absolute;
      width: 4px; height: 4px;
      border-radius: 50%;
      opacity: 0;
      animation: particleFall linear infinite;
    }
    @keyframes particleFall {
      0%   { opacity: 0; transform: translateY(-20px) rotate(0deg); }
      10%  { opacity: .7; }
      90%  { opacity: .3; }
      100% { opacity: 0; transform: translateY(110vh) rotate(720deg); }
    }

    /* ── Layout ── */
    .gift-wrap {
      position: relative;
      z-index: 1;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      padding: 40px 20px 60px;
    }

    /* ── Logo ── */
    .gift-logo {
      margin-bottom: 48px;
      opacity: 0;
      animation: fadeUp .8s ease .2s forwards;
    }
    .gift-logo img {
      height: 36px;
      filter: brightness(0) invert(1);
      opacity: .6;
    }

    /* ── Selo de presente ── */
    .gift-seal {
      width: 96px; height: 96px;
      border-radius: 50%;
      background: linear-gradient(135deg, rgba(107,203,119,.15), rgba(107,203,119,.05));
      border: 1px solid rgba(107,203,119,.3);
      display: flex; align-items: center; justify-content: center;
      font-size: 2.8rem;
      margin-bottom: 28px;
      opacity: 0;
      animation: fadeUp .8s ease .4s forwards, sealPulse 3s ease-in-out 1.5s infinite;
    }
    @keyframes sealPulse {
      0%,100% { box-shadow: 0 0 0 0 rgba(107,203,119,0); }
      50%      { box-shadow: 0 0 0 16px rgba(107,203,119,.08); }
    }

    /* ── Texto principal ── */
    .gift-eyebrow {
      font-size: .6rem;
      letter-spacing: .3em;
      text-transform: uppercase;
      color: rgba(107,203,119,.7);
      margin-bottom: 16px;
      opacity: 0;
      animation: fadeUp .8s ease .55s forwards;
    }
    .gift-headline {
      font-family: 'EB Garamond', Georgia, serif;
      font-size: clamp(2rem, 7vw, 3.6rem);
      font-weight: 400;
      line-height: 1.2;
      text-align: center;
      max-width: 640px;
      margin-bottom: 12px;
      opacity: 0;
      animation: fadeUp .8s ease .7s forwards;
    }
    .gift-headline em { font-style: italic; color: var(--gold); }
    .gift-sub {
      font-family: 'EB Garamond', Georgia, serif;
      font-size: clamp(1rem, 2.5vw, 1.25rem);
      font-style: italic;
      color: rgba(255,255,255,.45);
      text-align: center;
      max-width: 480px;
      line-height: 1.65;
      margin-bottom: 48px;
      opacity: 0;
      animation: fadeUp .8s ease .85s forwards;
    }

    /* ── Card do presente ── */
    .gift-card {
      background: var(--surface);
      border: 1px solid rgba(107,203,119,.3);
      padding: 40px 44px;
      max-width: 420px;
      width: 100%;
      text-align: center;
      position: relative;
      margin-bottom: 40px;
      opacity: 0;
      animation: fadeUp .8s ease 1s forwards;
    }
    .gift-card::before {
      content: '';
      position: absolute;
      top: 0; left: 50%; transform: translateX(-50%);
      width: 60%; height: 1px;
      background: linear-gradient(90deg, transparent, rgba(107,203,119,.5), transparent);
    }
    .gift-card-label {
      font-size: .6rem;
      letter-spacing: .25em;
      text-transform: uppercase;
      color: rgba(255,255,255,.3);
      margin-bottom: 20px;
    }
    .gift-pkg-name {
      font-family: 'EB Garamond', Georgia, serif;
      font-size: 1.6rem;
      font-weight: 400;
      color: #fff;
      margin-bottom: 16px;
    }
    .gift-value-original {
      font-family: 'Inter', sans-serif;
      font-size: 1.1rem;
      color: rgba(255,255,255,.3);
      text-decoration: line-through;
      margin-bottom: 4px;
    }
    .gift-value-free {
      font-family: 'EB Garamond', Georgia, serif;
      font-size: clamp(2.8rem, 8vw, 4rem);
      font-weight: 400;
      color: var(--green);
      line-height: 1;
      margin-bottom: 4px;
      text-shadow: 0 0 40px rgba(107,203,119,.3);
    }
    .gift-value-label {
      font-size: .65rem;
      letter-spacing: .2em;
      text-transform: uppercase;
      color: rgba(107,203,119,.6);
      margin-bottom: 24px;
    }
    .gift-divider {
      width: 40px; height: 1px;
      background: rgba(197,160,89,.25);
      margin: 0 auto 20px;
    }
    .gift-includes {
      font-family: 'Inter', sans-serif;
      font-size: .75rem;
      color: rgba(255,255,255,.4);
      line-height: 1.8;
    }
    .gift-includes span { color: rgba(197,160,89,.7); margin-right: 6px; }

    /* ── Botão CTA ── */
    .gift-cta {
      max-width: 420px;
      width: 100%;
      opacity: 0;
      animation: fadeUp .8s ease 1.2s forwards;
    }
    .gift-cta a {
      display: block;
      width: 100%;
      background: linear-gradient(135deg, var(--green-dark), var(--green));
      background-size: 200%;
      color: #fff;
      text-decoration: none;
      padding: 18px 32px;
      font-family: 'Inter', sans-serif;
      font-size: .75rem;
      font-weight: 700;
      letter-spacing: .2em;
      text-transform: uppercase;
      text-align: center;
      transition: background-position .4s, transform .2s, box-shadow .3s;
      margin-bottom: 16px;
    }
    .gift-cta a:hover {
      background-position: 100%;
      transform: translateY(-2px);
      box-shadow: 0 12px 32px rgba(107,203,119,.25);
    }

    /* Botão WhatsApp */
    .gift-cta-wa {
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
      color: rgba(255,255,255,.35);
      font-size: .7rem;
      letter-spacing: .08em;
      text-align: center;
      cursor: pointer;
      background: none;
      border: none;
      font-family: 'Inter', sans-serif;
      transition: color .2s;
    }
    .gift-cta-wa:hover { color: rgba(255,255,255,.7); }

    /* ── Rodapé ── */
    .gift-footer {
      margin-top: 48px;
      text-align: center;
      opacity: 0;
      animation: fadeUp .8s ease 1.4s forwards;
    }
    .gift-footer p {
      font-size: .65rem;
      letter-spacing: .12em;
      text-transform: uppercase;
      color: rgba(255,255,255,.18);
    }

    /* ── Ribbon lateral ── */
    .gift-ribbon {
      position: absolute;
      top: -1px; left: 50%; transform: translateX(-50%);
      background: linear-gradient(90deg, var(--green-dark), var(--green));
      color: #fff;
      font-family: 'Inter', sans-serif;
      font-size: .58rem;
      font-weight: 700;
      letter-spacing: .15em;
      padding: 5px 20px;
      white-space: nowrap;
    }

    /* ── Animações ── */
    @keyframes fadeUp {
      from { opacity: 0; transform: translateY(20px); }
      to   { opacity: 1; transform: translateY(0); }
    }

    /* ── Responsivo ── */
    @media (max-width: 480px) {
      .gift-card { padding: 32px 24px; }
    }
  </style>
</head>
<body>

<!-- Fundo animado -->
<div class="gift-bg"></div>
<div class="gift-bg-particles" id="particles"></div>

<div class="gift-wrap">

  <!-- Logo -->
  <div class="gift-logo">
    <img src="<?= base_url('assets/img/jws-logo-horizontal.png') ?>" alt="James Webb Studio">
  </div>

  <!-- Selo -->
  <div class="gift-seal">🎁</div>

  <!-- Texto principal -->
  <p class="gift-eyebrow">Presente Exclusivo</p>
  <h1 class="gift-headline">
    Um ensaio fotográfico<br>
    <em>inteiramente para você.</em>
  </h1>
  <p class="gift-sub">
    Escolhemos o melhor que temos para oferecer —
    porque a sua imagem merece ser registrada com o máximo cuidado.
  </p>

  <!-- Card do valor -->
  <div class="gift-card">
    <div class="gift-ribbon">CORTESIA EXCLUSIVA 🎉</div>

    <p class="gift-card-label">Seu presente</p>

    <?php if ($maxPackage): ?>
      <p class="gift-pkg-name"><?= esc($maxPackage->name) ?></p>
      <p class="gift-value-original">R&nbsp;<?= number_format($maxPackage->base_price, 0, ',', '.') ?></p>
    <?php else: ?>
      <p class="gift-pkg-name">Ensaio Fotográfico</p>
    <?php endif; ?>

    <p class="gift-value-free">GRATUITO</p>
    <p class="gift-value-label">100% cortesia — sem nenhum custo</p>

    <?php if ($maxPackage && $maxPackage->included_photos > 0): ?>
    <div class="gift-divider"></div>
    <p class="gift-includes">
      <span>✓</span><?= (int)$maxPackage->included_photos ?> fotos tratadas incluídas<br>
      <span>✓</span>Sessão completa de ensaio<br>
      <span>✓</span>Experiência premium do início ao fim
    </p>
    <?php endif; ?>
  </div>

  <!-- CTA -->
  <div class="gift-cta">
    <a href="<?= esc($checkoutUrl) ?>" id="ctaBtn">
      QUERO RESGATAR MEU ENSAIO GRATUITO →
    </a>
    <button class="gift-cta-wa" onclick="compartilharWhats()">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/><path d="M12 0C5.373 0 0 5.373 0 12c0 2.136.562 4.14 1.537 5.876L.057 23.886l6.19-1.623A11.94 11.94 0 0012 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 21.818a9.8 9.8 0 01-5.032-1.387l-.36-.214-3.735.979 1-3.644-.234-.373A9.804 9.804 0 012.182 12C2.182 6.57 6.57 2.182 12 2.182c5.43 0 9.818 4.388 9.818 9.818 0 5.43-4.388 9.818-9.818 9.818z"/></svg>
      Compartilhar este presente via WhatsApp
    </button>
  </div>

  <!-- Rodapé -->
  <div class="gift-footer">
    <p>James Webb Studio · Fotografia de Alta Qualidade</p>
    <p style="margin-top:6px;">Cupom: <?= esc($code) ?> · Uso único e intransferível</p>
  </div>

</div><!-- /gift-wrap -->

<script>
  // ── Partículas douradas e verdes ──────────────────────────────────────
  const colors = ['#C5A059','#F5E27A','#6bcb77','#fff'];
  const container = document.getElementById('particles');
  for (let i = 0; i < 28; i++) {
    const p = document.createElement('div');
    p.className = 'particle';
    const size = Math.random() * 4 + 2;
    p.style.cssText = `
      left: ${Math.random()*100}%;
      width: ${size}px;
      height: ${size}px;
      background: ${colors[Math.floor(Math.random()*colors.length)]};
      animation-duration: ${6 + Math.random()*10}s;
      animation-delay: ${Math.random()*8}s;
    `;
    container.appendChild(p);
  }

  // ── Compartilhar no WhatsApp ──────────────────────────────────────────
  function compartilharWhats() {
    const msg = `🎁 *Você tem um presente especial!*\n\nRecebeu um ensaio fotográfico exclusivo do James Webb Studio — completamente gratuito.\n\nClique para resgatar:\n<?= esc(current_url()) ?>`;
    window.open('https://wa.me/?text=' + encodeURIComponent(msg), '_blank');
  }

  // ── Redirect com UTM no botão CTA ────────────────────────────────────
  document.getElementById('ctaBtn').addEventListener('click', function(e) {
    e.preventDefault();
    this.textContent = 'CARREGANDO...';
    setTimeout(() => { window.location.href = this.href; }, 300);
  });
</script>
</body>
</html>
