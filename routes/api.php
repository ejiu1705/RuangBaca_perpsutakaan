<?php

use App\Http\Controllers\Api\BukuController;
use Illuminate\Support\Facades\Route;


Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('buku', BukuController::class)
        ->only(['index', 'show'])
        ->names([
            'index' => 'api.buku.index',
            'show' => 'api.buku.show',
        ])
        ->middleware('permission:lihat daftar buku');

    Route::apiResource('buku', BukuController::class)
        ->only(['store', 'update', 'destroy'])
        ->names([
            'store' => 'api.buku.store',
            'update' => 'api.buku.update',
            'destroy' => 'api.buku.destroy',
        ])
        ->middleware('can:kelola-buku');

    Route::middleware('permission:lihat laporan perpustakaan')->group(function () {
        Route::get('/rekap/buku-per-kategori', [BukuController::class, 'rekapBukuPerKategori']);
        Route::get('/rekap/peminjaman-per-buku', [BukuController::class, 'rekapPeminjamanPerBuku']);
        Route::get('/rekap/pemminjaman-per-buku', [BukuController::class, 'rekapPeminjamanPerBuku']);
    });
});
