<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WeddingService extends Model
{
    protected $fillable = ['wedding_id', 'wedding_service_template_id', 'name', 'category', 'owner', 'status', 'estimated_cost', 'details'];
    protected function casts(): array { return ['details' => 'array', 'estimated_cost' => 'decimal:2']; }
    public function wedding(): BelongsTo { return $this->belongsTo(Wedding::class); }
    public function template(): BelongsTo { return $this->belongsTo(WeddingServiceTemplate::class, 'wedding_service_template_id'); }
}
