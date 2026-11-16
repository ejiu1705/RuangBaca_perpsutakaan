# Pedoman Konsistensi Antarmuka RuangBaca

Dokumen ini menjadi acuan tunggal untuk seluruh teks dan pola tampilan. Setiap perubahan pada halaman, komponen, JavaScript, atau pesan backend harus mengikuti istilah dan format yang ditetapkan di sini.

## Identitas aplikasi

- Nama produk: **RuangBaca**.
- Deskripsi produk: **Perpustakaan Digital**.
- Identitas header/sidebar: **RuangBaca · Perpustakaan Digital**.
- Workspace statis: **Ruang Kerja / Perpustakaan Utama** (bukan tombol, tanpa ikon panah).
- Bahasa antarmuka: Bahasa Indonesia.
- Zona waktu: `Asia/Jakarta`.

## Istilah baku

| Konteks | Istilah baku |
|---|---|
| Peran admin | Administrator |
| Peran operator | Petugas |
| Peran peminjam pelajar | Siswa |
| Halaman utama setelah login | Dashboard |
| Daftar koleksi | Katalog Buku |
| Pengelolaan transaksi | Peminjaman |
| Riwayat milik siswa | Riwayat Peminjaman |
| Data akun | Pengguna |
| Halaman akun sendiri | Profil Saya |
| Kata sandi | Kata Sandi |
| Alamat email | Alamat Email |
| Status pengajuan awal | Menunggu Persetujuan |
| Status disetujui | Disetujui |
| Status ditolak | Ditolak |
| Status selesai | Dikembalikan |
| Status lewat tenggat | Terlambat |
| Buku tidak memiliki kategori | Tanpa kategori |
| Data buku tidak tersedia | Buku tidak tersedia |
| Data peminjam tidak tersedia | Peminjam tidak tersedia |
| Alamat email tidak tersedia | Alamat email tidak tersedia |
| Tanggal belum dicatat | Belum dicatat |
| Belum terjadi pengembalian | Belum dikembalikan |

Nama menu/halaman lengkap yang dipakai di topbar dan judul halaman:

- `Beranda`
- `Dashboard Administrator`
- `Dashboard Petugas`
- `Dashboard Siswa`
- `Katalog Buku`
- `Detail Buku`
- `Pengelolaan Peminjaman`
- `Detail Peminjaman`
- `Riwayat Peminjaman`
- `Pengelolaan Pengguna`
- `Laporan Perpustakaan`
- `Profil Saya`

## Kapitalisasi, tanggal, dan angka

- Nama menu, judul halaman, tombol, dan label memakai kapitalisasi judul: `Katalog Buku`, `Simpan Buku`, `Riwayat Peminjaman`.
- Kalimat penjelas, pesan kosong, notifikasi, dan pesan validasi memakai kapitalisasi kalimat.
- Teks status selalu melalui komponen `x-status-badge`; jangan membuat label status terpisah.
- Nama peran selalu melalui komponen `x-role-label`.
- Tanggal tampil dalam format `d F Y` menggunakan locale `id`.
- Angka jumlah menggunakan `number_format($nilai, 0, ',', '.')`.
- Istilah satu tindakan harus sama pada tombol, konfirmasi, notifikasi sukses, dan pesan gagal.

## Pola layout

- Seluruh halaman setelah login menggunakan `x-app-layout`, `x-page-heading`, `.rb-content`, `.rb-card`, `.rb-table`, `.rb-form-field`, dan kelas tombol `.button`.
- Halaman publik memakai `layouts.perpustakaan`.
- Halaman autentikasi memakai `layouts.guest` dengan identitas warna, logo, tipografi, dan Bahasa Indonesia yang sama.
- Banner dashboard menggunakan teks terang yang kontras pada latar biru; aturan global judul tidak boleh menimpa warna banner.
- Workspace merupakan informasi identitas statis, bukan kontrol interaktif.
- Isi dashboard boleh berbeda sesuai hak akses peran, tetapi struktur, kartu, tabel, format tanggal, tombol, dan istilah bersama harus tetap seragam.

## Aturan validasi bahasa

1. Cari penggunaan istilah lama pada `resources/views`, `public/js`, `app/Http/Controllers`, dan `lang/id` sebelum menambah teks baru.
2. Gunakan Bahasa Indonesia pada teks aplikasi; nama teknologi seperti Laravel, Supabase, Vercel, GitHub, API, dan HTTP boleh tetap memakai nama resminya.
3. Pesan JavaScript, konfirmasi, validasi Laravel, pagination, halaman error, dan email autentikasi harus mengikuti Bahasa Indonesia.
4. Jangan mengubah teks yang bersifat data pengguna atau judul buku yang dimasukkan pengguna.
5. Jangan menerjemahkan nama route, kolom database, enum status, nama permission, atau identifier program karena merupakan kontrak teknis.
6. Perbedaan isi yang disebabkan hak akses Administrator, Petugas, dan Siswa diperbolehkan; perbedaan istilah atau desain untuk fungsi yang sama tidak diperbolehkan.
7. Setelah perubahan, periksa seluruh halaman sebagai Administrator, Petugas, dan Siswa serta lakukan pemeriksaan sintaks PHP/JavaScript/JSON.

## Pemeriksaan lintas halaman

- Beranda dan autentikasi menggunakan identitas `RuangBaca · Perpustakaan Digital`.
- Dashboard semua peran memakai label status baku, termasuk `Menunggu Persetujuan` dan `Peminjaman Aktif`.
- Katalog, detail buku, dan laporan memakai fallback kategori `Tanpa kategori` dan tahun kosong `Belum diisi`.
- Tabel, riwayat, dan detail peminjaman memakai fallback nama `Peminjam tidak tersedia`, email `Alamat email tidak tersedia`, judul buku `Buku tidak tersedia`, tanggal belum tercatat `Belum dicatat`, dan pengembalian belum terjadi `Belum dikembalikan`.
- Pesan sukses penolakan adalah `Pengajuan peminjaman berhasil ditolak.`
- Pesan penolakan akses menggunakan halaman 403 khusus dan tidak menampilkan stack trace.
