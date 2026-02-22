<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Kas extends Model
{
    protected $fillable = [
        'tanggal',
        'jenis_transaksi',
        'kategori_transaksi',
        'jumlah',
        'produksi_id',
        'penjualan_id',
        'keterangan',

    ];

    public function produksi(): BelongsTo
    {
        return $this->belongsTo(Produksi::class);
    }

    public function penjualan(): BelongsTo
    {
        return $this->belongsTo(Penjualan::class);
    }
}
