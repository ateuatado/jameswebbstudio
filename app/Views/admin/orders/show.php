<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <a href="<?= site_url('admin/orders') ?>" class="text-muted text-decoration-none small">← Pedidos</a>
        <h2 class="text-warning fw-bold text-uppercase mb-0">Pedido #<?= $order->id ?></h2>
    </div>
    <?php
    $badges = ['approved'=>'success','pending'=>'warning','cancelled'=>'danger','refunded'=>'secondary'];
    $labels = ['approved'=>'Aprovado','pending'=>'Pendente','cancelled'=>'Cancelado','refunded'=>'Reembolsado'];
    $badge  = $badges[$order->status] ?? 'secondary';
    $label  = $labels[$order->status] ?? $order->status;
    ?>
    <span class="badge bg-<?= $badge ?> fs-6"><?= $label ?></span>
</div>

<div class="row g-4">
    <!-- Dados do cliente -->
    <div class="col-md-6">
        <div class="card bg-dark border-secondary h-100">
            <div class="card-header border-secondary text-warning text-uppercase small fw-bold">
                Cliente
            </div>
            <div class="card-body">
                <table class="table table-dark table-sm mb-0">
                    <tr><td class="text-muted" width="35%">Nome</td><td class="fw-bold"><?= esc($order->buyer_name) ?></td></tr>
                    <tr><td class="text-muted">E-mail</td><td><?= esc($order->buyer_email) ?></td></tr>
                    <tr><td class="text-muted">WhatsApp</td><td><?= esc($order->buyer_phone ?: '—') ?></td></tr>
                    <tr><td class="text-muted">Data</td><td><?= date('d/m/Y H:i', strtotime($order->created_at)) ?></td></tr>
                </table>
                <!-- Botão WhatsApp -->
                <?php if ($order->buyer_phone): ?>
                <?php $wa = 'https://wa.me/55' . preg_replace('/\D/', '', $order->buyer_phone); ?>
                <a href="<?= $wa ?>" target="_blank" class="btn btn-outline-success btn-sm mt-3">
                    📱 Abrir WhatsApp
                </a>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Dados do pedido -->
    <div class="col-md-6">
        <div class="card bg-dark border-secondary h-100">
            <div class="card-header border-secondary text-warning text-uppercase small fw-bold">
                Pedido
            </div>
            <div class="card-body">
                <table class="table table-dark table-sm mb-0">
                    <tr>
                        <td class="text-muted" width="40%">Pacote</td>
                        <td><?= $package ? esc($package->name) : '<span class="text-muted">ID ' . $order->package_id . '</span>' ?></td>
                    </tr>
                    <tr>
                        <td class="text-muted">Valor</td>
                        <td class="fw-bold text-success fs-5">R$ <?= number_format($order->amount, 2, ',', '.') ?></td>
                    </tr>
                    <tr>
                        <td class="text-muted">Status</td>
                        <td><span class="badge bg-<?= $badge ?>"><?= $label ?></span></td>
                    </tr>
                    <tr>
                        <td class="text-muted">Preference MP</td>
                        <td class="small font-monospace text-muted"><?= esc($order->mp_preference_id ?: '—') ?></td>
                    </tr>
                    <tr>
                        <td class="text-muted">Payment MP</td>
                        <td class="small font-monospace">
                            <?php if ($order->mp_payment_id): ?>
                            <a href="https://www.mercadopago.com.br/activities/search?search_term=<?= $order->mp_payment_id ?>"
                               target="_blank" class="text-info"><?= $order->mp_payment_id ?></a>
                            <?php else: ?>
                            <span class="text-muted">Aguardando pagamento</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-muted">Agendamento</td>
                        <td>
                            <?php if (!empty($order->agenda_link)): ?>
                            <a href="<?= esc($order->agenda_link) ?>" target="_blank" class="text-info">
                                📅 Abrir link do agendamento
                            </a>
                            <?php elseif ($order->status === 'approved'): ?>
                            <span class="text-warning">⏳ Link ainda não gerado</span>
                            <?php else: ?>
                            <span class="text-muted">—</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php if (!empty($order->accepted_terms_at)): ?>
                    <tr>
                        <td class="text-muted">Termos aceitos</td>
                        <td class="small text-success">✅ <?= date('d/m/Y H:i', strtotime($order->accepted_terms_at)) ?></td>
                    </tr>
                    <?php endif; ?>
                    <?php if ($order->image_usage_authorized !== null): ?>
                    <tr>
                        <td class="text-muted">Uso de imagem</td>
                        <td>
                            <?= $order->image_usage_authorized ? '<span class="text-success">✅ Autorizado</span>' : '<span class="text-danger">❌ Não autorizado</span>' ?>
                        </td>
                    </tr>
                    <?php endif; ?>
                </table>
            </div>
        </div>
    </div>

    <!-- ═════════ DADOS CONTRATUAIS ═════════ -->
    <div class="col-12">
        <div class="card bg-dark border-secondary">
            <div class="card-header border-secondary d-flex justify-content-between align-items-center">
                <span class="text-warning text-uppercase small fw-bold">📋 Dados do Contrato</span>
                <a href="<?= site_url('admin/orders/' . $order->id . '/contract') ?>" target="_blank" class="btn btn-outline-light btn-sm" style="font-size:.72rem;letter-spacing:.08em;">
                    📄 Gerar Contrato PDF
                </a>
            </div>
            <div class="card-body">
                <?php if (session()->has('message')): ?>
                    <div class="alert alert-success alert-dismissible fade show" style="font-size:.85rem;" role="alert">
                        <?= session('message') ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <form action="<?= site_url('admin/orders/' . $order->id . '/contract') ?>" method="post">
                    <?= csrf_field() ?>
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label text-muted small">CPF</label>
                            <input type="text" name="cpf" class="form-control bg-black text-white border-secondary"
                                   value="<?= esc($order->cpf ?? '') ?>" placeholder="000.000.000-00"
                                   maxlength="14">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label text-muted small">RG</label>
                            <input type="text" name="rg" class="form-control bg-black text-white border-secondary"
                                   value="<?= esc($order->rg ?? '') ?>" placeholder="00.000.000-0">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label text-muted small">Estado Civil</label>
                            <select name="marital_status" class="form-select bg-black text-white border-secondary">
                                <option value="">Selecione...</option>
                                <?php foreach (['Solteiro(a)','Casado(a)','Divorciado(a)','Viúvo(a)','União Estável'] as $ms): ?>
                                    <option value="<?= $ms ?>" <?= ($order->marital_status ?? '') === $ms ? 'selected' : '' ?>><?= $ms ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted small">Endereço</label>
                            <input type="text" name="address" class="form-control bg-black text-white border-secondary"
                                   value="<?= esc($order->address ?? '') ?>" placeholder="Rua, número, complemento">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label text-muted small">Cidade</label>
                            <input type="text" name="city" class="form-control bg-black text-white border-secondary"
                                   value="<?= esc($order->city ?? '') ?>" placeholder="São Paulo">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label text-muted small">UF</label>
                            <input type="text" name="state" class="form-control bg-black text-white border-secondary"
                                   value="<?= esc($order->state ?? '') ?>" placeholder="SP" maxlength="2"
                                   style="text-transform:uppercase;">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label text-muted small">CEP</label>
                            <input type="text" name="zip_code" class="form-control bg-black text-white border-secondary"
                                   value="<?= esc($order->zip_code ?? '') ?>" placeholder="00000-000" maxlength="10">
                        </div>
                        <div class="col-md-3 d-flex align-items-end">
                            <button type="submit" class="btn btn-terroso btn-sm w-100">💾 Salvar Dados</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Payload bruto do MP (colapsável) -->
    <?php if ($order->mp_raw): ?>
    <div class="col-12">
        <div class="card bg-dark border-secondary">
            <div class="card-header border-secondary d-flex justify-content-between align-items-center">
                <span class="text-muted small text-uppercase">Dados brutos MercadoPago</span>
                <button class="btn btn-sm btn-outline-secondary" type="button"
                        data-bs-toggle="collapse" data-bs-target="#rawPayload">
                    Expandir
                </button>
            </div>
            <div id="rawPayload" class="collapse">
                <div class="card-body">
                    <pre class="text-muted small mb-0" style="max-height:300px;overflow:auto"><?= esc(json_encode(json_decode($order->mp_raw), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)) ?></pre>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>
