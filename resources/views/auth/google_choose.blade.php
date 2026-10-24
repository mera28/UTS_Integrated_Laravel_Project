<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign in with Google - Zahiyyah's Fit</title>
    <!-- Google Fonts: Roboto & Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <!-- Bootstrap 5.3 & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body {
            font-family: 'Roboto', 'Plus Jakarta Sans', sans-serif;
            background-color: #F0F4F9;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px 16px;
            color: #1F1F1F;
        }
        .google-card {
            background: #FFFFFF;
            border-radius: 28px;
            padding: 40px;
            max-width: 460px;
            width: 100%;
            box-shadow: 0 4px 24px rgba(0, 0, 0, 0.06);
            border: 1px solid #E3E3E3;
        }
        .account-item {
            padding: 12px 14px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            gap: 14px;
            text-decoration: none;
            color: #1F1F1F;
            transition: background-color 0.15s ease;
            cursor: pointer;
            border: 1px solid transparent;
            width: 100%;
            background: none;
            text-align: left;
        }
        .account-item:hover {
            background-color: #F8FAFD;
            border-color: #E2E8F0;
        }
        .account-avatar {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            object-fit: cover;
            border: 1px solid #E2E8F0;
        }
        .account-avatar-icon {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            background-color: #F1F3F4;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #5F6368;
            font-size: 1.25rem;
        }
        .custom-account-box {
            background: #F8FAFD;
            border: 1px solid #E2E8F0;
            border-radius: 16px;
            padding: 16px;
            margin-top: 10px;
        }
        .btn-google-primary {
            background-color: #0B57D0;
            color: #FFFFFF;
            border-radius: 20px;
            padding: 8px 24px;
            font-weight: 500;
            font-size: 0.88rem;
            border: none;
            transition: all 0.2s;
        }
        .btn-google-primary:hover {
            background-color: #0842A0;
            color: #FFFFFF;
        }
    </style>
</head>
<body>
    <div class="google-card">
        <!-- Google Logo & Header -->
        <div class="text-center mb-4">
            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 48 48" class="mb-3">
                <path fill="#EA4335" d="M24 9.5c3.54 0 6.71 1.22 9.21 3.6l6.85-6.85C35.9 2.38 30.47 0 24 0 14.62 0 6.51 5.38 2.56 13.22l7.98 6.19C12.43 13.72 17.74 9.5 24 9.5z"/>
                <path fill="#4285F4" d="M46.98 24.55c0-1.57-.15-3.09-.38-4.55H24v9.02h12.94c-.58 2.96-2.26 5.48-4.78 7.18l7.73 6c4.51-4.18 7.09-10.36 7.09-17.65z"/>
                <path fill="#FBBC05" d="M10.53 28.59c-.48-1.45-.76-2.99-.76-4.59s.27-3.14.76-4.59l-7.98-6.19C.92 16.46 0 20.12 0 24c0 3.88.92 7.54 2.56 10.78l7.97-6.19z"/>
                <path fill="#34A853" d="M24 48c6.48 0 11.93-2.13 15.89-5.81l-7.73-6c-2.15 1.45-4.92 2.3-8.16 2.3-6.26 0-11.57-4.22-13.47-9.91l-7.98 6.19C6.51 42.62 14.62 48 24 48z"/>
                <path fill="none" d="M0 0h48v48H0z"/>
            </svg>
            <h4 class="fw-normal mb-1">Pilih akun Google</h4>
            <div class="text-secondary small">
                untuk melanjutkan ke <strong class="text-dark">Zahiyyah's Fit</strong>
            </div>
        </div>

        <!-- Account List Form Options -->
        <div class="d-flex flex-column gap-1 mb-3">
            @foreach($presetAccounts as $acc)
                <form action="{{ route('auth.google.callback') }}" method="POST">
                    @csrf
                    <input type="hidden" name="name" value="{{ $acc['name'] }}">
                    <input type="hidden" name="email" value="{{ $acc['email'] }}">
                    <button type="submit" class="account-item">
                        <img src="{{ $acc['avatar'] }}" alt="{{ $acc['name'] }}" class="account-avatar">
                        <div class="flex-grow-1">
                            <div class="fw-bold text-dark small" style="font-size: 0.92rem;">{{ $acc['name'] }}</div>
                            <div class="text-secondary small" style="font-size: 0.8rem;">{{ $acc['email'] }}</div>
                            <span class="badge bg-light text-muted border mt-1" style="font-size: 0.68rem;">{{ $acc['subtitle'] }}</span>
                        </div>
                    </button>
                </form>
            @endforeach

            <!-- Toggle Use Another Account -->
            <button type="button" class="account-item mt-1" onclick="toggleCustomAccountForm()">
                <div class="account-avatar-icon">
                    <i class="bi bi-person-plus-fill"></i>
                </div>
                <div class="flex-grow-1">
                    <div class="fw-semibold text-dark small" style="font-size: 0.9rem;">Gunakan akun Google lain</div>
                    <div class="text-secondary small" style="font-size: 0.78rem;">Masuk dengan nama & email Anda</div>
                </div>
                <i class="bi bi-chevron-down text-muted small" id="chevronIcon"></i>
            </button>

            <!-- Custom Google Account Form -->
            <div id="customAccountBox" class="custom-account-box d-none">
                <form action="{{ route('auth.google.callback') }}" method="POST">
                    @csrf
                    <div class="mb-2">
                        <label for="custom_name" class="form-label small text-secondary mb-1">Nama Tampilan Google</label>
                        <input type="text" name="name" id="custom_name" class="form-control form-control-sm rounded-3" placeholder="Contoh: Fatimah Zahra" required>
                    </div>
                    <div class="mb-3">
                        <label for="custom_email" class="form-label small text-secondary mb-1">Alamat Email Google (@gmail.com)</label>
                        <input type="email" name="email" id="custom_email" class="form-control form-control-sm rounded-3" placeholder="nama@gmail.com" required>
                    </div>
                    <div class="d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-sm btn-light rounded-pill" onclick="toggleCustomAccountForm()">Batal</button>
                        <button type="submit" class="btn-google-primary btn-sm">Lanjutkan Masuk</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Privacy & Terms Notice -->
        <div class="border-top pt-3 mt-4 text-muted" style="font-size: 0.75rem; line-height: 1.4;">
            Untuk melanjutkan, Google akan membagikan nama, alamat email, dan preferensi bahasa Anda kepada <strong>Zahiyyah's Fit</strong>. Sebelum menggunakan aplikasi, Anda dapat membaca Kebijakan Privasi dan Persyaratan Layanan butik kami.
        </div>

        <!-- Cancel Link -->
        <div class="text-center mt-3">
            <a href="{{ route('login') }}" class="text-secondary small text-decoration-none">
                &larr; Batal dan kembali ke halaman login
            </a>
        </div>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function toggleCustomAccountForm() {
            const box = document.getElementById('customAccountBox');
            const icon = document.getElementById('chevronIcon');
            if (box.classList.contains('d-none')) {
                box.classList.remove('d-none');
                icon.classList.remove('bi-chevron-down');
                icon.classList.add('bi-chevron-up');
            } else {
                box.classList.add('d-none');
                icon.classList.remove('bi-chevron-up');
                icon.classList.add('bi-chevron-down');
            }
        }
    </script>
</body>
</html>
