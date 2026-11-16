@props(['status' => 'menunggu'])
@php
    $statusKey = strtolower((string) $status);
    $statusLabels = [
        'menunggu' => 'Menunggu Persetujuan',
        'disetujui' => 'Disetujui',
        'ditolak' => 'Ditolak',
        'dikembalikan' => 'Dikembalikan',
        'terlambat' => 'Terlambat',
        'aktif' => 'Aktif',
    ];
    $statusLabel = $statusLabels[$statusKey] ?? ucfirst(str_replace('_', ' ', $statusKey));
@endphp
<span {{ $attributes->merge(['class' => 'status-pill status-' . $statusKey]) }}>{{ $statusLabel }}</span>
