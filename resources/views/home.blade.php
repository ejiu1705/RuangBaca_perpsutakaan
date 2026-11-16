@extends('layouts.perpustakaan')

@section('judul', 'Beranda - Perpustakaan Digital')

@section('konten')
    <section class="home-hero">
        <div class="home-hero-content">
            <span class="eyebrow">SISTEM INFORMASI PERPUSTAKAAN</span>
            <h1>Temukan bacaan yang tepat. Kelola peminjaman dengan lebih mudah.</h1>
            <p>Jelajahi koleksi buku, ajukan peminjaman, dan pantau status transaksi melalui satu sistem yang praktis dan terintegrasi.</p>
            <div class="home-actions">
                @auth
                    <a class="button" href="{{ route('dashboard') }}">Buka Dashboard</a>
                    @can('lihat daftar buku') <a class="button secondary" href="{{ route('buku.index') }}">Jelajahi Buku</a> @endcan
                @else
                    <a class="button" href="{{ route('login') }}">Masuk ke Akun</a>
                    <a class="button secondary" href="{{ route('register') }}">Daftar sebagai Siswa</a>
                @endauth
            </div>
        </div>
        <div class="home-hero-art" aria-hidden="true">
            <div class="book-stack"><span></span><span></span><span></span><span></span></div>
            <div class="hero-art-caption">READ · LEARN · GROW</div>
        </div>
    </section>

    <section class="feature-grid" aria-label="Fitur sistem">
        <article class="feature-card"><span class="feature-icon">01</span><h2>Koleksi Buku</h2><p>Lihat informasi buku, penulis, tahun terbit, dan kategori secara terstruktur.</p></article>
        <article class="feature-card"><span class="feature-icon">02</span><h2>Peminjaman</h2><p>Kirim pengajuan peminjaman dan pantau proses persetujuan dari akun Anda.</p></article>
        <article class="feature-card"><span class="feature-icon">03</span><h2>Akses Terjaga</h2><p>Hak akses dibedakan sesuai peran Admin, Petugas, dan Siswa.</p></article>
    </section>
@endsection
