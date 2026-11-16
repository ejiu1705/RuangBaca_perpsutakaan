<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class KategoriController extends Controller
{
    /**
     * Menambahkan kategori langsung dari formulir tambah/ubah buku.
     * Endpoint ini hanya tersedia untuk Admin dan Petugas melalui middleware route.
     */
    public function storeInline(Request $request): JsonResponse
    {
        $request->merge([
            'nama' => trim((string) $request->input('nama')),
        ]);

        $validated = $request->validate([
            'nama' => ['required', 'string', 'min:2', 'max:80'],
        ], [
            'nama.required' => 'Nama kategori wajib diisi.',
            'nama.min' => 'Nama kategori minimal 2 karakter.',
            'nama.max' => 'Nama kategori maksimal 80 karakter.',
        ]);

        $nama = $validated['nama'];
        $sudahAda = Kategori::query()
            ->whereRaw('LOWER(nama) = LOWER(?)', [$nama])
            ->exists();

        if ($sudahAda) {
            return response()->json([
                'message' => 'Kategori dengan nama tersebut sudah tersedia.',
                'errors' => [
                    'nama' => ['Kategori dengan nama tersebut sudah tersedia.'],
                ],
            ], 422);
        }

        $kategori = Kategori::create(['nama' => $nama]);

        return response()->json([
            'message' => 'Kategori baru berhasil ditambahkan.',
            'data' => [
                'id' => $kategori->id,
                'nama' => $kategori->nama,
            ],
        ], 201);
    }
}
