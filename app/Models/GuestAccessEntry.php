<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class GuestAccessEntry extends Model { protected $fillable = ['guest_family_id', 'user_id', 'count', 'idempotency_key', 'entered_at']; protected function casts(): array { return ['entered_at' => 'datetime']; } public function family(): BelongsTo { return $this->belongsTo(GuestFamily::class, 'guest_family_id'); } public function operator(): BelongsTo { return $this->belongsTo(User::class, 'user_id'); } }
