<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\GuestFamily;
use App\Models\Wedding;
use App\Models\User;
use App\Models\WeddingMembership;
use Tests\TestCase;

class RsvpTest extends TestCase
{
    use RefreshDatabase;

    public function test_confirming_four_of_six_releases_exactly_two_seats_and_is_idempotent(): void
    {
        $wedding = Wedding::create(['name' => 'Boda RSVP', 'slug' => 'boda-rsvp', 'authorized_capacity' => 20]);
        $token = 'family-token-for-test';
        $family = GuestFamily::create(['wedding_id' => $wedding->id, 'label' => 'Familia García', 'original_allocation' => 6, 'current_allocation' => 6, 'invite_token_hash' => hash('sha256', $token)]);
        $payload = ['response' => 'confirmed', 'attending_count' => 4, 'idempotency_key' => '00000000-0000-4000-8000-000000000201'];

        $this->post(route('invitation.rsvp', $token), $payload)->assertRedirect();
        $this->post(route('invitation.rsvp', $token), $payload)->assertRedirect();
        $family->refresh();
        $this->assertSame(4, $family->current_allocation);
        $this->assertSame(4, $family->attending_count);
        $this->assertSame('confirmed', $family->rsvp_status);
        $this->assertSame(1, $family->responses()->count());
    }

    public function test_declining_releases_all_seats_and_unknown_token_is_not_available(): void
    {
        $wedding = Wedding::create(['name' => 'Boda RSVP dos', 'slug' => 'boda-rsvp-dos', 'authorized_capacity' => 10]);
        $token = 'second-family-token';
        $family = GuestFamily::create(['wedding_id' => $wedding->id, 'label' => 'Familia Ruiz', 'original_allocation' => 3, 'current_allocation' => 3, 'invite_token_hash' => hash('sha256', $token)]);
        $this->get(route('invitation.show', 'not-a-token'))->assertNotFound();
        $this->post(route('invitation.rsvp', $token), ['response' => 'declined', 'idempotency_key' => '00000000-0000-4000-8000-000000000202'])->assertRedirect();
        $this->assertSame(0, $family->fresh()->current_allocation);
        $this->assertSame('declined', $family->fresh()->rsvp_status);
    }

    public function test_couple_without_effective_permission_cannot_create_families(): void
    {
        $couple = User::factory()->create(['role' => 'couple']);
        $wedding = Wedding::create(['name' => 'Boda protegida', 'slug' => 'boda-protegida', 'authorized_capacity' => 10]);
        WeddingMembership::create(['wedding_id' => $wedding->id, 'user_id' => $couple->id, 'relationship' => 'partner', 'permissions' => ['view_progress' => true]]);

        $this->actingAs($couple)->post(route('weddings.families.store', $wedding), ['label' => 'Familia no autorizada', 'allocation' => 2])->assertForbidden();
        $this->assertDatabaseCount('guest_families', 0);
    }
}
