<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PenjualanResource extends JsonResource
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
            'kode_penjualan' => $this->kode_penjualan,
            'detail_penjualan' => DetailPenjualanResource::collection($this->whenLoaded('detailPenjualan')),
            'total' => (float) ($this->whenLoaded('detailPenjualan', fn () => $this->detailPenjualan->sum('subtotal')) ?? 0),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
