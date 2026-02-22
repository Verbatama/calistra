<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Kas;
use App\Http\Resources\KasResource;

class KasController extends ApiController
{
    public function index(Request $request){
        $limit = (int) $request->get('limit',10 );
        $kass = Kas::with(['produksi', 'penjualan'])->paginate($limit);
        return $this->success(KasResource::collection($kass)->resolve());
    }

    public function show($id){
        $kas = Kas::with(['produksi', 'penjualan'])->findOrFail($id);
        return $this->success((new KasResource($kas))->resolve());
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'tanggal' => 'required|date',
            'jenis_transaksi' => 'required|in:masuk,keluar',
            'kategori_transaksi' => 'required|string',
            'jumlah' => 'required|numeric',
            'produksi_id' => 'nullable|exists:produksis,id',
            'penjualan_id' => 'nullable|exists:penjualans,id',
            'keterangan' => 'nullable|string',
        ]);

        $kas = Kas::create($validator->validate());
        return $this->success((new KasResource($kas))->resolve(), 'Data kas berhasil disimpan', 201);
    }

}
