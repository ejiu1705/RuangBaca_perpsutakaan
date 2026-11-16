<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\BukuResource;
use App\Http\Requests\StoreBukuRequest;
use App\Http\Requests\UpdateBukuRequest;
use App\Models\Buku;
use App\Models\Kategori;
use Illuminate\Http\Request;

class BukuController extends Controller
{
    public function index(Request $request)
    {
        $validated = $request->validate([
            'cari' => ['nullable', 'string', 'max:150'],
            'kategori_id' => ['nullable', 'integer', 'exists:kategoris,id'],
            'sort' => ['nullable', 'in:terbaru,judul,tahun'],
            'per_page' => ['nullable', 'integer', 'min:5', 'max:50'],
        ]);

        $query = Buku::query()->with('kategori')->withCount('peminjamans');

        if (! empty($validated['cari'])) {
            $kataKunci = strtolower(trim($validated['cari']));
            $query->where(function ($subQuery) use ($kataKunci) {
                $subQuery->whereRaw('LOWER(judul) LIKE ?', ["%{$kataKunci}%"])
                    ->orWhereRaw('LOWER(penulis) LIKE ?', ["%{$kataKunci}%"]);
            });
        }

        if (! empty($validated['kategori_id'])) {
            $query->where('kategori_id', $validated['kategori_id']);
        }

        match ($validated['sort'] ?? 'terbaru') {
            'judul' => $query->orderBy('judul')->orderBy('id'),
            'tahun' => $query->orderByDesc('tahun_terbit')->orderBy('judul'),
            default => $query->orderByDesc('id'),
        };

        $buku = $query->paginate($validated['per_page'] ?? 10)->withQueryString();

        return BukuResource::collection($buku);
    }

    public function store(StoreBukuRequest $request)
    {
        $buku = Buku::create($request->validated())->load('kategori');

        return (new BukuResource($buku))->response()->setStatusCode(201);
    }

    public function show(string $id)
    {
        $buku = Buku::with('kategori')->find($id);
        if (!$buku) {
            return response()->json(['message' => 'Data buku tidak ditemukan.'], 404);
        }
        return new BukuResource($buku);
    }

    public function update(UpdateBukuRequest $request, string $id)
    {
        $buku = Buku::find($id);
        if (!$buku) {
            return response()->json(['message' => 'Data buku tidak ditemukan.'], 404);
        }

        $buku->update($request->validated());
        return new BukuResource($buku->load('kategori'));
    }

    public function destroy(string $id)
    {
        $buku = Buku::find($id);
        if (!$buku) {
            return response()->json(['message' => 'Data buku tidak ditemukan.'], 404);
        }
        if ($buku->peminjamans()->exists()) {
            return response()->json([
                'message' => 'Buku tidak dapat dihapus karena memiliki riwayat peminjaman.',
            ], 409);
        }

        $buku->delete();
        return response()->noContent();
    }

    public function rekapBukuPerKategori()
    {
        $data = Kategori::query()->select(['id', 'nama'])->withCount('bukus')->orderBy('nama')->get();
        return response()->json(['data' => $data], 200);
    }

    public function rekapPeminjamanPerBuku()
    {
        $data = Buku::query()->select(['id', 'judul', 'penulis', 'tahun_terbit'])
            ->withCount('peminjamans')->orderByDesc('peminjamans_count')
            ->orderBy('judul')->get();
        return response()->json(['data' => $data], 200);
    }
}
