<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'; import { Head, Link } from '@inertiajs/vue3';
const props=defineProps<{wedding:{id:number;name:string;events:{name:string;event_date:string|null;venue:string|null;is_primary:boolean}[]};role:string;canEdit:boolean;canFinance:boolean;canReceive:boolean}>(); const formatDate=(value:string|null)=>value?new Intl.DateTimeFormat('es-MX',{dateStyle:'medium',timeZone:'America/Mexico_City'}).format(new Date(value)):'Fecha pendiente';
const modules=[
 {title:'Expediente',text:'Datos, permisos y configuración.',route:'weddings.show',show:true},
 {title:'Familias e invitaciones',text:'Pases, RSVP y solicitudes.',route:'weddings.families.index',show:props.canEdit},
 {title:'Planeación',text:'Tareas, agenda, propuestas y menú.',route:'weddings.planning.index',show:props.role!=='reception'&&props.role!=='finance'},
 {title:'Mesas y cocina',text:'Asignaciones y restricciones.',route:'weddings.planning.seating',show:props.canEdit},
 {title:'Logística e inspiración',text:'Proveedores, hoteles y referencias.',route:'weddings.planning.logistics',show:props.canEdit},
 {title:'Finanzas',text:'Cobros, pagos y cotizaciones.',route:'weddings.finance.index',show:props.canFinance},
 {title:'Regalos',text:'Lista y reservas.',route:'weddings.gifts.manage',show:props.canEdit},
 {title:'Recepción',text:'Consulta de pase e ingresos.',route:'weddings.reception.index',show:props.canReceive},
 {title:'Entrevista inicial',text:'Configura y actualiza los datos iniciales de la boda.',route:'weddings.interview.edit',show:props.canEdit},
 {title:'Crear evento',text:'Ceremonia, civil, recepción o tornaboda.',route:'weddings.events.create',show:props.canEdit},
];
</script>
<template><Head :title="`Centro · ${wedding.name}`"/><AuthenticatedLayout><template #header><div><Link :href="route('dashboard')" class="text-sm text-rose-800 underline">← Panel</Link><h1 class="mt-1 font-serif text-3xl text-stone-800">{{ wedding.name }}</h1></div></template><main class="min-h-screen bg-[#fbf8f4] py-8"><div class="mx-auto max-w-6xl px-4"><section class="rounded-3xl bg-stone-800 p-7 text-white"><p class="text-rose-200">Centro de trabajo</p><h2 class="mt-1 font-serif text-3xl">Elige el área que quieres gestionar</h2><p class="mt-3 text-stone-200">{{ wedding.events[0]?.name || 'Evento pendiente' }} · {{ formatDate(wedding.events[0]?.event_date || null) }} · {{ wedding.events[0]?.venue || 'Lugar pendiente' }}</p></section><div class="mt-7 grid gap-4 sm:grid-cols-2 lg:grid-cols-3"><Link v-for="item in modules.filter((item)=>item.show)" :key="item.route" :href="route(item.route,wedding.id)" class="rounded-2xl border border-stone-200 bg-white p-5 shadow-sm transition hover:border-rose-300 hover:shadow"><h3 class="font-serif text-xl text-stone-800">{{ item.title }}</h3><p class="mt-2 text-sm text-stone-600">{{ item.text }}</p><p class="mt-4 text-sm font-medium text-rose-800">Abrir →</p></Link></div></div></main></AuthenticatedLayout></template>
