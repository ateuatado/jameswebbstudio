<?php $this->extend('admin/layout') ?>
<?php $this->section('styles') ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<?php $this->endSection() ?>

<?php $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="mb-1">📊 Dashboard de Rastreamento</h2>
        <p class="text-muted small mb-0">Métricas de tráfego, redes sociais e geolocalização dos visitantes.</p>
    </div>
    <div class="d-flex gap-2">
        <a href="<?= site_url('admin/tracking') ?>" class="btn btn-outline-secondary btn-sm">🔗 Gerenciar Links</a>
        <a href="<?= site_url('admin/tracking/create') ?>" class="btn btn-success btn-sm">+ Novo Link</a>
    </div>
</div>

<!-- Filtros -->
<div class="card bg-dark border-secondary mb-4">
    <div class="card-body py-3">
        <form method="get" class="row g-2 align-items-end">
            <div class="col-md-2">
                <label class="form-label small text-muted mb-1">De</label>
                <input type="date" name="date_from" class="form-control form-control-sm" value="<?= esc($from) ?>">
            </div>
            <div class="col-md-2">
                <label class="form-label small text-muted mb-1">Até</label>
                <input type="date" name="date_to" class="form-control form-control-sm" value="<?= esc($to) ?>">
            </div>
            <div class="col-md-3">
                <label class="form-label small text-muted mb-1">Link específico</label>
                <select name="link_id" class="form-select form-select-sm">
                    <option value="">— Todos os Links —</option>
                    <?php foreach ($links as $l): ?>
                        <option value="<?= $l->id ?>" <?= $selectedLink == $l->id ? 'selected' : '' ?>>
                            <?= esc($l->slug) ?> <?= $l->utm_source ? '(' . esc($l->utm_source) . ')' : '' ?>
                        </option>
                    <?php endforeach ?>
                </select>
            </div>
            <div class="col-md-2">
                <div class="form-check mt-3">
                    <input class="form-check-input" type="checkbox" name="exclude_bots" value="1" id="excludeBots" <?= $excludeBots ? 'checked' : '' ?>>
                    <label class="form-check-label small text-light" for="excludeBots" title="Oculta pré-visualizações de robôs como Facebook/Instagram bot">
                        Ocultar Robôs
                    </label>
                </div>
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-primary btn-sm flex-grow-1">Filtrar</button>
                <a href="<?= site_url('admin/tracking/dashboard') ?>" class="btn btn-outline-secondary btn-sm">Limpar</a>
            </div>
        </form>
    </div>
</div>

<!-- Cards de Total -->
<div class="row g-3 mb-4">
    <div class="col-6 col-md">
        <div class="card bg-dark border-info text-center py-3">
            <div class="fs-2 fw-bold text-info"><?= number_format($total) ?></div>
            <div class="text-muted small">Total de Cliques</div>
        </div>
    </div>
    <div class="col-6 col-md">
        <div class="card bg-dark border-success text-center py-3">
            <div class="fs-2 fw-bold text-success"><?= number_format($unique) ?></div>
            <div class="text-muted small">Visitantes Únicos</div>
        </div>
    </div>
    <div class="col-6 col-md">
        <div class="card bg-dark border-warning text-center py-3">
            <div class="fs-2 fw-bold text-warning"><?= count($bySource) ?></div>
            <div class="text-muted small">Fontes de Tráfego</div>
        </div>
    </div>
    <div class="col-6 col-md">
        <div class="card bg-dark border-danger text-center py-3">
            <div class="fs-2 fw-bold text-danger"><?= count($byCity) ?></div>
            <div class="text-muted small">Cidades</div>
        </div>
    </div>
    <div class="col-6 col-md">
        <div class="card bg-dark border-secondary text-center py-3">
            <div class="fs-2 fw-bold text-secondary"><?= number_format($botCount) ?></div>
            <div class="text-muted small">Robôs / Previews</div>
        </div>
    </div>
</div>

<!-- Gráfico por Dia -->
<div class="card bg-dark border-secondary mb-4">
    <div class="card-body">
        <h5 class="card-title text-light mb-3">📈 Visitas por Dia</h5>
        <?php if (empty($byDay)): ?>
            <p class="text-muted text-center py-4 mb-0">Nenhuma visita registrada no período selecionado.</p>
        <?php else: ?>
            <canvas id="chartByDay" height="70"></canvas>
        <?php endif ?>
    </div>
</div>

