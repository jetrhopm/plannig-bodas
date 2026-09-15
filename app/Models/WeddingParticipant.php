<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class WeddingParticipant extends Model { protected $fillable = ['wedding_id','name','role','phone','schedule_note']; }
