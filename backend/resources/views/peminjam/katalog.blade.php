@extends('layouts.app')

@section('title', 'Katalog Alat - Peminjam')
@section('header-title', 'Katalog & Pengajuan Peminjaman')

@section('content')
<div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">

    @if(session('success'))
        <div class="mb-4 bg-green-50 border border-green-200 text-green-800 p-3 rounded-lg text-sm">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="mb-4 bg-red-50 border border-red-200 text-red-800 p-3 rounded-lg text-sm">
            {{ session('error') }}
        </div>
    @endif

    <div class="flex flex-col sm:flex-row gap-3 mb-6">
        <form method="GET" action="{{ route('peminjam.katalog') }}" class="flex-1 flex gap-2">
            <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama alat..."
                class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            <select name="kategori" onchange="this.form.submit()"
                class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">Semua Kategori</option>
                @foreach($kategoris as $kat)
                    <option value="{{ $kat->id }}" {{ (string)$kategoriId === (string)$kat->id ? 'selected' : '' }}>
                        {{ $kat->nama_kategori }}
                    </option>
                @endforeach
            </select>
            <button type="submit" class="bg-gray-800 hover:bg-gray-900 text-white px-4 py-2 rounded-lg text-sm font-semibold transition">
                Cari
            </button>
        </form>
    </div>

    <form action="{{ route('peminjam.peminjaman.ajukan') }}" method="POST" id="form-pinjam">
        @csrf

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
            @forelse($alats as $alat)
                <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition">
                    <div class="w-full h-32 bg-gray-100 rounded-lg mb-3 flex items-center justify-center text-gray-400 text-3xl overflow-hidden">
                        @if($alat->gambar)
                            <img src="{{ asset($alat->gambar) }}" alt="{{ $alat->nama_alat }}" class="w-full h-full object-cover">
                        @else
                            🔧
                        @endif
                    </div>

                    <div class="flex justify-between items-start mb-1">
                        <h4 class="font-semibold text-gray-800">{{ $alat->nama_alat }}</h4>
                        <span class="bg-green-100 text-green-700 text-xs font-semibold px-2 py-0.5 rounded-full whitespace-nowrap ml-2">
                            Stok {{ $alat->stok }}
                        </span>
                    </div>
                    <p class="text-xs text-gray-400 mb-3">{{ $alat->kategori->nama_kategori ?? '-' }}</p>

                    <label class="flex items-center gap-2 text-sm text-gray-700 mb-2">
                        <input type="checkbox" name="alat_id[]" value="{{ $alat->id }}"
                            class="alat-checkbox rounded border-gray-300"
                            {{ $alat->stok < 1 ? 'disabled' : '' }}>
                        Pilih alat ini
                    </label>

                    <input type="number" name="jumlah[]" min="1" max="{{ $alat->stok }}" value="1"
                        {{ $alat->stok < 1 ? 'disabled' : '' }}
                        class="w-full border border-gray-300 rounded-lg px-3 py-1.5 text-sm">

                    @if($alat->stok < 1)
                        <p class="text-xs text-red-500 mt-1">Stok habis</p>
                    @endif
                </div>
            @empty
                <p class="col-span-full text-center text-gray-500 py-6">Tidak ada alat ditemukan.</p>
            @endforelse
        </div>

        <div class="border-t border-gray-200 pt-4 grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Tanggal Pinjam</label>
                <input type="date" name="tgl_pinjam" required
                    value="{{ old('tgl_pinjam', now()->format('Y-m-d')) }}"
                    min="{{ now()->format('Y-m-d') }}"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Rencana Kembali</label>
                <input type="date" name="tgl_kembali_plan" required
                    value="{{ old('tgl_kembali_plan') }}"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
        </div>

        <div class="flex items-center justify-between">
            <span id="jumlah-dipilih" class="text-sm text-gray-500">0 alat dipilih</span>
            <button type="submit" id="btn-ajukan" disabled
                class="bg-gray-300 text-gray-500 px-6 py-2.5 rounded-lg text-sm font-semibold cursor-not-allowed transition">
                Ajukan Peminjaman
            </button>
        </div>
    </form>
</div>

<script>
    const checkboxes = document.querySelectorAll('.alat-checkbox');
    const jumlahInputs = document.querySelectorAll('input[name="jumlah[]"]');
    const counter = document.getElementById('jumlah-dipilih');
    const btnAjukan = document.getElementById('btn-ajukan');
    const formPinjam = document.getElementById('form-pinjam');

    function updateCounter() {
        const jumlah = document.querySelectorAll('.alat-checkbox:checked').length;
        counter.textContent = `${jumlah} alat dipilih`;

        if (jumlah > 0) {
            btnAjukan.disabled = false;
            btnAjukan.classList.remove('bg-gray-300', 'text-gray-500', 'cursor-not-allowed');
            btnAjukan.classList.add('bg-blue-600', 'hover:bg-blue-700', 'text-white');
        } else {
            btnAjukan.disabled = true;
            btnAjukan.classList.add('bg-gray-300', 'text-gray-500', 'cursor-not-allowed');
            btnAjukan.classList.remove('bg-blue-600', 'hover:bg-blue-700', 'text-white');
        }
    }

    // Paksa jumlah nggak boleh lebih dari stok / kurang dari 1, walau diketik manual
    function clampJumlah(input) {
        const max = parseInt(input.getAttribute('max')) || 1;
        let val = parseInt(input.value);

        if (isNaN(val) || val < 1) val = 1;
        if (val > max) val = max;

        input.value = val;
    }

    jumlahInputs.forEach(input => {
        input.addEventListener('input', () => clampJumlah(input));
        input.addEventListener('blur', () => clampJumlah(input));
    });

    checkboxes.forEach(cb => cb.addEventListener('change', updateCounter));

    // Jaga-jaga terakhir sebelum submit, clamp ulang semua input jumlah
    formPinjam.addEventListener('submit', function () {
        jumlahInputs.forEach(input => clampJumlah(input));
    });
</script>
@endsection