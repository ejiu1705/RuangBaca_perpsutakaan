@props(['eyebrow' => 'RUANGBACA', 'title', 'description' => '', 'date' => true])
<div class="page-title-wrap">
    <div>
        <span class="page-title-eyebrow">{{ $eyebrow }}</span>
        <h1>{{ $title }}</h1>
        @if ($description !== '')<p>{{ $description }}</p>@endif
    </div>
    @if ($date)
        <span class="page-date-chip"><span></span>{{ now()->translatedFormat('d F Y') }}</span>
    @endif
</div>
