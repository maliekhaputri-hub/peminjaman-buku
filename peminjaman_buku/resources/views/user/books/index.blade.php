@extends('layouts.app')

@section('title', 'Buku Tersedia')

@section('content')
<div class="page-header">
    <h1 class="page-title"><i class="fas fa-book"></i> Buku Tersedia</h1>
</div>

<div class="card">
    <div class="d-flex justify-between align-center mb-3">
        <form action="{{ route('user.books.index') }}" method="GET" class="search-box" style="max-width: 300px;">
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
                    @if($book->stock > 0)
                    <a href="{{ route('user.transactions.create', ['book_id' => $book->id]) }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-book-reader"></i> Pinjam
                    </a>
                    @else
                    <button class="btn btn-secondary btn-sm" disabled>
                        <i class="fas fa-times"></i> Tidak Tersedia
                    </button>
                    @endif
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
        <h3>Tidak Ada Buku</h3>
        <p>Buku tidak tersedia saat ini</p>
    </div>
    @endif
</div>
@endsection
