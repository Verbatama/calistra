<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Kas;

class KasController extends ApiController
{
    public function index(Request $request){
        $limit = (int) $request->get('limit',10 );
        $kass = Kas::paginate($limit);
        return $this->success(null);
    }

    public function show($id){
        $kas = Kas::findOrFail($id);
        return $this->success(null);
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'tanggal' => 'required|date',
            'jenis_transaksi' => 'required|in:masuk,keluar',
            'jumlah' => 'required|numeric',
            'produksi_id' => 'nullable|exists:produksi,id',
            'penjualan_id' => 'nullable|exists:penjualan,id',
            'keterangan' => 'nullable|string',
        ]);

        $kas = Kas::create($validator->validate());
        return $this->success(null);
    }

}
