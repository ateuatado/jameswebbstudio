<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Um presente especial para você — James Webb Studio</title>
  <meta name="description" content="Você recebeu um ensaio fotográfico exclusivo como presente do James Webb Studio.">
  <meta name="robots" content="noindex">

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
      position: fixed; inset: 0; z-index: 0;
      background:
        radial-gradient(ellipse 80% 60% at 50% 0%, rgba(107,203,119,.08) 0%, transparent 70%),
        radial-gradient(ellipse 60% 40% at 20% 80%, rgba(197,160,89,.06) 0%, transparent 60%),
        #000;
    }
    .gift-bg-particles {
      position: fixed; inset: 0; z-index: 0;
      pointer-events: none; overflow: hidden;
    }
    .particle {
      position: absolute; width: 4px; height: 4px;
      border-radius: 50%; opacity: 0;
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
      position: relative; z-index: 1;
      min-height: 100vh;
      display: flex; flex-direction: column;
      align-items: center;
      padding: 40px 20px 80px;
    }

    /* ── Logo ── */
    .gift-logo {
      margin-bottom: 48px;
      opacity: 0; animation: fadeUp .8s ease .2s forwards;
    }
    .gift-logo img { height: 36px; filter: brightness(0) invert(1); opacity: .6; }

    /* ── Selo ── */
    .gift-seal {
      width: 96px; height: 96px; border-radius: 50%;
      background: linear-gradient(135deg, rgba(107,203,119,.15), rgba(107,203,119,.05));
      border: 1px solid rgba(107,203,119,.3);
      display: flex; align-items: center; justify-content: center;
      font-size: 2.8rem; margin-bottom: 28px;
      opacity: 0;
      animation: fadeUp .8s ease .4s forwards, sealPulse 3s ease-in-out 1.5s infinite;
    }
    @keyframes sealPulse {
      0%,100% { box-shadow: 0 0 0 0 rgba(107,203,119,0); }
      50%      { box-shadow: 0 0 0 16px rgba(107,203,119,.08); }
    }

    /* ── Texto principal ── */
    .gift-eyebrow {
      font-size: .6rem; letter-spacing: .3em; text-transform: uppercase;
      color: rgba(107,203,119,.7); margin-bottom: 16px;
      opacity: 0; animation: fadeUp .8s ease .55s forwards;
    }
    .gift-headline {
      font-family: 'EB Garamond', Georgia, serif;
      font-size: clamp(2rem, 7vw, 3.6rem); font-weight: 400;
      line-height: 1.2; text-align: center; max-width: 640px;
      margin-bottom: 12px;
      opacity: 0; animation: fadeUp .8s ease .7s forwards;
    }
    .gift-headline em { font-style: italic; color: var(--gold); }
    .gift-sub {
      font-family: 'EB Garamond', Georgia, serif;
      font-size: clamp(1rem, 2.5vw, 1.2rem); font-style: italic;
      color: rgba(255,255,255,.45); text-align: center;
      max-width: 480px; line-height: 1.65; margin-bottom: 40px;
      opacity: 0; animation: fadeUp .8s ease .85s forwards;
    }

    /* ── Banner do cupom (NOVO) ── */
    .coupon-banner {
      max-width: 560px; width: 100%;
      background: linear-gradient(135deg, rgba(107,203,119,.08), rgba(107,203,119,.03));
      border: 1px solid rgba(107,203,119,.35);
      border-radius: 4px;
      padding: 32px 36px; text-align: center;
      margin-bottom: 56px; position: relative;
      opacity: 0; animation: fadeUp .8s ease 1s forwards;
    }
    .coupon-banner::before {
      content: '';
      position: absolute; top: 0; left: 50%; transform: translateX(-50%);
      width: 50%; height: 1px;
      background: linear-gradient(90deg, transparent, rgba(107,203,119,.6), transparent);
    }
    .coupon-banner-title {
      font-family: 'Inter', sans-serif; font-size: .65rem;
      letter-spacing: .25em; text-transform: uppercase;
      color: var(--green); margin-bottom: 12px;
    }
    .coupon-banner-headline {
      font-family: 'EB Garamond', Georgia, serif;
      font-size: clamp(1.2rem, 4vw, 1.6rem); font-weight: 400;
      color: #fff; margin-bottom: 6px;
    }
    .coupon-banner-sub {
      font-family: 'Inter', sans-serif; font-size: .8rem;
      color: rgba(255,255,255,.4); margin-bottom: 24px;
    }

    /* Código grande */
    .coupon-code-box {
      display: flex; align-items: center; justify-content: center;
      gap: 12px; flex-wrap: wrap;
    }
    .coupon-code {
      font-family: 'Inter', monospace;
      font-size: clamp(1.6rem, 6vw, 2.4rem);
      font-weight: 700; letter-spacing: .15em;
      color: var(--green);
      background: rgba(107,203,119,.08);
      border: 1px dashed rgba(107,203,119,.4);
      padding: 10px 24px; border-radius: 6px;
      text-shadow: 0 0 30px rgba(107,203,119,.3);
      user-select: all; cursor: copy;
    }
    .btn-copy {
      background: none; border: 1px solid rgba(107,203,119,.35);
      color: rgba(107,203,119,.7); font-family: 'Inter', sans-serif;
      font-size: .65rem; letter-spacing: .15em; text-transform: uppercase;
      padding: 8px 14px; border-radius: 4px; cursor: pointer;
      transition: all .2s; white-space: nowrap;
    }
    .btn-copy:hover { background: rgba(107,203,119,.1); color: var(--green); }
    .btn-copy.copied { background: rgba(107,203,119,.15); color: var(--green); }

    .coupon-banner-hint {
      margin-top: 20px; font-size: .75rem;
      color: rgba(255,255,255,.3); line-height: 1.7;
    }
    .coupon-banner-hint strong { color: rgba(255,255,255,.55); }
    .coupon-arrow {
      display: block; margin-top: 18px;
      font-size: 1.5rem; color: rgba(107,203,119,.5);
      animation: bounce 1.5s ease-in-out infinite;
    }
    @keyframes bounce {
      0%,100% { transform: translateY(0); }
      50%      { transform: translateY(6px); }
    }

    /* ── Seção de pacotes ── */
    .packages-section {
      max-width: 860px; width: 100%;
      opacity: 0; animation: fadeUp .8s ease 1.2s forwards;
    }
    .packages-title {
      font-family: 'Inter', sans-serif; font-size: .6rem;
      letter-spacing: .3em; text-transform: uppercase;
      color: rgba(255,255,255,.25); text-align: center;
      margin-bottom: 28px;
    }
    .packages-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
      gap: 1.25rem;
    }

    /* Card de pacote */
    .pkg-card {
      background: rgba(255,255,255,.03);
      border: 1px solid rgba(255,255,255,.08);
      border-radius: 6px; overflow: hidden;
      display: flex; flex-direction: column;
      transition: border-color .3s, transform .3s;
    }
    .pkg-card:hover {
      border-color: rgba(197,160,89,.35);
      transform: translateY(-4px);
      box-shadow: 0 12px 40px rgba(0,0,0,.5);
    }
    .pkg-card-top {
      padding: 28px 24px 20px;
      border-bottom: 1px solid rgba(255,255,255,.05);
      flex: 1;
    }
    .pkg-name {
      font-family: 'EB Garamond', Georgia, serif;
      font-size: 1.3rem; font-weight: 400;
      color: #fff; margin-bottom: 10px;
    }
    .pkg-original-price {
      font-size: .8rem; color: rgba(255,255,255,.25);
      text-decoration: line-through; margin-bottom: 4px;
    }
    .pkg-free {
      font-family: 'EB Garamond', Georgia, serif;
      font-size: 1.8rem; font-weight: 400;
      color: var(--green); line-height: 1;
      margin-bottom: 16px;
      text-shadow: 0 0 20px rgba(107,203,119,.2);
    }
    .pkg-features {
      font-size: .73rem; color: rgba(255,255,255,.4);
      line-height: 1.9; list-style: none;
    }
    .pkg-features li::before { content: '✓ '; color: rgba(197,160,89,.6); }

    .pkg-card-footer { padding: 20px 24px; }
    .btn-escolher {
      display: block; width: 100%; text-align: center;
      background: linear-gradient(135deg, var(--green-dark), var(--green));
      color: #fff; text-decoration: none;
      padding: 13px 20px;
      font-family: 'Inter', sans-serif; font-size: .7rem;
      font-weight: 700; letter-spacing: .18em; text-transform: uppercase;
      border-radius: 4px;
      transition: transform .2s, box-shadow .3s;
    }
    .btn-escolher:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 24px rgba(107,203,119,.25);
    }

    /* Destaque no pacote mais caro */
    .pkg-card.featured { border-color: rgba(197,160,89,.3); }
    .pkg-featured-badge {
      background: linear-gradient(90deg, #C5A059, #F5E27A);
      color: #000; font-family: 'Inter', sans-serif;
      font-size: .55rem; font-weight: 700;
      letter-spacing: .2em; text-transform: uppercase;
      padding: 5px 16px; text-align: center;
    }

    /* ── Rodapé ── */
    .gift-footer {
      margin-top: 56px; text-align: center;
      opacity: 0; animation: fadeUp .8s ease 1.4s forwards;
    }
    .gift-footer p {
      font-size: .62rem; letter-spacing: .12em;
      text-transform: uppercase; color: rgba(255,255,255,.15);
    }
    .gift-footer-wa {
      display: inline-flex; align-items: center; gap: 6px;
      color: rgba(255,255,255,.25); font-size: .7rem;
      cursor: pointer; background: none; border: none;
      font-family: 'Inter', sans-serif; margin-top: 12px;
      transition: color .2s;
    }
    .gift-footer-wa:hover { color: rgba(255,255,255,.6); }

    /* ── Animações ── */
    @keyframes fadeUp {
      from { opacity: 0; transform: translateY(20px); }
      to   { opacity: 1; transform: translateY(0); }
    }

    /* ── Responsivo ── */
    @media (max-width: 480px) {
      .coupon-banner { padding: 24px 20px; }
      .packages-grid { grid-template-columns: 1fr; }
    }
  </style>
</head>
<body>

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
    Você ganhou um ensaio fotográfico 100% gratuito —
    escolha o que mais combina com você e resgate agora.
  </p>

  <!-- ── Banner do cupom ── -->
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
      no checkout, marque a opção de cupom e cole o código acima.
    </p>
    <span class="coupon-arrow">↓</span>
  </div>

  <!-- ── Pacotes ── -->
  <div class="packages-section">
    <p class="packages-title">Escolha o ensaio que é a sua cara</p>

    <div class="packages-grid">
      <?php foreach ($packages as $pkg): ?>
        <?php $isFeatured = ($maxPackage && $pkg->id === $maxPackage->id); ?>
        <div class="pkg-card <?= $isFeatured ? 'featured' : '' ?>">

          <?php if ($isFeatured): ?>
            <div class="pkg-featured-badge">⭐ Mais completo</div>
          <?php endif ?>

          <div class="pkg-card-top">
            <div class="pkg-name"><?= esc($pkg->name) ?></div>

            <?php if ($pkg->base_price > 0): ?>
              <div class="pkg-original-price">R$ <?= number_format($pkg->base_price, 0, ',', '.') ?></div>
            <?php endif ?>

            <div class="pkg-free">Gratuito</div>

            <ul class="pkg-features">
              <?php if (!empty($pkg->included_photos) && $pkg->included_photos > 0): ?>
                <li><?= (int)$pkg->included_photos ?> fotos tratadas incluídas</li>
              <?php endif ?>
              <li>Sessão completa de ensaio</li>
              <li>Experiência premium</li>
              <?php if (!empty($pkg->description)): ?>
                <li><?= esc(mb_substr(strip_tags($pkg->description), 0, 60)) ?>...</li>
              <?php endif ?>
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
  // ── Partículas ──
  const colors = ['#C5A059','#F5E27A','#6bcb77','#fff'];
  const container = document.getElementById('particles');
  for (let i = 0; i < 28; i++) {
    const p = document.createElement('div');
    p.className = 'particle';
    const size = Math.random() * 4 + 2;
    p.style.cssText = `
      left: ${Math.random()*100}%;
      width: ${size}px; height: ${size}px;
      background: ${colors[Math.floor(Math.random()*colors.length)]};
      animation-duration: ${6 + Math.random()*10}s;
      animation-delay: ${Math.random()*8}s;
    `;
    container.appendChild(p);
  }

  // ── Copiar código ──
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

  // ── WhatsApp ──
  function compartilharWhats() {
    const msg = `🎁 *Você tem um presente especial!*\n\nRecebeu um ensaio fotográfico exclusivo do James Webb Studio — completamente gratuito.\n\nClique para resgatar:\n<?= esc(current_url()) ?>`;
    window.open('https://wa.me/?text=' + encodeURIComponent(msg), '_blank');
  }
</script>
</body>
</html>
