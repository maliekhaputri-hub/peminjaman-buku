@extends('layouts.app')

@section('title', 'Tambah Buku')

@section('content')
<div class="page-header">
    <h1 class="page-title"><i class="fas fa-plus-circle"></i> Tambah Buku Baru</h1>
</div>

<div class="card">
<form method="POST" action="{{ route('admin.books.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label class="form-label">Judul Buku</label>
            <input type="text" name="title" class="form-control" placeholder="Masukkan judul buku" required value="{{ old('title') }}">
        </div>

        <div class="form-group">
            <label class="form-label">Penulis</label>
            <input type="text" name="author" class="form-control" placeholder="Masukkan nama penulis" required value="{{ old('author') }}">
        </div>

        <div class="form-group">
            <label class="form-label">ISBN</label>
            <input type="text" name="isbn" class="form-control" placeholder="Masukkan ISBN" required value="{{ old('isbn') }}">
        </div>

        <div class="form-group">
            <label class="form-label">Stok</label>
            <input type="number" name="stock" class="form-control" placeholder="Masukkan jumlah stok" required min="0" value="{{ old('stock', 1) }}">
        </div>

        <div class="form-group">
            <label class="form-label">Cover Buku</label>
            <input type="file" name="cover_image" class="form-control" accept="image/*">
            <small class="form-text text-muted">Ukuran maksimal 5MB (JPG, PNG, GIF)</small>
        </div>

        <div class="form-group">
            <label class="form-label">Deskripsi</label>
            <textarea name="description" class="form-control" rows="4" placeholder="Masukkan deskripsi buku">{{ old('description') }}</textarea>
        </div>

        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-success" onclick="return confirm('Yakin Mau Menambahkan Buku Ini?')">
                <i class="fas fa-save"></i> Simpan Buku
            </button>
            <a href="{{ route('admin.books.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </form>
</div>
@endsection
