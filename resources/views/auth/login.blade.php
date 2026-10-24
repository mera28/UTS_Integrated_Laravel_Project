<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk Akun - Zahiyyah's Fit</title>
    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Bootstrap 5.3 & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        :root {
            --brand-primary: #B76E79;
            --brand-primary-hover: #9F5862;
            --brand-light: #FBF5F5;
            --brand-accent: #E8C5C8;
            --brand-dark: #2A2426;
        }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: linear-gradient(135deg, #FFF9FA 0%, #F5ECEB 45%, #EFE8E5 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 30px 15px;
            color: var(--brand-dark);
        }
        .auth-card {
            background: #ffffff;
            border-radius: 24px;
            box-shadow: 0 20px 45px rgba(183, 110, 121, 0.12);
            border: 1px solid rgba(232, 197, 200, 0.4);
            overflow: hidden;
            max-width: 480px;
            width: 100%;
        }
        .auth-header {
            background: linear-gradient(145deg, #FFFFFF 0%, #FCF7F7 100%);
            padding: 35px 35px 20px;
            text-align: center;
            border-bottom: 1px solid #F6ECEC;
        }
        .auth-brand-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: #FDF2F4;
            color: var(--brand-primary);
            font-weight: 700;
            padding: 6px 16px;
            border-radius: 999px;
            font-size: 0.85rem;
            margin-bottom: 14px;
            border: 1px solid rgba(183, 110, 121, 0.2);
        }
        .auth-body {
            padding: 30px 35px 35px;
        }
        .demo-box {
            background: #FDF9FA;
            border: 1px dashed #E2B9BE;
            border-radius: 16px;
            padding: 16px;
            margin-bottom: 24px;
        }
        .demo-btn {
            font-size: 0.78rem;
            font-weight: 600;
            border-radius: 10px;
            padding: 6px 12px;
            transition: all 0.2s ease;
        }
        .form-control {
            border-radius: 12px;
            padding: 12px 16px;
            border: 1.5px solid #E6DCDD;
            font-size: 0.95rem;
            transition: all 0.2s;
        }
        .form-control:focus {
            border-color: var(--brand-primary);
            box-shadow: 0 0 0 4px rgba(183, 110, 121, 0.15);
        }
        .btn-brand {
            background-color: var(--brand-primary);
            color: #ffffff;
            font-weight: 700;
            border-radius: 12px;
            padding: 13px 20px;
            border: none;
            width: 100%;
            transition: all 0.2s ease;
        }
        .btn-brand:hover {
            background-color: var(--brand-primary-hover);
            color: #ffffff;
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(183, 110, 121, 0.25);
        }
        .btn-brand:active {
            transform: translateY(0);
        }
    </style>
</head>
<body>
    <div class="auth-card">
        <div class="auth-header">
            <span class="auth-brand-badge">
                <i class="bi bi-gem"></i> Zahiyyah's Fit
            </span>
            <h1 class="h4 fw-bold mb-1 text-dark">Selamat Datang Kembali</h1>
            <p class="text-muted small mb-0">Masuk untuk mengelola e-commerce, stok, dan kasir penjualan</p>
        </div>

        <div class="auth-body">
            <!-- Flash Messages -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show border-0 rounded-3 mb-3 small d-flex align-items-center" role="alert">
                    <i class="bi bi-check-circle-fill me-2 fs-5"></i>
                    <div>{{ session('success') }}</div>
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

            <!-- Quick Demo Login Box -->
            <div class="demo-box">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="fw-bold small text-dark"><i class="bi bi-lightning-charge-fill text-warning me-1"></i> Quick Demo Login:</span>
                    <span class="badge bg-light text-secondary border">Klik untuk coba role</span>
                </div>
                <div class="d-flex flex-wrap gap-2">
                    <button type="button" class="btn btn-outline-danger demo-btn flex-fill" onclick="fillCredentials('admin@zahiyyah.com', 'password')">
                        <i class="bi bi-shield-lock me-1"></i> Admin Owner
                    </button>
                    <button type="button" class="btn btn-outline-primary demo-btn flex-fill" onclick="fillCredentials('staff@zahiyyah.com', 'password')">
                        <i class="bi bi-person-badge me-1"></i> Staff Kasir
                    </button>
                    <button type="button" class="btn btn-outline-success demo-btn flex-fill" onclick="fillCredentials('customer@zahiyyah.com', 'password')">
                        <i class="bi bi-bag-heart me-1"></i> Customer
                    </button>
                </div>
            </div>

            <!-- Login Form -->
            <form action="{{ route('login') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="email" class="form-label small fw-semibold text-secondary">Alamat Email</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0 rounded-start-3 text-muted"><i class="bi bi-envelope"></i></span>
                        <input type="email" class="form-control rounded-end-3 @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" placeholder="nama@email.com" required autofocus>
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
                        <span class="input-group-text bg-light border-end-0 rounded-start-3 text-muted"><i class="bi bi-key"></i></span>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" placeholder="••••••••" required>
                        <button class="btn btn-light border border-start-0 rounded-end-3 text-muted" type="button" onclick="togglePassword()">
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
                            Ingat saya di perangkat ini
                        </label>
                    </div>
                </div>

                <button type="submit" class="btn btn-brand mb-3">
                    <i class="bi bi-box-arrow-in-right me-1"></i> Masuk ke Akun
                </button>

                <div class="text-center small text-muted mb-3">
                    Belum punya akun? <a href="{{ route('register') }}" class="text-decoration-none fw-bold" style="color: var(--brand-primary);">Daftar Customer Member</a>
                </div>

                <div class="text-center pt-2 border-top">
                    <a href="{{ route('shop.index') }}" class="text-secondary small text-decoration-none">
                        <i class="bi bi-arrow-left me-1"></i> Kembali ke Katalog Outfit (Guest)
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
