<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Member - Zahiyyah's Fit</title>
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
            max-width: 540px;
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
    </style>
</head>
<body>
    <div class="auth-card">
        <div class="auth-header">
            <span class="auth-brand-badge">
                <i class="bi bi-gem"></i> Zahiyyah's Fit
            </span>
            <h1 class="h4 fw-bold mb-1 text-dark">Registrasi Customer Member</h1>
            <p class="text-muted small mb-0">Nikmati pengalaman belanja busana modest premium dengan mudah</p>
        </div>

        <div class="auth-body">
            @if ($errors->any())
                <div class="alert alert-danger border-0 rounded-3 small mb-3">
                    <ul class="mb-0 ps-3">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('register') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="name" class="form-label small fw-semibold text-secondary">Nama Lengkap</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0 rounded-start-3 text-muted"><i class="bi bi-person"></i></span>
                        <input type="text" class="form-control rounded-end-3 @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" placeholder="Contoh: Alya Rohali" required autofocus>
                    </div>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label for="email" class="form-label small fw-semibold text-secondary">Alamat Email</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 rounded-start-3 text-muted"><i class="bi bi-envelope"></i></span>
                            <input type="email" class="form-control rounded-end-3 @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" placeholder="nama@email.com" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label for="phone" class="form-label small fw-semibold text-secondary">Nomor WhatsApp / HP</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 rounded-start-3 text-muted"><i class="bi bi-whatsapp"></i></span>
                            <input type="text" class="form-control rounded-end-3 @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone') }}" placeholder="0812xxxxxxxx">
                        </div>
                    </div>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label for="password" class="form-label small fw-semibold text-secondary">Kata Sandi</label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" placeholder="Min. 6 karakter" required>
                    </div>
                    <div class="col-md-6">
                        <label for="password_confirmation" class="form-label small fw-semibold text-secondary">Ulangi Kata Sandi</label>
                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Ulangi kata sandi" required>
                    </div>
                </div>

                <div class="mb-4">
                    <label for="address" class="form-label small fw-semibold text-secondary">Alamat Lengkap Pengiriman (Opsional)</label>
                    <textarea class="form-control @error('address') is-invalid @enderror" id="address" name="address" rows="2" placeholder="Nama Jalan, No. Rumah, Kelurahan, Kecamatan, Kota...">{{ old('address') }}</textarea>
                </div>

                <button type="submit" class="btn btn-brand mb-3">
                    <i class="bi bi-person-check me-1"></i> Buat Akun & Mulai Berbelanja
                </button>

                <div class="text-center small text-muted mb-3">
                    Sudah memiliki akun member? <a href="{{ route('login') }}" class="text-decoration-none fw-bold" style="color: var(--brand-primary);">Masuk di Sini</a>
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
</body>
</html>
