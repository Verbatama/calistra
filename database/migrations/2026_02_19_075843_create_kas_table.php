<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('kas', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            $table->enum('jenis_transaksi', ['masuk', 'keluar']);
            $table->string('kategori_transaksi');
            $table->decimal('jumlah', 15, 2);
            $table->foreignId('produksi_id')->nullable()
                ->constrained('produksis');
            $table->foreignId('penjualan_id')->nullable()
                ->constrained('penjualans');

            $table->string('keterangan')->nullable();
            $table->timestamps();
        });

        DB::statement("ALTER TABLE kas ADD CONSTRAINT kas_jenis_referensi_check CHECK ((jenis_transaksi = 'masuk' AND penjualan_id IS NOT NULL AND produksi_id IS NULL) OR (jenis_transaksi = 'keluar' AND produksi_id IS NOT NULL AND penjualan_id IS NULL))");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kas');
    }
};
