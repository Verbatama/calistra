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
        Schema::create('detail_produksis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('produksi_id')->references('id')
                ->on('produksis');
            $table->foreignId('bahan_id')->references('id')
                ->on('bahan_bakus');
            $table->integer('qty_bahan')->unsigned();
            $table->decimal('biaya', 15, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_produksis');
    }
};
