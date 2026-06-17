<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>
<div class="mb-4">
    <a href="<?= site_url('admin/heroes') ?>" class="btn btn-outline-secondary">&larr; Voltar para Heróis</a>
</div>

<?= view('admin/heroes/_nav', ['hero' => $hero, 'active' => 'schedule', 'title' => 'Agenda']) ?>

<div class="row">
    <div class="col-md-4">
        <div class="card bg-dark text-white border-secondary mb-4">
            <div class="card-header border-secondary text-info fw-bold text-uppercase">
                Adicionar Horário Disponível
            </div>
            <div class="card-body">
                <form action="<?= site_url('admin/heroes/' . $hero['id'] . '/schedule') ?>" method="post">
                    <div class="mb-3">
                        <label class="form-label">Tipo</label>
                        <select name="type" class="form-select bg-black text-white border-secondary" required>
                            <option value="photoshoot">Ensaio Fotográfico</option>
                            <option value="visit_presential">Visita Presencial (Estúdio)</option>
                            <option value="visit_online">Visita Online (Vídeo Call)</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Data Única</label>
                        <input type="date" name="date" class="form-control bg-black text-white border-secondary">
                    </div>
                    <div class="row">
                        <div class="col-6 mb-3">
                            <label class="form-label">Início</label>
                            <input type="time" name="start_time" class="form-control bg-black text-white border-secondary" required>
                        </div>
                        <div class="col-6 mb-3">
                            <label class="form-label">Fim</label>
                            <input type="time" name="end_time" class="form-control bg-black text-white border-secondary" required>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-info w-100 fw-bold">ADICIONAR SLOT ÚNICO</button>
                </form>

                <hr class="border-secondary my-4">

                <h6 class="text-info text-uppercase fw-bold mb-3">Abrir Período (Lote)</h6>
                <form action="<?= site_url('admin/heroes/' . $hero['id'] . '/schedule/bulk') ?>" method="post">
                    <div class="mb-3">
                        <label class="form-label">Data Início</label>
                        <input type="date" name="start_date" class="form-control bg-black text-white border-secondary" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Data Fim</label>
                        <input type="date" name="end_date" class="form-control bg-black text-white border-secondary" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Dias da Semana</label>
                        <div class="d-flex flex-wrap gap-2">
                            <?php $days = ['Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sab', 'Dom']; ?>
                            <?php foreach($days as $i => $day): ?>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="days[]" value="<?= $i+1 ?>" id="day_<?= $i ?>" checked>
                                    <label class="form-check-label small" for="day_<?= $i ?>"><?= $day ?></label>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6 mb-3">
                            <label class="form-label">Início</label>
                            <input type="time" name="start_time" class="form-control bg-black text-white border-secondary" required>
                        </div>
                        <div class="col-6 mb-3">
                            <label class="form-label">Fim</label>
                            <input type="time" name="end_time" class="form-control bg-black text-white border-secondary" required>
                        </div>
                    </div>
                    <input type="hidden" name="type" value="photoshoot" id="bulk_type">
                    <div class="mb-3">
                        <label class="form-label text-muted small">O tipo seguirá o selecionado no formulário acima.</label>
                    </div>
                    <button type="submit" class="btn btn-outline-info w-100 fw-bold">GERAR SLOTS EM LOTE</button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card bg-dark text-white border-secondary">
            <div class="card-header border-secondary text-warning fw-bold text-uppercase">
                Slots Definidos
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-dark table-hover align-middle">
                        <thead>
                            <tr>
                                <th>Data</th>
                                <th>Horário</th>
                                <th>Tipo</th>
                                <th>Status</th>
                                <th class="text-end">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(!empty($availabilities)): ?>
                                <?php foreach($availabilities as $avail): ?>
                                <tr>
                                    <td><?= date('d/m/Y', strtotime($avail['date'])) ?></td>
                                    <td><?= substr($avail['start_time'], 0, 5) ?> - <?= substr($avail['end_time'], 0, 5) ?></td>
                                    <td>
                                        <?php if($avail['type'] === 'photoshoot'): ?>
                                            <span class="badge bg-primary">Ensaio</span>
                                        <?php elseif($avail['type'] === 'visit_presential'): ?>
                                            <span class="badge bg-success">Visita (Estúdio)</span>
                                        <?php else: ?>
                                            <span class="badge bg-info text-dark">Visita (Online)</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if($avail['is_booked']): ?>
                                            <span class="badge bg-warning text-dark text-uppercase">Reservado</span>
                                        <?php else: ?>
                                            <span class="badge bg-outline-secondary text-uppercase opacity-50">Livre</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-end">
                                        <form action="<?= site_url('admin/schedules/' . $avail['id'] . '/delete') ?>" method="post" class="d-inline">
                                            <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Excluir este slot?')">Remover</button>
                                        </form>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">Nenhum horário definido.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
