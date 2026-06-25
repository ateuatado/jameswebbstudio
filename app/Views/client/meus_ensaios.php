<?= $this->extend('layout/main') ?>

<?= $this->section('styles') ?>
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
<style>
    .client-hub { margin-top: 80px; min-height: 80vh; }

    /* ── Tab Nav ── */
    .hub-tabs {
        background: rgba(0,0,0,0.6);
        border-bottom: 1px solid rgba(197,160,89,.15);
        backdrop-filter: blur(10px);
        position: sticky; top: 72px; z-index: 100;
    }
    .hub-tabs-inner {
        display: flex;
        gap: 0;
        overflow-x: auto;
        scrollbar-width: none;
        max-width: 960px;
        margin: 0 auto;
        padding: 0 1rem;
    }
    .hub-tabs-inner::-webkit-scrollbar { display: none; }
    .hub-tab {
        display: flex; align-items: center; gap: 8px;
        padding: 1rem 1.4rem;
        font-family: 'Inter', sans-serif;
        font-size: .72rem;
        letter-spacing: .15em;
        text-transform: uppercase;
        color: rgba(255,255,255,.4);
        text-decoration: none;
        border-bottom: 2px solid transparent;
        transition: all .25s;
        white-space: nowrap;
        flex-shrink: 0;
    }
    .hub-tab:hover { color: rgba(197,160,89,.7); }
    .hub-tab.active {
        color: #C5A059;
        border-bottom-color: #C5A059;
    }
    .hub-tab .tab-count {
        background: rgba(197,160,89,.15);
        color: #C5A059;
        font-size: .58rem;
        padding: 2px 7px;
        border-radius: 20px;
        font-weight: 700;
    }

    /* ── Conteúdo das Abas ── */
    .hub-content { max-width: 960px; margin: 0 auto; padding: 2.5rem 1rem 4rem; }

    /* ── Header da aba ── */
    .tab-header {
        margin-bottom: 2rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid rgba(197,160,89,.12);
    }
    .tab-header h2 {
        font-family: 'EB Garamond', Georgia, serif;
        font-size: 1.6rem; font-weight: 400;
        color: #fff; margin: 0 0 4px;
    }
    .tab-header p {
        font-family: 'Inter', sans-serif;
        font-size: .75rem; color: rgba(255,255,255,.35);
        letter-spacing: .1em; margin: 0;
    }

    /* ── Card de Ensaio ── */
    .ensaio-card {
        background: rgba(255,255,255,.03);
        border: 1px solid rgba(255,255,255,.08);
        padding: 0; margin-bottom: 20px;
        transition: all .3s; position: relative;
        border-radius: 8px; overflow: hidden;
    }
    .ensaio-card::before {
        content: ''; position: absolute;
        top: 0; left: 0; width: 3px; height: 100%;
        background: var(--accent-color, rgba(197,160,89,.4));
    }
    .ensaio-card:hover { border-color: rgba(197,160,89,.2); }
    .card-header {
        padding: 20px 24px 16px;
        border-bottom: 1px solid rgba(255,255,255,.06);
        display: flex; align-items: flex-start;
        justify-content: space-between; gap: 12px; flex-wrap: wrap;
    }
    .card-title { font-family: 'EB Garamond', Georgia, serif; font-size: 1.3rem; color: #fff; margin: 0 0 4px; }
    .card-meta  { font-family: 'Inter', sans-serif; font-size: .7rem; color: rgba(255,255,255,.35); margin: 0; }

    /* Bloco de preços */
    .card-pricing {
        padding: 16px 24px;
        border-bottom: 1px solid rgba(255,255,255,.06);
        display: flex; align-items: baseline; gap: 14px; flex-wrap: wrap;
    }
    .price-original {
        font-family: 'Inter', sans-serif;
        font-size: .85rem;
        color: rgba(255,255,255,.3);
        text-decoration: line-through;
    }
    .price-discount-tag {
        font-family: 'Inter', sans-serif;
        font-size: .65rem; letter-spacing: .12em; text-transform: uppercase;
        background: rgba(197,160,89,.12);
        border: 1px solid rgba(197,160,89,.25);
        color: #C5A059;
        padding: 3px 10px; border-radius: 20px;
    }
    .price-paid {
        font-family: 'EB Garamond', Georgia, serif;
        font-size: 1.6rem;
        color: #C5A059;
        font-weight: 400;
        letter-spacing: .02em;
    }
    .price-free {
        font-family: 'EB Garamond', Georgia, serif;
        font-size: 1.4rem;
        color: #66bb6a;
        font-style: italic;
    }

    /* Data do ensaio */
    .card-date {
        padding: 12px 24px;
        background: rgba(46,125,50,.08);
        border-bottom: 1px solid rgba(102,187,106,.15);
        display: flex; align-items: center; gap: 10px;
    }
    .card-date .date-label {
        font-family: 'Inter', sans-serif;
        font-size: .6rem; letter-spacing: .18em; text-transform: uppercase;
        color: rgba(102,187,106,.7); margin: 0 0 2px;
    }
    .card-date .date-value {
        font-family: 'EB Garamond', Georgia, serif;
        font-size: 1.05rem; color: #fff; margin: 0;
    }

    /* Ações */
    .card-actions {
        padding: 14px 24px;
        display: flex; gap: 10px; flex-wrap: wrap; align-items: center;
    }

    /* ── Status badges ── */
    .status-badge {
        display: inline-flex; align-items: center; gap: 6px;
        font-family: 'Inter', sans-serif; font-size: .62rem;
        letter-spacing: .1em; text-transform: uppercase;
        padding: 5px 12px; border-radius: 20px; white-space: nowrap;
    }
    .status-badge.approved  { background: rgba(46,125,50,.15); color: #66bb6a; }
    .status-badge.pending   { background: rgba(255,183,77,.12); color: #ffb74d; }
    .status-badge.open,
    .status-badge.selecting { background: rgba(100,181,246,.12); color: #64b5f6; }
    .status-badge.completed { background: rgba(255,255,255,.06); color: rgba(255,255,255,.5); }

    /* ── Botões de ação ── */
    .btn-ensaio {
        font-family: 'Inter', sans-serif; font-size: .67rem;
        letter-spacing: .12em; text-transform: uppercase;
        padding: 9px 20px; text-decoration: none;
        border: 1px solid rgba(197,160,89,.35); color: #C5A059;
        background: transparent; border-radius: 4px; transition: all .2s;
        display: inline-flex; align-items: center; gap: 6px; cursor: pointer;
    }
    .btn-ensaio:hover { background: rgba(197,160,89,.08); color: #C5A059; }
    .btn-ensaio.primary {
        background: linear-gradient(135deg, #C5A059, #F5E27A);
        color: #000; border-color: transparent; font-weight: 700;
    }
    .btn-ensaio.primary:hover { transform: translateY(-1px); box-shadow: 0 4px 16px rgba(197,160,89,.25); color: #000; }

    /* ── Modal de Agendamento Embutido ── */
    .bk-overlay {
        display: none; position: fixed; inset: 0;
        background: rgba(0,0,0,.88); backdrop-filter: blur(8px);
        z-index: 9990; align-items: center; justify-content: center;
        padding: 20px;
    }
    .bk-overlay.open { display: flex; }
    .bk-modal {
        background: #0d0d0d;
        border: 1px solid rgba(197,160,89,.2);
        border-radius: 4px;
        width: 100%; max-width: 520px;
        max-height: 90vh; overflow-y: auto;
        position: relative;
        animation: bkIn .25s ease;
    }
    @keyframes bkIn { from{opacity:0;transform:translateY(16px)} to{opacity:1;transform:translateY(0)} }
    .bk-modal-head {
        padding: 24px 28px 16px;
        border-bottom: 1px solid rgba(255,255,255,.07);
        display: flex; align-items: flex-start; justify-content: space-between;
    }
    .bk-modal-head h3 {
        font-family: 'EB Garamond', Georgia, serif;
        font-size: 1.3rem; font-style: italic;
        color: #C5A059; margin: 0 0 4px;
    }
    .bk-modal-head p {
        font-family: 'Inter', sans-serif;
        font-size: .72rem; color: rgba(255,255,255,.4); margin: 0;
    }
    .bk-close {
        background: none; border: none; color: rgba(255,255,255,.3);
        font-size: 1.4rem; cursor: pointer; line-height: 1; padding: 4px;
        transition: color .2s; flex-shrink: 0;
    }
    .bk-close:hover { color: #fff; }
    .bk-body { padding: 24px 28px; }

    /* Calendário */
    .bk-cal-nav {
        display: flex; align-items: center; justify-content: space-between;
        margin-bottom: 16px;
    }
    .bk-cal-nav button {
        background: transparent; border: 1px solid rgba(255,255,255,.15);
        color: #fff; width: 36px; height: 36px; border-radius: 50%;
        cursor: pointer; font-size: 1rem; transition: all .2s;
    }
    .bk-cal-nav button:hover { border-color: #C5A059; color: #C5A059; }
    .bk-month-label {
        font-family: 'EB Garamond', Georgia, serif;
        font-size: 1.1rem; font-style: italic;
        color: #C5A059; letter-spacing: .05em;
    }
    .bk-weekdays {
        display: grid; grid-template-columns: repeat(7,1fr);
        text-align: center; font-family: 'Inter', sans-serif;
        font-size: .6rem; color: rgba(255,255,255,.3);
        letter-spacing: .1em; text-transform: uppercase;
        margin-bottom: 8px;
    }
    .bk-grid {
        display: grid; grid-template-columns: repeat(7,1fr);
        gap: 5px; min-height: 200px;
    }
    .bk-day {
        aspect-ratio: 1; display: flex; align-items: center; justify-content: center;
        border-radius: 6px; font-family: 'Inter', sans-serif;
        font-size: .85rem; border: 1px solid transparent; transition: all .18s;
    }
    .bk-day.empty  { background: transparent; }
    .bk-day.past   { color: rgba(255,255,255,.1); }
    .bk-day.booked { background: rgba(255,255,255,.03); color: rgba(255,255,255,.18); }
    .bk-day.avail  {
        background: rgba(197,160,89,.1); color: #C5A059;
        border-color: rgba(197,160,89,.25); cursor: pointer;
    }
    .bk-day.avail:hover {
        background: rgba(197,160,89,.22); border-color: rgba(197,160,89,.6);
        transform: scale(1.08);
    }
    .bk-day.today  { border-color: rgba(255,255,255,.25); }
    .bk-loading { grid-column:1/-1; display:flex; align-items:center; justify-content:center; padding:40px 0; }
    .bk-spinner {
        width:28px; height:28px;
        border: 2px solid rgba(255,255,255,.08);
        border-top-color: #C5A059;
        border-radius: 50%; animation: bkSpin .8s linear infinite;
    }
    @keyframes bkSpin { to{transform:rotate(360deg)} }
    .bk-legend {
        font-family: 'Inter', sans-serif; font-size: .75rem;
        color: rgba(255,255,255,.35); margin-top: 14px; text-align: center;
    }
    .bk-dot {
        display: inline-block; width: 8px; height: 8px;
        border-radius: 50%; margin-right: 4px; vertical-align: middle;
    }
    .bk-dot.a { background: #C5A059; }
    .bk-dot.b { background: rgba(255,255,255,.15); }

    /* Slots */
    .bk-slots-list { display: flex; flex-direction: column; gap: 8px; max-height: 280px; overflow-y: auto; }
    .bk-slot {
        background: rgba(255,255,255,.04);
        border: 1px solid rgba(255,255,255,.1);
        border-radius: 8px; padding: 12px 16px;
        cursor: pointer; transition: all .18s;
        display: flex; justify-content: space-between; align-items: center;
    }
    .bk-slot:hover { background: rgba(197,160,89,.1); border-color: rgba(197,160,89,.35); }
    .bk-slot-time { font-family: 'Inter', sans-serif; font-weight: 600; color: #fff; }
    .bk-slot-type { font-family: 'Inter', sans-serif; font-size: .75rem; color: rgba(255,255,255,.4); }
    .bk-back-link {
        background: none; border: none; color: rgba(197,160,89,.7);
        font-family: 'Inter', sans-serif; font-size: .75rem;
        cursor: pointer; padding: 0; margin-bottom: 16px;
        text-transform: uppercase; letter-spacing: .1em;
        transition: color .2s;
    }
    .bk-back-link:hover { color: #C5A059; }

    /* Formulário */
    .bk-field { margin-bottom: 14px; }
    .bk-field label {
        display: block; font-family: 'Inter', sans-serif;
        font-size: .7rem; letter-spacing: .08em; text-transform: uppercase;
        color: rgba(255,255,255,.45); margin-bottom: 5px;
    }
    .bk-field input, .bk-field textarea {
        width: 100%; background: rgba(255,255,255,.05);
        border: 1px solid rgba(255,255,255,.12); border-radius: 6px;
        color: #fff; padding: 10px 12px;
        font-family: inherit; font-size: .9rem;
        transition: border-color .2s; box-sizing: border-box;
    }
    .bk-field input:focus, .bk-field textarea:focus {
        outline: none; border-color: rgba(197,160,89,.5);
    }
    .bk-form-actions { display: flex; gap: 10px; margin-top: 18px; }
    .bk-btn-primary {
        flex: 1; background: linear-gradient(135deg,#C5A059,#F5E27A);
        color: #000; border: none; padding: 13px 20px;
        font-family: 'Inter', sans-serif; font-size: .72rem;
        font-weight: 700; letter-spacing: .15em; text-transform: uppercase;
        cursor: pointer; transition: all .2s; border-radius: 2px;
    }
    .bk-btn-primary:hover { transform: translateY(-1px); box-shadow: 0 4px 16px rgba(197,160,89,.3); }
    .bk-btn-primary:disabled { opacity: .5; cursor: not-allowed; transform: none; }
    .bk-btn-secondary {
        background: transparent; border: 1px solid rgba(255,255,255,.15);
        color: rgba(255,255,255,.5); padding: 13px 16px;
        font-family: 'Inter', sans-serif; font-size: .72rem;
        letter-spacing: .1em; text-transform: uppercase;
        cursor: pointer; transition: all .2s; border-radius: 2px;
    }
    .bk-btn-secondary:hover { border-color: rgba(255,255,255,.4); color: #fff; }
    .bk-error {
        background: rgba(220,53,69,.12); border: 1px solid rgba(220,53,69,.25);
        border-radius: 6px; padding: 10px 14px;
        color: #ff8a8a; font-family: 'Inter', sans-serif; font-size: .82rem;
        margin-bottom: 12px;
    }

    /* Sucesso */
    .bk-success { text-align: center; padding: 16px 0; }
    .bk-success-icon {
        width: 56px; height: 56px; border-radius: 50%;
        border: 1px solid #C5A059; color: #C5A059;
        font-size: 1.6rem; display: flex;
        align-items: center; justify-content: center;
        margin: 0 auto 16px;
        animation: bkPop .4s cubic-bezier(.34,1.56,.64,1);
    }
    @keyframes bkPop { from{transform:scale(0);opacity:0} to{transform:scale(1);opacity:1} }
    .bk-success h4 {
        font-family: 'EB Garamond', Georgia, serif;
        font-size: 1.4rem; font-style: italic;
        color: #C5A059; margin: 0 0 8px;
    }
    .bk-success p { color: rgba(255,255,255,.5); font-size: .88rem; line-height: 1.6; }
    .bk-success-close {
        margin-top: 20px;
        background: linear-gradient(135deg,#C5A059,#F5E27A);
        color: #000; border: none; padding: 12px 32px;
        font-family: 'Inter', sans-serif; font-size: .72rem;
        font-weight: 700; letter-spacing: .15em; text-transform: uppercase;
        cursor: pointer; border-radius: 2px;
    }

    /* ── Galeria grid ── */
    .gallery-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
        gap: 1.25rem;
    }
    .gallery-card {
        background: rgba(255,255,255,.03);
        border: 1px solid rgba(255,255,255,.08);
        border-radius: 8px; overflow: hidden;
        transition: all .3s; text-decoration: none;
        display: flex; flex-direction: column;
    }
    .gallery-card:hover { border-color: rgba(197,160,89,.35); transform: translateY(-3px); box-shadow: 0 10px 30px rgba(0,0,0,.4); }
    .gallery-card-cover {
        height: 160px; background: #111;
        display: flex; align-items: center; justify-content: center;
        font-size: 2.5rem; color: rgba(197,160,89,.2);
    }
    .gallery-card-body { padding: 1rem; flex: 1; }
    .gallery-card-title { font-family: 'EB Garamond', Georgia, serif; font-size: 1.1rem; color: #fff; margin: 0 0 4px; }
    .gallery-card-meta { font-family: 'Inter', sans-serif; font-size: .7rem; color: rgba(255,255,255,.35); margin: 0; }
    .gallery-card-footer { padding: .75rem 1rem; border-top: 1px solid rgba(255,255,255,.05); display: flex; align-items: center; justify-content: space-between; }

    /* ── Busca ── */
    .search-box {
        display: flex; gap: .75rem; margin-bottom: 2rem;
    }
    .search-input {
        flex: 1; background: rgba(255,255,255,.04) !important;
        border: 1px solid rgba(197,160,89,.3) !important;
        color: #fff !important; padding: .8rem 1.1rem !important;
        border-radius: 8px !important; font-family: 'Inter', sans-serif;
        font-size: .9rem !important; transition: border-color .3s;
    }
    .search-input:focus { border-color: #C5A059 !important; outline: none !important; box-shadow: 0 0 0 3px rgba(197,160,89,.1) !important; }
    .btn-buscar {
        background: #C5A059; color: #000; font-weight: 700;
        border: none; padding: .8rem 1.6rem; border-radius: 8px;
        font-family: 'Inter', sans-serif; font-size: .8rem;
        letter-spacing: .1em; text-transform: uppercase;
        cursor: pointer; transition: background .2s; white-space: nowrap;
    }
    .btn-buscar:hover { background: #d4b06a; }
    .photo-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(180px, 1fr)); gap: 1rem; }
    .photo-card { border-radius: 8px; overflow: hidden; background: #111; border: 1px solid rgba(255,255,255,.07); cursor: zoom-in; transition: all .3s; }
    .photo-card:hover { border-color: #C5A059; transform: translateY(-2px); }
    .photo-card img { width: 100%; height: 200px; object-fit: contain; background: #000; display: block; }
    .photo-card-body { padding: 8px 10px; }
    .project-pill { display: inline-block; background: rgba(197,160,89,.1); color: #C5A059; font-size: .6rem; font-weight: 700; text-transform: uppercase; letter-spacing: .05em; padding: 2px 8px; border-radius: 20px; max-width: 100%; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
    .tag { background: #1a1a1a; color: #666; font-size: .58rem; padding: 2px 6px; border-radius: 6px; }
    .tag.hl { background: rgba(197,160,89,.15); color: #C5A059; }
    #searchStatus { font-family: 'Inter', sans-serif; font-size: .8rem; color: rgba(255,255,255,.4); margin-bottom: 1rem; min-height: 1.2em; }

    /* ── Perfil ── */
    .perfil-form { max-width: 560px; }
    .perfil-section-title {
        font-family: 'Inter', sans-serif;
        font-size: .65rem; letter-spacing: .2em;
        text-transform: uppercase; color: #C5A059;
        margin-bottom: 1rem;
        padding-bottom: .5rem;
        border-bottom: 1px solid rgba(197,160,89,.15);
    }
    .perfil-form label {
        font-family: 'Inter', sans-serif;
        font-size: .72rem; letter-spacing: .08em;
        text-transform: uppercase; color: rgba(255,255,255,.45);
        margin-bottom: 5px; display: block;
    }
    .field-hint {
        font-size: .6rem; text-transform: none;
        letter-spacing: 0; opacity: .5;
    }
    .perfil-row {
        display: flex; gap: 1rem; margin-bottom: 0;
    }
    .perfil-row > div { flex: 1; }
    .perfil-form input {
        width: 100%; background: rgba(255,255,255,.04);
        border: 1px solid rgba(255,255,255,.1); color: #fff;
        padding: .7rem .9rem; border-radius: 6px;
        font-family: 'Inter', sans-serif; font-size: .88rem;
        margin-bottom: 1.1rem; transition: border-color .2s;
    }
    .perfil-form input:focus { border-color: rgba(197,160,89,.5); outline: none; }
    .perfil-form input[readonly] { opacity: .45; cursor: not-allowed; }
    .perfil-email {
        font-family: 'Inter', sans-serif; font-size: .85rem;
        color: rgba(255,255,255,.35); padding: .7rem .9rem;
        background: rgba(255,255,255,.02);
        border: 1px solid rgba(255,255,255,.06);
        border-radius: 6px; margin-bottom: 1.1rem;
    }

    /* ── Empty state ── */
    .empty-state { text-align: center; padding: 3rem 1.5rem; border: 1px dashed rgba(255,255,255,.08); border-radius: 8px; }
    .empty-state .emoji { font-size: 2.5rem; margin-bottom: 1rem; }
    .empty-state p { font-family: 'Inter', sans-serif; font-size: .85rem; color: rgba(255,255,255,.35); margin: 0; }

    /* ── Lightbox ── */
    .lightbox { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.93); z-index: 9999; align-items: center; justify-content: center; flex-direction: column; gap: 1rem; }
    .lightbox.active { display: flex; }
    .lightbox img { max-width: 90vw; max-height: 80vh; object-fit: contain; border-radius: 6px; }
    .lightbox-close { position: absolute; top: 1.5rem; right: 1.5rem; color: #aaa; font-size: 1.5rem; cursor: pointer; background: none; border: none; }
    .lightbox-close:hover { color: #C5A059; }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<?php $user = auth()->user(); ?>

<div class="client-hub">

    <!-- ── Barra de Abas ── -->
    <nav class="hub-tabs">
        <div class="hub-tabs-inner">
            <a href="?tab=ensaios" class="hub-tab <?= $tab === 'ensaios' ? 'active' : '' ?>">
                <i class="fas fa-clipboard-list"></i> Meus Ensaios
                <?php if (count($orders)): ?>
                    <span class="tab-count"><?= count($orders) ?></span>
                <?php endif ?>
            </a>
            <a href="?tab=galeria" class="hub-tab <?= $tab === 'galeria' ? 'active' : '' ?>">
                <i class="fas fa-images"></i> Galeria
                <?php if (count($projects)): ?>
                    <span class="tab-count"><?= count($projects) ?></span>
                <?php endif ?>
            </a>
            <a href="?tab=busca" class="hub-tab <?= $tab === 'busca' ? 'active' : '' ?>">
                <i class="fas fa-search"></i> Busca de Fotos
            </a>
            <a href="?tab=perfil" class="hub-tab <?= $tab === 'perfil' ? 'active' : '' ?>">
                <i class="fas fa-user"></i> Meu Perfil
            </a>
        </div>
    </nav>

    <div class="hub-content">

        <!-- ════════════════════════════════════════════
             ABA: MEUS ENSAIOS
             ════════════════════════════════════════════ -->
        <?php if ($tab === 'ensaios'): ?>

            <div class="tab-header">
                <h2>Meus Ensaios</h2>
                <p>Suas compras, agendamentos e documentos</p>
            </div>

            <?php if (isset($_GET['bv'])): ?>
            <div style="background:rgba(197,160,89,.08);border:1px solid rgba(197,160,89,.25);border-radius:6px;padding:16px 20px;margin-bottom:24px;display:flex;align-items:center;gap:14px;">
                <span style="font-size:1.8rem;">&#127775;</span>
                <div>
                    <p style="font-family:'EB Garamond',Georgia,serif;font-size:1.15rem;font-style:italic;color:#C5A059;margin:0 0 3px;">Bem-vindo ao seu portal!</p>
                    <p style="font-family:'Inter',sans-serif;font-size:.8rem;color:rgba(255,255,255,.4);margin:0;">Seu ensaio est&aacute; confirmado. Clique em <strong style="color:#C5A059;">Agendar Ensaio</strong> para escolher a data.</p>
                </div>
            </div>
            <?php endif ?>

            <?php if (!empty($orders)): ?>
            <?php foreach ($orders as $order): ?>
            <?php
                $statusLabel = [
                    'approved'  => '&#10003; Pago',
                    'pending'   => '&#9203; Aguardando',
                    'cancelled' => '&#10007; Cancelado',
                ][$order->status] ?? $order->status;
                $packageName  = $order->package ? $order->package->name : 'Ensaio Fotogr&aacute;fico';
                $amountFmt    = 'R$ ' . number_format((float) $order->amount, 2, ',', '.');
                $origFmt      = !empty($order->original_price) ? 'R$ ' . number_format($order->original_price, 2, ',', '.') : null;
                $datePurchase = date('d/m/Y', strtotime($order->created_at));
                $scheduledAt  = $order->scheduled_at ?? null;
                $isFree       = ((float) $order->amount === 0.0);
                $accentColor  = $order->status === 'approved' ? '#66bb6a' : '#ffb74d';
            ?>
            <div class="ensaio-card" style="--accent-color:<?= $accentColor ?>" id="order-card-<?= $order->id ?>">

                <!-- Cabecalho -->
                <div class="card-header">
                    <div>
                        <h4 class="card-title"><?= esc($packageName) ?></h4>
                        <p class="card-meta">Comprado em <?= $datePurchase ?></p>
                    </div>
                    <span class="status-badge <?= esc($order->status) ?>"><?= $statusLabel ?></span>
                </div>

                <!-- Precos -->
                <div class="card-pricing">
                    <?php if ($isFree): ?>
                        <span class="price-free">&#127873; Presente &mdash; Gratuito</span>
                    <?php else: ?>
                        <?php if ($origFmt): ?>
                            <span class="price-original"><?= $origFmt ?></span>
                        <?php endif ?>
                        <?php if ($order->discount_percent > 0): ?>
                            <span class="price-discount-tag">
                                <?= (int)$order->discount_percent ?>% OFF<?= $order->coupon_code ? ' &middot; ' . esc($order->coupon_code) : '' ?>
                            </span>
                        <?php endif ?>
                        <span class="price-paid"><?= $amountFmt ?></span>
                    <?php endif ?>
                </div>

                <!-- Data agendada -->
                <?php if ($scheduledAt): ?>
                <div class="card-date" id="order-date-wrap-<?= $order->id ?>">
                    <span style="font-size:1.4rem">&#128197;</span>
                    <div>
                        <p class="date-label">Data do Ensaio</p>
                        <p class="date-value" id="order-date-<?= $order->id ?>">
                            <?= date('d \d\e F \d\e Y \a\s H:i', strtotime($scheduledAt)) ?>
                        </p>
                    </div>
                </div>
                <?php endif ?>

                <!-- Acoes -->
                <div class="card-actions">
                    <?php if ($order->status === 'approved'): ?>
                        <button
                            class="btn-ensaio primary"
                            onclick="openBookingModal(
                                <?= $order->id ?>,
                                '<?= esc(addslashes($packageName)) ?>',
                                '<?= esc(addslashes($order->buyer_name)) ?>',
                                '<?= esc(addslashes($order->buyer_email)) ?>',
                                '<?= esc(addslashes($order->buyer_phone ?? '')) ?>'
                            )">
                            <i class="fas fa-calendar-alt"></i>
                            <?= $scheduledAt ? 'Alterar Data' : 'Agendar Ensaio' ?>
                        </button>
                        <a href="<?= site_url('client/guia-pre-ensaio/' . $order->id) ?>" class="btn-ensaio" target="_blank">
                            <i class="fas fa-file-alt"></i> Guia Pre-Ensaio
                        </a>
                        <a href="<?= site_url('client/contrato/' . $order->id) ?>" class="btn-ensaio" target="_blank">
                            <i class="fas fa-file-contract"></i> Meu Contrato
                        </a>
                    <?php else: ?>
                        <span class="btn-ensaio" style="opacity:.4;cursor:default">Aguardando confirmacao do pagamento</span>
                    <?php endif ?>
                </div>

            </div>
            <?php endforeach ?>
            <?php else: ?>
                <div class="empty-state">
                    <div class="emoji">&#128722;</div>
                    <p>Nenhum ensaio adquirido ainda.</p>
                </div>
            <?php endif ?>


        <!-- ════════════════════════════════════════════
             ABA: GALERIA
             ════════════════════════════════════════════ -->
        <?php elseif ($tab === 'galeria'): ?>

            <div class="tab-header">
                <h2>Minha Galeria</h2>
                <p>Seus ensaios realizados — clique para acessar as fotos</p>
            </div>

            <?php if (!empty($projects)): ?>
                <div class="gallery-grid">
                    <?php foreach ($projects as $proj): ?>
                        <?php
                            $statusLabel = [
                                'open'      => 'Aberta',
                                'selecting' => 'Selecionando',
                                'paid'      => 'Paga',
                                'completed' => 'Concluída',
                            ][$proj->status] ?? $proj->status;
                            $statusColor = in_array($proj->status, ['open','selecting']) ? '#64b5f6' : 'rgba(255,255,255,.4)';
                        ?>
                        <a href="<?= site_url('client/galeria/' . $proj->id) ?>" class="gallery-card">
                            <div class="gallery-card-cover">
                                <i class="fas fa-images"></i>
                            </div>
                            <div class="gallery-card-body">
                                <div class="gallery-card-title"><?= esc($proj->name ?? 'Ensaio #' . $proj->id) ?></div>
                                <div class="gallery-card-meta">
                                    <?= $proj->photo_count ?> foto<?= $proj->photo_count !== 1 ? 's' : '' ?>
                                    &middot; <?= date('d/m/Y', strtotime($proj->created_at)) ?>
                                </div>
                            </div>
                            <div class="gallery-card-footer">
                                <span style="font-family:'Inter',sans-serif;font-size:.65rem;color:<?= $statusColor ?>;text-transform:uppercase;letter-spacing:.1em;">
                                    <?= esc($statusLabel) ?>
                                </span>
                                <span style="color:#C5A059;font-size:.8rem;"><i class="fas fa-arrow-right"></i></span>
                            </div>
                        </a>
                    <?php endforeach ?>
                </div>
            <?php else: ?>
                <div class="empty-state">
                    <div class="emoji">📷</div>
                    <p>Nenhuma galeria disponível ainda.</p>
                </div>
            <?php endif ?>


        <!-- ════════════════════════════════════════════
             ABA: BUSCA
             ════════════════════════════════════════════ -->
        <?php elseif ($tab === 'busca'): ?>

            <div class="tab-header">
                <h2>Busca de Fotos</h2>
                <p>Pesquise por elementos, poses ou momentos nas suas fotos</p>
            </div>

            <div class="search-box">
                <input type="text" id="searchInput" class="search-input"
                       placeholder="Ex: sorrindo, vestido, jardim, olhando para cima..."
                       autofocus>
                <button class="btn-buscar" onclick="doSearch()">
                    <i class="fas fa-search me-1"></i> Buscar
                </button>
            </div>

            <div id="searchStatus"></div>
            <div id="photoGrid" class="photo-grid"></div>

            <!-- Sugestões iniciais -->
            <div id="suggestions" style="display:flex;flex-wrap:wrap;gap:8px;margin-top:1rem;">
                <?php foreach (['sorrindo','vestido','pose','olhando','jardim','estúdio','família','retrato'] as $s): ?>
                    <button onclick="searchFor('<?= $s ?>')"
                        style="background:#141414;color:#666;border:1px solid #2a2a2a;border-radius:20px;padding:4px 14px;font-size:.78rem;cursor:pointer;transition:all .2s;"
                        onmouseover="this.style.borderColor='#C5A059';this.style.color='#C5A059'"
                        onmouseout="this.style.borderColor='#2a2a2a';this.style.color='#666'">
                        <?= $s ?>
                    </button>
                <?php endforeach ?>
            </div>


        <!-- ════════════════════════════════════════════
             ABA: PERFIL
             ════════════════════════════════════════════ -->
        <?php elseif ($tab === 'perfil'): ?>

            <div class="tab-header">
                <h2>Meu Perfil</h2>
                <p>Seus dados pessoais e endereço para contratos</p>
            </div>

            <?php if (session()->getFlashdata('perfil_ok')): ?>
                <div style="font-size:.85rem;background:rgba(46,125,50,.15);border:1px solid rgba(46,125,50,.3);color:#66bb6a;padding:12px 16px;border-radius:6px;margin-bottom:1.5rem;">
                    ✅ Perfil atualizado com sucesso!
                </div>
            <?php endif ?>

            <form class="perfil-form" method="post" action="<?= site_url('client/perfil/salvar') ?>">
                <?= csrf_field() ?>

                <div class="perfil-section-title">Identificação</div>

                <label>Como gosta de ser chamado(a)</label>
                <input type="text" name="display_name" value="<?= esc($displayName) ?>"
                       placeholder="Nome que aparece no sistema">

                <label>Apelidos <span class="field-hint">separados por vírgula</span></label>
                <input type="text" name="nicknames" value="<?= esc($nicknames) ?>"
                       placeholder="Ex: Bê, Beta, Beatriz...">

                <label>E-mail</label>
                <div class="perfil-email"><?= esc($user->email) ?></div>

                <div class="perfil-section-title" style="margin-top:2rem;">Dados para contrato</div>

                <label>Nome completo <span class="field-hint">conforme documento</span></label>
                <input type="text" name="nome_completo" value="<?= esc($nomeCompleto) ?>"
                       placeholder="Nome como está no RG ou CNH">

                <div class="perfil-row">
                    <div>
                        <label>CPF</label>
                        <input type="text" name="cpf" id="cpf" value="<?= esc($cpf) ?>"
                               placeholder="000.000.000-00" maxlength="14">
                    </div>
                    <div>
                        <label>RG</label>
                        <input type="text" name="rg" value="<?= esc($rg) ?>"
                               placeholder="00.000.000-0">
                    </div>
                </div>

                <div class="perfil-section-title" style="margin-top:2rem;">Endereço</div>

                <div class="perfil-row">
                    <div style="max-width:160px;">
                        <label>CEP</label>
                        <div style="position:relative;">
                            <input type="text" name="endereco_cep" id="cep"
                                   value="<?= esc($enderecoCep) ?>"
                                   placeholder="00000-000" maxlength="9">
                            <span id="cepSpinner" style="display:none;position:absolute;right:10px;top:50%;transform:translateY(-50%);color:#C5A059;">⟳</span>
                        </div>
                    </div>
                    <div style="flex:1;">
                        <label>Logradouro <span class="field-hint">preenchido pelo CEP</span></label>
                        <input type="text" name="endereco_logradouro" id="logradouro"
                               value="<?= esc($enderecoLogradouro) ?>" placeholder="Rua, Av, Alameda...">
                    </div>
                </div>

                <div class="perfil-row">
                    <div style="max-width:120px;">
                        <label>Número</label>
                        <input type="text" name="endereco_numero" id="numero"
                               value="<?= esc($enderecoNumero) ?>" placeholder="123">
                    </div>
                    <div style="flex:1;">
                        <label>Complemento <span class="field-hint">opcional</span></label>
                        <input type="text" name="endereco_complemento"
                               value="<?= esc($enderecoComplemento) ?>" placeholder="Apto, Bloco...">
                    </div>
                </div>

                <div class="perfil-row">
                    <div style="flex:1;">
                        <label>Cidade <span class="field-hint">preenchido pelo CEP</span></label>
                        <input type="text" name="endereco_cidade" id="cidade"
                               value="<?= esc($enderecoCidade) ?>" placeholder="Cidade">
                    </div>
                    <div style="max-width:80px;">
                        <label>Estado</label>
                        <input type="text" name="endereco_estado" id="estado"
                               value="<?= esc($enderecoEstado) ?>" placeholder="SP" maxlength="2"
                               style="text-transform:uppercase;">
                    </div>
                </div>

                <button type="submit" class="btn-ensaio primary" style="margin-top:.5rem;">
                    <i class="fas fa-save"></i> Salvar alterações
                </button>
            </form>

        <?php endif ?>

    </div><!-- /hub-content -->
</div>

<!-- Lightbox -->
<div class="lightbox" id="lightbox" onclick="closeLightbox()">
    <button class="lightbox-close" onclick="closeLightbox()">✕</button>
    <img id="lightboxImg" src="" alt="">
</div>

<!-- ===== MODAL DE AGENDAMENTO ===== -->
<div class="bk-overlay" id="bkOverlay">
    <div class="bk-modal">

        <!-- Header -->
        <div class="bk-modal-head">
            <div>
                <p style="font-size:.65rem;letter-spacing:.2em;text-transform:uppercase;color:rgba(197,160,89,.5);margin:0 0 4px">STUDIO MARCOSANTOFOTO</p>
                <h3 id="bkTitle">Escolha sua Data</h3>
                <p id="bkSubtitle" style="color:rgba(255,255,255,.4);font-size:.8rem;margin:0;"></p>
            </div>
            <button class="bk-close" onclick="closeBookingModal()" aria-label="Fechar">✕</button>
        </div>

        <input type="hidden" id="bkOrderId" value="">
        <input type="hidden" id="bkSlotId"   value="">

        <div class="bk-body">

            <!-- Step 1: Calendário -->
            <div id="bkStepCalendar">
                <div class="bk-cal-nav">
                    <button onclick="bkChangeMonth(-1)">‹</button>
                    <span class="bk-month-label" id="bkMonthLabel"></span>
                    <button onclick="bkChangeMonth(1)">›</button>
                </div>
                <div class="bk-weekdays">
                    <span>Dom</span><span>Seg</span><span>Ter</span>
                    <span>Qua</span><span>Qui</span><span>Sex</span><span>Sáb</span>
                </div>
                <div class="bk-grid" id="bkCalGrid"></div>
                <div class="bk-legend">
                    <span style="margin-right:14px"><span class="bk-dot a"></span> Disponível</span>
                    <span><span class="bk-dot b"></span> Indisponível</span>
                </div>
            </div>

            <!-- Step 2: Horários -->
            <div id="bkStepSlots" style="display:none;">
                <button class="bk-back-link" onclick="bkShowStep('calendar')">← Voltar ao calendário</button>
                <h4 id="bkSlotDateLabel" style="color:#fff;font-family:'EB Garamond',serif;font-size:1.1rem;font-style:italic;margin:0 0 16px;"></h4>
                <div class="bk-slots-list" id="bkSlotsList"></div>
            </div>

            <!-- Step 3: Formulário -->
            <div id="bkStepForm" style="display:none;">
                <button class="bk-back-link" onclick="bkShowStep('slots')">← Alterar horário</button>
                <p id="bkFormSlotLabel" style="color:rgba(197,160,89,.8);font-size:.85rem;margin:0 0 16px;"></p>
                <form id="bkForm" onsubmit="bkSubmitForm(event)">
                    <div class="bk-field">
                        <label>Seu nome completo</label>
                        <input type="text" id="bkName" required placeholder="Nome Sobrenome">
                    </div>
                    <div class="bk-field">
                        <label>E-mail</label>
                        <input type="email" id="bkEmail" required placeholder="voce@email.com">
                    </div>
                    <div class="bk-field">
                        <label>Telefone / WhatsApp</label>
                        <input type="tel" id="bkPhone" placeholder="(11) 99999-9999">
                    </div>
                    <div class="bk-field">
                        <label>Observações (opcional)</label>
                        <textarea id="bkNotes" rows="3" placeholder="Alguma informação especial sobre o ensaio?"></textarea>
                    </div>
                    <div class="bk-error" id="bkFormError" style="display:none;"></div>
                    <div class="bk-form-actions">
                        <button type="submit" class="bk-btn-primary" id="bkSubmitBtn">Confirmar Agendamento</button>
                    </div>
                </form>
            </div>

            <!-- Step 4: Sucesso -->
            <div id="bkStepSuccess" style="display:none;">
                <div class="bk-success">
                    <div class="bk-success-icon">✨</div>
                    <h3 class="bk-success-title">Agendamento Confirmado!</h3>
                    <p id="bkSuccessMsg" style="color:rgba(255,255,255,.7);margin:0 0 8px;"></p>
                    <p style="color:rgba(255,255,255,.4);font-size:.82rem;margin:0 0 24px;">Você receberá um e-mail de confirmação em instantes.</p>
                    <button class="bk-btn-secondary" onclick="closeBookingModal()" style="width:100%;">Fechar</button>
                </div>
            </div>

        </div><!-- /bk-body -->
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
const searchUrl = '<?= site_url('client/buscar') ?>';

function searchFor(q) {
    document.getElementById('searchInput').value = q;
    doSearch();
}

document.getElementById('searchInput')?.addEventListener('keydown', e => {
    if (e.key === 'Enter') doSearch();
});

async function doSearch() {
    const q = document.getElementById('searchInput').value.trim();
    if (!q) return;

    document.getElementById('suggestions').style.display = 'none';
    document.getElementById('searchStatus').textContent  = 'Buscando...';
    document.getElementById('photoGrid').innerHTML        = '';

    const res  = await fetch(`${searchUrl}?q=${encodeURIComponent(q)}`);
    const data = await res.json();

    const status = document.getElementById('searchStatus');
    const grid   = document.getElementById('photoGrid');

    if (data.total === 0) {
        status.textContent = `Nenhuma foto encontrada para "${q}"`;
        return;
    }

    status.innerHTML = `<strong style="color:#fff">${data.total}</strong> foto${data.total !== 1 ? 's' : ''} encontrada${data.total !== 1 ? 's' : ''} para <strong style="color:#C5A059">"${q}"</strong>`;

    data.results.forEach(photo => {
        const qLower = q.toLowerCase();
        const tags   = (photo.ai_tags || '').split(',').slice(0, 6)
            .map(t => t.trim()).filter(Boolean)
            .map(t => `<span class="tag ${t.toLowerCase().includes(qLower) ? 'hl' : ''}">${t}</span>`)
            .join(' ');

        grid.insertAdjacentHTML('beforeend', `
            <div class="photo-card" onclick="openLightbox('${photo.presigned_url}')">
                <img src="${photo.presigned_url}" alt="${photo.original_filename}" loading="lazy">
                <div class="photo-card-body">
                    <span class="project-pill">📁 ${photo.project_name}</span>
                    <div style="margin-top:6px;display:flex;flex-wrap:wrap;gap:3px;">${tags}</div>
                </div>
            </div>
        `);
    });
}

function openLightbox(url) {
    document.getElementById('lightboxImg').src = url;
    document.getElementById('lightbox').classList.add('active');
}
function closeLightbox() {
    document.getElementById('lightbox').classList.remove('active');
}
document.addEventListener('keydown', e => { if (e.key === 'Escape') closeLightbox(); });

// ── ViaCEP auto-fill ──
const cepInput = document.getElementById('cep');
if (cepInput) {
    cepInput.addEventListener('input', () => {
        let v = cepInput.value.replace(/\D/g, '');
        if (v.length > 5) v = v.slice(0,5) + '-' + v.slice(5,8);
        cepInput.value = v;
    });
    cepInput.addEventListener('blur', async () => {
        const cep = cepInput.value.replace(/\D/g, '');
        if (cep.length !== 8) return;
        const spinner = document.getElementById('cepSpinner');
        if (spinner) spinner.style.display = 'inline';
        try {
            const res  = await fetch(`https://viacep.com.br/ws/${cep}/json/`);
            const data = await res.json();
            if (!data.erro) {
                document.getElementById('logradouro').value = data.logradouro || '';
                document.getElementById('cidade').value     = data.localidade  || '';
                const est = document.getElementById('estado');
                if (est) est.value = data.uf || '';
                const num = document.getElementById('numero');
                if (num) num.focus();
            }
        } catch(e) {}
        if (spinner) spinner.style.display = 'none';
    });
}

// ── Máscara CPF ──
const cpfEl = document.getElementById('cpf');
if (cpfEl) {
    cpfEl.addEventListener('input', () => {
        let v = cpfEl.value.replace(/\D/g,'');
        if (v.length > 9)      v = v.slice(0,3)+'.'+v.slice(3,6)+'.'+v.slice(6,9)+'-'+v.slice(9,11);
        else if (v.length > 6) v = v.slice(0,3)+'.'+v.slice(3,6)+'.'+v.slice(6);
        else if (v.length > 3) v = v.slice(0,3)+'.'+v.slice(3);
        cpfEl.value = v;
    });
}

// ===== MODAL DE AGENDAMENTO =====

// Formata "2026-07-15 16:00:00" → "15 de Julho de 2026 às 16h00"
function formatScheduledAt(raw) {
    if (!raw) return '';
    const parts = raw.trim().split(' ');
    const [y, m, d] = (parts[0] || '').split('-');
    const [hh, mm] = (parts[1] || '00:00').split(':');
    const months = ['Janeiro','Fevereiro','Março','Abril','Maio','Junho',
                    'Julho','Agosto','Setembro','Outubro','Novembro','Dezembro'];
    const mName = months[parseInt(m, 10) - 1] || '';
    return `${parseInt(d,10)} de ${mName} de ${y} às ${hh}h${mm}`;
}

const BK = {
    orderId: 0, year: new Date().getFullYear(), month: new Date().getMonth() + 1,
    availability: {}, selectedDate: null, selectedSlot: null,
    prefill: { name: '', email: '', phone: '' }, loading: false,
};

function openBookingModal(orderId, pkgName, name, email, phone) {
    BK.orderId = orderId;
    BK.prefill = { name, email, phone };
    document.getElementById('bkTitle').textContent = 'Escolha sua Data';
    document.getElementById('bkSubtitle').textContent = pkgName;
    document.getElementById('bkOrderId').value = orderId;
    document.getElementById('bkOverlay').classList.add('open');
    document.body.style.overflow = 'hidden';
    bkShowStep('calendar');
    bkLoadMonth();
}

function closeBookingModal() {
    document.getElementById('bkOverlay').classList.remove('open');
    document.body.style.overflow = '';
}

function bkShowStep(step) {
    ['calendar','slots','form','success'].forEach(s =>
        document.getElementById('bkStep' + s.charAt(0).toUpperCase() + s.slice(1)).style.display = 'none'
    );
    document.getElementById('bkStep' + step.charAt(0).toUpperCase() + step.slice(1)).style.display = 'block';
}

function bkChangeMonth(delta) {
    BK.month += delta;
    if (BK.month > 12) { BK.month = 1;  BK.year++; }
    if (BK.month < 1)  { BK.month = 12; BK.year--; }
    bkLoadMonth();
}

async function bkLoadMonth() {
    const grid  = document.getElementById('bkCalGrid');
    const label = document.getElementById('bkMonthLabel');
    const today = new Date(); today.setHours(0,0,0,0);
    const months = ['Janeiro','Fevereiro','Mar\u00e7o','Abril','Maio','Junho',
                    'Julho','Agosto','Setembro','Outubro','Novembro','Dezembro'];
    label.textContent = months[BK.month - 1] + ' ' + BK.year;
    grid.innerHTML = '<div class="bk-loading"><div class="bk-spinner"></div></div>';

    try {
        const res  = await fetch(`/agenda-api/availability?year=${BK.year}&month=${BK.month}`);
        const json = await res.json();
        // A API retorna { success: true, data: [...slots] }
        const slots = json.data || json.slots || json || [];
        BK.availability = {};
        slots.forEach(s => {
            const d = (s.date || '').substring(0,10);
            if (!BK.availability[d]) BK.availability[d] = [];
            BK.availability[d].push(s);
        });
    } catch(e) { BK.availability = {}; }

    grid.innerHTML = '';
    const firstDay = new Date(BK.year, BK.month - 1, 1).getDay();
    const daysInMonth = new Date(BK.year, BK.month, 0).getDate();
    for (let i = 0; i < firstDay; i++) {
        const el = document.createElement('div');
        el.className = 'bk-day empty'; grid.appendChild(el);
    }
    for (let d = 1; d <= daysInMonth; d++) {
        const dateStr = `${BK.year}-${String(BK.month).padStart(2,'0')}-${String(d).padStart(2,'0')}`;
        const dayDate = new Date(BK.year, BK.month - 1, d);
        const isPast  = dayDate < today;
        const slots   = BK.availability[dateStr] || [];
        const hasAvail = slots.some(s => s.status === 'available');

        const el = document.createElement('div');
        el.textContent = d;
        if (isPast) { el.className = 'bk-day past'; }
        else if (hasAvail) {
            el.className = 'bk-day avail';
            el.onclick = () => bkSelectDate(dateStr, slots);
        } else { el.className = 'bk-day booked'; }
        grid.appendChild(el);
    }
}

function bkSelectDate(dateStr, slots) {
    BK.selectedDate = dateStr;
    const [y, m, d] = dateStr.split('-');
    const months = ['Jan','Fev','Mar','Abr','Mai','Jun','Jul','Ago','Set','Out','Nov','Dez'];
    document.getElementById('bkSlotDateLabel').textContent =
        `${d} de ${months[parseInt(m)-1]} de ${y}`;
    const list = document.getElementById('bkSlotsList');
    list.innerHTML = '';
    const avail = slots.filter(s => s.status === 'available');
    if (!avail.length) { list.innerHTML = '<p style="color:rgba(255,255,255,.4);font-size:.85rem">Sem hor\u00e1rios dispon\u00edveis neste dia.</p>'; }
    avail.forEach(slot => {
        const div = document.createElement('div');
        div.className = 'bk-slot';
        // API retorna start_time e session_type_name
        const timeStr = (slot.start_time || slot.time || '').substring(0,5);
        const typeStr = slot.session_type_name || slot.type || 'Ensaio';
        div.innerHTML = `<span class="bk-slot-time">${timeStr}</span><span class="bk-slot-type">${typeStr}</span>`;
        div.onclick = () => bkSelectSlot(slot);
        list.appendChild(div);
    });
    bkShowStep('slots');
}

function bkSelectSlot(slot) {
    BK.selectedSlot = slot;
    document.getElementById('bkSlotId').value = slot.id;
    const timeStr = (slot.start_time || slot.time || '').substring(0,5);
    const typeStr = slot.session_type_name || slot.type || 'Ensaio';
    document.getElementById('bkFormSlotLabel').textContent =
        `Hor\u00e1rio: ${timeStr} \u2014 ${typeStr}`;
    document.getElementById('bkName').value  = BK.prefill.name;
    document.getElementById('bkEmail').value = BK.prefill.email;
    document.getElementById('bkPhone').value = BK.prefill.phone;
    document.getElementById('bkFormError').style.display = 'none';
    bkShowStep('form');
}

async function bkSubmitForm(e) {
    e.preventDefault();
    const btn  = document.getElementById('bkSubmitBtn');
    const errEl = document.getElementById('bkFormError');
    btn.disabled = true;
    btn.textContent = 'Aguarde...';
    errEl.style.display = 'none';
    const body = {
        slot_id:  document.getElementById('bkSlotId').value,
        order_id: parseInt(document.getElementById('bkOrderId').value) || 0,
        name:     document.getElementById('bkName').value,
        email:    document.getElementById('bkEmail').value,
        phone:    document.getElementById('bkPhone').value,
        notes:    document.getElementById('bkNotes').value,
    };
    try {
        const res  = await fetch('/agenda-api/book', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
            body: JSON.stringify(body),
        });
        const data = await res.json();
        // API retorna { success: true, data: { booking_id, scheduled_at, date, start_time } }
        if (data.success && data.data && data.data.booking_id) {
            const d = data.data;
            const label = d.scheduled_at || d.date || '';
            const labelFmt = label ? formatScheduledAt(label) : '';
            document.getElementById('bkSuccessMsg').textContent =
                labelFmt ? `Seu ensaio est\u00e1 agendado para ${labelFmt}.` : 'Agendamento confirmado!';
            bkShowStep('success');
            // Atualiza o card na p\u00e1gina sem reload
            const ordId = body.order_id;
            const wrap  = document.getElementById('order-date-wrap-' + ordId);
            if (wrap) {
                document.getElementById('order-date-' + ordId).textContent = labelFmt || 'Data confirmada';
            } else if (labelFmt) {
                const card = document.getElementById('order-card-' + ordId);
                if (card) {
                    const pricing = card.querySelector('.card-pricing');
                    if (pricing) {
                        pricing.insertAdjacentHTML('afterend',
                            `<div class="card-date" id="order-date-wrap-${ordId}">`+
                            `<span style="font-size:1.4rem">&#128197;</span>`+
                            `<div><p class="date-label">Data do Ensaio</p>`+
                            `<p class="date-value" id="order-date-${ordId}">${labelFmt}</p></div></div>`);
                    }
                }
            }
        } else {
            errEl.textContent = data.message || 'Erro ao agendar. Tente novamente.';
            errEl.style.display = 'block';
            btn.disabled = false;
            btn.textContent = 'Confirmar Agendamento';
        }
    } catch(ex) {
        errEl.textContent = 'Erro de conex\u00e3o. Tente novamente.';
        errEl.style.display = 'block';
        btn.disabled = false;
        btn.textContent = 'Confirmar Agendamento';
    }
}

// Fecha modal ao clicar fora
document.getElementById('bkOverlay').addEventListener('click', function(e) {
    if (e.target === this) closeBookingModal();
});
document.addEventListener('keydown', e => { if (e.key === 'Escape') closeBookingModal(); });
</script>
<?= $this->endSection() ?>
