<script setup lang="ts">
import { computed } from 'vue';
import { usePage } from '@inertiajs/vue3';

interface Item { label: string; route: string; visible: boolean; icon: string; description: string }
const props = defineProps<{ context: { wedding: { id: number; name: string; next_event?: { date?: string | null; venue?: string | null } | null }; items: Item[] } }>();
const page = usePage();
const config = computed(() => ({
    basePath: page.props.appBasePath || '', wedding: props.context.wedding,
    items: props.context.items.map((item) => ({ ...item, href: route(item.route, props.context.wedding.id), active: route().current(item.route) })),
}));
</script>

<template><wedding-area-menu :data-config="JSON.stringify(config)" /></template>
