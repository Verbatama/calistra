<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\DetailPenjualan;
use App\Models\Penjualan;

class PenjualanController extends ApiController
{
    public function index(Request $request){
        $query = Penjualan::with(['detailPenjualan.barang', 'user'])
            ->orderBy('tanggal', 'desc');

        // Filter by bulan & tahun (opsional)
        if ($request->has('bulan') && $request->has('tahun')) {
            $query->whereMonth('tanggal', $request->bulan)
                  ->whereYear('tanggal', $request->tahun);
        }

        $penjualan = $query->get();
        return $this->success(null);

    }

    public function show($id){
        $penjualan = Penjualan::with(['detailPenjualan.barang'],
                    'user')->find($id);
        return $this->success(null);
    }

    public function store(Request $request){

    }

    public function destroy($id){

    }

}
