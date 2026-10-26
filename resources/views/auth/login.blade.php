<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk Member - Zahiyyah's Fit Bali Boutique</title>
    <!-- Google Fonts: Cormorant Garamond, Playfair Display, & Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,500;0,600;0,700;1,400;1,600&family=Playfair+Display:ital,wght@0,500;0,600;0,700;1,500&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Bootstrap 5.3 & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="{{ asset('css/zahiyyah.css') }}?v={{ time() }}">
    <style>
        body {
            font-family: var(--font-sans);
            background: linear-gradient(135deg, #FAF7F2 0%, #F3ECE3 50%, #EDE4D8 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 32px 16px;
            color: var(--bali-espresso);
        }
        .auth-card {
            background: #FFFFFF;
            border-radius: 24px;
            box-shadow: 0 16px 45px rgba(44, 34, 30, 0.07);
            border: 1px solid var(--bali-border);
            overflow: hidden;
            max-width: 460px;
            width: 100%;
        }
        .auth-header {
            padding: 32px 32px 20px;
            text-align: center;
            border-bottom: 1px solid var(--bali-border-light);
            background-color: var(--bali-bg-ivory);
        }
        .auth-brand-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: #FFFFFF;
            color: var(--bali-tan);
            font-weight: 600;
            padding: 6px 16px;
            border-radius: 999px;
            font-size: 0.78rem;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            margin-bottom: 12px;
            border: 1px solid var(--bali-border);
        }
        .auth-body {
            padding: 28px 32px 36px;
            background: #FFFFFF;
        }
        .btn-google {
            background-color: #FFFFFF;
            color: var(--bali-espresso);
            border: 1px solid var(--bali-border);
            font-weight: 600;
            border-radius: 999px;
            padding: 11px 18px;
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            transition: all 0.25s ease;
            box-shadow: 0 2px 6px rgba(44, 34, 30, 0.04);
            font-size: 0.9rem;
        }
        .btn-google:hover {
            background-color: var(--bali-bg-linen);
            border-color: var(--bali-tan);
            color: var(--bali-tan-dark);
            transform: translateY(-1px);
        }
        .divider-line {
            display: flex;
            align-items: center;
            text-align: center;
            margin: 22px 0;
            color: var(--bali-sand-tag);
            font-size: 0.72rem;
            text-transform: uppercase;
            letter-spacing: 0.1em;
        }
        .divider-line::before,
        .divider-line::after {
            content: '';
            flex: 1;
            border-bottom: 1px solid var(--bali-border);
        }
        .divider-line::before { margin-right: 14px; }
        .divider-line::after { margin-left: 14px; }
        .auth-nav-tabs {
            display: flex;
            background: var(--bali-bg-linen);
            border-radius: 999px;
            padding: 4px;
            margin-bottom: 22px;
            border: 1px solid var(--bali-border-light);
        }
        .auth-nav-tab {
            flex: 1;
            text-align: center;
            padding: 8px 12px;
            font-size: 0.84rem;
            font-weight: 500;
            color: var(--bali-muted);
            text-decoration: none;
            border-radius: 999px;
            transition: all 0.2s;
        }
        .auth-nav-tab.active {
            background: #FFFFFF;
            color: var(--bali-espresso);
            font-weight: 600;
            box-shadow: 0 2px 6px rgba(44, 34, 30, 0.06);
        }
    </style>
