<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class CommunicationPreference extends Model { protected $fillable=['user_id','email_enabled','whatsapp_enabled']; protected function casts(): array { return ['email_enabled'=>'boolean','whatsapp_enabled'=>'boolean']; } }
