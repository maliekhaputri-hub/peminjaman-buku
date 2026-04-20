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
    <div class="books-grid">
        @foreach($books as $book)
        <div class="book-card">
            <div class="book-card-image" @if($book->cover_image_url) style="background-image: url('{{ $book->cover_image_url }}');" @endif>
                @if(!$book->cover_image_url)
                    <div class="book-placeholder">
                        <i class="fas fa-book"></i>
                    </div>
                @endif>
            </div>
            <div class="book-card-content">
                <h4 class="book-card-title">{{ Str::limit($book->title, 60) }}</h4>
                <p class="book-card-author">by {{ $book->author }}</p>
                <div class="book-stock-badge {{ $book->stock > 0 ? 'available' : 'unavailable' }}">
                    <i class="fas fa-{{ $book->stock > 0 ? 'check-circle' : 'times-circle' }}"></i>
                    {{ $book->stock > 0 ? $book->stock . ' Tersedia' : 'Stok Habis' }}
                </div>
                @if($book->stock > 0)
                    <a href="{{ route('user.transactions.create', ['book_id' => $book->id]) }}" class="btn btn-primary">
                        <i class="fas fa-book-reader"></i> Pinjam Sekarang
                    </a>
                @else
                    <button class="btn btn-secondary" disabled>
                        <i class="fas fa-times"></i> Buku Habis
                    </button>
                @endif
            </div>
        </div>
        @endforeach
    </div>
    
    <div class="page-info mb-3 text-center">
        Menampilkan {{ $books->firstItem() }} - {{ $books->lastItem() }} dari {{ $books->total() }} total
    </div>
    <div class="pagination" style="justify-content: center; margin-top: 2rem;">
        {{ $books->links('pagination::bootstrap-5') }}
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
