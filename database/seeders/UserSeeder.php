<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // ===== OWNER =====
        User::create([
            'name' => 'Owner Satu',
            'username' => 'owner',
            'email' => 'owner@mail.com',
            'password' => Hash::make('#MyOwner77'),
            'role' => 'owner',
        ]);

        User::create([
            'name' => 'Owner Dua',
            'username' => 'owner2',
            'email' => 'owner2@mail.com',
            'password' => Hash::make('#Secret123'),
            'role' => 'owner',
        ]);

        // ===== USER =====
        User::create([
            'name' => 'User',
            'username' => 'user',
            'email' => 'user@mail.com',
            'password' => Hash::make('#SerUser123'),
            'role' => 'user',
        ]);

        // User::create([
        //     'name' => 'User Dua',
        //     'username' => 'user2',
        //     'email' => 'user2@mail.com',
        //     'password' => Hash::make('Secret123'),
        //     'role' => 'user',
        // ]);

        // User::create([
        //     'name' => 'User Tiga',
        //     'username' => 'user3',
        //     'email' => 'user3@mail.com',
        //     'password' => Hash::make('Secret123'),
        //     'role' => 'user',
        // ]);
    }
}
