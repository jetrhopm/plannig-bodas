<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Wedding;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WeddingCreateTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_a_new_wedding_and_initial_interview(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)->post(route('weddings.store'), [
            'name' => 'Valeria & Rodrigo',
            'status' => 'consultation',
            'support_type' => 'full',
            'authorized_capacity' => 150,
            'timezone' => 'America/Mexico_City',
        ])->assertRedirect();

        $wedding = Wedding::where('slug', 'valeria-rodrigo')->firstOrFail();
        $this->assertSame(150, $wedding->authorized_capacity);
        $this->assertDatabaseHas('wedding_interviews', ['wedding_id' => $wedding->id, 'current_step' => 1, 'status' => 'draft']);
        $this->assertDatabaseHas('wedding_audits', ['wedding_id' => $wedding->id, 'action' => 'wedding.created', 'actor_id' => $admin->id]);
    }

    public function test_non_admin_cannot_create_a_wedding(): void
    {
        $coordinator = User::factory()->create(['role' => 'coordinator']);

        $this->actingAs($coordinator)->get(route('weddings.create'))->assertForbidden();
    }
}
