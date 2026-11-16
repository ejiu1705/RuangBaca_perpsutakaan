<x-app-layout>
    <x-slot name="header">
        <x-page-heading eyebrow="KATALOG / DETAIL BUKU" title="Detail Buku" description="Informasi lengkap mengenai buku yang dipilih." />
    </x-slot>
    <div class="rb-content">
        <section class="rb-card rb-detail-card">
            <div class="rb-card-heading">
                <div><span class="eyebrow">INFORMASI BUKU</span><h2>{{ $buku['judul'] }}</h2><p>Data koleksi yang tercatat pada RuangBaca.</p></div>
                <span class="rb-count-chip">ID Buku #{{ $buku['id'] }}</span>
            </div>
            <dl class="rb-detail-grid">
                <div class="rb-detail-item"><dt>Penulis</dt><dd>{{ $buku['penulis'] ?: 'Belum diisi' }}</dd></div>
                <div class="rb-detail-item"><dt>Tahun Terbit</dt><dd>{{ $buku['tahun'] ?: 'Belum diisi' }}</dd></div>
                <div class="rb-detail-item"><dt>Kategori</dt><dd>{{ $buku['kategori'] ?: 'Tanpa Kategori' }}</dd></div>
            </dl>
            <div class="rb-form-actions">
                <a class="button secondary" href="{{ route('buku.index') }}">Kembali ke Katalog</a>
                @can('mengajukan peminjaman buku')
                    <form method="POST" action="{{ route('peminjaman.store') }}">@csrf<input type="hidden" name="buku_id" value="{{ $buku['id'] }}"><button class="button" type="submit">Ajukan Peminjaman</button></form>
                @endcan
            </div>
        </section>
    </div>
</x-app-layout>
