<?= $this->extend('admin/layout') ?>

<?= $this->section('styles') ?>
<style>
    .phase-group { margin-bottom: 20px; }
    .phase-group-title {
        font-size: .7rem; font-weight: 600;
        letter-spacing: .12em; text-transform: uppercase;
        color: #C5A059; margin-bottom: 8px;
        padding-bottom: 6px; border-bottom: 1px solid rgba(197,160,89,.2);
    }
    .service-check {
        display: flex; align-items: flex-start; gap: 10px;
        padding: 6px 8px; margin-bottom: 2px;
        border-radius: 4px; transition: background .15s;
    }
    .service-check:hover { background: rgba(255,255,255,.04); }
    .service-check input[type="checkbox"] { margin-top: 4px; flex-shrink: 0; }
    .service-check .svc-info { flex: 1; }
    .service-check .svc-name { font-size: .85rem; color: #fff; }
    .service-check .svc-desc { font-size: .72rem; color: rgba(255,255,255,.35); margin-top: 1px; }
    .service-check .svc-price { font-size: .8rem; color: #66bb6a; font-weight: 600; white-space: nowrap; }
    .cost-summary {
        background: rgba(255,255,255,.04); border: 1px solid rgba(255,255,255,.08);
        padding: 16px; margin-top: 12px;
    }
    .cost-row { display: flex; justify-content: space-between; padding: 4px 0; font-size: .85rem; }
    .cost-row.total { border-top: 1px solid rgba(255,255,255,.1); padding-top: 8px; margin-top: 8px; font-weight: 600; font-size: 1rem; }
    .cost-row .label { color: rgba(255,255,255,.5); }
    .cost-row .value { color: #66bb6a; }
    .cost-row.total .value { color: #C5A059; }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="mb-4">
    <a href="<?= site_url('admin/packages') ?>" class="btn btn-outline-secondary">&larr; Voltar</a>
    <h2 class="mt-2"><?= esc($title) ?></h2>
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

<?php $isEdit = isset($package); ?>
<form action="<?= $isEdit ? site_url('admin/packages/' . $package->id) : site_url('admin/packages') ?>" method="post">
    <?php if ($isEdit): ?>
        <input type="hidden" name="_method" value="PUT">
    <?php endif; ?>

    <div class="row">
        <!-- ── Coluna esquerda: dados do pacote ── -->
        <div class="col-md-5">
            <div class="card bg-dark text-white border-secondary mb-4">
                <div class="card-header border-secondary text-info fw-bold text-uppercase" style="font-size:.8rem;letter-spacing:.1em;">Dados do Pacote</div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Nome do Pacote</label>
                        <input type="text" name="name" class="form-control bg-dark text-white border-secondary" value="<?= old('name', $package->name ?? '') ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Categoria / Nicho</label>
                        <select name="category_id" class="form-select bg-dark text-white border-secondary">
                            <option value="">Nenhum (Geral)</option>
                            <?php foreach ($categories as $cat): ?>
                                <?php $selected = (isset($package) && $package->category_id == $cat->id) ? 'selected' : ''; ?>
                                <option value="<?= esc($cat->id) ?>" <?= $selected ?>><?= esc($cat->name) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Valor Cobrado do Cliente (R$)</label>
                        <input type="number" step="0.01" name="base_price" id="basePrice"
                               class="form-control bg-dark text-white border-secondary"
                               value="<?= old('base_price', $package->base_price ?? '0.00') ?>" required>
                        <small class="text-muted">O preço final que o cliente paga.</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Descrição (visível ao cliente)</label>
                        <textarea name="description" class="form-control bg-dark text-white border-secondary" rows="3"
                                  placeholder="Benefícios e o que inclui..."><?= old('description', $package->description ?? '') ?></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Notas Internas (equipe)</label>
                        <textarea name="internal_notes" class="form-control bg-dark text-white border-secondary" rows="2"
                                  placeholder="Custos, produção, agenda..."><?= old('internal_notes', $package->internal_notes ?? '') ?></textarea>
                    </div>

                    <div class="row mb-3">
                        <div class="col-6">
                            <label class="form-label">Fotos Inclusas</label>
                            <input type="number" name="included_photos" class="form-control bg-dark text-white border-secondary"
                                   value="<?= old('included_photos', $package->included_photos ?? '') ?>" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label">Foto Extra (R$)</label>
                            <input type="number" step="0.01" name="extra_photo_price" class="form-control bg-dark text-white border-secondary"
                                   value="<?= old('extra_photo_price', $package->extra_photo_price ?? '0.00') ?>" required>
                        </div>
                    </div>

                    <div class="form-check mb-2">
                        <input type="checkbox" name="is_active" class="form-check-input" id="is_active" value="1"
                            <?= old('is_active', $package->is_active ?? 1) == 1 ? 'checked' : '' ?>>
                        <label class="form-check-label text-white-50" for="is_active">Ativo no site</label>
                    </div>
                    <div class="form-check mb-3">
                        <input type="checkbox" name="is_preferred" class="form-check-input" id="is_preferred" value="1"
                            <?= old('is_preferred', $package->is_preferred ?? 0) == 1 ? 'checked' : '' ?>>
                        <label class="form-check-label text-white-50" for="is_preferred">Pacote Destaque ⭐</label>
                    </div>
                </div>
            </div>
        </div>

        <!-- ── Coluna direita: serviços inclusos ── -->
        <div class="col-md-7">
            <div class="card bg-dark text-white border-secondary mb-4">
                <div class="card-header border-secondary text-info fw-bold text-uppercase" style="font-size:.8rem;letter-spacing:.1em;">Serviços Inclusos</div>
                <div class="card-body">
                    <p class="text-muted small mb-3">Marque os serviços que fazem parte deste pacote. O custo é calculado automaticamente.</p>

                    <?php foreach ($servicesGrouped as $phase => $group): ?>
                        <?php if (!empty($group['services'])): ?>
                        <div class="phase-group">
                            <div class="phase-group-title"><?= esc($group['label']) ?></div>
                            <?php foreach ($group['services'] as $svc): ?>
                            <label class="service-check">
                                <input type="checkbox" name="services[]" value="<?= $svc->id ?>"
                                       class="svc-checkbox" data-price="<?= $svc->price ?>"
                                       <?= in_array($svc->id, $selectedServices) ? 'checked' : '' ?>>
                                <div class="svc-info">
                                    <div class="svc-name"><?= esc($svc->name) ?></div>
                                    <?php if ($svc->description): ?>
                                        <div class="svc-desc"><?= esc($svc->description) ?></div>
                                    <?php endif; ?>
                                </div>
                                <span class="svc-price">
                                    <?= $svc->price > 0 ? 'R$ ' . number_format($svc->price, 0, ',', '.') : 'Incluso' ?>
                                </span>
                            </label>
                            <?php endforeach; ?>
                        </div>
                        <?php endif; ?>
                    <?php endforeach; ?>

                    <!-- Resumo de custos -->
                    <div class="cost-summary">
                        <div class="cost-row">
                            <span class="label">Serviços selecionados</span>
                            <span class="value" id="selectedCount">0</span>
                        </div>
                        <div class="cost-row">
                            <span class="label">Custo total dos serviços</span>
                            <span class="value" id="servicesCost">R$ 0</span>
                        </div>
                        <div class="cost-row">
                            <span class="label">Preço cobrado do cliente</span>
                            <span class="value" id="clientPrice">R$ 0</span>
                        </div>
                        <div class="cost-row total">
                            <span class="label">Margem</span>
                            <span class="value" id="margin">R$ 0</span>
                        </div>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary btn-lg w-100">
                <?= $isEdit ? 'Atualizar Pacote' : 'Salvar Pacote' ?>
            </button>
        </div>
    </div>
</form>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
function updateCostSummary() {
    const checkboxes = document.querySelectorAll('.svc-checkbox');
    let count = 0, cost = 0;
    checkboxes.forEach(cb => {
        if (cb.checked) {
            count++;
            cost += parseFloat(cb.dataset.price || 0);
        }
    });
    const clientPrice = parseFloat(document.getElementById('basePrice').value || 0);
    const margin = clientPrice - cost;

    document.getElementById('selectedCount').textContent = count;
    document.getElementById('servicesCost').textContent = 'R$ ' + cost.toLocaleString('pt-BR', {minimumFractionDigits: 0});
    document.getElementById('clientPrice').textContent = 'R$ ' + clientPrice.toLocaleString('pt-BR', {minimumFractionDigits: 0});
    document.getElementById('margin').textContent = 'R$ ' + margin.toLocaleString('pt-BR', {minimumFractionDigits: 0});
    document.getElementById('margin').style.color = margin >= 0 ? '#C5A059' : '#dc3545';
}

document.querySelectorAll('.svc-checkbox').forEach(cb => cb.addEventListener('change', updateCostSummary));
document.getElementById('basePrice').addEventListener('input', updateCostSummary);
updateCostSummary();
</script>
<?= $this->endSection() ?>
