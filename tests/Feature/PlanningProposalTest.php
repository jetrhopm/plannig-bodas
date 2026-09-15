<?php
namespace Tests\Feature;
use App\Models\User;
use App\Models\Wedding;
use App\Models\WeddingProposal;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
class PlanningProposalTest extends TestCase { use RefreshDatabase; public function test_revised_proposal_requires_a_new_approval(): void { $admin = User::factory()->create(['role' => 'admin']); $wedding = Wedding::create(['name' => 'Planeación', 'slug' => 'planeacion']); $original = WeddingProposal::create(['wedding_id' => $wedding->id, 'proposal_key' => '11111111-1111-4111-8111-111111111111', 'version' => 1, 'title' => 'Flores', 'status' => 'approved', 'created_by' => $admin->id]); $this->actingAs($admin)->post(route('weddings.planning.proposals.revise', [$wedding, $original]), ['title' => 'Flores ajustadas', 'amount' => 1200])->assertRedirect(); $revision = WeddingProposal::where('proposal_key', $original->proposal_key)->where('version', 2)->firstOrFail(); $this->assertSame('sent', $revision->status); $this->assertSame('approved', $original->fresh()->status); } }
