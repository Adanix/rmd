<?php

namespace Database\Seeders;

use App\Models\Makanan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MakananSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['nama' => 'Nasi Kotak', 'keterangan' => 'Nasi kotak lengkap lauk'],
            ['nama' => 'Nasi Kebuli', 'keterangan' => 'Nasi kebuli khas Arab'],
            ['nama' => 'Mie Goreng', 'keterangan' => 'Mie goreng rumahan'],
            ['nama' => 'Roti', 'keterangan' => 'Roti isi coklat / keju'],
            ['nama' => 'Kurma', 'keterangan' => 'Kurma premium'],
        ];

        Makanan::insert($data);
    }
}
