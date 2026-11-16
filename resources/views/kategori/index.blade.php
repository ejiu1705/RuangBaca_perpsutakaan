@extends('layouts.perpustakaan')

@section('judul', 'Manajemen Kategori - Perpustakaan Digital')

@section('konten')
    <div class="page-heading">
        <div>
            <span class="eyebrow">PENGELOLAAN MASTER DATA</span>
            <h1>Manajemen Kategori</h1>
            <p>Buat, ubah, dan kelola kategori untuk mengelompokkan koleksi buku.</p>
        </div>
        <a class="button secondary" href="{{ route('buku.index') }}">Kembali ke Katalog</a>
    </div>

    <div class="management-stats">
        <article class="management-stat"><span>Total Kategori</span><strong>{{ $totalKategori }}</strong><small>Seluruh kategori terdaftar</small></article>
        <article class="management-stat"><span>Total Buku</span><strong>{{ $totalBuku }}</strong><small>Buku dalam seluruh kategori</small></article>
        <article class="management-stat"><span>Kategori Terpakai</span><strong>{{ $kategoriTerpakai }}</strong><small>Kategori yang memiliki koleksi</small></article>
    </div>

    <section class="detail-card category-create-card">
        <div class="section-heading">
            <div><span class="eyebrow">LANGKAH 1</span><h2>Tambah Kategori Baru</h2></div>
            <span class="section-note">Nama kategori 2–80 karakter</span>
        </div>
        <form action="{{ route('kategori.store') }}" method="POST" class="category-create-form">
            @csrf
            <label for="nama-kategori">Nama kategori
                <input id="nama-kategori" type="text" name="nama" value="{{ old('nama') }}" maxlength="80" minlength="2" placeholder="Contoh: Kecerdasan Buatan" required>
            </label>
            <button class="button" type="submit">Tambah Kategori</button>
        </form>
        <p class="form-hint">Kategori yang ditambahkan langsung tersedia pada pilihan kategori di formulir tambah dan ubah buku.</p>
    </section>

    <section class="detail-card catalog-card">
        <div class="table-heading">
            <div><span class="eyebrow">LANGKAH 2</span><h2>Daftar Kategori</h2></div>
            <span class="section-note">{{ $totalKategori }} kategori</span>
        </div>

        @if ($kategori->isEmpty())
            <div class="empty-management"><strong>Belum ada kategori.</strong><span>Tambahkan kategori pertama menggunakan formulir di atas.</span></div>
        @else
            <div class="category-list">
                @foreach ($kategori as $item)
                    <article class="category-row">
                        <div class="category-identity">
                            <span class="category-icon">{{ strtoupper(substr($item->nama, 0, 1)) }}</span>
                            <div><strong>{{ $item->nama }}</strong><small>ID kategori #{{ $item->id }}</small></div>
                        </div>
                        <div class="category-count"><strong>{{ $item->bukus_count }}</strong><span>buku</span></div>
                        <form action="{{ route('kategori.update', $item) }}" method="POST" class="category-edit-form">
                            @csrf
                            @method('PATCH')
                            <label class="sr-only" for="kategori-{{ $item->id }}">Ubah nama kategori {{ $item->nama }}</label>
                            <input id="kategori-{{ $item->id }}" type="text" name="nama" value="{{ $item->nama }}" maxlength="80" minlength="2" required>
                            <button class="button secondary small" type="submit">Simpan</button>
                        </form>
                        <form action="{{ route('kategori.destroy', $item) }}" method="POST" class="category-delete-form" onsubmit="return confirm('Hapus kategori ini?')">
                            @csrf
                            @method('DELETE')
                            <button class="button danger small" type="submit" @if ($item->bukus_count > 0) disabled title="Kategori masih digunakan" @endif>Hapus</button>
                        </form>
                    </article>
                @endforeach
            </div>
            <p class="form-hint">Kategori yang masih memiliki buku tidak dapat dihapus. Ubah kategori buku terlebih dahulu agar relasi data tetap aman.</p>
        @endif
    </section>
@endsection
