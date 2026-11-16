@props([
    'judul',
    'penulis',
    'tahun',
])

<article class="book-card">
    <div>
        <h3>{{ $judul }}</h3>
        <p><strong>Penulis:</strong> {{ $penulis }}</p>
        <p><strong>Tahun Terbit:</strong> {{ $tahun }}</p>
    </div>

    <div class="card-footer">
        {{ $footer ?? '' }}
    </div>
</article>
