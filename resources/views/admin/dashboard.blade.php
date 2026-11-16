<x-app-layout>
    <x-slot name="header">
        <div class="page-title-wrap">
            <div><span class="page-title-eyebrow">OVERVIEW / ADMINISTRATOR</span><h1>Dashboard Admin</h1><p>Ringkasan operasional dan kendali sistem perpustakaan.</p></div>
            <span class="page-date-chip"><span></span> {{ now()->format('d M Y') }}</span>
        </div>
    </x-slot>

    <div class="dashboard-content">
        <section class="dashboard-welcome dashboard-welcome-admin">
            <div class="welcome-copy">
                <span class="welcome-kicker"><i></i> RUANG KENDALI ADMINISTRATOR</span>
                <h2>Selamat datang kembali,<br><strong>{{ auth()->user()->name }}.</strong></h2>
                <p>Kelola koleksi, pantau aktivitas peminjaman, dan pastikan seluruh layanan perpustakaan berjalan dengan baik.</p>
                <div class="welcome-actions">
                    <a href="{{ route('buku.index') }}" class="welcome-primary">Kelola koleksi <span>↗</span></a>
                    <a href="{{ route('laporan.index') }}" class="welcome-secondary">Lihat laporan</a>
                </div>
            </div>
            <div class="welcome-art" aria-hidden="true">
                <div class="art-orbit orbit-one"></div><div class="art-orbit orbit-two"></div>
                <div class="art-book book-back"></div><div class="art-book book-front"><span>RB</span><i></i><b></b></div>
                <div class="art-glass-card"><span class="art-card-dot"></span><small>KOLEKSI TERCATAT</small><strong>{{ number_format($stats['buku']) }}</strong><em>Judul buku</em></div>
                <div class="art-spark spark-one">✦</div><div class="art-spark spark-two">✧</div>
            </div>
            <div class="welcome-bottom-note"><span>●</span> Sistem perpustakaan siap digunakan</div>
        </section>

        <section class="metric-grid" aria-label="Statistik perpustakaan">
            <article class="metric-card metric-blue"><div class="metric-head"><span class="metric-icon"><svg viewBox="0 0 24 24"><path d="M5 4.8A2.8 2.8 0 0 1 7.8 2H20v17H7.8A2.8 2.8 0 0 0 5 21.8V4.8Z"/><path d="M5 5v16.5M9 6h7M9 10h7"/></svg></span><span class="metric-caption">TOTAL KOLEKSI</span></div><strong>{{ number_format($stats['buku']) }}</strong><p>Buku terdaftar di katalog</p><a href="{{ route('buku.index') }}">Buka katalog <span>→</span></a></article>
            <article class="metric-card metric-violet"><div class="metric-head"><span class="metric-icon"><svg viewBox="0 0 24 24"><path d="M4 5h16M4 12h16M4 19h16"/><circle cx="8" cy="5" r="1.8"/><circle cx="15" cy="12" r="1.8"/><circle cx="10" cy="19" r="1.8"/></svg></span><span class="metric-caption">KATEGORI</span></div><strong>{{ number_format($stats['kategori']) }}</strong><p>Kelompok koleksi tersedia</p><a href="{{ route('buku.index') }}">Atur melalui buku <span>→</span></a></article>
            <article class="metric-card metric-amber"><div class="metric-head"><span class="metric-icon"><svg viewBox="0 0 24 24"><rect x="3.5" y="5" width="17" height="15.5" rx="2"/><path d="M7.5 3v4M16.5 3v4M3.5 9.5h17M8 13h3M8 16h7"/></svg></span><span class="metric-caption">TOTAL PEMINJAMAN</span></div><strong>{{ number_format($stats['peminjaman']) }}</strong><p>Seluruh transaksi tercatat</p><a href="{{ route('peminjaman.index') }}">Lihat transaksi <span>→</span></a></article>
            <article class="metric-card metric-rose"><div class="metric-head"><span class="metric-icon"><svg viewBox="0 0 24 24"><circle cx="9" cy="8" r="3.2"/><path d="M3.5 20v-1.3a5.5 5.5 0 0 1 11 0V20M17 7.5a3 3 0 0 1 0 5.8M17 15.5a4.5 4.5 0 0 1 3.5 4.4"/></svg></span><span class="metric-caption">PENGGUNA</span></div><strong>{{ number_format($stats['pengguna']) }}</strong><p>Akun terdaftar dalam sistem</p><a href="{{ route('admin.users.index') }}">Kelola pengguna <span>→</span></a></article>
        </section>

        <div class="dashboard-section-heading"><div><span class="section-overline">AKSES CEPAT</span><h2>Semua pekerjaan dalam satu tempat</h2><p>Pilih layanan yang ingin Anda kelola.</p></div><span class="section-heading-note">ADMIN WORKSPACE</span></div>
        <section class="quick-action-grid">
            <a href="{{ route('buku.index') }}" class="quick-action-card"><span class="quick-action-icon quick-blue"><svg viewBox="0 0 24 24"><path d="M5 4.8A2.8 2.8 0 0 1 7.8 2H20v17H7.8A2.8 2.8 0 0 0 5 21.8V4.8Z"/><path d="M5 5v16.5M9 6h7M9 10h7"/></svg></span><span class="quick-action-copy"><strong>Kelola Buku</strong><small>Tambah, ubah, dan telusuri katalog</small></span><span class="quick-action-arrow">↗</span></a>
            <a href="{{ route('peminjaman.index') }}" class="quick-action-card"><span class="quick-action-icon quick-green"><svg viewBox="0 0 24 24"><path d="M4 5.5h16v14H4z"/><path d="M8 3.5v4M16 3.5v4M4 9h16M8 13h3M8 16h7"/></svg></span><span class="quick-action-copy"><strong>Peminjaman</strong><small>Persetujuan dan pengembalian buku</small></span><span class="quick-action-arrow">↗</span>@if($stats['menunggu'] > 0)<span class="quick-action-badge">{{ $stats['menunggu'] }} menunggu</span>@endif</a>
            <a href="{{ route('admin.users.index') }}" class="quick-action-card"><span class="quick-action-icon quick-purple"><svg viewBox="0 0 24 24"><circle cx="9" cy="8" r="3.2"/><path d="M3.5 20v-1.3a5.5 5.5 0 0 1 11 0V20M17 7.5a3 3 0 0 1 0 5.8M17 15.5a4.5 4.5 0 0 1 3.5 4.4"/></svg></span><span class="quick-action-copy"><strong>Pengguna</strong><small>Kelola akun dan hak akses</small></span><span class="quick-action-arrow">↗</span></a>
            <a href="{{ route('laporan.index') }}" class="quick-action-card"><span class="quick-action-icon quick-orange"><svg viewBox="0 0 24 24"><path d="M4 20V10M10 20V4M16 20v-7M22 20V7"/><path d="M2.5 20.5h20"/></svg></span><span class="quick-action-copy"><strong>Laporan</strong><small>Lihat rekap koleksi dan peminjaman</small></span><span class="quick-action-arrow">↗</span></a>
        </section>

        <section class="dashboard-bottom-grid">
            <div class="dashboard-panel activity-panel">
                <div class="panel-heading"><div><span class="section-overline">AKTIVITAS TERKINI</span><h2>Peminjaman terbaru</h2><p>Transaksi terakhir yang tercatat dalam sistem.</p></div><a href="{{ route('peminjaman.index') }}" class="panel-link">Lihat semua <span>→</span></a></div>
                <div class="activity-table-wrap"><table class="activity-table"><thead><tr><th>Peminjam</th><th>Buku</th><th>Tanggal</th><th>Status</th></tr></thead><tbody>
                @forelse($recentPeminjamans as $item)
                    @php $status = $item->status ?? 'menunggu'; $namaPeminjam = $item->anggota?->nama ?? $item->user?->name ?? 'Pengguna'; @endphp
                    <tr><td><div class="activity-person"><span class="activity-avatar">{{ strtoupper(substr($namaPeminjam, 0, 1)) }}</span><span><strong>{{ $namaPeminjam }}</strong><small>{{ $item->user?->email ?? $item->anggota?->email ?? 'Anggota perpustakaan' }}</small></span></div></td><td class="activity-book">{{ $item->buku?->judul ?? 'Buku tidak tersedia' }}</td><td>{{ $item->tanggal_pinjam?->format('d M Y') ?? $item->created_at?->format('d M Y') }}</td><td><span class="status-pill status-{{ $status }}">{{ ucfirst($status) }}</span></td></tr>
                @empty
                    <tr><td colspan="4"><div class="empty-activity"><span>◷</span><strong>Belum ada aktivitas peminjaman</strong><small>Transaksi yang masuk akan tampil di bagian ini.</small></div></td></tr>
                @endforelse
                </tbody></table></div>
            </div>
            <aside class="dashboard-panel system-panel"><div class="panel-heading"><div><span class="section-overline">INFORMASI SISTEM</span><h2>Status layanan</h2><p>Kondisi modul utama perpustakaan.</p></div></div><div class="system-status-list"><div><span class="system-status-icon"><svg viewBox="0 0 24 24"><path d="M5 4.8A2.8 2.8 0 0 1 7.8 2H20v17H7.8A2.8 2.8 0 0 0 5 21.8V4.8Z"/><path d="M5 5v16.5"/></svg></span><span><strong>Katalog buku</strong><small>Pengelolaan koleksi</small></span><i>Aktif</i></div><div><span class="system-status-icon"><svg viewBox="0 0 24 24"><rect x="3.5" y="5" width="17" height="15.5" rx="2"/><path d="M7.5 3v4M16.5 3v4M3.5 9.5h17"/></svg></span><span><strong>Sirkulasi</strong><small>Peminjaman dan pengembalian</small></span><i>Aktif</i></div><div><span class="system-status-icon"><svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="8.5"/><path d="m8.5 12 2.2 2.2 4.8-5"/></svg></span><span><strong>Hak akses</strong><small>Kontrol berdasarkan role</small></span><i>Aktif</i></div></div><div class="system-panel-footer"><span></span> Seluruh menu mengikuti hak akses akun Anda.</div></aside>
        </section>
    </div>
</x-app-layout>
