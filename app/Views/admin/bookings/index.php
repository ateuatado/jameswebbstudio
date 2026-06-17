<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="text-success fw-bold text-uppercase">Agenda Geral / Agendamentos</h2>
</div>

<div class="card bg-dark text-white border-secondary">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-dark table-hover align-middle">
                <thead>
                    <tr>
                        <th>Data/Hora</th>
                        <th>Herói</th>
                        <th>Tipo</th>
                        <th>Cliente</th>
                        <th>Contato</th>
                        <th class="text-end">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(!empty($bookings)): ?>
                        <?php foreach($bookings as $booking): ?>
                        <tr>
                            <td>
                                <div><?= date('d/m/Y', strtotime($booking['date'])) ?></div>
                                <div class="small text-info"><?= substr($booking['start_time'], 0, 5) ?> - <?= substr($booking['end_time'], 0, 5) ?></div>
                            </td>
                            <td><span class="badge bg-secondary"><?= esc($booking['hero_name']) ?></span></td>
                            <td>
                                <?php if($booking['type'] === 'photoshoot'): ?>
                                    <span class="badge bg-primary">Ensaio</span>
                                <?php elseif($booking['type'] === 'visit_presential'): ?>
                                    <span class="badge bg-success">Visita (Estúdio)</span>
                                <?php else: ?>
                                    <span class="badge bg-info text-dark">Visita (Online)</span>
                                <?php endif; ?>
                            </td>
                            <td class="fw-bold"><?= esc($booking['name']) ?></td>
                            <td>
                                <div><?= esc($booking['email']) ?></div>
                                <div class="small text-muted"><?= esc($booking['phone']) ?></div>
                            </td>
                            <td class="text-end">
                                <form action="<?= site_url('admin/bookings/' . $booking['id'] . '/delete') ?>" method="post" class="d-inline" onsubmit="return confirm('Excluir este agendamento? O slot voltará a ficar livre.')">
                                    <button type="submit" class="btn btn-sm btn-outline-danger">Remover</button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">Nenhum agendamento realizado.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
