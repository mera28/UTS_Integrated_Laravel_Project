<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Katalog Fashion & Outfit') - Zahiyyah's Fit Official Store</title>
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
            --brand-light: #FDF7F8;
            --brand-dark: #231F20;
            --brand-soft-pink: #F8E8EA;
        }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #FAF9F8;
            color: #2D3748;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        /* Top Promo Bar */
        .top-promo-bar {
            background: linear-gradient(90deg, #B76E79 0%, #C4828C 50%, #B76E79 100%);
            color: #FFFFFF;
            font-size: 0.8rem;
            font-weight: 600;
            padding: 7px 0;
            text-align: center;
        }
        /* Sticky Marketplace Navbar */
        .shop-navbar {
            background-color: #FFFFFF;
            border-bottom: 1px solid #ECECEC;
            position: sticky;
            top: 0;
            z-index: 1030;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.04);
            padding: 14px 0;
        }
        .navbar-brand-custom {
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
        }
        .brand-badge-icon {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #B76E79 0%, #D89AA2 100%);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #FFFFFF;
            font-size: 1.25rem;
            box-shadow: 0 4px 10px rgba(183, 110, 121, 0.3);
        }
        .search-box-group {
            position: relative;
            max-width: 520px;
            width: 100%;
        }
        .search-input {
            border-radius: 30px;
            padding: 10px 20px 10px 45px;
            border: 1.5px solid #E6D8DA;
            font-size: 0.9rem;
            transition: all 0.2s;
            background-color: #FCF9FA;
        }
        .search-input:focus {
            background-color: #FFFFFF;
            border-color: var(--brand-primary);
            box-shadow: 0 0 0 4px rgba(183, 110, 121, 0.12);
        }
        .search-icon-inside {
            position: absolute;
            left: 18px;
            top: 50%;
            transform: translateY(-50%);
            color: #A09698;
            font-size: 1rem;
        }
        .btn-brand-primary {
            background-color: var(--brand-primary);
            color: #FFFFFF;
            border-radius: 12px;
            font-weight: 600;
            padding: 9px 20px;
            border: none;
            transition: all 0.2s;
        }
        .btn-brand-primary:hover {
            background-color: var(--brand-primary-hover);
            color: #FFFFFF;
            transform: translateY(-1px);
            box-shadow: 0 6px 15px rgba(183, 110, 121, 0.25);
        }
        .btn-outline-brand {
            border: 1.5px solid var(--brand-primary);
            color: var(--brand-primary);
            border-radius: 12px;
            font-weight: 600;
            padding: 8px 18px;
            background: transparent;
            transition: all 0.2s;
        }
        .btn-outline-brand:hover {
            background-color: var(--brand-soft-pink);
            color: var(--brand-primary-hover);
        }
        .cart-btn-pill {
            position: relative;
            background-color: #FCF9FA;
            border: 1.5px solid #E6D8DA;
            color: var(--brand-primary);
            border-radius: 30px;
            padding: 8px 16px;
            font-weight: 600;
            font-size: 0.9rem;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
            transition: all 0.2s;
        }
        .cart-btn-pill:hover {
            background-color: var(--brand-soft-pink);
            color: var(--brand-primary-hover);
        }
        .cart-badge {
            background-color: #E74C3C;
            color: white;
            font-size: 0.75rem;
            padding: 2px 7px;
            border-radius: 10px;
            font-weight: 700;
        }
        /* Footer */
        .shop-footer {
            background-color: #1F1B1D;
            color: #D3CBCE;
            padding: 60px 0 30px;
            margin-top: auto;
        }
        .payment-pill {
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.12);
            border-radius: 8px;
            padding: 6px 12px;
            font-size: 0.78rem;
            font-weight: 600;
            color: #FFFFFF;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
    </style>
    @stack('styles')
