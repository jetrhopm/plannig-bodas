<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Wedding extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug', 'status', 'support_type', 'authorized_capacity', 'timezone', 'coordinator_id', 'settings'];
    protected function casts(): array { return ['settings' => 'array']; }
    public function coordinator(): BelongsTo { return $this->belongsTo(User::class, 'coordinator_id'); }
    public function memberships(): HasMany { return $this->hasMany(WeddingMembership::class); }
    public function events(): HasMany { return $this->hasMany(WeddingEvent::class); }
    public function interview(): \Illuminate\Database\Eloquent\Relations\HasOne { return $this->hasOne(WeddingInterview::class); }
    public function services(): HasMany { return $this->hasMany(WeddingService::class); }
    public function responsibilities(): HasMany { return $this->hasMany(WeddingResponsibility::class); }
    public function audits(): HasMany { return $this->hasMany(WeddingAudit::class); }
    public function guestFamilies(): HasMany { return $this->hasMany(GuestFamily::class); }
    public function capacityExpansionRequests(): HasMany { return $this->hasMany(CapacityExpansionRequest::class); }
    public function tasks(): HasMany { return $this->hasMany(WeddingTask::class); }
    public function agendaItems(): HasMany { return $this->hasMany(WeddingAgendaItem::class); }
    public function proposals(): HasMany { return $this->hasMany(WeddingProposal::class); }
    public function participants(): HasMany { return $this->hasMany(WeddingParticipant::class); }
    public function menuOptions(): HasMany { return $this->hasMany(WeddingMenuOption::class); }
    public function tables(): HasMany { return $this->hasMany(WeddingTable::class); }
    public function logistics(): HasMany { return $this->hasMany(WeddingLogistic::class); }
    public function inspirations(): HasMany { return $this->hasMany(WeddingInspiration::class); }
    public function financeItems(): HasMany { return $this->hasMany(WeddingFinanceItem::class); }
    public function vendorQuotes(): HasMany { return $this->hasMany(VendorQuote::class); }
    public function gifts(): HasMany { return $this->hasMany(WeddingGift::class); }
}
