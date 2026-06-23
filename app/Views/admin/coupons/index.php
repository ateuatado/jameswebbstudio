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
                    <th class="text-end">Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($coupons)): ?>
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">Nenhum cupom cadastrado ainda.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($coupons as $c): ?>
                    <?php $giftUrl = site_url('cortesia/' . $c->code); ?>
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
                            <div class="d-flex gap-2 justify-content-end align-items-center">
                            <?php if (!$c->used): ?>
                                <button class="btn btn-outline-info btn-sm"
                                        onclick="abrirLink('<?= esc($giftUrl) ?>', '<?= esc($c->email) ?>', '<?= esc($c->code) ?>')"
                                        title="Enviar link do presente">
                                    📤 Enviar
                                </button>
                                <form method="POST" action="<?= site_url('admin/coupons/' . $c->id . '/delete') ?>"
                                      onsubmit="return confirm('Remover o cupom <?= esc($c->code) ?>?')">
                                    <?= csrf_field() ?>
                                    <button class="btn btn-outline-danger btn-sm">Remover</button>
                                </form>
                            <?php else: ?>
                                <span class="text-muted small">—</span>
                            <?php endif; ?>
                            </div>
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

<!-- Modal compartilhar -->
<div class="modal fade" id="shareModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered" style="max-width:520px;">
    <div class="modal-content bg-dark border-secondary">
      <div class="modal-header border-secondary">
        <h5 class="modal-title text-white">📤 Enviar Presente</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <p class="text-muted small mb-3">Envie este link para <strong id="shareEmail" class="text-warning"></strong>:</p>
        <div class="input-group mb-3">
          <input type="text" id="shareUrl" class="form-control bg-black text-white border-secondary font-monospace small" readonly>
          <button class="btn btn-outline-warning" onclick="copiarLink()" id="copyBtn">Copiar</button>
        </div>
        <a id="previewBtn" href="#" target="_blank" class="btn btn-outline-secondary btn-sm w-100 mb-2">
          👁️ Pré-visualizar a página de presente
        </a>
        <button class="btn btn-success w-100" onclick="enviarWhats()" style="background:#25D366;border-color:#25D366;">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="white" class="me-2" style="vertical-align:middle;"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/><path d="M12 0C5.373 0 0 5.373 0 12c0 2.136.562 4.14 1.537 5.876L.057 23.886l6.19-1.623A11.94 11.94 0 0012 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 21.818a9.8 9.8 0 01-5.032-1.387l-.36-.214-3.735.979 1-3.644-.234-.373A9.804 9.804 0 012.182 12C2.182 6.57 6.57 2.182 12 2.182c5.43 0 9.818 4.388 9.818 9.818 0 5.43-4.388 9.818-9.818 9.818z"/></svg>
          Enviar pelo WhatsApp
        </button>
      </div>
    </div>
  </div>
</div>

<script>
let _shareUrl = '', _shareEmail = '', _shareCode = '';

function abrirLink(url, email, code) {
    _shareUrl = url; _shareEmail = email; _shareCode = code;
    document.getElementById('shareEmail').textContent = email;
    document.getElementById('shareUrl').value = url;
    document.getElementById('previewBtn').href = url;
    new bootstrap.Modal(document.getElementById('shareModal')).show();
}

function copiarLink() {
    navigator.clipboard.writeText(_shareUrl).then(() => {
        const btn = document.getElementById('copyBtn');
        btn.textContent = '✓ Copiado!'; btn.className = 'btn btn-success';
        setTimeout(() => { btn.textContent = 'Copiar'; btn.className = 'btn btn-outline-warning'; }, 2000);
    });
}

function enviarWhats() {
    const msg = `🎁 *Você tem um presente especial!*\n\nO James Webb Studio preparou um ensaio fotográfico exclusivo para você — completamente gratuito.\n\nClique para ver e resgatar:\n${_shareUrl}\n\n_(Cupom: ${_shareCode} · Uso único e intransferível)_`;
    window.open('https://wa.me/?text=' + encodeURIComponent(msg), '_blank');
}
</script>

<?= $this->endSection() ?>
