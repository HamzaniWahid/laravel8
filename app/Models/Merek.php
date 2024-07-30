<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Merek extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function inventories(): HasMany
    {
        return $this->hasMany(Inventories::class);
    }
}
