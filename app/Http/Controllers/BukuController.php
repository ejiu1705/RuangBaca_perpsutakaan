<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Kategori;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class BukuController extends Controller
{
    public function index()
    {
        $kategori = Kategori::query()->orderBy('nama')->get();
        $daftarBuku = Buku::with('kategori')->withCount('peminjamans')->orderByDesc('id')->limit(10)->get();

        return view('buku.index', [
            'kategori' => $kategori,
            'daftarBuku' => $daftarBuku,
            'totalBuku' => Buku::count(),
            'totalKategori' => $kategori->count(),
            'tahunTerbitTerbaru' => Buku::max('tahun_terbit'),
        ]);
    }


    public function show($id)
    {
        $item = Buku::with('kategori')->findOrFail($id);
        $buku = [
            'id' => $item->id,
            'judul' => $item->judul,
            'penulis' => $item->penulis,
            'tahun' => $item->tahun_terbit,
            'kategori' => $item->kategori?->nama,
        ];

        return view('buku.show', ['buku' => $buku]);
    }
    // Modul 6: pencarian aman menggunakan query binding.
   public function cari(Request $request)
{
    $judul = $request->query('judul', '');
    $data = DB::select('SELECT * FROM bukus WHERE judul = ?', [$judul]);

    return response()->json($data); 
}


    // Modul 6: penghapusan melalui DELETE + CSRF (web middleware).
    public function destroyWeb(Buku $buku)
    {
        if ($buku->peminjamans()->exists()) {
            return redirect()->route('buku.index')->withErrors([
                'buku' => 'Buku tidak dapat dihapus karena memiliki riwayat peminjaman. Riwayat transaksi harus tetap tersimpan.',
            ]);
        }

        $buku->delete();

        return redirect()->route('buku.index')
            ->with('success', 'Buku berhasil dihapus.');
    }
}
