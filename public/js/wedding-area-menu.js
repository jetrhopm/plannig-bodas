(() => {
    const esc = (value = '') => String(value).replace(/[&<>'"]/g, (char) => ({ '&':'&amp;','<':'&lt;','>':'&gt;',"'":'&#39;','"':'&quot;' }[char]));
    const icon = (basePath, id) => `<svg viewBox="0 0 24 24" aria-hidden="true"><use href="${basePath}/images/wedding-icons.svg#${id}"></use></svg>`;

    class WeddingAreaMenu extends HTMLElement {
        connectedCallback() { this.render(); }
        static get observedAttributes() { return ['data-config']; }
        attributeChangedCallback() { if (this.isConnected) this.render(); }
        render() {
            let config;
            try { config = JSON.parse(this.getAttribute('data-config') || '{}'); } catch { return; }
            const basePath = config.basePath || '';
            const event = config.wedding?.next_event || {};
            const cards = (config.items || []).filter((item) => item.visible).map((item) => `
                <a class="area ${item.active ? 'is-active' : ''}" href="${esc(item.href)}">
                    <span class="icon">${icon(basePath, item.icon || 'dossier')}</span>
                    <b>${esc(item.label)}</b><small>${esc(item.label === 'Crear evento' ? 'Nuevo momento' : item.description || 'Abrir área')}</small>
                </a>`).join('');
            this.innerHTML = `<style>
                .wedding-area-menu{background:#fbf7f3;color:#392d2c;font-family:"DM Sans",Arial,sans-serif}.area-hero{min-height:310px;padding:35px 6vw;position:relative;overflow:hidden;background:linear-gradient(90deg,#fff4efdd,#fff4ef8c),url("${basePath}/images/casa-de-bodas-hero.png") center/cover}.area-hero p{margin:0;color:#765e5a;font-size:10px;letter-spacing:.22em;font-weight:600}.area-hero h1{margin:9px 0;font:600 clamp(42px,12vw,68px)/.95 "Playfair Display",Georgia,serif}.area-copy{max-width:330px;color:#77635e;font-size:16px;line-height:1.45}.area-date{position:absolute;right:6vw;bottom:23px;padding:14px 17px;border-radius:17px;background:#fffdfbe8;box-shadow:0 12px 30px #663a321e}.area-date b,.area-date small{display:block}.area-date small{text-transform:capitalize}.area-date b{margin-top:4px}.area-nav{padding:20px 5vw 28px;max-width:900px;margin:auto}.area-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:10px}.area{min-height:105px;padding:14px;border-radius:17px;background:#fffdfa;color:#493938;text-decoration:none;box-shadow:0 10px 24px #5f383111;transition:transform .25s}.area:hover{transform:translateY(-3px)}.area.is-active{background:linear-gradient(140deg,#a04f5f,#d8898c);color:#fff}.icon{width:27px;height:27px;display:block}.icon svg{width:27px;height:27px;fill:none;stroke:currentColor;stroke-width:1.65;stroke-linecap:round;stroke-linejoin:round}.area b,.area small{display:block}.area b{margin-top:12px;font:18px "Playfair Display",Georgia,serif}.area small{font-size:10px;opacity:.78}@media(max-width:390px){.area-grid{grid-template-columns:repeat(2,1fr)}}@media(prefers-reduced-motion:reduce){.area{transition:none}}
            </style><section class="wedding-area-menu"><section class="area-hero"><p>CENTRO DE TRABAJO</p><h1>${esc(config.wedding?.name)}</h1><div class="area-copy">Organiza, planifica y haz realidad momentos inolvidables.</div><aside class="area-date"><small>${esc(event.date || 'Fecha pendiente')}</small><b>${esc(event.venue || 'Lugar pendiente')}</b></aside></section><nav class="area-nav" aria-label="Áreas de trabajo"><div class="area-grid">${cards}</div></nav></section>`;
        }
    }
    if (!customElements.get('wedding-area-menu')) customElements.define('wedding-area-menu', WeddingAreaMenu);
})();
