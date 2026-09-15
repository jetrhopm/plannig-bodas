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
            'wedding' => ['id' => $wedding->id, 'name' => $wedding->name],
            'items' => [
                ['label' => 'Centro', 'route' => 'weddings.workspace', 'visible' => true],
                ['label' => 'Expediente', 'route' => 'weddings.show', 'visible' => $canViewProfile],
                ['label' => 'Crear evento', 'route' => 'weddings.events.create', 'visible' => $canEdit],
                ['label' => 'Familias', 'route' => 'weddings.families.index', 'visible' => $canEdit],
                ['label' => 'Planeación', 'route' => 'weddings.planning.index', 'visible' => $canPlan],
                ['label' => 'Mesas', 'route' => 'weddings.planning.seating', 'visible' => $canEdit],
                ['label' => 'Logística', 'route' => 'weddings.planning.logistics', 'visible' => $canEdit],
                ['label' => 'Finanzas', 'route' => 'weddings.finance.index', 'visible' => $canFinance],
                ['label' => 'Regalos', 'route' => 'weddings.gifts.manage', 'visible' => $canEdit],
                ['label' => 'Recepción', 'route' => 'weddings.reception.index', 'visible' => $canReceive],
                ['label' => 'Entrevista', 'route' => 'weddings.interview.edit', 'visible' => $canEdit],
            ],
        ];
    }
}
