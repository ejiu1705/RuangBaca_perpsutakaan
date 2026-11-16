<div class="rb-card-heading"><div><span class="eyebrow">INFORMASI AKUN</span><h2>Informasi Profil</h2><p>Perbarui nama dan alamat email yang digunakan pada akun ini.</p></div></div>
<form id="send-verification" method="post" action="{{ route('verification.send') }}">@csrf</form>
<form method="post" action="{{ route('profile.update') }}" class="rb-form-stack">
    @csrf @method('patch')
    <label class="rb-form-field" for="name"><span>Nama Lengkap</span><input id="name" name="name" type="text" value="{{ old('name', $user->name) }}" required autocomplete="name"><small>{{ $errors->first('name') }}</small></label>
    <label class="rb-form-field" for="email"><span>Alamat Email</span><input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" required autocomplete="username"><small>{{ $errors->first('email') }}</small></label>
    @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
        <div class="rb-alert rb-alert-warning"><span>Alamat email Anda belum diverifikasi.</span><button form="send-verification" class="rb-text-button">Kirim ulang tautan verifikasi</button>
        </div>
    @endif
    <div class="rb-form-actions"><button class="button" type="submit">Simpan Perubahan</button></div>
</form>
