<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Pengelolaan Pengguna</h2>
    </x-slot>
    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 shadow-sm sm:rounded-lg">
                @if (session('status'))
                    <div class="mb-4 text-green-700">{{ session('status') }}</div>
                @endif
                @if ($errors->any())
                    <div class="mb-4 text-red-700">{{ $errors->first() }}</div>
                @endif
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm border-collapse">
                        <thead><tr class="border-b text-left">
                            <th class="p-3">Nama</th><th class="p-3">Email</th><th class="p-3">Role</th><th class="p-3">Ubah Role</th>
                        </tr></thead>
                        <tbody>
                        @forelse ($users as $user)
                            <tr class="border-b">
                                <td class="p-3">{{ $user->name }}</td>
                                <td class="p-3">{{ $user->email }}</td>
                                <td class="p-3">{{ $user->getRoleNames()->implode(', ') ?: 'Belum ditetapkan' }}</td>
                                <td class="p-3">
                                    <form method="POST" action="{{ route('admin.users.role.update', $user) }}" class="flex gap-2 items-center">
                                        @csrf @method('PATCH')
                                        <select name="role" class="rounded border-gray-300 text-sm">
                                            @foreach (['admin', 'petugas', 'siswa'] as $role)
                                                <option value="{{ $role }}" @selected($user->hasRole($role))>{{ ucfirst($role) }}</option>
                                            @endforeach
                                        </select>
                                        <button class="rounded bg-blue-600 px-3 py-2 text-white">Simpan</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="p-3">Belum ada pengguna.</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">{{ $users->links() }}</div>
            </div>
        </div>
    </div>
</x-app-layout>
