<script setup lang="ts">
interface NavigationItem { label: string; route: string; visible: boolean }
defineProps<{ context: { wedding: { id: number; name: string }; items: NavigationItem[] } }>();

</script>

<template>
    <nav class="wedding-menu" aria-label="Áreas de la boda">
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
                    <svg v-if="item.label === 'Centro'" viewBox="0 0 24 24"><path d="m3 10 9-7 9 7v10H3zM9 21v-6h6v6"/></svg>
                    <svg v-else-if="item.label === 'Expediente'" viewBox="0 0 24 24"><rect x="5" y="3" width="14" height="18" rx="2"/><path d="M8 8h8M8 12h8M8 16h5"/></svg>
                    <svg v-else-if="item.label === 'Crear evento'" viewBox="0 0 24 24"><rect x="3" y="5" width="18" height="16" rx="2"/><path d="M8 3v4m8-4v4M3 10h18m9 4v6m-3-3h6"/></svg>
                    <svg v-else-if="item.label === 'Familias'" viewBox="0 0 24 24"><circle cx="9" cy="8" r="3"/><path d="M3 21v-2a6 6 0 0 1 12 0v2m2-12a3 3 0 1 0-1-5.83M18 21v-2a6 6 0 0 0-3-5.2"/></svg>
                    <svg v-else-if="item.label === 'Planeación'" viewBox="0 0 24 24"><rect x="4" y="3" width="16" height="18" rx="2"/><path d="M8 8h8M8 12h8M8 16h5"/></svg>
                    <svg v-else-if="item.label === 'Mesas'" viewBox="0 0 24 24"><path d="M4 10h16M7 10v10m10-10v10M10 4h4v6h-4z"/></svg>
                    <svg v-else-if="item.label === 'Logística'" viewBox="0 0 24 24"><path d="M3 7h11v10H3zM14 10h4l3 3v4h-7zM6 21a2 2 0 1 0 0-4 2 2 0 0 0 0 4Zm12 0a2 2 0 1 0 0-4 2 2 0 0 0 0 4Z"/></svg>
                    <svg v-else-if="item.label === 'Finanzas'" viewBox="0 0 24 24"><path d="M4 20V10m6 10V4m6 16v-7m6 7H2"/></svg>
                    <svg v-else-if="item.label === 'Regalos'" viewBox="0 0 24 24"><path d="M3 10h18v11H3zM2 6h20v4H2zm10 4v11M7 6c-2 0-3-1-3-2s1-2 3-1l3 3m7 0c2 0 3-1 3-2s-1-2-3-1l-3 3"/></svg>
                    <svg v-else-if="item.label === 'Recepción'" viewBox="0 0 24 24"><path d="M3 20h18M5 20V9h14v11M3 9h18M8 9V4h8v5M9 14h6"/></svg>
                    <svg v-else viewBox="0 0 24 24"><circle cx="12" cy="7" r="4"/><path d="M4 21a8 8 0 0 1 16 0m-2-12 3 3m0-3-3 3"/></svg>
                </span>
                <b>{{ item.label }}</b>
                <small>{{ item.label === 'Crear evento' ? 'Nuevo momento' : 'Abrir área' }}</small>
            </a>
        </div>
    </nav>
</template>

<style scoped>
.wedding-menu{position:relative;z-index:5;padding:18px max(1rem,5vw) 20px;border-bottom:1px solid #ead8d0;background:linear-gradient(145deg,#fffdfa,#fbf4ef);color:#3b2f2d}
.wedding-menu__head{display:flex;align-items:baseline;justify-content:space-between;gap:12px;max-width:76rem;margin:0 auto 14px}
.wedding-menu__head span{font:600 clamp(1.45rem,6vw,2rem)/1.1 'Playfair Display',Georgia,serif}.wedding-menu__head small{font:700 10px/1 Arial,sans-serif;letter-spacing:.18em;color:#a35e68;white-space:nowrap}
.wedding-menu__grid{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:10px;max-width:76rem;margin:auto}
.wedding-menu__item{min-height:132px;display:flex;flex-direction:column;justify-content:flex-end;padding:13px 10px;border:1px solid #eadbd3;border-radius:18px;background:#fffdfa;color:#4b3a38;text-decoration:none;box-shadow:0 8px 22px #5e38300e;transition:transform .22s ease,box-shadow .22s ease,background .22s ease}
.wedding-menu__item:active{transform:scale(.97)}.wedding-menu__item:hover{transform:translateY(-2px);box-shadow:0 12px 25px #5e38301a}
.wedding-menu__icon{width:31px;height:31px;display:grid;place-items:center;align-self:flex-start;margin-bottom:auto;color:#8d4c59}.wedding-menu__icon svg{width:29px;height:29px;fill:none;stroke:currentColor;stroke-width:1.8;stroke-linecap:round;stroke-linejoin:round}
.wedding-menu__item b{font:600 clamp(1rem,4.4vw,1.35rem)/1.04 'Playfair Display',Georgia,serif;letter-spacing:-.02em}.wedding-menu__item small{display:block;margin-top:5px;color:#8b7771;font:500 10px/1.15 Arial,sans-serif}
.wedding-menu__item.active{border-color:#a75060;background:linear-gradient(135deg,#934d5d,#d47c83);color:#fff;box-shadow:0 12px 25px #91495444}.wedding-menu__item.active .wedding-menu__icon,.wedding-menu__item.active small{color:#fff}
@media(min-width:640px){.wedding-menu__grid{grid-template-columns:repeat(4,minmax(0,1fr));gap:14px}.wedding-menu__item{min-height:146px;padding:17px}.wedding-menu__item small{font-size:11px}}
@media(min-width:1024px){.wedding-menu__grid{grid-template-columns:repeat(6,minmax(0,1fr))}.wedding-menu__item{min-height:130px}}
@media(prefers-reduced-motion:reduce){.wedding-menu__item{transition:none}}
</style>
