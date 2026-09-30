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
    const photoShareUrl = document.querySelector('[data-share-url]')?.dataset.shareUrl || window.location.href;
    const photoName = document.title.replace(/\s*\|\s*Fotos\s*\|.*$/, '');
    const updatePurchaseLinks = () => {
        if (buy) buy.href = whatsappUrl(`Olá, Marco Santo! Gostei muito deste quadro e gostaria de conversar com você sobre ele.\n\nLink: ${photoShareUrl}`);
        const quoteText = quote?.dataset.photoQuoteIntent === 'information'
            ? `Olá! Gostaria de obter mais informações sobre a fotografia "${photoName}". Link: ${photoShareUrl}`
            : `Olá! Gostaria de pedir um orçamento para a fotografia "${photoName}". Link: ${photoShareUrl}`;
        if (quote) quote.href = whatsappUrl(quoteText);
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
        const url = photoShareUrl;
        const imageUrl = document.querySelector('[data-share-image]')?.dataset.shareImage || '';
        const shareRoot = document.querySelector('[data-share-url]');
        const whatsappShareUrl = shareRoot?.dataset.shareWhatsappUrl || url;
        const facebookShareUrl = shareRoot?.dataset.shareFacebookUrl || url;
        const xShareUrl = shareRoot?.dataset.shareXUrl || url;
        const title = document.title;
        const text = 'Conheça esta fotografia autoral do James Webb Studio.';
        const setShareLink = (selector, value) => {
            const link = document.querySelector(selector);
            if (link) link.href = value;
        };

        const whatsappLink = `https://wa.me/?text=${encodeURIComponent(`${text} ${whatsappShareUrl}`)}`;
        const whatsappButton = document.querySelector('[data-share-whatsapp]');
        setShareLink('[data-share-whatsapp]', whatsappLink);
        setShareLink('[data-share-facebook]', `https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(facebookShareUrl)}`);
        setShareLink('[data-share-x]', `https://twitter.com/intent/tweet?text=${encodeURIComponent(text)}&url=${encodeURIComponent(xShareUrl)}`);

        // Em celulares compatíveis, os botões de WhatsApp compartilham a
        // própria foto como mídia. No desktop, o link wa.me continua sendo o
        // fallback e aparece como uma prévia compacta.
        const shareAsMedia = (button) => {
            if (!button || !imageUrl) return;
            button.addEventListener('click', async (event) => {
                if (!navigator.share || !navigator.canShare) return;
                event.preventDefault();
                const fallbackUrl = button.href;
                let shareText = text;
                try {
                    shareText = new URL(fallbackUrl).searchParams.get('text') || text;
                } catch (_) {
                    // Mantém o texto padrão se o link ainda não estiver pronto.
                }
                try {
                    const response = await fetch(imageUrl, { credentials: 'same-origin' });
                    if (!response.ok) throw new Error('Não foi possível carregar a fotografia.');
                    const blob = await response.blob();
                    const file = new File([blob], 'fotografia.jpg', { type: blob.type || 'image/jpeg' });
                    if (!navigator.canShare({ files: [file] })) throw new Error('Compartilhamento de arquivos não suportado.');
                    await navigator.share({ files: [file], title, text: shareText });
                } catch (error) {
                    if (error?.name !== 'AbortError') window.open(fallbackUrl, '_blank', 'noopener');
                }
            });
        };
        shareAsMedia(whatsappButton);
        shareAsMedia(buy);
        shareAsMedia(quote);

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
