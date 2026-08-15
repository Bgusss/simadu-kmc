<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>

        @yield('title', 'SIMODU KMC')

    </title>

    <link rel="icon" type="image/png" href="{{ asset('images/kmc-logo.png') }}">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    
    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --kmc-blue: #0d47a1;
            --kmc-blue-dark: #071f49;
            --kmc-orange: #f57c00;
            --kmc-orange-hover: #e65100;
        }

        @keyframes toastProgress {
            from { width: 100%; }
            to { width: 0%; }
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: #f8fafc;
            color: #1e293b;
            margin: 0;
            height: 100vh;
            overflow: hidden;
        }

        /* Override Bootstrap Primary and Accent colors with KMC Theme */
        .text-primary {
            color: var(--kmc-blue) !important;
        }
        .bg-primary {
            background-color: var(--kmc-blue) !important;
        }
        .border-primary {
            border-color: var(--kmc-blue) !important;
        }
        .btn-primary {
            background-color: var(--kmc-blue) !important;
            border-color: var(--kmc-blue) !important;
            transition: all 0.2s ease-in-out;
        }
        .btn-primary:hover, .btn-primary:focus, .btn-primary:active {
            background-color: var(--kmc-blue-dark) !important;
            border-color: var(--kmc-blue-dark) !important;
            box-shadow: 0 4px 12px rgba(13, 71, 161, 0.2) !important;
        }
        .btn-outline-primary {
            color: var(--kmc-blue) !important;
            border-color: var(--kmc-blue) !important;
            transition: all 0.2s ease-in-out;
        }
        .btn-outline-primary:hover, .btn-outline-primary:focus, .btn-outline-primary:active {
            background-color: var(--kmc-blue) !important;
            border-color: var(--kmc-blue) !important;
            color: white !important;
        }
        .badge.bg-primary {
            background-color: var(--kmc-blue) !important;
        }
        .bg-primary-subtle {
            background-color: rgba(13, 71, 161, 0.08) !important;
        }
        .text-primary-subtle {
            color: var(--kmc-blue) !important;
        }

        /* Ensure all profile photos & avatars use solid white background for PNG images */
        .chat-avatar img,
        .sidebar-user-dropdown img,
        #opd-photo-preview-container img,
        #admin-photo-preview-container img,
        .profile-photo-img,
        img.profile-photo {
            background-color: #ffffff !important;
        }
        .pagination .page-item.active .page-link {
            background-color: var(--kmc-blue) !important;
            border-color: var(--kmc-blue) !important;
        }
        .pagination .page-link {
            color: var(--kmc-blue);
        }
        .form-control:focus {
            border-color: rgba(13, 71, 161, 0.5);
            box-shadow: 0 0 0 0.25rem rgba(13, 71, 161, 0.15);
        }

        .sidebar {
            width: 270px;
            min-height: 100vh;
            background: linear-gradient(180deg, #0d47a1 0%, #071f49 100%);
            position: fixed;
            left: 0;
            top: 0;
            color: white;
            padding: 25px 20px;
            box-shadow: 4px 0 24px rgba(7, 31, 73, 0.15);
            z-index: 1030;
        }

        .sidebar-title {
            font-size: 28px;
            font-weight: bold;
        }

        .sidebar-subtitle {
            font-size: 12px;
            opacity: .8;
            margin-bottom: 40px;
        }

        .sidebar a {
            display: flex;
            align-items: center;
            gap: 12px;
            color: #cbd5e1;
            text-decoration: none;
            padding: 12px 15px;
            border-radius: 12px;
            margin-bottom: 10px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            font-weight: 500;
        }

        .sidebar a i {
            width: 24px;
            text-align: center;
            font-size: 18px;
            transition: transform 0.3s ease;
        }

        .sidebar a:hover {
            background: rgba(255, 255, 255, 0.08);
            color: white;
        }

        .sidebar a:hover i {
            transform: scale(1.1);
        }

        .sidebar a.active {
            background: var(--kmc-orange);
            color: white;
            box-shadow: 0 4px 14px rgba(245, 124, 0, 0.4);
            font-weight: 600;
        }

        .sidebar a.active i {
            color: white;
        }

        .main-content {
            margin-left: 270px;
            height: 100vh;
            display: flex;
            flex-direction: column;
            transition: all 0.3s ease;
            overflow: hidden;
        }

        .topbar {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            padding: 14px 30px;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.05);
            border-bottom: 1px solid rgba(241, 245, 249, 0.8);
            flex-shrink: 0;
        }

        .content {
            padding: 28px 30px 0 30px;
            flex-grow: 1;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
        }


        .footer {
            flex-shrink: 0;
        }


        /* Hover animations and common design styles */
        .hover-card {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .hover-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 20px -8px rgba(0, 0, 0, 0.08) !important;
        }

        /* Modern Premium Badges for Category & Sub-Category */
        .badge-category {
            background-color: rgba(13, 71, 161, 0.08) !important;
            color: #0d47a1 !important;
            border: 1px solid rgba(13, 71, 161, 0.16) !important;
            font-size: 0.725rem !important;
            font-weight: 600 !important;
            padding: 5px 10px !important;
            border-radius: 8px !important;
            letter-spacing: 0.2px;
            display: inline-flex !important;
            align-items: center;
            gap: 6px;
            white-space: nowrap !important;
            line-height: 1.2 !important;
        }
        .badge-category::before {
            content: "";
            display: inline-block;
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background-color: #0d47a1;
            flex-shrink: 0;
        }

        .badge-subcategory {
            background-color: rgba(100, 116, 139, 0.08) !important;
            color: #475569 !important;
            border: 1px solid rgba(100, 116, 139, 0.16) !important;
            font-size: 0.675rem !important;
            font-weight: 500 !important;
            padding: 4px 8px !important;
            border-radius: 6px !important;
            letter-spacing: 0.1px;
            display: inline-flex !important;
            align-items: center;
            gap: 5px;
            white-space: nowrap !important;
            line-height: 1.2 !important;
        }
        .badge-subcategory::before {
            content: "";
            display: inline-block;
            width: 5px;
            height: 5px;
            border-radius: 50%;
            background-color: #64748b;
            flex-shrink: 0;
        }

        /* Modern Back Button Style */
        .btn-back {
            display: inline-flex !important;
            align-items: center;
            justify-content: center;
            width: 34px;
            height: 34px;
            border-radius: 8px !important;
            background-color: #ffffff !important;
            border: 1px solid #cbd5e1 !important;
            color: #475569 !important;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05) !important;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1) !important;
            text-decoration: none !important;
        }
        .btn-back:hover {
            background-color: #f8fafc !important;
            color: var(--kmc-blue) !important;
            border-color: var(--kmc-blue) !important;
            transform: translateX(-2px);
            box-shadow: 0 4px 6px -1px rgba(13, 71, 161, 0.08), 0 2px 4px -2px rgba(13, 71, 161, 0.08) !important;
        }

        /* iPhone iOS Push Notification Toast */
        @keyframes iosSlideIn {
            0% {
                transform: translateY(-80px) scale(0.92);
                opacity: 0;
            }
            55% {
                transform: translateY(8px) scale(1.01);
                opacity: 0.95;
            }
            78% {
                transform: translateY(-3px) scale(0.995);
                opacity: 1;
            }
            100% {
                transform: translateY(0) scale(1);
                opacity: 1;
            }
        }

        /* Custom Toast Container */
        #ios-toast-container {
            position: fixed !important;
            top: 20px !important;
            right: 20px !important;
            z-index: 99999 !important;
            display: flex !important;
            flex-direction: column !important;
            gap: 12px !important;
            pointer-events: none !important;
            max-height: 100vh;
            overflow: visible;
        }

        .ios-toast-popup {
            pointer-events: auto !important;
            background: rgba(255, 255, 255, 0.82) !important;
            backdrop-filter: blur(20px) saturate(190%) !important;
            -webkit-backdrop-filter: blur(20px) saturate(190%) !important;
            border-radius: 22px !important;
            border: 1px solid rgba(255, 255, 255, 0.5) !important;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08), 0 1px 3px rgba(0, 0, 0, 0.05) !important;
            padding: 14px 16px !important;
            width: 380px !important;
            max-width: 380px !important;
            min-width: 380px !important;
            cursor: pointer !important;
            transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1) !important;
            font-family: 'Plus Jakarta Sans', sans-serif !important;
            box-sizing: border-box !important;
            overflow: hidden !important;
            animation: iosSlideIn 0.52s cubic-bezier(0.16, 1, 0.3, 1) forwards !important;
        }

        .ios-toast-popup.toast-leaving {
            animation: iosSlideOut 0.4s cubic-bezier(0.16, 1, 0.3, 1) forwards !important;
        }

        @keyframes iosSlideOut {
            0% { transform: translateX(0) scale(1); opacity: 1; }
            100% { transform: translateX(120%) scale(0.9); opacity: 0; }
        }

        .ios-toast-popup:hover {
            transform: scale(1.02) !important;
            box-shadow: 0 12px 35px rgba(0, 0, 0, 0.12) !important;
        }

        /* Override SweetAlert2 Default Styles for elements */
        .ios-toast-popup .swal2-html-container {

        /* ── SweetAlert Flash Toast ── */
        .swal-toast-custom {
            font-family: 'Instrument Sans', system-ui, sans-serif !important;
            font-size: 0.875rem !important;
            font-weight: 500 !important;
            border-radius: 12px !important;
            box-shadow: 0 8px 24px rgba(0,0,0,0.12) !important;
            padding: 12px 20px !important;
        }
        .swal-toast-success { border-left: 4px solid #22c55e !important; }
        .swal-toast-error   { border-left: 4px solid #ef4444 !important; }
        .swal-toast-warning { border-left: 4px solid #f59e0b !important; }
        .swal-toast-info    { border-left: 4px solid #3b82f6 !important; }
            margin: 0 !important;
            padding: 0 !important;
            text-align: left !important;
            color: inherit !important;
            font-size: inherit !important;
            font-family: inherit !important;
            overflow: hidden !important;
            background: transparent !important;
            width: 100% !important;
            box-sizing: border-box !important;
        }

        /* iOS Push Notification Header */
        .ios-header {
            display: flex !important;
            align-items: center !important;
            justify-content: space-between !important;
            margin-bottom: 8px !important;
            width: 100% !important;
        }

        .ios-app-info {
            display: flex !important;
            align-items: center !important;
            gap: 8px !important;
        }

        .ios-app-icon {
            width: 20px !important;
            height: 20px !important;
            border-radius: 5px !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            color: #ffffff !important;
            font-size: 11px !important;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1) !important;
            flex-shrink: 0 !important;
        }

        .ios-app-name {
            font-size: 14px !important;
            font-weight: 700 !important;
            color: #1d1d1f !important;
            line-height: 1.3 !important;
            display: -webkit-box !important;
            -webkit-line-clamp: 1 !important;
            -webkit-box-orient: vertical !important;
            overflow: hidden !important;
            word-break: break-all !important;
        }

        .ios-time {
            font-size: 11px !important;
            color: #aeaeab !important;
            font-weight: 500 !important;
        }

        /* iOS Push Notification Body */
        .ios-body {
            display: flex !important;
            flex-direction: column !important;
            gap: 2px !important;
            padding-left: 2px !important;
            width: 100% !important;
        }

        .ios-message {
            font-size: 13.5px !important;
            font-weight: 400 !important;
            color: #3a3a3c !important;
            line-height: 1.4 !important;
            word-break: break-word !important;
            display: -webkit-box !important;
            -webkit-line-clamp: 2 !important;
            -webkit-box-orient: vertical !important;
            overflow: hidden !important;
            text-overflow: ellipsis !important;
        }

        /* Custom Timer Progress Bar (KMC Theme) */
        .ios-timer-bar-container {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: transparent;
            border-bottom-left-radius: 22px;
            border-bottom-right-radius: 22px;
            overflow: hidden;
        }
        
        .ios-timer-bar {
            height: 100%;
            width: 100%;
            background: linear-gradient(90deg, #0d6efd 0%, #fd7e14 100%);
            transform-origin: left;
        }

        /* ── Mobile Responsive ── */
        .sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.4);
            z-index: 1029;
            transition: opacity 0.3s;
        }
        .sidebar-overlay.show { display: block; }

        .hamburger-btn {
            display: none;
            background: none;
            border: none;
            font-size: 1.4rem;
            color: var(--kmc-blue);
            padding: 8px 10px;
            cursor: pointer;
            min-width: 44px;
            min-height: 44px;
            align-items: center;
            justify-content: center;
            z-index: 1050;
            position: relative;
            -webkit-tap-highlight-color: rgba(13,71,161,0.1);
            border-radius: 8px;
            transition: background 0.2s;
        }
        .hamburger-btn:active {
            background: rgba(13,71,161,0.08);
        }

        @media (max-width: 991.98px) {
            body { height: auto !important; overflow: auto !important; }
            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s ease;
            }
            .sidebar.show { transform: translateX(0); }
            .main-content {
                margin-left: 0 !important;
                height: auto !important;
                overflow: visible !important;
            }
            .content {
                overflow-y: visible !important;
                height: auto !important;
            }
            .hamburger-btn { display: inline-flex; }
            .topbar { padding: 10px 16px !important; }
            .content { padding: 16px 16px 0 16px !important; }
            .content > div:last-child { margin-left: -16px !important; margin-right: -16px !important; }
            #ios-toast-container { right: 10px !important; left: 10px !important; max-width: 100% !important; }
            .ios-toast-popup { width: auto !important; min-width: 0 !important; max-width: 100% !important; border-radius: 16px !important; }
        }

        @media (max-width: 575.98px) {
            .topbar { padding: 8px 12px !important; }
            .content { padding: 12px 12px 0 12px !important; }
            .content > div:last-child { margin-left: -12px !important; margin-right: -12px !important; }
            body { font-size: 0.9rem; }
        }

        /* Premium Image Upload Preview Card (Full-width row layout) */
        .image-upload-preview-wrap {
            width: 100% !important;
            flex-basis: 100% !important;
            clear: both !important;
            background: #ffffff !important;
            border: 1px solid #cbd5e1 !important;
            border-radius: 14px !important;
            box-shadow: 0 4px 16px -4px rgba(15, 23, 42, 0.08) !important;
            padding: 10px 14px !important;
            margin-top: 10px !important;
            transition: all 0.25s ease;
        }
        .image-upload-preview-wrap:hover {
            border-color: #94a3b8 !important;
            box-shadow: 0 6px 20px -4px rgba(15, 23, 42, 0.12) !important;
        }
        .image-upload-preview-wrap .btn-clear-preview {
            background: #fee2e2 !important;
            color: #dc2626 !important;
            width: 32px !important;
            height: 32px !important;
            border-radius: 50% !important;
            border: none !important;
            transition: transform 0.2s ease, background 0.2s ease !important;
        }
        .image-upload-preview-wrap .btn-clear-preview:hover {
            background: #fca5a5 !important;
            color: #991b1b !important;
            transform: scale(1.08) !important;
        }
    </style>

    @stack('styles')

</head>

<body>

    <div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>
    @include('partials.sidebar')

    <div class="main-content">

        @include('partials.header')

        <div class="content">

            <div class="flex-grow-1 pb-4">
                @yield('content')
            </div>

            <div style="margin-left: -30px; margin-right: -30px;">
                @include('partials.footer')
            </div>

        </div>


    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Global SweetAlert2 Confirmation Modal Helper
        function confirmAction(event, options) {
            if (event && event.preventDefault) event.preventDefault();
            options = options || {};
            var form = event ? (event.target.closest('form') || event.target) : null;
            var title = options.title || 'Apakah Anda yakin?';
            var text = options.text || 'Tindakan ini tidak dapat dibatalkan!';
            var confirmText = options.confirmButtonText || 'Ya, Lanjutkan';
            var icon = options.icon || 'warning';

            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: title,
                    text: text,
                    icon: icon,
                    showCancelButton: true,
                    confirmButtonColor: '#dc3545',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: confirmText,
                    cancelButtonText: 'Batal',
                    customClass: {
                        popup: 'rounded-4 shadow-lg border-0',
                        confirmButton: 'px-4 py-2 rounded-3 fw-bold',
                        cancelButton: 'px-4 py-2 rounded-3'
                    }
                }).then(function(result) {
                    if (result.isConfirmed) {
                        if (typeof options.onConfirm === 'function') {
                            options.onConfirm();
                        } else if (form && form.tagName === 'FORM') {
                            form.submit();
                        }
                    }
                });
                return false;
            } else {
                return false;
            }
        }

        // Global Image Upload Inline Preview Helper
        function previewUploadImage(input) {
            if (!input) return;

            function safeEscape(str) {
                if (!str) return '';
                return String(str).replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;").replace(/"/g, "&quot;").replace(/'/g, "&#039;");
            }

            var parent = input.parentElement || input.parentNode;
            if (!parent) return;

            var container = parent.querySelector('.image-upload-preview-wrap');
            if (!container) {
                container = document.createElement('div');
                container.className = 'image-upload-preview-wrap mt-2.5 position-relative';
                if (input.nextSibling) {
                    parent.insertBefore(container, input.nextSibling);
                } else {
                    parent.appendChild(container);
                }
            }

            var file = input.files && input.files[0];
            if (!file) {
                container.innerHTML = '';
                container.style.display = 'none';
                return;
            }

            container.style.display = 'block';

            if (!file.type.match('image.*')) {
                container.innerHTML = 
                    '<div class="d-flex align-items-center justify-content-between p-1.5">' +
                        '<div class="d-flex align-items-center gap-2.5 overflow-hidden">' +
                            '<div class="rounded-3 bg-primary bg-opacity-10 p-2.5 text-primary flex-shrink-0 d-flex align-items-center justify-content-center" style="width:48px; height:48px;">' +
                                '<i class="fas fa-file-alt fs-4"></i>' +
                            '</div>' +
                            '<div class="text-truncate small">' +
                                '<div class="fw-bold text-dark text-truncate mb-0.5" style="font-size:0.85rem;">' + safeEscape(file.name) + '</div>' +
                                '<div class="text-muted" style="font-size:0.75rem;"><i class="far fa-hdd me-1"></i>' + (file.size/1024/1024).toFixed(2) + ' MB</div>' +
                            '</div>' +
                        '</div>' +
                        '<button type="button" class="btn btn-sm btn-clear-preview p-0 d-flex align-items-center justify-content-center flex-shrink-0" onclick="clearUploadImage(this)" title="Hapus File">' +
                            '<i class="fas fa-trash-alt" style="font-size: 0.82rem;"></i>' +
                        '</button>' +
                    '</div>';
                return;
            }

            var reader = new FileReader();
            reader.onload = function(e) {
                var imgSrc = e.target.result;
                container.innerHTML = 
                    '<div class="d-flex align-items-center justify-content-between gap-2.5">' +
                        '<div class="d-flex align-items-center gap-3 overflow-hidden" style="cursor: pointer;" onclick="openLightbox(\'' + imgSrc.replace(/'/g, "\\'") + '\')" title="Klik untuk lihat ukuran penuh">' +
                            '<div class="position-relative flex-shrink-0">' +
                                '<img src="' + imgSrc + '" alt="Preview" class="rounded-3 border" style="width: 58px; height: 58px; object-fit: cover; box-shadow: 0 4px 10px rgba(15,23,42,0.1);">' +
                            '</div>' +
                            '<div class="overflow-hidden">' +
                                '<div class="fw-bold text-dark small text-truncate mb-0.5" style="max-width: 210px; font-size: 0.85rem;">' + safeEscape(file.name) + '</div>' +
                                '<div class="d-flex align-items-center gap-2 text-muted" style="font-size: 0.74rem;">' +
                                    '<span><i class="far fa-file-image me-1 text-primary"></i>' + (file.size / 1024 / 1024).toFixed(2) + ' MB</span>' +
                                    '<span class="text-primary fw-semibold" style="font-size: 0.72rem;"><i class="fas fa-search-plus me-1"></i>Lihat Penuh</span>' +
                                '</div>' +
                            '</div>' +
                        '</div>' +
                        '<button type="button" class="btn btn-sm btn-clear-preview p-0 d-flex align-items-center justify-content-center flex-shrink-0" onclick="clearUploadImage(this)" title="Hapus Foto">' +
                            '<i class="fas fa-trash-alt" style="font-size: 0.82rem;"></i>' +
                        '</button>' +
                    '</div>';
            };
            reader.readAsDataURL(file);
        }

        function clearUploadImage(btn) {
            var wrap = btn.closest('.image-upload-preview-wrap');
            if (!wrap) return;
            var parent = wrap.parentElement;
            if (parent) {
                var input = parent.querySelector('input[type="file"]');
                if (input) input.value = '';
            }
            wrap.innerHTML = '';
            wrap.style.display = 'none';
        }

        window.previewUploadImage = previewUploadImage;
        window.clearUploadImage = clearUploadImage;

        // Auto attach change listener for file inputs named 'attachment'
        document.addEventListener('change', function(e) {
            var input = e.target;
            if (input && input.tagName === 'INPUT' && input.type === 'file') {
                if (input.name === 'attachment' || input.id === 'official-response-attachment' || input.id === 'admin-status-file' || input.id === 'opd-respond-file') {
                    previewUploadImage(input);
                }
            }
        });

        // Global Live Image Preview Modal Helper
        let currentPreviewInputId = null;
        let currentPreviewFileNameId = null;

        function previewImageInModal(input, fileNameId) {
            if (!input || !input.files || !input.files[0]) return;
            previewUploadImage(input);
            var file = input.files[0];
            var fileNameEl = fileNameId ? document.getElementById(fileNameId) : null;
            if (fileNameEl) fileNameEl.textContent = file.name;

            currentPreviewInputId = input.id;
            currentPreviewFileNameId = fileNameId;

            var previewBtn = document.getElementById(input.id + '-btn-preview');
            if (previewBtn) previewBtn.classList.remove('d-none');

            if (file.type.indexOf('image/') === 0) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    var modalImg = document.getElementById('globalImagePreviewModalImg');
                    var modalFileName = document.getElementById('globalImagePreviewModalFileName');
                    if (modalImg) modalImg.src = e.target.result;
                    if (modalFileName) modalFileName.textContent = file.name + ' (' + (file.size / 1024 / 1024).toFixed(2) + ' MB)';

                    var modalEl = document.getElementById('globalImagePreviewModal');
                    if (modalEl && typeof bootstrap !== 'undefined') {
                        var bsModal = bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl);
                        bsModal.show();
                    }
                };
                reader.readAsDataURL(file);
            }
        }

        function openCurrentPreviewModal() {
            var modalEl = document.getElementById('globalImagePreviewModal');
            if (modalEl && typeof bootstrap !== 'undefined') {
                var bsModal = bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl);
                bsModal.show();
            }
        }

        function clearGlobalImagePreviewModal() {
            if (currentPreviewInputId) {
                var input = document.getElementById(currentPreviewInputId);
                if (input) input.value = '';
                var previewBtn = document.getElementById(currentPreviewInputId + '-btn-preview');
                if (previewBtn) previewBtn.classList.add('d-none');
            }
            if (currentPreviewFileNameId) {
                var fileNameEl = document.getElementById(currentPreviewFileNameId);
                if (fileNameEl) fileNameEl.textContent = 'Belum ada file...';
            }
            var modalEl = document.getElementById('globalImagePreviewModal');
            if (modalEl && typeof bootstrap !== 'undefined') {
                var instance = bootstrap.Modal.getInstance(modalEl);
                if (instance) instance.hide();
            }
        }
    </script>

    <!-- Global Image Attachment Preview Modal -->
    <div class="modal fade" id="globalImagePreviewModal" tabindex="-1" aria-labelledby="globalImagePreviewModalLabel" aria-hidden="true" style="z-index: 1090;">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content rounded-4 border-0 shadow-lg overflow-hidden">
                <div class="modal-header bg-light border-bottom py-3 px-4">
                    <h5 class="modal-title fw-bold text-dark fs-6 mb-0" id="globalImagePreviewModalLabel">
                        <i class="fas fa-image me-2 text-primary"></i>Pratinjau Lampiran Gambar
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4 text-center bg-dark bg-opacity-10 d-flex flex-column align-items-center justify-content-center" style="min-height: 260px; max-height: 70vh; overflow-y: auto;">
                    <img id="globalImagePreviewModalImg" src="#" alt="Pratinjau Gambar" class="img-fluid rounded-3 shadow" style="max-height: 60vh; object-fit: contain;">
                    <div id="globalImagePreviewModalFileName" class="mt-3 small fw-semibold text-secondary bg-white px-3 py-1 rounded-pill border shadow-sm"></div>
                </div>
                <div class="modal-footer bg-light border-top py-2 px-4 d-flex justify-content-between">
                    <button type="button" class="btn btn-outline-danger btn-sm rounded-pill px-3" onclick="clearGlobalImagePreviewModal()"><i class="fas fa-trash-alt me-1"></i>Hapus Lampiran</button>
                    <button type="button" class="btn btn-primary btn-sm rounded-pill px-4" data-bs-dismiss="modal">Gunakan Foto Ini</button>
                </div>
            </div>
        </div>
    </div>
    @include('partials.flash-toast')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            setTimeout(function() {
                let alerts = document.querySelectorAll('.alert:not(.alert-permanent)');
                alerts.forEach(function(alert) {
                    alert.style.transition = "opacity 0.5s ease-out";
                    alert.style.opacity = "0";
                    setTimeout(function() {
                        alert.style.display = "none";
                    }, 500);
                });
            }, 5000);

            // iPhone "Ting" notification chime (chime tone synthesis)
            function playNotificationSound() {
                try {
                    const audioCtx = new (window.AudioContext || window.webkitAudioContext)();
                    const now = audioCtx.currentTime;

                    // Tone 1: G6 (1567.98 Hz)
                    const osc1 = audioCtx.createOscillator();
                    const gain1 = audioCtx.createGain();
                    osc1.type = 'sine';
                    osc1.frequency.setValueAtTime(1567.98, now);
                    gain1.gain.setValueAtTime(0, now);
                    gain1.gain.linearRampToValueAtTime(0.12, now + 0.005);
                    gain1.gain.exponentialRampToValueAtTime(0.001, now + 0.25);
                    osc1.connect(gain1);
                    gain1.connect(audioCtx.destination);
                    
                    // Tone 2: C7 (2093.00 Hz) - starting slightly later
                    const osc2 = audioCtx.createOscillator();
                    const gain2 = audioCtx.createGain();
                    osc2.type = 'sine';
                    osc2.frequency.setValueAtTime(2093.00, now + 0.06);
                    gain2.gain.setValueAtTime(0, now + 0.06);
                    gain2.gain.linearRampToValueAtTime(0.18, now + 0.065);
                    gain2.gain.exponentialRampToValueAtTime(0.001, now + 0.40);
                    osc2.connect(gain2);
                    gain2.connect(audioCtx.destination);

                    osc1.start(now);
                    osc1.stop(now + 0.3);
                    osc2.start(now + 0.06);
                    osc2.stop(now + 0.5);
                } catch(e) {
                    console.error('Audio not supported or blocked:', e);
                }
            }

            // Custom Vanilla JS Notification Manager
            function showIosToast(platformName, platformClass, platformIcon, iconColor, senderText, bodyText, onClickUrl = null) {
                const containerId = 'ios-toast-container';
                let container = document.getElementById(containerId);
                if (!container) {
                    container = document.createElement('div');
                    container.id = containerId;
                    document.body.appendChild(container);
                }

                // Enforce max 5 notifications
                if (container.children.length >= 5) {
                    const oldest = container.firstElementChild;
                    oldest.classList.add('toast-leaving');
                    setTimeout(() => oldest.remove(), 400);
                }

                const toast = document.createElement('div');
                toast.className = `ios-toast-popup toast-${platformClass}`;
                toast.innerHTML = `
                    <div class="ios-header">
                        <div class="ios-app-info">
                        <div class="ios-app-icon" style="background-color: ${iconColor};">
                            ${platformIcon}
                        </div>
                        <span class="ios-app-name">${senderText}</span>
                        </div>
                        <span class="ios-time">baru saja</span>
                    </div>
                    <div class="ios-body">
                        <div class="ios-message">${bodyText}</div>
                    </div>
                    <div class="ios-timer-bar-container">
                        <div class="ios-timer-bar"></div>
                    </div>
                `;

                if (onClickUrl) {
                    toast.onclick = () => window.open(onClickUrl, '_blank');
                }

                container.appendChild(toast);
                playNotificationSound();

                // Timer Logic
                const timerBar = toast.querySelector('.ios-timer-bar');
                toast.offsetHeight; // Force reflow
                
                let remainingTime = 10000;
                let timerId;
                let startTime = Date.now();

                const startTimer = () => {
                    timerBar.style.transition = `width ${remainingTime}ms linear`;
                    timerBar.style.width = '0%';
                    timerId = setTimeout(() => {
                        toast.classList.add('toast-leaving');
                        setTimeout(() => toast.remove(), 400);
                    }, remainingTime);
                };

                const pauseTimer = () => {
                    clearTimeout(timerId);
                    remainingTime -= (Date.now() - startTime);
                    const computedWidth = window.getComputedStyle(timerBar).width;
                    timerBar.style.transition = 'none';
                    timerBar.style.width = computedWidth;
                };

                toast.addEventListener('mouseenter', pauseTimer);
                toast.addEventListener('mouseleave', () => {
                    startTime = Date.now();
                    startTimer();
                });

                startTimer();
            }

            // Notification Polling with Content
            let lastNotificationId = null;

            function checkNotifications() {
                fetch('/notifications-data', {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(notifications => {
                    // On first load, initialize lastNotificationId
                    if (lastNotificationId === null) {
                        if (notifications.length === 0) {
                            lastNotificationId = 0;
                        } else {
                            lastNotificationId = Math.max(...notifications.map(n => n.id));
                        }
                        return;
                    }

                    if (notifications.length === 0) return;

                    // Find new notifications
                    let newNotifications = notifications.filter(n => n.id > lastNotificationId).reverse();
                    
                    if (newNotifications.length > 0) {
                        // Update the last ID
                        lastNotificationId = Math.max(...notifications.map(n => n.id));

                        // Show a toast for each new notification
                        newNotifications.forEach((notif, index) => {
                            setTimeout(() => {
                                // Gunakan nama pengirim untuk judul toast
                                let senderText = notif.sender_name ? notif.sender_name : (notif.sender ? notif.sender : 'Sistem');
                                let bodyText = notif.comment_message ? notif.comment_message : (notif.message ? notif.message : 'Ada pembaruan baru');
                                
                                // Hilangkan Tag Simadu KMC
                                bodyText = bodyText.replace(/@?simadu\s*kmc\s*/gi, '');

                                if(bodyText.length > 160) bodyText = bodyText.substring(0, 160) + '...';

                                let platformIcon = '<i class="fa-solid fa-bell"></i>';
                                let iconColor = '#0d47a1'; 
                                let platformClass = 'default';
                                let platformName = 'Sistem';

                                if (notif.title && notif.title.toLowerCase().includes('facebook')) {
                                    platformIcon = '<i class="fa-brands fa-facebook-f"></i>';
                                    iconColor = '#1877F2';
                                    platformClass = 'facebook';
                                    platformName = 'Facebook';
                                } else if (notif.title && notif.title.toLowerCase().includes('instagram')) {
                                    platformIcon = '<i class="fa-brands fa-instagram"></i>';
                                    iconColor = '#E4405F';
                                    platformClass = 'instagram';
                                    platformName = 'Instagram';
                                } else if (notif.title && notif.title.toLowerCase().includes('whatsapp')) {
                                    platformIcon = '<i class="fa-brands fa-whatsapp"></i>';
                                    iconColor = '#25D366';
                                    platformClass = 'whatsapp';
                                    platformName = 'WhatsApp';
                                } else if (notif.title && notif.title.toLowerCase().includes('web simadu')) {
                                    platformIcon = '<i class="fa-solid fa-globe"></i>';
                                    iconColor = '#0d6efd';
                                    platformClass = 'web';
                                    platformName = 'Sistem Tiket';
                                }

                                let targetUrl = notif.permalink ? notif.permalink : '/notifications';
                                let url = `/notification/${notif.id}/detail?url=${encodeURIComponent(targetUrl)}`;
                                // Parameter kelima dipakai sebagai judul toast: tampilkan nama pelapor.
                                showIosToast(platformName, platformClass, platformIcon, iconColor, senderText, bodyText, url);
                            }, index * 1200);  
                        });
                    }
                })
                .catch(error => console.error('Error fetching notifications data:', error));
            }

            // (Dummy testing script removed to use artisan command)

            // Initial check to set base ID, then poll every 5 seconds
            checkNotifications();
            setInterval(checkNotifications, 5000);
        });
    </script>
