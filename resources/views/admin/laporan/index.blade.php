<x-app-layout>
    <x-slot name="header"><x-page-heading eyebrow="ADMINISTRASI / LAPORAN" title="Laporan Perpustakaan" description="Ringkasan koleksi buku dan aktivitas peminjaman yang tercatat." /></x-slot>
    <div class="rb-content">
        <section class="rb-stat-grid rb-stat-grid-two">
            <article class="rb-stat-card"><span class="rb-stat-label">Total Buku</span><strong>{{ number_format($totalBuku, 0, ',', '.') }}</strong><small>Judul buku yang tercatat di katalog</small></article>
            <article class="rb-stat-card"><span class="rb-stat-label">Total Kategori</span><strong>{{ number_format($totalKategori, 0, ',', '.') }}</strong><small>Kelompok koleksi yang tersedia</small></article>
        </section>
        <section class="rb-card">
            <div class="rb-card-heading"><div><span class="eyebrow">REKAPITULASI KOLEKSI</span><h2>Rekap Buku dan Peminjaman</h2><p>Jumlah peminjaman dihitung dari seluruh transaksi untuk setiap buku.</p></div><span class="rb-count-chip">{{ number_format($buku->total(), 0, ',', '.') }} buku</span></div>
            <div class="rb-table-wrap"><table class="rb-table"><thead><tr><th>Judul Buku</th><th>Kategori</th><th>Penulis</th><th>Jumlah Peminjaman</th></tr></thead><tbody>
                @forelse ($buku as $item)
                    <tr><td><strong class="rb-primary-text">{{ $item->judul }}</strong></td><td>{{ $item->kategori?->nama ?? 'Tanpa Kategori' }}</td><td>{{ $item->penulis }}</td><td><span class="rb-count-chip">{{ number_format($item->peminjamans_count, 0, ',', '.') }} kali</span></td></tr>
                @empty
                    <tr><td colspan="4"><div class="rb-empty-state"><strong>Belum ada data buku.</strong><span>Data koleksi akan ditampilkan setelah buku ditambahkan.</span></div></td></tr>
                @endforelse
            </tbody></table></div>
            <div class="rb-pagination">{{ $buku->links() }}</div>
        </section>
    </div>
</x-app-layout>
