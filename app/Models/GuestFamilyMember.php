<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GuestFamilyMember extends Model
{
    protected $fillable = ['guest_family_id', 'wedding_table_id', 'position', 'display_name', 'attending', 'dietary_restrictions', 'accessibility_needs'];
    protected function casts(): array { return ['attending' => 'boolean']; }
    public function family(): BelongsTo { return $this->belongsTo(GuestFamily::class, 'guest_family_id'); }
}
