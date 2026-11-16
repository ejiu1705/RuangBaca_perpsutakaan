@php
    $flashStatus = session('status');
    $flashSuccess = session('success');
    $flashStatus = match ($flashStatus) {
        'profile-updated' => 'Profil berhasil diperbarui.',
        'password-updated' => 'Kata sandi berhasil diperbarui.',
        'verification-link-sent' => 'Tautan verifikasi telah dikirim ke alamat email Anda.',
        default => $flashStatus,
    };
@endphp
@if (filled($flashStatus))
    <div class="rb-alert rb-alert-success" role="status">{{ $flashStatus }}</div>
@endif
@if (filled($flashSuccess))
    <div class="rb-alert rb-alert-success" role="status">{{ $flashSuccess }}</div>
@endif
@if ($errors->any() && ! request()->routeIs('profile.*', 'siswa.riwayat'))
    <div class="rb-alert rb-alert-error" role="alert">{{ $errors->first() }}</div>
@endif
