<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Laporan Perpustakaan</h2>
    </x-slot>
    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">
            <div class="grid gap-4 md:grid-cols-2">
                <div class="bg-white p-6 shadow-sm sm:rounded-lg"><div class="text-gray-500">Total Buku</div><div class="text-3xl font-bold">{{ $totalBuku }}</div></div>
                <div class="bg-white p-6 shadow-sm sm:rounded-lg"><div class="text-gray-500">Total Kategori</div><div class="text-3xl font-bold">{{ $totalKategori }}</div></div>
            </div>
            <div class="bg-white p-6 shadow-sm sm:rounded-lg overflow-x-auto">
                <h3 class="font-semibold mb-4">Rekap Buku dan Jumlah Peminjaman</h3>
                <table class="min-w-full text-sm">
                    <thead><tr class="border-b text-left"><th class="p-3">Judul</th><th class="p-3">Kategori</th><th class="p-3">Penulis</th><th class="p-3">Jumlah Peminjaman</th></tr></thead>
                    <tbody>
                    @forelse ($buku as $item)
                        <tr class="border-b"><td class="p-3">{{ $item->judul }}</td><td class="p-3">{{ $item->kategori?->nama ?? '-' }}</td><td class="p-3">{{ $item->penulis }}</td><td class="p-3">{{ $item->peminjamans_count }}</td></tr>
                    @empty
                        <tr><td colspan="4" class="p-3">Belum ada data buku.</td></tr>
                    @endforelse
                    </tbody>
                </table>
                <div class="mt-4">{{ $buku->links() }}</div>
            </div>
        </div>
    </div>
</x-app-layout>
