<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 - Akses Ditolak | Zahiyyah's Fit</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: linear-gradient(135deg, #FFF9FA 0%, #F5EBEB 50%, #FAF0E6 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #2D3748;
        }
        .error-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 24px;
            box-shadow: 0 20px 40px rgba(183, 110, 121, 0.12);
            border: 1px solid rgba(220, 180, 185, 0.3);
            max-width: 520px;
            padding: 40px;
            text-align: center;
        }
        .badge-brand {
            background-color: #F8E8EB;
            color: #B76E79;
            font-weight: 700;
            padding: 6px 16px;
            border-radius: 30px;
            font-size: 0.85rem;
            display: inline-block;
            margin-bottom: 20px;
        }
        .error-icon-box {
            width: 90px;
            height: 90px;
            border-radius: 50%;
            background: #FDEDEC;
            color: #E74C3C;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 2.75rem;
            margin-bottom: 24px;
            box-shadow: 0 10px 20px rgba(231, 76, 60, 0.15);
        }
        .btn-brand {
            background: #B76E79;
            color: #fff;
            font-weight: 600;
            border-radius: 12px;
            padding: 12px 24px;
            border: none;
            transition: all 0.2s ease;
        }
        .btn-brand:hover {
            background: #9F5862;
            color: #fff;
            transform: translateY(-2px);
            box-shadow: 0 8px 16px rgba(183, 110, 121, 0.25);
        }
        .btn-outline-brand {
            border: 1.5px solid #B76E79;
            color: #B76E79;
            font-weight: 600;
            border-radius: 12px;
            padding: 12px 24px;
            background: transparent;
            transition: all 0.2s ease;
        }
        .btn-outline-brand:hover {
            background: #F8E8EB;
            color: #9F5862;
        }
    </style>
</head>
<body>
    <div class="container px-3">
        <div class="error-card mx-auto">
            <span class="badge-brand"><i class="bi bi-shield-lock-fill me-1"></i> Keamanan Server-Side</span>
            <div class="d-block">
                <div class="error-icon-box">
                    <i class="bi bi-shield-x"></i>
                </div>
            </div>
            <h1 class="h3 fw-bold mb-2 text-dark">403 - Akses Ditolak</h1>
            <p class="text-muted mb-4 small" style="line-height: 1.6;">
                {{ $message ?? 'Maaf, akun Anda tidak memiliki izin hak akses untuk membuka halaman atau aksi ini.' }}
            </p>
            <div class="alert alert-light border small text-start p-3 mb-4 rounded-3 text-secondary">
                <div class="fw-semibold text-dark mb-1"><i class="bi bi-info-circle-fill text-danger me-1"></i> Informasi Autorisasi:</div>
                Halaman ini dilindungi oleh <strong>Role-Based Access Control (RBAC)</strong> Zahiyyah's Fit. Hak akses master data tertentu dibatasi hanya untuk Owner / Admin atau Staff berwenang.
            </div>
            <div class="d-flex flex-wrap gap-2 justify-content-center">
                @auth
                    @if(auth()->user()->role === 'customer')
                        <a href="{{ route('shop.index') }}" class="btn btn-brand">
                            <i class="bi bi-bag-heart me-1"></i> Kembali ke Katalog Shop
                        </a>
                    @else
                        <a href="{{ route('dashboard') }}" class="btn btn-brand">
                            <i class="bi bi-speedometer2 me-1"></i> Kembali ke Dashboard
                        </a>
                    @endif
                @else
                    <a href="{{ route('login') }}" class="btn btn-brand">
                        <i class="bi bi-box-arrow-in-right me-1"></i> Masuk Akun
                    </a>
                @endauth
                <button type="button" onclick="window.history.back()" class="btn btn-outline-brand">
                    <i class="bi bi-arrow-left me-1"></i> Halaman Sebelumnya
                </button>
            </div>
        </div>
    </div>
</body>
</html>
