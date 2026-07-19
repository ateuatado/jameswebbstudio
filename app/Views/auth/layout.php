<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
    <!-- Espaçamento no topo para não ficar oculto sob a navbar fixa -->
    <div style="padding-top: 100px; min-height: 80vh;">
        <?= $this->renderSection('main') ?>
    </div>
<?= $this->endSection() ?>
