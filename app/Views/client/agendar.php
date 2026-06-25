<?= $this->extend('layout/main') ?>

<?= $this->section('styles') ?>
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
<style>
    .booking-page { margin-top: 80px; min-height: 80vh; }

    .booking-header {
        max-width: 760px;
        margin: 0 auto;
        padding: 2.5rem 1.5rem 0;
        text-align: center;
    }
    .booking-header .breadcrumb {
        font-family: 'Inter', sans-serif;
        font-size: .7rem;
        letter-spacing: .15em;
        text-transform: uppercase;
        color: rgba(255,255,255,.3);
        margin-bottom: 1.5rem;
    }
    .booking-header .breadcrumb a {
        color: rgba(197,160,89,.6);
        text-decoration: none;
    }
    .booking-header .breadcrumb a:hover { color: #C5A059; }
    .booking-header h1 {
        font-family: 'EB Garamond', Georgia, serif;
        font-size: 1.8rem;
        font-weight: 400;
        color: #fff;
        margin: 0 0 8px;
    }
    .booking-header p {
        font-family: 'Inter', sans-serif;
        font-size: .82rem;
        color: rgba(255,255,255,.4);
        margin: 0 0 1.5rem;
    }
    .booking-package-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        border: 1px solid rgba(197,160,89,.25);
        padding: 8px 20px;
        font-family: 'Inter', sans-serif;
        font-size: .68rem;
        letter-spacing: .15em;
        text-transform: uppercase;
        color: #C5A059;
        margin-bottom: 2rem;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="booking-page">

    <div class="booking-header">
        <p class="breadcrumb">
            <a href="<?= site_url('client/meus-ensaios') ?>">← Meus Ensaios</a>
            &nbsp;/&nbsp; Agendar Data
        </p>
        <h1>Escolha sua Data</h1>
        <p>Selecione o melhor dia e horário para o seu ensaio fotográfico</p>
        <?php if (!empty($packageName)): ?>
            <div class="booking-package-badge">
                <i class="fas fa-camera"></i>
                <?= esc($packageName) ?>
            </div>
        <?php endif ?>
    </div>

    <?php
        // Variáveis para o widget — sem hero (página pública), mas com prefill do cliente
        $hero = ['name' => ''];
        // As variáveis $agendaPrefillName/Email/Phone/$agendaOrderId
        // já foram definidas pelo controller
    ?>
    <?= $this->include('partials/agenda_widget') ?>

</div>

<?= $this->endSection() ?>
