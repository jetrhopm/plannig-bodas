<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

interface Wedding { id:number; name:string; authorized_capacity:number|null }
interface SeatRequest { id:number; requested_count:number; approved_count:number|null; status:string; request_note:string|null; decision_reason:string|null }
interface Guest { id:number; position:number; display_name:string|null; attending:boolean; dietary_restrictions:string|null; accessibility_needs:string|null }
interface Family { id:number; label:string; responsible_name:string|null; responsible_email:string|null; responsible_phone:string|null; current_allocation:number; original_allocation:number; rsvp_status:string; attending_count:number|null; members_count:number; members:Guest[]; additional_seat_requests:SeatRequest[] }

const props = defineProps<{wedding:Wedding;families:Family[];assignedSeats:number;availableSeats:number|null;canEdit:boolean}>();
const form = useForm({label:'',responsible_name:'',responsible_email:'',responsible_phone:'',allocation:1});
const editForm = useForm({label:'',responsible_name:'',responsible_email:'',responsible_phone:'',allocation:1});
const editing = ref<number|null>(null);
const visibleGuests = ref<number|null>(null);
const approvalCounts = ref<Record<number,number>>({});

function submit(): void { form.post(route('weddings.families.store', props.wedding.id), { preserveScroll:true, onSuccess:() => form.reset() }); }
function begin(family:Family): void { editing.value=family.id; Object.assign(editForm,{label:family.label,responsible_name:family.responsible_name??'',responsible_email:family.responsible_email??'',responsible_phone:family.responsible_phone??'',allocation:family.current_allocation}); }
function save(family:Family): void { editForm.patch(route('weddings.families.update',[props.wedding.id,family.id]), { preserveScroll:true, onSuccess:() => { editing.value=null; editForm.reset(); } }); }
function approvedCount(request:SeatRequest): number { return approvalCounts.value[request.id] ?? request.requested_count; }
function setApprovalCount(id:number,event:Event): void { approvalCounts.value[id]=Number((event.target as HTMLInputElement).value); }
function resolveRequest(id:number,decision:'approved'|'rejected',approvedCount?:number): void { router.patch(route('weddings.additional-seats.resolve',[props.wedding.id,id]), { decision, approved_count:approvedCount, decision_reason:decision==='rejected'?'Sin disponibilidad actualmente.':null }, { preserveScroll:true }); }
</script>

