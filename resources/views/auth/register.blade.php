<x-guest-layout>
<div class="auth-heading"><span class="auth-eyebrow">AKUN BARU</span><h1>Daftar RuangBaca</h1><p>Buat akun untuk menggunakan layanan perpustakaan digital.</p></div>
@if ($errors->any())<div class="rb-alert rb-alert-error" role="alert">{{ $errors->first() }}</div>@endif
<form method="POST" action="{{ route('register') }}" class="rb-form-stack">
    @csrf
    <label class="rb-form-field" for="name"><span>Nama Lengkap</span><input id="name" name="name" type="text" value="{{ old('name') }}" required autofocus autocomplete="name" placeholder="Masukkan nama lengkap"><small>{{ $errors->first('name') }}</small></label>
    <label class="rb-form-field" for="email"><span>Alamat Email</span><input id="email" name="email" type="email" value="{{ old('email') }}" required autocomplete="username" placeholder="nama@contoh.com"><small>{{ $errors->first('email') }}</small></label>
    <label class="rb-form-field" for="password"><span>Kata Sandi</span><input id="password" name="password" type="password" required autocomplete="new-password"><small>{{ $errors->first('password') }}</small></label>
    <label class="rb-form-field" for="password_confirmation"><span>Konfirmasi Kata Sandi</span><input id="password_confirmation" name="password_confirmation" type="password" required autocomplete="new-password"></label>
    <button class="button auth-submit" type="submit">Buat Akun</button>
</form>
<p class="auth-switch">Sudah memiliki akun? <a href="{{ route('login') }}">Masuk</a></p>
</x-guest-layout>
