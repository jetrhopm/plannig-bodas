<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
defineProps<{ wedding: { id: number; name: string; events?: { name: string; event_date: string | null; venue: string | null }[] }; eyebrow?: string; title?: string }>();
</script>

<template>
  <main class="wedding-area-shell">
    <aside class="area-flower area-flower-left" aria-hidden="true"></aside><aside class="area-flower area-flower-right" aria-hidden="true"></aside>
    <section class="area-hero"><p>{{ eyebrow || 'CENTRO DE TRABAJO' }}</p><h1>{{ title || wedding.name }}</h1><slot name="intro" /><Link :href="route('weddings.workspace', wedding.id)">← Centro de trabajo</Link></section>
    <section class="area-content"><slot /></section>
  </main>
</template>

<style scoped>
.wedding-area-shell{min-height:100vh;background:#fbf7f3;position:relative;overflow:hidden;padding:1.5rem 1rem 4rem}.area-hero,.area-content{position:relative;z-index:1;max-width:72rem;margin:auto}.area-hero{padding:2rem 0 1.5rem}.area-hero p{margin:0;color:#a35e68;font-size:.68rem;letter-spacing:.2em;font-weight:700}.area-hero h1{margin:.5rem 0;font:clamp(2.5rem,8vw,4.3rem)/.96 'Playfair Display',Georgia,serif;color:#392d2c}.area-hero a{display:inline-block;margin-top:.8rem;color:#934e5b;font-size:.82rem}.area-content{border-radius:1.35rem}.area-flower{position:fixed;z-index:0;pointer-events:none;width:120px;height:260px;opacity:.25;transform-origin:bottom;animation:breeze 5s ease-in-out infinite alternate}.area-flower:before,.area-flower:after{content:'';position:absolute;width:55px;height:85px;border-radius:100% 0 100% 0;background:linear-gradient(135deg,#dba4a7,#f4d8c9);box-shadow:30px 70px 0 -12px #c7a083}.area-flower-left{left:-35px;bottom:4vh}.area-flower-right{right:-40px;top:25vh;scale:-1 1;animation-delay:-2s}@keyframes breeze{from{rotate:-3deg}to{rotate:4deg}}@media(prefers-reduced-motion:reduce){.area-flower{animation:none}}
</style>
