<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Validasi seluruh password sebelum seeder lain membuat data,
        // agar tidak terjadi seeding parsial pada database Supabase/PostgreSQL.
        $adminPassword = config('app.seed_admin_password');
        if (is_string($adminPassword) && trim($adminPassword) !== '' && strlen(trim($adminPassword)) < 12) {
            throw new \RuntimeException('SEED_ADMIN_PASSWORD harus minimal 12 karakter.');
        }

        if (config('database.default') === 'pgsql') {
            foreach ([
                'SEED_ADMIN_PASSWORD' => $adminPassword,
                'SEED_PETUGAS_PASSWORD' => config('app.seed_petugas_password'),
                'SEED_SISWA_PASSWORD' => config('app.seed_siswa_password'),
            ] as $key => $value) {
                if (! is_string($value) || trim($value) === '' || strlen(trim($value)) < 12) {
                    throw new \RuntimeException($key.' wajib diisi minimal 12 karakter saat memakai PostgreSQL/Supabase.');
                }
            }
        }

        $this->call([
            KategoriSeeder::class,
            AnggotaSeeder::class,
            BukuSeeder::class,
            PeminjamanSeeder::class,
            RolePermissionSeeder::class,
            UserSeeder::class,
            Modul6TestSeeder::class,
        ]);
    }
}
