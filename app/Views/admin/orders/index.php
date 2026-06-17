<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>

<!-- Header + Summary Cards -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="text-warning fw-bold text-uppercase">Pedidos</h2>
</div>

<!-- Cards de resumo -->
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card bg-success bg-opacity-10 border-success text-center p-3">
            <div class="fs-2 fw-bold text-success"><?= $summary['approved'] ?></div>
            <div class="small text-white-50">Aprovados</div>
            <div class="text-success fw-bold">R$ <?= number_format($summary['revenue'], 2, ',', '.') ?></div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-warning bg-opacity-10 border-warning text-center p-3">
            <div class="fs-2 fw-bold text-warning"><?= $summary['pending'] ?></div>
            <div class="small text-white-50">Pendentes</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-danger bg-opacity-10 border-danger text-center p-3">
            <div class="fs-2 fw-bold text-danger"><?= $summary['cancelled'] ?></div>
            <div class="small text-white-50">Cancelados</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-secondary bg-opacity-10 border-secondary text-center p-3">
            <div class="fs-2 fw-bold text-secondary"><?= $summary['approved'] + $summary['pending'] + $summary['cancelled'] ?></div>
            <div class="small text-white-50">Total</div>
        </div>
    </div>
</div>

<!-- Filtros -->
<div class="mb-3 d-flex gap-2 flex-wrap">
    <a href="<?= site_url('admin/orders') ?>" class="btn btn-sm <?= $filter === '' ? 'btn-warning' : 'btn-outline-secondary' ?>">Todos</a>
    <a href="<?= site_url('admin/orders?status=approved') ?>" class="btn btn-sm <?= $filter === 'approved' ? 'btn-success' : 'btn-outline-success' ?>">Aprovados</a>
    <a href="<?= site_url('admin/orders?status=pending') ?>"  class="btn btn-sm <?= $filter === 'pending'  ? 'btn-warning' : 'btn-outline-warning' ?>">Pendentes</a>
    <a href="<?= site_url('admin/orders?status=cancelled') ?>" class="btn btn-sm <?= $filter === 'cancelled' ? 'btn-danger' : 'btn-outline-danger' ?>">Cancelados</a>
</div>

<!-- Tabela -->
<div class="card bg-dark text-white border-secondary">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-dark table-hover align-middle mb-0">
                <thead class="border-bottom border-secondary">
                    <tr>
                        <th class="ps-3">#</th>
                        <th>Data</th>
                        <th>Cliente</th>
                        <th>Contato</th>
                        <th>Valor</th>
                        <th>Status</th>
                        <th>Agenda</th>
                        <th>MP ID</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($orders)): ?>
                        <?php foreach ($orders as $o): ?>
                        <tr>
                            <td class="ps-3 text-muted small"><?= $o->id ?></td>
                            <td class="small text-muted"><?= date('d/m/Y H:i', strtotime($o->created_at)) ?></td>
                            <td class="fw-bold"><?= esc($o->buyer_name) ?></td>
                            <td>
                                <div class="small"><?= esc($o->buyer_email) ?></div>
                                <?php if ($o->buyer_phone): ?>
                                <div class="small text-info"><?= esc($o->buyer_phone) ?></div>
                                <?php endif; ?>
                            </td>
                            <td class="fw-bold text-success">R$ <?= number_format($o->amount, 2, ',', '.') ?></td>
                            <td>
                                <?php
                                $badges = [
                                    'approved'  => 'bg-success',
                                    'pending'   => 'bg-warning text-dark',
                                    'cancelled' => 'bg-danger',
                                    'refunded'  => 'bg-secondary',
                                ];
                                $labels = [
                                    'approved'  => 'Aprovado',
                                    'pending'   => 'Pendente',
                                    'cancelled' => 'Cancelado',
                                    'refunded'  => 'Reembolsado',
                                ];
                                $badge = $badges[$o->status] ?? 'bg-secondary';
                                $label = $labels[$o->status] ?? $o->status;
                                ?>
                                <span class="badge <?= $badge ?>"><?= $label ?></span>
                            </td>
                            <td>
                                <?php if (!empty($o->agenda_link)): ?>
                                    <a href="<?= esc($o->agenda_link) ?>" target="_blank"
                                       class="badge bg-info text-dark text-decoration-none">📅 Agendado</a>
                                <?php elseif ($o->status === 'approved'): ?>
                                    <span class="badge bg-warning text-dark">⏳ Pendente</span>
                                <?php else: ?>
                                    <span class="text-muted">—</span>
                                <?php endif; ?>
                            </td>
                            <td class="small text-muted font-monospace">
                                <?php if ($o->mp_payment_id): ?>
                                    <a href="https://www.mercadopago.com.br/activities/search?search_term=<?= $o->mp_payment_id ?>"
                                       target="_blank" class="text-info text-decoration-none">
                                        <?= substr($o->mp_payment_id, 0, 10) ?>...
                                    </a>
                                <?php else: ?>
                                    <span class="text-muted">—</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="<?= site_url('admin/orders/' . $o->id) ?>"
                                   class="btn btn-sm btn-outline-secondary">Ver</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="9" class="text-center text-muted py-5">
                                Nenhum pedido registrado ainda.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Paginação -->
<?php if ($pager && $pager->getPageCount() > 1): ?>
<div class="mt-3 d-flex justify-content-center">
    <?= $pager->links() ?>
</div>
<?php endif; ?>

<?= $this->endSection() ?>
