<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal Internal Staff & Admin - Zahiyyah's Fit</title>
    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
    <!-- Bootstrap 5.3 & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="{{ asset('css/zahiyyah.css') }}?v={{ time() }}">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: linear-gradient(135deg, #0F172A 0%, #1E293B 60%, #334155 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 30px 16px;
            color: #F8FAFC;
        }
        .portal-card {
            background: #1E293B;
            border-radius: 20px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.45);
            border: 1px solid rgba(255, 255, 255, 0.1);
            overflow: hidden;
            max-width: 440px;
            width: 100%;
        }
        .portal-header {
            padding: 32px 32px 24px;
            text-align: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            background: rgba(15, 23, 42, 0.4);
        }
        .portal-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(183, 110, 121, 0.15);
            color: #E2B9BE;
            font-weight: 700;
            padding: 6px 16px;
            border-radius: 999px;
            font-size: 0.8rem;
            margin-bottom: 12px;
            border: 1px solid rgba(183, 110, 121, 0.3);
        }
        .portal-body {
            padding: 28px 32px 32px;
        }
        .form-control-dark {
            background-color: #0F172A;
            border: 1.5px solid #334155;
            color: #F8FAFC;
            border-radius: 10px;
            padding: 11px 14px;
            font-size: 0.92rem;
            transition: all 0.2s;
        }
        .form-control-dark:focus {
            background-color: #0F172A;
            border-color: #B76E79;
            color: #FFFFFF;
            box-shadow: 0 0 0 3px rgba(183, 110, 121, 0.25);
        }
        .input-group-text-dark {
            background-color: #1E293B;
            border: 1.5px solid #334155;
            color: #94A3B8;
        }
        .btn-portal {
            background: linear-gradient(135deg, #B76E79 0%, #9F5862 100%);
            color: #FFFFFF;
            font-weight: 600;
            border-radius: 10px;
            padding: 11px 20px;
            border: none;
            width: 100%;
            transition: all 0.2s ease;
        }
        .btn-portal:hover {
            transform: translateY(-1px);
            box-shadow: 0 8px 20px rgba(183, 110, 121, 0.35);
            color: #FFFFFF;
        }
        .demo-portal-box {
            background: rgba(15, 23, 42, 0.6);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 12px;
            padding: 14px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="portal-card">
        <div class="portal-header">
            <span class="portal-badge">
                <i class="bi bi-shield-lock-fill"></i> Internal Backoffice Portal
            </span>
            <h1 class="h5 fw-bold mb-1 text-white">Zahiyyah's Fit</h1>
            <p class="text-secondary small mb-0">Khusus akses Administrator, Kasir POS, & Pengelola Gudang</p>
        </div>

        <div class="portal-body">
            <!-- Flash Notifications -->
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

            <!-- Quick Demo Portal Buttons -->
            <div class="demo-portal-box p-3 mb-4 rounded-4" style="background: rgba(15, 23, 42, 0.7); border: 1px solid rgba(255, 255, 255, 0.12);">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="small fw-bold text-white"><i class="bi bi-lightning-charge-fill text-warning"></i> Quick Fill Akses Internal:</span>
                    <span class="badge bg-secondary bg-opacity-35 text-white" style="font-size: 0.7rem;">1-Klik Isi</span>
                </div>
                <div class="row g-2">
                    <div class="col-6">
                        <button type="button" class="btn btn-outline-danger btn-sm w-100 rounded-3 text-start p-2" onclick="fillCredentials('admin@zahiyyah.com', 'password')">
                            <div class="fw-bold text-white small"><i class="bi bi-shield-lock-fill text-danger"></i> Admin / Owner</div>
                            <div class="text-secondary" style="font-size: 0.72rem;">admin@zahiyyah.com</div>
                            <div class="text-secondary" style="font-size: 0.68rem;">Pass: <code>password</code></div>
                        </button>
                    </div>
                    <div class="col-6">
                        <button type="button" class="btn btn-outline-info btn-sm w-100 rounded-3 text-start p-2" onclick="fillCredentials('staff@zahiyyah.com', 'password')">
                            <div class="fw-bold text-white small"><i class="bi bi-person-badge-fill text-info"></i> Staff Kasir</div>
                            <div class="text-secondary" style="font-size: 0.72rem;">staff@zahiyyah.com</div>
                            <div class="text-secondary" style="font-size: 0.68rem;">Pass: <code>password</code></div>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Login Form -->
            <form action="{{ route('portal.login') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="email" class="form-label small fw-semibold text-secondary">Email Petugas</label>
                    <div class="input-group">
                        <span class="input-group-text input-group-text-dark border-end-0"><i class="bi bi-envelope"></i></span>
                        <input type="email" class="form-control form-control-dark @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" placeholder="admin@zahiyyah.com" required autofocus>
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
                        <span class="input-group-text input-group-text-dark border-end-0"><i class="bi bi-key"></i></span>
                        <input type="password" class="form-control form-control-dark @error('password') is-invalid @enderror" id="password" name="password" placeholder="••••••••" required>
                        <button class="btn input-group-text-dark border border-start-0" type="button" onclick="togglePassword()">
                            <i class="bi bi-eye text-secondary" id="toggleIcon"></i>
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
                            Ingat sesi saya
                        </label>
                    </div>
                </div>

                <button type="submit" class="btn btn-portal mb-3">
                    <i class="bi bi-box-arrow-in-right me-1"></i> Masuk ke Backoffice
                </button>

                <div class="p-3 rounded-3 text-center mb-2 border border-secondary border-opacity-25" style="background: rgba(15, 23, 42, 0.4);">
                    <span class="small text-muted d-block mb-1">Bukan staf butik?</span>
                    <a href="{{ route('login') }}" class="small fw-semibold text-info text-decoration-none">
                        &larr; Masuk ke Akun Belanja Pelanggan
                    </a>
                </div>

                <div class="text-center pt-2">
                    <a href="{{ route('shop.index') }}" class="text-secondary small text-decoration-none">
                        <i class="bi bi-shop me-1"></i> Lihat Toko Online Zahiyyah's Fit
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
