@extends('layouts.perpustakaan')

@section('judul', 'Detail Buku - Perpustakaan Digital')

@section('konten')
    <div class="page-heading"><div><span class="eyebrow">KATALOG PERPUSTAKAAN</span><h1>Detail Buku</h1><p>Informasi lengkap dari buku yang dipilih.</p></div><a class="button secondary" href="{{ route('buku.index') }}">Kembali ke Daftar</a></div>
    @if ($buku)
        <section class="detail-card book-detail-card">
            <div class="book-detail-mark">BOOK</div>
            <div class="book-detail-content"><span class="eyebrow">ID BUKU #{{ $buku['id'] }}</span><h2>{{ $buku['judul'] }}</h2>
                <dl class="book-meta"><div><dt>Penulis</dt><dd>{{ $buku['penulis'] ?: '-' }}</dd></div><div><dt>Tahun Terbit</dt><dd>{{ $buku['tahun'] ?: '-' }}</dd></div><div><dt>Kategori</dt><dd>{{ $buku['kategori'] ?: '-' }}</dd></div></dl>
                @can('mengajukan peminjaman buku')
                    <form method="POST" action="{{ route('peminjaman.store') }}">@csrf<input type="hidden" name="buku_id" value="{{ $buku['id'] }}"><button class="button" type="submit">Ajukan Peminjaman</button></form>
                @endcan
            </div>
        </section>
    @endif
@endsection
