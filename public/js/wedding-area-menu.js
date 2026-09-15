(() => {
    const esc = (value = '') => String(value).replace(/[&<>'"]/g, (char) => ({ '&':'&amp;','<':'&lt;','>':'&gt;',"'":'&#39;','"':'&quot;' }[char]));
    const icon = (basePath, id) => `<svg viewBox="0 0 24 24" aria-hidden="true"><use href="${basePath}/images/wedding-icons.svg#${id}"></use></svg>`;
    const descriptions = { Centro: 'Vista general', Expediente: 'Datos y configuración', Familias: 'Invitados y RSVPs', Planeación: 'Cronograma', Mesas: 'Distribución', Logística: 'Proveedores', Finanzas: 'Presupuesto', Regalos: 'Mesa de regalos', Recepción: 'Día del evento', Entrevista: 'Pareja / Cliente' };

    class WeddingAreaMenu extends HTMLElement {
        connectedCallback() { this.render(); }
        static get observedAttributes() { return ['data-config']; }
        attributeChangedCallback() { if (this.isConnected) this.render(); }
        render() {
            let config;
            try { config = JSON.parse(this.getAttribute('data-config') || '{}'); } catch { return; }
            const basePath = config.basePath || '';
            const event = config.wedding?.next_event || {};
            const parsedDate = event.date ? new Date(`${event.date}T12:00:00`) : null;
            const weekday = parsedDate ? new Intl.DateTimeFormat('es-MX', { weekday: 'long' }).format(parsedDate) : '';
            const displayDate = parsedDate ? `${parsedDate.getDate()} ${new Intl.DateTimeFormat('en-US', { month: 'short' }).format(parsedDate)} ${parsedDate.getFullYear()}` : '';
            const cards = (config.items || []).filter((item) => item.visible).map((item) => `
                <a class="area ${item.active ? 'is-active' : ''}" href="${esc(item.href)}">
                    <span class="icon">${icon(basePath, item.icon || 'dossier')}</span>
                    <b>${esc(item.label)}</b><small>${esc(item.label === 'Crear evento' ? 'Nuevo momento' : item.description || descriptions[item.label] || 'Abrir área')}</small>
                </a>`).join('');
            this.innerHTML = `<style>
                .wedding-area-menu{background:#fbf7f3;color:#392d2c;font:14px "DM Sans",Arial,sans-serif}.wedding-area-menu .hero{min-height:310px;padding:35px 6vw;position:relative;overflow:hidden;background:linear-gradient(90deg,#fff4efdd,#fff4ef8c),url("${basePath}/images/casa-de-bodas-hero.png") center/cover}.wedding-area-menu .hero h1{font:600 clamp(42px,12vw,68px)/.95 "Playfair Display",Georgia,serif;margin:9px 0}.wedding-area-menu .hero>p:not(.eyebrow){max-width:330px;margin:1em 0;font-size:16px;line-height:1.45;color:#77635e}.wedding-area-menu .eyebrow{margin:1em 0;font-size:10px;letter-spacing:.22em;color:#a35e68;font-weight:600}.wedding-area-menu .date{position:absolute;right:6vw;bottom:23px;padding:14px 17px;border-radius:17px;background:#fffdfbe8;box-shadow:0 12px 30px #663a321e}.wedding-area-menu .date b,.wedding-area-menu .date small{display:block}.wedding-area-menu .date small:first-child{text-transform:lowercase}.area-nav{padding:20px 5vw 28px;max-width:900px;margin:auto}.area-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:10px}.area{min-height:105px;padding:14px;border-radius:17px;background:#fffdfa;color:#493938;text-decoration:none;box-shadow:0 10px 24px #5f383111;transition:transform .25s}.area:hover{transform:translateY(-3px)}.area.is-active{background:linear-gradient(140deg,#a04f5f,#d8898c);color:#fff}.icon{width:27px;height:27px;display:block}.icon svg{width:27px;height:27px;fill:none;stroke:currentColor;stroke-width:1.65;stroke-linecap:round;stroke-linejoin:round}.area b,.area small{display:block}.area b{margin-top:12px;font:18px "Playfair Display",Georgia,serif}.area small{font-size:10px;opacity:.78}@media(max-width:390px){.area-grid{grid-template-columns:repeat(2,1fr)}}@media(prefers-reduced-motion:reduce){.area{transition:none}}
            </style><section class="wedding-area-menu"><section class="hero"><p class="eyebrow">CENTRO DE TRABAJO</p><h1>${esc(config.wedding?.name)}</h1><p>Organiza, planifica y haz realidad momentos inolvidables.</p>${parsedDate ? `<aside class="date"><small>${esc(weekday)}</small><b>${esc(displayDate)}</b><small>${esc(event.venue || 'Lugar pendiente')}</small></aside>` : ''}</section><nav class="area-nav" aria-label="Áreas de trabajo"><div class="area-grid">${cards}</div></nav></section>`;
        }
    }
    if (!customElements.get('wedding-area-menu')) customElements.define('wedding-area-menu', WeddingAreaMenu);
})();
