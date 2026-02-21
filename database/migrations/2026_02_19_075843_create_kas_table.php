<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
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
            // sebagai refernsi_id
            $table->foreignId('produksi_id')->nullable()
                ->references('id')
                ->on('produksis');
            $table->foreignId('penjualan_id')->nullable()
                ->references('id')
                ->on('penjualans');

            $table->string('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kas');
    }
};
