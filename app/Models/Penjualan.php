<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Penjualan extends Model
{
    protected $fillable = [
        'user_id',
        'tanggal',
        'kode_penjualan'

    ];
}
