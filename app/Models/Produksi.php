<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Produksi extends Model
{
    protected $fillable = [
        'user_id',
        'tanggal',
        'kode_produksi',
        'barang_id',
        'quantity_pcs',

    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function barang(): BelongsTo
    {
        return $this->belongsTo(Barang::class);
    }

    public function detailProduksi(): HasMany
    {
        return $this->hasMany(DetailProduksi::class);
    }
}
