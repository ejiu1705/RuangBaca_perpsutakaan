<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name', 'Perpustakaan Digital'))</title>
    <meta name="theme-color" content="#f5f7fb">
    @vite(['resources/css/app.css'])
    <link rel="stylesheet" href="{{ asset('css/app.css') }}?v=20261116-contrast">
    @stack('styles')
</head>
<body class="app-body">
    @include('layouts.navigation')
    @isset($header)
        <div class="app-page-head"><div class="app-page-head-inner">{{ $header }}</div></div>
    @endisset
    <main class="app-main">{{ $slot }}</main>
    <footer class="app-footer"><span><strong>RuangBaca</strong> · Perpustakaan Digital</span><span>© {{ date('Y') }} Sistem Informasi Perpustakaan</span></footer>
    </div>
</div>
<script defer src="{{ asset('js/alpine.min.js') }}"></script>
</body>
</html>
