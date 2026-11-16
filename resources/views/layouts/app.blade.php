<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'RuangBaca · Perpustakaan Digital')</title>
    <meta name="theme-color" content="#f5f7fb">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="app-body">
    @include('layouts.navigation')
    @isset($header)
        <div class="app-page-head"><div class="app-page-head-inner">{{ $header }}</div></div>
    @endisset
    <main class="app-main"><x-flash-messages />{{ $slot }}</main>
    <footer class="app-footer"><span><strong>RuangBaca</strong> · Perpustakaan Digital</span><span>© {{ date('Y') }} RuangBaca. Seluruh hak cipta dilindungi.</span></footer>
    </div>
</div>
@stack('scripts')
</body>
</html>
