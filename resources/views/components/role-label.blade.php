@props(['role' => ''])
@php
    $roleKey = strtolower((string) $role);
    $roleLabels = ['admin' => 'Administrator', 'petugas' => 'Petugas', 'siswa' => 'Siswa'];
@endphp
{{ $roleLabels[$roleKey] ?? 'Belum Ditentukan' }}
