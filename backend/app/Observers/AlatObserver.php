<?php

namespace App\Observers;

use App\Models\Alat;
use App\Models\LogAktivitas;

class AlatObserver
{
    public function created(Alat $alat)
    {
        LogAktivitas::create([
            'user_id' => auth()->id(),
            'aktivitas' => "Menambahkan data alat baru: '{$alat->nama_alat}' (Stok: {$alat->stok}, Kondisi: {$alat->status_kondisi}).",
        ]);
    }

    public function updated(Alat $alat)
    {
        $changes = [];
        foreach ($alat->getChanges() as $key => $newValue) {
            if ($key !== 'updated_at') {
                $oldValue = $alat->getOriginal($key);
                $changes[] = "kolom '{$key}' berubah dari '{$oldValue}' menjadi '{$newValue}'";
            }
        }

        $detailPerubahan = !empty($changes) ? implode(', ', $changes) : 'memperbarui data alat';

        LogAktivitas::create([
            'user_id' => auth()->id(),
            'aktivitas' => "Memperbarui alat '{$alat->nama_alat}': {$detailPerubahan}.",
        ]);
    }

    public function deleted(Alat $alat)
    {
        LogAktivitas::create([
            'user_id' => auth()->id(),
            'aktivitas' => "Menghapus data alat: '{$alat->nama_alat}'.",
        ]);
    }
}