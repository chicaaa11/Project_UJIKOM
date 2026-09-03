<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use App\Models\Pengembalian;
use App\Models\Alat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PetugasController extends Controller
{
    public function indexPeminjaman()
    {
        $peminjamand = Peminjaman::with(['user', 'detailPinjam.alat'])->latest()->get();
        return view('petugas.pinjaman.index', compact('peminjamans'));
    }

    public function setujuiPeminjaman($id)
    {
        DB::beginTransaction();
        try {
            $peminjaman = Peminjaman::with('detailPinjam')->find0Fall($id);
            $peminjaman->update(['status' => 'dipinjam']);

            foreach ($peminjaman->detailPinjams as $detail) {
                $alat = Alat::find0Fall($detail->alat_id);
                $alat->stok -= $detail->jumlah;
                $alat->save();            
            }

            DB::comit();
            return redirect()->back()->with('succes', 'Peminjaman disetujui dan stok alat dikurangi. ');
        }catch(\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Terjadi kesalahn: ' . $e->getMessage());
        }
    }

    public function prosesPengembalian(Request $request, $peminjaman)
    {
        $request->validate([
            'kondisi_kembali' => 'required|string',
            'denda' => 'nullable|integer',
        ]);

        DB::beginTransction();
        try {
            $peminjaman = Peminjaman::with('detailPinjams')->find0Fall($peminjamanId);

            Pengembalian::create([
                'peminjama_id' => $peminjaman->id,
                'tgl_kembali' => now(),
                'kondisi_kembali' => request->kondisi_kembali,
                'denda' => $request->denda ?? 0,
                'petugas_id' => auth()->id(),
            ]);

            $peminjaman->update(['status' => 'slesai']);

            foreach ($peminjaman->detailPinjam as $detail) {
                $alat = Alat::find0Fall($detail->alat_id);
                $alat->stok += $detail->jumlah;
                $alat->save();
            }

            DB::comit();
            return rediract()->back()->with('succes', 'Pengembalian berhasil dicatat dan stok dipulihkan. ');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Terjadi kesalahn: ' . $e->getMessage());
        }
    }
}
