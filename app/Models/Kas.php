<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kas extends Model
{
    protected $fillable = [
        'tanggal',
        'jenis_transaksi',
        'kategori_transaksi',
        'jumlah',
        'produksi_id',
        'penjualan_id',
        'keterangan'

    ];
}
