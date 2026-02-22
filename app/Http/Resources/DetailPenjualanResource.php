<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DetailPenjualanResource extends JsonResource
{
    public static $wrap = null;

    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'penjualan_id' => $this->penjualan_id,
            'barang_id' => $this->barang_id,
            'barang' => new BarangResource($this->whenLoaded('barang')),
            'jumlah' => (int) $this->jumlah,
            'harga_jual' => (float) $this->harga_jual,
            'subtotal' => (float) $this->subtotal,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
