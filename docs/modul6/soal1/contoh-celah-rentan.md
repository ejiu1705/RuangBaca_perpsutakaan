# Soal 1 — Potongan kondisi rentan untuk pengujian lokal

**Jangan aktifkan kode ini di server publik.** Gunakan bersama `../PANDUAN_PRAKTIKUM_MODUL6.md` agar perubahan ditempatkan pada file proyek yang benar.

## A1 — XSS pada Blade

```blade
<td>{!! $item->judul !!}</td>
```

Payload judul: `<script>alert('250441100045')</script>`

## A2 — SQL Injection

```php
$judul = $request->query('judul', '');
$data = DB::select("SELECT * FROM bukus WHERE judul = '$judul'");
return response()->json($data);
```

URL: `/buku-cari?judul=' OR '1'='1`

## A3 — Penghapusan melalui GET

Tambahkan import controller:

```php
use App\Http\Controllers\Api\BukuController as ApiBukuController;
```

Route sementara di dalam grup `auth`:

```php
Route::get('/buku/{buku}/hapus', [ApiBukuController::class, 'destroy'])
    ->name('buku.hapus.rentan');
```

Salin `jebakan.html` ke luar folder proyek Laravel dan sesuaikan host serta ID buku.

## A4 — IDOR

```php
public function show(string $id)
{
    $peminjaman = Peminjaman::with(['anggota', 'buku'])->findOrFail($id);

    return view('modul6.peminjaman.show', compact('peminjaman'));
}
```

Hapus sementara `Gate::authorize('view', $peminjaman)` untuk membuktikan akses tanpa pemeriksaan pemilik.

Semua kode rentan harus dipulihkan ke kondisi aman sesuai Soal 2–4 setelah screenshot Soal 1 dan commit Soal 1 selesai.
