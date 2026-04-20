@extends('layouts.app')

@section('title', 'Tambah Anggota')

@section('content')
<div class="page-header">
    <h1 class="page-title"><i class="fas fa-user-plus"></i> Tambah Anggota</h1>
</div>

<div class="card">
    <form method="POST" action="{{ route('admin.members.store') }}">
        @csrf
        <div class="form-group">
            <label class="form-label">Nama Lengkap</label>
            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" placeholder="Masukkan nama lengkap" required value="{{ old('name') }}">
            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" placeholder="Masukkan email" required value="{{ old('email') }}">
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Password minimal 6 karakter" required minlength="6">
            @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label class="form-label">Konfirmasi Password</label>
            <input type="password" name="password_confirmation" class="form-control" placeholder="Konfirmasi password" required>
        </div>

        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-success" onclick="return confirm('Yakin Mau Menambahkan anggota ini?')">>
                <i class="fas fa-save"></i> Tambah Anggota
            </button>
            <a href="{{ route('admin.members.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </form>
</div>
@endsection

