<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CapacityExpansionRequest extends Model
{
    protected $fillable = ['wedding_id', 'requested_by', 'requested_count', 'status', 'reason', 'additional_cost', 'decision_reason', 'proposal_version', 'proposed_by', 'proposed_at', 'responded_by', 'responded_at', 'decided_by', 'decided_at'];
    protected function casts(): array { return ['additional_cost' => 'decimal:2', 'proposed_at' => 'datetime', 'responded_at' => 'datetime', 'decided_at' => 'datetime']; }
    public function wedding(): BelongsTo { return $this->belongsTo(Wedding::class); }
    public function requester(): BelongsTo { return $this->belongsTo(User::class, 'requested_by'); }
}
