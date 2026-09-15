<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WeddingMembership extends Model
{
    protected $fillable = ['wedding_id', 'user_id', 'relationship', 'permissions'];
    protected function casts(): array { return ['permissions' => 'array']; }
    public function wedding(): BelongsTo { return $this->belongsTo(Wedding::class); }
    public function user(): BelongsTo { return $this->belongsTo(User::class); }
}
