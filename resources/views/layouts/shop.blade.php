<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Katalog Fashion & Outfit') - Zahiyyah's Fit Bali Resort Boutique</title>
    <!-- Google Fonts: Cormorant Garamond, Playfair Display, & Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,500;0,600;0,700;1,400;1,600&family=Playfair+Display:ital,wght@0,500;0,600;0,700;1,500&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Bootstrap 5.3 & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="{{ asset('css/zahiyyah.css') }}?v={{ time() }}">
    @stack('styles')
</head>
<body>
    <!-- Top Announcement Bar (Warm Light Brown Contrast with Portal Link) -->
    <div class="top-promo-bar">
        <div class="container d-flex align-items-center justify-content-between flex-wrap gap-2">
            <span class="mx-auto mx-md-0"><i class="bi bi-water"></i> ZAHIYYAH'S FIT BALI &bull; KOLEKSI RESORT &amp; KNITWEAR EKSKLUSIF</span>
            <a href="{{ url('/portal/login') }}" class="portal-link-promo ms-auto">
                <i class="bi bi-shield-lock"></i> Portal Admin / Staff
            </a>
        </div>
    </div>

    <!-- Marketplace Header Navbar (Clean White Glassmorphism) -->
    <nav class="shop-navbar">
        <div class="container d-flex align-items-center justify-content-between flex-wrap gap-3">
            <!-- Brand Logo -->
            <a href="{{ route('shop.index') }}" class="navbar-brand-custom">
                <div class="brand-badge-icon">
                    <i class="bi bi-flower1"></i>
                </div>
                <div>
                    <h5 class="brand-title-bali">Zahiyyah's Fit</h5>
                    <span class="brand-subcaption">Bali &bull; Modest &amp; Resort Boutique</span>
                </div>
            </a>

            <!-- Search Bar (Flex Wrapper - Zero Icon/Text Overlap Guarantee) -->
            <form action="{{ route('shop.index') }}" method="GET" class="d-none d-md-block flex-grow-1 mx-lg-4" style="max-width: 480px;">
                <div class="search-box-wrapper">
                    <span class="search-icon-box"><i class="bi bi-search"></i></span>
                    <input type="text" name="q" class="search-input-field flex-grow-1" placeholder="Cari linen abaya, beach knitwear, tunik, hijab..." value="{{ request('q') }}">
                </div>
            </form>

            <!-- Action Buttons / User Menu -->
            <div class="d-flex align-items-center gap-3">
                <!-- Cart Button -->
                <a href="{{ route('shop.cart') }}" class="cart-btn-pill">
                    <i class="bi bi-bag"></i>
                    <span class="d-none d-sm-inline">Keranjang</span>
                    @php
                        $cartCount = count(session('cart', []));
                    @endphp
                    <span class="cart-badge" id="cartCountBadge">{{ $cartCount }}</span>
                </a>

                @auth
                    <!-- User Profile Dropdown -->
                    <div class="dropdown">
                        <button class="btn btn-sand rounded-pill dropdown-toggle d-flex align-items-center gap-2 py-1 px-3" type="button" data-bs-toggle="dropdown">
                            <div class="rounded-circle d-flex align-items-center justify-content-center text-white" style="width: 26px; height: 26px; font-size: 0.78rem; background-color: var(--bali-tan);">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </div>
                            <span class="small fw-semibold" style="color: var(--bali-espresso);">{{ auth()->user()->name }}</span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow-sm border rounded-3 mt-2 p-2" style="min-width: 230px; border-color: var(--bali-border);">
                            <li class="px-3 py-2 border-bottom" style="border-color: var(--bali-border-light) !important;">
                                <span class="d-block small text-muted" style="font-size: 0.72rem; letter-spacing: 0.05em; text-transform: uppercase;">Akun Terhubung:</span>
                                <span class="fw-bold small text-uppercase" style="color: var(--bali-espresso);">{{ auth()->user()->role }}</span>
                            </li>
                            @if(auth()->user()->isAdmin() || auth()->user()->isStaff())
                                <li>
                                    <a class="dropdown-item rounded-2 small fw-semibold py-2 mt-1" style="color: var(--bali-tan);" href="{{ route('dashboard') }}">
                                        <i class="bi bi-speedometer2"></i> Ke Panel Backoffice
                                    </a>
                                </li>
                            @endif
                            <li>
                                <a class="dropdown-item rounded-2 small py-2 text-dark" href="{{ route('shop.orders') }}">
                                    <i class="bi bi-receipt"></i> Pesanan Saya
                                </a>
                            </li>
                            <li class="border-top mt-1 pt-1" style="border-color: var(--bali-border-light) !important;">
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger rounded-2 small fw-semibold py-2">
                                        <i class="bi bi-box-arrow-right"></i> Keluar (Logout)
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                @else
                    <div class="d-flex align-items-center gap-2">
                        <a href="{{ route('login') }}" class="btn btn-outline-brand small py-1 px-3">
                            <i class="bi bi-person"></i> Masuk
                        </a>
                        <a href="{{ route('register') }}" class="btn btn-brand-primary small py-1 px-3 d-none d-sm-inline-block">
                            Daftar
                        </a>
                    </div>
                @endauth
            </div>
        </div>

        <!-- Mobile Search Bar (Flex Wrapper) -->
        <div class="container d-md-none mt-2">
            <form action="{{ route('shop.index') }}" method="GET" class="w-100">
                <div class="search-box-wrapper">
                    <span class="search-icon-box"><i class="bi bi-search"></i></span>
                    <input type="text" name="q" class="search-input-field flex-grow-1" placeholder="Cari linen abaya, beach knitwear, tunik..." value="{{ request('q') }}">
                </div>
            </form>
        </div>
    </nav>

    <!-- Sub Navigation Bar (Categories Strip) -->
    <div class="shop-subnav-strip">
        <div class="container d-flex align-items-center gap-2 overflow-auto text-nowrap">
            <a href="{{ route('shop.index') }}" class="subnav-link {{ !request('category') ? 'active' : '' }}">
                <i class="bi bi-grid"></i> Semua Koleksi
            </a>
            <a href="{{ route('shop.index', ['category' => 'gamis-maxy-dress']) }}" class="subnav-link {{ request('category') == 'gamis-maxy-dress' ? 'active' : '' }}">
                Gamis &amp; Maxy
            </a>
            <a href="{{ route('shop.index', ['category' => 'blouse-tunik']) }}" class="subnav-link {{ request('category') == 'blouse-tunik' ? 'active' : '' }}">
                Blouse &amp; Tunik
            </a>
            <a href="{{ route('shop.index', ['category' => 'celana-kulot']) }}" class="subnav-link {{ request('category') == 'celana-kulot' ? 'active' : '' }}">
                Celana &amp; Kulot
            </a>
            <a href="{{ route('shop.index', ['category' => 'rok-pleated-skirt']) }}" class="subnav-link {{ request('category') == 'rok-pleated-skirt' ? 'active' : '' }}">
                Rok Plisket
            </a>
            <a href="{{ route('shop.index', ['category' => 'hijab-pashmina']) }}" class="subnav-link {{ request('category') == 'hijab-pashmina' ? 'active' : '' }}">
                Hijab &amp; Pashmina
            </a>
            <a href="{{ route('shop.index', ['category' => 'outerwear-cardigan']) }}" class="subnav-link {{ request('category') == 'outerwear-cardigan' ? 'active' : '' }}">
                Outerwear &amp; Knitwear
            </a>
        </div>
    </div>

    <!-- Flash Messages Container -->
    <div class="container mt-4">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show border-0 rounded-4 d-flex align-items-center surface-ivory p-3" role="alert">
                <i class="bi bi-check-circle-fill fs-5" style="color: var(--bali-tan);"></i>
                <div style="color: var(--bali-espresso);">{{ session('success') }}</div>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show border-0 rounded-4 d-flex align-items-center p-3" style="background-color: #FAF0F0; border: 1px solid #E8C1C1 !important;" role="alert">
                <i class="bi bi-exclamation-triangle-fill fs-5 text-danger"></i>
                <div class="text-danger">{{ session('error') }}</div>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
            </div>
        @endif
    </div>

    <!-- Content Slot -->
    <main class="flex-grow-1">
        @yield('content')
    </main>

    <!-- Footer (Warm Espresso & Linen) -->
    <footer class="shop-footer">
        <div class="container">
            <div class="row g-4 mb-5">
                <div class="col-lg-4 col-md-6">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <div class="brand-badge-icon" style="width: 32px; height: 32px; font-size: 1rem;">
                            <i class="bi bi-flower1"></i>
                        </div>
                        <h5 class="fw-bold mb-0 text-white" style="letter-spacing: 0.12em; font-family: var(--font-serif);">Zahiyyah's Fit</h5>
                    </div>
                    <p class="small mb-3" style="color: #AFA29A; line-height: 1.8;">
                        Butik busana modest &amp; island resort wear terinspirasi ketenangan pesisir Bali. Menghadirkan siluet anggun berbahan linen alami, beach knitwear rajut halus putih kecoklatan, dan jahitan standar butik premium.
                    </p>
                    <div class="small" style="color: #EDE4D8;">
                        <i class="bi bi-geo-alt" style="color: var(--bali-tan);"></i> Seminyak &bull; Canggu &bull; Banda Aceh &bull; Indonesia
                    </div>
                </div>

                <div class="col-lg-2 col-md-6">
                    <h6 class="text-white mb-3" style="font-family: var(--font-serif); font-size: 1.05rem;">Koleksi Pilihan</h6>
                    <ul class="list-unstyled small mb-0 d-flex flex-column gap-2">
                        <li><a href="{{ route('shop.index', ['category' => 'outerwear-cardigan']) }}">Beach Knitwear Rajut</a></li>
                        <li><a href="{{ route('shop.index', ['category' => 'gamis-maxy-dress']) }}">Gamis Silk Abaya</a></li>
                        <li><a href="{{ route('shop.index', ['category' => 'blouse-tunik']) }}">Linen Coastal Blouse</a></li>
                        <li><a href="{{ route('shop.index', ['category' => 'celana-kulot']) }}">Highwaist Linen Kulot</a></li>
                        <li><a href="{{ route('shop.index', ['category' => 'hijab-pashmina']) }}">Pashmina Silk Voal</a></li>
                    </ul>
                </div>

                <div class="col-lg-3 col-md-6">
                    <h6 class="text-white mb-3" style="font-family: var(--font-serif); font-size: 1.05rem;">Standar Kualitas Butik</h6>
                    <ul class="list-unstyled small mb-0 d-flex flex-column gap-2" style="color: #AFA29A;">
                        <li><i class="bi bi-check2" style="color: var(--bali-tan);"></i> 100% Breathable Natural Fabric</li>
                        <li><i class="bi bi-check2" style="color: var(--bali-tan);"></i> Rajut Halus &amp; Modest Coastal Cut</li>
                        <li><i class="bi bi-check2" style="color: var(--bali-tan);"></i> Pengiriman Cepat ke Seluruh Nusantara</li>
                        <li><i class="bi bi-check2" style="color: var(--bali-tan);"></i> Layanan Konsultasi Busana 24/7</li>
                    </ul>
                </div>

                <div class="col-lg-3 col-md-6">
                    <h6 class="text-white mb-3" style="font-family: var(--font-serif); font-size: 1.05rem;">Metode Pembayaran Resmi</h6>
                    <p class="small mb-2" style="color: #AFA29A;">Simulasi Payment Gateway Instan &amp; Otomatis:</p>
                    <div class="d-flex flex-wrap gap-2">
                        <span class="payment-pill"><i class="bi bi-qr-code text-warning"></i> QRIS</span>
                        <span class="payment-pill"><i class="bi bi-bank"></i> BCA VA</span>
                        <span class="payment-pill"><i class="bi bi-bank"></i> Mandiri VA</span>
                        <span class="payment-pill"><i class="bi bi-bank"></i> BRI VA</span>
                        <span class="payment-pill"><i class="bi bi-bank"></i> BSI VA</span>
                        <span class="payment-pill"><i class="bi bi-wallet2 text-success"></i> GoPay</span>
                        <span class="payment-pill"><i class="bi bi-wallet2 text-danger"></i> ShopeePay</span>
                    </div>
                </div>
            </div>

            <div class="border-top pt-4 d-flex flex-wrap justify-content-between align-items-center small" style="border-color: #382F2A !important; color: #8D8076;">
                <div>&copy; {{ date('Y') }} <strong style="color: #FAF7F2;">Zahiyyah's Fit</strong> &bull; Luxury Island &amp; Modest Boutique.</div>
                <div class="d-flex align-items-center gap-3">
                    <span>Project UTS Web Programming 2</span>
                    <a href="{{ route('portal.login') }}" style="color: #AFA29A;" class="text-decoration-none">
                        <i class="bi bi-shield-lock"></i> Portal Staff
                    </a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap 5 JS & Zahiyyah Utilities -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/zahiyyah.js') }}?v={{ time() }}"></script>
    @stack('scripts')
</body>
</html>
