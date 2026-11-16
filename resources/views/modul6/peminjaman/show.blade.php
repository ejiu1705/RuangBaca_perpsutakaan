@extends('layouts.perpustakaan')

@section('judul', 'Detail Peminjaman')

@section('konten')
    <div class="page-heading">
        <h2>Detail Peminjaman #{{ $peminjaman->id }}</h2>
        <p>Informasi transaksi peminjaman buku.</p>
    </div>

    <section class="detail-card">
        <p><strong>Anggota:</strong> {{ $peminjaman->anggota?->nama ?? '-' }}</p>
        <p><strong>Email anggota:</strong> {{ $peminjaman->anggota?->email ?? '-' }}</p>
        <p><strong>Buku:</strong> {{ $peminjaman->buku?->judul ?? '-' }}</p>
        <p><strong>Tanggal pinjam:</strong> {{ $peminjaman->tanggal_pinjam?->format('d-m-Y') ?? '-' }}</p>
        <p><strong>Tanggal kembali:</strong> {{ $peminjaman->tanggal_kembali?->format('d-m-Y') ?? '-' }}</p>
    </section>
@endsection
