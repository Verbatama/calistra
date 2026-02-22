<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetailProduksi extends Model
{
    protected $fillable = [
        'produksi_id',
        'bahan_id',
        'qty_bahan',
        'biaya',

    ];

    public function produksi(): BelongsTo
    {
        return $this->belongsTo(Produksi::class);
    }

    public function bahan(): BelongsTo
    {
        return $this->belongsTo(BahanBaku::class, 'bahan_id');
    }
}
