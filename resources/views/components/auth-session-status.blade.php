@props(['status'])
@php
    $displayStatus = match ($status) {
        'profile-updated' => 'Profil berhasil diperbarui.',
        'password-updated' => 'Kata sandi berhasil diperbarui.',
        'verification-link-sent' => 'Tautan verifikasi telah dikirim ke alamat email Anda.',
        default => $status,
    };
@endphp
@if (filled($displayStatus))
    <div {{ $attributes->merge(['class' => 'rb-alert rb-alert-success']) }} role="status">
        {{ $displayStatus }}
    </div>
@endif
