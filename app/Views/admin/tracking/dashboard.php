<?php $this->extend('admin/layout') ?>
<?php $this->section('styles') ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<?php $this->endSection() ?>

<?php $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>📊 Dashboard de Rastreamento</h2>
    <a href="<?= site_url('admin/tracking') ?>" class="btn btn-outline-secondary btn-sm">🔗 Gerenciar Links</a>
</div>

<!-- Filtros -->
<form method="get" class="row g-2 mb-4 align-items-end">
    <div class="col-md-2">
        <label class="form-label small">De</label>
        <input type="date" name="date_from" class="form-control form-control-sm" value="<?= esc($from) ?>">
    </div>
    <div class="col-md-2">
        <label class="form-label small">Até</label>
        <input type="date" name="date_to" class="form-control form-control-sm" value="<?= esc($to) ?>">
    </div>
    <div class="col-md-3">
        <label class="form-label small">Link específico</label>
        <select name="link_id" class="form-select form-select-sm">
            <option value="">— Todos —</option>
            <?php foreach ($links as $l): ?>
                <option value="<?= $l->id ?>" <?= $selectedLink == $l->id ? 'selected' : '' ?>>
                    <?= esc($l->slug) ?> <?= $l->utm_source ? '(' . esc($l->utm_source) . ')' : '' ?>
                </option>
            <?php endforeach ?>
        </select>
    </div>
    <div class="col-md-2">
        <button type="submit" class="btn btn-primary btn-sm w-100">Filtrar</button>
    </div>
    <div class="col-md-2">
        <a href="<?= site_url('admin/tracking/dashboard') ?>" class="btn btn-outline-secondary btn-sm w-100">Limpar</a>
    </div>
</form>

<!-- Cards de Total -->
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="card bg-dark border-secondary text-center py-3">
            <div class="fs-1 fw-bold text-info"><?= number_format($total) ?></div>
            <div class="text-muted small">Total de Visitas</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card bg-dark border-secondary text-center py-3">
            <div class="fs-1 fw-bold text-warning"><?= count($bySource) ?></div>
            <div class="text-muted small">Fontes Únicas</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card bg-dark border-secondary text-center py-3">
            <div class="fs-1 fw-bold text-success"><?= count($byCampaign) ?></div>
            <div class="text-muted small">Campanhas</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card bg-dark border-secondary text-center py-3">
            <div class="fs-1 fw-bold text-danger"><?= count($byCity) ?></div>
            <div class="text-muted small">Cidades</div>
        </div>
    </div>
</div>

<!-- Gráfico por Dia -->
<div class="card bg-dark border-secondary mb-4">
    <div class="card-body">
        <h5 class="card-title">Visitas por Dia</h5>
        <?php if (empty($byDay)): ?>
            <p class="text-muted text-center py-3">Nenhuma visita no período selecionado.</p>
        <?php else: ?>
            <canvas id="chartByDay" height="80"></canvas>
        <?php endif ?>
    </div>
</div>

<div class="row g-3">
    <!-- Por Source -->
    <div class="col-md-4">
        <div class="card bg-dark border-secondary h-100">
            <div class="card-header">Por Rede Social</div>
            <div class="table-responsive">
                <table class="table table-dark table-sm mb-0">
                    <thead><tr><th>Source</th><th class="text-end">Visitas</th><th class="text-end">%</th></tr></thead>
                    <tbody>
                    <?php foreach ($bySource as $row): ?>
                        <tr>
                            <td><?= esc($row->utm_source ?? '(direto)') ?></td>
                            <td class="text-end"><?= $row->total ?></td>
                            <td class="text-end text-muted"><?= $total ? round($row->total / $total * 100, 1) : 0 ?>%</td>
                        </tr>
                    <?php endforeach ?>
                    <?php if (empty($bySource)): ?>
                        <tr><td colspan="3" class="text-center text-muted py-3">Sem dados</td></tr>
                    <?php endif ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Por Campanha -->
    <div class="col-md-4">
        <div class="card bg-dark border-secondary h-100">
            <div class="card-header">Por Campanha</div>
            <div class="table-responsive">
                <table class="table table-dark table-sm mb-0">
                    <thead><tr><th>Campanha</th><th class="text-end">Visitas</th><th class="text-end">%</th></tr></thead>
                    <tbody>
                    <?php foreach ($byCampaign as $row): ?>
                        <tr>
                            <td><?= esc($row->utm_campaign ?? '(sem campanha)') ?></td>
                            <td class="text-end"><?= $row->total ?></td>
                            <td class="text-end text-muted"><?= $total ? round($row->total / $total * 100, 1) : 0 ?>%</td>
                        </tr>
                    <?php endforeach ?>
                    <?php if (empty($byCampaign)): ?>
                        <tr><td colspan="3" class="text-center text-muted py-3">Sem dados</td></tr>
                    <?php endif ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Por Dispositivo -->
    <div class="col-md-4">
        <div class="card bg-dark border-secondary h-100">
            <div class="card-header">Por Dispositivo</div>
            <div class="table-responsive">
                <table class="table table-dark table-sm mb-0">
                    <thead><tr><th>Dispositivo</th><th class="text-end">Visitas</th><th class="text-end">%</th></tr></thead>
                    <tbody>
                    <?php foreach ($byDevice as $row): ?>
                        <tr>
                            <td><?= ucfirst(esc($row->device_type ?? 'desconhecido')) ?></td>
                            <td class="text-end"><?= $row->total ?></td>
                            <td class="text-end text-muted"><?= $total ? round($row->total / $total * 100, 1) : 0 ?>%</td>
                        </tr>
                    <?php endforeach ?>
                    <?php if (empty($byDevice)): ?>
                        <tr><td colspan="3" class="text-center text-muted py-3">Sem dados</td></tr>
                    <?php endif ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Top Cidades -->
    <div class="col-12">
        <div class="card bg-dark border-secondary">
            <div class="card-header">Top 10 Cidades</div>
            <div class="table-responsive">
                <table class="table table-dark table-sm mb-0">
                    <thead><tr><th>#</th><th>Cidade</th><th>País</th><th class="text-end">Visitas</th></tr></thead>
                    <tbody>
                    <?php foreach ($byCity as $i => $row): ?>
                        <tr>
                            <td class="text-muted"><?= $i + 1 ?></td>
                            <td><?= esc($row->city ?? '—') ?></td>
                            <td><?= esc($row->country ?? '—') ?></td>
                            <td class="text-end"><?= $row->total ?></td>
                        </tr>
                    <?php endforeach ?>
                    <?php if (empty($byCity)): ?>
                        <tr><td colspan="4" class="text-center text-muted py-3">Sem dados de geolocalização</td></tr>
                    <?php endif ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php $this->endSection() ?>

<?php $this->section('scripts') ?>
<?php if (!empty($byDay)): ?>
<script>
const days   = <?= json_encode(array_column($byDay, 'day')) ?>;
const totals = <?= json_encode(array_map('intval', array_column($byDay, 'total'))) ?>;

new Chart(document.getElementById('chartByDay'), {
    type: 'line',
    data: {
        labels: days,
        datasets: [{
            label: 'Visitas',
            data: totals,
            borderColor: '#0dcaf0',
            backgroundColor: 'rgba(13, 202, 240, 0.1)',
            tension: 0.3,
            fill: true,
            pointRadius: 4,
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
            x: { ticks: { color: '#adb5bd' }, grid: { color: '#333' } },
            y: { ticks: { color: '#adb5bd' }, grid: { color: '#333' }, beginAtZero: true }
        }
    }
});
</script>
<?php endif ?>
<?php $this->endSection() ?>