</body>

<!-- Lightbox Preview Modal -->
<div id="globalLightbox" style="display:none; position:fixed; top:0; left:0; width:100vw; height:100vh; height:100dvh; z-index:999999; background:rgba(15,23,42,0.92); backdrop-filter:blur(10px); -webkit-backdrop-filter:blur(10px); cursor:zoom-out; align-items:center; justify-content:center;">
    <a id="lightboxDownloadBtn" href="#" download target="_blank" style="position:absolute; top:20px; right:76px; background:rgba(255,255,255,0.18); border:none; color:#fff; height:44px; padding:0 18px; border-radius:22px; font-size:0.88rem; font-weight:600; cursor:pointer; z-index:1000000; display:flex; align-items:center; gap:8px; backdrop-filter:blur(4px); transition:all 0.2s; text-decoration:none;" title="Unduh file media ini">
        <i class="fas fa-download"></i> <span>Unduh</span>
    </a>
    <button onclick="closeLightbox()" style="position:absolute; top:20px; right:20px; background:rgba(255,255,255,0.15); border:none; color:#fff; width:44px; height:44px; border-radius:50%; font-size:1.4rem; cursor:pointer; z-index:1000000; display:flex; align-items:center; justify-content:center; backdrop-filter:blur(4px); transition:all 0.2s;" title="Tutup">
        <i class="fas fa-times"></i>
    </button>
    <div id="lightboxSpinner" class="spinner-border text-light" role="status" style="display:none; width: 3rem; height: 3rem; position:absolute; z-index:999999;">
        <span class="visually-hidden">Memuat...</span>
    </div>
    <div id="lightboxError" class="text-center text-white p-4" style="display:none; position:absolute; max-width: 90vw; z-index:999999;">
        <i class="fas fa-exclamation-triangle fa-3x text-warning mb-3"></i>
        <h5 class="fw-bold mb-2">Gagal Memuat Lampiran</h5>
        <p class="text-white-50 small mb-3">Pratinjau lampiran ini tidak dapat ditampilkan secara langsung di perangkat Anda.</p>
        <a id="lightboxErrorDlBtn" href="#" target="_blank" download class="btn btn-light rounded-pill px-4 fw-bold shadow">
            <i class="fas fa-external-link-alt me-2"></i>Buka / Unduh File
        </a>
    </div>
    <img id="lightboxImg" src="" alt="Preview" style="display:none; max-width:94vw; max-height:86vh; max-height:86dvh; object-fit:contain; border-radius:12px; box-shadow:0 20px 60px rgba(0,0,0,0.5); cursor:default; animation:lightboxIn 0.25s ease;">
    <video id="lightboxVideo" controls style="display:none; max-width:94vw; max-height:86vh; max-height:86dvh; border-radius:12px; box-shadow:0 20px 60px rgba(0,0,0,0.5); cursor:default; outline:none; animation:lightboxIn 0.25s ease;"></video>
    <iframe id="lightboxPdf" src="" style="display:none; width:92vw; height:86vh; height:86dvh; border:none; border-radius:12px; box-shadow:0 20px 60px rgba(0,0,0,0.5); background:#fff; animation:lightboxIn 0.25s ease;"></iframe>
