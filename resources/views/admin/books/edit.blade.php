@extends('layouts.app')

@section('title', 'Edit Buku')

@section('content')
<div class="page-header">
    <h1 class="page-title"><i class="fas fa-edit"></i> Edit Buku - {{ $book->title }}</h1>
</div>

<div class="card">
<form method="POST" action="{{ route('admin.books.update', $book->id) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label class="form-label">Judul Buku</label>
            <input type="text" name="title" class="form-control" placeholder="Masukkan judul buku" required value="{{ old('title', $book->title) }}">
        </div>

        <div class="form-group">
            <label class="form-label">Penulis</label>
            <input type="text" name="author" class="form-control" placeholder="Masukkan nama penulis" required value="{{ old('author', $book->author) }}">
        </div>

        <div class="form-group">
            <label class="form-label">ISBN</label>
            <input type="text" name="isbn" class="form-control" placeholder="Masukkan ISBN" required value="{{ old('isbn', $book->isbn) }}">
        </div>

        <div class="form-group">
            <label class="form-label">Stok</label>
            <input type="number" name="stock" class="form-control" placeholder="Masukkan jumlah stok" required min="0" value="{{ old('stock', $book->stock) }}">
        </div>

        <div class="form-group">
            <label class="form-label">Cover Buku</label>
            @if($book->cover_image_url)
                <div class="mb-2">
                    <img src="{{ $book->cover_image_url }}" alt="Current cover" style="max-width:200px;max-height:300px;object-fit:cover;">
                    <p class="text-muted small">Gambar saat ini</p>
                </div>
            @endif
            <input type="file" name="cover_image" class="form-control" accept="image/*">
            <small class="form-text text-muted">Ukuran maksimal 5MB (JPG, PNG, GIF). Biarkan kosong untuk tetap gunakan gambar lama.</small>
        </div>

        <div class="form-group">
            <label class="form-label">Deskripsi</label>
            <textarea name="description" class="form-control" rows="4" placeholder="Masukkan deskripsi buku">{{ old('description', $book->description) }}</textarea>
        </div>

        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-success" onclick="return confirm('Yakin Mau Mengedit Buku ini?')">
                <i class="fas fa-save"></i> Update Buku
            </button>
            <a href="{{ route('admin.books.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </form>
</div>
@endsection

