<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\BahanBaku;
use Illuminate\Support\Facades\Validator;


class BahanBakuController extends ApiController
{

    public function index(Request $request){
        $limit = (int) $request->get('limit',10);
        $bahan_bakus = BahanBaku::paginate($limit);

        return $this->success($bahan_bakus);
    }

    public function show($id){
        $bahan_baku = BahanBaku::findOrFail($id);
        return $this->success($bahan_baku);
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'nama_bahan' => 'required|string',
            'satuan' => 'required|string',
            'harga_satuan' => 'required|numeric',
        ]);

        $bahan_baku = BahanBaku::create($validator->validate());
        return $this->success($bahan_baku, 'Data bahan baku berhasil disimpan', 201);
    }

    public function update(Request $request, $id){
        $bahan_baku = BahanBaku::findOrFail($id);
        $validator = Validator::make($request->all(), [
            'nama_bahan' => 'sometimes|required|string',
            'satuan' => 'sometimes|required|string',
            'harga_satuan' => 'sometimes|required|numeric',
        ]);

        $bahan_baku->update($validator->validate());

        return $this->success($bahan_baku->fresh(), 'Data bahan baku berhasil diperbarui');
    }

    public function destroy($id){

        $bahan_baku = BahanBaku::findOrFail($id);
        $bahan_baku->delete();
        return $this->success(null, 'Data bahan baku berhasil dihapus');

    }
}
