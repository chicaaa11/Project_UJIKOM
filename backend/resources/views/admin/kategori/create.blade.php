<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Kategori - Panel Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .sidebar { width: 250px; height: 100vh; position: fixed; background: #1a2232; color: #fff; }
        .sidebar a { color: #a0aec0; text-decoration: none; padding: 12px 20px; display: block; }
        .sidebar a:hover, .sidebar a.active { background: #2d3748; color: #fff; }
        .main-content { margin-left: 250px; padding: 30px; }
    </style>
</head>
<body>

    <!-- Sidebar -->
    <div class="sidebar">
        <h4 class="p-3 fw-bold">PANEL ADMIN</h4>
        <a href="{{ route('admin.dashboard') }}">Dashboard</a>
        <a href="{{ route('admin.alat.index') }}">Kelola Alat</a>
        <a href="{{ route('admin.users.index') }}">Kelola User</a>
        <a href="{{ route('admin.kategori.index') }}" class="active">Kelola Kategori</a>
        <a href="{{ route('admin.peminjaman.index') }}">Kelola Peminjaman</a>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="fw-bold">Manajemen Kategori Alat</h3>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-danger">Logout</button>
            </form>
        </div>

        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-body p-4">
                <h5 class="fw-bold mb-4">Tambah Kategori Baru</h5>

                <form action="{{ route('admin.kategori.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="nama_kategori" class="form-label font-weight-bold">Nama Kategori</label>
                        <input type="text" name="nama_kategori" id="nama_kategori" 
                               class="form-control @error('nama_kategori') is-invalid @enderror" 
                               placeholder="Masukkan nama kategori..." value="{{ old('nama_kategori') }}" required>
                        
                        @error('nama_kategori')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary px-4">Simpan</button>
                        <a href="{{ route('admin.kategori.index') }}" class="btn btn-secondary px-4">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

</body>
</html>