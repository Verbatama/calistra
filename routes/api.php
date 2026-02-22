<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\BarangController;
use App\Http\Controllers\Api\BahanBakuController;
use App\Http\Controllers\Api\KasController;
use App\Http\Controllers\Api\PenjualanController;
use App\Http\Controllers\Api\ProduksiController;
use App\Http\Controllers\Api\AuthController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::apiResource('barang', BarangController::class);
    Route::apiResource('bahan-baku', BahanBakuController::class);

    Route::apiResource('kas', KasController::class)
        ->only(['index', 'show', 'store']);

    Route::apiResource('penjualan', PenjualanController::class)
        ->only(['index', 'show', 'store']);

    Route::apiResource('produksi', ProduksiController::class)
        ->only(['index', 'show', 'store']);
});
