<?php

namespace App\Support;

use App\Models\User;
use App\Models\Wedding;

class WeddingNavigation
{
    /**
     * Builds the visible wedding navigation from the same effective permissions
     * that protect the server-side actions. The Vue layer never grants access.
     */
    public static function for(User $user, Wedding $wedding): array
    {
        $nextEvent = $wedding->events()->orderByDesc('is_primary')->orderBy('event_date')->first();
        $membership = $user->weddingMemberships()->where('wedding_id', $wedding->id)->first();
        $canEdit = $user->can('update', $wedding);
        $canFinance = $user->role === 'admin' || (bool) data_get($membership?->permissions, 'view_finance');
        $canReceive = $user->role === 'admin'
            || $wedding->coordinator_id === $user->id
            || (bool) data_get($membership?->permissions, 'scan_passes')
            || (bool) data_get($membership?->permissions, 'edit_wedding');
        $canPlan = ! in_array($user->role, ['finance', 'reception'], true);
        $canViewProfile = ! in_array($user->role, ['finance', 'reception'], true);

        return [
            'wedding' => [
                'id' => $wedding->id,
                'name' => $wedding->name,
                'next_event' => $nextEvent ? [
                    'date' => $nextEvent->event_date?->toDateString(),
                    'venue' => $nextEvent->venue,
                ] : null,
            ],
            'items' => [
                ['label' => 'Centro', 'route' => 'weddings.workspace', 'icon' => 'center', 'description' => 'Vista general', 'visible' => true],
                ['label' => 'Expediente', 'route' => 'weddings.show', 'icon' => 'dossier', 'description' => 'Datos y configuración', 'visible' => $canViewProfile],
                ['label' => 'Crear evento', 'route' => 'weddings.events.create', 'icon' => 'event', 'description' => 'Nuevo momento', 'visible' => $canEdit],
                ['label' => 'Familias', 'route' => 'weddings.families.index', 'icon' => 'families', 'description' => 'Invitados y RSVPs', 'visible' => $canEdit],
                ['label' => 'Planeación', 'route' => 'weddings.planning.index', 'icon' => 'planning', 'description' => 'Cronograma', 'visible' => $canPlan],
                ['label' => 'Mesas', 'route' => 'weddings.planning.seating', 'icon' => 'tables', 'description' => 'Distribución', 'visible' => $canEdit],
                ['label' => 'Logística', 'route' => 'weddings.planning.logistics', 'icon' => 'logistics', 'description' => 'Proveedores', 'visible' => $canEdit],
                ['label' => 'Finanzas', 'route' => 'weddings.finance.index', 'icon' => 'finance', 'description' => 'Presupuesto', 'visible' => $canFinance],
                ['label' => 'Regalos', 'route' => 'weddings.gifts.manage', 'icon' => 'gifts', 'description' => 'Mesa de regalos', 'visible' => $canEdit],
                ['label' => 'Recepción', 'route' => 'weddings.reception.index', 'icon' => 'reception', 'description' => 'Día del evento', 'visible' => $canReceive],
                ['label' => 'Entrevista', 'route' => 'weddings.interview.edit', 'icon' => 'interview', 'description' => 'Pareja / Cliente', 'visible' => $canEdit],
            ],
        ];
    }
}
