<x-app-layout>
    <x-slot name="header">
        <div class="page-title-wrap"><div><span class="page-title-eyebrow">OVERVIEW / LIBRARY STAFF</span><h1>Dashboard Petugas</h1><p>Pantau proses layanan dan aktivitas sirkulasi perpustakaan.</p></div><span class="page-date-chip"><span></span> {{ now()->format('d M Y') }}</span></div>
    </x-slot>
    <div class="dashboard-content">
        <section class="dashboard-welcome dashboard-welcome-staff">
            <div class="welcome-copy"><span class="welcome-kicker"><i></i> RUANG KERJA PETUGAS</span><h2>Halo, {{ auth()->user()->name }}.<br><strong>Siap melayani hari ini?</strong></h2><p>Kelola data buku dan proses peminjaman dengan alur yang mudah dipantau dalam satu dashboard.</p><div class="welcome-actions"><a href="{{ route('peminjaman.index') }}" class="welcome-primary">Proses peminjaman <span>↗</span></a><a href="{{ route('buku.index') }}" class="welcome-secondary">Buka katalog</a></div></div>
            <div class="welcome-art staff-art" aria-hidden="true"><div class="art-orbit orbit-one"></div><div class="art-orbit orbit-two"></div><div class="art-book book-back"></div><div class="art-book book-front"><span>STAFF</span><i></i><b></b></div><div class="art-glass-card"><span class="art-card-dot"></span><small>MENUNGGU PROSES</small><strong>{{ number_format($stats['menunggu']) }}</strong><em>Pengajuan aktif</em></div><div class="art-spark spark-one">✦</div></div>
            <div class="welcome-bottom-note"><span>●</span> Semua proses tercatat dalam sistem</div>
        </section>
        <section class="metric-grid metric-grid-three">
            <article class="metric-card metric-blue"><div class="metric-head"><span class="metric-icon"><svg viewBox="0 0 24 24"><path d="M5 4.8A2.8 2.8 0 0 1 7.8 2H20v17H7.8A2.8 2.8 0 0 0 5 21.8V4.8Z"/><path d="M5 5v16.5M9 6h7"/></svg></span><span class="metric-caption">TOTAL KOLEKSI</span></div><strong>{{ number_format($stats['buku']) }}</strong><p>Buku tersedia di katalog</p><a href="{{ route('buku.index') }}">Lihat katalog <span>→</span></a></article>
            <article class="metric-card metric-amber"><div class="metric-head"><span class="metric-icon"><svg viewBox="0 0 24 24"><rect x="3.5" y="5" width="17" height="15.5" rx="2"/><path d="M7.5 3v4M16.5 3v4M3.5 9.5h17"/></svg></span><span class="metric-caption">MENUNGGU PERSETUJUAN</span></div><strong>{{ number_format($stats['menunggu']) }}</strong><p>Pengajuan perlu diperiksa</p><a href="{{ route('peminjaman.index') }}">Periksa pengajuan <span>→</span></a></article>
            <article class="metric-card metric-green"><div class="metric-head"><span class="metric-icon"><svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="8.5"/><path d="m8.5 12 2.2 2.2 4.8-5"/></svg></span><span class="metric-caption">SEDANG DIPINJAM</span></div><strong>{{ number_format($stats['disetujui']) }}</strong><p>Peminjaman telah disetujui</p><a href="{{ route('peminjaman.index') }}">Lihat peminjaman <span>→</span></a></article>
        </section>
        <div class="dashboard-section-heading"><div><span class="section-overline">AKSES CEPAT</span><h2>Mulai dari pekerjaan utama</h2><p>Pintasan ke layanan yang sering digunakan petugas.</p></div><span class="section-heading-note">STAFF WORKSPACE</span></div>
        <section class="quick-action-grid quick-action-grid-three">
            <a href="{{ route('peminjaman.index') }}" class="quick-action-card"><span class="quick-action-icon quick-green"><svg viewBox="0 0 24 24"><rect x="3.5" y="5" width="17" height="15.5" rx="2"/><path d="M7.5 3v4M16.5 3v4M3.5 9.5h17"/></svg></span><span class="quick-action-copy"><strong>Kelola Peminjaman</strong><small>Setujui, tolak, dan catat pengembalian</small></span><span class="quick-action-arrow">↗</span></a>
            <a href="{{ route('buku.index') }}" class="quick-action-card"><span class="quick-action-icon quick-blue"><svg viewBox="0 0 24 24"><path d="M5 4.8A2.8 2.8 0 0 1 7.8 2H20v17H7.8A2.8 2.8 0 0 0 5 21.8V4.8Z"/><path d="M5 5v16.5M9 6h7"/></svg></span><span class="quick-action-copy"><strong>Kelola Katalog</strong><small>Perbarui data buku dan kategori</small></span><span class="quick-action-arrow">↗</span></a>
            <a href="{{ route('laporan.index') }}" class="quick-action-card"><span class="quick-action-icon quick-orange"><svg viewBox="0 0 24 24"><path d="M4 20V10M10 20V4M16 20v-7M22 20V7"/><path d="M2.5 20.5h20"/></svg></span><span class="quick-action-copy"><strong>Laporan</strong><small>Periksa rekap koleksi dan transaksi</small></span><span class="quick-action-arrow">↗</span></a>
        </section>
        <section class="dashboard-panel activity-panel staff-activity-panel"><div class="panel-heading"><div><span class="section-overline">AKTIVITAS TERKINI</span><h2>Peminjaman terbaru</h2><p>Daftar pengajuan dan transaksi terakhir.</p></div><a href="{{ route('peminjaman.index') }}" class="panel-link">Buka peminjaman <span>→</span></a></div><div class="activity-table-wrap"><table class="activity-table"><thead><tr><th>Peminjam</th><th>Buku</th><th>Tanggal</th><th>Status</th></tr></thead><tbody>
        @forelse($recentPeminjamans as $item)
            @php $status = $item->status ?? 'menunggu'; $namaPeminjam = $item->anggota?->nama ?? $item->user?->name ?? 'Pengguna'; @endphp
            <tr><td><div class="activity-person"><span class="activity-avatar">{{ strtoupper(substr($namaPeminjam, 0, 1)) }}</span><span><strong>{{ $namaPeminjam }}</strong><small>{{ $item->user?->email ?? $item->anggota?->email ?? 'Anggota perpustakaan' }}</small></span></div></td><td class="activity-book">{{ $item->buku?->judul ?? 'Buku tidak tersedia' }}</td><td>{{ $item->tanggal_pinjam?->format('d M Y') ?? $item->created_at?->format('d M Y') }}</td><td><span class="status-pill status-{{ $status }}">{{ ucfirst($status) }}</span></td></tr>
        @empty
            <tr><td colspan="4"><div class="empty-activity"><span>◷</span><strong>Belum ada aktivitas peminjaman</strong><small>Pengajuan yang masuk akan tampil di sini.</small></div></td></tr>
        @endforelse
        </tbody></table></div></section>
    </div>
</x-app-layout>
