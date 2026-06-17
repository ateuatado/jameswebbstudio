<?= $this->extend('admin/layout') ?>
<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 text-gold mb-0"><?= isset($section) && $section ? 'Editar Cláusula' : 'Nova Cláusula' ?></h1>
    <a href="<?= site_url('admin/contract-sections') ?>" class="btn btn-outline-light btn-sm" style="font-size:.75rem;">← Voltar</a>
</div>

<?php if (session()->has('errors')): ?>
    <div class="alert alert-danger" style="font-size:.85rem;">
        <ul class="mb-0">
            <?php foreach (session('errors') as $e): ?>
                <li><?= esc($e) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<form action="<?= isset($section) && $section ? site_url('admin/contract-sections/' . $section->id . '/update') : site_url('admin/contract-sections/store') ?>" method="post">
    <?= csrf_field() ?>

    <div class="card bg-dark border-secondary">
        <div class="card-body">

            <div class="mb-3">
                <label class="form-label text-gold" style="font-size:.8rem;letter-spacing:.08em;">Título da Cláusula</label>
                <input type="text" name="title" class="form-control bg-black text-white border-secondary"
                       value="<?= esc(old('title', $section->title ?? '')) ?>"
                       placeholder="Ex: DO VALOR E PAGAMENTO" required>
            </div>

            <div class="mb-3">
                <label class="form-label text-gold" style="font-size:.8rem;letter-spacing:.08em;">Conteúdo</label>
                <textarea name="content" class="form-control bg-black text-white border-secondary" rows="14"
                          placeholder="Escreva o conteúdo da cláusula..." required
                          style="line-height:1.7;font-size:.9rem;"><?= esc(old('content', $section->content ?? '')) ?></textarea>
                <small class="text-muted">Use quebras de linha normais. O texto aparece como escrito no contrato.</small>
            </div>

            <div class="card bg-black border-secondary mb-3">
                <div class="card-body py-2 px-3">
                    <p class="mb-1" style="font-size:.75rem;letter-spacing:.08em;text-transform:uppercase;color:#C5A059;">Placeholders disponíveis</p>
                    <div class="row" style="font-size:.8rem;color:rgba(255,255,255,.6);">
                        <div class="col-md-4">
                            <strong style="color:#C5A059;font-size:.7rem;">CONTRATADO</strong><br>
                            <code>{contratado_nome}</code> — Nome civil<br>
                            <code>{contratado_cpf}</code> — CPF<br>
                            <code>{contratado_estado_civil}</code> — Estado civil<br>
                            <code>{contratado_endereco}</code> — Endereço<br>
                            <code>{nome_estudio}</code> — Nome do estúdio<br>
                        </div>
                        <div class="col-md-4">
                            <strong style="color:#C5A059;font-size:.7rem;">CONTRATANTE</strong><br>
                            <code>{nome_cliente}</code> — Nome do cliente<br>
                            <code>{cpf_cliente}</code> — CPF do cliente<br>
                            <code>{estado_civil}</code> — Estado civil<br>
                            <code>{endereco_completo}</code> — Endereço<br>
                            <code>{email}</code> — E-mail<br>
                            <code>{telefone}</code> — Telefone<br>
                        </div>
                        <div class="col-md-4">
                            <strong style="color:#C5A059;font-size:.7rem;">PACOTE / VALORES</strong><br>
                            <code>{nome_pacote}</code> — Nome do pacote<br>
                            <code>{valor}</code> — Valor total<br>
                            <code>{qtd_fotos}</code> — Qtd de fotos<br>
                            <code>{valor_foto_extra}</code> — Valor foto extra<br>
                            <code>{forma_pagamento}</code> — Forma de pagamento<br>
                            <code>{data_contratacao}</code> — Data<br>
                            <code>{autorizacao_imagem}</code> — Autorização<br>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-3">
                    <label class="form-label text-gold" style="font-size:.8rem;letter-spacing:.08em;">Ordem de exibição</label>
                    <input type="number" name="display_order" class="form-control bg-black text-white border-secondary"
                           value="<?= esc(old('display_order', $section->display_order ?? 0)) ?>" min="0">
                    <small class="text-muted">Menor número = aparece primeiro.</small>
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <div class="form-check mb-3">
                        <input type="checkbox" name="is_active" class="form-check-input" id="is_active" value="1"
                               <?= old('is_active', $section->is_active ?? 1) ? 'checked' : '' ?>>
                        <label class="form-check-label text-white" for="is_active">Ativo</label>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <div class="mt-3 d-flex gap-2">
        <button type="submit" class="btn btn-terroso">
            <?= isset($section) && $section ? 'Salvar Alterações' : 'Criar Cláusula' ?>
        </button>
        <a href="<?= site_url('admin/contract-sections') ?>" class="btn btn-outline-light">Cancelar</a>
    </div>
</form>

<?= $this->endSection() ?>
