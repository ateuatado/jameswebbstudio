<?= $this->extend('layout/main') ?>

<?= $this->section('styles') ?>
<style>
    .waiting-room {
        min-height: 100vh;
        background: #000;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 60px 24px;
    }
    .waiting-card {
        max-width: 560px;
        text-align: center;
    }
    .waiting-card .label {
        font-family: 'Inter', sans-serif;
        font-size: .7rem;
        letter-spacing: .25em;
        text-transform: uppercase;
        color: rgba(197,160,89,.6);
        margin-bottom: 12px;
    }
    .waiting-card h1 {
        font-family: 'EB Garamond', Georgia, serif;
        font-size: clamp(2rem, 5vw, 3rem);
        color: #fff;
        font-weight: 400;
        margin-bottom: 16px;
    }
    .waiting-card .subtitle {
        font-family: 'EB Garamond', Georgia, serif;
        font-style: italic;
        color: rgba(255,255,255,.5);
        font-size: 1.1rem;
        margin-bottom: 32px;
        line-height: 1.6;
    }
    .waiting-card .package-name {
        color: #C5A059;
        font-weight: 600;
    }

    /* ── Spinner de pulse ── */
    .pulse-ring {
        display: inline-block;
        position: relative;
        width: 80px;
        height: 80px;
        margin-bottom: 32px;
    }
    .pulse-ring .dot {
        position: absolute;
        top: 50%;
        left: 50%;
        width: 16px;
        height: 16px;
        background: #C5A059;
        border-radius: 50%;
        transform: translate(-50%, -50%);
    }
    .pulse-ring .ring {
        position: absolute;
        top: 0; left: 0;
        width: 100%; height: 100%;
        border: 2px solid rgba(197,160,89,.4);
        border-radius: 50%;
        animation: pulseRing 2s ease-out infinite;
    }
    .pulse-ring .ring:nth-child(2) { animation-delay: 0.6s; }
    .pulse-ring .ring:nth-child(3) { animation-delay: 1.2s; }
    @keyframes pulseRing {
        0%   { transform: scale(0.3); opacity: 1; }
        100% { transform: scale(1);   opacity: 0; }
    }

    /* ── Estado: Aprovado ── */
    .check-icon {
        display: none;
        font-size: 4rem;
        margin-bottom: 24px;
        animation: checkBounce 0.6s ease;
    }
    @keyframes checkBounce {
        0%   { transform: scale(0); opacity: 0; }
        50%  { transform: scale(1.2); }
        100% { transform: scale(1); opacity: 1; }
    }

    /* ── Progresso visual ── */
    .progress-bar-container {
        width: 100%;
        max-width: 300px;
        height: 3px;
        background: rgba(255,255,255,.08);
        border-radius: 2px;
        margin: 0 auto 24px;
        overflow: hidden;
    }
    .progress-bar-fill {
        width: 0%;
        height: 100%;
        background: linear-gradient(90deg, #C5A059, #F5E27A);
        border-radius: 2px;
        transition: width 3s linear;
    }

    /* ── Botão CTA ── */
    .btn-agenda {
        display: none;
        background: linear-gradient(135deg, #C5A059, #F5E27A);
        color: #000;
        text-decoration: none;
        padding: 16px 40px;
        font-family: 'Inter', sans-serif;
        font-size: .72rem;
        font-weight: 600;
        letter-spacing: .2em;
        text-transform: uppercase;
        border: none;
        cursor: pointer;
        transition: all 0.3s;
        animation: fadeSlideUp 0.6s ease;
    }
    .btn-agenda:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 30px rgba(197,160,89,.3);
        color: #000;
    }
    @keyframes fadeSlideUp {
        0%   { opacity: 0; transform: translateY(20px); }
        100% { opacity: 1; transform: translateY(0); }
    }

    /* ── Timeout fallback ── */
    .timeout-msg {
        display: none;
        margin-top: 32px;
        padding: 24px;
        border: 1px solid rgba(197,160,89,.2);
        text-align: center;
    }
    .timeout-msg p {
        font-family: 'Inter', sans-serif;
        font-size: .85rem;
        color: rgba(255,255,255,.6);
        margin: 0 0 12px;
        line-height: 1.6;
    }
    .timeout-msg a {
        color: #C5A059;
        text-decoration: none;
        font-weight: 500;
    }
    .timeout-msg a:hover { text-decoration: underline; }

    /* ── Btn de voltar ── */
    .btn-back {
        display: inline-block;
        background: transparent;
        border: 1px solid rgba(197,160,89,.4);
        color: #C5A059;
        padding: 14px 40px;
        font-family: 'Inter', sans-serif;
        font-size: .7rem;
        letter-spacing: .2em;
        text-transform: uppercase;
        text-decoration: none;
        transition: all .2s;
        margin-top: 16px;
    }
    .btn-back:hover {
        background: rgba(197,160,89,.1);
        color: #C5A059;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="waiting-room">
    <div class="waiting-card">

        <?php $status = $status ?? 'success'; ?>
        <?php $orderId = $orderId ?? 0; ?>

        <?php if ($status === 'falha'): ?>
            <!-- ── FALHA ── -->
            <div style="font-size:3rem;margin-bottom:24px;">😔</div>
            <p class="label">PAGAMENTO NÃO CONCLUÍDO</p>
            <h1>Algo não saiu como esperado</h1>
            <p class="subtitle">
                Ocorreu um problema com o pagamento. Você pode tentar novamente ou entrar em contato.
            </p>
            <a href="/" class="btn-back">VOLTAR AO INÍCIO</a>

        <?php else: ?>
            <!-- ── SALA DE ESPERA (success ou pendente) ── -->

            <!-- Estado: Processando -->
            <div id="stateProcessing">
                <div class="pulse-ring">
                    <div class="ring"></div>
                    <div class="ring"></div>
                    <div class="ring"></div>
                    <div class="dot"></div>
                </div>
                <p class="label">PROCESSANDO PAGAMENTO</p>
                <h1 id="processingTitle">
                    <?= $nome ? 'Aguarde, ' . esc($nome) . '...' : 'Confirmando pagamento...' ?>
                </h1>
                <p class="subtitle">
                    <?php if ($pacote): ?>
                        Seu ensaio <span class="package-name"><?= esc($pacote) ?></span> está quase confirmado.<br>
                    <?php endif; ?>
                    Estamos verificando o pagamento com o banco. Isso leva apenas alguns segundos.
                </p>
                <div class="progress-bar-container">
                    <div class="progress-bar-fill" id="progressFill"></div>
                </div>
            </div>

            <!-- Estado: Aprovado (hidden por padrão) -->
            <div id="stateApproved" style="display:none;">
                <div class="check-icon" id="checkIcon" style="display:block;">✨</div>
                <p class="label" style="color:#C5A059;">PAGAMENTO CONFIRMADO</p>
                <h1><?= $nome ? 'Parabéns, ' . esc($nome) . '!' : 'Pagamento confirmado!' ?></h1>
                <p class="subtitle">
                    <?php if ($pacote): ?>
                        Seu ensaio <span class="package-name"><?= esc($pacote) ?></span> está confirmado!<br>
                    <?php endif; ?>
                    Redirecionando para o agendamento...
                </p>
                <a href="#" id="btnAgenda" class="btn-agenda" style="display:inline-block;">
                    AGENDAR MEU ENSAIO →
                </a>
            </div>

            <!-- Timeout fallback -->
            <div id="timeoutMsg" class="timeout-msg">
                <p>⏳ O banco ainda está processando o pagamento.</p>
                <p>Não se preocupe! Assim que for confirmado, você receberá um <strong>e-mail com o link de agendamento</strong>.</p>
                <p>Caso precise de ajuda, entre em contato pelo <a href="https://wa.me/5511999999999" target="_blank">WhatsApp</a>.</p>
                <a href="/" class="btn-back" style="margin-top:24px;">VOLTAR AO INÍCIO</a>
            </div>

        <?php endif; ?>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<?php if (($status ?? '') !== 'falha'): ?>
<script>
(function() {
    const orderId    = <?= (int) ($orderId ?? 0) ?>;
    const startTime  = Date.now();
    const TIMEOUT_MS = 180000; // 3 minutos
    const POLL_MS    = 3000;   // a cada 3 segundos

    // Elementos
    const stateProcessing = document.getElementById('stateProcessing');
    const stateApproved   = document.getElementById('stateApproved');
    const btnAgenda       = document.getElementById('btnAgenda');
    const timeoutMsg      = document.getElementById('timeoutMsg');
    const progressFill    = document.getElementById('progressFill');

    if (!orderId) {
        // Sem order_id, não tem como fazer polling
        // Exibe mensagem de timeout diretamente
        if (stateProcessing) stateProcessing.style.display = 'none';
        if (timeoutMsg) timeoutMsg.style.display = 'block';
        return;
    }

    // Animação de progresso — avança suavemente
    let progressPercent = 0;
    function advanceProgress() {
        progressPercent = Math.min(progressPercent + 5, 90);
        if (progressFill) progressFill.style.width = progressPercent + '%';
    }

    function showApproved(agendaLink) {
        // Preenche a barra até 100%
        if (progressFill) {
            progressFill.style.transition = 'width 0.5s ease';
            progressFill.style.width = '100%';
        }

        setTimeout(function() {
            if (stateProcessing) stateProcessing.style.display = 'none';
            if (stateApproved)   stateApproved.style.display = 'block';

            if (agendaLink && btnAgenda) {
                btnAgenda.href = agendaLink;
                // Redireciona automaticamente após 3 segundos
                setTimeout(function() {
                    window.location.href = agendaLink;
                }, 3000);
            }
        }, 600);
    }

    function showTimeout() {
        if (stateProcessing) stateProcessing.style.display = 'none';
        if (timeoutMsg)      timeoutMsg.style.display = 'block';
    }

    function pollStatus() {
        // Verifica timeout
        if (Date.now() - startTime > TIMEOUT_MS) {
            showTimeout();
            return;
        }

        advanceProgress();

        fetch('/ensaio/status/' + orderId, {
            method: 'GET',
            headers: { 'Accept': 'application/json' }
        })
        .then(function(res) { return res.json(); })
        .then(function(data) {
            if (data.status === 'approved') {
                showApproved(data.agenda_link);
            } else if (data.status === 'cancelled' || data.status === 'refunded') {
                // Pagamento falhou
                if (stateProcessing) stateProcessing.style.display = 'none';
                window.location.href = '/ensaio/falha';
            } else {
                // Ainda pendente — tenta de novo
                setTimeout(pollStatus, POLL_MS);
            }
        })
        .catch(function() {
            // Erro de rede — tenta de novo
            setTimeout(pollStatus, POLL_MS);
        });
    }

    // Inicia o polling
    setTimeout(pollStatus, 1000);
})();
</script>
<?php endif; ?>
<?= $this->endSection() ?>
