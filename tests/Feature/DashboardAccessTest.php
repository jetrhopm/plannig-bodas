<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Wedding;
use App\Models\WeddingMembership;
use Tests\TestCase;

class DashboardAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_couple_only_receives_weddings_where_they_are_a_member(): void
    {
        $couple = User::factory()->create(['role' => 'couple']);
        $ownWedding = Wedding::create(['name' => 'Boda propia', 'slug' => 'boda-propia']);
        Wedding::create(['name' => 'Boda ajena', 'slug' => 'boda-ajena']);
        WeddingMembership::create(['wedding_id' => $ownWedding->id, 'user_id' => $couple->id, 'relationship' => 'partner']);

        $this->actingAs($couple)->get('/dashboard')
            ->assertOk()
            ->assertSee('Boda propia')
            ->assertDontSee('Boda ajena');
    }

    public function test_a_couple_cannot_view_a_wedding_without_a_membership(): void
    {
        $couple = User::factory()->create(['role' => 'couple']);
        $wedding = Wedding::create(['name' => 'Boda privada', 'slug' => 'boda-privada']);

        $this->assertFalse($couple->can('view', $wedding));
    }
}
