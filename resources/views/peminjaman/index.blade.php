<x-app-layout>
    <x-slot name="header"><x-page-heading eyebrow="SIRKULASI / TRANSAKSI" title="Pengelolaan Peminjaman" description="Periksa Pengajuan, berikan keputusan, dan catat pengembalian buku." /></x-slot>
    <div class="rb-content">
        <section class="rb-card">
            <div class="rb-card-heading"><div><span class="eyebrow">DATA SIRKULASI</span><h2>Daftar Transaksi Peminjaman</h2><p>Gunakan kolom tindakan untuk memproses pengajuan dan pengembalian.</p></div><span class="rb-count-chip">{{ number_format($peminjamans->total(), 0, ',', '.') }} transaksi</span></div>
            <div class="rb-table-wrap"><table class="rb-table"><thead><tr><th>Peminjam</th><th>Judul Buku</th><th>Tanggal Peminjaman</th><th>Tanggal Pengembalian</th><th>Status</th><th>Tindakan</th></tr></thead><tbody>
                @forelse ($peminjamans as $item)
                    <tr>
                        <td><strong class="rb-primary-text">{{ $item->anggota?->nama ?? $item->user?->name ?? 'Peminjam tidak tersedia' }}</strong><small class="rb-cell-subtext">{{ $item->user?->email ?? $item->anggota?->email ?? 'Alamat email tidak tersedia' }}</small></td>
                        <td>{{ $item->buku?->judul ?? 'Buku tidak tersedia' }}</td>
                        <td>{{ $item->tanggal_pinjam?->translatedFormat('d F Y') ?? 'Belum dicatat' }}</td>
                        <td>{{ $item->tanggal_kembali?->translatedFormat('d F Y') ?? 'Belum dikembalikan' }}</td>
                        <td><x-status-badge :status="$item->status ?? 'menunggu'" /></td>
                        <td><div class="rb-row-actions">
                            @if (($item->status ?? 'menunggu') === 'menunggu' && auth()->user()->can('memproses persetujuan peminjaman'))
                                <form method="POST" action="{{ route('peminjaman.approve', $item) }}">@csrf<button class="button small" type="submit">Setujui</button></form>
                                <form method="POST" action="{{ route('peminjaman.reject', $item) }}" onsubmit="return confirm('Apakah Anda yakin ingin menolak pengajuan peminjaman ini?')">@csrf<button class="button danger small" type="submit">Tolak</button></form>
                            @endif
                            @if (($item->status ?? '') === 'disetujui' && auth()->user()->can('mencatat pengembalian buku'))
                                <form method="POST" action="{{ route('peminjaman.return', $item) }}" onsubmit="return confirm('Apakah Anda yakin ingin mencatat bahwa buku ini telah dikembalikan?')">@csrf<button class="button secondary small" type="submit">Catat Pengembalian</button></form>
                            @endif
                            @if (($item->status ?? '') !== 'menunggu' && ($item->status ?? '') !== 'disetujui')
                                <span class="rb-muted-text">Tidak ada tindakan</span>
                            @endif
                        </div></td>
                    </tr>
                @empty
                    <tr><td colspan="6"><div class="rb-empty-state"><strong>Belum ada transaksi peminjaman.</strong><span>Pengajuan peminjaman yang masuk akan muncul di sini.</span></div></td></tr>
                @endforelse
            </tbody></table></div>
            <div class="rb-pagination">{{ $peminjamans->links() }}</div>
        </section>
    </div>
</x-app-layout>
