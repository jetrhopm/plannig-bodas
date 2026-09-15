<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class FinanceAttachment extends Model { protected $fillable = ['wedding_finance_item_id','original_name','path','mime_type','size','uploaded_by']; public function weddingFinanceItem(): \Illuminate\Database\Eloquent\Relations\BelongsTo { return $this->belongsTo(WeddingFinanceItem::class); } }
