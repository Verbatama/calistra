<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produksi extends Model
{
    protected $fillable = [
        'user_id',
        'tanggal',
        'kode_produksi',
        'barang_id',
        'quantity_pcs'

    ];
}
