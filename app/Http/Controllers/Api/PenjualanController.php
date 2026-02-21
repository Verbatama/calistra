<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\DetailPenjualan;
use App\Models\Kas;
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
        return $this->success($penjualan);

    }

    public function show($id){
        $penjualan = Penjualan::with(['detailPenjualan.barang'],
                    'user')->findOrFail($id);
        return $this->success($penjualan);
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'tanggal' => 'nullable|date',
            'kode_penjualan' => 'nullable|string|max:255',
            'keterangan' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.barang_id' => 'required|exists:barangs,id',
            'items.*.jumlah' => 'required|integer|min:1',
            'items.*.harga_jual' => 'nullable|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return $this->error($validator->errors()->first(), 422);
        }

        $validated = $validator->validated();

        $result = DB::transaction(function () use ($validated) {
            $tanggal = $validated['tanggal'] ?? now()->toDateString();
            $kodePenjualan = $validated['kode_penjualan'] ?? ('PJ-' . now()->format('YmdHis'));

            $penjualan = Penjualan::create([
                'user_id' => $validated['user_id'],
                'tanggal' => $tanggal,
                'kode_penjualan' => $kodePenjualan,
            ]);

            $total = 0;
            $detailItems = [];

            foreach ($validated['items'] as $item) {
                $barang = \App\Models\Barang::findOrFail($item['barang_id']);
                $hargaJual = $item['harga_jual'] ?? $barang->harga;
                $subtotal = $hargaJual * $item['jumlah'];

                $detail = DetailPenjualan::create([
                    'penjualan_id' => $penjualan->id,
                    'barang_id' => $barang->id,
                    'jumlah' => $item['jumlah'],
                    'harga_jual' => $hargaJual,
                    'subtotal' => $subtotal,
                ]);

                $detailItems[] = $detail;
                $total += $subtotal;
            }

            Kas::create([
                'tanggal' => $tanggal,
                'jenis_transaksi' => 'masuk',
                'kategori_transaksi' => 'penjualan',
                'jumlah' => $total,
                'penjualan_id' => $penjualan->id,
                'keterangan' => $validated['keterangan'] ?? ('Transaksi ' . $kodePenjualan),
            ]);

            $penjualan->load(['user', 'detailPenjualan.barang']);

            return [
                'penjualan' => $penjualan,
                'items' => $detailItems,
                'total' => $total,
            ];
        });

        return $this->success($result, 'Data penjualan berhasil disimpan', 201);
    }

    public function destroy($id){
        $penjualan = Penjualan::findOrFail($id);

        DB::transaction(function () use ($penjualan) {
            Kas::where('penjualan_id', $penjualan->id)->delete();
            DetailPenjualan::where('penjualan_id', $penjualan->id)->delete();
            $penjualan->delete();
        });

        return $this->success(null, 'Data penjualan berhasil dihapus');
    }

}
