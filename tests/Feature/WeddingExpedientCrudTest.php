<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Wedding;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WeddingExpedientCrudTest extends TestCase
{
    use RefreshDatabase;

    private function setupWedding(): array
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $wedding = Wedding::create([
            'name' => 'Boda original',
            'slug' => 'boda-original',
            'status' => 'consultation',
            'support_type' => 'full',
            'timezone' => 'America/Mexico_City',
        ]);

        return [$admin, $wedding];
    }

    public function test_admin_can_edit_the_initial_expedient_without_creating_another_wedding(): void
    {
        [$admin, $wedding] = $this->setupWedding();

        $this->actingAs($admin)->patch(route('weddings.update', $wedding), [
            'name' => 'Boda actualizada',
            'status' => 'preparation',
            'support_type' => 'shared',
            'authorized_capacity' => 90,
            'timezone' => 'America/Mexico_City',
        ])->assertRedirect();

        $this->assertDatabaseHas('weddings', ['id' => $wedding->id, 'name' => 'Boda actualizada', 'status' => 'preparation', 'authorized_capacity' => 90]);
    }

    public function test_admin_can_update_and_delete_services_and_responsibilities(): void
    {
        [$admin, $wedding] = $this->setupWedding();
        $service = $wedding->services()->create(['name' => 'Banquete', 'owner' => 'company', 'status' => 'included']);
        $responsibility = $wedding->responsibilities()->create(['label' => 'Confirmar flores', 'owner' => 'couple', 'status' => 'pending']);

        $this->actingAs($admin)->patch(route('weddings.services.update', [$wedding, $service]), ['name' => 'Banquete actualizado', 'owner' => 'shared', 'status' => 'confirmed', 'estimated_cost' => 15000])->assertRedirect();
        $this->actingAs($admin)->patch(route('weddings.responsibilities.update', [$wedding, $responsibility]), ['label' => 'Confirmar flores actualizadas', 'owner' => 'company', 'status' => 'in_progress', 'due_date' => '2026-10-01', 'notes' => 'Llamar al proveedor'])->assertRedirect();

        $this->assertDatabaseHas('wedding_services', ['id' => $service->id, 'name' => 'Banquete actualizado', 'status' => 'confirmed']);
        $this->assertDatabaseHas('wedding_responsibilities', ['id' => $responsibility->id, 'label' => 'Confirmar flores actualizadas', 'status' => 'in_progress']);

        $this->actingAs($admin)->delete(route('weddings.services.destroy', [$wedding, $service]))->assertRedirect();
        $this->actingAs($admin)->delete(route('weddings.responsibilities.destroy', [$wedding, $responsibility]))->assertRedirect();

        $this->assertDatabaseMissing('wedding_services', ['id' => $service->id]);
        $this->assertDatabaseMissing('wedding_responsibilities', ['id' => $responsibility->id]);
    }
}
