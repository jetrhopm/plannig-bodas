<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

interface EventItem { id: number; name: string; event_date: string | null; event_time: string | null; venue: string | null; is_primary: boolean }
const props = defineProps<{ wedding: { id: number; name: string; events: EventItem[] } }>();

const upcomingEvent = () => props.wedding.events.find((event) => event.is_primary) ?? props.wedding.events[0] ?? null;
const date = (value: string | null) => value
    ? new Intl.DateTimeFormat('es-MX', { dateStyle: 'long', timeZone: 'UTC' }).format(new Date(`${value.slice(0, 10)}T12:00:00Z`))
    : 'Fecha pendiente';
</script>

<template>
    <Head :title="`Centro · ${wedding.name}`" />
    <AuthenticatedLayout>
        <template #header>
            <div class="flow-heading">
                <Link :href="route('dashboard')" class="flow-back">← Panel principal</Link>
                <p class="flow-kicker">Centro de trabajo</p>
                <h1>{{ wedding.name }}</h1>
                <p>Elige un área en el menú de la boda para continuar.</p>
            </div>
        </template>

        <section class="mx-auto max-w-6xl px-4 py-8 sm:px-6">
            <article class="premium-card overflow-hidden">
                <div class="grid items-center gap-5 p-5 sm:grid-cols-[1fr_auto] sm:p-7">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[.18em] text-rose-800">Evento próximo</p>
                        <h2 class="mt-2 text-3xl">{{ upcomingEvent()?.name ?? 'Tu primer momento está por definir' }}</h2>
                        <p class="mt-3 text-stone-600">{{ date(upcomingEvent()?.event_date ?? null) }} · {{ upcomingEvent()?.venue ?? 'Lugar pendiente' }}</p>
                    </div>
                    <Link :href="route('weddings.events.create', wedding.id)" class="premium-button inline-flex justify-center px-5 py-3 text-sm font-semibold">Crear evento</Link>
                </div>
            </article>
        </section>
    </AuthenticatedLayout>
</template>
