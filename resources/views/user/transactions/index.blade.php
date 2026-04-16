@extends('layouts.app')

@section('title', 'Peminjaman Saya')

@section('content')
<div class="page-header">
    <h1 class="page-title"><i class="fas fa-history"></i> Peminjaman Saya</h1>
</div>

<div class="card">
    <div class="stats-grid mb-4">
        <div class="stat-card warning">
            <i class="fas fa-coins"></i>
            <div>
                <h3>Rp {{ number_format($totalFinesOwed ?? 0) }}</h3>
                <p>Total Denda Saya</p>
            </div>
        </div>
    </div>

    <div class="d-flex justify-between align-center mb-3" style="flex-wrap: wrap; gap: 1rem;">
        <form action="{{ route('user.transactions.index') }}" method="GET" class="search-box" style="max-width: 300px;">
            <i class="fas fa-search"></i>
            <input type="text" name="search" placeholder="Cari buku..." value="{{ request('search') }}">
            <button type="submit">Cari</button>
        </form>
        
        <div class="filter-dropdown">
            <select name="status" onchange="this.form.submit()">
                <option value="">Semua Status</option>
                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="borrowed" {{ request('status') === 'borrowed' ? 'selected' : '' }}>Dipinjam</option>
                <option value="returned" {{ request('status') === 'returned' ? 'selected' : '' }}>Dikembalikan</option>
            </select>
        </div>
    </div>

    @if($transactions->count() > 0)
    <table class="table">
<thead>
            <tr>
                <th>No</th>
                <th>Buku</th>
                <th>Tanggal Pinjam</th>
                <th>Tanggal Jatuh Tempo</th>
                <th>Status</th>
                <th>Denda</th>
                <th>Hari Telat</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transactions as $key => $transaction)
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
                <td>Rp {{ number_format($transaction->fine_amount) }}</td>
                <td>{{ $transaction->days_late }} hari</td>
                <td>
                    <div class="action-buttons">
                        @if($transaction->fine_amount > 0)
                        <form action="{{ route('user.transactions.payFine', $transaction->id) }}" method="POST" style="display: inline;">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="action-btn pay-fine" title="Bayar Denda">
                                <i class="fas fa-coins"></i>
                            </button>
                        </form>
                        @endif
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
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    
    <div class="pagination">
        {{ $transactions->links() }}
    </div>
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
