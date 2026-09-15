(() => {
    const mountBackdrop = () => {
        if (document.querySelector('[data-cb-botanical-backdrop]')) return;

        const backdrop = document.createElement('div');
        backdrop.className = 'cb-botanical-backdrop';
        backdrop.dataset.cbBotanicalBackdrop = '';
        backdrop.setAttribute('aria-hidden', 'true');
        backdrop.innerHTML = `
            <span class="cb-botanical cb-botanical--left">
                <i class="cb-botanical__stem"></i>
                <i class="cb-botanical__leaf cb-botanical__leaf--1"></i>
                <i class="cb-botanical__leaf cb-botanical__leaf--2"></i>
                <i class="cb-botanical__leaf cb-botanical__leaf--3"></i>
                <b class="cb-botanical__bloom cb-botanical__bloom--1"></b>
                <b class="cb-botanical__bloom cb-botanical__bloom--2"></b>
            </span>
            <span class="cb-botanical cb-botanical--right">
                <i class="cb-botanical__stem"></i>
                <i class="cb-botanical__leaf cb-botanical__leaf--1"></i>
                <i class="cb-botanical__leaf cb-botanical__leaf--2"></i>
                <i class="cb-botanical__leaf cb-botanical__leaf--3"></i>
                <b class="cb-botanical__bloom cb-botanical__bloom--1"></b>
                <b class="cb-botanical__bloom cb-botanical__bloom--2"></b>
            </span>
            <i class="cb-petal"></i><i class="cb-petal"></i><i class="cb-petal"></i><i class="cb-petal"></i><i class="cb-petal"></i>
        `;
        document.body.prepend(backdrop);
    };

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', mountBackdrop, { once: true });
    } else {
        mountBackdrop();
    }
})();
