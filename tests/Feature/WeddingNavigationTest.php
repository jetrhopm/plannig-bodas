<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Wedding;
use App\Support\WeddingNavigation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WeddingNavigationTest extends TestCase
{
    use RefreshDatabase;

    public function test_assigned_coordinator_sees_operational_links_but_not_finance(): void
    {
        $coordinator = User::factory()->create(['role' => 'coordinator']);
        $wedding = Wedding::create(['name' => 'Boda operativa', 'slug' => 'boda-operativa', 'coordinator_id' => $coordinator->id]);

        $items = collect(WeddingNavigation::for($coordinator, $wedding)['items'])->keyBy('route');

        $this->assertTrue($items['weddings.events.create']['visible']);
        $this->assertTrue($items['weddings.reception.index']['visible']);
        $this->assertFalse($items['weddings.finance.index']['visible']);
    }

    public function test_wedding_pages_receive_the_navigation_context(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $wedding = Wedding::create(['name' => 'Boda visible', 'slug' => 'boda-visible']);

        $this->actingAs($admin)->get(route('weddings.workspace', $wedding))
            ->assertOk()
            ->assertViewIs('weddings.workspace')
            ->assertViewHas('navigation', fn ($navigation) =>
                $navigation['wedding']['id'] === $wedding->id
                && count($navigation['items']) === 11);
    }
}
