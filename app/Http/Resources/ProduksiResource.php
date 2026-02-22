<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProduksiResource extends JsonResource
{
    public static $wrap = null;

    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'user' => $this->whenLoaded('user', fn () => [
                'id' => $this->user?->id,
                'name' => $this->user?->name,
                'email' => $this->user?->email,
            ]),
            'tanggal' => $this->tanggal,
            'kode_produksi' => $this->kode_produksi,
            'barang_id' => $this->barang_id,
            'barang' => new BarangResource($this->whenLoaded('barang')),
            'quantity_pcs' => (int) $this->quantity_pcs,
            'detail_produksi' => DetailProduksiResource::collection($this->whenLoaded('detailProduksi')),
            'total_biaya' => (float) ($this->whenLoaded('detailProduksi', fn () => $this->detailProduksi->sum('biaya')) ?? 0),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
