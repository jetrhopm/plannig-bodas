<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Wedding;

class WeddingPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->isInternal() || $user->weddingMemberships()->exists();
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Wedding $wedding): bool
    {
        if ($user->role === 'admin' || $wedding->coordinator_id === $user->id) {
            return true;
        }

        $membership = $user->weddingMemberships()->where('wedding_id', $wedding->id)->first();

        return $membership !== null && ($user->isInternal() || (bool) data_get($membership->permissions, 'view_progress'));
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->role === 'admin';
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Wedding $wedding): bool
    {
        if ($user->role === 'admin' || $wedding->coordinator_id === $user->id) {
            return true;
        }

        $membership = $user->weddingMemberships()->where('wedding_id', $wedding->id)->first();

        return $membership !== null && (bool) data_get($membership->permissions, 'edit_wedding');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Wedding $wedding): bool
    {
        return $user->role === 'admin';
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Wedding $wedding): bool
    {
        return $this->delete($user, $wedding);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Wedding $wedding): bool
    {
        return $user->role === 'admin';
    }
}
