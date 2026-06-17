<?= $this->extend('admin/layout') ?>
<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 text-gold mb-1">Dados do Estúdio</h1>
        <p class="text-muted mb-0" style="font-size:.8rem;">Dados do fotógrafo e estúdio utilizados nos contratos, guias e demais documentos.</p>
    </div>
</div>

<?php if (session()->has('message')): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert" style="font-size:.85rem;">
        <?= session('message') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<form action="<?= site_url('admin/studio/update') ?>" method="post">
    <?= csrf_field() ?>

    <div class="card bg-dark border-secondary">
        <div class="card-header" style="border-bottom:1px solid rgba(197,160,89,.15);">
            <span style="font-size:.7rem;letter-spacing:.12em;text-transform:uppercase;color:#C5A059;">📸 Identificação e Endereço</span>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <?php foreach ($settings as $s): ?>
                    <?php
                        $colSize = 'col-md-6';
                        if (in_array($s->setting_key, ['studio_state', 'studio_zip', 'owner_cpf', 'owner_marital_status', 'studio_cnpj'])) $colSize = 'col-md-3';
                        if (in_array($s->setting_key, ['studio_address'])) $colSize = 'col-md-9';
                    ?>
                    <div class="<?= $colSize ?>">
                        <label class="form-label text-muted" style="font-size:.75rem;"><?= esc($s->label) ?></label>
                        <?php if ($s->setting_key === 'owner_marital_status'): ?>
                            <select name="<?= esc($s->setting_key) ?>" class="form-select bg-black text-white border-secondary" style="font-size:.9rem;">
                                <option value="">Selecione...</option>
                                <?php foreach (['Solteiro(a)','Casado(a)','Divorciado(a)','Viúvo(a)','União Estável','Casado'] as $ms): ?>
                                    <option value="<?= $ms ?>" <?= $s->setting_value === $ms ? 'selected' : '' ?>><?= $ms ?></option>
                                <?php endforeach; ?>
                            </select>
                        <?php elseif ($s->setting_key === 'studio_state'): ?>
                            <input type="text" name="<?= esc($s->setting_key) ?>" class="form-control bg-black text-white border-secondary"
                                   value="<?= esc($s->setting_value) ?>" maxlength="2" style="text-transform:uppercase;font-size:.9rem;">
                        <?php else: ?>
                            <input type="text" name="<?= esc($s->setting_key) ?>" class="form-control bg-black text-white border-secondary"
                                   value="<?= esc($s->setting_value) ?>" style="font-size:.9rem;">
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <div class="mt-3">
        <button type="submit" class="btn btn-terroso">💾 Salvar Dados</button>
    </div>
</form>

<div class="mt-4 p-3" style="background:rgba(197,160,89,.05);border:1px solid rgba(197,160,89,.1);">
    <p class="text-muted mb-0" style="font-size:.75rem;">
        <strong style="color:#C5A059;">ℹ️ Onde esses dados são usados?</strong><br>
        Contrato de Prestação de Serviços · Guia Pré-Ensaio (contracapa) · E-mails automáticos · Qualquer documento futuro
    </p>
</div>

<?= $this->endSection() ?>
