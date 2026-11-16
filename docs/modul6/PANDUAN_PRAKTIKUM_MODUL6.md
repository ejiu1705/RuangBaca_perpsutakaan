# Panduan Pengerjaan Tugas Praktikum Modul 6

Panduan ini dibuat berdasarkan struktur proyek Perpustakaan Digital pada arsip ini dan urutan soal dalam file **TUGAS PRAKTIKUM-Modul 6 Keamanan Web dan Otorisasi**.

## Penting: urutan kondisi program

Arsip proyek utama berada pada **kondisi aman akhir** untuk Soal 2–5. Soal 1 mengharuskan empat celah dibuat sementara, diuji, lalu semuanya di-commit dalam satu commit sebelum perbaikan. Karena itu, jangan langsung mengumpulkan screenshot Soal 1 dari kondisi ZIP ini. Terapkan empat perubahan sementara di bawah, uji hanya di komputer lokal sendiri, buat commit Soal 1, kemudian lakukan patch pada Soal 2.

Jangan pernah mengaktifkan celah ini pada hosting atau server publik. Sebelum mulai, buat salinan database dan pastikan `.env` tidak ikut Git.

## Persiapan proyek

Buka PowerShell di folder proyek:

```powershell
cd C:\laragon\www\perpustakaan_250441100045x
composer install
npm install
php artisan migrate
php artisan optimize:clear
php artisan route:list
```

Bila role/akun pengujian belum tersedia, jalankan seeder secara berurutan setelah memeriksa konfigurasi database:

```powershell
php artisan db:seed --class=RolePermissionSeeder
php artisan db:seed --class=UserSeeder
```

`UserSeeder` akan mengganti password admin dengan nilai `SEED_ADMIN_PASSWORD` (atau membuat password acak jika kosong). Atur password tersebut lebih dahulu bila ingin mengendalikan kredensial akun admin.

Pastikan `.env` lokal menggunakan database proyek Modul 5. Jangan jalankan `migrate:fresh` pada database yang berisi data penting. Arsip tidak menyertakan `.env`; salin `.env.example` menjadi `.env` hanya bila belum ada, isi koneksi database, lalu jalankan `php artisan key:generate` hanya bila `APP_KEY` belum pernah dibuat.

Periksa `.gitignore` dan pastikan `.env` diabaikan:

```powershell
git check-ignore .env
```

Jika folder belum menjadi repository:

```powershell
git init
git add .
git commit -m "Baseline: Proyek Perpustakaan Digital Modul 1-5"
```

## Soal 1 — Aktifkan dan buktikan empat celah (lokal saja)

Lakukan semua perubahan A1–A4 berikut terlebih dahulu. Jangan melakukan patch apa pun sampai empat serangan berhasil diuji.

### A1 — XSS pada daftar buku

**File:** `app/Http/Controllers/BukuController.php`

Pada method `index()`, siapkan data buku untuk dirender oleh Blade:

```php
public function index()
{
    $kategori = Kategori::orderBy('nama')->get();
    $daftarBuku = Buku::with('kategori')->orderByDesc('id')->get();

    return view('buku.index', compact('kategori', 'daftarBuku'));
}
```

**File:** `resources/views/buku/index.blade.php`

Pada kolom judul di dalam loop `<tbody>`, ubah output aman menjadi sintaks rentan berikut. Ini adalah perubahan sementara untuk membuktikan XSS:

```blade
<td><a href="{{ route('buku.show', $item->id) }}">{!! $item->judul !!}</a></td>
```

Tambahkan buku dengan judul berikut menggunakan akun admin:

```html
<script>alert('250441100045')</script>
```

Buka `/buku` dan ambil screenshot ketika popup tampil. Pastikan screenshot memperlihatkan URL browser.

### A2 — SQL Injection pada pencarian

**File:** `app/Http/Controllers/BukuController.php`

Ganti sementara method `cari()` dengan query yang menggabungkan input langsung ke SQL:

```php
public function cari(Request $request)
{
    $judul = $request->query('judul', '');
    $data = DB::select("SELECT * FROM bukus WHERE judul = '$judul'");

    return response()->json($data);
}
```

