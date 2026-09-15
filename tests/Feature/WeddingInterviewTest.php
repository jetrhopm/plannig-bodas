<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Wedding;
use App\Models\WeddingEvent;
use App\Models\WeddingInterview;
use Tests\TestCase;

class WeddingInterviewTest extends TestCase
{
    use RefreshDatabase;

    public function test_authorized_administrator_can_save_and_resume_an_interview(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $wedding = Wedding::create(['name' => 'Boda entrevista', 'slug' => 'boda-entrevista']);

        $this->actingAs($admin)->put(route('weddings.interview.update', $wedding), [
            'current_step' => 2, 'contact_name' => 'Mariana López', 'contact_email' => 'mariana@example.test',
            'support_type' => 'shared', 'authorized_capacity' => 90, 'estimated_budget' => 180000,
            'timezone' => 'America/Mexico_City', 'notes' => 'Ceremonia civil pendiente.',
        ])->assertRedirect();

        $interview = WeddingInterview::where('wedding_id', $wedding->id)->firstOrFail();
        $this->assertSame(2, $interview->current_step);
        $this->assertSame('Mariana López', $interview->answers['contact_name']);
        $this->assertSame('shared', $wedding->fresh()->support_type);
        $this->assertDatabaseCount('wedding_audits', 1);
    }

    public function test_event_start_is_saved_as_a_utc_instant(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $wedding = Wedding::create(['name' => 'Boda fecha', 'slug' => 'boda-fecha', 'timezone' => 'America/Mexico_City']);
        $event = WeddingEvent::create(['wedding_id' => $wedding->id, 'name' => 'Recepción', 'type' => 'reception', 'is_primary' => true]);

        $this->actingAs($admin)->put(route('weddings.events.update', [$wedding, $event]), [
            'name' => 'Recepción', 'type' => 'reception', 'event_date' => '2027-02-20', 'event_time' => '18:30', 'venue' => 'Casa Jardín', 'is_primary' => true,
        ])->assertRedirect();

        $this->assertSame('2027-02-21 00:30:00', $event->fresh()->starts_at->utc()->format('Y-m-d H:i:s'));
    }

    public function test_configuration_activation_notifies_the_couple_and_is_audited(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $couple = User::factory()->create(['role' => 'couple']);
        $wedding = Wedding::create(['name' => 'Boda activa', 'slug' => 'boda-activa']);
        WeddingInterview::create(['wedding_id' => $wedding->id, 'answers' => []]);
        \App\Models\WeddingMembership::create(['wedding_id' => $wedding->id, 'user_id' => $couple->id, 'relationship' => 'partner', 'permissions' => ['view_progress' => true]]);

        $this->actingAs($admin)->post(route('weddings.configuration.activate', $wedding))->assertRedirect();

        $this->assertSame('active', $wedding->interview->fresh()->status);
        $this->assertSame(1, $couple->notifications()->count());
        $this->assertDatabaseHas('wedding_audits', ['wedding_id' => $wedding->id, 'action' => 'configuration.activated']);
    }
}
