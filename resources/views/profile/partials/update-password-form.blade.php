<div class="rb-card-heading"><div><span class="eyebrow">KEAMANAN AKUN</span><h2>Ubah Kata Sandi</h2><p>Gunakan kata sandi yang kuat dan tidak digunakan pada akun lain.</p></div></div>
<form method="post" action="{{ route('password.update') }}" class="rb-form-stack">
    @csrf @method('put')
    <label class="rb-form-field" for="update_password_current_password"><span>Kata Sandi Saat Ini</span><input id="update_password_current_password" name="current_password" type="password" autocomplete="current-password" required><small>{{ $errors->updatePassword->first('current_password') }}</small></label>
    <label class="rb-form-field" for="update_password_password"><span>Kata Sandi Baru</span><input id="update_password_password" name="password" type="password" autocomplete="new-password" required><small>{{ $errors->updatePassword->first('password') }}</small></label>
    <label class="rb-form-field" for="update_password_password_confirmation"><span>Konfirmasi Kata Sandi Baru</span><input id="update_password_password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" required><small>{{ $errors->updatePassword->first('password_confirmation') }}</small></label>
    <div class="rb-form-actions"><button class="button" type="submit">Perbarui Kata Sandi</button></div>
</form>
