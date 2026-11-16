<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Buku;
use App\Models\Kategori;
use Illuminate\View\View;

class LaporanController extends Controller
{
    public function index(): View
    {
        $totalBuku = Buku::count();
        $totalKategori = Kategori::count();
        $buku = Buku::with('kategori')
            ->withCount('peminjamans')
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('admin.laporan.index', compact(
            'totalBuku',
            'totalKategori',
            'buku'
        ));
    }
}
