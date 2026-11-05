@extends('layouts.app')

@section('title', 'Riwayat Peminjaman - Peminjam')
@section('header-title', 'Riwayat & Pengembalian Alat')

@section('content')
<div class="space-y-4">

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-800 p-3 rounded-lg text-sm">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="bg-red-50 border border-red-200 text-red-800 p-3 rounded-lg text-sm">
            {{ session('error') }}
        </div>
    @endif

    @forelse($peminjamans as $item)
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-5">
            <div class="flex justify-between items-start mb-3">
                <div>
                    <p class="text-sm text-gray-400 font-medium">Peminjaman #{{ $item->id }}</p>
                    <p class="text-lg font-bold text-gray-800">
                        {{ \Carbon\Carbon::parse($item->tgl_pinjam)->format('d-m-Y') }}
                        &ndash;
                        {{ \Carbon\Carbon::parse($item->tgl_kembali_plan)->format('d-m-Y') }}
                    </p>
                </div>

                @php
                    $badgeMap = [
                        'diajukan'     => ['Menunggu Persetujuan', 'bg-yellow-100 text-yellow-700'],
                        'dipinjam'     => ['Sedang Dipinjam', 'bg-blue-100 text-blue-700'],
                        'dikembalikan' => ['Dikembalikan', 'bg-emerald-100 text-emerald-700'],
                        'selesai'      => ['Selesai', 'bg-emerald-100 text-emerald-700'],
                        'telat'        => ['Telat', 'bg-red-100 text-red-700'],
                        'ditolak'      => ['Ditolak', 'bg-red-100 text-red-700'],
                    ];
                    [$label, $classes] = $badgeMap[$item->status] ?? [ucfirst($item->status), 'bg-gray-100 text-gray-600'];
                @endphp
                <span class="{{ $classes }} text-xs font-semibold px-3 py-1.5 rounded-full whitespace-nowrap">
                    {{ $label }}
                </span>
            </div>

            <p class="text-xs text-gray-400 uppercase font-semibold mb-1">Alat Dipinjam</p>
            <div class="flex flex-wrap gap-2 mb-4">
                @foreach($item->detailPinjams as $detail)
                    <span class="bg-blue-50 text-blue-700 text-xs px-3 py-1 rounded-full">
                        {{ $detail->alat->nama_alat ?? 'Alat Dihapus' }} &times;{{ $detail->jumlah }}
                    </span>
                @endforeach
            </div>

            {{-- Pesan penolakan --}}
            @if($item->status == 'ditolak')
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-sm">
                    <p class="font-semibold">Pengajuan Anda ditolak oleh petugas.</p>
                    <p class="mt-1">
                        Alasan: {{ $item->alasan_penolakan ?: 'Petugas tidak mencantumkan alasan.' }}
                    </p>
                </div>
            @endif

            @if(in_array($item->status, ['dipinjam', 'telat']))
                <form action="{{ route('peminjam.pengembalian.ajukan', $item->id) }}" method="POST"
                    onsubmit="return confirm('Ajukan pengembalian untuk peminjaman ini?')">
                    @csrf
                    <button type="submit"
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2.5 rounded-lg text-sm font-semibold transition">
                        ⟳ Ajukan Pengembalian
                    </button>
                </form>
            @endif
        </div>
    @empty
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-10 text-center text-gray-500">
            Belum ada riwayat peminjaman.
        </div>
    @endforelse

</div>
@endsection