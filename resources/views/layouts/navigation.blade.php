@php
    $currentRole = auth()->user()?->getRoleNames()->first() ?? 'pengunjung';
    $currentPage = match (true) {
        request()->routeIs('admin.dashboard', 'petugas.dashboard', 'siswa.dashboard', 'dashboard') => 'Dashboard',
        request()->routeIs('buku.*') => 'Katalog Buku',
        request()->routeIs('peminjaman.*') => 'Peminjaman',
        request()->routeIs('siswa.riwayat') => 'Riwayat Peminjaman',
        request()->routeIs('admin.users.*') => 'Pengguna',
        request()->routeIs('laporan.*') => 'Laporan',
        request()->routeIs('profile.*') => 'Pengaturan Profil',
        default => 'Perpustakaan Digital',
    };
@endphp

<div class="app-shell" x-data="{ sidebarOpen: false }">
    <div class="sidebar-scrim" x-cloak x-show="sidebarOpen" x-transition.opacity @click="sidebarOpen = false"></div>

    <aside class="app-sidebar" :class="{ 'is-open': sidebarOpen }" aria-label="Navigasi utama">
        <a href="{{ auth()->check() ? route('dashboard') : route('home') }}" class="sidebar-brand">
            <span class="sidebar-brand-mark">
                <svg viewBox="0 0 32 32" aria-hidden="true"><path d="M5.5 7.8c4.1-.9 7.7-.2 10.5 2.1 2.8-2.3 6.4-3 10.5-2.1v16.1c-4.1-.9-7.7-.2-10.5 2.1-2.8-2.3-6.4-3-10.5-2.1V7.8Z"/><path d="M16 10v16"/></svg>
            </span>
            <span class="sidebar-brand-copy"><strong>RuangBaca</strong><small>LIBRARY MANAGEMENT</small></span>
        </a>

        <div class="sidebar-workspace" role="note" aria-label="Workspace aktif: Perpustakaan utama">
            <span class="workspace-indicator" aria-hidden="true"></span>
            <span class="sidebar-workspace-copy">
                <strong>Workspace</strong>
                <small>Perpustakaan utama</small>
            </span>
        </div>

        @auth
            <div class="sidebar-menu-label">MENU UTAMA</div>
            <nav class="sidebar-menu">
                <a href="{{ route('dashboard') }}" class="sidebar-link {{ request()->routeIs('dashboard', 'admin.dashboard', 'petugas.dashboard', 'siswa.dashboard') ? 'is-active' : '' }}">
                    <span class="sidebar-link-icon"><svg viewBox="0 0 24 24" aria-hidden="true"><rect x="3.5" y="3.5" width="7" height="7" rx="1.5"/><rect x="13.5" y="3.5" width="7" height="5" rx="1.5"/><rect x="13.5" y="11.5" width="7" height="9" rx="1.5"/><rect x="3.5" y="13.5" width="7" height="7" rx="1.5"/></svg></span>
                    <span>Dashboard</span><i class="sidebar-active-dot"></i>
                </a>
                @can('lihat daftar buku')
                    <a href="{{ route('buku.index') }}" class="sidebar-link {{ request()->routeIs('buku.*') ? 'is-active' : '' }}">
                        <span class="sidebar-link-icon"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M5 4.8A2.8 2.8 0 0 1 7.8 2H20v17H7.8A2.8 2.8 0 0 0 5 21.8V4.8Z"/><path d="M5 5v16.5M9 6h7M9 10h7"/></svg></span><span>Katalog Buku</span>
                    </a>
                @endcan
            </nav>

            <div class="sidebar-menu-label">SIRKULASI</div>
            <nav class="sidebar-menu">
                @can('kelola peminjaman')
                    <a href="{{ route('peminjaman.index') }}" class="sidebar-link {{ request()->routeIs('peminjaman.index') ? 'is-active' : '' }}">
                        <span class="sidebar-link-icon"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 5.5h16v14H4z"/><path d="M8 3.5v4M16 3.5v4M4 9h16M8 13h3M8 16h7"/></svg></span><span>Peminjaman</span>
                    </a>
                @endcan
                @can('melihat riwayat peminjaman sendiri')
                    <a href="{{ route('siswa.riwayat') }}" class="sidebar-link {{ request()->routeIs('siswa.riwayat') ? 'is-active' : '' }}">
                        <span class="sidebar-link-icon"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 5v5h5M4.8 9A8 8 0 1 1 4 14"/><path d="M12 7v5l3 2"/></svg></span><span>Riwayat Saya</span>
                    </a>
                @endcan
            </nav>

            @if (auth()->user()->can('kelola data pengguna') || auth()->user()->can('lihat laporan perpustakaan'))
                <div class="sidebar-menu-label">ADMINISTRASI</div>
                <nav class="sidebar-menu">
                    @can('kelola data pengguna')
                        <a href="{{ route('admin.users.index') }}" class="sidebar-link {{ request()->routeIs('admin.users.*') ? 'is-active' : '' }}">
                            <span class="sidebar-link-icon"><svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="9" cy="8" r="3.2"/><path d="M3.5 20v-1.3a5.5 5.5 0 0 1 11 0V20M17 7.5a3 3 0 0 1 0 5.8M17 15.5a4.5 4.5 0 0 1 3.5 4.4"/></svg></span><span>Pengguna</span>
                        </a>
                    @endcan
                    @can('lihat laporan perpustakaan')
                        <a href="{{ route('laporan.index') }}" class="sidebar-link {{ request()->routeIs('laporan.*') ? 'is-active' : '' }}">
                            <span class="sidebar-link-icon"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 20V10M10 20V4M16 20v-7M22 20V7"/><path d="M2.5 20.5h20"/></svg></span><span>Laporan</span>
                        </a>
                    @endcan
                </nav>
            @endif
        @else
            <div class="sidebar-menu-label">MULAI DI SINI</div>
            <nav class="sidebar-menu"><a href="{{ route('login') }}" class="sidebar-link"><span class="sidebar-link-icon"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M10 4H5a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h5M14 16l4-4-4-4M18 12H9"/></svg></span><span>Masuk</span></a></nav>
        @endauth

        <div class="sidebar-spacer"></div>
        <div class="sidebar-help-card">
            <span class="help-card-icon"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 3.5a8.5 8.5 0 1 0 8.5 8.5A8.5 8.5 0 0 0 12 3.5Z"/><path d="M9.7 9a2.4 2.4 0 1 1 4.2 1.6c-1.1 1.1-1.9 1.4-1.9 3M12 17.4v.1"/></svg></span>
            <strong>Butuh bantuan?</strong>
            <small>Gunakan menu di samping untuk mengelola aktivitas perpustakaan.</small>
        </div>

        @auth
            <div class="sidebar-user">
                <a href="{{ route('profile.edit') }}" class="sidebar-user-link">
                    <span class="sidebar-user-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
                    <span class="sidebar-user-copy"><strong>{{ auth()->user()->name }}</strong><small>{{ ucfirst($currentRole) }}</small></span>
                </a>
                <form method="POST" action="{{ route('logout') }}" class="sidebar-logout-form">
                    @csrf
                    <button type="submit" aria-label="Keluar dari akun" title="Keluar"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M10 4H5a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h5M14 16l4-4-4-4M18 12H9"/></svg></button>
                </form>
            </div>
        @endauth
    </aside>

    <div class="app-main-area">
        <header class="app-topbar">
            <div class="topbar-left">
                <button type="button" class="sidebar-toggle" @click="sidebarOpen = !sidebarOpen" :aria-expanded="sidebarOpen.toString()" aria-label="Buka navigasi"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 6h16M4 12h16M4 18h16"/></svg></button>
                <div class="topbar-heading"><small>PERPUSTAKAAN DIGITAL</small><strong>{{ $currentPage }}</strong></div>
            </div>
            <div class="topbar-right">
                <span class="topbar-live"><i></i> Sistem aktif</span>
                <span class="topbar-date"><svg viewBox="0 0 24 24" aria-hidden="true"><rect x="3.5" y="5" width="17" height="15.5" rx="2"/><path d="M7.5 3v4M16.5 3v4M3.5 9.5h17"/></svg>{{ now()->format('d M Y') }}</span>
                @auth
                    <a class="topbar-profile" href="{{ route('profile.edit') }}"><span class="topbar-profile-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span><span><strong>{{ auth()->user()->name }}</strong><small>{{ ucfirst($currentRole) }}</small></span><svg class="topbar-profile-chevron" viewBox="0 0 20 20" aria-hidden="true"><path d="m6 8 4 4 4-4"/></svg></a>
                @endauth
            </div>
        </header>
