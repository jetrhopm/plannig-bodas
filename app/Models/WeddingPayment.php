<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class WeddingPayment extends Model { protected $fillable = ['wedding_finance_item_id','amount','paid_at','method','reference','recorded_by']; protected function casts(): array { return ['amount' => 'decimal:2','paid_at' => 'date']; } }
