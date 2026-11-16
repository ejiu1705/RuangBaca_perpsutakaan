<x-app-layout>
    <x-slot name="header">
        <x-page-heading eyebrow="SIRKULASI / DETAIL PEMINJAMAN" :title="'Detail Peminjaman #' . $peminjaman->id" description="Informasi transaksi peminjaman dan pengembalian buku." />
    </x-slot>
    <div class="rb-content">
        <section class="rb-card rb-detail-card">
            <div class="rb-card-heading"><div><span class="eyebrow">INFORMASI TRANSAKSI</span><h2>Data Peminjaman</h2><p>Rincian transaksi sesuai hak akses akun Anda.</p></div><x-status-badge :status="$peminjaman->status ?? 'menunggu'" /></div>
            <dl class="rb-detail-grid">
                <div class="rb-detail-item"><dt>Nama Peminjam</dt><dd>{{ $peminjaman->anggota?->nama ?? $peminjaman->user?->name ?? 'Tidak tersedia' }}</dd></div>
                <div class="rb-detail-item"><dt>Alamat Email</dt><dd>{{ $peminjaman->anggota?->email ?? $peminjaman->user?->email ?? 'Tidak tersedia' }}</dd></div>
                <div class="rb-detail-item"><dt>Judul Buku</dt><dd>{{ $peminjaman->buku?->judul ?? 'Tidak tersedia' }}</dd></div>
                <div class="rb-detail-item"><dt>Tanggal Peminjaman</dt><dd>{{ $peminjaman->tanggal_pinjam?->translatedFormat('d F Y') ?? 'Belum dicatat' }}</dd></div>
                <div class="rb-detail-item"><dt>Tanggal Pengembalian</dt><dd>{{ $peminjaman->tanggal_kembali?->translatedFormat('d F Y') ?? 'Belum dikembalikan' }}</dd></div>
            </dl>
            <div class="rb-form-actions"><a href="{{ url()->previous() }}" class="button secondary">Kembali</a></div>
        </section>
    </div>
</x-app-layout>
