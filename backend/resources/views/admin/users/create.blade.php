@extends('layouts.app')

@section('content')

<div class="container mt-5">

    <h2>Tambah User</h2>

    <form action="{{ url('/admin/users') }}" method="POST">
        
        @csrf

        <div class="mb-3">
            <label>Nama</label>
            <input type="text" name="name" class="form-control">
        </div>

        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" class="form-control">
        </div>

        <div class="mb-3">
            <label>Password</label>
            <input type="password" name="password" class="form-control">
        </div>

        <div class="mb-3">
            <label>Role</label>

            <select name="role" class="form-control">
                <option value="admin">Admin</option>
                <option value="petugas">Petugas</option>
                <option value="guru">Guru</option>
            </select>
        </div>

        <button class="btn btn-success">
            Simpan
        </button>

    </form>

</div>

@endsection