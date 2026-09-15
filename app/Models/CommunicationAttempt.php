<?php
namespace App\Models; use Illuminate\Database\Eloquent\Model; class CommunicationAttempt extends Model { protected $fillable=['user_id','channel','status','subject','body']; }
