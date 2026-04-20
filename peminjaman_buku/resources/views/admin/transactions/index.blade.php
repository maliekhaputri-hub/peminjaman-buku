@extends('layouts.app')

@section('title', 'Kelola Transaksi')

@section('content')
<div class="page-header">
    <h1 class="page-title"><i class="fas fa-exchange-alt"></i> Kelola Transaksi</h1>
    <a href="{{ route('admin.transactions.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Tambah Transaksi
    </a>
</div>

<div class="card">
    <div class="stats-grid mb-4">
        <div class="stat-card">
            <i class="fas fa-coins"></i>
            <div>
                <h3>Rp {{ number_format($totalFines ?? 0) }}</h3>
                <p>Total Denda</p>
            </div>
        </div>
        <div class="stat-card warning">
            <i class="fas fa-exclamation-triangle"></i>
            <div>
                <h3>Rp {{ number_format($pendingFines ?? 0) }}</h3>
                <p>Denda Belum Dibayar</p>
            </div>
        </div>
    </div>

    <div class="d-flex justify-between align-center mb-3" style="flex-wrap: wrap; gap: 1rem;">
        <form action="{{ route('admin.transactions.index') }}" method="GET" class="search-box" style="max-width: 300px;">
            <i class="fas fa-search"></i>
            <input type="text" name="search" placeholder="Cari transaksi..." value="{{ request('search') }}">
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
                <th>Anggota</th>
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
                <td>{{ $transaction->user->name }}</td>
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
                <td>Rp {{ number_format(abs($transaction->fine_amount)) }}</td>
                <td>{{ $transaction->days_late }} hari</td>
                <td>
                    <div class="action-buttons">
                        @if($transaction->fine_amount > 0)
                        <form action="{{ route('admin.transactions.payFine', $transaction->id) }}" method="POST" style="display: inline;">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="action-btn pay-fine" title="Bayar Denda">
                                <i class="fas fa-coins"></i>
                            </button>
                        </form>
                        @endif
                        @if($transaction->status !== 'returned')
                        <form action="{{ route('admin.transactions.updateStatus', $transaction->id) }}" method="POST" style="display: inline;">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status" value="returned">
                            <button type="submit" class="action-btn view" title="Kembalikan">
                                <i class="fas fa-check"></i>
                            </button>
                        </form>
                        @endif
                        <form action="{{ route('admin.transactions.destroy', $transaction->id) }}" method="POST" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="action-btn delete" title="Hapus" onclick="return confirm('Yakin ingin menghapus transaksi ini?')">
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
        {{ $transactions->links() }}
    </div>
    @else
    <div class="empty-state">
        <i class="fas fa-exchange-alt"></i>
        <h3>Belum Ada Transaksi</h3>
        <p>Transaksi akan muncul di sini</p>
    </div>
    @endif
</div>
@endsection
