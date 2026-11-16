<?php

namespace App\Policies;

use App\Models\Anggota;
use App\Models\Peminjaman;
use App\Models\User;

class PeminjamanPolicy
{
    /**
     * Admin dan petugas boleh melihat semua transaksi.
     * Siswa hanya boleh melihat transaksi miliknya berdasarkan user_id. Untuk
     * transaksi lama yang belum memiliki user_id, kepemilikan diverifikasi
     * melalui relasi anggota dan email akun login.
     */
    public function view(User $user, Peminjaman $peminjaman): bool
    {
        if ($user->hasRole(['admin', 'petugas'])) {
            return true;
        }

        if (! $user->hasRole('siswa')) {
            return false;
        }

        if ($peminjaman->user_id !== null) {
            return (int) $peminjaman->user_id === (int) $user->id;
        }

        // Kompatibilitas untuk transaksi lama yang belum memiliki user_id.
        $anggotaId = Anggota::query()
            ->where('email', $user->email)
            ->value('id');

        return $anggotaId !== null
            && (int) $peminjaman->anggota_id === (int) $anggotaId;
    }
}
