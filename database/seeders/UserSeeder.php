<?php

namespace Database\Seeders;

use App\Models\Anggota;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $adminPassword = config('app.seed_admin_password');
        if (! is_string($adminPassword) || trim($adminPassword) === '') {
            $adminPassword = Str::password(24);
            $this->command?->warn('Password admin sementara (simpan sekarang): '.$adminPassword);
        } elseif (strlen($adminPassword) < 12) {
            throw new \RuntimeException('SEED_ADMIN_PASSWORD harus minimal 12 karakter.');
        }

        // Jika database memakai PostgreSQL (Supabase untuk Modul VII), jangan
        // membuat akun Petugas/Siswa production dengan password demo "password".
        $usesSupabase = config('database.default') === 'pgsql';
        $petugasPassword = config('app.seed_petugas_password');
        $siswaPassword = config('app.seed_siswa_password');

        if ($usesSupabase) {
            foreach ([
                'SEED_PETUGAS_PASSWORD' => $petugasPassword,
                'SEED_SISWA_PASSWORD' => $siswaPassword,
            ] as $key => $value) {
                if (! is_string($value) || strlen($value) < 12) {
                    throw new \RuntimeException($key.' wajib diisi minimal 12 karakter saat memakai PostgreSQL/Supabase.');
                }
            }
        } else {
            $petugasPassword = is_string($petugasPassword) && $petugasPassword !== ''
                ? $petugasPassword
                : 'password';
            $siswaPassword = is_string($siswaPassword) && $siswaPassword !== ''
                ? $siswaPassword
                : 'password';
        }

        $accounts = [
            ['name' => 'Administrator', 'email' => 'admin@gmail.com', 'role' => 'admin', 'password' => $adminPassword],
            ['name' => 'Petugas Perpustakaan', 'email' => 'petugas@gmail.com', 'role' => 'petugas', 'password' => $petugasPassword],
            ['name' => 'Siswa Perpustakaan', 'email' => 'siswa@gmail.com', 'role' => 'siswa', 'password' => $siswaPassword],
            ['name' => 'Siswa Perpustakaan', 'email' => 'siswa1@gmail.com', 'role' => 'siswa', 'password' => $siswaPassword],
            ['name' => 'Siswa Perpustakaan', 'email' => 'siswa2@gmail.com', 'role' => 'siswa', 'password' => $siswaPassword],
        ];

        foreach ($accounts as $account) {
            $attributes = ['name' => $account['name'], 'password' => $account['password']];
            $user = ($account['role'] === 'admin' || $usesSupabase)
                ? User::updateOrCreate(['email' => $account['email']], $attributes)
                : User::firstOrCreate(['email' => $account['email']], $attributes);

            $user->syncRoles([$account['role']]);

            Anggota::firstOrCreate(
                ['email' => $user->email],
                ['nama' => $user->name, 'no_telepon' => '-']
            );
        }
    }
}
