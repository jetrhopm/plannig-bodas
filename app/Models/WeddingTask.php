<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class WeddingTask extends Model { protected $fillable = ['wedding_id','title','status','priority','assigned_to','due_date','notes']; protected function casts(): array { return ['due_date' => 'date']; } }
