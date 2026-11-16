# Implementasi Modul 5 — Sistem Perpustakaan Digital

Paket ini berisi file tambahan Modul 5 untuk digabungkan ke project Laravel perpustakaan dari Modul 1–4. Paket **bukan** project Laravel utuh karena source project asli tidak disertakan pada percakapan ini. File di dalam `app/`, `database/`, dan `resources/` dapat disalin ke project yang sudah ada; potongan pada `integrasi/` harus digabungkan dengan file lama agar route dan fitur Modul 1–4 tidak hilang.

## Asumsi yang digunakan dari laporan Modul 1–4

- Project menggunakan Laravel 13.
- Model buku: `App\Models\Buku`, kategori: `App\Models\Kategori`, dan relasi buku `kategori()` serta `peminjamans()`.
- Tabel kategori bernama `kategoris`, tabel buku bernama `bukus`, dan tabel peminjaman bernama `peminjamans`.
- Model peminjaman diasumsikan bernama `App\Models\Peminjaman`.
- Tabel `users` menggunakan kolom standar Breeze: `name`, `email`, `password`.
- Agar riwayat peminjaman dapat dimiliki akun, migration menambahkan `user_id` nullable ke `peminjamans`.

Jika nama tabel/model/relasi pada project berbeda, sesuaikan bagian yang ditandai pada file integrasi sebelum menjalankan migration.

## 1. Backup dan pemasangan dependensi

Buka terminal di folder project:

```powershell
cd C:\laragon\www\perpustakaan_250441100045x
```

Backup database dan folder project terlebih dahulu. Jangan gunakan `migrate:fresh` karena akan menghapus data Modul 1–4.

```bash
composer require laravel/breeze --dev
php artisan breeze:install blade
npm install
composer require spatie/laravel-permission
php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"
```

Jika Breeze atau Spatie sudah terpasang dari pengerjaan sebelumnya, jangan mengulang instalasi. Salin folder `app/`, `database/`, dan `resources/` dari paket ke root project. Untuk file yang sudah ada, **gabungkan** perubahan dari `integrasi/` jangan menimpanya secara penuh.

## 2. Integrasi model User

Pada `app/Models/User.php`, gabungkan `HasRoles` ke trait yang sudah dipakai. Jangan menghapus `HasApiTokens` jika Modul 4 masih menggunakan Sanctum.

Tambahkan import dan trait dari `integrasi/User.php.snippet`. Tambahkan relasi `peminjamans()` hanya jika belum ada.

## 3. Registrasi otomatis sebagai Siswa

Pada `app/Http/Controllers/Auth/RegisteredUserController.php`, setelah `User::create(...)`, tambahkan:

```php
$user->assignRole('siswa');
```

Jangan menambahkan input role pada form registrasi. Breeze sudah menggunakan `Hash::make()` untuk menyimpan password.

## 4. Middleware alias Spatie

Pada `bootstrap/app.php`, gabungkan alias `role` dan `permission` di callback `withMiddleware()` yang sudah ada. Jangan membuat callback kedua jika sudah tersedia.

## 5. Route web

Lihat `integrasi/routes-web.php.snippet`. Gabungkan route ke `routes/web.php` dan pastikan:

- route `/` bernama `home` tetap dipertahankan;
- route `/buku` dan `/buku/{id}` tetap memakai controller dan nama route lama, tetapi berada di dalam grup `auth` + permission `lihat daftar buku`;
- route CRUD buku yang sudah ada diberi permission `kelola data buku`;
- route lama tidak didaftarkan ulang dengan nama yang sama;
- route profil Breeze dibatasi dengan permission profil yang sesuai.

Contoh route Admin/Petugas/dashboard dan riwayat tersedia pada snippet. Sesuaikan namespace controller Buku dengan file yang benar pada project.

## 6. API Modul 3–4

Pertahankan endpoint dan login/token API yang sudah ada. Jika API Modul 4 tetap menggunakan bearer token Sanctum, tambahkan middleware `auth:sanctum` dan permission ke route CRUD yang sudah ada, bukan membuat route duplikat. Contoh pengelompokan ada di `integrasi/routes-api.php.snippet`.

