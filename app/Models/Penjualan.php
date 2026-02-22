<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Penjualan extends Model
{
    protected $fillable = [
        'user_id',
        'tanggal',
        'kode_penjualan',

    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function detailPenjualan(): HasMany
    {
        return $this->hasMany(DetailPenjualan::class);
    }
}
