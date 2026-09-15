<script setup lang="ts">
import { computed } from 'vue';
import { usePage } from '@inertiajs/vue3';

interface NavigationItem { label: string; route: string; visible: boolean }
const props = defineProps<{ context: { wedding: { id: number; name: string; next_event?: { date?: string | null; venue?: string | null } | null }; items: NavigationItem[] } }>();
const page = usePage();
const bannerImage = computed(() => `${page.props.appBasePath || ''}/images/casa-de-bodas-hero.png`);
const eventDate = computed(() => props.context.wedding.next_event?.date
    ? new Intl.DateTimeFormat('es-MX', { weekday: 'long', day: 'numeric', month: 'short', year: 'numeric' }).format(new Date(`${props.context.wedding.next_event.date}T12:00:00`))
    : 'Fecha pendiente');
const iconId = (label: string) => ({
    'Centro': 'center', 'Expediente': 'dossier', 'Crear evento': 'event', 'Familias': 'families',
    'Planeación': 'planning', 'Mesas': 'tables', 'Logística': 'logistics', 'Finanzas': 'finance',
    'Regalos': 'gifts', 'Recepción': 'reception', 'Entrevista': 'interview',
}[label] ?? 'dossier');
const iconSprite = computed(() => `${page.props.appBasePath || ''}/images/wedding-icons.svg`);

</script>

<template>
    <nav class="wedding-menu" aria-label="Áreas de la boda">
        <section class="wedding-menu__hero" :style="{ backgroundImage: `linear-gradient(90deg, #fff4efdd, #fff4ef8c), url('${bannerImage}')` }">
            <p>CENTRO DE TRABAJO</p>
            <h1>{{ context.wedding.name }}</h1>
            <div class="wedding-menu__copy">Organiza, planifica y haz realidad momentos inolvidables.</div>
            <aside class="wedding-menu__date"><small>{{ eventDate }}</small><b>{{ context.wedding.next_event?.venue || 'Lugar pendiente' }}</b></aside>
        </section>
        <div class="wedding-menu__head">
            <span>{{ context.wedding.name }}</span>
            <small>ÁREAS DE TRABAJO</small>
        </div>
        <div class="wedding-menu__grid">
            <a
                v-for="item in context.items.filter((item) => item.visible)"
                :key="item.route"
                :href="route(item.route, context.wedding.id)"
                class="wedding-menu__item"
                :class="{ active: route().current(item.route) }"
            >
                <span class="wedding-menu__icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24"><use :href="`${iconSprite}#${iconId(item.label)}`" /></svg>
                </span>
                <b>{{ item.label }}</b>
                <small>{{ item.label === 'Crear evento' ? 'Nuevo momento' : 'Abrir área' }}</small>
            </a>
        </div>
    </nav>
</template>

<style scoped>
.wedding-menu{position:relative;z-index:5;border-bottom:1px solid #ead8d0;background:#fbf7f3;color:#3b2f2d}.wedding-menu__hero{min-height:310px;padding:35px max(1rem,6vw);position:relative;overflow:hidden;background-position:center;background-size:cover}.wedding-menu__hero p{margin:0;color:#765e5a;font:700 10px/1 Arial,sans-serif;letter-spacing:.22em}.wedding-menu__hero h1{max-width:700px;margin:9px 0;font:600 clamp(2.6rem,12vw,4.25rem)/.95 'Playfair Display',Georgia,serif}.wedding-menu__copy{max-width:330px;color:#77635e;font-size:16px;line-height:1.45}.wedding-menu__date{position:absolute;right:max(1rem,6vw);bottom:23px;padding:14px 17px;border-radius:17px;background:#fffdfbe8;box-shadow:0 12px 30px #663a321e}.wedding-menu__date b,.wedding-menu__date small{display:block}.wedding-menu__date small{text-transform:capitalize;font-size:12px}.wedding-menu__date b{margin-top:4px;font-size:14px}
.wedding-menu__head{display:flex;align-items:baseline;justify-content:space-between;gap:12px;max-width:76rem;margin:0 auto 14px;padding-top:18px}
.wedding-menu__head span{font:600 clamp(1.45rem,6vw,2rem)/1.1 'Playfair Display',Georgia,serif}.wedding-menu__head small{font:700 10px/1 Arial,sans-serif;letter-spacing:.18em;color:#a35e68;white-space:nowrap}
.wedding-menu__grid{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:10px;max-width:76rem;margin:auto}
.wedding-menu__item{min-height:132px;display:flex;flex-direction:column;justify-content:flex-end;padding:13px 10px;border:1px solid #eadbd3;border-radius:18px;background:#fffdfa;color:#4b3a38;text-decoration:none;box-shadow:0 8px 22px #5e38300e;transition:transform .22s ease,box-shadow .22s ease,background .22s ease}
.wedding-menu__item:active{transform:scale(.97)}.wedding-menu__item:hover{transform:translateY(-2px);box-shadow:0 12px 25px #5e38301a}
.wedding-menu__icon{width:31px;height:31px;display:grid;place-items:center;align-self:flex-start;margin-bottom:auto;color:#8d4c59}.wedding-menu__icon svg{width:29px;height:29px;fill:none;stroke:currentColor;stroke-width:1.8;stroke-linecap:round;stroke-linejoin:round}
.wedding-menu__item b{font:600 clamp(1rem,4.4vw,1.35rem)/1.04 'Playfair Display',Georgia,serif;letter-spacing:-.02em}.wedding-menu__item small{display:block;margin-top:5px;color:#8b7771;font:500 10px/1.15 Arial,sans-serif}
.wedding-menu__item.active{border-color:#a75060;background:linear-gradient(135deg,#934d5d,#d47c83);color:#fff;box-shadow:0 12px 25px #91495444}.wedding-menu__item.active .wedding-menu__icon,.wedding-menu__item.active small{color:#fff}
@media(min-width:640px){.wedding-menu__grid{grid-template-columns:repeat(4,minmax(0,1fr));gap:14px}.wedding-menu__item{min-height:146px;padding:17px}.wedding-menu__item small{font-size:11px}}
@media(min-width:1024px){.wedding-menu__grid{grid-template-columns:repeat(6,minmax(0,1fr))}.wedding-menu__item{min-height:130px}}
@media(max-width:390px){.wedding-menu__grid{grid-template-columns:repeat(2,minmax(0,1fr))}.wedding-menu__hero{min-height:280px}.wedding-menu__date{bottom:16px}}
@media(prefers-reduced-motion:reduce){.wedding-menu__item{transition:none}}
</style>
