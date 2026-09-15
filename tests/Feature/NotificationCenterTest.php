<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use Tests\TestCase;

class NotificationCenterTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_sees_only_own_notifications_and_can_mark_them_as_read(): void
    {
        $this->withoutVite();
        $user = User::factory()->create();
        $other = User::factory()->create();
        $own = $user->notifications()->create(['id' => '00000000-0000-4000-8000-000000000101', 'type' => 'test', 'data' => ['title' => 'Aviso propio']]);
        $other->notifications()->create(['id' => '00000000-0000-4000-8000-000000000102', 'type' => 'test', 'data' => ['title' => 'Aviso ajeno']]);

        $this->actingAs($user)->get(route('notifications.index'))
            ->assertOk()
            ->assertSee('Aviso propio')
            ->assertDontSee('Aviso ajeno');

        $this->actingAs($user)->patch(route('notifications.read', $own->id))->assertRedirect();
        $this->assertNotNull($own->fresh()->read_at);
        $this->actingAs($user)->patch(route('notifications.read', '00000000-0000-4000-8000-000000000102'))->assertNotFound();
    }
}
