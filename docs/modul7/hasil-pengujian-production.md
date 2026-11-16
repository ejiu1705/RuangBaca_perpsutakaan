# Catatan Pengujian Production — Modul VII

**Nama aplikasi:** RuangBaca — Perpustakaan Digital  
**NIM:** 250441100045  
**URL Vercel:** Isi setelah deployment berstatus Ready  
**Tanggal pengujian:** Isi tanggal pengujian  
**Browser/perangkat:** Isi sesuai perangkat yang digunakan

> Isi kolom hasil aktual berdasarkan pengujian nyata pada URL Vercel. Jangan menganggap hasil yang diharapkan sebagai hasil aktual. Sertakan screenshot yang menampilkan URL dan respons/halaman terkait pada laporan resmi.

| No. | Peran | Skenario | Hasil yang diharapkan | Hasil aktual | Status |
|---:|---|---|---|---|---|
| 1 | Belum login | Membuka URL utama aplikasi | Halaman tampil tanpa pesan galat | Belum diuji | Belum diuji |
| 2 | Belum login | Membuka `/peminjaman/1` | Dialihkan ke halaman masuk | Belum diuji | Belum diuji |
| 3 | Administratoristrator | Masuk dengan akun administrator dan kata sandi baru | Berhasil masuk | Belum diuji | Belum diuji |
| 4 | Administratoristrator | Masuk dengan kata sandi `password` | Masuk ditolak | Belum diuji | Belum diuji |
| 5 | Administratoristrator | Menambahkan buku dengan judul berisi NIM `250441100045` | Buku tampil pada katalog dan Table Editor Supabase | Belum diuji | Belum diuji |
| 6 | Administratoristrator | Mengubah lalu menghapus buku uji | Perubahan dan penghapusan berhasil | Belum diuji | Belum diuji |
| 7 | Siswa | Masuk lalu membuka katalog buku | Daftar tampil; tombol Tambah, Ubah, dan Hapus tidak tampil | Belum diuji | Belum diuji |
| 8 | Siswa | Membuka `/buku/create` secara langsung | Respons `403 Forbidden` | Belum diuji | Belum diuji |
| 9 | Siswa | Membuka peminjaman milik sendiri | Respons `200`, data tampil | Belum diuji | Belum diuji |
| 10 | Siswa | Membuka transaksi milik siswa lain | Respons `403 Forbidden` | Belum diuji | Belum diuji |
| 11 | Petugas | Membuka transaksi milik siswa mana pun | Respons `200`, data tampil | Belum diuji | Belum diuji |
| 12 | Semua | Membuka `/.env` | Respons `404` atau `403` | Belum diuji | Belum diuji |
| 13 | Semua | Membuka `/halaman-tidak-ada` | Halaman `404` umum tanpa detail kode | Belum diuji | Belum diuji |

## Catatan kendala dan perbaikan

| No. skenario | Hasil awal | Penyebab | Perbaikan | Hasil uji ulang |
|---|---|---|---|---|
| Isi jika ditemukan kendala | | | | |

## Ringkasan perbedaan lokal dan production

Isi setelah seluruh skenario diuji. Catat perbedaan yang benar-benar ditemukan, misalnya konfigurasi database, koneksi, hak akses, sesi, atau perilaku deployment. Jangan mencantumkan nilai rahasia.

## Daftar screenshot

- [ ] URL utama tanpa login
- [ ] Redirect ke halaman masuk
- [ ] Login administrator berhasil dan ditolak
- [ ] Katalog setelah tambah, ubah, dan hapus buku uji
- [ ] Tampilan katalog akun Siswa
- [ ] Respons 403 untuk akses yang dilarang
- [ ] Detail transaksi siswa sendiri dan siswa lain
- [ ] Akses `/.env`
- [ ] Halaman 404 umum
- [ ] Screenshot Table Editor Supabase
