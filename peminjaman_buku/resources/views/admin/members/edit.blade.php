@extends('layouts.app')

@section('title', 'Edit Anggota')

@section('content')
<div class="page-header">
    <h1 class="page-title"><i class="fas fa-user-edit"></i> Edit Anggota</h1>
</div>

<div class="card">
    <form method="POST" action="{{ route('admin.members.update', $member->id) }}">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label class="form-label">Nama Lengkap</label>
            <input type="text" name="name" class="form-control" placeholder="Masukkan nama lengkap" required value="{{ old('name', $member->name) }}">
        </div>

        <div class="form-group">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" placeholder="Masukkan email" required value="{{ old('email', $member->email) }}">
        </div>

        <div class="form-group">
            <label class="form-label">Role</label>
            <select name="role" class="form-select" required>
                <option value="user" {{ $member->role === 'user' ? 'selected' : '' }}>User</option>
                <option value="admin" {{ $member->role === 'admin' ? 'selected' : '' }}>Admin</option>
            </select>
        </div>

        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-success">
                <i class="fas fa-save"></i> Update
            </button>
            <a href="{{ route('admin.members.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </form>
</div>
@endsection
