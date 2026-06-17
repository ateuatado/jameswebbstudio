<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>

<div class="mb-4">
    <a href="<?= site_url('admin/services') ?>" class="btn btn-outline-secondary">&larr; Voltar para Serviços</a>
</div>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card bg-dark text-white border-secondary">
            <div class="card-header border-secondary">
                <h5 class="mb-0 text-info fw-bold text-uppercase"><?= esc($title) ?></h5>
            </div>
            <div class="card-body">
                <?php if (session()->has('errors')): ?>
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            <?php foreach (session('errors') as $err): ?>
                                <li><?= esc($err) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <form action="<?= isset($service) ? site_url('admin/services/' . $service->id . '/edit') : site_url('admin/services') ?>" method="post">

                    <!-- Fase -->
                    <div class="mb-3">
                        <label class="form-label">Fase do Ensaio <span class="text-danger">*</span></label>
                        <select name="phase" class="form-select bg-black text-white border-secondary" required>
                            <?php foreach ($phases as $value => $label): ?>
                                <option value="<?= $value ?>"
                                    <?= old('phase', $service->phase ?? '') === $value ? 'selected' : '' ?>>
                                    <?= esc($label) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <small class="text-muted">Em qual momento do ensaio este serviço acontece?</small>
                    </div>

                    <!-- Nome -->
                    <div class="mb-3">
                        <label class="form-label">Nome do Serviço <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control bg-black text-white border-secondary"
                               value="<?= old('name', $service->name ?? '') ?>" required
                               placeholder="Ex: Consultoria de Figurino">
                    </div>

                    <!-- Descrição -->
                    <div class="mb-3">
                        <label class="form-label">Descrição</label>
                        <textarea name="description" class="form-control bg-black text-white border-secondary" rows="3"
                                  placeholder="Ex: Reunião online de 30 minutos para definir roupas, cores e acessórios ideais para o ensaio."><?= old('description', $service->description ?? '') ?></textarea>
                        <small class="text-muted">Descreva o que está incluso neste serviço. Será usado na montagem dos pacotes.</small>
                    </div>

                    <!-- Preço -->
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Valor (R$) <span class="text-danger">*</span></label>
                            <input type="number" name="price" step="0.01" min="0"
                                   class="form-control bg-black text-white border-secondary"
                                   value="<?= old('price', $service->price ?? '0.00') ?>" required>
                            <small class="text-muted">Use 0.00 para serviços sem custo adicional (inclusos na base).</small>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Status</label>
                            <select name="is_active" class="form-select bg-black text-white border-secondary">
                                <option value="1" <?= old('is_active', $service->is_active ?? 1) == 1 ? 'selected' : '' ?>>Ativo</option>
                                <option value="0" <?= old('is_active', $service->is_active ?? 1) == 0 ? 'selected' : '' ?>>Inativo</option>
                            </select>
                        </div>
                    </div>

                    <hr class="border-secondary">

                    <button type="submit" class="btn btn-primary px-4">
                        <?= isset($service) ? 'Atualizar Serviço' : 'Salvar Serviço' ?>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
