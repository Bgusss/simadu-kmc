@extends('public.layouts.app')

@section('title', 'Lapor Aduan - SIMADU KMC')

@push('styles')
<style>
    .complaint-hero {
        background: linear-gradient(135deg, var(--kmc-blue-dark) 0%, var(--kmc-blue) 60%, #1565c0 100%);
        padding: 60px 0 40px;
        color: white;
        position: relative;
        overflow: hidden;
    }
    .complaint-hero::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -20%;
        width: 600px;
        height: 600px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.03);
    }
    .complaint-hero::after {
        content: '';
        position: absolute;
        bottom: -30%;
        left: -10%;
        width: 400px;
        height: 400px;
        border-radius: 50%;
        background: rgba(245, 124, 0, 0.08);
    }
    .complaint-hero h1 {
        font-weight: 800;
        font-size: 2rem;
        letter-spacing: -0.5px;
    }
    .complaint-hero p {
        opacity: 0.85;
        font-size: 1.05rem;
    }

    .complaint-form-card {
        border: none;
        border-radius: 20px;
        box-shadow: 0 20px 40px rgba(13, 71, 161, 0.08);
        overflow: hidden;
        margin-top: -30px;
        position: relative;
        z-index: 10;
    }
    .complaint-form-card .card-body {
        padding: 2.5rem;
    }

    .form-label {
        font-weight: 600;
        font-size: 0.9rem;
        color: #334155;
        margin-bottom: 0.4rem;
    }
    .form-label .text-danger { font-size: 0.85rem; }

    .form-control, .form-select {
        border-radius: 12px;
        border: 2px solid #e2e8f0;
        padding: 12px 16px;
        font-size: 0.95rem;
        transition: all 0.3s ease;
    }
    .form-control:focus, .form-select:focus {
        border-color: var(--kmc-blue);
        box-shadow: 0 0 0 4px rgba(13, 71, 161, 0.1);
    }
    .form-control.is-invalid {
        border-color: #ef4444;
        box-shadow: 0 0 0 4px rgba(239, 68, 68, 0.1);
    }

    textarea.form-control {
        min-height: 140px;
        resize: vertical;
    }

    .btn-submit {
        background: linear-gradient(135deg, var(--kmc-orange) 0%, var(--kmc-orange-hover) 100%);
        border: none;
        border-radius: 14px;
        padding: 14px 32px;
        font-weight: 700;
        font-size: 1rem;
        color: white;
        letter-spacing: 0.5px;
        transition: all 0.3s ease;
        box-shadow: 0 8px 20px rgba(245, 124, 0, 0.3);
    }
    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 28px rgba(245, 124, 0, 0.4);
        color: white;
    }
    .btn-submit:active {
        transform: translateY(0);
    }

    .upload-area {
        border: 2px dashed #cbd5e1;
        border-radius: 14px;
        padding: 30px;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s ease;
        background: #f8fafc;
    }
    .upload-area:hover {
        border-color: var(--kmc-blue);
        background: #eff6ff;
    }
    .upload-area.dragover {
        border-color: var(--kmc-orange);
        background: #fff7ed;
    }
    .upload-area i {
        font-size: 2.5rem;
        color: #94a3b8;
        margin-bottom: 10px;
    }
    .upload-area p {
        color: #64748b;
        font-size: 0.9rem;
        margin: 0;
    }
    .upload-area .file-name {
        color: var(--kmc-blue);
        font-weight: 600;
        font-size: 0.95rem;
    }

    .success-card {
        background: linear-gradient(135deg, #065f46 0%, #10b981 100%);
        color: white;
        border-radius: 20px;
        padding: 2.5rem;
        text-align: center;
        box-shadow: 0 20px 40px rgba(16, 185, 129, 0.2);
    }
    .success-card .success-icon {
        font-size: 4rem;
        margin-bottom: 1rem;
        animation: successPulse 2s ease infinite;
    }
    @keyframes successPulse {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.1); }
    }
    .success-card .tracking-number {
        background: rgba(255,255,255,0.2);
        border-radius: 12px;
        padding: 12px 24px;
        display: inline-block;
        font-size: 1.3rem;
        font-weight: 800;
        letter-spacing: 1px;
        margin: 15px 0;
        backdrop-filter: blur(10px);
    }

    .info-box {
        background: #eff6ff;
        border: 1px solid #bfdbfe;
        border-radius: 14px;
        padding: 16px 20px;
        font-size: 0.9rem;
        color: #1e40af;
    }
    .info-box i { margin-right: 8px; }

    .wa-channel-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: #dcfce7;
        color: #166534;
        border-radius: 50px;
        padding: 8px 16px;
        font-size: 0.85rem;
        font-weight: 600;
    }

    .step-indicator {
        display: flex;
        justify-content: center;
        gap: 8px;
        margin-bottom: 2rem;
    }
    .step-indicator .step {
        width: 40px;
        height: 4px;
        border-radius: 4px;
        background: #e2e8f0;
        transition: all 0.3s ease;
    }
    .step-indicator .step.active {
        background: var(--kmc-orange);
        width: 60px;
    }
