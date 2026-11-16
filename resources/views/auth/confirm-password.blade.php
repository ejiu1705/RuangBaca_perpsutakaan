<x-guest-layout>
<div class="auth-heading"><span class="auth-eyebrow">KONFIRMASI KEAMANAN</span><h1>Konfirmasi Kata Sandi</h1><p>Ini adalah bagian aman dari aplikasi. Masukkan kembali kata sandi sebelum melanjutkan.</p></div>
@if ($errors->any())<div class="rb-alert rb-alert-error" role="alert">{{ $errors->first() }}</div>@endif
<form method="POST" action="{{ route('password.confirm') }}" class="rb-form-stack">
    @csrf
    <label class="rb-form-field" for="password"><span>Kata Sandi</span><input id="password" name="password" type="password" required autocomplete="current-password" autofocus><small>{{ $errors->first('password') }}</small></label>
    <button class="button auth-submit" type="submit">Konfirmasi</button>
</form>
</x-guest-layout>
