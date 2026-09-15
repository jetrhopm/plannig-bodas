<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RsvpResponse extends Model
{
    protected $fillable = ['guest_family_id', 'idempotency_key', 'response', 'attending_count', 'allocation_before', 'allocation_after', 'member_details', 'responded_at'];
    protected function casts(): array { return ['member_details' => 'array', 'responded_at' => 'datetime']; }
    public function family(): BelongsTo { return $this->belongsTo(GuestFamily::class, 'guest_family_id'); }
}
