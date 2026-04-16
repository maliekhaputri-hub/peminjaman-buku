@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content')
<div class="dashboard-header">
    <h1 class="dashboard-title">Dashboard Admin</h1>
    <p class="dashboard-subtitle">Selamat datang di panel admin perpustakaan</p>
</div>

<div class="stats-grid">
    <div class="stats-card">
        <div class="stats-icon primary">
            <i class="fas fa-book"></i>
        </div>
        <div class="stats-number">{{ $stats['totalBooks'] }}</div>
        <div class="stats-label">Total Buku</div>
    </div>

    <div class="stats-card">
        <div class="stats-icon secondary">
            <i class="fas fa-users"></i>
        </div>
        <div class="stats-number">{{ $stats['totalMembers'] }}</div>
        <div class="stats-label">Total Anggota</div>
    </div>

    <div class="stats-card">
        <div class="stats-icon info">
            <i class="fas fa-exchange-alt"></i>
        </div>
        <div class="stats-number">{{ $stats['totalTransactions'] }}</div>
        <div class="stats-label">Total Transaksi</div>
    </div>

    <div class="stats-card">
        <div class="stats-icon warning">
            <i class="fas fa-coins"></i>
        </div>
        <div class="stats-number">Rp {{ number_format($stats['totalFines']) }}</div>
        <div class="stats-label">Total Denda</div>
    </div>



    <a href="#buku-terlambat" class="stats-card">
        <div class="stats-icon danger">
            <i class="fas fa-exclamation-triangle{{ $stats['overdueBooks'] > 0 ? '' : ' d-none' }}"></i>
        </div>
        <div class="stats-number{{ $stats['overdueBooks'] > 0 ? ' animate-pulse' : '' }}">{{ $stats['overdueBooks'] }}</div>
        <div class="stats-label">Buku Terlambat{{ $stats['overdueBooks'] > 0 ? '!' : '' }}</div>
    </a>
</div>


<div class="main-content">
    <h3 class="mb-3"><i class="fas fa-clock"></i> Transaksi Terbaru</h3>
    
    @if($recentTransactions->count() > 0)
    <table class="table">
        <thead>
            <tr>
                <th>No</th>
                <th>Anggota</th>
                <th>Buku</th>
                <th>Tanggal Pinjam</th>
                <th>Tanggal Kembali</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($recentTransactions as $key => $transaction)
            <tr>
                <td>{{ $key + 1 }}</td>
                <td>{{ $transaction->user->name }}</td>
                <td>{{ $transaction->book->title }}</td>
                <td>{{ \Carbon\Carbon::parse($transaction->borrow_date)->format('d/m/Y') }}</td>
                <td>
                    @if($transaction->return_date)
                        {{ \Carbon\Carbon::parse($transaction->return_date)->format('d/m/Y') }}
                    @else
                        -
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
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <div class="empty-state">
        <i class="fas fa-inbox"></i>
        <h3>Belum Ada Transaksi</h3>
        <p>Transaksi akan muncul di sini</p>
    </div>
@endif

<div class="main-content mt-8">
    
    @if($overdueTransactions->count() > 0)
    <table class="table">
        <thead>
            <tr>
                <th>No</th>
                <th>Anggota</th>
                <th>Buku</th>
                <th>Tgl Pinjam</th>
                <th>Tgl Jatuh Tempo</th>
                <th>Hari Terlambat</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($overdueTransactions as $key => $transaction)
            <tr>
                <td>{{ $key + 1 }}</td>
                <td>{{ $transaction->user->name ?? '-' }}</td>
                <td>{{ Str::limit($transaction->book->title ?? '-', 30) }}</td>
                <td>{{ \Carbon\Carbon::parse($transaction->borrow_date)->format('d/m/Y') }}</td>
                <td>{{ \Carbon\Carbon::parse($transaction->due_date)->format('d/m/Y') }}</td>
                <td>
                    <span class="badge badge-danger">{{ $transaction->days_late ?? now()->diffInDays($transaction->due_date) }} hari</span>
                </td>
                <td>
                    <a href="{{ route('admin.transactions.edit', $transaction->id) }}" class="btn btn-sm btn-warning">
                        <i class="fas fa-edit"></i>
                    </a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <div class="empty-state">
        <i class="fas fa-check-circle text-success"></i>
        <h3>Tidak Ada Buku Terlambat</h3>
        <p>Semua buku telah dikembalikan tepat waktu</p>
    </div>
    @endif
</div>
</div>
@endsection
