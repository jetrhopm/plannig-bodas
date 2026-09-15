<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

defineProps<{ coordinators: { id: number; name: string; email: string }[] }>();
const form = useForm({ name: '', status: 'consultation', support_type: 'full', authorized_capacity: null as number | null, timezone: 'America/Mexico_City', coordinator_id: null as number | null });
function submit(): void { form.post(route('weddings.store')); }
</script>

<template>
    <Head title="Nueva boda" />
    <AuthenticatedLayout>
        <template #header>
            <div>
                <Link :href="route('dashboard')" class="text-sm text-rose-800 underline">← Panel</Link>
                <h1 class="mt-1 font-serif text-3xl text-stone-800">Nueva boda</h1>
            </div>
        </template>
        <main class="min-h-screen bg-[#fbf8f4] py-8">
            <form class="mx-auto max-w-2xl space-y-5 rounded-3xl border border-stone-200 bg-white p-6 shadow-sm" @submit.prevent="submit">
                <div><p class="text-sm uppercase tracking-[.18em] text-rose-700">Expediente inicial</p><p class="mt-1 text-sm text-stone-600">Crea la boda y después configura entrevista, eventos, invitados y planeación.</p></div>
                <label class="block text-sm font-medium text-stone-700">Nombre de la pareja o boda<input v-model="form.name" required autofocus class="mt-1 w-full rounded-lg border-stone-300" placeholder="Ej. Valeria & Rodrigo"><span v-if="form.errors.name" class="mt-1 block text-sm text-rose-700">{{ form.errors.name }}</span></label>
                <div class="grid gap-4 sm:grid-cols-2"><label class="text-sm font-medium text-stone-700">Estado<select v-model="form.status" class="mt-1 w-full rounded-lg border-stone-300"><option value="consultation">Consulta</option><option value="contracted">Contratada</option><option value="preparation">En preparación</option></select></label><label class="text-sm font-medium text-stone-700">Acompañamiento<select v-model="form.support_type" class="mt-1 w-full rounded-lg border-stone-300"><option value="full">Integral</option><option value="shared">Compartido</option><option value="advisory">Asesoría</option></select></label></div>
                <div class="grid gap-4 sm:grid-cols-2"><label class="text-sm font-medium text-stone-700">Cupo estimado<input v-model.number="form.authorized_capacity" type="number" min="1" max="100000" class="mt-1 w-full rounded-lg border-stone-300" placeholder="Pendiente"><span v-if="form.errors.authorized_capacity" class="mt-1 block text-sm text-rose-700">{{ form.errors.authorized_capacity }}</span></label><label class="text-sm font-medium text-stone-700">Coordinador responsable<select v-model="form.coordinator_id" class="mt-1 w-full rounded-lg border-stone-300"><option :value="null">Asignar después</option><option v-for="coordinator in coordinators" :key="coordinator.id" :value="coordinator.id">{{ coordinator.name }} · {{ coordinator.email }}</option></select></label></div>
                <label class="block text-sm font-medium text-stone-700">Zona horaria<select v-model="form.timezone" class="mt-1 w-full rounded-lg border-stone-300"><option value="America/Mexico_City">México central (America/Mexico_City)</option><option value="America/Tijuana">México noroeste (America/Tijuana)</option><option value="America/Cancun">Quintana Roo (America/Cancun)</option></select></label>
                <div class="flex flex-wrap gap-3 pt-2"><button :disabled="form.processing" class="rounded-lg bg-rose-800 px-5 py-2.5 text-sm font-medium text-white disabled:opacity-60">Crear boda y abrir centro</button><Link :href="route('dashboard')" class="rounded-lg border border-stone-300 px-5 py-2.5 text-sm text-stone-700">Cancelar</Link></div>
            </form>
        </main>
    </AuthenticatedLayout>
</template>
