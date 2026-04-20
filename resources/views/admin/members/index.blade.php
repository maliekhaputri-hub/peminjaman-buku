@extends('layouts.app')

@section('title', 'Kelola Anggota')

@section('content')

<div class="card">
    <div class="d-flex justify-between align-center mb-3" style="gap: 1rem;">
        <form action="{{ route('admin.members.index') }}" method="GET" class="search-box" style="max-width: 300px;">
            <i class="fas fa-search"></i>
            <input type="text" name="search" placeholder="Cari anggota..." value="{{ request('search') }}">
            <button type="submit">Cari</button>
        </form>
        <a href="{{ route('admin.members.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Tambah Anggota
        </a>
    </div>

    @if($members->count() > 0)
    <table class="table">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Email</th>
                <th>Role</th>
                <th>Tanggal Daftar</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($members as $key => $member)
            <tr>
                <td>{{ $key + 1 }}</td>
                <td>
                    <div class="d-flex align-center gap-1">
                        <div class="user-avatar small">{{ substr($member->name, 0, 1) }}</div>
                        {{ $member->name }}
                    </div>
                </td>
                <td>{{ $member->email }}</td>
                <td>
                    @if($member->role === 'admin')
                        <span class="badge badge-admin">Admin</span>
                    @else
                        <span class="badge badge-user">User</span>
                    @endif
                </td>
                <td>{{ \Carbon\Carbon::parse($member->created_at)->format('d/m/Y') }}</td>
                <td>
                    <div class="action-buttons">
                        <a href="{{ route('admin.members.edit', $member->id) }}" class="action-btn edit" title="Edit">
                            <i class="fas fa-edit"></i>
                        </a>
                        @if($member->id !== auth()->id())
                        <form action="{{ route('admin.members.destroy', $member->id) }}" method="POST" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="action-btn delete" title="Hapus" onclick="return confirm('Yakin ingin menghapus anggota ini?')">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                        @endif
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    
    <div class="page-info mb-3 text-center">
        Menampilkan {{ $members->firstItem() }} - {{ $members->lastItem() }} dari {{ $members->total() }} total
    </div>
    <div class="pagination">
        {{ $members->links('pagination::bootstrap-5') }}
    </div>
    @else
    <div class="empty-state">
        <i class="fas fa-users"></i>
        <h3>Belum Ada Anggota</h3>
        <p>Anggota akan muncul di sini</p>
    </div>
    @endif
</div>
@endsection
