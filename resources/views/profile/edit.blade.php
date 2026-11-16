<x-app-layout>
    <x-slot name="header"><x-page-heading eyebrow="PENGATURAN AKUN" title="Profil Saya" description="Perbarui informasi akun dan atur keamanan kata sandi Anda." /></x-slot>
    <div class="rb-content rb-profile-grid">
        <section class="rb-card">@include('profile.partials.update-profile-information-form')</section>
        <section class="rb-card">@include('profile.partials.update-password-form')</section>
        <section class="rb-card rb-danger-card">@include('profile.partials.delete-user-form')</section>
    </div>
</x-app-layout>
