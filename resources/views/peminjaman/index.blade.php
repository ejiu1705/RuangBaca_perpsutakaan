<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Pengelolaan Peminjaman dan Pengembalian</h2>
    </x-slot>
    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 shadow-sm sm:rounded-lg">
                @if (session('status')) <p class="mb-4 text-green-700">{{ session('status') }}</p> @endif
                @if ($errors->any()) <p class="mb-4 text-red-700">{{ $errors->first() }}</p> @endif
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead><tr class="border-b text-left"><th class="p-3">Anggota</th><th class="p-3">Buku</th><th class="p-3">Tanggal Pinjam</th><th class="p-3">Tanggal Kembali</th><th class="p-3">Status</th><th class="p-3">Aksi</th></tr></thead>
                        <tbody>
                        @forelse ($peminjamans as $item)
                            <tr class="border-b">
                                <td class="p-3">{{ $item->anggota?->nama ?? $item->user?->name ?? '-' }}</td>
                                <td class="p-3">{{ $item->buku?->judul ?? '-' }}</td>
                                <td class="p-3">{{ $item->tanggal_pinjam?->format('d-m-Y') ?? '-' }}</td>
                                <td class="p-3">{{ $item->tanggal_kembali?->format('d-m-Y') ?? '-' }}</td>
                                <td class="p-3">{{ ucfirst($item->status ?? 'menunggu') }}</td>
                                <td class="p-3">
                                    <div class="flex flex-wrap gap-2">
                                        @if (($item->status ?? 'menunggu') === 'menunggu' && auth()->user()->can('memproses persetujuan peminjaman'))
                                            <form method="POST" action="{{ route('peminjaman.approve', $item) }}">@csrf<button class="rounded bg-green-600 px-3 py-2 text-white">Setujui</button></form>
                                            <form method="POST" action="{{ route('peminjaman.reject', $item) }}">@csrf<button class="rounded bg-red-600 px-3 py-2 text-white">Tolak</button></form>
                                        @endif
                                        @if (($item->status ?? '') === 'disetujui' && auth()->user()->can('mencatat pengembalian buku'))
                                            <form method="POST" action="{{ route('peminjaman.return', $item) }}">@csrf<button class="rounded bg-blue-600 px-3 py-2 text-white">Catat Pengembalian</button></form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="p-3">Belum ada data peminjaman.</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">{{ $peminjamans->links() }}</div>
            </div>
        </div>
    </div>
</x-app-layout>
