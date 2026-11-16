<?php

namespace Database\Factories;

use App\Models\Kategori;
use Illuminate\Database\Eloquent\Factories\Factory;

class BukuFactory extends Factory
{
    public function definition(): array
    {
        return [
            'kategori_id' => Kategori::inRandomOrder()->value('id'),
            'judul' => fake()->randomElement([
                'Pemrograman PHP Dasar',
                'Belajar Laravel untuk Pemula',
                'Dasar-Dasar Basis Data',
                'Algoritma dan Pemrograman',
                'Pemrograman Berbasis Web',
                'Pengantar Sistem Informasi',
                'Belajar Database MySQL',
                'Membangun Aplikasi Web dengan Laravel',
            ]),
            'penulis' => fake()->randomElement([
                'Andi Setiawan',
                'Budi Santoso',
                'Citra Lestari',
                'Dedi Pratama',
                'Eka Rahmawati',
                'Fajar Nugroho',
                'Rina Wulandari',
                'Sari Permata',
            ]),
            'tahun_terbit' => fake()->numberBetween(2020, 2025),
        ];
    }
}
