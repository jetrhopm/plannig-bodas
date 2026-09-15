<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GuestFamily extends Model
{
    protected $fillable = ['wedding_id', 'label', 'responsible_name', 'responsible_email', 'responsible_phone', 'original_allocation', 'current_allocation', 'rsvp_status', 'attending_count', 'invite_token_hash', 'invite_revoked_at'];
    protected function casts(): array { return ['invite_revoked_at' => 'datetime']; }
    public function wedding(): BelongsTo { return $this->belongsTo(Wedding::class); }
    public function members(): HasMany { return $this->hasMany(GuestFamilyMember::class); }
    public function responses(): HasMany { return $this->hasMany(RsvpResponse::class); }
    public function notifications(): HasMany { return $this->hasMany(FamilyNotification::class); }
    public function additionalSeatRequests(): HasMany { return $this->hasMany(AdditionalSeatRequest::class); }
    public function accessEntries(): HasMany { return $this->hasMany(GuestAccessEntry::class); }
}
