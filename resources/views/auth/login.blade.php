<x-guest-layout>
<div class="auth-heading"><span class="auth-eyebrow">SELAMAT DATANG KEMBALI</span><h1>Masuk ke RuangBaca</h1><p>Gunakan akun Anda untuk mengakses layanan perpustakaan digital.</p><span class="auth-nim">Identitas Praktikum · NIM 250441100045</span></div>
<x-auth-session-status class="mb-4" :status="session('status')" />
@if ($errors->any())<div class="rb-alert rb-alert-error" role="alert">{{ $errors->first() }}</div>@endif
<form method="POST" action="{{ route('login') }}" class="rb-form-stack">
    @csrf
    <label class="rb-form-field" for="email"><span>Alamat Email</span><input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus autocomplete="username" placeholder="nama@contoh.com"><small>{{ $errors->first('email') }}</small></label>
    <label class="rb-form-field" for="password"><span>Kata Sandi</span><input id="password" name="password" type="password" required autocomplete="current-password" placeholder="Masukkan kata sandi"><small>{{ $errors->first('password') }}</small></label>
    <div class="auth-options"><label class="auth-checkbox" for="remember_me"><input id="remember_me" type="checkbox" name="remember"><span>Ingat saya</span></label>@if (Route::has('password.request'))<a href="{{ route('password.request') }}">Lupa kata sandi?</a>@endif</div>
    <button class="button auth-submit" type="submit">Masuk</button>
</form>
@if (Route::has('register'))<p class="auth-switch">Belum memiliki akun? <a href="{{ route('register') }}">Daftar sekarang</a></p>@endif
</x-guest-layout>
