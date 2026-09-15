<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class WeddingTable extends Model { protected $fillable = ['wedding_id','label','capacity']; public function members(): \Illuminate\Database\Eloquent\Relations\HasMany { return $this->hasMany(GuestFamilyMember::class); } }
