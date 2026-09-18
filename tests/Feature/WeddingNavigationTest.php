<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Wedding;
use App\Models\WeddingMembership;
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
            ->assertInertia(fn ($page) => $page
                ->component('Weddings/Workspace')
                ->where('weddingContext.wedding.id', $wedding->id)
                ->has('weddingContext.items', 11));
    }

    public function test_permissions_are_saved_as_one_multiple_selection_form(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $partner = User::factory()->create(['role' => 'couple']);
        $wedding = Wedding::create(['name' => 'Permisos', 'slug' => 'permisos']);
        $membership = WeddingMembership::create([
            'wedding_id' => $wedding->id,
            'user_id' => $partner->id,
            'relationship' => 'partner',
            'permissions' => ['view_progress' => true],
        ]);

        $this->actingAs($admin)
            ->post(route('weddings.memberships.permissions.bulk', $wedding), [
                'permissions' => [
                    $membership->id => [
                        'view_progress' => '1',
                        'share_invitation' => '1',
                        'view_finance' => '1',
                    ],
                ],
            ])
            ->assertRedirect();

        $this->assertSame([
            'view_progress' => true,
            'edit_wedding' => false,
            'create_requests' => false,
            'approve_proposals' => false,
            'share_invitation' => true,
            'view_finance' => true,
            'scan_passes' => false,
        ], $membership->fresh()->permissions);
    }
}
