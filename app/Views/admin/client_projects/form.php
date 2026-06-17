<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>
<div class="mb-4">
    <h2><?= esc($title) ?></h2>
    <a href="<?= site_url('admin/client-projects') ?>" class="btn btn-sm btn-outline-secondary">Voltar</a>
</div>

<?php if (session()->getFlashdata('errors')): ?>
    <div class="alert alert-danger">
        <ul class="mb-0">
            <?php foreach (session()->getFlashdata('errors') as $error): ?>
                <li><?= esc($error) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<div class="card bg-dark text-white border-secondary">
    <div class="card-body">
        <?php $isEdit = isset($project); ?>
        <form action="<?= $isEdit ? site_url('admin/client-projects/' . $project->id) : site_url('admin/client-projects') ?>" method="post">
            <?php if ($isEdit): ?>
                <input type="hidden" name="_method" value="PUT">
            <?php endif; ?>
            
            <div class="mb-3">
                <label class="form-label">Nome do Ensaio / Atendimento</label>
                <input type="text" name="name" class="form-control bg-dark text-white border-secondary" placeholder="Ex: Corporativo 2026" value="<?= esc($project->name ?? old('name')) ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Cliente (Usuário)</label>
                <select name="user_id" class="form-select bg-dark text-white" required>
                    <option value="">Selecione...</option>
                    <?php foreach ($users as $u): ?>
                        <?php $selected = (isset($project) && $project->user_id == $u->id) ? 'selected' : ''; ?>
                        <option value="<?= esc($u->id) ?>" <?= $selected ?>><?= esc($u->email) ?> (<?= esc($u->username) ?>)</option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="mb-3">
                <label class="form-label">Pacote</label>
                <select name="package_id" class="form-select bg-dark text-white" required>
                    <option value="">Selecione...</option>
                    <?php foreach ($packages as $p): ?>
                        <?php $selected = (isset($project) && $project->package_id == $p->id) ? 'selected' : ''; ?>
                        <option value="<?= esc($p->id) ?>" <?= $selected ?>><?= esc($p->name) ?> (<?= $p->included_photos ?> fotos)</option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-4">
                <label class="form-label">Status</label>
                <select name="status" class="form-select bg-dark text-white" required>
                    <?php 
                        $statusList = ['open' => 'Aberto (Em Seleção)', 'selecting' => 'Selecionando', 'paid' => 'Pago', 'completed' => 'Finalizado'];
                        $currentStatus = $project->status ?? 'open';
                    ?>
                    <?php foreach ($statusList as $key => $label): ?>
                        <option value="<?= $key ?>" <?= $key === $currentStatus ? 'selected' : '' ?>><?= $label ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Salvar Projeto</button>
        </form>
    </div>
</div>
<?= $this->endSection() ?>