Route `/buku-cari` sudah tersedia di `routes/web.php` di dalam grup `auth` dan memiliki pembatasan permission daftar buku.

Buka URL berikut setelah login sebagai admin:

```text
http://perpustakaan.test/buku-cari?judul=' OR '1'='1
```

Sesuaikan host bila proyek Anda memakai alamat lain. Hasil rentan yang diharapkan adalah seluruh data buku tampil sebagai JSON. Screenshot harus memperlihatkan URL dan hasil JSON.

### A3 — Penghapusan melalui GET (CSRF)

**File:** `routes/web.php`

Tambahkan import alias controller API di bagian atas:

```php
use App\Http\Controllers\Api\BukuController as ApiBukuController;
```

Di dalam grup `Route::middleware('auth')->group(...)`, tambahkan route GET sementara berikut sebelum route detail buku:

```php
Route::get('/buku/{buku}/hapus', [ApiBukuController::class, 'destroy'])
    ->name('buku.hapus.rentan');
```

Method `destroy()` memang sudah ada pada `app/Http/Controllers/Api/BukuController.php`. Route sementara ini sengaja hanya memakai `auth`, tanpa Gate dan tanpa perlindungan CSRF.

**File jebakan:** salin `docs/modul6/soal1/jebakan.html` ke luar folder proyek Laravel, misalnya `C:\Users\Public\jebakan.html`. Ubah alamat tautan di file itu agar sama dengan host lokal dan ID buku yang akan diuji, contohnya:

```html
<a href="http://perpustakaan.test/buku/1/hapus">Klik untuk melihat informasi</a>
```

Login sebagai admin atau petugas, buka file `jebakan.html`, lalu klik tautannya. Buku dengan ID tersebut harus hilang dari daftar. Ambil screenshot halaman/hasil setelah klik dan URL yang dipanggil.

### A4 — IDOR pada detail peminjaman

**File:** `app/Http/Controllers/Modul6/PeminjamanSecurityController.php`

Pada method `show()`, hilangkan sementara pemanggilan `Gate::authorize()` sehingga method menjadi:

```php
public function show(string $id)
{
    $peminjaman = Peminjaman::with(['anggota', 'buku'])->findOrFail($id);

    return view('modul6.peminjaman.show', compact('peminjaman'));
}
```

Route `/peminjaman/{id}` sudah berada dalam grup `auth`, sehingga harus login. Login sebagai siswa, buka ID peminjaman milik siswa lain, dan pastikan detail dapat terbuka dengan HTTP 200. Screenshot harus memperlihatkan URL dan detail peminjaman.

### Commit Soal 1

Setelah A1–A4 aktif dan keempat hasil serangan sudah dibuktikan, lakukan satu commit untuk seluruh empat celah:

```powershell
git add app/Http/Controllers/BukuController.php app/Http/Controllers/Modul6/PeminjamanSecurityController.php routes/web.php resources/views/buku/index.blade.php
git commit -m "Soal 1: Audit empat celah keamanan"
```

Jangan masukkan `.env`, database lokal, atau kredensial ke commit. Apabila `jebakan.html` disimpan di luar proyek, file itu tidak perlu di-commit bersama proyek.

## Soal 2 — Tambal A1, A2, dan A3

### Patch A1

Di `resources/views/buku/index.blade.php`, ganti `{!! $item->judul !!}` menjadi:

```blade
<td><a href="{{ route('buku.show', $item->id) }}">{{ $item->judul }}</a></td>
```

Output judul sekarang di-escape oleh Blade. Payload harus tampil sebagai teks dan tidak menjalankan JavaScript.

### Patch A2

Di `BukuController::cari()`, ganti query menjadi query binding:

```php
public function cari(Request $request)
{
    $judul = $request->query('judul', '');
    $data = DB::select('SELECT * FROM bukus WHERE judul = ?', [$judul]);

    return response()->json($data);
}
```

Uji lagi URL SQL Injection. Hasil yang diharapkan adalah array kosong `[]`.

### Patch A3

Hapus route GET `/buku/{buku}/hapus` yang ditambahkan pada Soal 1 dan hapus import `ApiBukuController` bila tidak dipakai lagi di `routes/web.php`.

