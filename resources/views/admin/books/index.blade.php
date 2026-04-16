@extends('layouts.app')

@section('title', 'Kelola Buku')

@section('content')
<div class="page-header">
    <h1 class="page-title"><i class="fas fa-book"></i> Kelola Buku</h1>
    <a href="{{ route('admin.books.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Tambah Buku
    </a>
</div>

<div class="card">
    <div class="d-flex justify-between align-center mb-3">
        <form action="{{ route('admin.books.index') }}" method="GET" class="search-box" style="max-width: 300px;">
            <i class="fas fa-search"></i>
            <input type="text" name="search" placeholder="Cari buku..." value="{{ request('search') }}">
            <button type="submit">Cari</button>
        </form>
    </div>

    @if($books->count() > 0)
    <table class="table">
        <thead>
            <tr>
                <th>No</th>
                <th>Judul</th>
                <th>Penulis</th>
                <th>ISBN</th>
                <th>Stok</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
@foreach($books as $book)
            <tr>
                <td>{{ $books->firstItem() + $loop->index }}</td>
                <td>{{ $book->title }}</td>

                <td>{{ $book->author }}</td>
                <td>{{ $book->isbn }}</td>
                <td>
                    @if($book->stock > 0)
                        <span class="badge badge-borrowed">{{ $book->stock }}</span>
                    @else
                        <span class="badge badge-overdue">Habis</span>
                    @endif
                </td>
                <td>
                    <div class="action-buttons">
                        <a href="{{ route('admin.books.edit', $book->id) }}" class="action-btn edit" title="Edit">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('admin.books.destroy', $book->id) }}" method="POST" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="action-btn delete" title="Hapus" onclick="return confirm('Yakin ingin menghapus buku ini?')">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    
    <div class="pagination">
        {{ $books->links('pagination::simple-bootstrap-5') }}
    </div>
    @else
    <div class="empty-state">
        <i class="fas fa-book"></i>
        <h3>Belum Ada Buku</h3>
        <p>Silakan tambah buku pertama Anda</p>
    </div>
    @endif
</div>
@endsection
