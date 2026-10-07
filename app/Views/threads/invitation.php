<!doctype html>
<html lang="pt-BR">
<head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="robots" content="noindex,nofollow">
<title><?= esc($opportunity->page_title ?: 'Um convite do James Webb Studio') ?></title>
<style>
:root{--gold:#c5a059;--ink:#111;--paper:#f6f2ea}*{box-sizing:border-box}body{margin:0;background:var(--ink);color:#fff;font-family:Georgia,serif;line-height:1.65}.wrap{max-width:820px;margin:0 auto;padding:48px 22px 80px}.brand{text-align:center;letter-spacing:.28em;text-transform:uppercase;color:var(--gold);font:600 12px system-ui,sans-serif;margin-bottom:70px}.hero{text-align:center;border-bottom:1px solid #3a352c;padding-bottom:52px}.eyebrow{color:var(--gold);font:600 12px system-ui,sans-serif;letter-spacing:.12em;text-transform:uppercase}.hero h1{font-size:clamp(34px,7vw,68px);font-weight:normal;line-height:1.05;margin:18px auto;max-width:720px}.lead{font-size:20px;color:#d9d1c5;max-width:650px;margin:0 auto}.section{max-width:680px;margin:54px auto}.section h2{font-size:28px;font-weight:normal;color:var(--gold)}.quote{border-left:3px solid var(--gold);padding:18px 22px;background:#1c1a17;color:#e9e0d2;font-style:italic;white-space:pre-line}.offer{background:var(--paper);color:var(--ink);padding:30px;border-radius:4px}.offer h2{margin-top:0}.cta{display:inline-block;background:var(--gold);color:#111;text-decoration:none;padding:14px 22px;border-radius:3px;font:700 13px system-ui,sans-serif;letter-spacing:.08em;text-transform:uppercase}.fine{color:#938b80;font:12px system-ui,sans-serif;text-align:center;margin-top:60px}.original{font-size:14px;color:#aca398;white-space:pre-line}.back{color:var(--gold)}.decline{margin:30px auto 0;text-align:center}.decline button{background:transparent;border:0;color:#938b80;text-decoration:underline;cursor:pointer;font:12px system-ui,sans-serif}.decline button:hover{color:#fff}
</style>
</head>
<body>
<main class="wrap">
<div class="brand">James Webb Studio</div>
<section class="hero"><div class="eyebrow">Um convite preparado para <strong>@<?= esc(ltrim($opportunity->threads_username, '@')) ?></strong></div><h1><?= esc($opportunity->page_title ?: 'O que você expressou merece ser visto') ?></h1><?php if ($opportunity->page_intro): ?><p class="lead"><?= nl2br(esc($opportunity->page_intro)) ?></p><?php endif ?><p class="fine">Convite criado a partir de uma publicação sua<?php if ($opportunity->threads_post_url): ?> · <a class="back" href="<?= esc($opportunity->threads_post_url) ?>" target="_blank" rel="noopener"><?= str_contains((string) $opportunity->threads_post_url, '/post/') ? 'ver publicação original' : 'ver perfil no Threads' ?></a><?php endif ?></p></section>
<?php if ($opportunity->original_text): ?><section class="section"><h2>O que você compartilhou</h2><div class="quote"><?= esc($opportunity->original_text) ?></div><?php if ($opportunity->hashtags): ?><p class="original"><?= esc($opportunity->hashtags) ?></p><?php endif ?></section><?php endif ?>
<?php if ($opportunity->offer_copy): ?><section class="section offer"><h2>Uma possibilidade para você</h2><div><?= nl2br(esc($opportunity->offer_copy)) ?></div><?php if ($opportunity->cta_url): ?><p><a class="cta" href="<?= esc($opportunity->cta_url) ?>" target="_blank" rel="noopener"><?= esc($opportunity->cta_label ?: 'Quero conversar') ?></a></p><?php endif ?></section><?php endif ?>
<section class="section"><h2>Sobre o convite</h2><p>Esta página nasceu de uma publicação pública sua. O convite é pessoal e não cria nenhuma obrigação. Se fizer sentido, teremos prazer em conversar sobre uma experiência que respeite sua história, seu estilo e o que você deseja transmitir.</p><?php if (!$opportunity->cta_url): ?><p><a class="back" href="<?= site_url('/') ?>">Conhecer o James Webb Studio →</a></p><?php endif ?></section>
<div class="decline"><form method="post" action="<?= site_url('convite/' . $opportunity->page_token . '/nao-quero-receber') ?>" onsubmit="return confirm('Tem certeza de que não deseja receber novos contatos sobre este convite?')"><button type="submit">Não quero receber este convite nem novos contatos</button></form></div>
<p class="fine">James Webb Studio · Lapa, São Paulo<br>Se preferir não receber novos contatos, basta nos avisar.</p>
</main>
</body>
</html>
