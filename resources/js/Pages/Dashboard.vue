<script setup lang="ts">
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link } from "@inertiajs/vue3";

interface Wedding {
    id: number;
    name: string;
    status: string;
    events: { name: string; event_date: string | null; venue: string | null }[];
}
interface Summary {
    nextEvents: {
        id: number;
        wedding_id: number;
        name: string;
        event_date: string | null;
        event_time: string | null;
        venue: string | null;
    }[];
    tasks: {
        id: number;
        wedding_id: number;
        title: string;
        priority: string;
        due_date: string | null;
        status: string;
    }[];
    seatRequests: {
        id: number;
        requested_count: number;
        request_note: string | null;
        family: { wedding_id: number; label: string };
    }[];
    financeItems: {
        id: number;
        wedding_id: number;
        description: string;
        amount: string;
        due_date: string | null;
        status: string;
    }[];
    receptionFamilies: {
        id: number;
        wedding_id: number;
        label: string;
        attending_count: number | null;
        entered_count: number | null;
    }[];
    proposals: {
        id: number;
        wedding_id: number;
        title: string;
        amount: string | null;
        version: number;
        status: string;
    }[];
}
const props = defineProps<{
    weddings: Wedding[];
    unreadNotifications: number;
    role: string;
    canCreateWedding: boolean;
    roleSummary: Summary;
}>();
const labels: Record<string, string> = {
    admin: "Administración",
    coordinator: "Coordinación",
    finance: "Finanzas",
    reception: "Recepción",
    couple: "Mi boda",
};
const formatDate = (value: string | null) =>
    value
        ? new Intl.DateTimeFormat("es-MX", {
              dateStyle: "medium",
              timeZone: "America/Mexico_City",
          }).format(new Date(value))
        : "Fecha pendiente";
const weddingName = (id: number) =>
    props.weddings.find((wedding) => wedding.id === id)?.name ?? "Boda";
const canPlan = () => !["finance", "reception"].includes(props.role);
const canReceive = () =>
    ["admin", "coordinator", "reception"].includes(props.role);
const canManage = () => ["admin", "coordinator"].includes(props.role);
</script>

