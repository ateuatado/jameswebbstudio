<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">🎟️ Cupons de Desconto</h1>
    <a href="<?= site_url('admin/coupons/create') ?>" class="btn btn-success">+ Novo Cupom</a>
</div>

<?php if (session()->has('message')): ?>
    <div class="alert alert-success"><?= session('message') ?></div>
<?php endif; ?>
<?php if (session()->has('error')): ?>
    <div class="alert alert-danger"><?= session('error') ?></div>
<?php endif; ?>

<div class="card bg-dark border-secondary">
    <div class="card-body p-0">
        <table class="table table-dark table-hover mb-0">
            <thead class="border-bottom border-secondary">
                <tr>
                    <th>Código</th>
                    <th>E-mail Vinculado</th>
                    <th class="text-center">Desconto</th>
                    <th class="text-center">Status</th>
                    <th>Criado em</th>
                    <th>Utilizado em</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($coupons)): ?>
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">Nenhum cupom cadastrado ainda.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($coupons as $c): ?>
                    <tr>
                        <td>
                            <code class="text-warning fs-6 fw-bold"><?= esc($c->code) ?></code>
                        </td>
                        <td><?= esc($c->email) ?></td>
                        <td class="text-center">
                            <?php if ($c->discount_percent == 100): ?>
                                <span class="badge bg-danger fs-6">100% — Cortesia Total</span>
                            <?php else: ?>
                                <span class="badge bg-success fs-6"><?= $c->discount_percent ?>%</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-center">
                            <?php if ($c->used): ?>
                                <span class="badge bg-secondary">Utilizado</span>
                            <?php else: ?>
                                <span class="badge bg-success">Disponível</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-muted small">
                            <?= $c->created_at ? date('d/m/Y H:i', strtotime($c->created_at)) : '—' ?>
                        </td>
                        <td class="text-muted small">
                            <?= $c->used_at ? date('d/m/Y H:i', strtotime($c->used_at)) : '—' ?>
                            <?php if ($c->order_id): ?>
                                <a href="<?= site_url('admin/orders/' . $c->order_id) ?>" class="ms-1 text-warning small">#<?= $c->order_id ?></a>
                            <?php endif; ?>
                        </td>
                        <td class="text-end">
                            <?php if (!$c->used): ?>
                            <form method="POST" action="<?= site_url('admin/coupons/' . $c->id . '/delete') ?>"
                                  onsubmit="return confirm('Remover o cupom <?= esc($c->code) ?>?')">
                                <?= csrf_field() ?>
                                <button class="btn btn-outline-danger btn-sm">Remover</button>
                            </form>
                            <?php else: ?>
                                <span class="text-muted small">—</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3 text-muted small">
    <strong><?= count($coupons) ?></strong> cupom(ns) total •
    <strong><?= count(array_filter($coupons, fn($c) => !$c->used)) ?></strong> disponível(is) •
    <strong><?= count(array_filter($coupons, fn($c) => $c->used)) ?></strong> utilizado(s)
</div>
<?= $this->endSection() ?>