Catatan: Fetch API dari browser yang sebelumnya memanggil API tanpa token akan perlu mengirim token Sanctum atau menggunakan jalur web berbasis session. Jangan memasang `auth:sanctum` tanpa memperbarui mekanisme token frontend yang digunakan di Modul 4.

## 7. Seeder role dan permission

Salin `RolePermissionSeeder.php`. Jalankan:

```bash
php artisan optimize:clear
php artisan migrate
php artisan db:seed --class=RolePermissionSeeder
```

Seeder membuat 3 role (`admin`, `petugas`, `siswa`) dan 16 permission sesuai tabel pada PDF tugas.

Untuk memanggilnya dari `php artisan db:seed`, tambahkan `RolePermissionSeeder::class` ke `$this->call([...])` di `DatabaseSeeder.php` tanpa menghapus seeder Modul 1–4.

## 8. Menghubungkan akun Admin dan Petugas

Registrasi biasa otomatis menjadi `siswa`. Buat akun melalui halaman register lalu ubah role melalui Tinker:

```bash
php artisan tinker
```

```php
$user = App\Models\User::where('email', 'email-admin-anda@example.com')->firstOrFail();
$user->syncRoles(['admin']);

$petugas = App\Models\User::where('email', 'email-petugas-anda@example.com')->firstOrFail();
$petugas->syncRoles(['petugas']);
```

Ganti alamat email dengan akun yang sudah dibuat. Penetapan role tidak boleh dilakukan melalui form registrasi.

## 9. Kepemilikan riwayat peminjaman

Migration `2026_11_02_000001_add_user_id_to_peminjamans_table.php` menambahkan `user_id` nullable ke tabel `peminjamans`. Pastikan tabel tersebut ada. Tambahkan relasi `user()` ke model Peminjaman dan `peminjamans()` ke User.

Pada method penyimpanan peminjaman yang sudah ada, set pemilik menggunakan identitas session:

```php
$peminjaman->user_id = auth()->id();
$peminjaman->save();
```

Jangan mengambil `user_id` dari request/form. Query riwayat harus menggunakan `where('user_id', auth()->id())`. Data peminjaman lama yang `user_id`-nya NULL tidak otomatis terhubung ke siswa; tentukan pemiliknya sebelum ditampilkan sebagai riwayat akun.

## 10. Jalankan dan verifikasi

```bash
php artisan optimize:clear
php artisan migrate:status
php artisan db:seed --class=RolePermissionSeeder
php artisan route:list
npm run dev
```

Jalankan server sesuai konfigurasi Laragon atau `php artisan serve`. Lakukan seluruh pengujian di `PENGUJIAN_MODUL5.md`. Kolom hasil aktual pada laporan harus diisi berdasarkan hasil tes nyata.

## File yang disediakan

- `app/Http/Controllers/Admin/UserManagementController.php` — daftar pengguna dan pengubahan role oleh Admin.
- `app/Http/Controllers/Admin/LaporanController.php` — ringkasan data buku/kategori.
- `app/Http/Controllers/Siswa/RiwayatPeminjamanController.php` — riwayat peminjaman yang dibatasi untuk user login.
- `database/seeders/RolePermissionSeeder.php` — tiga role dan 16 permission.
- `database/migrations/...add_user_id_to_peminjamans_table.php` — relasi peminjaman ke akun.
- `resources/views/...` — dashboard, manajemen pengguna, laporan, dan riwayat.
- `integrasi/` — potongan perubahan ke file Modul 1–4 yang harus digabungkan.


## Penyempurnaan antarmuka dashboard

Antarmuka seluruh halaman setelah login menggunakan sidebar dan topbar yang sama. Dashboard Admin, Petugas, dan Siswa menampilkan statistik serta aktivitas sesuai peran. Lihat `CATATAN_UI_DASHBOARD.md` untuk daftar file dan langkah pemeriksaan.
