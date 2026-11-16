<?php

namespace App\Http\Controllers\Modul6;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use Illuminate\Support\Facades\Gate;

class PeminjamanSecurityController extends Controller
{
    /** Detail peminjaman dengan pemeriksaan Policy untuk mencegah IDOR. */
    public function show(string $id)
{
    $peminjaman = Peminjaman::with(['anggota', 'buku'])->findOrFail($id);

    Gate::authorize('view', $peminjaman);

    return view('modul6.peminjaman.show', compact('peminjaman'));
}
}