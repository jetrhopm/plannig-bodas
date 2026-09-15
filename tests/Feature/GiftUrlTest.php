<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Wedding;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GiftUrlTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_long_store_url_can_be_saved_for_a_gift(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $wedding = Wedding::create(['name' => 'Boda', 'slug' => 'boda-regalo', 'timezone' => 'America/Mexico_City']);
        $url = 'https://www.amazon.com.mx/dp/example?'.http_build_query(['tag' => str_repeat('x', 800)]);

        $this->actingAs($admin)->post(route('weddings.gifts.store', $wedding), [
            'name' => 'Regalo de prueba',
            'quantity' => 1,
            'reference_url' => $url,
        ])->assertRedirect();

        $this->assertDatabaseHas('wedding_gifts', ['wedding_id' => $wedding->id, 'reference_url' => $url]);
    }
}
