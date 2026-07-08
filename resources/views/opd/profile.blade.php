@extends('layouts.app')

@section('title', 'Profil Pengguna')
@section('page-title')
    <i class="fa-solid fa-id-card text-primary me-2"></i> Profil OPD
@endsection

@section('content')
<form action="{{ route('opd.profile.update') }}" method="POST" enctype="multipart/form-data" class="container-fluid px-0">
    @csrf

    @if ($errors->any())
        <div class="alert alert-danger border-0 rounded-4 shadow-sm mb-4">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="row g-4">
        <!-- Left Column: Form Fields -->
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white border-0 py-3 rounded-top-4 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 fw-bold text-dark"><i class="fas fa-user-edit text-primary me-2"></i> Edit Profil & Keamanan</h6>
                    <a href="{{ route('opd.dashboard') }}" class="btn btn-sm btn-light border rounded-pill px-3 fw-semibold text-secondary">
                        <i class="fa-solid fa-arrow-left me-1"></i> Kembali
                    </a>
                </div>
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-3 text-muted border-bottom pb-2">Informasi Akun</h6>
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama Lengkap/Nama Instansi</label>
                        <input type="text" name="name" class="form-control rounded-3" value="{{ old('name', $user->name) }}" required style="height: 42px; border-color: #cbd5e1;">
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold">Email / Username</label>
                        <input type="email" name="email" class="form-control rounded-3" value="{{ old('email', $user->email) }}" required style="height: 42px; border-color: #cbd5e1;">
                    </div>

                    <h6 class="fw-bold mb-3 text-muted border-bottom pb-2">Ubah Password <span class="fw-normal small">(Opsional)</span></h6>
                    
                    <div class="alert alert-info border-0 bg-info bg-opacity-10 text-info small rounded-3 alert-permanent">
                        <i class="fas fa-info-circle me-1"></i> Biarkan kosong jika tidak ingin mengubah password saat ini.
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Password Baru</label>
                        <input type="password" name="password" class="form-control rounded-3" placeholder="Minimal 6 karakter" style="height: 42px; border-color: #cbd5e1;">
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold">Konfirmasi Password Baru</label>
                        <input type="password" name="password_confirmation" class="form-control rounded-3" placeholder="Ulangi password baru" style="height: 42px; border-color: #cbd5e1;">
                    </div>

                    <button type="submit" class="btn btn-primary rounded-pill px-4 py-2 fw-semibold" style="height: 42px;">
                        <i class="fas fa-save me-2"></i> Simpan Perubahan
                    </button>
                </div>
            </div>
        </div>

        <!-- Right Column: Profile Picture -->
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white border-0 py-3 rounded-top-4">
                    <h6 class="m-0 fw-bold text-dark"><i class="fa-solid fa-image text-primary me-2"></i> Foto Profil</h6>
                </div>
                <div class="card-body p-4 text-center">
                    <div class="mb-4">
                        @if(Auth::user()->profile_photo)
                            <img src="{{ asset('storage/' . Auth::user()->profile_photo) }}" alt="Profile" class="rounded-circle shadow-sm border p-1" style="width: 140px; height: 140px; object-fit: cover;">
                        @else
                            <div class="bg-primary-subtle text-primary rounded-circle d-flex align-items-center justify-content-center mx-auto shadow-sm border border-primary border-opacity-25" style="width: 140px; height: 140px; font-size: 50px;">
                                <i class="fas fa-user"></i>
                            </div>
                        @endif
                    </div>
                    
                    <div class="mb-3 text-start">
                        <label class="form-label fw-semibold">Unggah Foto Baru</label>
                        <label class="d-block m-0">
                            <div class="form-control rounded-3 d-flex align-items-center @error('profile_photo') is-invalid @enderror" style="cursor: pointer; padding: 0.4rem; height: 42px; border-color: #cbd5e1;">
                                <div class="bg-secondary text-white px-3 py-2 rounded me-3 small fw-bold"><i class="fas fa-image me-1"></i> Pilih Foto</div>
                                <span class="text-muted small text-truncate" style="flex: 1;" id="filename-profile-opd">Belum ada file...</span>
                            </div>
                            <input type="file" name="profile_photo" id="profile_photo" class="d-none" accept=".jpg,.jpeg,.png" onchange="document.getElementById('filename-profile-opd').innerText = this.files[0] ? this.files[0].name : 'Belum ada file...'">
                        </label>
                        <div class="form-text mt-2 small text-muted"><i class="fas fa-info-circle me-1 text-primary"></i> Hanya format <strong>JPG, JPEG, PNG</strong>. Maksimal <strong>2MB</strong>.</div>
                        @error('profile_photo')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
</div>
@endsection
