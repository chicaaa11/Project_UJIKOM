<?php

namespace App\Observers;

use App\Models\Pengembalian;
use App\Models\LogAktivitas;

class PengembalianObserver
{
    public function created(Pengembalian $pengembalian)
    {
        LogAktivitas::create([
            'user_id' => auth()->id() ?? $pengembalian->petugas_id,
            'aktivitas' => "Memproses pengembalian alat untuk Peminjaman ID: {$pengembalian->peminjaman_id} " .
                "dengan kondisi '{$pengembalian->kondisi_kembali}' " .
                "dan denda Rp " . number_format($pengembalian->denda, 0, ',', '.') . ".",
        ]);
    }

    public function updated(Pengembalian $pengembalian)
    {
        $changes = [];
        foreach ($pengembalian->getChanges() as $key => $newValue) {
            if ($key !== 'updated_at') {
                $oldValue = $pengembalian->getOriginal($key);
                $changes[] = "kolom '{$key}' dari '{$oldValue}' ke '{$newValue}'";
            }
        }

        $detailPerubahan = !empty($changes) ? implode(', ', $changes) : 'memperbarui data';

        LogAktivitas::create([
            'user_id' => auth()->id(),
            'aktivitas' => "Memperbarui pengembalian ID {$pengembalian->id}: {$detailPerubahan}.",
        ]);
    }

    public function deleted(Pengembalian $pengembalian)
    {
        LogAktivitas::create([
            'user_id' => auth()->id(),
            'aktivitas' => "Menghapus data pengembalian (ID: {$pengembalian->id}).",
        ]);
    }
}