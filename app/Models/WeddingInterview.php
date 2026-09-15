<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WeddingInterview extends Model
{
    protected $fillable = ['wedding_id', 'current_step', 'status', 'answers', 'last_saved_at'];
    protected function casts(): array { return ['answers' => 'array', 'last_saved_at' => 'datetime']; }
    public function wedding(): BelongsTo { return $this->belongsTo(Wedding::class); }
}