</style>
@endpush

@section('content')
    {{-- Hero Section --}}
    <div class="complaint-hero">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-12">
                    <h1><i class="fas fa-bullhorn me-2"></i> Lapor Aduan</h1>
                    <p class="mb-0">
                        Sampaikan keluhan atau aspirasi Anda kepada Pemerintah Kabupaten Ketapang.
                        Laporan Anda akan ditindaklanjuti oleh OPD terkait.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="container pb-5">
        {{-- Success State --}}
        @if(session('success'))
            <div class="row justify-content-center" style="margin-top: -10px;">
                <div class="col-lg-7">
                    <div class="success-card">
                        <div class="success-icon">✅</div>
                        <h3 style="font-weight: 800;">Laporan Berhasil Dikirim!</h3>
                        <p style="opacity: 0.9;">Terima kasih telah melapor. Laporan Anda akan segera ditindaklanjuti oleh OPD terkait.</p>

                        <div class="tracking-number">
                            {{ session('tracking_number') }}
                        </div>

                        <p style="opacity: 0.8; font-size: 0.9rem;">
                            Simpan nomor tiket di atas untuk melacak status aduan Anda.
                        </p>

                        <div class="d-flex flex-column flex-sm-row gap-3 justify-content-center mt-4">
                            <a href="{{ route('ticketing.show', session('tracking_number')) }}" class="btn btn-light px-4 py-2" style="border-radius: 12px; font-weight: 600;">
                                <i class="fas fa-search me-1"></i> Lacak Status
                            </a>
                            <a href="{{ route('public.complaint.create') }}" class="btn btn-outline-light px-4 py-2" style="border-radius: 12px; font-weight: 600;">
                                <i class="fas fa-plus me-1"></i> Lapor Lagi
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @else
            {{-- Form --}}
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="card complaint-form-card">
                        <div class="card-body">

                            <div class="step-indicator">
                                <div class="step active"></div>
                                <div class="step"></div>
                                <div class="step"></div>
                            </div>

                            <h4 class="mb-1" style="font-weight: 800; color: var(--kmc-blue-dark);">
                                <i class="fas fa-edit me-1"></i> Form Pengaduan
                            </h4>
                            <p class="text-muted mb-4" style="font-size: 0.9rem;">
                                Isi data di bawah ini dengan lengkap dan benar. Laporan Anda akan diproses secara otomatis.
                            </p>

                            @if(session('error'))
                                <div class="alert alert-danger d-flex align-items-center" style="border-radius: 12px;">
                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                    {{ session('error') }}
                                </div>
                            @endif

                            <form action="{{ route('public.complaint.store') }}" method="POST" enctype="multipart/form-data" id="complaintForm">
                                @csrf

                                {{-- Nama Lengkap --}}
                                <div class="mb-4">
                                    <label for="reporter_name" class="form-label">
                                        Nama Lengkap <span class="text-danger">*</span>
                                    </label>
                                    <input type="text"
                                           class="form-control @error('reporter_name') is-invalid @enderror"
                                           id="reporter_name"
                                           name="reporter_name"
                                           value="{{ old('reporter_name') }}"
                                           placeholder="Masukkan nama lengkap Anda"
                                           required>
                                    @error('reporter_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Nomor HP / WhatsApp --}}
                                <div class="mb-4">
                                    <label for="reporter_phone" class="form-label">
                                        Nomor HP / WhatsApp <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text" style="border-radius: 12px 0 0 12px; border: 2px solid #e2e8f0; border-right: 0; background: #f8fafc;">
                                            <i class="fab fa-whatsapp text-success"></i> +62
                                        </span>
                                        <input type="tel"
                                               class="form-control @error('reporter_phone') is-invalid @enderror"
                                               id="reporter_phone"
                                               name="reporter_phone"
                                               value="{{ old('reporter_phone') }}"
                                               placeholder="8123456789"
                                               style="border-radius: 0 12px 12px 0;"
                                               required>
                                    </div>
                                    <small class="text-muted">Konfirmasi tiket akan dikirim via WhatsApp</small>
                                    @error('reporter_phone')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Isi Pengaduan --}}
                                <div class="mb-4">
                                    <label for="complaint" class="form-label">
                                        Isi Pengaduan <span class="text-danger">*</span>
                                    </label>
                                    <textarea class="form-control @error('complaint') is-invalid @enderror"
                                              id="complaint"
                                              name="complaint"
                                              rows="5"
                                              placeholder="Jelaskan permasalahan Anda secara detail. Sertakan lokasi, waktu, dan kronologi kejadian agar dapat segera ditindaklanjuti."
                                              required>{{ old('complaint') }}</textarea>
                                    <div class="d-flex justify-content-between mt-1">
                                        <small class="text-muted">Maksimal 5.000 karakter</small>
                                        <small class="text-muted"><span id="charCount">0</span>/5000</small>
                                    </div>
                                    @error('complaint')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Lampiran --}}
                                <div class="mb-4">
                                    <label class="form-label">Lampiran <span class="text-muted">(Opsional)</span></label>
                                    <div class="upload-area" id="uploadArea" onclick="document.getElementById('attachment').click()">
                                        <i class="fas fa-cloud-upload-alt" id="uploadIcon"></i>
                                        <p id="uploadText">Klik atau seret file untuk mengunggah</p>
                                        <p class="file-name d-none" id="fileName"></p>
                                        <small class="text-muted d-block mt-1" id="uploadHint">JPG, PNG, WEBP, MP4, MOV, 3GP — Maks 20MB</small>
                                    </div>
                                    {{-- Preview Lampiran --}}
                                    <div id="previewContainer" class="d-none mt-3">
                                        <div class="position-relative d-inline-block">
                                            <img id="imagePreview" class="d-none rounded" style="max-width: 100%; max-height: 300px; border: 2px solid #e2e8f0; border-radius: 14px !important; object-fit: contain;" alt="Preview">
                                            <video id="videoPreview" class="d-none rounded" controls style="max-width: 100%; max-height: 300px; border: 2px solid #e2e8f0; border-radius: 14px !important;"></video>
                                            <button type="button" id="removeFile" class="btn btn-sm btn-danger position-absolute" style="top: 8px; right: 8px; border-radius: 50%; width: 32px; height: 32px; padding: 0; display: flex; align-items: center; justify-content: center; box-shadow: 0 2px 8px rgba(0,0,0,0.2);">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <input type="file" class="d-none" id="attachment" name="attachment"
                                           accept=".jpg,.jpeg,.png,.webp,.heic,.heif,.mp4,.mov,.avi,.3gp">
                                    @error('attachment')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>



                                {{-- Submit --}}
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-submit" id="submitBtn">
                                        <i class="fas fa-paper-plane me-2"></i> Kirim Laporan
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>


                </div>
            </div>
        @endif
    </div>
