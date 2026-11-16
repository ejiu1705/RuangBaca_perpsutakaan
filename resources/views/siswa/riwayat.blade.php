<x-app-layout>
    <x-slot name="header"><x-page-heading eyebrow="SIRKULASI / RIWAYAT" title="Riwayat Peminjaman" description="Lihat status pengajuan dan daftar buku yang Anda pinjam." /></x-slot>
    <div class="rb-content">

        @can('mengajukan peminjaman buku')
            <section class="rb-card">
                <div class="rb-card-heading"><div><span class="eyebrow">PENGAJUAN BARU</span><h2>Ajukan Peminjaman Buku</h2><p>Pilih buku dari katalog untuk mengirim pengajuan kepada petugas.</p></div></div>
                <form method="POST" action="{{ route('peminjaman.store') }}" class="rb-inline-request-form">
                    @csrf
                    <label class="rb-form-field" for="buku_id"><span>Pilih Buku</span>
                        <select id="buku_id" name="buku_id" required>
                            <option value="">Pilih judul buku</option>
                            @foreach ($buku as $item)
                                <option value="{{ $item->id }}" @selected(old('buku_id') == $item->id)>{{ $item->judul }}</option>
                            @endforeach
                        </select>
                        <small>{{ $errors->first("buku_id") }}</small>
                    </label>
                    <button class="button" type="submit">Kirim Pengajuan</button>
                </form>
            </section>
        @endcan

        <section class="rb-card">
            <div class="rb-card-heading"><div><span class="eyebrow">RIWAYAT AKUN</span><h2>Daftar Peminjaman Saya</h2><p>Data yang ditampilkan hanya berasal dari akun yang sedang masuk.</p></div><span class="rb-count-chip">{{ number_format($peminjamans->total(), 0, ',', '.') }} transaksi</span></div>
            <div class="rb-table-wrap"><table class="rb-table"><thead><tr><th>ID</th><th>Judul Buku</th><th>Tanggal Peminjaman</th><th>Tanggal Pengembalian</th><th>Status</th></tr></thead><tbody>
                @forelse ($peminjamans as $item)
                    <tr><td>#{{ $item->id }}</td><td><strong class="rb-primary-text">{{ $item->buku?->judul ?? 'Buku tidak tersedia' }}</strong></td><td>{{ $item->tanggal_pinjam?->translatedFormat('d F Y') ?? 'Belum dicatat' }}</td><td>{{ $item->tanggal_kembali?->translatedFormat('d F Y') ?? 'Belum dikembalikan' }}</td><td><x-status-badge :status="$item->status ?? 'menunggu'" /></td></tr>
                @empty
                    <tr><td colspan="5"><div class="rb-empty-state"><strong>Belum ada riwayat peminjaman.</strong><span>Ajukan peminjaman melalui katalog untuk memulai.</span><a href="{{ route('buku.index') }}" class="button small">Buka Katalog Buku</a></div></td></tr>
                @endforelse
            </tbody></table></div>
            <div class="rb-pagination">{{ $peminjamans->links() }}</div>
        </section>
    </div>
</x-app-layout>
