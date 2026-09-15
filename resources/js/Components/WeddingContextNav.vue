<script setup lang="ts">
import { Link } from '@inertiajs/vue3';

interface NavigationItem { label: string; route: string; visible: boolean }
defineProps<{ context: { wedding: { id: number; name: string }; items: NavigationItem[] } }>();
</script>

<template>
    <nav class="wedding-menu" aria-label="Áreas de la boda">
        <div class="wedding-menu__head"><span>{{ context.wedding.name }}</span><small>ÁREAS DE TRABAJO</small></div>
        <div class="wedding-menu__grid">
            <a v-for="item in context.items.filter((item) => item.visible)" :key="item.route" :href="route(item.route, context.wedding.id)" class="wedding-menu__item" :class="{ active: route().current(item.route) }">
                <span class="wedding-menu__icon">{{ item.label.slice(0, 1) }}</span><b>{{ item.label }}</b><small>Abrir área</small>
            </a>
        </div>
    </nav>
</template>

<style scoped>
.wedding-menu{position:relative;z-index:5;padding:12px max(1rem,5vw);border-bottom:1px solid #ead8d0;background:#fffdfa}.wedding-menu__head{display:flex;align-items:baseline;justify-content:space-between;margin:0 auto 10px;max-width:80rem;color:#3b2f2d;font:600 18px 'Playfair Display',Georgia}.wedding-menu__head small{font:600 9px Arial;letter-spacing:.15em;color:#a35e68}.wedding-menu__grid{display:flex;gap:8px;overflow-x:auto;max-width:80rem;margin:auto;padding-bottom:2px}.wedding-menu__item{min-width:104px;padding:10px;border:1px solid #eadbd3;border-radius:14px;background:#fffdfa;color:#4f403d;text-decoration:none;box-shadow:0 5px 13px #5e38300c}.wedding-menu__item b,.wedding-menu__item small{display:block}.wedding-menu__item b{margin-top:6px;font:16px 'Playfair Display',Georgia}.wedding-menu__item small{margin-top:2px;font-size:9px;color:#8b7771}.wedding-menu__icon{display:grid;place-items:center;width:25px;height:25px;border-radius:8px;background:#f4e7e2;color:#9b5663;font:600 12px Georgia}.wedding-menu__item.active{background:linear-gradient(135deg,#9b5260,#d78387);color:#fff}.wedding-menu__item.active .wedding-menu__icon{background:#ffffff29;color:#fff}.wedding-menu__item.active small{color:#ffe5e5}@media(min-width:760px){.wedding-menu__grid{flex-wrap:wrap}.wedding-menu__item{min-width:125px}}
</style>
