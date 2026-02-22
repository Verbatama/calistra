<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class KasResource extends JsonResource
{
    public static $wrap = null;

    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'tanggal' => $this->tanggal,
            'jenis_transaksi' => $this->jenis_transaksi,
            'kategori_transaksi' => $this->kategori_transaksi,
            'jumlah' => (float) $this->jumlah,
            'produksi_id' => $this->produksi_id,
            'penjualan_id' => $this->penjualan_id,
            'keterangan' => $this->keterangan,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
