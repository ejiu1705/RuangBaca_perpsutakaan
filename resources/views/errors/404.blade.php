<x-guest-layout>
    <div class="auth-heading">
        <span class="auth-eyebrow">RUANGBACA · 404</span>
        <h1>Halaman Tidak Ditemukan</h1>
        <p>Halaman yang Anda cari tidak tersedia. Periksa alamat halaman atau kembali ke beranda.</p>
    </div>
    <div class="rb-form-stack">
        @auth
            <a href="{{ route('dashboard') }}" class="button auth-submit">Kembali ke Dashboard</a>
        @else
            <a href="{{ route('home') }}" class="button auth-submit">Kembali ke Beranda</a>
        @endauth
    </div>
</x-guest-layout>
