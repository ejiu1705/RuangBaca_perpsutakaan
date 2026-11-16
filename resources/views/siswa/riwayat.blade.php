<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Riwayat Peminjaman Saya</h2>
    </x-slot>
    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">
            @if (session('status')) <div class="bg-green-50 p-4 rounded text-green-800">{{ session('status') }}</div> @endif
            @if ($errors->any()) <div class="bg-red-50 p-4 rounded text-red-700">{{ $errors->first() }}</div> @endif

            @can('mengajukan peminjaman buku')
                <div class="bg-white p-6 shadow-sm sm:rounded-lg">
                    <h3 class="font-semibold mb-4">Ajukan Peminjaman Buku</h3>
                    <form method="POST" action="{{ route('peminjaman.store') }}" class="flex flex-col sm:flex-row gap-3">
                        @csrf
                        <select name="buku_id" required class="flex-1 rounded-md border-gray-300">
                            <option value="">-- Pilih buku --</option>
                            @foreach ($buku as $item)
                                <option value="{{ $item->id }}" @selected(old('buku_id') == $item->id)>{{ $item->judul }}</option>
                            @endforeach
                        </select>
                        <button class="rounded bg-blue-600 px-4 py-2 text-white">Ajukan Peminjaman</button>
                    </form>
                </div>
            @endcan

            <div class="bg-white p-6 shadow-sm sm:rounded-lg overflow-x-auto">
                <p class="mb-4 text-gray-600">Data berikut dibatasi berdasarkan akun yang sedang login.</p>
                <table class="min-w-full text-sm">
                    <thead><tr class="border-b text-left"><th class="p-3">ID</th><th class="p-3">Buku</th><th class="p-3">Tanggal Pinjam</th><th class="p-3">Tanggal Kembali</th><th class="p-3">Status</th></tr></thead>
                    <tbody>
                    @forelse ($peminjamans as $item)
                        <tr class="border-b">
                            <td class="p-3">{{ $item->id }}</td>
                            <td class="p-3">{{ $item->buku?->judul ?? '-' }}</td>
                            <td class="p-3">{{ $item->tanggal_pinjam?->format('d-m-Y') ?? '-' }}</td>
                            <td class="p-3">{{ $item->tanggal_kembali?->format('d-m-Y') ?? '-' }}</td>
                            <td class="p-3">{{ ucfirst($item->status ?? 'menunggu') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="p-3">Belum ada riwayat peminjaman.</td></tr>
                    @endforelse
                    </tbody>
                </table>
                <div class="mt-4">{{ $peminjamans->links() }}</div>
            </div>
        </div>
    </div>
</x-app-layout>