Route aman `DELETE` sudah tersedia pada kondisi akhir proyek:

```php
Route::delete('/buku/{buku}', [BukuController::class, 'destroyWeb'])
    ->middleware('can:kelola-buku')
    ->name('buku.destroy');
```

Form penghapusan pada `resources/views/buku/index.blade.php` memakai:

```blade
<form method="POST" action="{{ route('buku.destroy', $item->id) }}">
    @csrf
    @method('DELETE')
    <button type="submit" class="button danger small">Hapus</button>
</form>
```

Tabel yang dimuat melalui Fetch juga membentuk form POST dengan hidden `_token` dan `_method=DELETE`, sehingga tombol Hapus yang tampil tetap menggunakan route web `DELETE` dan token CSRF. Uji tombol Hapus dari aplikasi; buku harus terhapus. Buka kembali `jebakan.html`; tautan GET harus gagal (404/405) dan tidak menghapus data.

Untuk pengujian Postman, gunakan request ke `DELETE /buku/{id}` tanpa token CSRF dalam konteks sesi web yang sudah login. Hasil yang diminta tugas adalah 419. Pastikan pengujian tidak sekadar menjadi 302 ke halaman login karena sesi autentikasinya tidak terbawa.

Commit:

```powershell
git add app/Http/Controllers/BukuController.php resources/views/buku/index.blade.php public/js/buku-api.js routes/web.php
git commit -m "Soal 2: Perbaiki XSS SQL Injection dan CSRF"
```

## Soal 3 — Gate pengelolaan buku

Implementasi Gate sudah tersedia pada `app/Providers/AppServiceProvider.php`:

```php
Gate::define('kelola-buku', function ($user): bool {
    return $user->hasRole(['admin', 'petugas']);
});
```

Gate dipakai pada route web penghapusan dan route API `store`, `update`, serta `destroy` di `routes/api.php` melalui `can:kelola-buku`. Halaman daftar buku menggunakan `@can('kelola-buku')` untuk menampilkan form dan tombol kelola.

Periksa route:

```powershell
php artisan optimize:clear
php artisan route:list -v --path=buku
```

Uji:
- Admin: tambah, ubah, dan hapus buku berhasil.
- Petugas: tambah, ubah, dan hapus buku berhasil.
- Siswa: tombol kelola tidak tampil dan membuka `/buku/create` secara langsung menghasilkan 403.

Commit:

```powershell
git add app/Providers/AppServiceProvider.php routes/web.php routes/api.php resources/views/buku/index.blade.php
git commit -m "Soal 3: Terapkan Gate pengelolaan buku"
```

## Soal 4 — Policy anti-IDOR

Implementasi sudah tersedia pada:
- `app/Policies/PeminjamanPolicy.php`
- `app/Http/Controllers/Modul6/PeminjamanSecurityController.php`

Policy mengizinkan admin dan petugas melihat semua peminjaman. Siswa hanya dapat melihat transaksi miliknya berdasarkan `user_id`; untuk transaksi lama tanpa `user_id`, kepemilikan dicocokkan melalui email akun dan email anggota.

Pastikan method `show()` memuat data, lalu melakukan otorisasi sebelum menampilkan view:

```php
public function show(string $id)
{
    $peminjaman = Peminjaman::with(['anggota', 'buku'])->findOrFail($id);

    Gate::authorize('view', $peminjaman);

    return view('modul6.peminjaman.show', compact('peminjaman'));
}
```

Uji tiga kondisi:
1. Siswa pemilik membuka peminjamannya sendiri: HTTP 200.
2. Siswa lain membuka ID yang bukan miliknya: HTTP 403.
3. Petugas membuka peminjaman siapa pun: HTTP 200.

Pastikan email akun siswa dan data anggota cocok untuk data lama yang tidak mempunyai `user_id`.

Commit:

```powershell
git add app/Policies/PeminjamanPolicy.php app/Http/Controllers/Modul6/PeminjamanSecurityController.php
git commit -m "Soal 4: Terapkan Policy peminjaman anti IDOR"
```

## Soal 5 — Penguatan konfigurasi dan pengujian akhir

