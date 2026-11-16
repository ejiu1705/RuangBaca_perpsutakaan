<x-app-layout>
    <x-slot name="header"><x-page-heading eyebrow="ADMINISTRASI / AKUN" title="Pengelolaan Pengguna" description="Atur akun pengguna dan peran sesuai tanggung jawab masing-masing." /></x-slot>
    <div class="rb-content">
        <section class="rb-stat-grid rb-stat-grid-two">
            <article class="rb-stat-card"><span class="rb-stat-label">Total Pengguna</span><strong>{{ number_format($users->total(), 0, ',', '.') }}</strong><small>Akun yang terdaftar dalam sistem</small></article>
            <article class="rb-stat-card"><span class="rb-stat-label">Halaman Saat Ini</span><strong>{{ $users->currentPage() }} / {{ max(1, $users->lastPage()) }}</strong><small>Daftar pengguna ditampilkan bertahap</small></article>
        </section>
        <section class="rb-card">
            <div class="rb-card-heading"><div><span class="eyebrow">DATA AKUN</span><h2>Daftar Pengguna</h2><p>Periksa informasi akun dan perbarui peran jika diperlukan.</p></div><span class="rb-count-chip">{{ number_format($users->total(), 0, ',', '.') }} pengguna</span></div>
            <div class="rb-table-wrap"><table class="rb-table"><thead><tr><th>Nama Lengkap</th><th>Alamat Email</th><th>Peran</th><th>Tindakan</th></tr></thead><tbody>
                @forelse ($users as $user)
                    @php $role = $user->getRoleNames()->first() ?? ''; @endphp
                    <tr>
                        <td><strong class="rb-primary-text">{{ $user->name }}</strong></td>
                        <td>{{ $user->email }}</td>
                        <td><span class="rb-role-chip"><x-role-label :role="$role" /></span></td>
                        <td><form method="POST" action="{{ route('admin.users.role.update', $user) }}" class="rb-inline-form">@csrf @method('PATCH')
                            <select name="role" aria-label="Pilih peran untuk {{ $user->name }}" required>
                                @foreach (['admin', 'petugas', 'siswa'] as $availableRole)
                                    <option value="{{ $availableRole }}" @selected($user->hasRole($availableRole))><x-role-label :role="$availableRole" /></option>
                                @endforeach
                            </select>
                            <button class="button small" type="submit">Simpan Perubahan</button>
                        </form></td>
                    </tr>
                @empty
                    <tr><td colspan="4"><div class="rb-empty-state"><strong>Belum ada pengguna.</strong><span>Akun yang terdaftar akan muncul di bagian ini.</span></div></td></tr>
                @endforelse
            </tbody></table></div>
            <div class="rb-pagination">{{ $users->links() }}</div>
        </section>
    </div>
</x-app-layout>
