<?php
/**
 * Widget de Agendamento — integração com agenda.test
 * Variáveis esperadas: $hero (array), $cta (array|null)
 */
$agendaBaseUrl = 'https://agenda.test';
$heroContext   = esc($hero['name'] ?? '');
?>

<!-- ========================================================
     AGENDA WIDGET
     ======================================================== -->
<section id="agenda-widget" class="agenda-section">
    <div class="agenda-wrap">

        <div class="agenda-header">
            <h2>Agende seu Ensaio</h2>
            <p class="agenda-subtitle">Escolha a melhor data para imortalizarmos sua história.</p>
        </div>

        <!-- Navegação do calendário -->
        <div class="agenda-calendar-nav">
            <button id="agenda-prev-month" class="agenda-nav-btn" aria-label="Mês anterior">&#8592;</button>
            <span id="agenda-month-label" class="agenda-month-label">Carregando...</span>
            <button id="agenda-next-month" class="agenda-nav-btn" aria-label="Próximo mês">&#8594;</button>
        </div>

        <!-- Grade do calendário -->
        <div class="agenda-calendar-wrap">
            <div class="agenda-weekdays">
                <span>Dom</span><span>Seg</span><span>Ter</span>
                <span>Qua</span><span>Qui</span><span>Sex</span><span>Sáb</span>
            </div>
            <div id="agenda-grid" class="agenda-grid">
                <div class="agenda-loading">
                    <div class="agenda-spinner"></div>
                </div>
            </div>
        </div>

        <p class="agenda-legend">
            <span class="leg-dot available"></span> Disponível &nbsp;
            <span class="leg-dot booked"></span> Ocupado
        </p>
    </div>
</section>

<!-- ========================================================
     MODAL — Horários disponíveis
     ======================================================== -->
<div id="agenda-modal-overlay" class="agenda-modal-overlay" role="dialog" aria-modal="true" aria-labelledby="agenda-modal-title">
    <div class="agenda-modal">
        <button id="agenda-modal-close" class="agenda-modal-close" aria-label="Fechar">&times;</button>
        <h3 id="agenda-modal-title" class="agenda-modal-title"></h3>

        <!-- Passo 1: Escolher horário -->
        <div id="agenda-step-slots">
            <p class="agenda-modal-hint">Selecione um horário disponível:</p>
            <div id="agenda-slots-list" class="agenda-slots-list"></div>
        </div>

        <!-- Passo 2: Formulário de dados -->
        <div id="agenda-step-form" class="agenda-hidden">
            <p class="agenda-modal-hint">Complete seus dados para confirmar:</p>
            <div id="agenda-form-errors" class="agenda-form-errors agenda-hidden"></div>
            <form id="agenda-booking-form" novalidate>
                <input type="hidden" id="agenda-slot-id" name="slot_id">
                <div class="agenda-field">
                    <label for="agenda-name">Nome completo *</label>
                    <input type="text" id="agenda-name" name="name" required autocomplete="name">
                </div>
                <div class="agenda-field">
                    <label for="agenda-email">E-mail *</label>
                    <input type="email" id="agenda-email" name="email" required autocomplete="email">
                </div>
                <div class="agenda-field">
                    <label for="agenda-phone">Telefone *</label>
                    <input type="tel" id="agenda-phone" name="phone" required autocomplete="tel"
                           placeholder="(11) 99999-9999">
                </div>
                <div class="agenda-field">
                    <label for="agenda-notes">Observações <span style="opacity:.5">(opcional)</span></label>
                    <textarea id="agenda-notes" name="notes" rows="2"
                              placeholder="Modalidade, expectativa, dúvidas..."></textarea>
                </div>
                <div class="agenda-form-actions">
                    <button type="button" id="agenda-back-btn" class="agenda-btn-secondary">&#8592; Voltar</button>
                    <button type="submit" id="agenda-submit-btn" class="agenda-btn-primary">
                        <span id="agenda-submit-text">Confirmar Agendamento</span>
                        <span id="agenda-submit-spinner" class="d-none">Enviando...</span>
                    </button>
                </div>
            </form>
        </div>

        <!-- Passo 3: Confirmação -->
        <div id="agenda-step-success" class="agenda-hidden agenda-success">
            <div class="agenda-success-icon">✓</div>
            <h4 class="brand-font">Agendamento Confirmado!</h4>
            <p id="agenda-success-msg"></p>
            <a id="agenda-success-link" href="https://agenda.marcosantofoto.com.br/minha-agenda" target="_blank" class="agenda-btn-primary" style="margin-top:16px;">
                Ver minha agenda
            </a>
        </div>
    </div>
