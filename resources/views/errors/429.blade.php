<x-guest-layout>
    <div class="auth-heading">
        <span class="auth-eyebrow">RUANGBACA · 429</span>
        <h1>Terlalu Banyak Permintaan</h1>
        <p>Anda mengirim terlalu banyak permintaan dalam waktu singkat. Tunggu beberapa saat sebelum mencoba kembali.</p>
    </div>
    <div class="rb-form-stack">
        @auth
            <a href="{{ route('dashboard') }}" class="button auth-submit">Kembali ke Dashboard</a>
        @else
            <a href="{{ route('home') }}" class="button auth-submit">Kembali ke Beranda</a>
        @endauth
    </div>
</x-guest-layout>
