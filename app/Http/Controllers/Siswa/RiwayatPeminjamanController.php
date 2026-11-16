<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Buku;
use App\Models\Peminjaman;
use Illuminate\View\View;

class RiwayatPeminjamanController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();

        $peminjamans = Peminjaman::with(['buku', 'anggota'])
            ->where(function ($query) use ($user) {
                $query->where('user_id', $user->id)
                    ->orWhere(function ($legacy) use ($user) {
                        // Baris lama Modul 2 yang belum punya user_id hanya dipetakan
                        // lewat email anggota yang sama dengan akun login.
                        $legacy->whereNull('user_id')
                            ->whereHas('anggota', fn ($anggota) => $anggota->where('email', $user->email));
                    });
            })
            ->latest()
            ->paginate(10);

        $buku = Buku::orderBy('judul')->get(['id', 'judul']);

        return view('siswa.riwayat', compact('peminjamans', 'buku'));
    }
}
