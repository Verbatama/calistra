<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BahanBakuResource extends JsonResource
{
    public static $wrap = null;

    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'nama_bahan' => $this->nama_bahan,
            'satuan' => $this->satuan,
            'harga_satuan' => (float) $this->harga_satuan,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
