<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">🎟️ Novo Cupom de Desconto</h1>
    <a href="<?= site_url('admin/coupons') ?>" class="btn btn-outline-secondary">← Voltar</a>
</div>

<?php if (session()->has('error')): ?>
    <div class="alert alert-danger"><?= session('error') ?></div>
<?php endif; ?>

<div class="card bg-dark border-secondary" style="max-width:520px;">
    <div class="card-body p-4">

        <p class="text-muted small mb-4">
            O código será gerado automaticamente. O desconto é vinculado ao e-mail informado —
            apenas esse e-mail pode aplicá-lo, e o cupom é de <strong>uso único</strong>.
        </p>

        <form method="POST" action="<?= site_url('admin/coupons/store') ?>">
            <?= csrf_field() ?>

            <div class="mb-4">
                <label class="form-label fw-semibold">E-mail do cliente <span class="text-danger">*</span></label>
                <input type="email" name="email" class="form-control bg-black text-white border-secondary"
                       value="<?= old('email') ?>" required
                       placeholder="cliente@email.com">
                <div class="form-text">Somente este e-mail poderá usar o cupom.</div>
            </div>

            <div class="mb-4">
                <label class="form-label fw-semibold">Percentual de desconto <span class="text-danger">*</span></label>
                <div class="input-group">
                    <input type="number" name="discount_percent" class="form-control bg-black text-white border-secondary"
                           value="<?= old('discount_percent', 10) ?>"
                           min="1" max="100" required id="discountInput">
                    <span class="input-group-text bg-secondary border-secondary text-white">%</span>
                </div>
                <div id="discountHint" class="form-text mt-1"></div>
            </div>

            <div id="coutersy-warning" class="alert alert-warning d-none p-2 small mb-4">
                ⚠️ <strong>Cortesia total (100%)</strong> — o cliente não passará pelo pagamento. O ensaio será confirmado automaticamente ao usar o cupom.
            </div>

            <div class="d-grid">
                <button type="submit" class="btn btn-success btn-lg">
                    Gerar Cupom
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    const input = document.getElementById('discountInput');
    const hint  = document.getElementById('discountHint');
    const warn  = document.getElementById('coutersy-warning');

    function updateHint() {
        const v = parseInt(input.value) || 0;
        if (v === 100) {
            hint.textContent = '';
            warn.classList.remove('d-none');
        } else if (v > 0) {
            warn.classList.add('d-none');
            hint.textContent = `O cliente pagará ${100 - v}% do valor original do pacote.`;
        } else {
            hint.textContent = '';
            warn.classList.add('d-none');
        }
    }

    input.addEventListener('input', updateHint);
    updateHint();
</script>
<?= $this->endSection() ?>
