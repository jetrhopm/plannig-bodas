<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FamilyNotification extends Model
{
    protected $fillable = ['guest_family_id', 'type', 'title', 'description', 'importance', 'read_at'];
    protected function casts(): array { return ['read_at' => 'datetime']; }
    public function family(): BelongsTo { return $this->belongsTo(GuestFamily::class, 'guest_family_id'); }
}
