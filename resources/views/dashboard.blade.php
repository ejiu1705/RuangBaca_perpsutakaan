<x-app-layout>
    <x-slot name="header"><x-page-heading eyebrow="RUANGBACA" title="Dashboard" description="Anda akan diarahkan ke dashboard sesuai peran akun." /></x-slot>
    <div class="rb-content"><section class="rb-card"><div class="rb-empty-state"><strong>Menyiapkan dashboard Anda.</strong><span>Silakan tunggu hingga pengalihan selesai.</span><a href="{{ route('dashboard') }}" class="button small">Buka Dashboard</a></div></section></div>
</x-app-layout>
