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
        background: white;
        border-radius: 20px;
        padding: 3rem 2.5rem;
        text-align: center;
        box-shadow: 0 20px 50px rgba(13, 71, 161, 0.12);
        border: 1px solid #e2e8f0;
        position: relative;
        overflow: hidden;
    }
    .success-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 5px;
        background: linear-gradient(90deg, var(--kmc-blue) 0%, var(--kmc-orange) 100%);
    }
    .success-card .success-icon-wrap {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--kmc-blue) 0%, #1565c0 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.2rem;
        animation: successPulse 2s ease infinite;
    }
    .success-card .success-icon-wrap i {
        font-size: 2rem;
        color: white;
    }
    @keyframes successPulse {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.05); }
    }
    .success-card h3 {
        font-weight: 800;
        color: var(--kmc-blue-dark);
        margin-bottom: 0.5rem;
    }
    .success-card .success-desc {
        color: #64748b;
        font-size: 0.95rem;
        margin-bottom: 1.5rem;
    }
    .success-card .tracking-number {
        background: linear-gradient(135deg, var(--kmc-blue) 0%, #1565c0 100%);
        color: white;
        border-radius: 14px;
        padding: 14px 28px;
        display: inline-block;
        font-size: 1.2rem;
        font-weight: 800;
        letter-spacing: 2px;
        margin: 0 0 1rem;
    }
    .success-card .tracking-hint {
        color: #94a3b8;
        font-size: 0.85rem;
        margin-bottom: 1.5rem;
    }
    .success-card .btn-kmc-primary {
        background: linear-gradient(135deg, var(--kmc-orange) 0%, var(--kmc-orange-hover) 100%);
        border: none;
        color: white;
        border-radius: 12px;
        padding: 10px 24px;
        font-weight: 700;
        transition: all 0.3s ease;
        box-shadow: 0 4px 12px rgba(245, 124, 0, 0.3);
    }
    .success-card .btn-kmc-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(245, 124, 0, 0.4);
        color: white;
    }
    .success-card .btn-kmc-outline {
        background: transparent;
        border: 2px solid var(--kmc-blue);
        color: var(--kmc-blue);
        border-radius: 12px;
        padding: 10px 24px;
        font-weight: 700;
        transition: all 0.3s ease;
    }
    .success-card .btn-kmc-outline:hover {
        background: var(--kmc-blue);
        color: white;
        transform: translateY(-2px);
    }

    /* Toast notification */
    .upload-toast {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 9999;
        min-width: 320px;
        max-width: 420px;
        padding: 16px 20px;
        border-radius: 14px;
        color: white;
        font-size: 0.9rem;
        font-weight: 500;
        box-shadow: 0 8px 24px rgba(0,0,0,0.15);
        transform: translateX(120%);
        transition: transform 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .upload-toast.show {
        transform: translateX(0);
    }
    .upload-toast.toast-error {
        background: linear-gradient(135deg, #ef4444, #dc2626);
    }
    .upload-toast.toast-warning {
        background: linear-gradient(135deg, var(--kmc-orange), var(--kmc-orange-hover));
    }
    .upload-toast i {
        font-size: 1.3rem;
        flex-shrink: 0;
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

    .preview-item {
        position: relative;
        width: 140px;
        height: 140px;
        border-radius: 14px;
        overflow: hidden;
        border: 2px solid #e2e8f0;
        background: #f8fafc;
        transition: all 0.3s ease;
        flex-shrink: 0;
    }
    .preview-item:hover {
        border-color: var(--kmc-blue);
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    .preview-item.preview-video {
        width: 260px;
        height: 160px;
    }
    .preview-item img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .preview-item video {
        width: 100%;
        height: 100%;
        object-fit: contain;
        background: #000;
    }
    .preview-item .remove-btn {
        position: absolute;
        top: 6px;
        right: 6px;
        width: 24px;
        height: 24px;
        border-radius: 50%;
        border: none;
        background: rgba(239, 68, 68, 0.9);
        color: white;
        font-size: 11px;
        line-height: 24px;
        text-align: center;
        padding: 0;
        cursor: pointer;
        box-shadow: 0 2px 6px rgba(0,0,0,0.25);
        transition: all 0.2s ease;
        z-index: 5;
    }
    .preview-item .remove-btn:hover {
        background: #dc2626;
        transform: scale(1.1);
    }
    .preview-item .remove-btn i {
        vertical-align: middle;
        line-height: inherit;
    }
    .preview-item .file-label {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        background: rgba(0,0,0,0.6);
        color: white;
        font-size: 0.65rem;
        padding: 4px 8px;
        text-align: center;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .preview-item .video-badge {
        position: absolute;
        top: 6px;
        left: 6px;
        background: rgba(0,0,0,0.7);
        color: white;
        font-size: 0.6rem;
        padding: 2px 8px;
        border-radius: 6px;
        z-index: 4;
    }
</style>
@endpush

@section('content')
    {{-- Toast Notification --}}
    <div class="upload-toast" id="uploadToast">
        <i class="fas fa-exclamation-circle"></i>
        <span id="toastMessage"></span>
    </div>

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
        @if(session('success') || session('submitted'))
            <div class="row justify-content-center" style="margin-top: -10px;">
                <div class="col-lg-7">
                    <div class="success-card">
                        @if(session('submitted'))
                            <div class="success-icon-wrap" style="background: linear-gradient(135deg, #2563a8, #0f4c81);">
                                <i class="fas fa-paper-plane"></i>
                            </div>
                            <h3>Laporan Berhasil Dikirim</h3>
                            <p class="success-desc">
                                Terima kasih telah menyampaikan laporan. Laporan Anda telah diterima dan sedang diproses oleh Ketapang Media Center.
                            </p>
                            <div class="d-flex justify-content-center">
                                <a href="{{ route('public.complaint.create') }}" class="btn btn-kmc-outline">
                                    <i class="fas fa-plus me-1"></i> Buat Laporan Lain
                                </a>
                            </div>
                        @else
                            <div class="success-icon-wrap">
                                <i class="fas fa-check"></i>
                            </div>
                            <h3>Laporan Berhasil Dikirim!</h3>
                            <p class="success-desc">
                                Terima kasih telah melapor. Laporan Anda akan segera ditindaklanjuti oleh OPD terkait.
                            </p>

                            <div class="tracking-number">
                                {{ session('tracking_number') }}
                            </div>

                            <p class="tracking-hint">
                                <i class="fas fa-info-circle me-1"></i>
                                Simpan nomor tiket di atas untuk melacak status aduan Anda.
                            </p>

                            <div class="d-flex flex-column flex-sm-row gap-3 justify-content-center">
                                <a href="{{ route('ticketing.show', session('tracking_number')) }}" class="btn btn-kmc-primary">
                                    <i class="fas fa-search me-1"></i> Lacak Status
                                </a>
                                <a href="{{ route('public.complaint.create') }}" class="btn btn-kmc-outline">
                                    <i class="fas fa-plus me-1"></i> Lapor Lagi
                                </a>
                            </div>
                        @endif
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
                                    <label class="form-label">
                                        Lampiran <span class="text-muted">(Opsional — Maks 5 gambar + 1 video)</span>
                                    </label>
                                    <div class="upload-area" id="uploadArea">
                                        <i class="fas fa-cloud-upload-alt" id="uploadIcon"></i>
                                        <p id="uploadText">Klik atau seret file untuk mengunggah</p>
                                        <small class="text-muted d-block mt-1" id="uploadHint">JPG, PNG, WEBP, MP4, MOV, 3GP — Maks 20MB per file</small>
                                        <span class="badge bg-secondary mt-2 d-none" id="fileCountBadge"></span>
                                    </div>

                                    {{-- Preview Grid --}}
                                    <div id="previewGrid" class="d-none mt-3">
                                        <div class="d-flex flex-wrap justify-content-center gap-3" id="previewItems">
                                        </div>
                                    </div>

                                    {{-- Hidden file inputs --}}
                                    <input type="file" class="d-none" id="fileSelector"
                                           accept=".jpg,.jpeg,.png,.webp,.heic,.heif,.mp4,.mov,.avi,.3gp" multiple>
                                    {{-- Dynamic inputs will be injected here --}}
                                    <div id="fileInputsContainer"></div>

                                    @error('attachments')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                    @error('attachments.*')
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
        charCount.textContent = complaintField.value.length;
    }

    // ── Multi-file upload ──────────────────────────────
    const MAX_IMAGES = 5;
    const MAX_VIDEOS = 1;
    const MAX_SIZE_MB = 20;
    const VIDEO_EXTS = ['mp4', 'mov', 'avi', '3gp', 'webm'];

    const uploadArea = document.getElementById('uploadArea');
    const fileSelector = document.getElementById('fileSelector');
    const previewGrid = document.getElementById('previewGrid');
    const previewItems = document.getElementById('previewItems');
    const fileInputsContainer = document.getElementById('fileInputsContainer');
    const fileCountBadge = document.getElementById('fileCountBadge');

    let selectedFiles = []; // { file, id, isVideo }

    function getExt(name) {
        return name.split('.').pop().toLowerCase();
    }

    function isVideoFile(name) {
        return VIDEO_EXTS.includes(getExt(name));
    }

    function countImages() {
        return selectedFiles.filter(f => !f.isVideo).length;
    }

    function countVideos() {
        return selectedFiles.filter(f => f.isVideo).length;
    }

    // Toast notification
    const toastEl = document.getElementById('uploadToast');
    const toastMsg = document.getElementById('toastMessage');
    let toastTimer = null;

    function showToast(message, type = 'error') {
        toastEl.className = 'upload-toast toast-' + type;
        toastMsg.textContent = message;
        // Force reflow for re-triggering animation
        void toastEl.offsetWidth;
        toastEl.classList.add('show');

        if (toastTimer) clearTimeout(toastTimer);
        toastTimer = setTimeout(() => {
            toastEl.classList.remove('show');
        }, 4000);
    }

    const ALLOWED_EXTS = ['jpg','jpeg','png','webp','heic','heif','mp4','mov','avi','3gp'];

    function addFiles(files) {
        for (const file of files) {
            // Format check
            const ext = getExt(file.name);
            if (!ALLOWED_EXTS.includes(ext)) {
                showToast(`Format "${ext}" tidak didukung. Gunakan JPG, PNG, WEBP, MP4, MOV, atau 3GP.`, 'error');
                continue;
            }

            // Size check
            const sizeMB = (file.size / 1024 / 1024).toFixed(1);
            if (file.size > MAX_SIZE_MB * 1024 * 1024) {
                showToast(`File "${file.name}" (${sizeMB}MB) melebihi batas maksimal ${MAX_SIZE_MB}MB.`, 'error');
                continue;
            }

            const isVideo = isVideoFile(file.name);

            // Limit check
            if (isVideo && countVideos() >= MAX_VIDEOS) {
                showToast(`Maksimal ${MAX_VIDEOS} video. Hapus video sebelumnya untuk mengganti.`, 'warning');
                continue;
            }
            if (!isVideo && countImages() >= MAX_IMAGES) {
                showToast(`Maksimal ${MAX_IMAGES} gambar tercapai. Hapus gambar untuk menambah yang baru.`, 'warning');
                continue;
            }

            const id = 'file_' + Date.now() + '_' + Math.random().toString(36).substr(2, 5);
            selectedFiles.push({ file, id, isVideo });
        }

        renderPreviews();
    }

    function removeFileById(id) {
        selectedFiles = selectedFiles.filter(f => f.id !== id);
        renderPreviews();
    }

    function renderPreviews() {
        // Clear
        previewItems.innerHTML = '';
        fileInputsContainer.innerHTML = '';

        if (selectedFiles.length === 0) {
            previewGrid.classList.add('d-none');
            fileCountBadge.classList.add('d-none');
            return;
        }

        previewGrid.classList.remove('d-none');
        fileCountBadge.classList.remove('d-none');
        fileCountBadge.textContent = `${countImages()} gambar, ${countVideos()} video`;

        selectedFiles.forEach((item, index) => {
            const url = URL.createObjectURL(item.file);

            // Preview card
            const div = document.createElement('div');
            div.className = 'preview-item';

            if (item.isVideo) {
                div.classList.add('preview-video');
                div.innerHTML = `
                    <video src="${url}" controls playsinline></video>
                    <span class="video-badge"><i class="fas fa-video"></i> Video</span>
                    <button type="button" class="remove-btn" data-id="${item.id}"><i class="fas fa-times"></i></button>
                    <div class="file-label">${item.file.name}</div>
                `;
            } else {
                div.innerHTML = `
                    <img src="${url}" alt="Preview">
                    <button type="button" class="remove-btn" data-id="${item.id}"><i class="fas fa-times"></i></button>
                    <div class="file-label">${item.file.name}</div>
                `;
            }

            previewItems.appendChild(div);

            // Hidden file input (using DataTransfer to set .files)
            const input = document.createElement('input');
            input.type = 'file';
            input.name = 'attachments[]';
            input.style.display = 'none';
            const dt = new DataTransfer();
            dt.items.add(item.file);
            input.files = dt.files;
            fileInputsContainer.appendChild(input);
        });

        // Bind remove buttons
        previewItems.querySelectorAll('.remove-btn').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.stopPropagation();
                removeFileById(this.dataset.id);
            });
        });
    }

    // Click to upload
    if (uploadArea) {
        uploadArea.addEventListener('click', function() {
            fileSelector.click();
        });
    }

    if (fileSelector) {
        fileSelector.addEventListener('change', function() {
            if (this.files.length > 0) {
                addFiles(this.files);
            }
            this.value = ''; // reset so same file can be selected again
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
            addFiles(e.dataTransfer.files);
        });
    }

    // Submit loading state
    const form = document.getElementById('complaintForm');
    const submitBtn = document.getElementById('submitBtn');
    if (form && submitBtn) {
        form.addEventListener('submit', function() {
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Mengirim Laporan...';

            // Safety: re-enable button after 90 seconds
            setTimeout(function() {
                if (submitBtn.disabled) {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = '<i class="fas fa-paper-plane me-2"></i> Kirim Laporan';
                }
            }, 90000);
        });
    }
</script>
@endpush
