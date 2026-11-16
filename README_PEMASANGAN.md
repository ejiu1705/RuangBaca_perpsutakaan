# Panduan Pemasangan RuangBaca

Dokumen ini merupakan panduan singkat menjalankan proyek RuangBaca yang sudah berisi integrasi Modul 1–6 dan persiapan Modul VII. Untuk langkah deployment lengkap, gunakan `PANDUAN_MODUL7.md`.

## 1. Persiapan

Pastikan perangkat sudah memiliki PHP sesuai kebutuhan Laravel 13, Composer, Node.js 22.x, npm, dan Laragon atau web server PHP setara.

Buka terminal pada folder proyek. Jika `.env` belum tersedia, salin template berikut:

```powershell
copy .env.example .env
```

Jangan menimpa `.env` yang sudah berisi konfigurasi aktif.

## 2. Pasang dependency

```bash
composer install
npm ci
```

## 3. Atur environment

Untuk praktikum Modul VII, gunakan koneksi Supabase PostgreSQL Session Pooler port `5432` saat bekerja dari komputer lokal. Aktifkan ekstensi `pdo_pgsql` dan `pgsql` pada `php.ini`.

Isi `.env` dengan detail koneksi dari Supabase **Connect**. Gunakan host dan username persis seperti yang disediakan. Isi password akun seeder pribadi dengan panjang minimal 12 karakter jika database memakai PostgreSQL.

## 4. Migrasi dan seeder

```bash
php artisan config:clear
php artisan db:show
php artisan migrate
php artisan db:seed
```

Jangan jalankan `migrate:fresh` pada database yang sudah berisi data penting. Jika database sudah berisi tabel dan data dari Modul 1–6, periksa `php artisan migrate:status` sebelum menjalankan migrasi.

## 5. Jalankan aplikasi

Untuk penggunaan lokal tanpa Vite server terpisah, buat bundle aset terlebih dahulu:

```bash
npm run build
php artisan optimize:clear
php artisan serve
```

Buka `http://127.0.0.1:8000`.

Paket ini juga menyertakan bundle tampilan awal pada `public/build` agar halaman tidak tampil polos sebelum dependency frontend dipasang ulang. Sumber utama CSS tetap `resources/css/app.css`; saat `npm run build` dijalankan, Vite akan mengganti bundle awal tersebut dengan hasil build lengkap termasuk utilitas Tailwind.

Untuk mode pengembangan dengan hot reload, gunakan dua terminal dan biarkan keduanya aktif:

Terminal pertama:
```bash
npm run dev
```

Terminal kedua:
```bash
php artisan serve
```

Jika Vite pernah dihentikan paksa dan file `public/hot` tertinggal, hapus file tersebut lalu jalankan `npm run build`. Bundle Vite sekarang memuat seluruh stylesheet RuangBaca, Alpine.js, dan skrip katalog. Perintah build juga memeriksa bahwa stylesheet login dan dashboard benar-benar masuk ke hasil build.

## 6. Uji konsistensi dan hak akses

Periksa seluruh halaman sebagai Administrator, Petugas, dan Siswa. Gunakan `docs/ui/PEDOMAN_KONSISTENSI.md` untuk istilah antarmuka baku. Pastikan Siswa tidak melihat kontrol pengelolaan buku, tidak dapat membuka route pengelolaan, dan hanya dapat melihat peminjaman miliknya sendiri.

## 7. Lanjutkan Modul VII

Ikuti keenam soal pada `PANDUAN_MODUL7.md`. Pengujian skenario production harus dilakukan pada URL Vercel, bukan localhost, dan hasil aktual harus diisi berdasarkan pengujian nyata.

## Catatan keamanan

Jangan commit `.env`, `.env.production`, `APP_KEY`, password database, password akun seeder, atau token Vercel. Gunakan `.env.example` sebagai template aman. Untuk Vercel production, gunakan Supabase Transaction Pooler port `6543`, `APP_DEBUG=false`, `SESSION_DRIVER=database`, dan `SESSION_SECURE_COOKIE=true`.
