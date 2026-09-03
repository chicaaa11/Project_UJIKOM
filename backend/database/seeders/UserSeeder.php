<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'name' => 'Salsabila Nur Rahma',
                'email' => 'admin@gmail.com',
                'password' => Hash::make('password123'),
                'role' => 'admin',
                'no_hp' => '081234567890',
                'alamat' => 'Bandung, West Java',
            ],
            [
                'name' => 'Syifa Nurul',
                'email' => 'petugas@gmail.com',
                'password' => Hash::make('password123'),
                'role' => 'petugas',
                'no_hp' => '082345678901',
                'alamat' => 'Baleendah, Bandung',
            ],
            [
                'name' => 'Firli Nontin',
                'email' => 'rian@gmail.com',
                'password' => Hash::make('password123'),
                'role' => 'peminjam',
                'no_hp' => '083456789012',
                'alamat' => 'Ciparay, Bandung',
            ],
            [
                'name' => 'Meyra sabila',
                'email' => 'siti@gmail.com',
                'password' => Hash::make('password123'),
                'role' => 'peminjam',
                'no_hp' => '084567890123',
                'alamat' => 'Dayeuhkolot, Bandung',
            ],
            [
                'name' => 'Eka Pratama',
                'email' => 'eka@gmail.com',
                'password' => Hash::make('password123'),
                'role' => 'peminjam',
                'no_hp' => '085678901234',
                'alamat' => 'Banjaran, Bandung',
            ],
        ];

        foreach ($users as $user) {
            User::create($user);
        }
    }
}