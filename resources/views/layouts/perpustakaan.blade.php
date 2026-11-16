<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('judul', 'RuangBaca · Perpustakaan Digital')</title>
    <meta name="theme-color" content="#f5f7fb">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="app-body">
    @include('layouts.navigation')
    <main class="app-main custom-main">
        <x-flash-messages />
        @yield('konten')
    </main>
    <footer class="app-footer"><span><strong>RuangBaca</strong> · Perpustakaan Digital</span><span>© {{ date('Y') }} RuangBaca. Seluruh hak cipta dilindungi.</span></footer>
    </div>
</div>
@stack('scripts')
</body>
</html>
