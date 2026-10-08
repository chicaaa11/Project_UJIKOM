@extends('layouts.app')

@section('content')

<div class="container mt-5">

    <h2>Edit User</h2>

    <form action="{{ url('/admin/users/'.$user->id) }}" method="POST">
        
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label>Nama</label>
            <input type="text" 
                   name="name" 
                   value="{{ $user->name }}" 
                   class="form-control">
        </div>

        <div class="mb-3">
            <label>Email</label>
            <input type="email" 
                   name="email" 
                   value="{{ $user->email }}" 
                   class="form-control">
        </div>

        <div class="mb-3">
            <label>Role</label>

            <select name="role" class="form-control">
                <option value="admin" 
                    {{ $user->role == 'admin' ? 'selected' : '' }}>
                    Admin
                </option>
                <option value="petugas" 
                    {{ $user->role == 'petugas' ? 'selected' : '' }}>
                    Petugas
                </option>
                <option value="guru" 
                    {{ $user->role == 'guru' ? 'selected' : '' }}>
                    Guru
                </option>
            </select>
        </div>

        <button class="btn btn-primary">
            Update
        </button>

    </form>

</div>

@endsection