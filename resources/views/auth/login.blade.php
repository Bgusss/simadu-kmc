<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SIMADU-KMC</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        body {
            background-color: #f8fafc;
            font-family: 'Plus Jakarta Sans', sans-serif;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-card {
            border: none;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(7, 31, 73, 0.08);
            overflow: hidden;
            width: 100%;
            max-width: 450px;
            background: white;
        }
        .login-header {
            background: linear-gradient(135deg, #0d47a1 0%, #071f49 100%);
            padding: 40px 20px;
            text-align: center;
            color: white;
            border-bottom: 4px solid #f57c00;
        }
        .login-header img {
            max-width: 80px;
            margin-bottom: 15px;
            filter: drop-shadow(0 4px 8px rgba(0,0,0,0.15));
        }
        .login-body {
            padding: 40px 30px;
            background: white;
        }
        .input-group-text {
            background: transparent;
            border-right: none;
            color: #64748b;
        }
        .form-control {
            border-left: none;
            color: #1e293b;
        }
        .form-control:focus {
            box-shadow: none;
            border-color: #dee2e6;
        }
        .input-group:focus-within {
            box-shadow: 0 0 0 0.25rem rgba(13, 71, 161, 0.15);
            border-radius: 0.375rem;
        }
        .input-group:focus-within .input-group-text,
        .input-group:focus-within .form-control {
            border-color: rgba(13, 71, 161, 0.5);
        }
        .btn-login {
            background: linear-gradient(135deg, #0d47a1 0%, #0c3e8c 100%);
            border: none;
            padding: 12px;
            font-weight: 600;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(13, 71, 161, 0.2);
            transition: all 0.2s ease;
        }
        .btn-login:hover {
            background: linear-gradient(135deg, #071f49 0%, #0d47a1 100%);
            box-shadow: 0 6px 16px rgba(13, 71, 161, 0.3);
            transform: translateY(-1px);
        }
    </style>
</head>
<body>
    <div class="login-card">
        <div class="login-header">
            <img src="{{ asset('images/kmc-logo.png') }}" alt="KMC Logo" onerror="this.style.display='none'">
            <h4 class="mb-0 fw-bold">SIMADU-KMC</h4>
            <p class="mb-0 text-white-50">Sistem Monitoring Aduan Multi Channel</p>
        </div>
        <div class="login-body">
            @if ($errors->any())
                <div class="alert alert-danger pb-0">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            
            <form action="{{ route('login') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="form-label text-muted small fw-bold text-uppercase">Username / Email</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                        <input type="text" name="email" class="form-control" placeholder="Masukkan username atau email" required autofocus>
                    </div>
                </div>
                <div class="mb-4">
                    <div class="d-flex justify-content-between">
                        <label class="form-label text-muted small fw-bold text-uppercase">Password</label>
                    </div>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-lock"></i></span>
                        <input type="password" name="password" class="form-control" placeholder="Masukkan password" required>
                    </div>
                </div>
                <div class="mb-4 form-check">
                    <input type="checkbox" class="form-check-input" id="remember" name="remember">
                    <label class="form-check-label text-muted" for="remember">Ingat Saya</label>
                </div>
                <div class="d-grid">
                    <button type="submit" class="btn btn-primary btn-login">
                        <i class="fas fa-sign-in-alt me-2"></i> Masuk
                    </button>
                </div>
            </form>
            <div class="text-center mt-4">
                <a href="/" class="text-decoration-none text-muted small">
                    <i class="fas fa-arrow-left me-1"></i> Kembali ke Beranda
                </a>
            </div>
        </div>
    </div>
</body>
</html>
