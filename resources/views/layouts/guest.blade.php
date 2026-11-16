<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'RuangBaca · Perpustakaan Digital')</title>
    <meta name="theme-color" content="#102a43">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="app-body">
    <main class="auth-shell">
        <a href="{{ route('home') }}" class="app-brand auth-brand">
            <span class="app-brand-mark">RB</span>
            <span class="app-brand-copy"><strong>RuangBaca</strong><small>Perpustakaan Digital</small></span>
        </a>
        <section class="auth-card">
            {{ $slot }}
        </section>
        <a href="{{ route('home') }}" class="auth-back">Kembali ke Beranda</a>
    </main>
</body>
</html>
