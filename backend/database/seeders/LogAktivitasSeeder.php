<?php

namespace Database\Seeders;

use App\Models\LogAktivitas;
use Illuminate\Database\Seeder;

class LogAktivitasSeeder extends Seeder
{
    public function run(): void
    {
        $logs = [
            ['user_id' => 3, 'aktivitas' => 'Melakukan pengajuan peminjaman alat.'],
            ['user_id' => 2, 'aktivitas' => 'Menyetujui peminjaman alat.'],
            ['user_id' => 2, 'aktivitas' => 'Mencatat pengembalian alat.'],
        ];

        foreach ($logs as $log) {
            LogAktivitas::create($log);
        }
    }
}