<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Alat;
use App\Models\Kategori;
use App\Models\Peminjaman;
use App\Models\DetilPinjam;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PeminjamController extends Controller
{
    /**
     * Halaman Katalog Alat & Form Pengajuan Peminjaman
     */
    public function katalogAlat(Request $request)
    {
        $search     = $request->input('search');
        $kategoriId = $request->input('kategori');

        $alats = Alat::with('kategori')
            ->when($search, fn($q) => $q->where('nama_alat', 'like', "%{$search}%"))
            ->when($kategoriId, fn($q) => $q->where('kategori_id', $kategoriId))
            ->orderBy('nama_alat')
            ->get();

        $kategoris = Kategori::orderBy('nama_kategori')->get();

        return view('peminjam.katalog', compact('alat', 'kategoris', 'search', 'kategoriId'));
    }

    /**
     * Menyimpan pengajuan peminjaman (bisa lebih dari 1 alat sekaligus)
     */
    public function ajukanPeminjaman(Request $request)
    {
        $validated = $request->validate([
            'tgl_pinjam'        => 'required|date|after_or_equal:today',
            'tgl_kembali_plan'  => 'required|date|after:tgl_pinjam',
            'alat_id'           => 'required|array|min:1',
            'alat_id.*'         => 'required|exists:alat,id', // <-- SUDAH DIPERBAIKI (alats -> alat)
            'jumlah'            => 'required|array',
            'jumlah.*'          => 'required|integer|min:1',
        ], [
            'alat_id.required' => 'Pilih minimal 1 alat yang mau dipinjam.',
        ]);

        DB::beginTransaction();
        try {
            // Cek stok cukup untuk semua alat yang dipilih SEBELUM nyimpen apapun
            foreach ($validated['alat_id'] as $index => $alatId) {
                $alat = Alat::findOrFail($alatId);
                $jumlahDiminta = $validated['jumlah'][$index];

                if ($jumlahDiminta > $alat->stok) {
                    throw new \Exception("Stok {$alat->nama_alat} tidak cukup (tersisa {$alat->stok}).");
                }
            }

            // Buat data peminjaman utama, status masih 'diajukan' (belum potong stok)
            $peminjaman = Peminjaman::create([
                'user_id'          => auth()->id(),
                'tgl_pinjam'       => $validated['tgl_pinjam'],
                'tgl_kembali_plan' => $validated['tgl_kembali_plan'],
                'status'           => 'diajukan',
            ]);

            // Simpan detail alat yang dipinjam
            foreach ($validated['alat_id'] as $index => $alatId) {
                DetilPinjam::create([
                    'peminjaman_id' => $peminjaman->id,
                    'alat_id'       => $alatId,
                    'jumlah'        => $validated['jumlah'][$index],
                ]);
            }

            DB::commit();

            return redirect()->route('peminjam.riwayat')
                ->with('success', 'Pengajuan peminjaman berhasil dikirim, menunggu persetujuan petugas.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    /**
     * Halaman Riwayat & Pengembalian Alat
     */
    public function riwayatPeminjaman()
    {
        $peminjamans = Peminjaman::with('detailPinjams.alat')
            ->where('user_id', auth()->id())
            ->latest()
            ->get();

        return view('peminjam.riwayat', compact('peminjamans'));
    }

    /**
     * Peminjam menekan tombol "Ajukan Pengembalian".
     */
    public function ajukanPengembalian($id)
    {
        $peminjaman = Peminjaman::where('user_id', auth()->id())->findOrFail($id);

        if (!in_array($peminjaman->status, ['dipinjam', 'telat'])) {
            return back()->with('error', 'Peminjaman ini tidak bisa diajukan pengembaliannya.');
        }

        // Opsional: catat ke log aktivitas biar Petugas tau ada pengajuan pengembalian baru
        DB::table('log_aktivitas')->insert([
            'user_id'    => auth()->id(),
            'aktivitas'  => "Mengajukan pengembalian untuk Peminjaman #{$peminjaman->id}",
            'created_at' => now(),
        ]);

        return back()->with('success', 'Pengajuan pengembalian terkirim, silakan tunggu diproses oleh petugas.');
    }
}