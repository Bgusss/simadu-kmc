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
<div id="globalLightbox" style="display:none; position:fixed; inset:0; z-index:99999; background:rgba(0,0,0,0.85); backdrop-filter:blur(8px); cursor:zoom-out; align-items:center; justify-content:center;">
    <button onclick="closeLightbox()" style="position:absolute; top:20px; right:20px; background:rgba(255,255,255,0.15); border:none; color:#fff; width:44px; height:44px; border-radius:50%; font-size:1.4rem; cursor:pointer; z-index:100000; display:flex; align-items:center; justify-content:center; backdrop-filter:blur(4px); transition:all 0.2s;">
        <i class="fas fa-times"></i>
    </button>
    <img id="lightboxImg" src="" alt="Preview" style="max-width:92vw; max-height:88vh; object-fit:contain; border-radius:12px; box-shadow:0 20px 60px rgba(0,0,0,0.5); cursor:default; animation:lightboxIn 0.25s ease;">
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
    #globalLightbox button:hover {
        background: rgba(255,255,255,0.3) !important;
        transform: scale(1.1);
    }
</style>
<script>
    function openLightbox(src) {
        var lb = document.getElementById('globalLightbox');
        document.getElementById('lightboxImg').src = src;
        lb.style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }
    function closeLightbox() {
        var lb = document.getElementById('globalLightbox');
        lb.style.display = 'none';
        document.body.style.overflow = '';
    }
    document.getElementById('globalLightbox').addEventListener('click', function(e) {
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
            openLightbox(img.src);
        }
    });
</script>

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
</script>

</html>