</div>
<style>
    @keyframes lightboxIn {
        from { opacity:0; transform:scale(0.92); }
        to { opacity:1; transform:scale(1); }
    }
    .lightbox-img {
        cursor: zoom-in !important;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .lightbox-img:hover {
        transform: scale(1.02);
        box-shadow: 0 8px 24px rgba(0,0,0,0.15);
    }
    #globalLightbox button:hover, #globalLightbox a:hover {
        background: rgba(255,255,255,0.32) !important;
        transform: scale(1.05);
    }
</style>
<script>
    function openLightbox(src, type) {
        var lb = document.getElementById('globalLightbox');
        if (!lb) return;

        // Ensure lightbox is attached directly to document.body to avoid parent transform pinning
        if (lb.parentElement !== document.body) {
            document.body.appendChild(lb);
        }

        var img = document.getElementById('lightboxImg');
        var vid = document.getElementById('lightboxVideo');
        var pdf = document.getElementById('lightboxPdf');
        var dlBtn = document.getElementById('lightboxDownloadBtn');
        var spinner = document.getElementById('lightboxSpinner');
        var errorBox = document.getElementById('lightboxError');
        var errorDlBtn = document.getElementById('lightboxErrorDlBtn');

        if (dlBtn && src) {
            dlBtn.href = src;
        }
        if (errorDlBtn && src) {
            errorDlBtn.href = src;
        }

        var cleanSrc = typeof src === 'string' ? src.toLowerCase().split('?')[0] : '';
        var isPdf = type === 'pdf' || cleanSrc.endsWith('.pdf');
        var isVid = type === 'video' || type === true || cleanSrc.endsWith('.mp4') || cleanSrc.endsWith('.mov') || cleanSrc.endsWith('.avi') || cleanSrc.endsWith('.3gp') || cleanSrc.endsWith('.webm');

        if (vid) { vid.pause(); vid.style.display = 'none'; vid.src = ''; }
        if (img) { img.style.display = 'none'; img.src = ''; }
        if (pdf) { pdf.style.display = 'none'; pdf.src = ''; }
        if (errorBox) { errorBox.style.display = 'none'; }
        if (spinner) { spinner.style.display = 'block'; }

        var isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);

        if (isPdf) {
            if (spinner) spinner.style.display = 'none';
            if (isMobile) {
                // Mobile PDF: open directly in new tab or display clean action card to avoid blank iframe on mobile
                window.open(src, '_blank');
                return;
            } else if (pdf) {
                pdf.src = src;
                pdf.style.display = 'block';
            }
        } else if (isVid) {
            if (vid) {
                vid.src = src;
                vid.style.display = 'block';
                vid.onloadeddata = function() {
                    if (spinner) spinner.style.display = 'none';
                };
                vid.onerror = function() {
                    if (spinner) spinner.style.display = 'none';
                    if (vid) vid.style.display = 'none';
                    if (errorBox) errorBox.style.display = 'block';
                };
                vid.play().catch(function(){});
            }
        } else {
            if (img) {
                img.onload = function() {
                    if (spinner) spinner.style.display = 'none';
                };
                img.onerror = function() {
                    if (spinner) spinner.style.display = 'none';
                    if (img) img.style.display = 'none';
                    if (errorBox) errorBox.style.display = 'block';
                };
                img.src = src;
                img.style.display = 'block';
            }
        }
        lb.style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }

    function closeLightbox() {
        var lb = document.getElementById('globalLightbox');
        if (!lb) return;
        var vid = document.getElementById('lightboxVideo');
        var pdf = document.getElementById('lightboxPdf');
        var spinner = document.getElementById('lightboxSpinner');
        var errorBox = document.getElementById('lightboxError');

        if (vid) {
            vid.pause();
            vid.src = '';
        }
        if (pdf) {
            pdf.src = '';
        }
        if (spinner) spinner.style.display = 'none';
        if (errorBox) errorBox.style.display = 'none';

        lb.style.display = 'none';
        document.body.style.overflow = '';
    }

    document.getElementById('globalLightbox')?.addEventListener('click', function(e) {
        if (e.target === this) closeLightbox();
    });
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') closeLightbox();
    });
    document.addEventListener('click', function(e) {
        var img = e.target.closest('.lightbox-img');
        if (img) {
            e.preventDefault();
            e.stopPropagation();
            openLightbox(img.src, false);
        }
    });
