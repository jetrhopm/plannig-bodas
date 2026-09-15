<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Carbon\Carbon;

class WeddingEvent extends Model
{
    protected $fillable = ['wedding_id', 'name', 'type', 'event_date', 'event_time', 'starts_at', 'venue', 'address', 'is_primary', 'visibility'];
    protected function casts(): array { return ['event_date' => 'date', 'visibility' => 'array', 'is_primary' => 'boolean']; }
    protected function startsAt(): Attribute { return Attribute::make(get: fn (?string $value) => $value ? Carbon::parse($value, 'UTC') : null); }
    public function wedding(): BelongsTo { return $this->belongsTo(Wedding::class); }
}
