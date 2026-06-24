/**
 * pricing-accordion.js
 * Acordeões da página /investimento:
 *   - Descrição de categoria (clique no toggle do cabeçalho)
 *   - Descrição de pacote (clique no toggle dentro do card)
 *
 * Sem dependências externas. Sem PHP. Sem colisões.
 */
(function () {
    'use strict';

    /**
     * Abre/fecha um acordeão genérico.
     * @param {HTMLElement} toggle  - botão toggle
     * @param {HTMLElement} body    - div colapsável
     */
    function toggleAccordion(toggle, body) {
        const isOpen = body.classList.contains('open');
        body.classList.toggle('open', !isOpen);
        toggle.classList.toggle('open', !isOpen);
    }

    document.addEventListener('DOMContentLoaded', function () {

        // ── Acordeões de Categoria ────────────────────────────────────────
        document.querySelectorAll('.cat-accordion-toggle').forEach(function (btn) {
            var bodyId = btn.getAttribute('data-target');
            var body   = document.getElementById(bodyId);
            if (!body) return;

            btn.addEventListener('click', function () {
                toggleAccordion(btn, body);
                btn.querySelector('.acc-label').textContent =
                    body.classList.contains('open') ? 'ocultar' : 'saiba mais';
            });
        });

        // ── Acordeões de Pacote ───────────────────────────────────────────
        document.querySelectorAll('.pkg-acc-toggle').forEach(function (btn) {
            var bodyId = btn.getAttribute('data-target');
            var body   = document.getElementById(bodyId);
            if (!body) return;

            btn.addEventListener('click', function () {
                toggleAccordion(btn, body);
                btn.querySelector('.pkg-acc-label').textContent =
                    body.classList.contains('open') ? 'ocultar' : 'sobre este pacote';
            });
        });

    });
})();
