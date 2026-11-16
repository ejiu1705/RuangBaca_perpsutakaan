<x-guest-layout>
<div class="auth-heading"><span class="auth-eyebrow">PEMULIHAN AKUN</span><h1>Lupa Kata Sandi?</h1><p>Masukkan alamat email yang terdaftar. Kami akan mengirimkan tautan untuk membuat kata sandi baru.</p></div>
<x-auth-session-status class="mb-4" :status="session('status')" />
@if ($errors->any())<div class="rb-alert rb-alert-error" role="alert">{{ $errors->first() }}</div>@endif
<form method="POST" action="{{ route('password.email') }}" class="rb-form-stack">
    @csrf
    <label class="rb-form-field" for="email"><span>Alamat Email</span><input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus autocomplete="username" placeholder="nama@contoh.com"><small>{{ $errors->first('email') }}</small></label>
    <button class="button auth-submit" type="submit">Kirim Tautan Pemulihan</button>
</form>
<p class="auth-switch"><a href="{{ route('login') }}">Kembali ke Halaman Masuk</a></p>
</x-guest-layout>
