<?php

namespace Database\Seeders;

use App\Models\Minuman;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MinumanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['nama' => 'Teh Manis', 'keterangan' => '1 Ceret Teh Manis Panas'],
            ['nama' => 'Air Mineral', 'keterangan' => 'Air mineral gelas atau botol'],
            ['nama' => 'Kopi', 'keterangan' => 'Kopi hitam atau kopi susu'],
            ['nama' => 'Jus Jeruk', 'keterangan' => 'Jus jeruk segar'],
            ['nama' => 'Susu Kurma', 'keterangan' => 'Susu dicampur kurma halus'],
        ];

        // DB::table('minumans')->insert($data);
        Minuman::insert($data);
    }
}
