@extends('layouts.app')

@section('title', 'Data User')
@section('header-title', 'Kelola User')

@section('content')
<div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">

    <!-- HEADER DATA USER & TOMBOL TAMBAH -->
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-bold text-gray-800">Data User</h2>
        <a href="{{ route('admin.users.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-4 py-2 rounded-lg transition shadow-sm text-sm">
            + Tambah User
        </a>
    </div>

    <!-- PESAN SUKSES -->
    @if(session('success'))
        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    <!-- TABEL DATA USER -->
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-200 text-xs font-semibold text-gray-600 uppercase tracking-wider">
                    <th class="py-3 px-4">No</th>
                    <th class="py-3 px-4">Nama</th>
                    <th class="py-3 px-4">Email</th>
                    <th class="py-3 px-4">Role</th>
                    <th class="py-3 px-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 text-sm">
                @forelse($users as $index => $user)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="py-3 px-4 font-medium text-gray-700">{{ $index + 1 }}</td>
                        <td class="py-3 px-4 text-gray-800 font-semibold">{{ $user->name }}</td>
                        <td class="py-3 px-4 text-gray-600">{{ $user->email }}</td>
                        <td class="py-3 px-4">
                            <span class="px-3 py-1 text-xs font-semibold rounded-full 
                                {{ $user->role == 'admin' ? 'bg-purple-100 text-purple-700' : ($user->role == 'petugas' ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-700') }}">
                                {{ $user->role }}
                            </span>
                        </td>
                        <td class="py-3 px-4 text-center">
                            <div class="flex justify-center items-center gap-2">
                                <a href="{{ route('admin.users.edit', $user->id) }}" class="bg-amber-500 hover:bg-amber-600 text-white text-xs font-semibold px-3 py-1.5 rounded transition">
                                    Edit
                                </a>
                                <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus user ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-red-500 hover:bg-red-600 text-white text-xs font-semibold px-3 py-1.5 rounded transition">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-6 text-gray-500">Belum ada data user.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>
@endsection