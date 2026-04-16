@extends('layouts.app')

@section('title', 'Edit Transaksi - ' . $transaction->id)

@section('content')
<div class="page-header">
    <h1 class="page-title">
        <i class="fas fa-edit"></i> Edit Transaksi #{{ $transaction->id }}
    </h1>
</div>

<div class="card">
    <form action="{{ route('admin.transactions.update', $transaction) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="form-group">
            <label class="form-label">Anggota</label>
            <select name="user_id" class="form-select" required>
                @foreach($users as $user)
                    <option value="{{ $user->id }}" {{ $transaction->user_id == $user->id ? 'selected' : '' }}>
                        {{ $user->name }} ({{ $user->email }})
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label class="form-label">Buku</label>
            <select name="book_id" class="form-select" required>
                @foreach($books as $book)
                    <option value="{{ $book->id }}" {{ $transaction->book_id == $book->id ? 'selected' : '' }}>
                        {{ $book->title }} (Stock: {{ $book->stock }})
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label class="form-label">Tanggal Pinjam</label>
            <input type="date" name="borrow_date" class="form-control" value="{{ $transaction->borrow_date->format('Y-m-d') }}" required>
        </div>

        <div class="form-group">
            <label class="form-label">Tanggal Jatuh Tempo</label>
            <input type="date" name="due_date" class="form-control" value="{{ $transaction->due_date->format('Y-m-d') }}" required>
        </div>

        <div class="form-group">
            <label class="form-label">Status</label>
            <select name="status" class="form-select" required>
                <option value="pending" {{ $transaction->status == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="borrowed" {{ $transaction->status == 'borrowed' ? 'selected' : '' }}>Dipinjam</option>
                <option value="returned" {{ $transaction->status == 'returned' ? 'selected' : '' }}>Dikembalikan</option>
                <option value="overdue" {{ $transaction->status == 'overdue' ? 'selected' : '' }}>Terlambat</option>
            </select>
        </div>

        <div class="form-group">
            <label class="form-label">Hari Terlambat</label>
            <input type="number" name="days_late" class="form-control" value="{{ $transaction->days_late }}" readonly>
        </div>

        <div class="form-group">
            <label class="form-label">Denda (Rp)</label>
            <input type="number" name="fine_amount" class="form-control" value="{{ $transaction->fine_amount }}" readonly>
        </div>

        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Update Transaksi
            </button>
            <a href="{{ route('admin.transactions.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </form>
</div>
@endsection
