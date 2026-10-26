@extends('layouts.shop')

@section('title', 'Koleksi Busana Modest & Island Resort Wear')

@section('content')
<!-- ==========================================================================
     HERO SHOWCASE: LUXURY BALI BEACH & COCONUT PALMS PHOTOGRAPHY
     ========================================================================== -->
<section class="bali-hero-section">
    <div class="bali-hero-overlay"></div>
    <div class="container position-relative z-1">
        <div class="row align-items-center g-5">
            <div class="col-lg-7">
                <span class="bali-hero-tag">
                    <i class="bi bi-sun me-1"></i> Island &bull; Modest Resort Wear &bull; Collection 2026
                </span>
                <h1 class="bali-hero-title">
                    Zahiyyah's Fit &mdash; Island &amp; Modest Resort Wear
                </h1>
                <p class="bali-hero-desc">
                    <em>Effortless Elegance Inspired by Bali's Coastal Serenity.</em> Temukan perpaduan sempurna antara material linen adem alami, sutra mengalir anggun, dan potongan modest yang nyaman untuk suasana santai di pesisir maupun momen istimewa Anda.
                </p>
                <div class="d-flex flex-wrap gap-3">
                    <a href="#katalog" class="btn btn-brand-primary">
                        <i class="bi bi-compass me-1"></i> Jelajahi Koleksi Busana
                    </a>
                    @auth
                        @if(auth()->user()->isAdmin() || auth()->user()->isStaff())
                            <a href="{{ route('dashboard') }}" class="btn btn-outline-brand">
                                <i class="bi bi-speedometer2 me-1"></i> Panel Backoffice
                            </a>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="btn btn-outline-brand">
                            <i class="bi bi-person me-1"></i> Masuk Member / Google
                        </a>
                    @endauth
                </div>
            </div>
            <div class="col-lg-5 text-center">
                <div class="hero-featured-frame d-inline-block">
                    <img src="https://images.unsplash.com/photo-1583391733956-3750e0ff4e8b?q=80&w=700&auto=format&fit=crop" 
                         alt="Zahiyyah Bali Boutique Outfit" 
                         class="img-fluid" 
                         style="max-height: 440px; width: 100%; object-fit: cover;">
                    <div class="hero-floating-card text-start">
                        <div class="d-flex align-items-center gap-3">
                            <div class="brand-badge-icon" style="width: 34px; height: 34px; font-size: 1rem;">
                                <i class="bi bi-patch-check"></i>
                            </div>
                            <div>
                                <div class="fw-bold small" style="color: var(--bali-espresso); font-family: var(--font-serif); font-size: 0.95rem;">100% Breathable Fabric</div>
                                <div class="text-muted" style="font-size: 0.72rem; letter-spacing: 0.05em; text-transform: uppercase;">Linen, Silk &amp; Pure Voal</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ==========================================================================
     EDITORIAL STORY SECTION (COASTAL BALI ATMOSPHERE)
     ========================================================================== -->
<section class="editorial-story-section">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-5 order-lg-1 order-2">
                <div class="editorial-img-wrap">
                    <!-- Real Coastal Palm Trees & Tropical Resort Photography -->
                    <img src="https://images.unsplash.com/photo-1540541338287-41700207dee6?auto=format&fit=crop&w=1200&q=80" 
                         alt="Bali Coastal Palm Trees Resort" 
                         class="w-100 object-fit-cover" 
                         style="height: 380px;">
                </div>
            </div>
            <div class="col-lg-7 order-lg-2 order-1">
                <span class="editorial-tag">The Boutique Philosophy</span>
                <h2 class="editorial-title">
                    Harmoni Kesejukan Alami &amp; Keanggunan Busana Modest
                </h2>
                <div class="editorial-quote">
                    "Setiap helai pakaian dirancang khusus agar tetap sejuk di bawah terpaan angin pesisir, memberikan kebebasan bergerak dengan siluet sopan dan sentuhan kemewahan yang tenang."
                </div>
                <p class="small text-muted mb-4" style="line-height: 1.8;">
                    Terinspirasi oleh ketenangan pantai Seminyak dan suasana resort tropis Bali, butik <strong>Zahiyyah's Fit</strong> memadukan bahan berserat alami berkualitas tinggi dengan potongan longgar yang jatuh indah di tubuh. Mulai dari Gamis Silk Abaya, Tunik Berbahan Linen, Kulot Santai, hingga Pashmina Voal Shimmer yang wudhu-friendly.
                </p>
                <div class="d-flex flex-wrap gap-4 pt-2">
                    <div>
                        <div class="fw-bold text-dark fs-5" style="font-family: var(--font-serif); color: var(--bali-espresso);">100%</div>
                        <div class="small text-muted" style="font-size: 0.75rem;">Serat Kain Pilihan</div>
                    </div>
                    <div class="vr" style="background-color: var(--bali-border);"></div>
                    <div>
                        <div class="fw-bold text-dark fs-5" style="font-family: var(--font-serif); color: var(--bali-espresso);">Hand-Finished</div>
                        <div class="small text-muted" style="font-size: 0.75rem;">Jahitan Standar Butik</div>
                    </div>
                    <div class="vr" style="background-color: var(--bali-border);"></div>
                    <div>
                        <div class="fw-bold text-dark fs-5" style="font-family: var(--font-serif); color: var(--bali-espresso);">Modest Cut</div>
                        <div class="small text-muted" style="font-size: 0.75rem;">Anggun &amp; Tidak Terawang</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ==========================================================================
     CATALOG SECTION & FILTER (CLEAN WHITE & SOFT BEIGE)
     ========================================================================== -->