</script>

@stack('scripts')

<!-- Global Floating Live Chat Notification Toast Container -->
<div id="global-chat-toast-container" class="position-fixed top-0 end-0 p-3" style="z-index: 1085; pointer-events: none;"></div>

<script>
    // Mobile sidebar toggle
    function toggleSidebar() {
        var sidebar = document.querySelector('.sidebar');
        var overlay = document.getElementById('sidebarOverlay');
        if (sidebar && overlay) {
            sidebar.classList.toggle('show');
            overlay.classList.toggle('show');
        }
    }

    // Global Live Chat Notification System
    (function() {
        var notifiedChatIds = new Set();
        var isChatInitialPoll = true;

        function escapeChatText(str) {
            var node = document.createElement('div');
            node.textContent = str || '';
            return node.innerHTML;
        }

        function playIncomingMessageChime() {
            try {
                var audioCtx = new (window.AudioContext || window.webkitAudioContext)();
                var osc = audioCtx.createOscillator();
                var gain = audioCtx.createGain();
                osc.type = 'sine';
                osc.frequency.setValueAtTime(587.33, audioCtx.currentTime);
                osc.frequency.exponentialRampToValueAtTime(880, audioCtx.currentTime + 0.12);
                gain.gain.setValueAtTime(0.15, audioCtx.currentTime);
                gain.gain.exponentialRampToValueAtTime(0.001, audioCtx.currentTime + 0.3);
                osc.connect(gain);
                gain.connect(audioCtx.destination);
                osc.start();
                osc.stop(audioCtx.currentTime + 0.3);
            } catch(e) {}
        }

        async function checkGlobalUnreadChatNotifications() {
            if (document.hidden) return;
            try {
                var response = await fetch('{{ route('chat.unread.notifications') }}', {
                    headers: { 'Accept': 'application/json' },
                    credentials: 'same-origin'
                });
                if (!response.ok) return;
                var notifications = await response.json();

                notifications.forEach(function(notif) {
                    if (!notifiedChatIds.has(notif.id)) {
                        notifiedChatIds.add(notif.id);
                        if (!isChatInitialPoll) {
                            showGlobalChatToast(notif);
                        }
                    }
                });
                isChatInitialPoll = false;
            } catch (e) {
                console.warn('Global chat notification check failed', e);
            }
        }

        var shownToastMsgIds = new Set();

        function showGlobalChatToast(notif) {
            if (!notif || !notif.id) return;
            var msgId = String(notif.id);
            if (shownToastMsgIds.has(msgId)) return;
            shownToastMsgIds.add(msgId);

            var container = document.getElementById('global-chat-toast-container');
            if (!container) {
                container = document.createElement('div');
                container.id = 'global-chat-toast-container';
                container.style.cssText = 'position: fixed; top: 20px; right: 24px; z-index: 1090; display: flex; flex-direction: column; gap: 10px; max-width: 380px; width: calc(100vw - 48px); pointer-events: none;';
                document.body.appendChild(container);
            }

            playIncomingMessageChime();

            var currentPath = window.location.pathname;
            var isCurrentChatPage = currentPath.indexOf('/chat/' + notif.ticket_id) !== -1;

            var toast = document.createElement('div');
            toast.className = 'chat-toast-item bg-white overflow-hidden fade show position-relative';
            toast.style.cssText = 'pointer-events: auto; width: 360px; max-width: 100%; border-radius: 16px !important; box-shadow: 0 14px 36px rgba(15,23,42,0.18) !important; border: 1px solid #e2e8f0; transition: transform 0.25s ease, opacity 0.25s ease; cursor: pointer;';

            var avatarHtml = notif.sender_photo 
                ? '<img src="' + notif.sender_photo + '" alt="' + escapeChatText(notif.sender_name) + '" class="rounded-circle flex-shrink-0" style="width: 32px; height: 32px; object-fit: cover;">'
                : '<div class="rounded-circle bg-primary text-white flex-shrink-0 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; font-size: 0.85rem;"><i class="' + (notif.channel_icon || 'fas fa-comments') + '"></i></div>';

            var timeText = notif.created_at || 'baru saja';
            var trackingText = notif.tracking_number ? 'Tiket #' + notif.tracking_number : 'Livechat Tiket';
            var msgSnippet = notif.message ? (notif.message.length > 95 ? notif.message.substring(0, 95) + '...' : notif.message) : 'Mengirim lampiran';

            toast.innerHTML = '<div class="p-3">' +
                '<div class="d-flex align-items-start justify-content-between mb-2">' +
                    '<div class="d-flex align-items-center gap-2.5 overflow-hidden pe-2">' +
                        avatarHtml +
                        '<div class="d-flex flex-column overflow-hidden">' +
                            '<div class="fw-bold text-dark text-truncate" style="font-size: 0.9rem; line-height: 1.2;">' + escapeChatText(notif.sender_name) + '</div>' +
                            '<div class="text-primary fw-semibold text-truncate mt-0.5" style="font-size: 0.72rem; line-height: 1.2;"><i class="fas fa-comments me-1"></i>Livechat · ' + escapeChatText(trackingText) + '</div>' +
                        '</div>' +
                    '</div>' +
                    '<span class="text-muted flex-shrink-0" style="font-size: 0.75rem; margin-top: 2px;">' + escapeChatText(timeText) + '</span>' +
                '</div>' +
                '<div class="text-secondary" style="font-size: 0.85rem; line-height: 1.45; word-break: break-word;">' + escapeChatText(msgSnippet) + '</div>' +
            '</div>' +
            '<div class="toast-progress-bar" style="height: 4px; background: linear-gradient(90deg, #0d47a1, #f57c00); width: 100%; animation: toastProgress 4.8s linear forwards;"></div>';

            // Touch Swipe to Dismiss (Left / Right) for Mobile
            var startX = 0;
            var startY = 0;
            var deltaX = 0;
            var isSwiping = false;

            toast.addEventListener('touchstart', function(e) {
                if (e.touches.length !== 1) return;
                startX = e.touches[0].clientX;
                startY = e.touches[0].clientY;
                deltaX = 0;
                isSwiping = false;
                toast.style.transition = 'none';
            }, { passive: true });

            toast.addEventListener('touchmove', function(e) {
                if (e.touches.length !== 1) return;
                var currentX = e.touches[0].clientX;
                var currentY = e.touches[0].clientY;
                var diffX = currentX - startX;
                var diffY = currentY - startY;

                if (Math.abs(diffX) > Math.abs(diffY) && Math.abs(diffX) > 8) {
                    isSwiping = true;
                    deltaX = diffX;
                    toast.style.transform = 'translateX(' + deltaX + 'px)';
                    toast.style.opacity = Math.max(0, 1 - Math.abs(deltaX) / 240);
                }
            }, { passive: true });

            var handleTouchEnd = function(e) {
                if (!isSwiping) {
                    toast.style.transition = 'transform 0.25s ease, opacity 0.25s ease';
                    toast.style.transform = 'translateX(0)';
                    toast.style.opacity = '1';
                    return;
                }

                toast.style.transition = 'transform 0.25s ease, opacity 0.25s ease';
                if (Math.abs(deltaX) > 75) {
                    var targetX = deltaX > 0 ? '120%' : '-120%';
                    toast.style.transform = 'translateX(' + targetX + ')';
                    toast.style.opacity = '0';
                    setTimeout(function() {
                        if (toast && toast.parentNode) toast.remove();
                    }, 250);
                } else {
                    toast.style.transform = 'translateX(0)';
                    toast.style.opacity = '1';
                }
            };

            toast.addEventListener('touchend', handleTouchEnd, { passive: true });
            toast.addEventListener('touchcancel', handleTouchEnd, { passive: true });

            toast.onclick = function(e) {
                if (isSwiping || Math.abs(deltaX) > 10) {
                    return;
                }
                if (!isCurrentChatPage && notif.chat_url) {
                    window.location.href = notif.chat_url;
                }
                toast.classList.remove('show');
                setTimeout(function() { toast.remove(); }, 250);
            };

            container.appendChild(toast);

            setTimeout(function() {
                if (toast && toast.parentNode && !isSwiping) {
                    toast.classList.remove('show');
                    setTimeout(function() { if (toast && toast.parentNode) toast.remove(); }, 250);
                }
            }, 4800);
        }

        window.showGlobalChatToast = showGlobalChatToast;
        window.playIncomingMessageChime = playIncomingMessageChime;

        @auth
            setInterval(checkGlobalUnreadChatNotifications, 4000);
            setTimeout(checkGlobalUnreadChatNotifications, 1000);
        @endauth
    })();
</script>

</html>
