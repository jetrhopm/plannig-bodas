<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class WeddingMenuOption extends Model { protected $fillable = ['wedding_id','name','description','is_active']; protected function casts(): array { return ['is_active' => 'boolean']; } }
