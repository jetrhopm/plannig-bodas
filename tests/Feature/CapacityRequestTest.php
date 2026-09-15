<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\AdditionalSeatRequest;
use App\Models\GuestFamily;
use App\Models\User;
use App\Models\Wedding;
use Tests\TestCase;

class CapacityRequestTest extends TestCase
{
    use RefreshDatabase;

    public function test_approval_rechecks_availability_and_does_not_overassign(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $wedding = Wedding::create(['name' => 'Boda cupo', 'slug' => 'boda-cupo', 'authorized_capacity' => 5]);
        $familyA = GuestFamily::create(['wedding_id' => $wedding->id, 'label' => 'Familia A', 'original_allocation' => 4, 'current_allocation' => 4, 'invite_token_hash' => hash('sha256', 'a')]);
        $familyB = GuestFamily::create(['wedding_id' => $wedding->id, 'label' => 'Familia B', 'original_allocation' => 0, 'current_allocation' => 0, 'invite_token_hash' => hash('sha256', 'b')]);
        $requestA = AdditionalSeatRequest::create(['guest_family_id' => $familyA->id, 'requested_count' => 1]);
        $requestB = AdditionalSeatRequest::create(['guest_family_id' => $familyB->id, 'requested_count' => 1]);

        $this->actingAs($admin)->patch(route('weddings.additional-seats.resolve', [$wedding, $requestA]), ['decision' => 'approved', 'approved_count' => 1])->assertRedirect();
        $this->actingAs($admin)->patch(route('weddings.additional-seats.resolve', [$wedding, $requestB]), ['decision' => 'approved', 'approved_count' => 1])->assertSessionHasErrors('approved_count');
        $this->assertSame(5, $familyA->fresh()->current_allocation);
        $this->assertSame(0, $familyB->fresh()->current_allocation);
        $this->assertSame('pending', $requestB->fresh()->status);
    }

    public function test_capacity_expansion_does_not_assign_places_to_a_family(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $couple = User::factory()->create(['role' => 'couple']);
        $wedding = Wedding::create(['name' => 'Boda ampliación', 'slug' => 'boda-ampliacion', 'authorized_capacity' => 10]);
        $wedding->memberships()->create(['user_id' => $couple->id, 'relationship' => 'partner', 'permissions' => ['view_progress' => true, 'approve_proposals' => true]]);
        $family = GuestFamily::create(['wedding_id' => $wedding->id, 'label' => 'Familia C', 'original_allocation' => 10, 'current_allocation' => 10, 'invite_token_hash' => hash('sha256', 'c')]);
        $this->actingAs($admin)->post(route('weddings.capacity-expansions.store', $wedding), ['requested_count' => 5, 'reason' => 'Más invitados'])->assertRedirect();
        $request = $wedding->capacityExpansionRequests()->firstOrFail();
        $this->actingAs($admin)->patch(route('weddings.capacity-expansions.propose', [$wedding, $request]), ['additional_cost' => 2500, 'decision_reason' => 'Sujeto a disponibilidad del banquete'])->assertRedirect();
        $this->actingAs($couple)->patch(route('weddings.capacity-expansions.respond', [$wedding, $request]), ['decision' => 'accepted'])->assertRedirect();
        $this->actingAs($admin)->patch(route('weddings.capacity-expansions.resolve', [$wedding, $request]), ['decision' => 'approved'])->assertRedirect();
        $this->assertSame(15, $wedding->fresh()->authorized_capacity);
        $this->assertSame(10, $family->fresh()->current_allocation);
        $this->assertSame('approved', $request->fresh()->status);
    }
}
