<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router } from '@inertiajs/vue3';

interface NotificationItem {
    id: string;
    title: string;
    description: string | null;
    importance: 'normal' | 'high' | string;
    read_at: string | null;
    created_at: string;
}

defineProps<{ notifications: { data: NotificationItem[] } }>();

function markRead(id: string): void {
    router.patch(route('notifications.read', id), {}, { preserveScroll: true });
}

function markAllRead(): void {
    router.patch(route('notifications.read-all'), {}, { preserveScroll: true });
}

function dateLabel(value: string): string {
    return new Intl.DateTimeFormat('es-MX', { dateStyle: 'medium', timeStyle: 'short' }).format(new Date(value));
}
</script>

<template>
    <Head title="Notificaciones" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between gap-4">
                <div><p class="text-sm uppercase tracking-[0.2em] text-rose-700">Centro de avisos</p><h1 class="font-serif text-3xl text-stone-800">Notificaciones</h1></div>
                <button v-if="notifications.data.some((item) => !item.read_at)" type="button" class="rounded-lg border border-stone-300 px-4 py-2 text-sm font-medium text-stone-700 hover:bg-stone-50 focus:outline-none focus:ring-2 focus:ring-rose-500" @click="markAllRead">Marcar todas leídas</button>
            </div>
        </template>

        <div class="mx-auto max-w-4xl px-4 py-8 sm:px-6 lg:px-8">
            <div v-if="notifications.data.length" class="overflow-hidden rounded-2xl border border-stone-200 bg-white shadow-sm">
                <article v-for="item in notifications.data" :key="item.id" class="flex gap-4 border-b border-stone-100 p-5 last:border-b-0" :class="{ 'bg-rose-50/50': !item.read_at }">
                    <span class="mt-1.5 h-2.5 w-2.5 shrink-0 rounded-full" :class="item.read_at ? 'bg-stone-300' : item.importance === 'high' ? 'bg-rose-600' : 'bg-amber-500'" aria-hidden="true" />
                    <div class="min-w-0 flex-1"><h2 class="font-medium text-stone-800">{{ item.title }}</h2><p v-if="item.description" class="mt-1 text-sm text-stone-600">{{ item.description }}</p><p class="mt-2 text-xs text-stone-500">{{ dateLabel(item.created_at) }}</p></div>
                    <button v-if="!item.read_at" type="button" class="shrink-0 text-sm font-medium text-rose-800 underline underline-offset-4 focus:outline-none focus:ring-2 focus:ring-rose-500" @click="markRead(item.id)">Leída</button>
                </article>
            </div>
            <div v-else class="rounded-2xl border border-dashed border-stone-300 bg-white p-10 text-center text-stone-500">No hay avisos todavía.</div>
        </div>
    </AuthenticatedLayout>
</template>
