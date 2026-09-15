<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AdditionalSeatRequest extends Model
{
    protected $fillable = ['guest_family_id', 'requested_count', 'approved_count', 'status', 'request_note', 'decision_reason', 'decided_by', 'decided_at'];
    protected function casts(): array { return ['decided_at' => 'datetime']; }
    public function family(): BelongsTo { return $this->belongsTo(GuestFamily::class, 'guest_family_id'); }
    public function decider(): BelongsTo { return $this->belongsTo(User::class, 'decided_by'); }
}
