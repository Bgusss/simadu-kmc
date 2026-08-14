@extends('layouts.app')

@section('title', 'Profil Admin - SIMODU KMC')
@section('page-title')
    <i class="fa-solid fa-user-circle text-primary me-2"></i> Profil Admin
@endsection

@section('content')
<form action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data" class="container-fluid px-0">
    @csrf


    <div class="row g-4">
        <!-- Left Column: Form Fields -->
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white border-0 py-3 rounded-top-4">
                    <h5 class="mb-0 fw-bold text-dark"><i class="fa-solid fa-user-edit text-primary me-2"></i> Update Informasi Profil</h5>
                </div>
                <div class="card-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Email</label>
                        <input type="email" name="email" class="form-control rounded-3 @error('email') is-invalid @enderror" value="{{ old('email', Auth::user()->email) }}" required style="height: 42px; border-color: #cbd5e1;">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Username</label>
                        <input type="text" name="username" class="form-control rounded-3 @error('username') is-invalid @enderror" value="{{ old('username', Auth::user()->username) }}" required style="height: 42px; border-color: #cbd5e1;">
                        @error('username')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold">Password Baru</label>
                        <input type="password" name="password" class="form-control rounded-3 @error('password') is-invalid @enderror" placeholder="Biarkan kosong jika tidak ingin mengubah password" style="height: 42px; border-color: #cbd5e1;">
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-light border rounded-pill px-4 py-2 fw-semibold text-secondary" style="height: 42px;">
                            <i class="fa-solid fa-arrow-left me-2"></i> Kembali
                        </a>
                        <button type="submit" class="btn btn-primary rounded-pill px-4 py-2 fw-semibold" style="height: 42px;">
                            <i class="fa-solid fa-save me-2"></i> Simpan Perubahan
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column: Profile Picture -->
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white border-0 py-3 rounded-top-4">
                    <h5 class="mb-0 fw-bold text-dark"><i class="fa-solid fa-image text-primary me-2"></i> Foto Profil</h5>
                </div>
                <div class="card-body p-4 text-center">
                    <div class="mb-4" id="admin-photo-preview-container">
                        @if(Auth::user()->profile_photo)
                            <img src="{{ asset('storage/' . Auth::user()->profile_photo) }}" alt="Profile" class="rounded-circle shadow-sm border p-1 bg-white" style="width: 140px; height: 140px; object-fit: cover;">
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
                                <span class="text-muted small text-truncate" style="flex: 1;" id="filename-profile">Belum ada file...</span>
                            </div>
                            <input type="file" name="profile_photo" id="profile_photo" class="d-none" accept=".jpg,.jpeg,.png" onchange="previewProfilePhoto(this, 'filename-profile', 'admin-photo-preview-container')">
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

@push('scripts')
<script>
function previewProfilePhoto(input, labelId, containerId) {
    if (input.files && input.files[0]) {
        const file = input.files[0];
        const label = document.getElementById(labelId);
        if (label) label.innerText = file.name;

        const reader = new FileReader();
        reader.onload = function(e) {
            const container = document.getElementById(containerId);
            if (container) {
                container.innerHTML = `<img src="${e.target.result}" alt="Pratinjau Foto Profil" class="rounded-circle shadow-sm border p-1 bg-white" style="width: 140px; height: 140px; object-fit: cover;">`;
            }
        };
        reader.readAsDataURL(file);
    }
}
</script>
@endpush
@endsection
