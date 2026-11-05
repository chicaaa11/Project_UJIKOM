@extends('layouts.app')

@section('title', 'Riwayat Pengembalian - Panel Admin')
@section('header-title', 'Manajemen Pengembalian & Denda Alat')

@section('content')
<!-- Notifikasi Sukses / Gagal -->
@if(session('success'))
    <div class="mb-4 bg-emerald-50 border border-emerald-200 text-emerald-800 p-4 rounded-lg shadow-sm text-sm">
        {{ session('success') }}
    </div>
@endif
@if(session('error'))
    <div class="mb-4 bg-red-50 border border-red-200 text-red-800 p-4 rounded-lg shadow-sm text-sm">
        {{ session('error') }}
    </div>
@endif

<div class="bg-white rounded-lg shadow-sm overflow-hidden border border-gray-200">
    <!-- Header Tabel & Search Bar -->
    <div class="p-5 border-b border-gray-200 bg-gray-50 flex flex-col md:flex-row justify-between items-center gap-4">
        <div>
            <h3 class="text-lg font-bold text-gray-800">Daftar Riwayat Pengembalian Alat</h3>
            <p class="text-xs text-gray-500 mt-0.5"></p>
        </div>

        <form action="{{ route('admin.pengembalian.index') }}" method="GET" class="flex w-full md:w-80">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama peminjam / status..."
                class="w-full px-3 py-2 text-sm border border-gray-300 rounded-l-lg focus:outline-none focus:ring-1 focus:ring-blue-500">
            <button type="submit" class="bg-gray-800 hover:bg-gray-900 text-white px-4 py-2 text-sm font-semibold rounded-r-lg transition">
                Cari
            </button>
            @if(request('search'))
                <a href="{{ route('admin.pengembalian.index') }}" class="ml-2 bg-gray-300 hover:bg-gray-400 px-3 py-2 text-sm rounded-lg flex items-center transition">Reset</a>
            @endif
        </form>
    </div>

    <!-- Tabel Data Pengembalian -->
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-100 text-gray-600 text-xs uppercase tracking-wider">
                    <th class="py-3 px-4 border-b">Peminjam</th>
                    <th class="py-3 px-4 border-b">Alat yang Dikembalikan</th>
                    <th class="py-3 px-4 border-b">Jadwal & Tanggal Aktual</th>
                    <th class="py-3 px-4 border-b">Denda Keterlambatan</th>
                    <th class="py-3 px-4 border-b">Status</th>
                    <th class="py-3 px-4 border-b text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-gray-700 text-sm">
                @forelse($pengembalians as $item)
                <tr class="hover:bg-gray-50 transition align-top">
                    <!-- Kolom Peminjam -->
                    <td class="py-3 px-4 border-b font-medium text-gray-900">
                        {{ $item->user->name ?? 'User Dihapus' }}
                        <span class="block text-xs text-gray-400 font-normal">{{ $item->user->email ?? '' }}</span>
                    </td>

                    <!-- Kolom Alat -->
                    <td class="py-3 px-4 border-b">
                        <ul class="list-disc list-inside space-y-1">
                            @foreach($item->detailPinjams as $detail)
                            <li>
                                <span class="font-semibold">{{ $detail->alat->nama_alat ?? 'Alat Dihapus' }}</span>
                                <span class="text-xs bg-gray-100 border border-gray-200 text-gray-600 px-1.5 py-0.5 rounded">({{ $detail->jumlah }} pcs)</span>
                            </li>
                            @endforeach
                        </ul>
                    </td>

                    <!-- Kolom Tanggal -->
                    <td class="py-3 px-4 border-b text-xs text-gray-600 space-y-0.5">
                        <span class="block text-gray-500">Pinjam: {{ $item->tgl_pinjam }}</span>
                        <span class="block text-gray-700">Rencana Kembali: {{ $item->tgl_kembali_plan }}</span>
                        <span class="block font-semibold text-emerald-600">
                            Aktual Kembali: {{ $item->tgl_kembali_aktual ?? '-' }}
                        </span>
                    </td>

                    <!-- Kolom Denda -->
                    <td class="py-3 px-4 border-b text-xs font-bold text-red-600">
                        @if($item->denda > 0)
                            Rp {{ number_format($item->denda, 0, ',', '.') }}
                        @else
                            <span class="text-emerald-600 font-normal">Tidak Ada (Tepat Waktu)</span>
                        @endif
                    </td>

                    <!-- Kolom Status -->
                    <td class="py-3 px-4 border-b">
                        <span class="px-2.5 py-1 text-xs font-semibold rounded-full
                            @if($item->status == 'selesai') bg-emerald-100 text-emerald-800
                            @else bg-red-100 text-red-800 @endif">
                            {{ ucfirst($item->status) }}
                        </span>
                    </td>

                    <!-- Kolom Aksi -->
                    <td class="py-3 px-4 border-b text-center">
                        <form action="{{ route('admin.pengembalian.destroy', $item->id) }}" method="POST"
                            onsubmit="return confirm('Yakin ingin menghapus riwayat pengembalian ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1.5 rounded-lg text-xs font-semibold transition shadow-sm">
                                Hapus Riwayat
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="py-6 text-center text-gray-400 italic">Belum ada riwayat pengembalian alat.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="p-4 border-t border-gray-200 bg-gray-50">
        {{ $pengembalians->links() }}
    </div>
</div>
@endsection