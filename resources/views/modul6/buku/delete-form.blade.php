{{-- Partial contoh form penghapusan web untuk Modul 6; gunakan di dalam loop buku. --}}
<form action="{{ route('buku.destroy', $buku->id) }}" method="POST">
    @csrf
    @method('DELETE')
    <button type="submit" class="button danger small"
            onclick="return confirm('Yakin ingin menghapus buku ini?')">
        Hapus
    </button>
</form>
