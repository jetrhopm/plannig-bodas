<?php

namespace Tests\Feature;

use App\Models\GuestFamily;
use App\Models\User;
use App\Models\Wedding;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReceptionTest extends TestCase
{
    use RefreshDatabase;

    public function test_four_then_two_entries_complete_a_six_person_pass_without_duplicates(): void
    {
        $operator = User::factory()->create(['role' => 'reception']);
        $wedding = Wedding::create(['name' => 'Accesos', 'slug' => 'accesos', 'authorized_capacity' => 10]);
        $wedding->memberships()->create(['user_id' => $operator->id, 'relationship' => 'reception', 'permissions' => ['scan_passes' => true]]);
        $family = GuestFamily::create(['wedding_id' => $wedding->id, 'label' => 'Familia entrada', 'original_allocation' => 6, 'current_allocation' => 6, 'rsvp_status' => 'confirmed', 'attending_count' => 6, 'invite_token_hash' => hash('sha256', 'entry')]);

        $this->actingAs($operator)->post(route('weddings.reception.record', [$wedding, $family]), ['count' => 4, 'idempotency_key' => '11111111-1111-4111-8111-111111111111'])->assertRedirect();
        $this->actingAs($operator)->post(route('weddings.reception.record', [$wedding, $family]), ['count' => 4, 'idempotency_key' => '11111111-1111-4111-8111-111111111111'])->assertRedirect();
        $this->actingAs($operator)->post(route('weddings.reception.record', [$wedding, $family]), ['count' => 2, 'idempotency_key' => '22222222-2222-4222-8222-222222222222'])->assertRedirect();
        $this->actingAs($operator)->post(route('weddings.reception.record', [$wedding, $family]), ['count' => 1, 'idempotency_key' => '33333333-3333-4333-8333-333333333333'])->assertSessionHasErrors('count');
        $this->assertSame(6, (int) $family->accessEntries()->sum('count'));
    }
}
