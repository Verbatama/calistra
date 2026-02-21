<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BahanBaku;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\Model;


class BahanBakuController extends ApiController
{

    public function index(Request $request){
        $limit = (int) $request->get('limit',10);
        $bahan_bakus = BahanBaku::paginate($limit);

        return $this->success(null);
    }

    public function show($id){
        $bahan_baku = BahanBaku::findOrFail($id);
        return $this->success(null);
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'nama_bahan' => 'required|string',
            'satuan' => 'required|string',
            'harga_satuan' => 'required|numeric',
        ]);

        $bahan_baku = BahanBaku::create($validator->validate());
        return $this->success(null);
    }

    public function update(Request $request, $id){
        $bahan_baku = BahanBaku::findOrFail($id);
        $validator = Validator::make($request->all(), [
            'nama_bahan' => 'sometimes|required|string',
            'satuan' => 'sometimes|required|string',
            'harga_satuan' => 'sometimes|required|numeric',
        ]);

        $bahan_baku = BahanBaku::updated($validator->validate());

        return $this->success(null);
    }

    public function destroy($id){

        $bahan_baku = BahanBaku::findOrFail($id);
        $bahan_baku->delete();
        return $this->success(null);

    }
}
