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
            <input type="date" name="borrow_date" id="borrow_date" class="form-control date-input" required min="{{ date('Y-m-d', strtotime('-7 days')) }}" value="{{ date('Y-m-d') }}">
        </div>

        <div class="form-group">
            <label class="form-label">Tanggal Jatuh Tempo</label>
            <input type="date" name="due_date" id="due_date" class="form-control date-input" required min="{{ date('Y-m-d', strtotime('+1 day')) }}">
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

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const borrowDate = document.getElementById('borrow_date');
    const dueDate = document.getElementById('due_date');
    
    // Update due date min when borrow date changes
    borrowDate.addEventListener('change', function() {
        const borrow = new Date(this.value);
        const dueMin = new Date(borrow);
        dueMin.setDate(borrow.getDate() + 1);
        dueDate.min = dueMin.toISOString().split('T')[0];
        
        // Set default due date to borrow + 7 days
        const defaultDue = new Date(borrow);
        defaultDue.setDate(borrow.getDate() + 7);
        dueDate.value = defaultDue.toISOString().split('T')[0];
    });
    
    // Initial setup
    borrowDate.dispatchEvent(new Event('change'));
});
</script>
@endsection