<section class="py-5" id="katalog" style="background-color: #FFFFFF;">
    <div class="container">
        <!-- Section Header -->
        <div class="d-flex align-items-end justify-content-between mb-4 flex-wrap gap-3 pb-3 border-bottom" style="border-color: var(--bali-border-light) !important;">
            <div>
                <span class="text-uppercase small fw-semibold" style="letter-spacing: 0.15em; color: var(--bali-tan); font-size: 0.75rem;">Curated Wardrobe</span>
                <h3 class="mb-1" style="font-family: var(--font-serif); font-size: 2.1rem; color: var(--bali-espresso);">
                    Koleksi Busana &amp; Outfit Terpilih
                </h3>
                <p class="text-muted small mb-0">Menampilkan busana modest pilihan dengan kenyamanan maksimal</p>
            </div>
            <div class="text-muted small" style="color: var(--bali-muted);">
                Total <strong style="color: var(--bali-espresso);">{{ $products->total() }}</strong> Outfit Ditemukan
            </div>
        </div>

        <!-- Filter Chips & Search Bar Form -->
        <form action="{{ route('shop.index') }}" method="GET" class="row g-3 align-items-center mb-4">
            <input type="hidden" name="category" value="{{ request('category') }}">
            <div class="col-md-5">
                <div class="position-relative">
                    <i class="bi bi-search search-icon-inside"></i>
                    <input type="text" name="q" class="form-control search-input w-100" placeholder="Cari nama outfit, bahan, atau warna..." value="{{ request('q') }}">
                </div>
            </div>
            <div class="col-md-3 col-6">
                <select name="sort" class="form-select form-select-custom small" onchange="this.form.submit()">
                    <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Urutan: Koleksi Terbaru</option>
                    <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Harga: Terendah ke Tertinggi</option>
                    <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Harga: Tertinggi ke Terendah</option>
                    <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Nama: Alfabet A &ndash; Z</option>
                </select>
            </div>
            <div class="col-md-2 col-6">
                <div class="form-check form-switch pt-2">
                    <input class="form-check-input" type="checkbox" name="in_stock" value="1" id="inStockCheck" {{ request('in_stock') == '1' ? 'checked' : '' }} onchange="this.form.submit()">
                    <label class="form-check-label small" style="color: var(--bali-taupe);" for="inStockCheck">Hanya Ready</label>
                </div>
            </div>
            <div class="col-md-2 text-end">
                @if(request()->hasAny(['q', 'category', 'sort', 'in_stock']))
                    <a href="{{ route('shop.index') }}" class="btn btn-sm btn-sand w-100">
                        <i class="bi bi-arrow-counterclockwise me-1"></i> Reset Filter
                    </a>
                @endif
            </div>
        </form>

        <!-- Category Minimalist Pill Buttons -->
        <div class="d-flex gap-2 overflow-auto pb-3 mb-4 text-nowrap" style="scrollbar-width: thin;">
            <a href="{{ route('shop.index', request()->except('category')) }}" class="category-pill {{ !request('category') ? 'active' : '' }}">
                Semua Koleksi
            </a>
            @foreach($categories as $category)
                <a href="{{ route('shop.index', array_merge(request()->query(), ['category' => $category->slug])) }}" 
                   class="category-pill {{ request('category') == $category->slug ? 'active' : '' }}">
                    {{ $category->name }} ({{ $category->products_count }})
                </a>
            @endforeach
        </div>

        <!-- Product Grid (Magazine 3:4 Aspect Ratio) -->
        <div class="row g-4">
            @forelse($products as $product)
            <div class="col-xl-3 col-lg-4 col-sm-6">
                <div class="product-card-bali">
                    <!-- Image with 3:4 aspect ratio -->
                    <div class="product-card-img-wrap">
                        <!-- Stock Status Badge -->
                        <div class="position-absolute top-0 start-0 m-3 z-1">
                            @if($product->stock <= 0)
                                <span class="badge-stock-bali empty">Habis</span>
                            @elseif($product->isLowStock())
                                <span class="badge-stock-bali low"><i class="bi bi-clock me-1"></i> Sisa {{ $product->stock }} pcs</span>
                            @else
                                <span class="badge-stock-bali">Ready {{ $product->stock }} pcs</span>
                            @endif
                        </div>

                        <!-- Product Photo linking to detail -->
                        <a href="{{ route('shop.products.show', $product) }}" class="d-block w-100 h-100">
                            <img src="{{ $product->image_url ?? 'https://images.unsplash.com/photo-1515886657613-9f3515b0c78f?q=80&w=800&auto=format&fit=crop' }}" 
                                 alt="{{ $product->name }}"
                                 onerror="this.src='https://images.unsplash.com/photo-1515886657613-9f3515b0c78f?q=80&w=800&auto=format&fit=crop'">
                        </a>
                    </div>

                    <!-- Card Body -->
                    <div class="p-3 d-flex flex-column justify-content-between flex-grow-1">
                        <div>
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="product-tag-overline">{{ $product->category->name ?? 'Outfit' }}</span>
                                <span class="text-muted" style="font-size: 0.68rem;">{{ $product->sku }}</span>
                            </div>
                            
                            <h6 class="product-title-serif" title="{{ $product->name }}">
                                <a href="{{ route('shop.products.show', $product) }}">
                                    {{ $product->name }}
                                </a>
                            </h6>

                            <div class="d-flex flex-wrap gap-1 mb-2">
                                @if($product->size)
                                    <span class="badge" style="background-color: var(--bali-bg-ivory); color: var(--bali-taupe); border: 1px solid var(--bali-border); font-size: 0.68rem;">{{ $product->size }}</span>
                                @endif
                                @if($product->color)
                                    <span class="badge" style="background-color: var(--bali-bg-ivory); color: var(--bali-taupe); border: 1px solid var(--bali-border); font-size: 0.68rem;">{{ $product->color }}</span>
                                @endif
                            </div>
                        </div>

                        <div class="pt-2 border-top" style="border-color: var(--bali-border-light) !important;">
                            <div class="d-flex align-items-baseline justify-content-between mb-3">
                                <div>
                                    <span class="small d-block text-muted" style="font-size: 0.68rem; letter-spacing: 0.05em; text-transform: uppercase;">Harga Butik</span>
                                    <span class="product-price-bali">
                                        {{ \App\Support\FormatHelper::rupiah($product->selling_price) }}
                                    </span>
                                </div>
                            </div>

                            <a href="{{ route('shop.products.show', $product) }}" class="btn btn-sand w-100 py-2 d-flex align-items-center justify-content-center">
                                <i class="bi bi-eye me-1"></i> Lihat Koleksi
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12 text-center py-5">
                <div class="p-5 rounded-4 border max-w-md mx-auto surface-ivory" style="border-color: var(--bali-border) !important;">
                    <div class="brand-badge-icon mx-auto mb-3" style="width: 54px; height: 54px; font-size: 1.6rem;">
                        <i class="bi bi-search"></i>
                    </div>
                    <h5 class="fw-bold mb-2" style="font-family: var(--font-serif); color: var(--bali-espresso);">Tidak Ada Koleksi Ditemukan</h5>
                    <p class="text-muted small mb-4">Coba cari dengan kata kunci lain atau pilih kategori busana yang tersedia.</p>
                    <a href="{{ route('shop.index') }}" class="btn btn-brand-primary px-4 py-2">
                        Tampilkan Semua Koleksi
                    </a>
                </div>
            </div>
            @endforelse
        </div>

        <!-- Pagination (Clean & Constrained) -->
        <div class="mt-5 d-flex justify-content-center">
            {{ $products->links() }}
        </div>
    </div>
</section>
@endsection
