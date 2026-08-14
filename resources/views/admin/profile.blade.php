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
                            <input type="file" name="profile_photo" id="profile_photo" class="d-none" accept=".jpg,.jpeg,.png" onchange="handlePhotoSelect(this, 'filename-profile', 'admin-photo-preview-container')">
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

<!-- Modal Crop Foto Profil -->
<div class="modal fade" id="cropPhotoModal" tabindex="-1" aria-labelledby="cropPhotoModalLabel" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="modal-header bg-primary text-white border-0 py-3">
                <h5 class="modal-title fw-bold fs-6 mb-0" id="cropPhotoModalLabel">
                    <i class="fas fa-crop-alt me-2"></i>Potong & Sesuaikan Foto Profil
                </h5>
                <button type="button" class="btn-close btn-close-white shadow-none" data-bs-dismiss="modal" aria-label="Batal"></button>
            </div>
            <div class="modal-body p-4 bg-light text-center">
                <div class="img-container mx-auto mb-3 overflow-hidden rounded-3 bg-dark" style="max-height: 400px; width: 100%;">
                    <img id="cropperImage" src="" alt="Pratinjau Foto" style="max-width: 100%; display: block;">
                </div>
                
                <div class="d-flex justify-content-center align-items-center gap-2 flex-wrap bg-white p-2 rounded-3 border shadow-sm mx-auto mb-2" style="max-width: 480px;">
                    <button type="button" class="btn btn-outline-secondary btn-sm rounded-circle px-2.5 py-1" onclick="cropperZoom(0.1)" title="Perbesar"><i class="fas fa-search-plus"></i></button>
                    <button type="button" class="btn btn-outline-secondary btn-sm rounded-circle px-2.5 py-1" onclick="cropperZoom(-0.1)" title="Perkecil"><i class="fas fa-search-minus"></i></button>
                    <button type="button" class="btn btn-outline-secondary btn-sm rounded-circle px-2.5 py-1" onclick="cropperRotate(-90)" title="Putar Kiri"><i class="fas fa-undo"></i></button>
                    <button type="button" class="btn btn-outline-secondary btn-sm rounded-circle px-2.5 py-1" onclick="cropperRotate(90)" title="Putar Kanan"><i class="fas fa-redo"></i></button>
                    <button type="button" class="btn btn-outline-secondary btn-sm rounded-pill px-3 py-1" onclick="cropperReset()"><i class="fas fa-sync-alt me-1"></i>Reset</button>
                </div>
                <div class="small text-muted"><i class="fas fa-info-circle me-1 text-primary"></i>Geser dan atur area foto agar pas di dalam lingkaran profil.</div>
            </div>
            <div class="modal-footer bg-white border-top-0 py-3">
                <button type="button" class="btn btn-light border rounded-pill px-4 fw-semibold text-secondary" data-bs-dismiss="modal">
                    Batal
                </button>
                <button type="button" class="btn btn-primary rounded-pill px-4 fw-semibold" id="btnCropSave">
                    <i class="fas fa-check me-1"></i>Terapkan & Potong
                </button>
            </div>
        </div>
    </div>
</div>

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.css">
<style>
.cropper-view-box,
.cropper-face {
    border-radius: 50%;
}
.cropper-view-box {
    outline: 2px solid #0d47a1;
    outline-color: rgba(13, 71, 161, 0.85);
}
</style>
@endpush

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.js"></script>
<script>
let cropperInstance = null;
let currentFileInput = null;
let currentLabelId = null;
let currentContainerId = null;
let originalFileName = 'profile_photo.jpg';

function handlePhotoSelect(input, labelId, containerId) {
    if (input.files && input.files[0]) {
        const file = input.files[0];
        originalFileName = file.name;
        currentFileInput = input;
        currentLabelId = labelId;
        currentContainerId = containerId;

        const reader = new FileReader();
        reader.onload = function(e) {
            const cropperImg = document.getElementById('cropperImage');
            cropperImg.src = e.target.result;
            
            const cropModalEl = document.getElementById('cropPhotoModal');
            const cropModal = new bootstrap.Modal(cropModalEl);
            cropModal.show();
        };
        reader.readAsDataURL(file);
    }
}

document.getElementById('cropPhotoModal').addEventListener('shown.bs.modal', function () {
    const cropperImg = document.getElementById('cropperImage');
    if (cropperInstance) {
        cropperInstance.destroy();
    }
    cropperInstance = new Cropper(cropperImg, {
        aspectRatio: 1,
        viewMode: 1,
        dragMode: 'move',
        autoCropArea: 0.9,
        restore: false,
        guides: true,
        center: true,
        highlight: false,
        cropBoxMovable: true,
        cropBoxResizable: true,
        toggleDragModeOnDblclick: false,
    });
});

document.getElementById('cropPhotoModal').addEventListener('hidden.bs.modal', function () {
    if (cropperInstance) {
        cropperInstance.destroy();
        cropperInstance = null;
    }
});

function cropperZoom(ratio) {
    if (cropperInstance) cropperInstance.zoom(ratio);
}
function cropperRotate(degree) {
    if (cropperInstance) cropperInstance.rotate(degree);
}
function cropperReset() {
    if (cropperInstance) cropperInstance.reset();
}

document.getElementById('btnCropSave').addEventListener('click', function () {
    if (!cropperInstance || !currentFileInput) return;

    const canvas = cropperInstance.getCroppedCanvas({
        width: 400,
        height: 400,
        imageSmoothingEnabled: true,
        imageSmoothingQuality: 'high',
    });

    canvas.toBlob(function (blob) {
        if (!blob) return;

        const file = new File([blob], originalFileName, { type: blob.type || 'image/jpeg' });
        const container = new DataTransfer();
        container.items.add(file);
        currentFileInput.files = container.files;

        const label = document.getElementById(currentLabelId);
        if (label) label.innerText = originalFileName + ' (Terpotong)';

        const imgContainer = document.getElementById(currentContainerId);
        if (imgContainer) {
            imgContainer.innerHTML = `<img src="${canvas.toDataURL()}" alt="Pratinjau Foto Profil" class="rounded-circle shadow-sm border p-1 bg-white" style="width: 140px; height: 140px; object-fit: cover;">`;
        }

        const cropModalEl = document.getElementById('cropPhotoModal');
        const modalInstance = bootstrap.Modal.getInstance(cropModalEl);
        if (modalInstance) modalInstance.hide();
    }, 'image/jpeg', 0.95);
});
</script>
@endpush
@endsection
