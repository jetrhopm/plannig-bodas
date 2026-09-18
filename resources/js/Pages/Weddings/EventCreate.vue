<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
const props = defineProps<{ wedding: { id: number; name: string; timezone: string } }>();
const form = useForm({ name: '', type: 'ceremony', event_date: '', event_time: '', venue: '', address: '', is_primary: true });
function submit(): void { form.post(route('weddings.events.store', props.wedding.id)); }
</script>
<template>
 <Head :title="`Nuevo evento · ${wedding.name}`" />
 <AuthenticatedLayout>
  <template #header><div><Link :href="route('weddings.show', wedding.id)" class="text-sm text-rose-800 underline">← Expediente</Link><h1 class="mt-1 font-serif text-3xl">Crear evento</h1></div></template>
  <form class="mx-auto max-w-2xl space-y-4 p-6" @submit.prevent="submit">
   <p class="rounded-xl bg-rose-50 p-4 text-sm text-stone-700">Crea cada momento de la boda. La hora se interpretará en <b>{{ wedding.timezone }}</b>. El principal aparece primero en el centro de trabajo.</p>
   <label class="block text-sm">Nombre del evento<input v-model="form.name" required class="mt-1 w-full rounded-lg border-stone-300" placeholder="Ceremonia religiosa" /><span v-if="form.errors.name" class="mt-1 block text-red-700">{{ form.errors.name }}</span></label>
   <label class="block text-sm">Tipo de evento<select v-model="form.type" class="mt-1 w-full rounded-lg border-stone-300"><option value="ceremony">Ceremonia</option><option value="civil">Civil</option><option value="reception">Recepción</option><option value="after_party">Tornaboda</option><option value="other">Otro</option></select></label>
   <div class="grid gap-4 sm:grid-cols-2"><label class="text-sm">Fecha<input v-model="form.event_date" type="date" class="mt-1 w-full rounded-lg border-stone-300" /><span v-if="form.errors.event_date" class="mt-1 block text-red-700">{{ form.errors.event_date }}</span></label><label class="text-sm">Hora<input v-model="form.event_time" type="time" class="mt-1 w-full rounded-lg border-stone-300" /><span v-if="form.errors.event_time" class="mt-1 block text-red-700">{{ form.errors.event_time }}</span></label></div>
   <label class="block text-sm">Lugar<input v-model="form.venue" class="mt-1 w-full rounded-lg border-stone-300" placeholder="Nombre del recinto" /></label>
   <label class="block text-sm">Dirección<textarea v-model="form.address" class="mt-1 w-full rounded-lg border-stone-300" /></label>
   <label class="flex items-start gap-3 rounded-xl border border-rose-200 bg-rose-50 p-3 text-sm"><input v-model="form.is_primary" type="checkbox" class="mt-1 h-4 w-4" /><span><b>Marcar como evento principal</b><br /><small class="text-stone-600">Puedes cambiarlo después desde el expediente.</small></span></label>
   <button :disabled="form.processing" class="block rounded-lg bg-rose-800 px-4 py-2 text-white disabled:opacity-50">Crear evento</button>
  </form>
 </AuthenticatedLayout>
</template>
