<?php

namespace Database\Seeders;

use App\Models\Kategori;
use Illuminate\Database\Seeder;

class KategoriSeeder extends Seeder
{
    public function run(): void
    {
        // Kategori bawaan hanya data awal; Admin/Petugas tetap dapat menambah kategori baru.
        foreach ([
            'Pemrograman',
            'Web',
            'Basis Data',
            'Sistem Informasi',
            'Jaringan Komputer',
            'Keamanan Siber',
        ] as $nama) {
            Kategori::firstOrCreate(['nama' => $nama]);
        }
    }
}
