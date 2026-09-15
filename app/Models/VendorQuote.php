<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class VendorQuote extends Model { protected $fillable = ['wedding_id','wedding_logistic_id','vendor_name','title','details','amount','valid_until','status']; protected function casts(): array { return ['amount' => 'decimal:2','valid_until' => 'date']; } }
