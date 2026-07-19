@extends('layouts.app')

@section('title', 'Tambah OPD - SIMODU KMC')
@section('page-title')
    <a href="{{ route('admin.opd.index') }}" class="btn-back me-2"><i class="fas fa-arrow-left"></i></a> 
    Tambah OPD
@endsection

@section('content')
<div class="row">
    <div class="col-lg-8 col-xl-6 mx-auto">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-header bg-white border-0 py-3 rounded-top-4">
                <h5 class="mb-0 fw-bold text-dark d-flex align-items-center">
                    <i class="fa-solid fa-plus-circle text-primary me-2"></i> Form Tambah OPD
                </h5>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('admin.opd.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama OPD</label>
                        <input type="text" name="name" class="form-control rounded-3 @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="Masukkan nama OPD" required style="height: 42px; border-color: #cbd5e1;">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <h6 class="fw-bold mt-4 mb-3 text-secondary border-bottom pb-2 d-flex align-items-center">
                        <i class="fa-solid fa-user-shield me-2 text-primary"></i> Informasi Akun OPD
                    </h6>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Username</label>
                        <input type="text" name="username" class="form-control rounded-3 @error('username') is-invalid @enderror" value="{{ old('username') }}" placeholder="Masukkan username login" required style="height: 42px; border-color: #cbd5e1;">
                        @error('username')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Email</label>
                        <input type="email" name="email" class="form-control rounded-3 @error('email') is-invalid @enderror" value="{{ old('email') }}" placeholder="Masukkan email aktif" required style="height: 42px; border-color: #cbd5e1;">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold">Password</label>
                        <input type="password" name="password" class="form-control rounded-3 @error('password') is-invalid @enderror" placeholder="Minimal 6 karakter" required style="height: 42px; border-color: #cbd5e1;">
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <a href="{{ route('admin.opd.index') }}" class="btn btn-light border rounded-pill px-4 py-2 fw-semibold text-secondary" style="height: 42px;">
                            <i class="fa-solid fa-arrow-left me-2"></i> Kembali
                        </a>
                        <button type="submit" class="btn btn-primary rounded-pill px-4 py-2 fw-semibold" style="height: 42px;">
                            <i class="fa-solid fa-save me-2"></i> Simpan OPD
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
