<?php

namespace Database\Seeders;

use App\Models\Jamaah;
use Faker\Factory as Faker;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class JamaahSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        $alamatList = ['Tegal', 'Klenteng', 'Senden', 'Pepe', 'Bogoran Utara'];
        $keteranganList = [
            'Makanan dan Minuman',
            'Makanan',
            'Minuman'
        ];

        for ($i = 1; $i <= 40; $i++) {

            // peluang ekonomi
            $ekonomi = (random_int(1, 100) <= 30) ? 'Kurang Mampu' : 'Mampu';

            Jamaah::create([
                'nama'       => $faker->name(),
                'alamat'     => $faker->randomElement($alamatList),
                'ekonomi'    => $ekonomi,
                'setoran'    => $ekonomi === 'Kurang Mampu' ? 1 : 2,
                'keterangan' => $faker->randomElement($keteranganList),
                'notes'      => $faker->optional(0.3)->text(30),
            ]);
        }
    }
}