</head>
<body>
    <!-- Top Announcement Bar -->
    <div class="top-promo-bar">
        <span><i class="bi bi-sparkles me-1"></i> Promo Spesial: Gratis Ongkir & Diskon Anggota Baru Hingga 20% | Koleksi Outfit Modest Terlengkap</span>
    </div>

    <!-- Marketplace Header Navbar -->
    <nav class="shop-navbar">
        <div class="container d-flex align-items-center justify-content-between flex-wrap gap-3">
            <!-- Brand Logo -->
            <a href="{{ route('shop.index') }}" class="navbar-brand-custom">
                <div class="brand-badge-icon">
                    <i class="bi bi-gem"></i>
                </div>
                <div>
                    <h5 class="fw-bold mb-0 text-dark" style="letter-spacing: -0.5px;">Zahiyyah's Fit</h5>
                    <span class="small text-muted" style="font-size: 0.72rem; letter-spacing: 0.3px;">Official Fashion Mall</span>
                </div>
            </a>

            <!-- Search Bar -->
            <form action="{{ route('shop.index') }}" method="GET" class="search-box-group d-none d-md-block flex-grow-1 mx-lg-4">
                <i class="bi bi-search search-icon-inside"></i>
                <input type="text" name="q" class="form-control search-input" placeholder="Cari gamis, tunik, celana kulot, pashmina shimmer..." value="{{ request('q') }}">
            </form>

            <!-- Action Buttons / User Menu -->
            <div class="d-flex align-items-center gap-3">
                <!-- Cart Button -->
                <a href="{{ route('shop.cart') }}" class="cart-btn-pill">
                    <i class="bi bi-bag-heart fs-6"></i>
                    <span class="d-none d-sm-inline">Keranjang</span>
                    @php
                        $cartCount = count(session('cart', []));
                    @endphp
                    <span class="cart-badge" id="cartCountBadge">{{ $cartCount }}</span>
                </a>

                @auth
                    <!-- User Profile Dropdown -->
                    <div class="dropdown">
                        <button class="btn btn-light rounded-pill border dropdown-toggle d-flex align-items-center gap-2 py-1 px-3" type="button" data-bs-toggle="dropdown">
                            <div class="bg-dark text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 28px; height: 28px; font-size: 0.8rem;">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </div>
                            <span class="small fw-semibold text-dark">{{ auth()->user()->name }}</span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 rounded-3 mt-2 p-2" style="min-width: 220px;">
                            <li class="px-3 py-2 border-bottom">
                                <span class="d-block small text-muted">Role Pengguna:</span>
                                <span class="fw-bold small text-dark text-uppercase">{{ auth()->user()->role }}</span>
                            </li>
                            @if(auth()->user()->isAdmin() || auth()->user()->isStaff())
                                <li>
                                    <a class="dropdown-item rounded-2 small fw-semibold text-primary py-2 mt-1" href="{{ route('dashboard') }}">
                                        <i class="bi bi-speedometer2 me-1"></i> Ke Panel Backoffice
                                    </a>
                                </li>
                            @endif
                            <li>
                                <a class="dropdown-item rounded-2 small py-2" href="{{ route('shop.orders') }}">
                                    <i class="bi bi-receipt me-1"></i> Pesanan Saya
                                </a>
                            </li>
                            <li class="border-top mt-1 pt-1">
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger rounded-2 small fw-semibold py-2">
                                        <i class="bi bi-box-arrow-right me-1"></i> Keluar (Logout)
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                @else
                    <div class="d-flex align-items-center gap-2">
                        <a href="{{ route('login') }}" class="btn btn-outline-brand small py-1 px-3">
                            <i class="bi bi-box-arrow-in-right me-1"></i> Masuk
                        </a>
                        <a href="{{ route('register') }}" class="btn btn-brand-primary small py-1 px-3 d-none d-sm-inline-block">
                            Daftar
                        </a>
                    </div>
                @endauth
            </div>
        </div>

        <!-- Mobile Search Bar -->
        <div class="container d-md-none mt-2">
            <form action="{{ route('shop.index') }}" method="GET" class="search-box-group w-100">
                <i class="bi bi-search search-icon-inside"></i>
                <input type="text" name="q" class="form-control search-input" placeholder="Cari gamis, tunik, hijab..." value="{{ request('q') }}">
            </form>
        </div>
    </nav>

    <!-- Sub Navigation Bar (Categories shortcut) -->
    <div class="bg-white border-bottom py-2">
        <div class="container d-flex align-items-center gap-4 overflow-auto text-nowrap small fw-semibold">
            <a href="{{ route('shop.index') }}" class="text-decoration-none {{ !request('category') ? 'text-danger fw-bold' : 'text-secondary' }}">
                <i class="bi bi-grid-fill me-1"></i> Semua Koleksi
            </a>
            <a href="{{ route('shop.index', ['category' => 'gamis-maxy-dress']) }}" class="text-decoration-none {{ request('category') == 'gamis-maxy-dress' ? 'text-danger fw-bold' : 'text-secondary' }}">
                Gamis & Maxy
            </a>
            <a href="{{ route('shop.index', ['category' => 'blouse-tunik']) }}" class="text-decoration-none {{ request('category') == 'blouse-tunik' ? 'text-danger fw-bold' : 'text-secondary' }}">
                Blouse & Tunik
            </a>
            <a href="{{ route('shop.index', ['category' => 'celana-kulot']) }}" class="text-decoration-none {{ request('category') == 'celana-kulot' ? 'text-danger fw-bold' : 'text-secondary' }}">
                Celana & Kulot
            </a>
            <a href="{{ route('shop.index', ['category' => 'rok-pleated-skirt']) }}" class="text-decoration-none {{ request('category') == 'rok-pleated-skirt' ? 'text-danger fw-bold' : 'text-secondary' }}">
                Rok Plisket
            </a>
            <a href="{{ route('shop.index', ['category' => 'hijab-pashmina']) }}" class="text-decoration-none {{ request('category') == 'hijab-pashmina' ? 'text-danger fw-bold' : 'text-secondary' }}">
                Hijab & Pashmina
            </a>
            <a href="{{ route('shop.index', ['category' => 'outerwear-cardigan']) }}" class="text-decoration-none {{ request('category') == 'outerwear-cardigan' ? 'text-danger fw-bold' : 'text-secondary' }}">
                Outerwear
            </a>
        </div>
    </div>

    <!-- Flash Messages Container -->
    <div class="container mt-4">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-3 d-flex align-items-center" role="alert">
                <i class="bi bi-check-circle-fill me-2 fs-5"></i>
                <div>{{ session('success') }}</div>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm rounded-3 d-flex align-items-center" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2 fs-5"></i>
                <div>{{ session('error') }}</div>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
            </div>
        @endif
    </div>

    <!-- Content Slot -->
    <main class="flex-grow-1">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="shop-footer">
        <div class="container">
            <div class="row g-4 mb-4">
                <div class="col-lg-4 col-md-6">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <div class="brand-badge-icon" style="width: 34px; height: 34px; font-size: 1.1rem;">
                            <i class="bi bi-gem"></i>
                        </div>
                        <h5 class="fw-bold mb-0 text-white">Zahiyyah's Fit</h5>
                    </div>
                    <p class="small text-muted mb-3" style="line-height: 1.7;">
                        Pusat perbelanjaan busana muslimah dan modest fashion modern terlengkap dengan jaminan bahan premium, jahitan rapi, dan desain anggun untuk setiap momen berharga Anda.
                    </p>
                    <div class="small text-white">
                        <i class="bi bi-geo-alt-fill text-danger me-1"></i> Banda Aceh, Indonesia
                    </div>
                </div>

                <div class="col-lg-2 col-md-6">
                    <h6 class="text-white fw-bold mb-3">Kategori Pilihan</h6>
                    <ul class="list-unstyled small mb-0 d-flex flex-column gap-2 text-muted">
                        <li><a href="{{ route('shop.index', ['category' => 'gamis-maxy-dress']) }}" class="text-decoration-none text-muted">Gamis Silk Abaya</a></li>
                        <li><a href="{{ route('shop.index', ['category' => 'blouse-tunik']) }}" class="text-decoration-none text-muted">Linen Peplum Blouse</a></li>
                        <li><a href="{{ route('shop.index', ['category' => 'celana-kulot']) }}" class="text-decoration-none text-muted">Highwaist Kulot</a></li>
                        <li><a href="{{ route('shop.index', ['category' => 'hijab-pashmina']) }}" class="text-decoration-none text-muted">Pashmina Shimmer Silk</a></li>
                    </ul>
                </div>

                <div class="col-lg-3 col-md-6">
                    <h6 class="text-white fw-bold mb-3">Layanan & Keamanan</h6>
                    <ul class="list-unstyled small mb-0 d-flex flex-column gap-2 text-muted">
                        <li><i class="bi bi-shield-check text-success me-1"></i> Garansi 100% Produk Original</li>
                        <li><i class="bi bi-truck text-info me-1"></i> Pengiriman Seluruh Nusantara</li>
                        <li><i class="bi bi-arrow-repeat text-warning me-1"></i> Tukar Size Mudah & Praktis</li>
                        <li><i class="bi bi-headset text-danger me-1"></i> Customer Support Siaga 24/7</li>
                    </ul>
                </div>

                <div class="col-lg-3 col-md-6">
                    <h6 class="text-white fw-bold mb-3">Metode Pembayaran Resmi</h6>
                    <p class="small text-muted mb-2">Simulasi Payment Gateway Instan & Otomatis:</p>
                    <div class="d-flex flex-wrap gap-2">
                        <span class="payment-pill"><i class="bi bi-qr-code text-warning"></i> QRIS</span>
                        <span class="payment-pill"><i class="bi bi-bank"></i> BCA VA</span>
                        <span class="payment-pill"><i class="bi bi-bank"></i> Mandiri VA</span>
                        <span class="payment-pill"><i class="bi bi-bank"></i> BRI VA</span>
                        <span class="payment-pill"><i class="bi bi-bank"></i> BSI VA</span>
                        <span class="payment-pill"><i class="bi bi-wallet2 text-danger"></i> ShopeePay</span>
                        <span class="payment-pill"><i class="bi bi-wallet2 text-success"></i> GoPay</span>
                    </div>
                </div>
            </div>

            <div class="border-top border-secondary pt-3 d-flex flex-wrap justify-content-between align-items-center text-muted small">
                <div>&copy; {{ date('Y') }} <strong>Zahiyyah's Fit</strong>. Hak Cipta Dilindungi Undang-Undang.</div>
                <div>Project UTS Web Programming 2 — Sistem E-Commerce, Inventory & Sales Management</div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
