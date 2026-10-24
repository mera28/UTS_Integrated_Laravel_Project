<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Member - Zahiyyah's Fit</title>
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
            --brand-light: #FBF5F5;
            --brand-accent: #E8C5C8;
            --brand-dark: #2A2426;
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
            max-width: 520px;
            width: 100%;
        }
        .auth-header {
            padding: 28px 32px 18px;
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
            margin-bottom: 10px;
            border: 1px solid rgba(183, 110, 121, 0.2);
        }
        .auth-body {
            padding: 24px 32px 32px;
        }
        .btn-google {
            background-color: #FFFFFF;
            color: #1F1F1F;
            border: 1.5px solid #E2E8F0;
            font-weight: 600;
            border-radius: 12px;
            padding: 11px 16px;
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            transition: all 0.2s ease;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
            font-size: 0.92rem;
        }
        .btn-google:hover {
            background-color: #F8FAFD;
            border-color: #CBD5E1;
            color: #0F172A;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }
        .divider-line {
            display: flex;
            align-items: center;
            text-align: center;
            margin: 20px 0;
            color: #94A3B8;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .divider-line::before,
        .divider-line::after {
            content: '';
            flex: 1;
            border-bottom: 1px solid #E2E8F0;
        }
        .divider-line::before { margin-right: 12px; }
        .divider-line::after { margin-left: 12px; }
        .form-control {
            border-radius: 10px;
            padding: 10px 14px;
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
        .auth-nav-tabs {
            display: flex;
            background: #F1F5F9;
            border-radius: 12px;
            padding: 4px;
            margin-bottom: 20px;
        }
        .auth-nav-tab {
            flex: 1;
            text-align: center;
            padding: 8px 12px;
            font-size: 0.85rem;
            font-weight: 600;
            color: #64748B;
            text-decoration: none;
            border-radius: 9px;
            transition: all 0.2s;
        }
        .auth-nav-tab.active {
            background: #FFFFFF;
            color: #0F172A;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
        }
    </style>
</head>
<body>
    <div class="auth-card">
        <div class="auth-header">
            <span class="auth-brand-badge">
                <i class="bi bi-gem"></i> Zahiyyah's Fit
            </span>
            <h1 class="h5 fw-bold mb-1 text-dark">Buat Akun Member Customer</h1>
            <p class="text-muted small mb-0">Daftar instan untuk berbelanja outfit modest premium dan lacak pesanan</p>
        </div>

        <div class="auth-body">
            <!-- Nav Tabs Switcher -->
            <div class="auth-nav-tabs">
                <a href="{{ route('login') }}" class="auth-nav-tab">
                    <i class="bi bi-box-arrow-in-right me-1"></i> Masuk Akun
                </a>
                <a href="{{ route('register') }}" class="auth-nav-tab active">
                    <i class="bi bi-person-plus me-1"></i> Buat Akun Baru
                </a>
            </div>

            <!-- Validation Errors -->
            @if ($errors->any())
                <div class="alert alert-danger border-0 rounded-3 small mb-3">
                    <div class="fw-semibold mb-1"><i class="bi bi-exclamation-triangle-fill me-1"></i> Harap periksa input berikut:</div>
                    <ul class="mb-0 ps-3">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
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
                <span>atau buat akun manual</span>
            </div>

            <!-- Fast Register Form -->
            <form action="{{ route('register') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="name" class="form-label small fw-semibold text-secondary">Nama Lengkap</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-person"></i></span>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" placeholder="Contoh: Alya Rohali" required autofocus>
                    </div>
                </div>

                <div class="row g-2 mb-3">
                    <div class="col-md-6">
                        <label for="email" class="form-label small fw-semibold text-secondary">Alamat Email</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-envelope"></i></span>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" placeholder="nama@email.com" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label for="phone" class="form-label small fw-semibold text-secondary">No. WhatsApp / HP</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-telephone"></i></span>
                            <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone') }}" placeholder="0812xxxxxxxx">
                        </div>
                    </div>
                </div>

                <div class="row g-2 mb-3">
                    <div class="col-md-6">
                        <label for="password" class="form-label small fw-semibold text-secondary">Kata Sandi</label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" placeholder="Min. 6 karakter" required>
                    </div>
                    <div class="col-md-6">
                        <label for="password_confirmation" class="form-label small fw-semibold text-secondary">Ulangi Kata Sandi</label>
                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Ulangi sandi" required>
                    </div>
                </div>

                <div class="mb-4">
                    <label for="address" class="form-label small fw-semibold text-secondary">Alamat Singkat Pengiriman (Opsional)</label>
                    <textarea class="form-control @error('address') is-invalid @enderror" id="address" name="address" rows="2" placeholder="Nama Jalan, Kota / Kabupaten...">{{ old('address') }}</textarea>
                </div>

                <button type="submit" class="btn btn-brand mb-3">
                    <i class="bi bi-person-check me-1"></i> Buat Akun & Mulai Belanja
                </button>

                <div class="text-center small text-muted mb-3">
                    Sudah punya akun member? <a href="{{ route('login') }}" class="text-decoration-none fw-bold" style="color: var(--brand-primary);">Masuk di Sini</a>
                </div>

                <div class="text-center pt-2 border-top">
                    <a href="{{ route('shop.index') }}" class="text-secondary small text-decoration-none">
                        <i class="bi bi-arrow-left me-1"></i> Kembali ke Katalog Outfit
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
