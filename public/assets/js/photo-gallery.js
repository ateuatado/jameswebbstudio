(() => {
    const storageKey = 'jws-photo-frame-theme';
    const root = document.documentElement;
    const buttons = document.querySelectorAll('[data-frame-theme]');

    const setTheme = (theme) => {
        if (theme === 'graphite') root.removeAttribute('data-frame-theme');
        else root.dataset.frameTheme = theme;
        buttons.forEach((button) => button.setAttribute('aria-pressed', String(button.dataset.frameTheme === theme)));
        localStorage.setItem(storageKey, theme);
    };

    const savedTheme = localStorage.getItem(storageKey);
    if (savedTheme && ['graphite', 'black', 'brown', 'white'].includes(savedTheme)) setTheme(savedTheme);
    buttons.forEach((button) => button.addEventListener('click', () => setTheme(button.dataset.frameTheme)));

    const printOption = document.querySelector('[data-print-option]');
    const price = document.querySelector('[data-print-price]');
    const interest = document.querySelector('[data-print-interest]');
    const buy = document.querySelector('[data-photo-buy]');
    const quote = document.querySelector('[data-photo-quote]');
    const whatsappNumber = '5511964322103';
    const whatsappUrl = (message) => `https://wa.me/${whatsappNumber}?text=${encodeURIComponent(message)}`;
    const photoName = document.title.replace(/\s*\|\s*Fotos\s*\|.*$/, '');
    const updatePurchaseLinks = (selected = null) => {
        const size = selected?.dataset.label || 'tamanho personalizado';
        const cents = Number(selected?.value || 0);
        const formattedPrice = cents ? new Intl.NumberFormat('pt-BR', { style: 'currency', currency: 'BRL' }).format(cents / 100) : '';
        if (buy) buy.href = whatsappUrl(`Olá! Quero comprar a edição "${photoName}" no tamanho ${size}${formattedPrice ? `, por ${formattedPrice}` : ''}. Link: ${window.location.href}`);
        if (quote) quote.href = whatsappUrl(`Olá! Gostaria de pedir um orçamento para a fotografia "${photoName}". Link: ${window.location.href}`);
    };
    const updateSpecifications = (selected) => {
        const values = {
            'print-material': selected.dataset.printMaterial,
            'frame-material': selected.dataset.frameMaterial,
            'backing-material': selected.dataset.backingMaterial,
            glazing: selected.dataset.glazing,
            weight: selected.dataset.weight ? `${selected.dataset.weight} g` : '',
            'lead-time': selected.dataset.leadTime,
        };
        Object.entries(values).forEach(([key, value]) => {
            const row = document.querySelector(`[data-spec-row="${key}"]`);
            const output = document.querySelector(`[data-spec="${key}"]`);
            if (row && output) {
                output.textContent = value || '';
                row.hidden = !value;
            }
        });
        const container = document.querySelector('[data-print-specifications]');
        if (container) container.hidden = !Object.values(values).some(Boolean);
    };
    if (interest) {
        interest.href = `mailto:contato@jameswebbstudio.com.br?subject=${encodeURIComponent(`Interesse em uma edição — ${document.title}`)}`;
    }
    if (printOption && price && interest) {
        const updatePrintOption = () => {
            const selected = printOption.options[printOption.selectedIndex];
            const cents = Number(selected.value);
            price.textContent = new Intl.NumberFormat('pt-BR', { style: 'currency', currency: 'BRL' }).format(cents / 100);
            const subject = `Interesse na edição ${selected.dataset.label} — ${document.title}`;
            interest.href = `mailto:contato@jameswebbstudio.com.br?subject=${encodeURIComponent(subject)}`;
            updatePurchaseLinks(selected);
            updateSpecifications(selected);
        };
        updatePrintOption();
        printOption.addEventListener('change', updatePrintOption);
    }
    updatePurchaseLinks(printOption ? printOption.options[printOption.selectedIndex] : null);

    const shareTrigger = document.querySelector('[data-share-trigger]');
    const shareOptions = document.querySelector('[data-share-options]');
    const shareFeedback = document.querySelector('[data-share-feedback]');
    if (shareTrigger && shareOptions) {
        const url = document.querySelector('[data-share-url]')?.dataset.shareUrl || window.location.href;
        const title = document.title;
        const text = 'Conheça esta fotografia autoral do James Webb Studio.';
        const setShareLink = (selector, value) => {
            const link = document.querySelector(selector);
            if (link) link.href = value;
        };

        setShareLink('[data-share-whatsapp]', `https://wa.me/?text=${encodeURIComponent(`${text} ${url}`)}`);
        setShareLink('[data-share-facebook]', `https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(url)}`);
        setShareLink('[data-share-x]', `https://twitter.com/intent/tweet?text=${encodeURIComponent(text)}&url=${encodeURIComponent(url)}`);

        shareTrigger.addEventListener('click', () => {
            const expanded = shareTrigger.getAttribute('aria-expanded') === 'true';
            shareTrigger.setAttribute('aria-expanded', String(!expanded));
            shareOptions.hidden = expanded;
        });

        const nativeShare = document.querySelector('[data-share-native]');
        if (nativeShare) nativeShare.addEventListener('click', async () => {
            if (!navigator.share) {
                if (shareFeedback) shareFeedback.textContent = 'Escolha uma das opções de compartilhamento acima.';
                return;
            }
            try {
                await navigator.share({ title, text, url });
            } catch (_) {
                // Cancelar o menu nativo não é um erro a ser exibido ao visitante.
            }
        });

        const copyShare = document.querySelector('[data-share-copy]');
        if (copyShare) copyShare.addEventListener('click', async () => {
            try {
                await navigator.clipboard.writeText(url);
                if (shareFeedback) shareFeedback.textContent = 'Link copiado.';
            } catch (_) {
                if (shareFeedback) shareFeedback.textContent = 'Não foi possível copiar automaticamente; use a barra de endereço.';
            }
        });
    }

    document.addEventListener('keydown', (event) => {
        if (event.target instanceof Element && event.target.matches('input, textarea, select, button')) return;
        const selector = event.key === 'ArrowLeft' ? '[data-gallery-previous]' : event.key === 'ArrowRight' ? '[data-gallery-next]' : null;
        const link = selector ? document.querySelector(selector) : null;
        if (link) window.location.href = link.href;
    });
})();
