<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<style>
    @page {
        margin: 50px 100px 55px 100px;
    }
    body {
        font-family: 'Inter', 'DejaVu Sans', 'Helvetica', sans-serif;
        font-size: 12pt;
        color: #333;
        line-height: 1.4;
    }

    /* ── Capa ── */
    .cover {
        text-align: center;
        padding-top: 180px;
    }
    .cover-brand {
        font-size: 13pt;
        letter-spacing: 6px;
        text-transform: uppercase;
        color: #999;
        margin-bottom: 30px;
    }
    .cover-title {
        font-size: 30pt;
        font-weight: 300;
        color: #1a1a1a;
        margin-bottom: 6px;
        letter-spacing: 1px;
    }
    .cover-subtitle {
        font-size: 13pt;
        color: #888;
        font-style: italic;
        margin-bottom: 50px;
    }
    .cover-client {
        font-size: 16pt;
        color: #B8963E;
        font-weight: 600;
        margin-bottom: 4px;
    }
    .cover-meta {
        font-size: 10pt;
        color: #aaa;
        letter-spacing: 1px;
    }
    .cover-line {
        width: 50px;
        height: 2px;
        background: #B8963E;
        margin: 24px auto;
    }

    /* ── Seções ── */
    .section {
        page-break-inside: avoid;
        margin-bottom: 12px;
    }
    .section-title {
        font-size: 13pt;
        font-weight: bold;
        color: #1a1a1a;
        margin-bottom: 4px;
        padding-bottom: 3px;
        border-bottom: 1px solid #e0e0e0;
        letter-spacing: 0.3px;
    }
    .section-content {
        font-size: 10.5pt;
        color: #444;
        line-height: 1.4;
    }

    /* ── Listas ── */
    .item-yes {
        color: #2e7d32;
        font-weight: bold;
    }
    .item-no {
        color: #c62828;
        font-weight: bold;
    }
    .item-bullet {
        color: #B8963E;
        font-weight: bold;
    }

    /* ── Divider entre grupos ── */
    .group-header {
        font-size: 11pt;
        letter-spacing: 3px;
        text-transform: uppercase;
        color: #B8963E;
        margin: 28px 0 14px;
        padding-bottom: 6px;
        border-bottom: 2px solid #B8963E;
    }

    /* ── Contracapa ── */
    .backcover {
        page-break-before: always;
        text-align: center;
        padding-top: 220px;
    }
    .backcover-brand {
        font-size: 20pt;
        font-weight: 300;
        color: #1a1a1a;
        letter-spacing: 2px;
        margin-bottom: 24px;
    }
    .backcover-info {
        font-size: 11pt;
        color: #777;
        line-height: 1.8;
    }
    .backcover-line {
        width: 40px;
        height: 1px;
        background: #B8963E;
        margin: 16px auto;
    }
    .backcover-footer {
        font-size: 8pt;
        color: #bbb;
        margin-top: 20px;
    }

    /* ── Footer ── */
    .page-footer {
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        text-align: center;
        font-size: 7pt;
        color: #ddd;
        letter-spacing: 2px;
    }
</style>
</head>
<body>

<div class="page-footer">MARCO SANTO FOTOGRAFIA</div>

<!-- CAPA -->
<div class="cover">
    <div class="cover-brand">Marco Santo</div>
    <div class="cover-line"></div>
    <div class="cover-title">Guia Pre-Ensaio</div>
    <div class="cover-subtitle">Tudo que voce precisa saber antes do seu ensaio</div>
    <div class="cover-line"></div>
    <div class="cover-client"><?= esc($clientName) ?></div>
    <?php if (!empty($shootType)): ?>
        <div class="cover-meta"><?= esc($shootType) ?></div>
    <?php endif; ?>
    <div class="cover-meta" style="margin-top:4px;"><?= esc($shootDate) ?></div>
</div>

<!-- CONTEUDO -->
<div style="page-break-before:always;"></div>

<?php
    $lastCatId = 'NONE';
?>
<?php foreach ($sections as $s): ?>
    <?php
        $isNiche = !empty($s->category_id);
        if ($isNiche && $s->category_id !== $lastCatId) {
            $lastCatId = $s->category_id;
    ?>
        <div class="group-header">Orientacoes Especificas</div>
    <?php } ?>

    <div class="section">
        <div class="section-title"><?= esc($s->title) ?></div>
        <div class="section-content"><?= $formatContent($s->content) ?></div>
    </div>
<?php endforeach; ?>

<!-- CONTRACAPA -->
<div class="backcover">
    <div class="backcover-brand">Marco Santo</div>
    <div class="backcover-line"></div>
    <div class="backcover-info">
        Estudio na Lapa - um bairro encantador que parou no tempo<br>
        Estacionamento disponivel na rua<br>
        Wi-Fi no estudio<br>
        <br>
        Duvidas? Chama no WhatsApp
    </div>
    <div class="backcover-line"></div>
    <div class="backcover-footer">
        Este guia foi preparado especialmente para <?= esc($clientName) ?><br>
        Marco Santo Fotografia - Todos os direitos reservados
    </div>
</div>

</body>
</html>
