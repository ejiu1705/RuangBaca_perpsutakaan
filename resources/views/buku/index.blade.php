@extends('layouts.perpustakaan')

@section('judul', 'Katalog Buku - Perpustakaan Digital')

@section('konten')
    <div class="page-heading catalog-page-heading">
        <div>
            <span class="eyebrow">SISTEM INFORMASI PERPUSTAKAAN</span>
            <h1>Katalog Buku</h1>
            <p>Kelola dan telusuri koleksi berdasarkan kategori, judul, atau penulis.</p>
        </div>
        <div class="catalog-heading-actions">
            <span class="page-tag">REST API · FETCH · PAGINATION</span>
        </div>
    </div>

    <div class="catalog-stats">
        <article class="catalog-stat"><span class="stat-symbol">BK</span><div><small>Total Koleksi</small><strong>{{ number_format($totalBuku) }}</strong><span>buku terdaftar</span></div></article>
        <article class="catalog-stat"><span class="stat-symbol category-symbol">KT</span><div><small>Kategori Tersedia</small><strong id="total-kategori">{{ number_format($totalKategori) }}</strong><span>kelompok koleksi</span></div></article>
        <article class="catalog-stat"><span class="stat-symbol year-symbol">TH</span><div><small>Terbitan Terbaru</small><strong>{{ $tahunTerbitTerbaru ?: '—' }}</strong><span>tahun terbit terbaru</span></div></article>
    </div>

    <div id="pesan-api" class="api-message" role="status" aria-live="polite"></div>

    @can('kelola-buku')
        <section class="detail-card api-form-card book-editor-card" id="panel-form-buku">
            <div class="section-heading">
                <div><span class="eyebrow">FORMULIR KOLEKSI</span><h2 id="judul-form">Tambah Buku Baru</h2><p class="section-description">Isi informasi buku secara berurutan. Data akan divalidasi sebelum disimpan.</p></div>
                <span class="editor-step">01 <i>/</i> 02</span>
            </div>

            <form id="form-buku" autocomplete="off">
                <input type="hidden" id="buku-id">
                <div class="book-form-sections">
                    <fieldset class="book-form-section">
                        <legend><span>01</span> Informasi Utama</legend>
                        <div class="form-grid">
                            <label for="judul">Judul Buku <em>*</em>
                                <input type="text" id="judul" minlength="3" maxlength="150" placeholder="Contoh: Dasar Pemrograman Web" required>
                                <small>Gunakan judul sesuai yang tercantum pada buku.</small>
                            </label>
                            <label for="penulis">Nama Penulis <em>*</em>
                                <input type="text" id="penulis" minlength="3" maxlength="100" placeholder="Masukkan nama penulis" required>
                                <small>Minimal 3 karakter dan maksimal 100 karakter.</small>
                            </label>
                        </div>
                    </fieldset>

                    <fieldset class="book-form-section">
                        <legend><span>02</span> Klasifikasi dan Penerbitan</legend>
                        <div class="form-grid">
                            <div class="category-input-group">
                                <label for="kategori_id">Kategori Buku <em>*</em></label>
                                <select id="kategori_id" required>
                                    <option value="">{{ $kategori->isEmpty() ? 'Belum ada kategori — tambahkan kategori baru' : 'Pilih kategori buku' }}</option>
                                    @foreach ($kategori as $item)
                                        <option value="{{ $item->id }}">{{ $item->nama }}</option>
                                    @endforeach
                                </select>
                                <div class="category-inline-actions">
                                    <small>Pilih kategori yang tersedia atau buat kategori baru di sini.</small>
                                    <button type="button" class="text-action" id="btn-tambah-kategori">+ Tambah kategori baru</button>
                                </div>
                                <div class="inline-category-panel" id="panel-kategori-baru" hidden>
                                    <label for="nama-kategori-baru">Nama kategori baru</label>
                                    <div class="inline-category-controls">
                                        <input type="text" id="nama-kategori-baru" minlength="2" maxlength="80" placeholder="Contoh: Kecerdasan Buatan">
                                        <button type="button" class="button small" id="btn-simpan-kategori">Tambahkan</button>
                                        <button type="button" class="button secondary small" id="btn-batal-kategori">Batal</button>
                                    </div>
                                    <small class="inline-category-message" id="pesan-kategori-baru" role="status" aria-live="polite"></small>
                                </div>
                            </div>
                            <label for="tahun_terbit">Tahun Terbit <em>*</em>
                                <input type="number" id="tahun_terbit" min="1900" max="{{ date('Y') }}" placeholder="{{ date('Y') }}" required>
                                <small>Masukkan tahun terbit antara 1900 hingga {{ date('Y') }}.</small>
                            </label>
                        </div>
                    </fieldset>
                </div>

                <div class="form-footer">
                    <span class="required-note"><em>*</em> Wajib diisi. Periksa kembali informasi sebelum menyimpan.</span>
                    <div class="form-actions">
                        <button class="button secondary" type="button" id="btn-batal" hidden>Batalkan Edit</button>
                        <button class="button" type="submit" id="btn-simpan">Simpan Buku</button>
                    </div>
                </div>
            </form>
        </section>
    @endcan

    <section class="detail-card catalog-card">
        <div class="table-heading">
            <div><span class="eyebrow">DATABASE KOLEKSI</span><h2>Daftar Buku</h2><p class="section-description">Gunakan pencarian dan filter untuk menemukan koleksi lebih cepat.</p></div>
            <button class="button secondary" id="btn-muat-ulang" type="button">Muat Ulang</button>
        </div>

        <div class="catalog-toolbar">
            <label class="catalog-search-field" for="cari-buku"><span>Cari buku</span><input type="search" id="cari-buku" placeholder="Ketik judul atau nama penulis..." autocomplete="off"></label>
            <label class="catalog-filter-field" for="filter-kategori"><span>Filter kategori</span>
                <select id="filter-kategori"><option value="">Semua kategori</option>@foreach ($kategori as $item)<option value="{{ $item->id }}">{{ $item->nama }}</option>@endforeach</select>
            </label>
            <label class="catalog-filter-field" for="urut-buku"><span>Urutkan</span>
                <select id="urut-buku"><option value="terbaru">Terbaru ditambahkan</option><option value="judul">Judul A–Z</option><option value="tahun">Tahun terbit terbaru</option></select>
            </label>
            <button class="button" id="btn-cari" type="button">Terapkan</button>
            <button class="button secondary" id="btn-reset-cari" type="button">Reset</button>
        </div>

        <div class="table-wrap">
            <table class="api-table">
                <thead><tr><th>No.</th><th>Kategori</th><th>Judul Buku</th><th>Penulis</th><th>Tahun</th>@can('kelola-buku')<th>Aksi</th>@endcan</tr></thead>
                <tbody id="tabel-buku">
                    @forelse ($daftarBuku as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td><span class="category-badge">{{ $item->kategori?->nama ?? 'Tanpa kategori' }}</span></td>
                            <td><a href="{{ route('buku.show', $item->id) }}">{{ $item->judul }}</a></td>
                            <td>{{ $item->penulis }}</td>
                            <td>{{ $item->tahun_terbit }}</td>
                            @can('kelola-buku')
                                <td class="action-cell"><button type="button" class="button secondary small" data-action="edit" data-id="{{ $item->id }}">Ubah</button>
                                    <form method="POST" action="{{ route('buku.destroy', $item->id) }}" class="inline-delete-form">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="button danger small" data-delete-book data-title="{{ $item->judul }}" @if ($item->peminjamans_count > 0) disabled title="Buku memiliki riwayat peminjaman" @endif>Hapus</button>
                                    </form>
                                </td>
                            @endcan
                        </tr>
                    @empty
                        <tr><td colspan="{{ auth()->user()->can('kelola-buku') ? 6 : 5 }}" class="table-empty">Belum ada data buku.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="pagination-row"><span id="info-halaman">Menyiapkan data...</span><div><button class="button secondary small" id="btn-sebelumnya" type="button" disabled>Sebelumnya</button><button class="button secondary small" id="btn-berikutnya" type="button" disabled>Berikutnya</button></div></div>
    </section>
@endsection

@push('scripts')
    <script>window.CAN_MANAGE_BOOKS = @json(auth()->user()->can('kelola-buku')); window.CATEGORY_STORE_URL = @json(route('buku.kategori.store'));</script>
    <script src="{{ asset('js/buku-api.js') }}" defer></script>
@endpush
