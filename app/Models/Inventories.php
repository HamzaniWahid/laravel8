<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Inventories extends Model
{
    use HasFactory;
    // protected $guarded=[];
    protected $fillable = ['nama', 'merek_id', 'jumlah', 'expired' ,'kategori_id', 'hargaJual', 'hargaBeli'];

    public function kategori():BelongsTo {
        return $this->belongsTo(Kategories::class);
    }
    public function merek():BelongsTo {
        return $this->belongsTo(Merek::class);
    }
    public function transaction_detail(){
        return $this->hasMany(Transaction_detail::class);
    }
}
