<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WeddingServiceTemplate extends Model
{
    protected $fillable = ['name', 'category', 'description', 'default_owner', 'is_active'];
    protected function casts(): array { return ['is_active' => 'boolean']; }
}