@endsection

@push('scripts')
<script>
    // Character counter
    const complaintField = document.getElementById('complaint');
    const charCount = document.getElementById('charCount');
    if (complaintField && charCount) {
        complaintField.addEventListener('input', function() {
            charCount.textContent = this.value.length;
        });
        // Init
        charCount.textContent = complaintField.value.length;
    }

    // File upload area with preview
    const uploadArea = document.getElementById('uploadArea');
    const fileInput = document.getElementById('attachment');
    const uploadText = document.getElementById('uploadText');
    const uploadIcon = document.getElementById('uploadIcon');
    const uploadHint = document.getElementById('uploadHint');
    const fileName = document.getElementById('fileName');
    const previewContainer = document.getElementById('previewContainer');
    const imagePreview = document.getElementById('imagePreview');
    const videoPreview = document.getElementById('videoPreview');
    const removeFile = document.getElementById('removeFile');

    function showPreview(file) {
        const url = URL.createObjectURL(file);
        const ext = file.name.split('.').pop().toLowerCase();
        const isVideo = ['mp4', 'mov', 'avi', '3gp', 'webm'].includes(ext);

        // Show file name
        uploadText.classList.add('d-none');
        uploadIcon.classList.add('d-none');
        uploadHint.classList.add('d-none');
        fileName.classList.remove('d-none');
        fileName.textContent = '📎 ' + file.name + ' (' + (file.size / 1024 / 1024).toFixed(1) + ' MB)';

        // Show preview
        previewContainer.classList.remove('d-none');
        if (isVideo) {
            imagePreview.classList.add('d-none');
            videoPreview.classList.remove('d-none');
            videoPreview.src = url;
        } else {
            videoPreview.classList.add('d-none');
            imagePreview.classList.remove('d-none');
            imagePreview.src = url;
        }
    }

    function resetUpload() {
        fileInput.value = '';
        uploadText.classList.remove('d-none');
        uploadIcon.classList.remove('d-none');
        uploadHint.classList.remove('d-none');
        fileName.classList.add('d-none');
        previewContainer.classList.add('d-none');
        imagePreview.classList.add('d-none');
        imagePreview.src = '';
        videoPreview.classList.add('d-none');
        videoPreview.src = '';
    }

    if (fileInput) {
        fileInput.addEventListener('change', function() {
            if (this.files.length > 0) {
                showPreview(this.files[0]);
            } else {
                resetUpload();
            }
        });
    }

    if (removeFile) {
        removeFile.addEventListener('click', function(e) {
            e.stopPropagation();
            resetUpload();
        });
    }

    // Drag and drop
    if (uploadArea) {
        uploadArea.addEventListener('dragover', function(e) {
            e.preventDefault();
            this.classList.add('dragover');
        });
        uploadArea.addEventListener('dragleave', function() {
            this.classList.remove('dragover');
        });
        uploadArea.addEventListener('drop', function(e) {
            e.preventDefault();
            this.classList.remove('dragover');
            fileInput.files = e.dataTransfer.files;
            fileInput.dispatchEvent(new Event('change'));
        });
    }

    // Submit loading state
    const form = document.getElementById('complaintForm');
    const submitBtn = document.getElementById('submitBtn');
    if (form && submitBtn) {
        form.addEventListener('submit', function() {
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Mengirim...';
        });
    }
</script>
@endpush