<div class="row g-3 mb-4">
    <!-- Por Source -->
    <div class="col-md-4">
        <div class="card bg-dark border-secondary h-100">
            <div class="card-header fw-semibold text-warning">📱 Origem (Rede Social)</div>
            <div class="table-responsive">
                <table class="table table-dark table-sm table-hover mb-0">
                    <thead><tr><th>Rede / Fonte</th><th class="text-end">Cliques</th><th class="text-end">%</th></tr></thead>
                    <tbody>
                    <?php foreach ($bySource as $row): ?>
                        <tr>
                            <td>
                                <span class="badge bg-secondary me-1"><?= esc($row->utm_source ?? 'direto') ?></span>
                            </td>
                            <td class="text-end fw-bold"><?= $row->total ?></td>
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
            <div class="card-header fw-semibold text-info">🎯 Por Campanha</div>
            <div class="table-responsive">
                <table class="table table-dark table-sm table-hover mb-0">
                    <thead><tr><th>Campanha</th><th class="text-end">Cliques</th><th class="text-end">%</th></tr></thead>
                    <tbody>
                    <?php foreach ($byCampaign as $row): ?>
                        <tr>
                            <td><?= esc($row->utm_campaign ?? '(sem campanha)') ?></td>
                            <td class="text-end fw-bold"><?= $row->total ?></td>
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
            <div class="card-header fw-semibold text-success">💻 Por Dispositivo</div>
            <div class="table-responsive">
                <table class="table table-dark table-sm table-hover mb-0">
                    <thead><tr><th>Dispositivo</th><th class="text-end">Cliques</th><th class="text-end">%</th></tr></thead>
                    <tbody>
                    <?php foreach ($byDevice as $row): ?>
                        <tr>
                            <td>
                                <?php if ($row->device_type === 'mobile'): ?>
                                    📱 Celular (Mobile)
                                <?php elseif ($row->device_type === 'desktop'): ?>
                                    🖥️ Computador (Desktop)
                                <?php elseif ($row->device_type === 'bot'): ?>
                                    🤖 Robô / Crawler
                                <?php else: ?>
                                    <?= ucfirst(esc($row->device_type ?? 'Outro')) ?>
                                <?php endif ?>
                            </td>
                            <td class="text-end fw-bold"><?= $row->total ?></td>
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
</div>

<div class="row g-3 mb-4">
    <!-- Top Cidades -->
    <div class="col-md-7">
        <div class="card bg-dark border-secondary h-100">
            <div class="card-header fw-semibold text-danger">🌍 Top 10 Cidades dos Visitantes</div>
            <div class="table-responsive">
                <table class="table table-dark table-sm table-hover mb-0">
                    <thead><tr><th>#</th><th>Cidade</th><th>País</th><th class="text-end">Cliques</th></tr></thead>
                    <tbody>
                    <?php foreach ($byCity as $i => $row): ?>
                        <tr>
                            <td class="text-muted"><?= $i + 1 ?></td>
                            <td class="fw-semibold text-light"><?= esc($row->city ?? '—') ?></td>
                            <td class="text-muted"><?= esc($row->country ?? '—') ?></td>
                            <td class="text-end fw-bold text-info"><?= $row->total ?></td>
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

    <!-- Navegador / App -->
    <div class="col-md-5">
        <div class="card bg-dark border-secondary h-100">
            <div class="card-header fw-semibold text-light">🌐 Navegador / Aplicativo</div>
            <div class="table-responsive">
                <table class="table table-dark table-sm table-hover mb-0">
                    <thead><tr><th>Navegador / App</th><th class="text-end">Cliques</th></tr></thead>
                    <tbody>
                    <?php foreach ($byBrowser as $row): ?>
                        <tr>
                            <td><?= esc($row->browser ?? 'Desconhecido') ?></td>
                            <td class="text-end fw-bold"><?= $row->total ?></td>
                        </tr>
                    <?php endforeach ?>
                    <?php if (empty($byBrowser)): ?>
                        <tr><td colspan="2" class="text-center text-muted py-3">Sem dados</td></tr>
                    <?php endif ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Últimos Cliques -->
<?php if (!empty($recentHits)): ?>
<div class="card bg-dark border-secondary">
    <div class="card-header fw-semibold text-light">⏱️ Últimos 15 Cliques Registrados</div>
    <div class="table-responsive">
        <table class="table table-dark table-sm table-hover mb-0" style="font-size: 0.85rem;">
            <thead>
                <tr>
                    <th>Data / Hora</th>
                    <th>Link (Slug)</th>
                    <th>Origem (Source)</th>
                    <th>Cidade / País</th>
                    <th>Dispositivo</th>
                    <th>Navegador / App</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($recentHits as $hit): ?>
                <tr>
                    <td class="text-muted"><?= date('d/m/Y H:i:s', strtotime($hit->created_at)) ?></td>
                    <td><code>/r/<?= esc($hit->slug ?? '—') ?></code></td>
                    <td><span class="badge bg-secondary"><?= esc($hit->utm_source ?? 'direto') ?></span></td>
                    <td>
                        <?= esc($hit->city ? $hit->city . ($hit->country ? ', ' . $hit->country : '') : '—') ?>
                    </td>
                    <td>
                        <?php if ($hit->device_type === 'mobile'): ?>
                            <span class="text-success">📱 Mobile</span>
                        <?php elseif ($hit->device_type === 'bot'): ?>
                            <span class="text-secondary">🤖 Bot</span>
                        <?php else: ?>
                            <span class="text-info">🖥️ Desktop</span>
                        <?php endif ?>
                    </td>
                    <td><?= esc($hit->browser ?? '—') ?></td>
                </tr>
                <?php endforeach ?>
            </tbody>
        </table>
    </div>
</div>
<?php endif ?>

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
            backgroundColor: 'rgba(13, 202, 240, 0.15)',
            tension: 0.3,
            fill: true,
            pointRadius: 4,
            pointBackgroundColor: '#0dcaf0'
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { display: false },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        return context.raw + ' visitas';
                    }
                }
            }
        },
        scales: {
            x: { ticks: { color: '#adb5bd' }, grid: { color: 'rgba(255,255,255,0.05)' } },
            y: { ticks: { color: '#adb5bd' }, grid: { color: 'rgba(255,255,255,0.05)' }, beginAtZero: true }
        }
    }
});
</script>
<?php endif ?>
<?php $this->endSection() ?>
