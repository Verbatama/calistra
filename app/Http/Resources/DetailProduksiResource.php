<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DetailProduksiResource extends JsonResource
{
    public static $wrap = null;

    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'produksi_id' => $this->produksi_id,
            'bahan_id' => $this->bahan_id,
            'bahan' => new BahanBakuResource($this->whenLoaded('bahan')),
            'qty_bahan' => (int) $this->qty_bahan,
            'biaya' => (float) $this->biaya,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
