<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('judul', 'Perpustakaan Digital')</title>
    <meta name="theme-color" content="#f5f7fb">
    @vite(['resources/css/app.css'])
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @stack('styles')
</head>
<body class="app-body">
    @include('layouts.navigation')
    <main class="app-main custom-main">
        @if (session('status')) <div class="app-alert success" role="status">{{ session('status') }}</div> @endif
        @if (session('success')) <div class="app-alert success" role="status">{{ session('success') }}</div> @endif
        @if ($errors->any()) <div class="app-alert error" role="alert">{{ $errors->first() }}</div> @endif
        @yield('konten')
    </main>
    <footer class="app-footer"><span><strong>RuangBaca</strong> · Perpustakaan Digital</span><span>© {{ date('Y') }} Sistem Informasi Perpustakaan</span></footer>
    </div>
</div>
@stack('scripts')
<script defer src="{{ asset('js/alpine.min.js') }}"></script>
</body>
</html>
