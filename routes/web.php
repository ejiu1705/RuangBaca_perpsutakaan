<?php

use App\Http\Controllers\Admin\LaporanController;
use App\Http\Controllers\Admin\KategoriController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Siswa\RiwayatPeminjamanController;
use App\Models\Buku;
use App\Models\Kategori;
use App\Models\Peminjaman;
use App\Models\User;
use App\Http\Controllers\Modul6\PeminjamanSecurityController;
use Illuminate\Support\Facades\Route;


// Halaman utama Modul 1 tetap tersedia tanpa login.
Route::get('/', fn () => view('home'))->name('home');

Route::middleware('auth')->group(function () {
    // Dashboard mengarahkan pengguna ke halaman sesuai role.
    Route::get('/dashboard', function () {
        $user = auth()->user();

        if ($user->can('akses dashboard admin')) {
            return redirect()->route('admin.dashboard');
        }
        if ($user->can('akses dashboard petugas')) {
            return redirect()->route('petugas.dashboard');
        }

        return redirect()->route('siswa.dashboard');
    })->name('dashboard');

    Route::get('/admin/dashboard', function () {
        $stats = [
            'buku' => Buku::count(),
            'kategori' => Kategori::count(),
            'peminjaman' => Peminjaman::count(),
            'menunggu' => Peminjaman::where('status', 'menunggu')->count(),
            'pengguna' => User::count(),
        ];
        $recentPeminjamans = Peminjaman::with(['buku', 'anggota', 'user'])->latest()->limit(5)->get();

        return view('admin.dashboard', compact('stats', 'recentPeminjamans'));
    })->middleware('permission:akses dashboard admin')->name('admin.dashboard');

    Route::get('/petugas/dashboard', function () {
        $stats = [
            'buku' => Buku::count(),
            'peminjaman' => Peminjaman::count(),
            'menunggu' => Peminjaman::where('status', 'menunggu')->count(),
            'disetujui' => Peminjaman::where('status', 'disetujui')->count(),
        ];
        $recentPeminjamans = Peminjaman::with(['buku', 'anggota', 'user'])->latest()->limit(5)->get();

        return view('petugas.dashboard', compact('stats', 'recentPeminjamans'));
    })->middleware('permission:akses dashboard petugas')->name('petugas.dashboard');

    Route::get('/siswa/dashboard', function () {
        $user = auth()->user();
        $peminjamanSaya = Peminjaman::query()->where(function ($query) use ($user) {
            $query->where('user_id', $user->id)
                ->orWhere(function ($legacy) use ($user) {
                    $legacy->whereNull('user_id')
                        ->whereHas('anggota', fn ($anggota) => $anggota->where('email', $user->email));
                });
        });

        $stats = [
            'buku' => Buku::count(),
            'peminjaman' => (clone $peminjamanSaya)->count(),
            'menunggu' => (clone $peminjamanSaya)->where('status', 'menunggu')->count(),
            'disetujui' => (clone $peminjamanSaya)->where('status', 'disetujui')->count(),
        ];
        $recentPeminjamans = (clone $peminjamanSaya)->with('buku')->latest()->limit(5)->get();

        return view('siswa.dashboard', compact('stats', 'recentPeminjamans'));
    })->middleware('role:siswa')->name('siswa.dashboard');

    // Kategori baru ditambahkan langsung dari formulir buku (tanpa halaman/menu terpisah).
    Route::post('/buku/kategori', [KategoriController::class, 'storeInline'])
        ->middleware('can:kelola-buku')
        ->name('buku.kategori.store');

    // Route Modul 1–4 dipertahankan: daftar dan detail buku.
    Route::get('/buku', [BukuController::class, 'index'])
        ->middleware('permission:lihat daftar buku')
        ->name('buku.index');

    // Modul 6: endpoint pencarian diletakkan sebelum route dinamis /buku/{id}.
    Route::get('/buku-cari', [BukuController::class, 'cari'])
        ->middleware('permission:lihat daftar buku')
        ->name('buku.cari');

    // Jalur kompatibilitas CRUD web: formulir tambah/ubah berada pada halaman daftar.
    // Rute ini tetap memeriksa Gate sehingga Siswa menerima 403 saat mengakses langsung.
    Route::get('/buku/create', fn () => redirect()->route('buku.index'))
        ->middleware('can:kelola-buku')
        ->name('buku.create');
    Route::get('/buku/{buku}/edit', fn () => redirect()->route('buku.index'))
        ->middleware('can:kelola-buku')
        ->name('buku.edit');

    Route::get('/buku/{id}', [BukuController::class, 'show'])
        ->middleware('permission:lihat daftar buku')
        ->name('buku.show');

    // Penghapusan web menggunakan DELETE + CSRF, bukan GET.

    Route::delete('/buku/{buku}', [BukuController::class, 'destroyWeb'])
        ->middleware('can:kelola-buku')
        ->name('buku.destroy');

    // Modul 6: detail peminjaman dilindungi Policy (anti-IDOR).
    Route::get('/peminjaman/{id}', [PeminjamanSecurityController::class, 'show'])
        ->name('peminjaman.show');

    // Profil selalu milik akun yang sedang login.
    Route::get('/profile', [ProfileController::class, 'edit'])
        ->middleware('permission:lihat profil sendiri')
        ->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])
        ->middleware('permission:ubah profil sendiri')
        ->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->middleware('permission:ubah profil sendiri')
        ->name('profile.destroy');

    // Pengelolaan akun dan role khusus Admin.
    Route::get('/admin/users', [UserManagementController::class, 'index'])
        ->middleware('permission:kelola data pengguna')
        ->name('admin.users.index');
    Route::patch('/admin/users/{user}/role', [UserManagementController::class, 'updateRole'])
        ->middleware('permission:kelola role pengguna')
        ->name('admin.users.role.update');

    // Dashboard operasional: Admin dan Petugas.
    Route::get('/peminjaman', [PeminjamanController::class, 'index'])
        ->middleware('permission:kelola peminjaman')
        ->name('peminjaman.index');
    Route::post('/peminjaman', [PeminjamanController::class, 'store'])
        ->middleware('permission:mengajukan peminjaman buku')
        ->name('peminjaman.store');
    Route::post('/peminjaman/{peminjaman}/setujui', [PeminjamanController::class, 'approve'])
        ->middleware('permission:memproses persetujuan peminjaman')
        ->name('peminjaman.approve');
    Route::post('/peminjaman/{peminjaman}/tolak', [PeminjamanController::class, 'reject'])
        ->middleware('permission:memproses persetujuan peminjaman')
        ->name('peminjaman.reject');
    Route::post('/peminjaman/{peminjaman}/kembalikan', [PeminjamanController::class, 'returnBook'])
        ->middleware('permission:mencatat pengembalian buku')
        ->name('peminjaman.return');

    // Setiap user hanya dapat melihat riwayat peminjamannya sendiri.
    Route::get('/riwayat-peminjaman', [RiwayatPeminjamanController::class, 'index'])
        ->middleware('permission:melihat riwayat peminjaman sendiri')
        ->name('siswa.riwayat');

    Route::get('/admin/laporan', [LaporanController::class, 'index'])
        ->middleware('permission:lihat laporan perpustakaan')
        ->name('laporan.index');

    // Route uji error Modul 6 hanya tersedia di lingkungan lokal/testing.
    if (app()->environment(['local', 'testing'])) {
        Route::get('/uji-error-modul6', function () {
            throw new RuntimeException('Pengujian keamanan Modul 6');
        });
    }
});

require __DIR__.'/auth.php';
