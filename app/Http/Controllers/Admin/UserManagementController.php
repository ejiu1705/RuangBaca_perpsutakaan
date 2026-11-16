<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Validation\Rule;

class UserManagementController extends Controller
{
    public function index(): View
    {
        $users = User::with('roles')->orderBy('name')->paginate(15);

        return view('admin.users.index', compact('users'));
    }

    public function updateRole(Request $request, User $user): RedirectResponse
    {
        // Hanya admin yang memiliki permission kelola role pengguna yang dapat mencapai method ini.
        $validated = $request->validate([
            'role' => ['required', 'string', Rule::in(['admin', 'petugas', 'siswa'])],
        ]);

        if ($user->is(auth()->user()) && $validated['role'] !== 'admin') {
            return back()->withErrors([
                'role' => 'Admin tidak dapat menurunkan role akun yang sedang digunakan.',
            ]);
        }

        $user->syncRoles([$validated['role']]);

        return back()->with('status', 'Role pengguna berhasil diperbarui.');
    }
}
