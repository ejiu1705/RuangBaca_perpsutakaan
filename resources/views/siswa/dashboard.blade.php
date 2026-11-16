<x-app-layout>
    <x-slot name="header">
        <div class="page-title-wrap"><div><span class="page-title-eyebrow">OVERVIEW / MEMBER AREA</span><h1>Dashboard Siswa</h1><p>Temukan buku dan pantau peminjaman Anda dengan mudah.</p></div><span class="page-date-chip"><span></span> {{ now()->format('d M Y') }}</span></div>
    </x-slot>
    <div class="dashboard-content">
        <section class="dashboard-welcome dashboard-welcome-student">
            <div class="welcome-copy"><span class="welcome-kicker"><i></i> PERSONAL READING SPACE</span><h2>Hai, {{ auth()->user()->name }}.<br><strong>Waktunya membaca hal baru.</strong></h2><p>Jelajahi koleksi perpustakaan, ajukan peminjaman, dan cek status buku yang sedang Anda pinjam.</p><div class="welcome-actions"><a href="{{ route('buku.index') }}" class="welcome-primary">Jelajahi buku <span>↗</span></a><a href="{{ route('siswa.riwayat') }}" class="welcome-secondary">Riwayat saya</a></div></div>
            <div class="welcome-art student-art" aria-hidden="true"><div class="art-orbit orbit-one"></div><div class="art-orbit orbit-two"></div><div class="art-book book-back"></div><div class="art-book book-front"><span>READ</span><i></i><b></b></div><div class="art-glass-card"><span class="art-card-dot"></span><small>BUKU TERSEDIA</small><strong>{{ number_format($stats['buku']) }}</strong><em>Koleksi perpustakaan</em></div><div class="art-spark spark-one">✦</div><div class="art-spark spark-two">✧</div></div>
            <div class="welcome-bottom-note"><span>●</span> Selamat datang di ruang baca digital Anda</div>
        </section>
        <section class="metric-grid metric-grid-three">
            <article class="metric-card metric-blue"><div class="metric-head"><span class="metric-icon"><svg viewBox="0 0 24 24"><path d="M5 4.8A2.8 2.8 0 0 1 7.8 2H20v17H7.8A2.8 2.8 0 0 0 5 21.8V4.8Z"/><path d="M5 5v16.5M9 6h7"/></svg></span><span class="metric-caption">KOLEKSI BUKU</span></div><strong>{{ number_format($stats['buku']) }}</strong><p>Buku yang dapat ditelusuri</p><a href="{{ route('buku.index') }}">Jelajahi koleksi <span>→</span></a></article>
            <article class="metric-card metric-amber"><div class="metric-head"><span class="metric-icon"><svg viewBox="0 0 24 24"><path d="M4 5.5h16v14H4z"/><path d="M8 3.5v4M16 3.5v4M4 9h16M8 13h3M8 16h7"/></svg></span><span class="metric-caption">PENGAJUAN MENUNGGU</span></div><strong>{{ number_format($stats['menunggu']) }}</strong><p>Menunggu konfirmasi petugas</p><a href="{{ route('siswa.riwayat') }}">Cek status <span>→</span></a></article>
            <article class="metric-card metric-green"><div class="metric-head"><span class="metric-icon"><svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="8.5"/><path d="m8.5 12 2.2 2.2 4.8-5"/></svg></span><span class="metric-caption">DISETUJUI</span></div><strong>{{ number_format($stats['disetujui']) }}</strong><p>Peminjaman yang sedang aktif</p><a href="{{ route('siswa.riwayat') }}">Lihat riwayat <span>→</span></a></article>
        </section>
        <div class="dashboard-section-heading"><div><span class="section-overline">PINTASAN</span><h2>Apa yang ingin Anda lakukan?</h2><p>Akses kebutuhan membaca tanpa langkah yang berbelit.</p></div><span class="section-heading-note">MEMBER WORKSPACE</span></div>
        <section class="quick-action-grid quick-action-grid-two">
            <a href="{{ route('buku.index') }}" class="quick-action-card"><span class="quick-action-icon quick-blue"><svg viewBox="0 0 24 24"><path d="M5 4.8A2.8 2.8 0 0 1 7.8 2H20v17H7.8A2.8 2.8 0 0 0 5 21.8V4.8Z"/><path d="M5 5v16.5M9 6h7"/></svg></span><span class="quick-action-copy"><strong>Temukan Buku</strong><small>Cari judul, penulis, dan kategori yang Anda sukai</small></span><span class="quick-action-arrow">↗</span></a>
            <a href="{{ route('siswa.riwayat') }}" class="quick-action-card"><span class="quick-action-icon quick-green"><svg viewBox="0 0 24 24"><path d="M4 5v5h5M4.8 9A8 8 0 1 1 4 14"/><path d="M12 7v5l3 2"/></svg></span><span class="quick-action-copy"><strong>Riwayat Peminjaman</strong><small>Pantau pengajuan dan status pengembalian buku</small></span><span class="quick-action-arrow">↗</span></a>
        </section>
        <section class="dashboard-panel activity-panel student-activity-panel"><div class="panel-heading"><div><span class="section-overline">AKTIVITAS SAYA</span><h2>Peminjaman terakhir</h2><p>Riwayat aktivitas dari akun yang sedang digunakan.</p></div><a href="{{ route('siswa.riwayat') }}" class="panel-link">Lihat riwayat <span>→</span></a></div><div class="activity-table-wrap"><table class="activity-table"><thead><tr><th>Buku</th><th>Tanggal pinjam</th><th>Tanggal kembali</th><th>Status</th></tr></thead><tbody>
        @forelse($recentPeminjamans as $item)
            @php $status = $item->status ?? 'menunggu'; @endphp
            <tr><td><div class="activity-person"><span class="activity-avatar book-avatar"><svg viewBox="0 0 24 24"><path d="M5 4.8A2.8 2.8 0 0 1 7.8 2H20v17H7.8A2.8 2.8 0 0 0 5 21.8V4.8Z"/></svg></span><span><strong>{{ $item->buku?->judul ?? 'Buku tidak tersedia' }}</strong><small>{{ $item->buku?->penulis ?? 'Koleksi perpustakaan' }}</small></span></div></td><td>{{ $item->tanggal_pinjam?->format('d M Y') ?? '-' }}</td><td>{{ $item->tanggal_kembali?->format('d M Y') ?? 'Belum dikembalikan' }}</td><td><span class="status-pill status-{{ $status }}">{{ ucfirst($status) }}</span></td></tr>
        @empty
            <tr><td colspan="4"><div class="empty-activity"><span>◷</span><strong>Belum ada riwayat peminjaman</strong><small>Temukan buku yang menarik, lalu ajukan peminjaman pertama Anda.</small><a href="{{ route('buku.index') }}">Jelajahi katalog <span>→</span></a></div></td></tr>
        @endforelse
        </tbody></table></div></section>
    </div>
</x-app-layout>