</div>

<!-- ========================================================
     ESTILOS DO WIDGET
     ======================================================== -->
<style>
/* --- Seção principal --- */
.agenda-section {
    background: var(--color-surface, #0a0a0a);
    padding: 80px 0 100px;
    border-top: 1px solid rgba(255,255,255,0.06);
}
.agenda-wrap {
    max-width: 760px;
    margin: 0 auto;
    padding: 0 24px;
}
/* Utilitário — substitui Bootstrap d-none */
.agenda-hidden, [id^="agenda-"].agenda-hidden { display: none !important; }
.agenda-header {
    text-align: center;
    margin-bottom: 48px;
}
.agenda-header h2 {
    font-family: var(--font-serif, 'EB Garamond', Georgia, serif);
    font-size: clamp(1.8rem, 4vw, 3rem);
    color: var(--color-gold, #C5A059);
    margin-bottom: 12px;
    font-weight: 500;
    letter-spacing: 0.03em;
}
.agenda-subtitle {
    color: rgba(255,255,255,0.45);
    font-size: 1rem;
    letter-spacing: 0.05em;
}

/* --- Navegação do mês --- */
.agenda-calendar-nav {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 24px;
    margin-bottom: 24px;
}
.agenda-nav-btn {
    background: transparent;
    border: 1px solid rgba(255,255,255,0.2);
    color: #fff;
    width: 40px; height: 40px;
    border-radius: 50%;
    font-size: 1.1rem;
    cursor: pointer;
    transition: all 0.2s;
}
.agenda-nav-btn:hover {
    background: rgba(255,255,255,0.1);
    border-color: rgba(255,255,255,0.5);
}
.agenda-month-label {
    font-family: var(--font-serif, 'EB Garamond', Georgia, serif);
    font-size: 1.2rem;
    letter-spacing: 2px;
    color: var(--color-gold, #C5A059);
    min-width: 220px;
    text-align: center;
    font-style: italic;
}

/* --- Grade do Calendário --- */
.agenda-calendar-wrap {
    max-width: 700px;
    margin: 0 auto;
}
.agenda-weekdays {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    text-align: center;
    color: rgba(255,255,255,0.3);
    font-size: 0.72rem;
    letter-spacing: 0.1em;
    text-transform: uppercase;
    margin-bottom: 8px;
    padding: 0 4px;
}
.agenda-grid {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    gap: 6px;
    padding: 4px;
    min-height: 220px;
}
.agenda-day {
    aspect-ratio: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 8px;
    font-size: 0.9rem;
    font-weight: 500;
    cursor: default;
    transition: all 0.18s;
    border: 1px solid transparent;
}
.agenda-day.empty {
    background: transparent;
}
.agenda-day.past {
    color: rgba(255,255,255,0.12);
}
.agenda-day.booked {
    background: rgba(255,255,255,0.04);
    color: rgba(255,255,255,0.2);
}
.agenda-day.available {
    background: rgba(197,160,89,0.12);
    color: var(--color-gold, #C5A059);
    border-color: rgba(197,160,89,0.30);
    cursor: pointer;
}
.agenda-day.available:hover {
    background: rgba(197,160,89,0.25);
    border-color: rgba(197,160,89,0.7);
    transform: scale(1.08);
    box-shadow: 0 0 16px rgba(197,160,89,0.2);
}
.agenda-day.today {
    border-color: rgba(255,255,255,0.3);
}

/* --- Loading --- */
.agenda-loading {
    grid-column: 1 / -1;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 60px 0;
}
.agenda-spinner {
    width: 32px; height: 32px;
    border: 2px solid rgba(255,255,255,0.1);
    border-top-color: var(--color-gold, #C5A059);
    border-radius: 50%;
    animation: agenda-spin 0.8s linear infinite;
}
@keyframes agenda-spin { to { transform: rotate(360deg); } }

/* --- Legenda --- */
.agenda-legend {
    text-align: center;
    margin-top: 20px;
    font-size: 0.8rem;
    color: rgba(255,255,255,0.4);
}
.leg-dot {
    display: inline-block;
    width: 10px; height: 10px;
    border-radius: 50%;
    margin-right: 4px;
    vertical-align: middle;
}
.leg-dot.available { background: var(--color-gold, #C5A059); }
.leg-dot.booked    { background: rgba(255,255,255,0.15); }

/* --- Modal overlay --- */
.agenda-modal-overlay {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,0.82);
    backdrop-filter: blur(6px);
    z-index: 9999;
    align-items: center;
    justify-content: center;
    padding: 20px;
}
.agenda-modal-overlay.open {
    display: flex;
}
.agenda-modal {
    background: #0d0d0d;
    border: 1px solid rgba(197,160,89,0.2);
    border-radius: 4px;
    padding: 40px 36px;
    width: 100%;
    max-width: 480px;
    position: relative;
    animation: agenda-modal-in 0.25s ease;
}
@keyframes agenda-modal-in {
    from { opacity:0; transform: translateY(20px) scale(0.97); }
    to   { opacity:1; transform: translateY(0)   scale(1); }
}
.agenda-modal-close {
    position: absolute;
    top: 16px; right: 20px;
    background: transparent;
    border: none;
    color: rgba(255,255,255,0.4);
    font-size: 1.6rem;
    cursor: pointer;
    line-height: 1;
    transition: color 0.2s;
}
.agenda-modal-close:hover { color: #fff; }
.agenda-modal-title {
    font-family: var(--font-serif, 'EB Garamond', Georgia, serif);
    font-size: 1.2rem;
    font-style: italic;
    letter-spacing: 0.05em;
    color: var(--color-gold, #C5A059);
    margin-bottom: 20px;
}
.agenda-modal-hint {
    color: rgba(255,255,255,0.5);
    font-size: 0.85rem;
    margin-bottom: 16px;
}

/* --- Lista de slots --- */
.agenda-slots-list {
    display: flex;
    flex-direction: column;
    gap: 10px;
    max-height: 320px;
    overflow-y: auto;
}
.agenda-slot-btn {
    background: rgba(255,255,255,0.04);
    border: 1px solid rgba(255,255,255,0.1);
    border-radius: 10px;
    color: #fff;
    padding: 14px 20px;
    text-align: left;
    cursor: pointer;
    transition: all 0.18s;
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.agenda-slot-btn:hover {
    background: rgba(197,160,89,0.12);
    border-color: rgba(197,160,89,0.4);
}
.agenda-slot-time { font-weight: 600; font-size: 1rem; }
.agenda-slot-type { font-size: 0.78rem; color: rgba(255,255,255,0.4); }

/* --- Formulário --- */
.agenda-field {
    margin-bottom: 16px;
}
.agenda-field label {
    display: block;
    font-size: 0.78rem;
    color: rgba(255,255,255,0.5);
    margin-bottom: 6px;
    letter-spacing: 0.05em;
    text-transform: uppercase;
}
.agenda-field input,
.agenda-field textarea {
    width: 100%;
    background: rgba(255,255,255,0.05);
    border: 1px solid rgba(255,255,255,0.12);
    border-radius: 8px;
    color: #fff;
    padding: 12px 14px;
    font-size: 0.95rem;
    font-family: inherit;
    transition: border-color 0.2s;
    box-sizing: border-box;
}
.agenda-field input:focus,
.agenda-field textarea:focus {
    outline: none;
    border-color: rgba(212,175,55,0.5);
    background: rgba(212,175,55,0.04);
}
.agenda-field textarea { resize: vertical; }
.agenda-form-errors {
    background: rgba(220,53,69,0.15);
    border: 1px solid rgba(220,53,69,0.3);
    border-radius: 8px;
    padding: 12px 16px;
    color: #ff8a8a;
    font-size: 0.85rem;
    margin-bottom: 16px;
}
.agenda-form-actions {
    display: flex;
    gap: 12px;
    margin-top: 20px;
}
.agenda-btn-primary {
    flex: 1;
    background: var(--color-gold, #C5A059);
    color: #000;
    border: none;
    border-radius: 0;
    padding: 14px 20px;
    font-family: var(--font-sans, 'Inter', sans-serif);
    font-size: 0.72rem;
    font-weight: 500;
    letter-spacing: 0.18em;
    text-transform: uppercase;
    cursor: pointer;
    transition: all 0.2s;
    text-decoration: none;
    text-align: center;
    display: inline-block;
}
.agenda-btn-primary:hover { background: #d4b06a; transform: translateY(-1px); }
.agenda-btn-primary:disabled { opacity: 0.5; cursor: not-allowed; }
.agenda-btn-secondary {
    background: transparent;
    border: 1px solid rgba(255,255,255,0.15);
    color: rgba(255,255,255,0.5);
    border-radius: 0;
    padding: 14px 16px;
    cursor: pointer;
    transition: all 0.2s;
    font-family: var(--font-sans, 'Inter', sans-serif);
    font-size: 0.72rem;
    letter-spacing: 0.1em;
    text-transform: uppercase;
}
.agenda-btn-secondary:hover {
    border-color: rgba(255,255,255,0.5);
    color: #fff;
}

/* --- Sucesso --- */
.agenda-success {
    text-align: center;
    padding: 20px 0;
}
.agenda-success-icon {
    width: 64px; height: 64px;
    border-radius: 50%;
    border: 1px solid var(--color-gold, #C5A059);
    color: var(--color-gold, #C5A059);
    font-size: 2rem;
    display: flex; align-items: center; justify-content: center;
    margin: 0 auto 20px;
    animation: agenda-pop 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
}
@keyframes agenda-pop {
    from { transform: scale(0); opacity: 0; }
    to   { transform: scale(1); opacity: 1; }
}
.agenda-success h4 {
    font-family: var(--font-serif, 'EB Garamond', Georgia, serif);
    font-size: 1.4rem;
    color: var(--color-gold, #C5A059);
    margin-bottom: 12px;
    font-style: italic;
}
.agenda-success p { color: rgba(255,255,255,0.5); font-size: 0.9rem; }
</style>

<!-- ========================================================
     JAVASCRIPT DO WIDGET
     ======================================================== -->
<script>
(function () {
    const AGENDA_BASE = '';
    // Proxy: hero.test/agenda-api/* → agenda.test/api/v1/* (server-side, sem CORS)
    const HERO_NAME   = '<?= $heroContext ?>';

    // Estado
    let currentYear  = new Date().getFullYear();
    let currentMonth = new Date().getMonth() + 1;
    let availableDates = {};  // { 'YYYY-MM-DD': [{id, start_time, session_type_name}, ...] }
    let selectedSlot = null;

    // Elementos
    const grid       = document.getElementById('agenda-grid');
    const monthLabel = document.getElementById('agenda-month-label');
    const overlay    = document.getElementById('agenda-modal-overlay');
    const modalTitle = document.getElementById('agenda-modal-title');
    const slotsList  = document.getElementById('agenda-slots-list');
    const stepSlots  = document.getElementById('agenda-step-slots');
    const stepForm   = document.getElementById('agenda-step-form');
    const stepSuccess= document.getElementById('agenda-step-success');
    const form       = document.getElementById('agenda-booking-form');
    const slotIdInput= document.getElementById('agenda-slot-id');
    const formErrors = document.getElementById('agenda-form-errors');
    const submitBtn  = document.getElementById('agenda-submit-btn');
    const submitText = document.getElementById('agenda-submit-text');
    const submitSpinner = document.getElementById('agenda-submit-spinner');

    const MONTH_NAMES = [
        'Janeiro','Fevereiro','Março','Abril','Maio','Junho',
        'Julho','Agosto','Setembro','Outubro','Novembro','Dezembro'
    ];

    // --- Carregar disponibilidade do mês ---
    async function loadMonth(year, month) {
        grid.innerHTML = '<div class="agenda-loading"><div class="agenda-spinner"></div></div>';
        monthLabel.textContent = MONTH_NAMES[month - 1] + ' ' + year;

        try {
            const res = await fetch(`/agenda-api/availability?year=${year}&month=${month}`);
            if (!res.ok) throw new Error('Resposta inválida');
            const data = await res.json();

            // data.data é array de slots [{date, start_time, id, session_type_name, status}, ...]
            availableDates = {};
            (data.data || []).forEach(slot => {
                if (!availableDates[slot.date]) availableDates[slot.date] = [];
                availableDates[slot.date].push(slot);
            });

            renderCalendar(year, month);
        } catch (e) {
            grid.innerHTML = '<p style="color:rgba(255,255,255,0.3);text-align:center;grid-column:1/-1;padding:40px 0;">Não foi possível carregar a disponibilidade.<br>Tente novamente em instantes.</p>';
        }
    }

    // --- Renderizar grade do calendário ---
    function renderCalendar(year, month) {
        const today     = new Date();
        const firstDay  = new Date(year, month - 1, 1).getDay(); // 0=dom
        const daysTotal = new Date(year, month, 0).getDate();

        let html = '';

        // Células vazias antes do dia 1
        for (let i = 0; i < firstDay; i++) {
            html += '<div class="agenda-day empty"></div>';
        }

        for (let d = 1; d <= daysTotal; d++) {
            const dateStr = `${year}-${String(month).padStart(2,'0')}-${String(d).padStart(2,'0')}`;
            const cellDate = new Date(year, month - 1, d);
            const isPast   = cellDate < new Date(today.getFullYear(), today.getMonth(), today.getDate());
            const isToday  = cellDate.toDateString() === today.toDateString();
            const hasSlots = availableDates[dateStr] && availableDates[dateStr].length > 0;

            let cls = 'agenda-day';
            if (isPast)     cls += ' past';
            else if (hasSlots) cls += ' available';
            else            cls += ' booked';
            if (isToday)    cls += ' today';

            const clickable = hasSlots && !isPast;
            html += `<div class="${cls}" ${clickable ? `data-date="${dateStr}" role="button" tabindex="0" aria-label="Disponível em ${d} de ${MONTH_NAMES[month-1]}"` : ''}>${d}</div>`;
        }

        grid.innerHTML = html;

        // Eventos nos dias disponíveis
        grid.querySelectorAll('.agenda-day.available[data-date]').forEach(el => {
            el.addEventListener('click', () => openSlots(el.dataset.date));
            el.addEventListener('keydown', e => { if (e.key === 'Enter' || e.key === ' ') openSlots(el.dataset.date); });
        });
    }

    // --- Abrir modal de horários ---
    function openSlots(dateStr) {
        const [y, m, d] = dateStr.split('-');
        const label = `${parseInt(d)} de ${MONTH_NAMES[parseInt(m)-1]} de ${y}`;
        modalTitle.textContent = label;

        const slots = availableDates[dateStr] || [];
        slotsList.innerHTML = slots.map(s => `
            <button class="agenda-slot-btn" data-slot-id="${s.id}" data-slot-label="${label}" data-slot-time="${s.start_time}" data-slot-type="${s.session_type_name || ''}">
                <span class="agenda-slot-time">${s.start_time ? s.start_time.substring(0,5) : ''}</span>
                <span class="agenda-slot-type">${s.session_type_name || 'Ensaio fotográfico'}</span>
            </button>
        `).join('');

        slotsList.querySelectorAll('.agenda-slot-btn').forEach(btn => {
            btn.addEventListener('click', () => selectSlot(btn));
        });

        showStep('slots');
        overlay.classList.add('open');
        document.body.style.overflow = 'hidden';
    }

    // --- Selecionar horário e ir para o form ---
    function selectSlot(btn) {
        selectedSlot = {
            id:    btn.dataset.slotId,
            label: btn.dataset.slotLabel,
            time:  btn.dataset.slotTime,
            type:  btn.dataset.slotType,
        };
        slotIdInput.value = selectedSlot.id;
        modalTitle.textContent = `${selectedSlot.time ? selectedSlot.time.substring(0,5) : ''} — ${selectedSlot.label}`;
        showStep('form');
    }

    // --- Submeter booking via API ---
    form.addEventListener('submit', async function (e) {
        e.preventDefault();
        formErrors.classList.add('agenda-hidden');
        formErrors.textContent = '';
        submitBtn.disabled = true;
        submitText.classList.add('agenda-hidden');
        submitSpinner.classList.remove('agenda-hidden');

        const body = {
            slot_id: selectedSlot.id,
            name:    document.getElementById('agenda-name').value,
            email:   document.getElementById('agenda-email').value,
            phone:   document.getElementById('agenda-phone').value,
            notes:   (document.getElementById('agenda-notes').value || '') +
                     (HERO_NAME ? `\n[Agendado via página do atleta: ${HERO_NAME}]` : ''),
        };

        try {
            const res = await fetch(`/agenda-api/book`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                body: JSON.stringify(body),
            });
            const data = await res.json();

            if (res.ok && data.success === true) {
                const name = body.name.split(' ')[0];
                document.getElementById('agenda-success-msg').textContent =
                    `${name}, seu ensaio foi confirmado para ${selectedSlot.time ? selectedSlot.time.substring(0,5) : ''} em ${selectedSlot.label}. Verifique seu e-mail!`;
                showStep('success');
            } else {
                const msg = data.message || (data.errors ? Object.values(data.errors).join(' ') : 'Erro ao confirmar. Tente novamente.');
                showError(msg);
            }
        } catch (err) {
            showError('Falha na conexão. Verifique sua internet e tente novamente.');
        } finally {
            submitBtn.disabled = false;
            submitText.classList.remove('agenda-hidden');
            submitSpinner.classList.add('agenda-hidden');
        }
    });

    // --- Utilitários ---
    function showStep(step) {
        stepSlots.classList.toggle('agenda-hidden', step !== 'slots');
        stepForm.classList.toggle('agenda-hidden',  step !== 'form');
        stepSuccess.classList.toggle('agenda-hidden', step !== 'success');
    }

    function showError(msg) {
        formErrors.textContent = msg;
        formErrors.classList.remove('agenda-hidden');
    }

    function closeModal() {
        overlay.classList.remove('open');
        document.body.style.overflow = '';
        form.reset();
        formErrors.classList.add('d-none');
        selectedSlot = null;
    }

    // --- Event listeners globais ---
    document.getElementById('agenda-modal-close').addEventListener('click', closeModal);
    overlay.addEventListener('click', e => { if (e.target === overlay) closeModal(); });
    document.addEventListener('keydown', e => { if (e.key === 'Escape') closeModal(); });
    document.getElementById('agenda-back-btn').addEventListener('click', () => showStep('slots'));

    document.getElementById('agenda-prev-month').addEventListener('click', () => {
        currentMonth--;
        if (currentMonth < 1) { currentMonth = 12; currentYear--; }
        loadMonth(currentYear, currentMonth);
    });
    document.getElementById('agenda-next-month').addEventListener('click', () => {
        currentMonth++;
        if (currentMonth > 12) { currentMonth = 1; currentYear++; }
        loadMonth(currentYear, currentMonth);
    });

    // --- Inicializar ---
    loadMonth(currentYear, currentMonth);
})();
</script>
