<x-guest-layout>
    <div class="auth-heading">
        <span class="auth-eyebrow">RUANGBACA · 419</span>
        <h1>Sesi Berakhir</h1>
        <p>Sesi Anda telah berakhir. Muat ulang halaman, lalu coba kembali.</p>
    </div>
    <div class="rb-form-stack">
        @auth
            <a href="{{ route('dashboard') }}" class="button auth-submit">Kembali ke Dashboard</a>
        @else
            <a href="{{ route('home') }}" class="button auth-submit">Kembali ke Beranda</a>
        @endauth
    </div>
</x-guest-layout>
