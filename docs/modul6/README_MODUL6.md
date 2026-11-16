# Modul 6 — Keamanan Web dan Otorisasi

Modul 6 ditambahkan ke proyek Modul 1–5. Fitur lama (Laravel Breeze, role Spatie, daftar buku melalui API/Fetch, kategori, anggota, peminjaman, dan dashboard) dipertahankan.

## Implementasi yang ditambahkan

- `app/Providers/AppServiceProvider.php`: Gate `kelola-buku` untuk role `admin` dan `petugas`.
- `routes/api.php`: endpoint CRUD buku untuk pengelola memakai `can:kelola-buku`.
- `routes/web.php`: GET `/buku-cari` (binding), DELETE `/buku/{buku}` (auth + Gate + CSRF), GET `/peminjaman/{id}` (Policy).
- `app/Http/Controllers/BukuController.php`: pencarian judul aman dengan query binding dan delete route web.
- `app/Policies/PeminjamanPolicy.php`: siswa hanya melihat peminjaman milik anggota dengan email sama; admin/petugas bisa melihat semua.
- `app/Http/Controllers/Modul6/PeminjamanSecurityController.php`: detail peminjaman memanggil `Gate::authorize('view', $peminjaman)`.
- `resources/views/modul6/peminjaman/show.blade.php`: halaman detail memakai Blade escaping.
- `resources/views/buku/index.blade.php`: kontrol kelola buku menggunakan Gate `kelola-buku`.

### Catatan skema Modul 1–5

Tabel `peminjamans` pada proyek awal memiliki `anggota_id` tetapi belum mempunyai `user_id`. Agar tidak mengubah struktur database yang sudah ada, Policy Modul 6 mengaitkan akun siswa dengan anggota berdasarkan email (`users.email` ↔ `anggotas.email`). Pastikan email akun siswa dan data anggota sesuai. Tidak diperlukan migration baru.

## Instalasi / menjalankan

1. Ekstrak folder proyek dan pindahkan ke `C:\laragon\www\perpustakaan_250441100045x`.
2. Gunakan database Modul 1–5 yang sudah ada. Jangan menjalankan `migrate:fresh`.
3. Simpan `.env` lokal Anda (file `.env` tidak disertakan di arsip untuk melindungi kredensial). Pastikan `APP_DEBUG=false`.
4. Jalankan:

   ```bash
   php artisan optimize:clear
   php artisan route:list
   ```

6. Pastikan role Spatie pada akun admin/petugas/siswa sudah ada dari konfigurasi Modul 5.
7. Uji detail peminjaman dengan email user dan email anggota yang sama.

## Uji Modul 6

- XSS: input judul `<script>alert('250441100045')</script>`. Daftar buku saat ini menggunakan escape HTML pada JavaScript dan Blade; script harus tampil sebagai teks.
- SQL Injection: buka `/buku-cari?judul=' OR '1'='1`. Endpoint menggunakan binding, sehingga payload diperlakukan sebagai nilai dan tidak menampilkan seluruh tabel.
- CSRF: route penghapusan web hanya menerima DELETE. Form harus memakai `@csrf` dan `@method('DELETE')`. GET ke `/buku/{id}/hapus` tidak didaftarkan.
- Gate: admin/petugas boleh CRUD; siswa harus mendapat 403 untuk route kelola.
- IDOR: siswa pemilik harus dapat membuka `/peminjaman/{id}`; siswa lain mendapat 403; admin/petugas boleh melihat transaksi siapa pun.
- APP_DEBUG: set `false`, tambahkan route error sementara hanya saat pengujian, lalu hapus kembali.
- `.env`: file tidak disertakan dalam ZIP dan sudah dicantumkan pada `.gitignore`.

## Panduan mengerjakan tugas praktikum

Penting: source utama di arsip ini berada pada kondisi aman akhir untuk Soal 2–5. Untuk memenuhi Soal 1, pengguna harus mengaktifkan empat celah sementara, membuktikannya secara lokal, lalu membuat satu commit sebelum melakukan patch. Ikuti panduan langkah demi langkah beserta cuplikan kode yang sesuai dengan struktur proyek ini di [`PANDUAN_PRAKTIKUM_MODUL6.md`](PANDUAN_PRAKTIKUM_MODUL6.md).

Jangan mengaktifkan contoh rentan pada server publik. Jangan commit `.env`. Panduan tersebut juga memuat urutan commit Soal 1–5, skenario pengujian, dan daftar screenshot untuk laporan.
