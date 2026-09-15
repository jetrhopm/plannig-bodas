<?php
namespace Tests\Feature;
use App\Models\User;
use App\Models\Wedding;
use App\Models\WeddingFinanceItem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
class FinanceAccessTest extends TestCase { use RefreshDatabase; public function test_couple_only_sees_finance_items_explicitly_shared_with_them(): void { $couple = User::factory()->create(['role' => 'couple']); $wedding = Wedding::create(['name' => 'Finanzas', 'slug' => 'finanzas']); $wedding->memberships()->create(['user_id' => $couple->id, 'relationship' => 'partner', 'permissions' => ['view_progress' => true, 'view_finance' => true]]); WeddingFinanceItem::create(['wedding_id' => $wedding->id, 'type' => 'cost', 'description' => 'Margen interno', 'amount' => 100, 'visible_to_couple' => false]); WeddingFinanceItem::create(['wedding_id' => $wedding->id, 'type' => 'charge', 'description' => 'Anticipo', 'amount' => 200, 'visible_to_couple' => true]); $response = $this->actingAs($couple)->get(route('weddings.finance.index', $wedding)); $response->assertOk()->assertInertia(fn ($page) => $page->has('items', 1)->where('items.0.description', 'Anticipo')); } }
