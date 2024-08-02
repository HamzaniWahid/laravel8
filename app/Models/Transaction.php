<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Transaction extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected static function booted()
    {
        static::created(function ($transaksi) {
            DB::transaction(function () use ($transaksi) {
                $inventory = Inventories::find($transaksi->inventory_id);
                $inventory->jumlah -= $transaksi->quantity;
                $inventory->save();
            });
        });

        static::updating(function ($transaction) {
            // Menyesuaikan jumlah saat transaksi diubah
            $originalQuantity = $transaction->getOriginal('quantity');
            $newQuantity = $transaction->quantity;

            $inventory = Inventories::find($transaction->inventory_id);
            if ($inventory) {
                $quantityDifference = $newQuantity - $originalQuantity;
                $inventory->jumlah -= $quantityDifference;
                $inventory->save();
            }
        });

        static::deleting(function ($transaction) {
            // Mengembalikan jumlah saat transaksi dihapus
            $inventory = Inventories::find($transaction->inventory_id);
            if ($inventory) {
                $inventory->jumlah += $transaction->quantity;
                $inventory->save();
            }
        });

    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function sale()
    {
        return $this->hasMany(Sale::class);
    }
    public function inventory()
    {
        return $this->belongsTo(Inventories::class);
    }
}

