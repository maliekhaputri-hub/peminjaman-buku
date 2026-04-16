@extends('layouts.app')

@section('title', 'Dashboard User')

@section('content')
<div class="dashboard-header">
    <h1 class="dashboard-title">Dashboard</h1>
    <p class="dashboard-subtitle">Selamat datang, {{ Auth::user()->name }}!</p>
</div>

<div class="stats-grid">
    <div class="stats-card">
        <div class="stats-icon primary">
            <i class="fas fa-book"></i>
        </div>
        <div class="stats-number">{{ $availableBooks }}</div>
        <div class="stats-label">Buku Tersedia</div>
    </div>

    <div class="stats-card">
        <div class="stats-icon warning">
            <i class="fas fa-book-reader"></i>
        </div>
        <div class="stats-number">{{ $borrowedCount }}</div>
        <div class="stats-label">Buku Dipinjam</div>
    </div>

    <div class="stats-card">
        <div class="stats-icon success">
            <i class="fas fa-check-circle"></i>
        </div>
        <div class="stats-number">{{ $returnedCount }}</div>
        <div class="stats-label">Buku Dikembalikan</div>
    </div>
    <div class="stats-card warning">
        <div class="stats-icon warning">
            <i class="fas fa-coins"></i>
        </div>
        <div class="stats-number">Rp {{ number_format($totalFinesOwed) }}</div>
        <div class="stats-label">Denda Saya</div>
    </div>
</div>

<div class="main-content">
    <div class="d-flex justify-between align-center mb-3">
        <h3><i class="fas fa-history"></i> Riwayat Peminjaman</h3>
        <a href="{{ route('user.books.index') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Pinjam Buku
        </a>
    </div>
    
    @if($myTransactions->count() > 0)
    <table class="table">
        <thead>
            <tr>
                <th>No</th>
                <th>Buku</th>
                <th>Tanggal Pinjam</th>
                <th>Tanggal Kembali</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($myTransactions as $key => $transaction)
            <tr>
                <td>{{ $key + 1 }}</td>
                <td>{{ $transaction->book->title }}</td>
                <td>{{ \Carbon\Carbon::parse($transaction->borrow_date)->format('d/m/Y') }}</td>
                <td>
                    @if($transaction->return_date)
                        {{ \Carbon\Carbon::parse($transaction->return_date)->format('d/m/Y') }}
                    @else
                        {{ \Carbon\Carbon::parse($transaction->due_date)->format('d/m/Y') }}
                    @endif
                </td>
                <td>
                    @if($transaction->status === 'borrowed')
                        @if(\Carbon\Carbon::parse($transaction->due_date)->isPast())
                            <span class="badge badge-overdue"><i class="fas fa-exclamation-triangle"></i> Terlambat</span>
                        @else
                            <span class="badge badge-borrowed"><i class="fas fa-book"></i> Dipinjam</span>
                        @endif
                    @elseif($transaction->status === 'returned')
                        <span class="badge badge-returned"><i class="fas fa-check"></i> Dikembalikan</span>
                    @else
                        <span class="badge badge-pending"><i class="fas fa-clock"></i> Pending</span>
                    @endif
                </td>
                <td>
                    @if($transaction->status === 'borrowed')
                    <form action="{{ route('user.transactions.return', $transaction->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-success btn-sm">
                            <i class="fas fa-undo"></i> Kembalikan
                        </button>
                    </form>
                    @else
                    -
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <div class="empty-state">
        <i class="fas fa-book-open"></i>
        <h3>Belum Ada Peminjaman</h3>
        <p>Silakan pilih buku untuk dipinjam</p>
        <a href="{{ route('user.books.index') }}" class="btn btn-primary mt-2">
            <i class="fas fa-book"></i> Lihat Buku Tersedia
        </a>
    </div>
    @endif
</div>
@endsection
