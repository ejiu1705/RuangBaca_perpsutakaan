<x-guest-layout>
    <div class="auth-heading">
        <span class="auth-eyebrow">RUANGBACA · 500</span>
        <h1>Terjadi Gangguan Sistem</h1>
        <p>Permintaan Anda belum dapat diproses. Silakan coba kembali beberapa saat lagi.</p>
    </div>
    <div class="rb-form-stack">
        @auth
            <a href="{{ route('dashboard') }}" class="button auth-submit">Kembali ke Dashboard</a>
        @else
            <a href="{{ route('home') }}" class="button auth-submit">Kembali ke Beranda</a>
        @endauth
    </div>
</x-guest-layout>
