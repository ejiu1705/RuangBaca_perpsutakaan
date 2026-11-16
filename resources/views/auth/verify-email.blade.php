<x-guest-layout>
<div class="auth-heading"><span class="auth-eyebrow">VERIFIKASI AKUN</span><h1>Verifikasi Alamat Email</h1><p>Terima kasih telah mendaftar. Buka tautan yang kami kirim ke email Anda untuk menyelesaikan verifikasi. Jika belum menerima email, Anda dapat meminta pengiriman ulang.</p></div>
@if (session('status') === 'verification-link-sent')<div class="rb-alert rb-alert-success" role="status">Tautan verifikasi baru telah dikirim ke alamat email yang Anda daftarkan.</div>@endif
<div class="rb-form-stack">
    <form method="POST" action="{{ route('verification.send') }}">@csrf<button class="button auth-submit" type="submit">Kirim Ulang Email Verifikasi</button></form>
    <form method="POST" action="{{ route('logout') }}">@csrf<button type="submit" class="rb-text-button">Keluar dari Akun</button></form>
</div>
</x-guest-layout>