<template>
    <Head :title="`Familias · ${wedding.name}`" />
    <AuthenticatedLayout>
        <template #header>
            <div>
                <Link :href="route('weddings.show', wedding.id)" class="text-sm text-rose-800 underline">← Expediente</Link>
                <h1 class="mt-1 font-serif text-3xl">Familias y pases</h1>
                <p class="mt-1 text-sm text-stone-600">Crea y corrige responsables, contactos y pases asignados.</p>
            </div>
        </template>

        <main class="mx-auto max-w-6xl px-4 py-8 sm:px-6">
            <div class="grid gap-4 sm:grid-cols-3">
                <div class="rounded-xl bg-stone-800 p-4 text-white"><p class="text-sm text-rose-100">Asignados vigentes</p><p class="mt-1 text-2xl font-semibold">{{ assignedSeats }}</p></div>
                <div class="rounded-xl border border-stone-200 bg-white p-4"><p class="text-sm text-stone-500">Disponibles</p><p class="mt-1 text-2xl font-semibold">{{ availableSeats ?? 'Pendiente' }}</p></div>
                <div class="rounded-xl border border-stone-200 bg-white p-4"><p class="text-sm text-stone-500">Confirmados</p><p class="mt-1 text-2xl font-semibold">{{ families.reduce((total,item) => total+(item.attending_count??0),0) }}</p></div>
            </div>

            <div class="mt-6 grid gap-6 lg:grid-cols-3">
                <section class="lg:col-span-2 overflow-hidden rounded-2xl border border-stone-200 bg-white">
                    <table class="min-w-full text-left text-sm">
                        <thead class="bg-stone-50 text-stone-500"><tr><th class="p-4">Familia / responsable</th><th class="p-4">Pases</th><th class="p-4">Respuesta</th><th class="p-4">Invitados</th><th v-if="canEdit" class="p-4"></th></tr></thead>
                        <tbody>
                            <template v-for="family in families" :key="family.id">
                                <tr class="border-t"><td class="p-4"><b>{{ family.label }}</b><p class="text-stone-500">{{ family.responsible_name || 'Responsable pendiente' }}</p><p v-if="family.responsible_email || family.responsible_phone" class="text-xs text-stone-500">{{ family.responsible_email }} {{ family.responsible_phone }}</p></td><td class="p-4">{{ family.current_allocation }} <small v-if="family.current_allocation!==family.original_allocation">(original: {{ family.original_allocation }})</small></td><td class="p-4">{{ family.rsvp_status }}<span v-if="family.attending_count!==null"> · {{ family.attending_count }}</span></td><td class="p-4"><button class="text-rose-800 underline" @click="visibleGuests=visibleGuests===family.id?null:family.id">{{ visibleGuests===family.id ? 'Ocultar invitados' : `Ver invitados (${family.members_count})` }}</button></td><td v-if="canEdit" class="p-4"><button class="text-rose-800 underline" @click="begin(family)">Editar</button></td></tr>
                                <tr v-if="visibleGuests===family.id" class="bg-stone-50"><td :colspan="canEdit?5:4" class="p-4"><p class="font-medium text-stone-800">Lista de invitados · {{ family.label }}</p><div v-if="family.members.length" class="mt-3 grid gap-2 sm:grid-cols-2"><article v-for="guest in family.members" :key="guest.id" class="rounded-lg border border-stone-200 bg-white p-3"><b>{{ guest.display_name || `Invitado ${guest.position}` }}</b><p class="mt-1 text-xs text-stone-500">{{ guest.attending ? 'Asistirá' : 'No asistirá' }}<span v-if="guest.dietary_restrictions"> · {{ guest.dietary_restrictions }}</span><span v-if="guest.accessibility_needs"> · {{ guest.accessibility_needs }}</span></p></article></div><p v-else class="mt-2 rounded-lg bg-amber-50 p-3 text-sm text-amber-900">Aún no hay nombres capturados para esta familia. El responsable debe confirmarlos desde su enlace de invitación; no se muestran espacios vacíos como invitados.</p></td></tr>
                                <tr v-if="editing===family.id" class="bg-rose-50"><td :colspan="canEdit?5:4" class="p-4"><form class="grid gap-3 sm:grid-cols-2" @submit.prevent="save(family)"><label>Nombre de familia<input v-model="editForm.label" required class="mt-1 w-full rounded border-stone-300"></label><label>Responsable<input v-model="editForm.responsible_name" class="mt-1 w-full rounded border-stone-300"></label><label>Correo<input v-model="editForm.responsible_email" type="email" class="mt-1 w-full rounded border-stone-300"></label><label>Teléfono / WhatsApp<input v-model="editForm.responsible_phone" class="mt-1 w-full rounded border-stone-300"></label><label>Pases asignados<input v-model.number="editForm.allocation" type="number" min="1" class="mt-1 w-full rounded border-stone-300"><small>Máximo de personas que esta familia puede confirmar.</small></label><p v-if="editForm.errors.allocation" class="text-red-700">{{ editForm.errors.allocation }}</p><div class="self-end"><button class="rounded bg-rose-800 px-3 py-2 text-white">Guardar cambios</button><button type="button" class="ml-2 underline" @click="editing=null">Cancelar</button></div></form></td></tr>
                            </template>
                            <tr v-if="!families.length"><td :colspan="canEdit?5:4" class="p-6 text-center text-stone-500">No hay familias registradas.</td></tr>
                        </tbody>
                    </table>
                </section>

                <section v-if="canEdit && families.some(family => family.additional_seat_requests.some(request => request.status === 'pending'))" class="lg:col-span-2 rounded-2xl border border-amber-200 bg-amber-50 p-5">
                    <h2 class="font-serif text-xl text-amber-950">Solicitudes de pases adicionales</h2>
                    <p class="mt-1 text-sm text-amber-900">La persona responsable solicitó más lugares. Aprobar actualiza el cupo únicamente si aún hay disponibilidad.</p>
                    <article v-for="family in families" :key="`requests-${family.id}`">
                        <div v-for="request in family.additional_seat_requests.filter(item => item.status === 'pending')" :key="request.id" class="mt-4 rounded-xl border border-amber-200 bg-white p-4">
                            <div class="flex flex-wrap items-start justify-between gap-3"><div><b>{{ family.label }}</b><p class="text-sm text-stone-600">Solicita {{ request.requested_count }} pase(s) adicional(es)<span v-if="request.request_note"> · {{ request.request_note }}</span></p></div><div class="flex flex-wrap items-center gap-2"><label class="text-sm">Aprobar <input :value="approvedCount(request)" type="number" min="1" :max="request.requested_count" class="ml-1 w-16 rounded border-stone-300" @input="setApprovalCount(request.id,$event)"> de {{ request.requested_count }}</label><button class="rounded bg-rose-800 px-3 py-2 text-sm text-white" @click="resolveRequest(request.id,'approved',approvedCount(request))">Aprobar</button><button class="rounded border border-stone-400 px-3 py-2 text-sm text-stone-700" @click="resolveRequest(request.id,'rejected')">Rechazar</button></div></div>
                        </div>
                    </article>
                </section>

                <form v-if="canEdit" class="rounded-2xl border border-stone-200 bg-white p-5" @submit.prevent="submit"><h2 class="font-serif text-xl">Nueva familia</h2><p class="mt-1 text-sm text-stone-600">Cada familia recibe su propio enlace de invitación.</p><div class="mt-4 space-y-3"><label>Nombre identificador<input v-model="form.label" required class="mt-1 w-full rounded-lg border-stone-300" placeholder="Familia García"></label><label>Responsable<input v-model="form.responsible_name" class="mt-1 w-full rounded-lg border-stone-300" placeholder="Nombre de contacto"></label><label>Correo<input v-model="form.responsible_email" type="email" class="mt-1 w-full rounded-lg border-stone-300"></label><label>Teléfono / WhatsApp<input v-model="form.responsible_phone" type="tel" class="mt-1 w-full rounded-lg border-stone-300"></label><label>Pases asignados<input v-model.number="form.allocation" type="number" min="1" required class="mt-1 w-full rounded-lg border-stone-300"><small class="text-stone-500">Número máximo de personas que esta familia puede confirmar asistencia.</small></label><p v-if="form.errors.allocation" class="text-sm text-red-700">{{ form.errors.allocation }}</p><button class="rounded-lg bg-rose-800 px-4 py-2 text-sm text-white">Crear familia</button></div></form>
            </div>
        </main>
    </AuthenticatedLayout>
</template>
