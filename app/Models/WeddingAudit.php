<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WeddingAudit extends Model
{
    protected $fillable = ['wedding_id', 'actor_id', 'action', 'subject_type', 'subject_id', 'before', 'after', 'request_id'];
    protected function casts(): array { return ['before' => 'array', 'after' => 'array']; }
    public function wedding(): BelongsTo { return $this->belongsTo(Wedding::class); }
    public function actor(): BelongsTo { return $this->belongsTo(User::class, 'actor_id'); }
}
