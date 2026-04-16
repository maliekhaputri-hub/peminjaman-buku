@extends('layouts.app')

@section('title', 'Pinjam Buku')

@section('content')
<div class="page-header">
    <h1 class="page-title"><i class="fas fa-book-reader"></i> Pinjam Buku</h1>
</div>

<div class="card">
    <form method="POST" action="{{ route('user.transactions.borrow') }}">
        @csrf
        <div class="form-group">
            <label class="form-label">Buku</label>
            <select name="book_id" class="form-select" required>
                <option value="">Pilih Buku</option>
                @foreach($books as $book)
                    @if($book->stock > 0)
                        <option value="{{ $book->id }}" {{ request('book_id') == $book->id ? 'selected' : '' }}>
                            {{ $book->title }} - {{ $book->author }} (Stok: {{ $book->stock }})
                        </option>
                    @endif
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label class="form-label">Tanggal Pinjam</label>
            <input type="date" name="borrow_date" class="form-control" required value="{{ date('Y-m-d') }}">
        </div>

        <div class="form-group">
            <label class="form-label">Tanggal Jatuh Tempo</label>
            <input type="date" name="due_date" class="form-control" required value="{{ date('Y-m-d', strtotime('+7 days')) }}">
        </div>

        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-success">
                <i class="fas fa-save"></i> Pinjam Buku
            </button>
            <a href="{{ route('user.books.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </form>
</div>
@endsection
