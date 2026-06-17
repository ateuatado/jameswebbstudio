<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="container" style="margin-top: 120px; min-height: 60vh;">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card bg-black border border-secondary shadow-lg">
                <div class="card-body p-5 text-center">
                    <h2 class="text-gold brand-font mb-4 text-uppercase">Resumo da Seleção</h2>
                    
                    <ul class="list-group list-group-flush bg-transparent mb-4 text-start">
                        <li class="list-group-item bg-transparent text-white border-secondary d-flex justify-content-between">
                            <span>Projeto</span>
                            <strong>#<?= esc($project->id) ?></strong>
                        </li>
                        <li class="list-group-item bg-transparent text-white border-secondary d-flex justify-content-between">
                            <span>Fotos Extras Selecionadas</span>
                            <strong><?= esc($extraPhotos) ?></strong>
                        </li>
                        <li class="list-group-item bg-transparent text-white border-secondary d-flex justify-content-between">
                            <span>Valor Total Extra</span>
                            <strong class="text-gold fs-5">R$ <?= number_format($total, 2, ',', '.') ?></strong>
                        </li>
                    </ul>

                    <p class="text-muted small mb-4">Ao clicar abaixo, você será redirecionado para o MercadoPago para concluir o pagamento de forma segura via Pix ou Cartão de Crédito.</p>

                    <form action="#" method="post">
                        <!-- Em produção, isso redireciona para a URL do preference->init_point do MercadoPago -->
                        <button type="submit" class="btn btn-terroso btn-lg w-100 mb-3">Pagar com MercadoPago</button>
                    </form>
                    
                    <a href="<?= site_url('client/galeria/' . $project->id) ?>" class="text-muted text-decoration-none small">Voltar para a Galeria</a>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
