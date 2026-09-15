<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WeddingResponsibility extends Model
{
    protected $fillable = ['wedding_id', 'label', 'owner', 'status', 'due_date', 'notes'];
    protected function casts(): array { return ['due_date' => 'date']; }
    public function wedding(): BelongsTo { return $this->belongsTo(Wedding::class); }
}
