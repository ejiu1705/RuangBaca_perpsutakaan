<x-guest-layout>
<div class="auth-heading"><span class="auth-eyebrow">PEMULIHAN AKUN</span><h1>Buat Kata Sandi Baru</h1><p>Masukkan alamat email dan kata sandi baru untuk akun Anda.</p></div>
@if ($errors->any())<div class="rb-alert rb-alert-error" role="alert">{{ $errors->first() }}</div>@endif
<form method="POST" action="{{ route('password.store') }}" class="rb-form-stack">
    @csrf
    <input type="hidden" name="token" value="{{ $request->route('token') }}">
    <label class="rb-form-field" for="email"><span>Alamat Email</span><input id="email" name="email" type="email" value="{{ old('email', $request->email) }}" required autofocus autocomplete="username"><small>{{ $errors->first('email') }}</small></label>
    <label class="rb-form-field" for="password"><span>Kata Sandi Baru</span><input id="password" name="password" type="password" required autocomplete="new-password"><small>{{ $errors->first('password') }}</small></label>
    <label class="rb-form-field" for="password_confirmation"><span>Konfirmasi Kata Sandi Baru</span><input id="password_confirmation" name="password_confirmation" type="password" required autocomplete="new-password"><small>{{ $errors->first('password_confirmation') }}</small></label>
    <button class="button auth-submit" type="submit">Simpan Kata Sandi Baru</button>
</form>
</x-guest-layout>
