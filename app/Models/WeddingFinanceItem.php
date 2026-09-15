<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
class WeddingFinanceItem extends Model { protected $fillable = ['wedding_id','type','description','amount','due_date','status','visible_to_couple','notes']; protected function casts(): array { return ['amount' => 'decimal:2','due_date' => 'date','visible_to_couple' => 'boolean']; } public function wedding(): \Illuminate\Database\Eloquent\Relations\BelongsTo { return $this->belongsTo(Wedding::class); } public function payments(): HasMany { return $this->hasMany(WeddingPayment::class); } public function attachments(): HasMany { return $this->hasMany(FinanceAttachment::class); } }