### A. Nonaktifkan debug

Pada `.env` lokal:

```env
APP_DEBUG=false
```

Jalankan:

```powershell
php artisan optimize:clear
```

Tambahkan route sementara berikut ke `routes/web.php` hanya untuk pengujian lokal:

```php
Route::get('/uji-error-modul6', function () {
    throw new RuntimeException('Pengujian keamanan Modul 6');
});
```

Buka `/uji-error-modul6`. Pesan error harus generik tanpa stack trace. Setelah screenshot, hapus route sementara dan jalankan `php artisan optimize:clear` lagi.

### B. Uji akses `.env`

Buka `http://perpustakaan.test/.env` (sesuaikan host). Hasil yang diharapkan 404 atau 403. Jangan pernah menampilkan isi `.env` pada laporan.

### C. Ganti password admin bawaan

Atur password yang kuat dan pribadi pada `.env` lokal:

```env
SEED_ADMIN_PASSWORD=GantiDenganPasswordPribadiMinimal12Karakter
```

Jangan gunakan contoh password tersebut secara literal. Seeder membaca variabel ini melalui `config/app.php` dan `UserSeeder` memakai `updateOrCreate` untuk akun admin sehingga password lama diganti.

Jalankan:

```powershell
php artisan config:clear
php artisan db:seed --class=UserSeeder
php artisan optimize:clear
```

Login admin harus memakai password baru; password bawaan sebelumnya harus ditolak.

### Tabel pengujian akhir

Isi hasil aktual berdasarkan pengujian lokal dan screenshot sendiri:

| No. | Role | Skenario | Hasil yang diharapkan |
|---:|---|---|---|
| 1 | Belum login | Membuka `/peminjaman/1` | Dialihkan ke halaman login |
| 2 | Admin | Tambah judul `<script>alert(1)</script>` | Judul tampil sebagai teks, tanpa popup |
| 3 | Admin | `/buku-cari?judul=' OR '1'='1` | `[]` |
| 4 | Admin | DELETE `/buku/{id}` via Postman tanpa token | 419 Page Expired |
| 5 | Admin | Hapus buku lewat tombol | Buku terhapus |
| 6 | Admin | Klik tautan di `jebakan.html` | Buku tidak terhapus; 404/405 |
| 7 | Siswa | Buka `/buku/create` | 403; tombol kelola tidak tampil |
| 8 | Siswa pemilik | Buka peminjaman sendiri | 200, data tampil |
| 9 | Siswa lain | Buka peminjaman milik siswa lain | 403 Forbidden |
| 10 | Petugas | Buka peminjaman siswa mana pun | 200, data tampil |
| 11 | Semua | Buka `/uji-error-modul6` setelah debug false | Pesan umum tanpa jejak kode |
| 12 | Semua | Buka `/.env` dan login admin dengan password lama | 404/403; login lama ditolak |

Commit:

```powershell
git add app routes resources public database/seeders/UserSeeder.php config/app.php .env.example .gitignore docs/modul6
git commit -m "Soal 5: Penguatan konfigurasi dan pengujian akhir"
git status
git log --oneline
```

Jangan pernah menambahkan `.env` ke `git add`. Jika `git status` menampilkan `.env`, perbaiki `.gitignore` sebelum commit.

## Daftar screenshot laporan

- Soal 1: A1 popup NIM; A2 semua buku tampil; A3 buku terhapus setelah klik jebakan; A4 siswa membuka transaksi siswa lain.
- Soal 2: A1 payload tampil sebagai teks; A2 hasil `[]`; A3 link GET gagal, tombol aplikasi berhasil, Postman tanpa token 419.
- Soal 3: akun siswa mendapat 403 dan tidak melihat tombol; akun petugas/admin bisa mengelola buku.
- Soal 4: siswa pemilik 200, siswa lain 403, petugas 200.
- Soal 5: error generik, akses `/.env` ditolak, password lama ditolak, serta tabel 12 skenario terisi hasil aktual.

Setiap screenshot harus memperlihatkan URL browser atau request URL dan status/hasil yang diminta. Gunakan NIM `250441100045` dalam payload sesuai instruksi.
