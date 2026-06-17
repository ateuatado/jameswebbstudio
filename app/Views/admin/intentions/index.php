<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="text-warning fw-bold text-uppercase">Leads de Intenção</h2>
</div>

<div class="card bg-dark text-white border-secondary">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-dark table-hover align-middle">
                <thead>
                    <tr>
                        <th>Data</th>
                        <th>Herói</th>
                        <th>Nome</th>
                        <th>Email / Telefone</th>
                        <th>Tipo Ensaio</th>
                        <th>Idade</th>
                        <th>Endereço</th>
                        <th>Status</th>
                        <th class="text-end">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(!empty($intentions)): ?>
                        <?php foreach($intentions as $intent): ?>
                        <tr>
                            <td class="small text-muted"><?= date('d/m/Y H:i', strtotime($intent['created_at'])) ?></td>
                            <td><span class="badge bg-secondary"><?= esc($intent['hero_name']) ?></span></td>
                            <td class="fw-bold"><?= esc($intent['name']) ?></td>
                            <td>
                                <div><?= esc($intent['email']) ?></div>
                                <div class="small text-info"><?= esc($intent['phone']) ?></div>
                            </td>
                            <td><span class="badge bg-info text-dark"><?= esc($intent['shoot_type']) ?></span></td>
                            <td><?= esc($intent['age']) ?></td>
                            <td class="small text-muted"><?= esc($intent['address']) ?></td>
                            <td>
                                <?php if($intent['booking_id']): ?>
                                    <span class="badge bg-success">AGENDADO</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary opacity-50">INTERESSE</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-end">
                                <form action="<?= site_url('admin/intentions/' . $intent['id'] . '/delete') ?>" method="post" class="d-inline" onsubmit="return confirm('Excluir este lead definitivamente?')">
                                    <button type="submit" class="btn btn-sm btn-outline-danger">Excluir</button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">Nenhuma intenção registrada ainda.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
