<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailProduksi extends Model
{
    protected $fillable = [
        'produksi_id',
        'bahan_id',
        'qty_bahan',
        'biaya'

    ];
}
