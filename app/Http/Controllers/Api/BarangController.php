<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Barang;

class BarangController extends ApiController
{
    public function index(Request $request){
        $limit = (int) $request->get('limit',10);
        $barangs = Barang::paginate($limit);

        return $this->success($barangs);

    }

    public function show($id){
        $barang = Barang::findOrFail($id);
        return $this->success($barang);
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'kode_barang' => 'required|string',
            'nama_barang' => 'required|string',
            'harga' => 'required|numeric',
        ]);

        $barang = Barang::create($validator->validate());
        return $this->success($barang, 'Data barang berhasil disimpan', 201);

    }

    public function update(Request $request, $id){
        $barang = Barang::findOrFail($id);
        $validator = Validator::make($request->all(), [
            'kode_barang' => 'sometimes|required|string',
            'nama_barang' => 'sometimes|required|string',
            'harga' => 'sometimes|required|numeric',
        ]);

        $barang->update($validator->validate());
        return $this->success($barang->fresh(), 'Data barang berhasil diperbarui');

    }

    public function destroy($id){
        $barang = Barang::findOrFail($id);
        $barang->delete();
        return $this->success(null, 'Data barang berhasil dihapus');
    }

}
