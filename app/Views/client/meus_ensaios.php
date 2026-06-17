<?= $this->extend('layout/main') ?>

<?= $this->section('styles') ?>
<style>
    .ensaios-page {
        margin-top: 100px;
        min-height: 70vh;
        padding-bottom: 60px;
    }
    .ensaios-page .page-title {
        font-family: 'EB Garamond', Georgia, serif;
        font-size: clamp(1.8rem, 4vw, 2.8rem);
        font-weight: 400;
        color: #C5A059;
        text-align: center;
        margin-bottom: 8px;
    }
    .ensaios-page .page-subtitle {
        font-family: 'Inter', sans-serif;
        font-size: .75rem;
        letter-spacing: .2em;
        text-transform: uppercase;
        color: rgba(255,255,255,.3);
        text-align: center;
        margin-bottom: 48px;
    }

    /* ── Seção ── */
    .section-header {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 24px;
        padding-bottom: 12px;
        border-bottom: 1px solid rgba(197,160,89,.15);
    }
    .section-header .icon {
        font-size: 1.5rem;
    }
    .section-header h3 {
        font-family: 'EB Garamond', Georgia, serif;
        font-size: 1.4rem;
        font-weight: 400;
        color: #fff;
        margin: 0;
    }
    .section-header .count {
        font-family: 'Inter', sans-serif;
        font-size: .65rem;
        letter-spacing: .15em;
        text-transform: uppercase;
        color: rgba(255,255,255,.35);
        margin-left: auto;
    }

    /* ── Card de Ensaio ── */
    .ensaio-card {
        background: rgba(255,255,255,.03);
        border: 1px solid rgba(255,255,255,.08);
        padding: 24px;
        margin-bottom: 16px;
        transition: all .3s ease;
        position: relative;
        overflow: hidden;
    }
    .ensaio-card::before {
        content: '';
        position: absolute;
        top: 0; left: 0;
        width: 4px; height: 100%;
        background: var(--accent-color, rgba(197,160,89,.4));
    }
    .ensaio-card:hover {
        background: rgba(255,255,255,.05);
        border-color: rgba(197,160,89,.2);
        transform: translateY(-2px);
    }
    .ensaio-card .card-top {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 16px;
        flex-wrap: wrap;
    }
    .ensaio-card .card-title {
        font-family: 'EB Garamond', Georgia, serif;
        font-size: 1.2rem;
        color: #fff;
        margin: 0 0 4px;
    }
    .ensaio-card .card-meta {
        font-family: 'Inter', sans-serif;
        font-size: .72rem;
        color: rgba(255,255,255,.4);
        margin: 0;
    }

    /* ── Badges de status ── */
    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-family: 'Inter', sans-serif;
        font-size: .65rem;
        letter-spacing: .12em;
        text-transform: uppercase;
        padding: 6px 14px;
        border-radius: 2px;
        white-space: nowrap;
    }
    .status-badge.approved  { background: rgba(46,125,50,.15); color: #66bb6a; --accent-color: #66bb6a; }
    .status-badge.pending   { background: rgba(255,183,77,.12); color: #ffb74d; --accent-color: #ffb74d; }
    .status-badge.selecting { background: rgba(100,181,246,.12); color: #64b5f6; --accent-color: #64b5f6; }
    .status-badge.open      { background: rgba(100,181,246,.12); color: #64b5f6; --accent-color: #64b5f6; }
    .status-badge.completed { background: rgba(255,255,255,.06); color: rgba(255,255,255,.5); --accent-color: rgba(255,255,255,.3); }

    .ensaio-card .card-actions {
        margin-top: 16px;
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
    }
    .btn-ensaio {
        font-family: 'Inter', sans-serif;
        font-size: .68rem;
        letter-spacing: .15em;
        text-transform: uppercase;
        padding: 10px 24px;
        text-decoration: none;
        border: 1px solid rgba(197,160,89,.4);
        color: #C5A059;
        background: transparent;
        transition: all .2s;
    }
    .btn-ensaio:hover {
        background: rgba(197,160,89,.1);
        color: #C5A059;
    }
    .btn-ensaio.primary {
        background: linear-gradient(135deg, #C5A059, #F5E27A);
        color: #000;
        border-color: transparent;
        font-weight: 600;
    }
    .btn-ensaio.primary:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 16px rgba(197,160,89,.25);
        color: #000;
    }

    /* ── Empty state ── */
    .empty-state {
        text-align: center;
        padding: 48px 24px;
        border: 1px dashed rgba(255,255,255,.1);
    }
    .empty-state .emoji {
        font-size: 2.5rem;
        margin-bottom: 16px;
    }
    .empty-state p {
        font-family: 'Inter', sans-serif;
        font-size: .85rem;
        color: rgba(255,255,255,.35);
        margin: 0;
    }

    /* ── Divider ── */
    .section-divider {
        border: none;
        border-top: 1px solid rgba(197,160,89,.1);
        margin: 48px 0;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container ensaios-page">

    <h1 class="page-title">Meus Ensaios</h1>
    <p class="page-subtitle">Gerencie suas compras, agendamentos e galerias</p>

    <!-- ═══════════════════════════════════════════════════════════════════════
         SEÇÃO 1: COMPRAS & AGENDAMENTOS
         ═══════════════════════════════════════════════════════════════════════ -->
    <div class="section-header">
        <span class="icon">📋</span>
        <h3>Minhas Compras</h3>
        <span class="count"><?= count($orders) ?> <?= count($orders) === 1 ? 'ensaio' : 'ensaios' ?></span>
    </div>

    <?php if (!empty($orders)): ?>
        <?php foreach ($orders as $order): ?>
            <?php
                $statusLabel = [
                    'approved'  => '✅ Pago',
                    'pending'   => '⏳ Aguardando pagamento',
                    'cancelled' => '❌ Cancelado',
                    'refunded'  => '↩️ Reembolsado',
                ][$order->status] ?? $order->status;

                $hasAgenda   = !empty($order->agenda_link);
                $packageName = $order->package ? $order->package->name : 'Ensaio Fotográfico';
                $amount      = 'R$ ' . number_format((float) $order->amount, 2, ',', '.');
                $date        = date('d/m/Y', strtotime($order->created_at));
            ?>
            <div class="ensaio-card" style="--accent-color: <?= $order->status === 'approved' ? '#66bb6a' : '#ffb74d' ?>">
                <div class="card-top">
                    <div>
                        <h4 class="card-title"><?= esc($packageName) ?></h4>
                        <p class="card-meta"><?= $amount ?> · Comprado em <?= $date ?></p>
                    </div>
                    <span class="status-badge <?= esc($order->status) ?>"><?= $statusLabel ?></span>
                </div>

                <div class="card-actions">
                    <?php if ($order->status === 'approved' && $hasAgenda): ?>
                        <a href="<?= esc($order->agenda_link) ?>" class="btn-ensaio primary" target="_blank">
                            📅 AGENDAR ENSAIO →
                        </a>
                    <?php elseif ($order->status === 'pending'): ?>
                        <span class="btn-ensaio" style="cursor:default; opacity:.5;">
                            AGUARDANDO CONFIRMAÇÃO DO BANCO
                        </span>
                    <?php else: ?>
                        <span class="btn-ensaio" style="cursor:default; opacity:.5;">
                            ENSAIO CONFIRMADO
                        </span>
                    <?php endif; ?>

                    <?php if ($order->status === 'approved'): ?>
                        <a href="<?= site_url('client/guia-pre-ensaio/' . $order->id) ?>" class="btn-ensaio" target="_blank">
                            📄 GUIA PRÉ-ENSAIO
                        </a>
                        <a href="<?= site_url('client/contrato/' . $order->id) ?>" class="btn-ensaio" target="_blank">
                            📋 MEU CONTRATO
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="empty-state">
            <div class="emoji">🛒</div>
            <p>Nenhuma compra registrada ainda.</p>
        </div>
    <?php endif; ?>

    <hr class="section-divider">

    <!-- ═══════════════════════════════════════════════════════════════════════
         SEÇÃO 2: GALERIAS DE FOTOS
         ═══════════════════════════════════════════════════════════════════════ -->
    <div class="section-header">
        <span class="icon">🖼️</span>
        <h3>Minhas Galerias</h3>
        <span class="count"><?= count($projects) ?> <?= count($projects) === 1 ? 'galeria' : 'galerias' ?></span>
    </div>

    <?php if (!empty($projects)): ?>
        <?php foreach ($projects as $proj): ?>
            <?php
                $statusLabel = [
                    'open'      => '🔵 Aberta',
                    'selecting' => '🔵 Selecionando',
                    'paid'      => '✅ Paga',
                    'completed' => '✔️ Concluída',
                ][$proj->status] ?? $proj->status;

                $packageName = $proj->package ? $proj->package->name : '';
            ?>
            <div class="ensaio-card" style="--accent-color: <?= in_array($proj->status, ['open','selecting']) ? '#64b5f6' : 'rgba(255,255,255,.3)' ?>">
                <div class="card-top">
                    <div>
                        <h4 class="card-title"><?= esc($proj->name ?? 'Projeto #' . $proj->id) ?></h4>
                        <?php if ($packageName): ?>
                            <p class="card-meta">Pacote: <?= esc($packageName) ?></p>
                        <?php endif; ?>
                    </div>
                    <span class="status-badge <?= esc($proj->status) ?>"><?= $statusLabel ?></span>
                </div>

                <div class="card-actions">
                    <a href="<?= site_url('client/galeria/' . $proj->id) ?>" class="btn-ensaio primary">
                        🖼️ ACESSAR GALERIA →
                    </a>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="empty-state">
            <div class="emoji">📷</div>
            <p>Nenhuma galeria disponível no momento.</p>
        </div>
    <?php endif; ?>

</div>
<?= $this->endSection() ?>
