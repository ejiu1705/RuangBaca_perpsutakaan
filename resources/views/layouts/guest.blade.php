<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Perpustakaan Digital') }}</title>
    <meta name="theme-color" content="#102a43">
    @vite(['resources/css/app.css'])
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body class="app-body">
    <main class="auth-shell">
        <a href="{{ route('home') }}" class="app-brand auth-brand">
            <span class="app-brand-mark">PD</span>
            <span class="app-brand-copy"><strong>Perpustakaan</strong><small>Digital Library</small></span>
        </a>
        <section class="auth-card">
            {{ $slot }}
        </section>
        <a href="{{ route('home') }}" class="auth-back">Kembali ke halaman utama</a>
    </main>
    <script defer src="{{ asset('js/alpine.min.js') }}"></script>
</body>
</html>
