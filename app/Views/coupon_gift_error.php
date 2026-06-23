<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Cupom inválido — James Webb Studio</title>
  <link href="https://fonts.googleapis.com/css2?family=EB+Garamond:ital,wght@0,400;1,400&family=Inter:wght@400;500&display=swap" rel="stylesheet">
  <style>
    body { background:#000; color:#fff; font-family:'Inter',sans-serif; min-height:100vh; display:flex; align-items:center; justify-content:center; text-align:center; padding:40px 20px; }
    .err-logo img { height:32px; filter:brightness(0) invert(1); opacity:.4; margin-bottom:40px; }
    .err-icon { font-size:3rem; margin-bottom:20px; }
    .err-title { font-family:'EB Garamond',serif; font-size:clamp(1.8rem,5vw,2.8rem); color:rgba(255,255,255,.7); margin-bottom:12px; }
    .err-sub { font-size:.85rem; color:rgba(255,255,255,.3); line-height:1.7; max-width:400px; margin:0 auto 32px; }
    .err-btn { display:inline-block; background:transparent; border:1px solid rgba(197,160,89,.35); color:#C5A059; padding:12px 32px; font-size:.7rem; font-weight:500; letter-spacing:.15em; text-transform:uppercase; text-decoration:none; transition:all .2s; }
    .err-btn:hover { border-color:rgba(197,160,89,.7); color:#F5E27A; }
  </style>
</head>
<body>
  <div>
    <div class="err-logo"><img src="<?= base_url('assets/img/jws-logo-horizontal.png') ?>" alt="James Webb Studio"></div>
    <div class="err-icon"><?= ($expired ?? false) ? '⏳' : '🔍' ?></div>
    <h1 class="err-title">
      <?= ($expired ?? false) ? 'Este presente já foi resgatado.' : 'Presente não encontrado.' ?>
    </h1>
    <p class="err-sub">
      <?= ($expired ?? false)
        ? 'O cupom <strong>' . esc($code) . '</strong> já foi utilizado. Cada presente é de uso único e intransferível.'
        : 'Não encontramos um presente com o código <strong>' . esc($code) . '</strong>. Verifique o link recebido.' ?>
    </p>
    <a href="<?= site_url() ?>" class="err-btn">Ver o Portfólio →</a>
  </div>
</body>
</html>
