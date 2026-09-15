<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class WeddingProposal extends Model { protected $fillable = ['wedding_id','proposal_key','version','title','description','amount','status','couple_comment','created_by','decided_by','decided_at']; protected function casts(): array { return ['amount' => 'decimal:2', 'decided_at' => 'datetime']; } }
