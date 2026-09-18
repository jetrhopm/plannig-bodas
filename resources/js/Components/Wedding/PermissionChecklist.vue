<script setup lang="ts">
interface Membership {
    id: number;
    relationship: string;
    permissions: Record<string, boolean> | null;
    user: { name: string; email?: string };
}

const props = defineProps<{
    weddingId: number;
    memberships: Membership[];
    editable: boolean;
}>();

const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ?? '';

const permissions: Record<string, string> = {
    view_progress: 'Consultar avance',
    edit_wedding: 'Editar expediente',
    create_requests: 'Crear solicitudes',
    approve_proposals: 'Aprobar propuestas',
    share_invitation: 'Compartir invitaciones',
    view_finance: 'Ver finanzas',
    scan_passes: 'Registrar recepción',
};

function activePermissions(member: Membership): string {
    return Object.entries(member.permissions ?? {})
        .filter(([, enabled]) => enabled)
        .map(([key]) => permissions[key] ?? key)
        .join(' · ') || 'Sin permisos asignados';
}
</script>

<template>
    <section class="premium-card p-5 sm:p-6">
        <div>
            <p class="text-xs font-semibold uppercase tracking-[.18em] text-rose-800">Accesos</p>
            <h2 class="mt-1 text-2xl">Equipo y pareja</h2>
            <p class="mt-2 text-sm text-stone-600">
                Selecciona todos los permisos necesarios y guarda una sola vez. Cada casilla es independiente.
            </p>
        </div>

        <form v-if="editable" class="mt-5" method="post" :action="route('weddings.memberships.permissions.bulk', weddingId)">
            <input type="hidden" name="_token" :value="csrfToken">
            <fieldset v-for="member in memberships" :key="member.id" class="mt-5 border-t border-stone-200 pt-5 first:mt-0">
                <legend class="font-medium text-stone-800">{{ member.user.name }} <span class="ml-1 text-sm font-normal text-stone-500">· {{ member.relationship }}</span></legend>
                <div class="mt-3 grid gap-2 sm:grid-cols-2 lg:grid-cols-3">
                    <label v-for="(label, key) in permissions" :key="key" class="permission-option">
                        <input :name="`permissions[${member.id}][${key}]`" type="checkbox" value="1" :checked="member.permissions?.[key] ?? false">
                        <span>{{ label }}</span>
                    </label>
                </div>
            </fieldset>
            <div class="mt-6 flex flex-wrap gap-3">
                <button type="submit" class="premium-button px-5 py-2.5 text-sm font-semibold">Guardar permisos</button>
                <button type="submit" name="activate_all" value="1" class="rounded-full border border-rose-700 px-5 py-2.5 text-sm font-semibold text-rose-800">Activar todos y guardar</button>
            </div>
        </form>

        <div v-else class="mt-5 space-y-4">
            <article v-for="member in memberships" :key="member.id" class="border-t border-stone-200 pt-4 first:border-0 first:pt-0">
                <b>{{ member.user.name }}</b>
                <p class="mt-1 text-sm text-stone-600">{{ activePermissions(member) }}</p>
            </article>
        </div>
    </section>
</template>

<style scoped>
.permission-option { display:flex; align-items:center; gap:.75rem; min-height:3rem; padding:.65rem .8rem; border:1px solid var(--line); border-radius:.8rem; background:#fffdfa; cursor:pointer; font-size:.875rem; transition:border-color .18s, background .18s; }
.permission-option:hover { border-color:var(--rose); background:#fff8f7; }
.permission-option input { width:1.05rem; height:1.05rem; accent-color:var(--wine); }
</style>