<template>
    <Head title="Panel" />
    <AuthenticatedLayout>
        <template #header>
            <div>
                <p class="text-xs uppercase tracking-[.2em] text-rose-700">
                    Casa de bodas
                </p>
                <h1 class="font-serif text-3xl text-stone-800">
                    Panel de {{ labels[role] }}
                </h1>
            </div>
        </template>
        <main class="dashboard-page min-h-screen py-8">
            <div class="mx-auto max-w-7xl space-y-7 px-4">
            <section class="dashboard-hero rounded-3xl p-7 text-white">
                    <div
                        class="flex flex-wrap items-center justify-between gap-4"
                    >
                        <div>
                            <p class="text-rose-200">Centro operativo</p>
                            <p class="mt-2 text-xl">
                                {{ unreadNotifications }} avisos pendientes ·
                                tus acciones relevantes están aquí.
                            </p>
                        </div>
                        <Link
                            v-if="canCreateWedding"
                            :href="route('weddings.create')"
                                class="hero-action px-5 py-3 text-sm font-medium"
                            >+ Nueva boda</Link
                        ><Link
                            v-else
                            :href="route('notifications.index')"
                                class="hero-action px-5 py-3 text-sm font-medium"
                            >Ver avisos</Link
                        >
                    </div>
                </section>

                <section
                    v-if="roleSummary.nextEvents.length"
                    class="rounded-2xl border border-stone-200 bg-white p-5"
                >
                    <p class="text-xs uppercase tracking-widest text-rose-700">
                        Próximos eventos
                    </p>
                    <h2 class="font-serif text-2xl text-stone-800">
                        Calendario inmediato
                    </h2>
                    <div class="mt-4 grid gap-3 md:grid-cols-2 xl:grid-cols-3">
                        <Link
                            v-for="item in roleSummary.nextEvents"
                            :key="item.id"
                            :href="route('weddings.workspace', item.wedding_id)"
                            class="rounded-xl bg-stone-50 p-4 transition hover:bg-rose-50"
                            ><p class="font-medium text-stone-800">
                                {{ item.name }}
                            </p>
                            <p class="mt-1 text-sm text-stone-600">
                                {{ weddingName(item.wedding_id) }} ·
                                {{ formatDate(item.event_date) }}
                            </p>
                            <p class="text-sm text-stone-500">
                                {{ item.venue || "Lugar pendiente" }}
                            </p></Link
                        >
                    </div>
                </section>

                <section
                    v-if="
                        canManage() &&
                        (roleSummary.tasks.length ||
                            roleSummary.seatRequests.length)
                    "
                    class="grid gap-5 lg:grid-cols-2"
                >
                    <article
                        class="rounded-2xl border border-stone-200 bg-white p-5"
                    >
                        <p
                            class="text-xs uppercase tracking-widest text-rose-700"
                        >
                            Operación
                        </p>
                        <h2 class="font-serif text-2xl text-stone-800">
                            Tareas pendientes
                        </h2>
                        <div
                            v-if="roleSummary.tasks.length"
                            class="mt-3 space-y-2"
                        >
                            <Link
                                v-for="item in roleSummary.tasks"
                                :key="item.id"
                                :href="
                                    route(
                                        'weddings.planning.index',
                                        item.wedding_id,
                                    )
                                "
                                class="block rounded-lg bg-stone-50 p-3 hover:bg-rose-50"
                                ><b class="text-stone-800">{{ item.title }}</b>
                                <p class="text-sm text-stone-600">
                                    {{ weddingName(item.wedding_id) }} ·
                                    {{ item.priority }} ·
                                    {{ formatDate(item.due_date) }}
                                </p></Link
                            >
                        </div>
                        <p v-else class="mt-3 text-sm text-stone-500">
                            No hay tareas pendientes.
                        </p>
                    </article>
                    <article
                        class="rounded-2xl border border-stone-200 bg-white p-5"
                    >
                        <p
                            class="text-xs uppercase tracking-widest text-rose-700"
                        >
                            Invitados
                        </p>
                        <h2 class="font-serif text-2xl text-stone-800">
                            Solicitudes de lugares
                        </h2>
                        <div
                            v-if="roleSummary.seatRequests.length"
                            class="mt-3 space-y-2"
                        >
                            <Link
                                v-for="item in roleSummary.seatRequests"
                                :key="item.id"
                                :href="
                                    route(
                                        'weddings.families.index',
                                        item.family.wedding_id,
                                    )
                                "
                                class="block rounded-lg bg-stone-50 p-3 hover:bg-rose-50"
                                ><b class="text-stone-800"
                                    >{{ item.family.label }} ·
                                    {{ item.requested_count }} lugar(es)</b
                                >
                                <p class="text-sm text-stone-600">
                                    {{ weddingName(item.family.wedding_id)
                                    }}{{
                                        item.request_note
                                            ? ` · ${item.request_note}`
                                            : ""
                                    }}
                                </p></Link
                            >
                        </div>
                        <p v-else class="mt-3 text-sm text-stone-500">
                            No hay solicitudes pendientes.
                        </p>
                    </article>
                </section>

                <section
                    v-if="
                        ['admin', 'finance'].includes(role) &&
                        roleSummary.financeItems.length
                    "
                    class="rounded-2xl border border-stone-200 bg-white p-5"
                >
                    <p class="text-xs uppercase tracking-widest text-rose-700">
                        Finanzas
                    </p>
                    <h2 class="font-serif text-2xl text-stone-800">
                        Cobros y costos pendientes
                    </h2>
                    <div class="mt-3 grid gap-3 md:grid-cols-2 xl:grid-cols-3">
                        <Link
                            v-for="item in roleSummary.financeItems"
                            :key="item.id"
                            :href="
                                route('weddings.finance.index', item.wedding_id)
                            "
                            class="rounded-xl bg-stone-50 p-4 hover:bg-rose-50"
                            ><b class="text-stone-800">{{
                                item.description
                            }}</b>
                            <p class="mt-1 text-sm text-stone-600">
                                {{ weddingName(item.wedding_id) }} · ${{
                                    item.amount
                                }}
                            </p>
                            <p class="text-sm text-stone-500">
                                {{ formatDate(item.due_date) }} ·
                                {{ item.status }}
                            </p></Link
                        >
                    </div>
                </section>

                <section
                    v-if="canReceive() && roleSummary.receptionFamilies.length"
                    class="rounded-2xl border border-stone-200 bg-white p-5"
                >
                    <p class="text-xs uppercase tracking-widest text-rose-700">
                        Recepción
                    </p>
                    <h2 class="font-serif text-2xl text-stone-800">
                        Pases confirmados para operar
                    </h2>
                    <div class="mt-3 grid gap-3 md:grid-cols-2 xl:grid-cols-3">
                        <Link
                            v-for="family in roleSummary.receptionFamilies"
                            :key="family.id"
                            :href="
                                route(
                                    'weddings.reception.index',
                                    family.wedding_id,
                                )
                            "
                            class="rounded-xl bg-stone-50 p-4 hover:bg-rose-50"
                            ><b class="text-stone-800">{{ family.label }}</b>
                            <p class="mt-1 text-sm text-stone-600">
                                {{ weddingName(family.wedding_id) }}
                            </p>
                            <p class="text-sm text-stone-500">
                                {{ family.entered_count ?? 0 }}/{{
                                    family.attending_count ?? 0
                                }}
                                ingresos registrados
                            </p></Link
                        >
                    </div>
                </section>

                <section
                    v-if="role === 'couple'"
                    class="rounded-2xl border border-stone-200 bg-white p-5"
                >
                    <p class="text-xs uppercase tracking-widest text-rose-700">
                        Decisiones de pareja
                    </p>
                    <h2 class="font-serif text-2xl text-stone-800">
                        Propuestas que requieren respuesta
                    </h2>
                    <div
                        v-if="roleSummary.proposals.length"
                        class="mt-3 grid gap-3 md:grid-cols-2 xl:grid-cols-3"
                    >
                        <Link
                            v-for="item in roleSummary.proposals"
                            :key="item.id"
                            :href="
                                route(
                                    'weddings.planning.index',
                                    item.wedding_id,
                                )
                            "
                            class="rounded-xl bg-rose-50 p-4 hover:bg-rose-100"
                            ><b class="text-stone-800">{{ item.title }}</b>
                            <p class="mt-1 text-sm text-stone-600">
                                {{ weddingName(item.wedding_id) }} · versión
                                {{ item.version }}
                            </p>
                            <p class="text-sm text-stone-500">
                                {{
                                    item.amount === null
                                        ? "Importe pendiente"
                                        : `$${item.amount}`
                                }}
                            </p></Link
                        >
                    </div>
                    <p v-else class="mt-3 text-sm text-stone-500">
                        No tienes propuestas pendientes. Consulta Planeación
                        para ver el avance de tu boda.
                    </p>
                </section>

                <section>
                    <div class="mb-4">
                        <p
                            class="text-xs uppercase tracking-widest text-rose-700"
                        >
                            {{
                                role === "couple"
                                    ? "Mi boda"
                                    : "Bodas disponibles"
                            }}
                        </p>
                        <h2 class="font-serif text-2xl text-stone-800">
                            Elige dónde continuar
                        </h2>
                    </div>
                    <div class="grid gap-5 lg:grid-cols-2">
                        <article
                            v-for="wedding in weddings"
                            :key="wedding.id"
                            class="wedding-card rounded-3xl border border-stone-200 bg-white p-6 shadow-sm"
                        >
                            <p
                                class="text-xs uppercase tracking-widest text-rose-700"
                            >
                                {{ wedding.status }}
                            </p>
                            <h3 class="mt-1 font-serif text-3xl text-stone-800">
                                {{ wedding.name }}
                            </h3>
                            <div class="mt-4 rounded-xl bg-stone-50 p-4">
                                <b>{{
                                    wedding.events[0]?.name ||
                                    "Evento principal"
                                }}</b>
                                <p class="text-sm text-stone-600">
                                    {{
                                        formatDate(
                                            wedding.events[0]?.event_date ||
                                                null,
                                        )
                                    }}
                                    ·
                                    {{
                                        wedding.events[0]?.venue ||
                                        "Lugar pendiente"
                                    }}
                                </p>
                            </div>
                            <div class="mt-5 flex flex-wrap gap-2">
                                <Link
                                    :href="
                                        route('weddings.workspace', wedding.id)
                                    "
                                    class="rounded-lg bg-rose-800 px-4 py-2 text-sm text-white"
                                    >Abrir centro de trabajo</Link
                                ><Link
                                    v-if="canManage()"
                                    :href="
                                        route(
                                            'weddings.events.create',
                                            wedding.id,
                                        )
                                    "
                                    class="rounded-lg bg-stone-800 px-4 py-2 text-sm text-white"
                                    >Crear evento</Link
                                ><Link
                                    v-if="canManage()"
                                    :href="
                                        route(
                                            'weddings.families.index',
                                            wedding.id,
                                        )
                                    "
                                    class="rounded-lg border px-4 py-2 text-sm"
                                    >Familias</Link
                                ><Link
                                    v-if="canPlan()"
                                    :href="
                                        route(
                                            'weddings.planning.index',
                                            wedding.id,
                                        )
                                    "
                                    class="rounded-lg border px-4 py-2 text-sm"
                                    >{{
                                        role === "couple"
                                            ? "Ver planeación"
                                            : "Planeación"
                                    }}</Link
                                ><Link
                                    v-if="['admin', 'finance'].includes(role)"
                                    :href="
                                        route(
                                            'weddings.finance.index',
                                            wedding.id,
                                        )
                                    "
                                    class="rounded-lg border px-4 py-2 text-sm"
                                    >Finanzas</Link
                                ><Link
                                    v-if="canReceive()"
                                    :href="
                                        route(
                                            'weddings.reception.index',
                                            wedding.id,
                                        )
                                    "
                                    class="rounded-lg border px-4 py-2 text-sm"
                                    >Recepción</Link
                                ><Link
                                    v-if="canManage()"
                                    :href="
                                        route(
                                            'weddings.gifts.manage',
                                            wedding.id,
                                        )
                                    "
                                    class="rounded-lg border px-4 py-2 text-sm"
                                    >Regalos</Link
                                >
                            </div>
                        </article>
                    </div>
                    <p
                        v-if="!weddings.length"
                        class="rounded-2xl border border-dashed border-stone-300 bg-white p-8 text-center text-stone-500"
                    >
                        No tienes bodas disponibles todavía.
                    </p>
                </section>
            </div>
        </main>
    </AuthenticatedLayout>
</template>

<style scoped>
.dashboard-page{background:radial-gradient(circle at 96% 10%,#f3ddd8 0,transparent 25rem),#fbf8f4}.dashboard-hero{position:relative;overflow:hidden;background:linear-gradient(120deg,#4a3035,#8e4e5c 62%,#bd7a7f)}.dashboard-hero:after{content:'♡';position:absolute;right:4%;bottom:-38%;font:15rem Georgia;color:#ffffff14}.dashboard-hero>div{position:relative;z-index:1}.hero-action{border-radius:999px;background:#fffaf6;color:#80434d;box-shadow:0 10px 24px #32141938}.wedding-card{position:relative;overflow:hidden;transition:transform .22s ease,box-shadow .22s ease}.wedding-card:before{content:'';position:absolute;left:0;top:0;width:5px;height:100%;background:linear-gradient(#c9858d,#8d4b59)}.wedding-card:hover{transform:translateY(-3px);box-shadow:0 20px 38px #663a3217!important}
</style>
