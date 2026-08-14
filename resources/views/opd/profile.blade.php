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
                    <div class="mb-4" id="opd-photo-preview-container">
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
                                <span class="text-muted small text-truncate" style="flex: 1;" id="filename-profile-opd">Belum ada file...</span>
                            </div>
                            <input type="file" name="profile_photo" id="profile_photo" class="d-none" accept=".jpg,.jpeg,.png" onchange="handlePhotoSelect(this, 'filename-profile-opd', 'opd-photo-preview-container')">
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
                
                <div class="d-flex justify-content-center align-items-center gap-2 flex-wrap bg-white p-2 rounded-3 border shadow-sm mx-auto mb-2" style="max-width: 560px;">
                    <button type="button" class="btn btn-outline-secondary btn-sm rounded-circle px-2.5 py-1" onclick="cropperZoom(0.1)" title="Perbesar"><i class="fas fa-search-plus"></i></button>
                    <button type="button" class="btn btn-outline-secondary btn-sm rounded-circle px-2.5 py-1" onclick="cropperZoom(-0.1)" title="Perkecil"><i class="fas fa-search-minus"></i></button>
                    <button type="button" class="btn btn-outline-secondary btn-sm rounded-circle px-2.5 py-1" onclick="cropperRotate(-90)" title="Putar Kiri"><i class="fas fa-undo"></i></button>
                    <button type="button" class="btn btn-outline-secondary btn-sm rounded-circle px-2.5 py-1" onclick="cropperRotate(90)" title="Putar Kanan"><i class="fas fa-redo"></i></button>
                    <button type="button" class="btn btn-outline-secondary btn-sm rounded-circle px-2.5 py-1" onclick="cropperFlipX()" title="Balik Horizontal"><i class="fas fa-arrows-alt-h"></i></button>
                    <button type="button" class="btn btn-outline-secondary btn-sm rounded-circle px-2.5 py-1" onclick="cropperFlipY()" title="Balik Vertikal"><i class="fas fa-arrows-alt-v"></i></button>
                    <button type="button" class="btn btn-outline-primary btn-sm rounded-pill px-3 py-1 fw-semibold" onclick="cropperCenter()" title="Posisikan di Tengah"><i class="fas fa-bullseye me-1"></i>Pusat Tengah</button>
                    <button type="button" class="btn btn-outline-secondary btn-sm rounded-pill px-3 py-1" onclick="cropperReset()"><i class="fas fa-sync-alt me-1"></i>Reset</button>
                </div>
                <div class="small text-muted"><i class="fas fa-info-circle me-1 text-primary"></i>Geser, balik, dan atur area foto agar pas di dalam lingkaran profil.</div>
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
let scaleXVal = 1;
let scaleYVal = 1;

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
    scaleXVal = 1;
    scaleYVal = 1;
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
function cropperFlipX() {
    if (!cropperInstance) return;
    scaleXVal = -scaleXVal;
    cropperInstance.scaleX(scaleXVal);
}
function cropperFlipY() {
    if (!cropperInstance) return;
    scaleYVal = -scaleYVal;
    cropperInstance.scaleY(scaleYVal);
}
function cropperCenter() {
    if (!cropperInstance) return;
    const containerData = cropperInstance.getContainerData();
    const canvasData = cropperInstance.getCanvasData();
    const cropBoxData = cropperInstance.getCropBoxData();

    cropperInstance.setCanvasData({
        left: (containerData.width - canvasData.width) / 2,
        top: (containerData.height - canvasData.height) / 2
    });
    cropperInstance.setCropBoxData({
        left: (containerData.width - cropBoxData.width) / 2,
        top: (containerData.height - cropBoxData.height) / 2
    });
}
function cropperReset() {
    if (!cropperInstance) return;
    scaleXVal = 1;
    scaleYVal = 1;
    cropperInstance.reset();
    cropperCenter();
}

document.getElementById('btnCropSave').addEventListener('click', function () {
    if (!cropperInstance || !currentFileInput) return;

    const canvas = cropperInstance.getCroppedCanvas({
        width: 400,
        height: 400,
        fillColor: '#ffffff',
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
