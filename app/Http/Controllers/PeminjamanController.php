<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use App\Models\Buku;
use App\Models\Peminjaman;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PeminjamanController extends Controller
{
    public function index(): View
    {
        $peminjamans = Peminjaman::with(['anggota', 'buku', 'user'])
            ->latest()
            ->paginate(15);

        return view('peminjaman.index', compact('peminjamans'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'buku_id' => ['required', 'integer', 'exists:bukus,id'],
        ]);

        $user = $request->user();
        $anggota = Anggota::firstOrCreate(
            ['email' => $user->email],
            ['nama' => $user->name, 'no_telepon' => '-']
        );

        // Kepemilikan ditetapkan dari session login, bukan dari input pengguna.
        Peminjaman::create([
            'user_id' => $user->id,
            'anggota_id' => $anggota->id,
            'buku_id' => $validated['buku_id'],
            'tanggal_pinjam' => now()->toDateString(),
            'tanggal_kembali' => null,
            'status' => 'menunggu',
        ]);

        return redirect()->route('siswa.riwayat')->with('status', 'Pengajuan peminjaman berhasil dikirim.');
    }

    public function approve(Peminjaman $peminjaman): RedirectResponse
    {
        if ($peminjaman->status !== 'menunggu') {
            return back()->withErrors(['status' => 'Hanya pengajuan berstatus menunggu yang dapat disetujui.']);
        }

        $peminjaman->update(['status' => 'disetujui']);

        return back()->with('status', 'Peminjaman berhasil disetujui.');
    }

    public function reject(Peminjaman $peminjaman): RedirectResponse
    {
        if ($peminjaman->status !== 'menunggu') {
            return back()->withErrors(['status' => 'Hanya pengajuan berstatus menunggu yang dapat ditolak.']);
        }

        $peminjaman->update(['status' => 'ditolak']);

        return back()->with('status', 'Pengajuan peminjaman ditolak.');
    }

    public function returnBook(Peminjaman $peminjaman): RedirectResponse
    {
        if ($peminjaman->status !== 'disetujui') {
            return back()->withErrors(['status' => 'Hanya buku yang sudah disetujui yang dapat dicatat pengembaliannya.']);
        }

        $peminjaman->update([
            'status' => 'dikembalikan',
            'tanggal_kembali' => now()->toDateString(),
        ]);

        return back()->with('status', 'Pengembalian buku berhasil dicatat.');
    }
}
