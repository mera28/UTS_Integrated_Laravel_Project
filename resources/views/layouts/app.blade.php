<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Backoffice') - Zahiyyah's Fit</title>
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
            --brand-light: #FDF9FA;
            --brand-sidebar: #1E1B1D;
            --brand-sidebar-hover: #2F2A2D;
            --brand-accent: #E8C5C8;
        }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #F8F9FA;
            color: #2D3748;
            overflow-x: hidden;
        }
        /* Sidebar styling */
        .sidebar {
            width: 270px;
            min-height: 100vh;
            background-color: var(--brand-sidebar);
            color: #FFFFFF;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1040;
            transition: all 0.3s ease;
            box-shadow: 4px 0 25px rgba(0, 0, 0, 0.07);
        }
        .sidebar-brand {
            padding: 24px 20px 20px;
            display: flex;
            align-items: center;
            gap: 12px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        }
        .brand-logo-icon {
            width: 42px;
            height: 42px;
            background: linear-gradient(135deg, #B76E79 0%, #D89AA2 100%);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
            color: #FFFFFF;
            box-shadow: 0 4px 10px rgba(183, 110, 121, 0.35);
        }
        .sidebar-user {
            padding: 18px 20px;
            background: rgba(255, 255, 255, 0.03);
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        }
        .sidebar-nav {
            padding: 16px 14px;
        }
        .nav-header {
            font-size: 0.72rem;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            color: #8C868B;
            font-weight: 700;
            margin: 16px 12px 6px;
        }
        .nav-link-custom {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 14px;
            color: #CFC9CC;
            border-radius: 10px;
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 500;
            transition: all 0.2s ease;
            margin-bottom: 4px;
        }
        .nav-link-custom:hover {
            background-color: var(--brand-sidebar-hover);
            color: #FFFFFF;
            transform: translateX(3px);
        }
        .nav-link-custom.active {
            background: linear-gradient(135deg, #B76E79 0%, #A35C67 100%);
            color: #FFFFFF;
            font-weight: 600;
            box-shadow: 0 4px 12px rgba(183, 110, 121, 0.3);
        }
        .nav-link-custom i {
            font-size: 1.15rem;
            width: 20px;
            text-align: center;
        }
        /* Main Content wrapper */
        .main-wrapper {
            margin-left: 270px;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            transition: all 0.3s ease;
        }
        /* Topbar */
        .topbar {
            height: 70px;
            background-color: #FFFFFF;
            border-bottom: 1px solid #ECECEC;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 30px;
            position: sticky;
            top: 0;
            z-index: 1020;
        }
        .btn-brand-primary {
            background-color: var(--brand-primary);
            color: #FFFFFF;
            border-radius: 10px;
            font-weight: 600;
            padding: 8px 16px;
            border: none;
            transition: all 0.2s;
        }
        .btn-brand-primary:hover {
            background-color: var(--brand-primary-hover);
            color: #FFFFFF;
        }
        .badge-role {
            font-size: 0.72rem;
            padding: 4px 10px;
            border-radius: 20px;
            font-weight: 700;
            letter-spacing: 0.3px;
        }
        .content-body {
            padding: 30px;
            flex: 1;
        }
        .card-custom {
            border: 1px solid #ECEEF2;
            border-radius: 16px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.03);
            background: #FFFFFF;
        }
        @media (max-width: 991.98px) {
            .sidebar {
                margin-left: -270px;
            }
            .sidebar.show {
                margin-left: 0;
            }
            .main-wrapper {
                margin-left: 0;
            }
        }
    </style>
    @stack('styles')
</head>
<body>
    <!-- Sidebar Navigation -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-brand">
            <div class="brand-logo-icon">
                <i class="bi bi-gem"></i>
            </div>
            <div>
                <h5 class="fw-bold mb-0 text-white" style="letter-spacing: -0.3px;">Zahiyyah's Fit</h5>
                <span class="small text-muted" style="font-size: 0.75rem;">Fashion & Sales System</span>
            </div>
        </div>

        <!-- Logged User Info -->
        <div class="sidebar-user">
            <div class="d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-2">
                    <div class="bg-secondary bg-opacity-25 rounded-circle p-2 text-white">
                        <i class="bi bi-person-fill"></i>
                    </div>
                    <div>
                        <div class="small fw-bold text-white text-truncate" style="max-width: 120px;">
                            {{ auth()->user()->name ?? 'User' }}
                        </div>
                        @if(auth()->user()->role === 'admin')
                            <span class="badge bg-danger badge-role"><i class="bi bi-shield-check"></i> OWNER</span>
                        @elseif(auth()->user()->role === 'staff')
                            <span class="badge bg-info text-dark badge-role"><i class="bi bi-person-badge"></i> STAFF</span>
                        @else
                            <span class="badge bg-light text-dark badge-role">MEMBER</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Navigation Menu -->
        <div class="sidebar-nav">
            <div class="nav-header">Utama</div>
            <a href="{{ route('dashboard') }}" class="nav-link-custom {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="bi bi-speedometer2"></i> Dashboard
            </a>

            <div class="nav-header">Manajemen Inventori</div>
            <a href="{{ url('/products') }}" class="nav-link-custom {{ request()->is('products*') ? 'active' : '' }}">
                <i class="bi bi-bag"></i> Master Produk Outfit
            </a>

            @if(auth()->user()->isAdmin())
                <a href="{{ url('/categories') }}" class="nav-link-custom {{ request()->is('categories*') ? 'active' : '' }}">
                    <i class="bi bi-tags"></i> Master Kategori
                </a>
                <a href="{{ url('/suppliers') }}" class="nav-link-custom {{ request()->is('suppliers*') ? 'active' : '' }}">
                    <i class="bi bi-truck"></i> Master Supplier
                </a>
            @endif

            <div class="nav-header">Aktivitas Transaksi</div>
            <a href="{{ url('/purchases') }}" class="nav-link-custom {{ request()->is('purchases*') ? 'active' : '' }}">
                <i class="bi bi-box-arrow-in-down"></i> Pembelian (Stock-In)
            </a>
            <a href="{{ url('/sales') }}" class="nav-link-custom {{ request()->is('sales*') ? 'active' : '' }}">
                <i class="bi bi-receipt"></i> Penjualan (POS & Order)
            </a>

            @if(auth()->user()->isAdmin())
                <div class="nav-header">Sistem & Hak Akses</div>
                <a href="{{ url('/users') }}" class="nav-link-custom {{ request()->is('users*') ? 'active' : '' }}">
                    <i class="bi bi-people"></i> Kelola Pengguna
                </a>
            @endif

            <div class="nav-header">E-Commerce</div>
            <a href="{{ route('shop.index') }}" target="_blank" class="nav-link-custom text-warning">
                <i class="bi bi-shop-window"></i> Lihat Toko Online <i class="bi bi-box-arrow-up-right ms-auto small"></i>
            </a>
        </div>
    </aside>

    <!-- Main Wrapper -->
    <div class="main-wrapper">
        <!-- Topbar -->
        <header class="topbar">
            <div class="d-flex align-items-center gap-3">
                <button class="btn btn-light d-lg-none border" type="button" onclick="toggleSidebar()">
                    <i class="bi bi-list fs-5"></i>
                </button>
                <div class="d-none d-sm-block">
                    <span class="text-muted small">Panel Manajemen</span>
                    <h6 class="mb-0 fw-bold text-dark">@yield('page_title', 'Dashboard')</h6>
                </div>
            </div>

            <!-- Topbar Right Profile & Actions -->
            <div class="d-flex align-items-center gap-3">
                <a href="{{ route('shop.index') }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3 d-none d-md-inline-flex align-items-center gap-1">
                    <i class="bi bi-bag-heart"></i> Katalog Shop
                </a>

                <div class="dropdown">
                    <button class="btn btn-light rounded-pill border dropdown-toggle d-flex align-items-center gap-2 py-1 px-3" type="button" data-bs-toggle="dropdown">
                        <div class="bg-dark text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 28px; height: 28px; font-size: 0.8rem;">
                            {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
                        </div>
                        <span class="small fw-semibold text-dark">{{ auth()->user()->name ?? 'User' }}</span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 rounded-3 mt-2 p-2" style="min-width: 200px;">
                        <li class="px-3 py-2 border-bottom">
                            <span class="d-block small text-muted">Masuk sebagai:</span>
                            <span class="fw-bold small text-dark">{{ auth()->user()->email }}</span>
                        </li>
                        <li>
                            <form action="{{ route('logout') }}" method="POST" class="mt-2">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger rounded-2 small fw-semibold d-flex align-items-center gap-2 py-2">
                                    <i class="bi bi-box-arrow-right"></i> Keluar (Logout)
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </header>

        <!-- Flash Messages & Errors Alert -->
        <main class="content-body">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-3 mb-4 d-flex align-items-center" role="alert">
                    <i class="bi bi-check-circle-fill me-2 fs-5"></i>
                    <div>{{ session('success') }}</div>
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm rounded-3 mb-4 d-flex align-items-center" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2 fs-5"></i>
                    <div>{{ session('error') }}</div>
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm rounded-3 mb-4" role="alert">
                    <div class="fw-bold mb-1"><i class="bi bi-x-circle-fill me-1"></i> Terdapat beberapa kesalahan input:</div>
                    <ul class="mb-0 ps-3 small">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Main Yield Content -->
            @yield('content')
        </main>

        <!-- Footer -->
        <footer class="bg-white border-top py-3 px-4 text-center text-muted small mt-auto">
            &copy; {{ date('Y') }} <strong>Zahiyyah's Fit</strong> — Integrated Fashion E-Commerce, Inventory & Sales Management (UTS Web Programming 2).
        </footer>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('show');
        }
    </script>
    @stack('scripts')
</body>
</html>
