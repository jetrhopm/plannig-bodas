<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class WeddingAgendaItem extends Model { protected $fillable = ['wedding_id','title','starts_at','ends_at','location','category','notes']; protected function casts(): array { return ['starts_at' => 'datetime', 'ends_at' => 'datetime']; } }
