<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Wedding;
use App\Models\WeddingMembership;
use App\Models\WeddingProposal;
use App\Models\WeddingTask;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardRolePanelsTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_receives_operational_summary_for_its_weddings(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $wedding = Wedding::create(['name' => 'Boda operativa', 'slug' => 'boda-operativa']);
        WeddingTask::create(['wedding_id' => $wedding->id, 'title' => 'Confirmar flores', 'priority' => 'high', 'status' => 'pending']);

        $this->actingAs($admin)->get(route('dashboard'))
            ->assertOk()
            ->assertViewIs('dashboard')
            ->assertViewHas('role', 'admin')
            ->assertViewHas('roleSummary', fn ($summary) =>
                count($summary['tasks']) === 1
                && $summary['tasks'][0]->title === 'Confirmar flores');
    }

    public function test_couple_receives_only_its_pending_proposals(): void
    {
        $couple = User::factory()->create(['role' => 'couple']);
        $ownWedding = Wedding::create(['name' => 'Boda propia', 'slug' => 'boda-propia']);
        $otherWedding = Wedding::create(['name' => 'Boda ajena', 'slug' => 'boda-ajena']);
        WeddingMembership::create(['wedding_id' => $ownWedding->id, 'user_id' => $couple->id, 'relationship' => 'partner', 'permissions' => ['view_progress' => true]]);
        WeddingProposal::create(['wedding_id' => $ownWedding->id, 'proposal_key' => 'own-key', 'version' => 1, 'title' => 'Flores propias', 'status' => 'sent']);
        WeddingProposal::create(['wedding_id' => $otherWedding->id, 'proposal_key' => 'other-key', 'version' => 1, 'title' => 'Flores ajenas', 'status' => 'sent']);

        $this->actingAs($couple)->get(route('dashboard'))
            ->assertOk()
            ->assertViewIs('dashboard')
            ->assertViewHas('role', 'couple')
            ->assertViewHas('roleSummary', fn ($summary) =>
                count($summary['proposals']) === 1
                && $summary['proposals'][0]->title === 'Flores propias');
    }
}
