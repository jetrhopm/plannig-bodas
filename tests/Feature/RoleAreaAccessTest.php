<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Wedding;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleAreaAccessTest extends TestCase
{
    use RefreshDatabase;

    private function weddingWith(User $user, array $permissions = []): Wedding
    {
        $wedding = Wedding::create(['name' => 'Boda de prueba', 'slug' => 'boda-de-prueba', 'timezone' => 'America/Mexico_City']);
        $wedding->memberships()->create([
            'user_id' => $user->id,
            'relationship' => 'test',
            'permissions' => ['view_progress' => true, ...$permissions],
        ]);

        return $wedding;
    }

    public function test_finance_can_only_open_its_financial_area(): void
    {
        $user = User::factory()->create(['role' => 'finance']);
        $wedding = $this->weddingWith($user, ['view_finance' => true]);

        $this->actingAs($user)->get(route('weddings.finance.index', $wedding))->assertOk();
        $this->actingAs($user)->get(route('weddings.planning.index', $wedding))->assertForbidden();
        $this->actingAs($user)->get(route('weddings.families.index', $wedding))->assertForbidden();
        $this->actingAs($user)->get(route('weddings.show', $wedding))->assertForbidden();
    }

    public function test_reception_can_only_open_its_reception_area(): void
    {
        $user = User::factory()->create(['role' => 'reception']);
        $wedding = $this->weddingWith($user, ['scan_passes' => true]);

        $this->actingAs($user)->get(route('weddings.reception.index', $wedding))->assertOk();
        $this->actingAs($user)->get(route('weddings.planning.index', $wedding))->assertForbidden();
        $this->actingAs($user)->get(route('weddings.families.index', $wedding))->assertForbidden();
        $this->actingAs($user)->get(route('weddings.show', $wedding))->assertForbidden();
    }

    public function test_couple_can_follow_progress_but_cannot_manage_guest_lists(): void
    {
        $user = User::factory()->create(['role' => 'couple']);
        $wedding = $this->weddingWith($user, ['approve_proposals' => true]);

        $this->actingAs($user)->get(route('weddings.show', $wedding))->assertOk();
        $this->actingAs($user)->get(route('weddings.planning.index', $wedding))->assertOk();
        $this->actingAs($user)->get(route('weddings.families.index', $wedding))->assertForbidden();
        $this->actingAs($user)->get(route('weddings.planning.seating', $wedding))->assertForbidden();
        $this->actingAs($user)->get(route('weddings.reception.index', $wedding))->assertForbidden();
    }
}
