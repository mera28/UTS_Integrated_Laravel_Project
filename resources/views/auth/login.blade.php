<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk Member - Zahiyyah's Fit</title>
    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Playfair+Display:ital,wght@0,600;0,700;1,600&display=swap" rel="stylesheet">
    <!-- Bootstrap 5.3 & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="{{ asset('css/zahiyyah.css') }}">
    <style>
        :root {
            --brand-primary: #B76E79;
            --brand-primary-hover: #9F5862;
            --brand-dark: #2A2426;
            --brand-light: #FDF9FA;
            --brand-border: #E8DCDD;
        }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: linear-gradient(135deg, #FFF9FA 0%, #F8EEF0 50%, #F0E6E8 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px 16px;
            color: var(--brand-dark);
        }
        .auth-card {
            background: #FFFFFF;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(183, 110, 121, 0.08);
            border: 1px solid rgba(232, 220, 221, 0.6);
            overflow: hidden;
            max-width: 440px;
            width: 100%;
        }
        .auth-header {
            padding: 32px 32px 20px;
            text-align: center;
            border-bottom: 1px solid #F6EDED;
        }
        .auth-brand-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: #FDF2F4;
            color: var(--brand-primary);
            font-weight: 700;
            padding: 5px 14px;
            border-radius: 999px;
            font-size: 0.82rem;
            margin-bottom: 12px;
            border: 1px solid rgba(183, 110, 121, 0.2);
        }
        .auth-body {
            padding: 28px 32px 32px;
        }
        .form-control {
            border-radius: 10px;
            padding: 11px 14px;
            border: 1.5px solid #E2E8F0;
            font-size: 0.92rem;
            transition: all 0.2s;
        }
        .form-control:focus {
            border-color: var(--brand-primary);
            box-shadow: 0 0 0 3px rgba(183, 110, 121, 0.15);
        }
        .btn-brand {
            background-color: var(--brand-primary);
            color: #FFFFFF;
            font-weight: 600;
            border-radius: 10px;
            padding: 11px 20px;
            border: none;
            width: 100%;
            transition: all 0.2s ease;
        }
        .btn-brand:hover {
            background-color: var(--brand-primary-hover);
            color: #FFFFFF;
            transform: translateY(-1px);
            box-shadow: 0 6px 15px rgba(183, 110, 121, 0.2);
        }
        .demo-box {
            background: #FDF9FA;
            border: 1px dashed #E2B9BE;
            border-radius: 12px;
            padding: 12px 14px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="auth-card">
        <div class="auth-header">
            <span class="auth-brand-badge">
                <i class="bi bi-gem"></i> Zahiyyah's Fit
            </span>
            <h1 class="h5 fw-bold mb-1 text-dark">Masuk Member Pelanggan</h1>
            <p class="text-muted small mb-0">Nikmati pengalaman berbelanja outfit muslimah modern & elegan</p>
        </div>

        <div class="auth-body">
            <!-- Flash Notifications -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show border-0 rounded-3 mb-3 small d-flex align-items-center" role="alert">
                    <i class="bi bi-check-circle-fill me-2 fs-5"></i>
                    <div>{{ session('success') }}</div>
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('info'))
                <div class="alert alert-info alert-dismissible fade show border-0 rounded-3 mb-3 small d-flex align-items-center" role="alert">
                    <i class="bi bi-info-circle-fill me-2 fs-5"></i>
                    <div>{{ session('info') }}</div>
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show border-0 rounded-3 mb-3 small d-flex align-items-center" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2 fs-5"></i>
                    <div>{{ session('error') }}</div>
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Quick Demo Customer Fill -->
            <div class="demo-box d-flex align-items-center justify-content-between">
                <div>
                    <span class="small fw-bold text-dark d-block">Akun Demo Pelanggan:</span>
                    <span class="small text-muted font-monospace" style="font-size: 0.78rem;">customer@zahiyyah.com</span>
                </div>
                <button type="button" class="btn btn-outline-danger btn-sm rounded-pill px-3" style="font-size: 0.8rem;" onclick="fillCredentials('customer@zahiyyah.com', 'password')">
                    <i class="bi bi-lightning-charge-fill me-1"></i> Quick Fill
                </button>
            </div>

            <!-- Login Form -->
            <form action="{{ route('login') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="email" class="form-label small fw-semibold text-secondary">Alamat Email</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-envelope"></i></span>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" placeholder="nama@email.com" required autofocus>
                    </div>
                    @error('email')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <label for="password" class="form-label small fw-semibold text-secondary mb-0">Kata Sandi</label>
                    </div>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-key"></i></span>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" placeholder="••••••••" required>
                        <button class="btn btn-light border border-start-0 text-muted" type="button" onclick="togglePassword()">
                            <i class="bi bi-eye" id="toggleIcon"></i>
                        </button>
                    </div>
                    @error('password')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                        <label class="form-check-label small text-secondary" for="remember">
                            Ingat saya
                        </label>
                    </div>
                </div>

                <button type="submit" class="btn btn-brand mb-3">
                    <i class="bi bi-box-arrow-in-right me-1"></i> Masuk Sekarang
                </button>

                <div class="text-center small text-muted mb-3">
                    Belum punya akun? <a href="{{ route('register') }}" class="text-decoration-none fw-bold" style="color: var(--brand-primary);">Daftar Member Baru</a>
                </div>

                <!-- Separation Link to Portal Staff -->
                <div class="p-3 bg-light rounded-3 text-center mb-3 border">
                    <span class="small text-muted d-block mb-1">Petugas Toko atau Administrator?</span>
                    <a href="{{ route('portal.login') }}" class="small fw-bold text-dark text-decoration-none">
                        Masuk lewat Portal Internal Staff &rarr;
                    </a>
                </div>

                <div class="text-center">
                    <a href="{{ route('shop.index') }}" class="text-muted small text-decoration-none">
                        <i class="bi bi-arrow-left me-1"></i> Kembali ke Katalog Toko
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function fillCredentials(email, password) {
            document.getElementById('email').value = email;
            document.getElementById('password').value = password;
        }

        function togglePassword() {
            const passInput = document.getElementById('password');
            const icon = document.getElementById('toggleIcon');
            if (passInput.type === 'password') {
                passInput.type = 'text';
                icon.classList.remove('bi-eye');
                icon.classList.add('bi-eye-slash');
            } else {
                passInput.type = 'password';
                icon.classList.remove('bi-eye-slash');
                icon.classList.add('bi-eye');
            }
        }
    </script>
</body>
</html>
