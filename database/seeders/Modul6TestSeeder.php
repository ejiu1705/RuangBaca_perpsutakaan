<?php

namespace Database\Seeders;

use App\Models\Anggota;
use App\Models\Buku;
use App\Models\Peminjaman;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class Modul6TestSeeder extends Seeder
{
    public function run(): void
    {
        $buku = Buku::query()->orderBy('id')->first();

        if (! $buku) {
            $this->command?->warn(
                'Belum ada data buku. Jalankan BukuSeeder terlebih dahulu.'
            );
            return;
        }

        foreach ([
            'siswa@gmail.com',
            'siswa1@gmail.com',
            'siswa2@gmail.com',
        ] as $email) {
            $user = User::where('email', $email)->first();

            if (! $user) {
                $this->command?->warn("Akun {$email} tidak ditemukan.");
                continue;
            }

            $anggota = Anggota::firstOrCreate(
                ['email' => $user->email],
                [
                    'nama' => $user->name,
                    'no_telepon' => '-',
                ]
            );

            Peminjaman::firstOrCreate(
                [
                    'user_id' => $user->id,
                    'anggota_id' => $anggota->id,
                    'buku_id' => $buku->id,
                    'tanggal_pinjam' => Carbon::today()->toDateString(),
                ],
                [
                    'tanggal_kembali' => Carbon::today()
                        ->addDays(7)
                        ->toDateString(),
                    'status' => 'menunggu',
                ]
            );
        }

        $this->command?->info(
            'Data uji Modul 6 berhasil disiapkan.'
        );
    }
}