</head>
<body>
    <div class="auth-card">
        <div class="auth-header">
            <span class="auth-brand-badge">
                <i class="bi bi-flower1"></i> Zahiyyah's Fit &bull; Bali Boutique
            </span>
            <h1 class="h4 fw-bold mb-1" style="font-family: var(--font-serif); color: var(--bali-espresso);">Masuk Member Pelanggan</h1>
            <p class="text-muted small mb-0">Lanjutkan untuk memesan busana modest dan lacak pengiriman Anda</p>
        </div>

        <div class="auth-body">
            <!-- Nav Tabs Switcher -->
            <div class="auth-nav-tabs">
                <a href="{{ route('login') }}" class="auth-nav-tab active">
                    <i class="bi bi-person-check me-1"></i> Masuk Akun
                </a>
                <a href="{{ route('register') }}" class="auth-nav-tab">
                    <i class="bi bi-person-plus me-1"></i> Buat Akun Baru
                </a>
            </div>

            <!-- Flash Notifications -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show border-0 rounded-3 mb-3 small d-flex align-items-center surface-ivory" role="alert">
                    <i class="bi bi-check-circle-fill me-2 fs-5" style="color: var(--bali-tan);"></i>
                    <div style="color: var(--bali-espresso);">{{ session('success') }}</div>
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('info'))
                <div class="alert alert-info alert-dismissible fade show border-0 rounded-3 mb-3 small d-flex align-items-center surface-sand" role="alert">
                    <i class="bi bi-info-circle-fill me-2 fs-5" style="color: var(--bali-tan-dark);"></i>
                    <div style="color: var(--bali-espresso);">{{ session('info') }}</div>
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show border-0 rounded-3 mb-3 small d-flex align-items-center p-3" style="background-color: #FAF0F0; border: 1px solid #E8C1C1 !important;" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2 fs-5 text-danger"></i>
                    <div class="text-danger">{{ session('error') }}</div>
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- PRIMARY ACTION: CONTINUE WITH GOOGLE -->
            <a href="{{ route('auth.google') }}" class="btn-google mb-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 48 48" class="me-2">
                    <path fill="#EA4335" d="M24 9.5c3.54 0 6.71 1.22 9.21 3.6l6.85-6.85C35.9 2.38 30.47 0 24 0 14.62 0 6.51 5.38 2.56 13.22l7.98 6.19C12.43 13.72 17.74 9.5 24 9.5z"/>
                    <path fill="#4285F4" d="M46.98 24.55c0-1.57-.15-3.09-.38-4.55H24v9.02h12.94c-.58 2.96-2.26 5.48-4.78 7.18l7.73 6c4.51-4.18 7.09-10.36 7.09-17.65z"/>
                    <path fill="#FBBC05" d="M10.53 28.59c-.48-1.45-.76-2.99-.76-4.59s.27-3.14.76-4.59l-7.98-6.19C.92 16.46 0 20.12 0 24c0 3.88.92 7.54 2.56 10.78l7.97-6.19z"/>
                    <path fill="#34A853" d="M24 48c6.48 0 11.93-2.13 15.89-5.81l-7.73-6c-2.15 1.45-4.92 2.3-8.16 2.3-6.26 0-11.57-4.22-13.47-9.91l-7.98 6.19C6.51 42.62 14.62 48 24 48z"/>
                    <path fill="none" d="M0 0h48v48H0z"/>
                </svg>
                Continue with Google
            </a>

            <!-- Divider -->
            <div class="divider-line">
                <span>atau masuk dengan email</span>
            </div>

            <!-- Login Form -->
            <form action="{{ route('login') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="email" class="form-label-custom">Alamat Email</label>
                    <div class="input-group">
                        <span class="input-group-text border-end-0 text-muted surface-ivory"><i class="bi bi-envelope"></i></span>
                        <input type="email" class="form-control form-control-custom @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" placeholder="nama@email.com" required autofocus>
                    </div>
                    @error('email')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label-custom">Kata Sandi</label>
                    <div class="input-group">
                        <span class="input-group-text border-end-0 text-muted surface-ivory"><i class="bi bi-key"></i></span>
                        <input type="password" class="form-control form-control-custom @error('password') is-invalid @enderror" id="password" name="password" placeholder="••••••••" required>
                        <button class="btn btn-sand border-start-0 text-muted" type="button" onclick="togglePassword()">
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
                        <label class="form-check-label small" style="color: var(--bali-taupe);" for="remember">
                            Ingat saya
                        </label>
                    </div>
                </div>

                <button type="submit" class="btn btn-brand-primary w-100 py-3 mb-3">
                    <i class="bi bi-box-arrow-in-right me-1"></i> Masuk Sekarang
                </button>

                <div class="text-center small text-muted mb-3">
                    Belum punya akun? <a href="{{ route('register') }}" class="text-decoration-none fw-bold" style="color: var(--bali-tan);">Daftar Member Cepat</a>
                </div>

                <!-- Separation Link to Portal Staff -->
                <div class="p-3 rounded-3 text-center mb-3 surface-ivory border" style="border-color: var(--bali-border) !important;">
                    <span class="small text-muted d-block mb-1">Petugas Toko atau Administrator?</span>
                    <a href="{{ route('portal.login') }}" class="small fw-bold text-dark text-decoration-none">
                        <i class="bi bi-shield-lock me-1" style="color: var(--bali-tan);"></i> Portal Internal (Admin &amp; Staff) &rarr;
                    </a>
                </div>

                <div class="text-center">
                    <a href="{{ route('shop.index') }}" class="small text-decoration-none" style="color: var(--bali-muted);">
                        <i class="bi bi-arrow-left me-1"></i> Kembali ke Katalog Butik
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
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
