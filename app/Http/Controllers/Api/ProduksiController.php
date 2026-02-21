<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\Produksi as ProduksiModel;
use App\Models\DetailProduksi;
use App\Models\BahanBaku;
use App\Models\Kas;

class ProduksiController extends ApiController
{
    public function index(Request $request){
        $query = ProduksiModel::with(['user', 'barang', 'detailProduksi.bahan'])
            ->orderBy('tanggal', 'desc');

        if ($request->has('bulan') && $request->has('tahun')) {
            $query->whereMonth('tanggal', $request->bulan)
                ->whereYear('tanggal', $request->tahun);
        }

        $produksis = $query->get();

        return $this->success($produksis);
    }

    public function show($id){
        $produksi = ProduksiModel::with(['user', 'barang', 'detailProduksi.bahan'])->findOrFail($id);

        return $this->success($produksi);
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'tanggal' => 'nullable|date',
            'kode_produksi' => 'nullable|string|max:255',
            'barang_id' => 'required|exists:barangs,id',
            'quantity_pcs' => 'required|integer|min:1',
            'keterangan' => 'nullable|string',
            'bahan' => 'required|array|min:1',
            'bahan.*.bahan_id' => 'required|exists:bahan_bakus,id',
            'bahan.*.qty_bahan' => 'required|integer|min:1',
            'bahan.*.biaya' => 'nullable|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return $this->error($validator->errors()->first(), 422);
        }

        $validated = $validator->validated();

        $result = DB::transaction(function () use ($validated) {
            $tanggal = $validated['tanggal'] ?? now()->toDateString();
            $kodeProduksi = $validated['kode_produksi'] ?? ('PRD-' . now()->format('YmdHis'));

            $produksi = ProduksiModel::create([
                'user_id' => $validated['user_id'],
                'tanggal' => $tanggal,
                'kode_produksi' => $kodeProduksi,
                'barang_id' => $validated['barang_id'],
                'quantity_pcs' => $validated['quantity_pcs'],
            ]);

            $totalBiaya = 0;
            $detailBahan = [];

            foreach ($validated['bahan'] as $item) {
                $bahan = BahanBaku::findOrFail($item['bahan_id']);
                $biaya = $item['biaya'] ?? ($bahan->harga_satuan * $item['qty_bahan']);

                $detail = DetailProduksi::create([
                    'produksi_id' => $produksi->id,
                    'bahan_id' => $bahan->id,
                    'qty_bahan' => $item['qty_bahan'],
                    'biaya' => $biaya,
                ]);

                $detailBahan[] = $detail;
                $totalBiaya += $biaya;
            }

            Kas::create([
                'tanggal' => $tanggal,
                'jenis_transaksi' => 'keluar',
                'kategori_transaksi' => 'produksi',
                'jumlah' => $totalBiaya,
                'produksi_id' => $produksi->id,
                'keterangan' => $validated['keterangan'] ?? ('Biaya produksi ' . $kodeProduksi),
            ]);

            $produksi->load(['user', 'barang', 'detailProduksi.bahan']);

            return [
                'produksi' => $produksi,
                'details' => $detailBahan,
                'total_biaya' => $totalBiaya,
            ];
        });

        return $this->success($result, 'Data produksi berhasil disimpan', 201);

    }


    public function destroy($id){
        $produksi = ProduksiModel::findOrFail($id);

        DB::transaction(function () use ($produksi) {
            Kas::where('produksi_id', $produksi->id)->delete();
            DetailProduksi::where('produksi_id', $produksi->id)->delete();
            $produksi->delete();
        });

        return $this->success(null, 'Data produksi berhasil dihapus');

    }

}
