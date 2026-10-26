@extends('layouts.shop')

@section('title', 'Koleksi Busana Modest & Island Resort Wear')

@section('content')
<!-- ==========================================================================
     HERO SHOWCASE: BALI BEACH & COCONUT PALMS WITH ASYMMETRICAL TRANSPARENCY
     Left: Soft White Gradient Fade for Crisp Readability
     Right: Crystal Clear Tropical Bali Beach, Palms, & White-Tan Knitwear Model
     ========================================================================== -->
<section class="bali-hero-section">
    <div class="container position-relative z-1">
        <div class="row align-items-center g-5">
            <!-- Left Narrative Column (Faded Soft White for Optimal Readability) -->
            <div class="col-lg-7">
                <span class="bali-hero-tag">
                    <i class="bi bi-sun"></i> Island Resort &bull; Beach Knitwear &bull; Collection 2026
                </span>
                <h1 class="bali-hero-title">
                    Zahiyyah's Fit &mdash; Island &amp; Modest Resort Wear
                </h1>
                <p class="bali-hero-desc">
                    <em>Effortless Elegance Inspired by Bali's Coastal Serenity.</em> Perpaduan istimewa busana rajut pantai (*beach knitwear*) warna putih kecoklatan (cream-tan), linen alami yang sejuk, dan siluet modest berkelas untuk kenyamanan maksimal di pesisir maupun momen berharga Anda.
                </p>
                <div class="d-flex flex-wrap gap-3">
                    <a href="#katalog" class="btn btn-brand-primary">
                        <i class="bi bi-compass"></i> Jelajahi Koleksi Busana
                    </a>
                    @auth
                        @if(auth()->user()->isAdmin() || auth()->user()->isStaff())
                            <a href="{{ route('dashboard') }}" class="btn btn-outline-brand">
                                <i class="bi bi-speedometer2"></i> Panel Backoffice
                            </a>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="btn btn-outline-brand">
                            <i class="bi bi-person"></i> Masuk Member / Google
                        </a>
                    @endauth
                </div>
            </div>

            <!-- Right Visual Column (Model Wearing White-Tan Beach Knitwear / Resort Outfit) -->
            <div class="col-lg-5 text-center">
                <div class="hero-arch-frame d-inline-block">
                    <!-- High-Resolution Editorial Photo: Model in White-Tan Resort Knitwear -->
                    <img src="https://images.unsplash.com/photo-1515886657613-9f3515b0c78f?auto=format&fit=crop&w=900&q=85" 
                         alt="Model Baju Pantai Rajut Putih Kecoklatan - Zahiyyah's Fit Bali" 
                         class="img-fluid w-100 object-fit-cover" 
                         style="height: 480px;">
                    
                    <!-- Inset Floating Card with Close-Up Texture of White-Tan Knitwear -->
                    <div class="hero-knit-badge text-start">
                        <img src="https://images.unsplash.com/photo-1620799140408-edc6dcb6d633?auto=format&fit=crop&w=600&q=85" 
                             alt="Detail Tekstur Baju Rajut Putih Coklat" 
                             class="knit-thumb-mini">
                        <div>
                            <div class="fw-bold" style="color: var(--bali-espresso); font-family: var(--font-serif); font-size: 1rem; line-height: 1.2;">
                                Beach Knitwear &amp; Linen
                            </div>
                            <div class="text-muted" style="font-size: 0.72rem; letter-spacing: 0.05em; text-transform: uppercase;">
                                Hand-Crafted &bull; Pure Cream &amp; Tan
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ==========================================================================
     EDITORIAL STORY SECTION (COASTAL BALI PALM TREES & SERENITY)
     ========================================================================== -->
<section class="editorial-story-section">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-5 order-lg-1 order-2">
                <div class="editorial-img-wrap">
                    <!-- Real Coastal Palm Trees & Tropical Resort Photography -->
                    <img src="https://images.unsplash.com/photo-1540541338287-41700207dee6?auto=format&fit=crop&w=1200&q=80" 
                         alt="Pohon Kelapa Pantai Tropis Bali Resort" 
                         class="w-100 object-fit-cover" 
                         style="height: 390px;">
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
                    Terinspirasi oleh ketenangan pantai Seminyak dan suasana resort tropis Bali, butik <strong>Zahiyyah's Fit</strong> memadukan bahan serat alami berkualitas tinggi dengan potongan longgar yang jatuh indah di tubuh. Mulai dari Beach Knitwear rajut pantai warna putih-tan, Gamis Silk Abaya, Tunik Berbahan Linen, Kulot Santai, hingga Pashmina Voal Shimmer yang wudhu-friendly.
                </p>
                <div class="d-flex flex-wrap gap-4 pt-2">
                    <div>
                        <div class="fw-bold fs-5" style="font-family: var(--font-serif); color: var(--bali-espresso);">100%</div>
                        <div class="small text-muted" style="font-size: 0.75rem;">Serat Alami Adem</div>
                    </div>
                    <div class="vr" style="background-color: var(--bali-border);"></div>
                    <div>
                        <div class="fw-bold fs-5" style="font-family: var(--font-serif); color: var(--bali-espresso);">Hand-Finished</div>
                        <div class="small text-muted" style="font-size: 0.75rem;">Jahitan Standar Butik</div>
                    </div>
                    <div class="vr" style="background-color: var(--bali-border);"></div>
                    <div>
                        <div class="fw-bold fs-5" style="font-family: var(--font-serif); color: var(--bali-espresso);">Modest Cut</div>
                        <div class="small text-muted" style="font-size: 0.75rem;">Anggun &amp; Tidak Terawang</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ==========================================================================
     CATALOG SECTION & FILTER (PURE CLEAN WHITE & WARM TAN ACCENTS)
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
                <p class="text-muted small mb-0">Menampilkan busana modest &amp; beach resort wear pilihan dengan kenyamanan maksimal</p>
            </div>
            <div class="text-muted small" style="color: var(--bali-muted);">
                Total <strong style="color: var(--bali-espresso);">{{ $products->total() }}</strong> Outfit Ditemukan
            </div>
        </div>

        <!-- Filter Chips & Search Bar Form (Zero Overlap Flex Search) -->
        <form action="{{ route('shop.index') }}" method="GET" class="row g-3 align-items-center mb-4">
            <input type="hidden" name="category" value="{{ request('category') }}">
            <div class="col-md-5">
                <div class="search-box-wrapper">
                    <span class="search-icon-box"><i class="bi bi-search"></i></span>
                    <input type="text" name="q" class="search-input-field flex-grow-1" placeholder="Cari nama outfit, bahan rajut, atau warna..." value="{{ request('q') }}">
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
                        <i class="bi bi-arrow-counterclockwise"></i> Reset Filter
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
                                <span class="badge-stock-bali low"><i class="bi bi-clock"></i> Sisa {{ $product->stock }} pcs</span>
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

                            <div class="d-flex gap-2">
                                <a href="{{ route('shop.products.show', $product) }}" class="btn btn-outline-brand btn-sm flex-fill py-2 d-flex align-items-center justify-content-center">
                                    <i class="bi bi-eye"></i> Detail
                                </a>
                                @if($product->stock > 0)
                                    <button type="button" 
                                            class="btn btn-brand-primary btn-sm flex-fill py-2 d-flex align-items-center justify-content-center btn-quick-select"
                                            data-bs-toggle="modal" 
                                            data-bs-target="#quickSelectModal"
                                            data-id="{{ $product->id }}"
                                            data-name="{{ $product->name }}"
                                            data-sku="{{ $product->sku }}"
                                            data-category="{{ $product->category->name ?? 'Outfit' }}"
                                            data-price="{{ \App\Support\FormatHelper::rupiah($product->selling_price) }}"
                                            data-image="{{ $product->image_url ?? 'https://images.unsplash.com/photo-1515886657613-9f3515b0c78f?q=80&w=800&auto=format&fit=crop' }}"
                                            data-stock="{{ $product->stock }}"
                                            data-sizes="{{ $product->size ?: 'All Size' }}"
                                            data-colors="{{ $product->color ?: 'Standar' }}"
                                            data-action="{{ route('cart.add', $product) }}">
                                        <i class="bi bi-bag-plus"></i> + Keranjang
                                    </button>
                                @else
                                    <button type="button" class="btn btn-sand btn-sm flex-fill py-2" disabled>
                                        Habis
                                    </button>
                                @endif
                            </div>
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

<!-- ==========================================================================
     QUICK SELECT VARIANT MODAL (SHOPEE & BOUTIQUE STYLE)
     Allows Customer to choose Size, Color, and Quantity before adding to cart
     ========================================================================== -->
<div class="modal fade" id="quickSelectModal" tabindex="-1" aria-labelledby="quickSelectModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 520px;">
        <div class="modal-content border-0 rounded-4 shadow-lg overflow-hidden" style="background-color: #FFFFFF; border: 1px solid var(--bali-border) !important;">
            <!-- Modal Header -->
            <div class="modal-header border-0 pb-0 pt-4 px-4 position-relative">
                <span class="badge" style="background-color: var(--bali-bg-linen); color: var(--bali-tan-dark); border: 1px solid var(--bali-border); font-size: 0.72rem; letter-spacing: 0.08em; text-transform: uppercase;">
                    <i class="bi bi-flower1"></i> Pilih Varian Busana Butik
                </span>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Modal Form -->
            <form id="quickSelectForm" action="" method="POST">
                @csrf
                <div class="modal-body px-4 pt-3 pb-2">
                    <!-- Product Header Snippet -->
                    <div class="d-flex gap-3 align-items-center mb-4 p-3 rounded-4 surface-ivory border" style="border-color: var(--bali-border-light) !important;">
                        <img id="modalProductImage" src="" alt="Produk" class="rounded-3 object-fit-cover flex-shrink-0" style="width: 76px; height: 96px; border: 1px solid var(--bali-border);">
                        <div class="flex-grow-1 min-w-0">
                            <span id="modalProductCategory" class="badge mb-1" style="background-color: var(--bali-bg-linen); color: var(--bali-taupe); font-size: 0.68rem;"></span>
                            <h6 id="modalProductName" class="fw-bold mb-1 text-truncate" style="font-family: var(--font-serif); font-size: 1.15rem; color: var(--bali-espresso);"></h6>
                            <div class="d-flex align-items-baseline gap-2 mb-1">
                                <span id="modalProductPrice" class="fs-5 fw-bold" style="color: var(--bali-tan-dark); font-weight: 800;"></span>
                            </div>
                            <div class="small">
                                <span class="badge" style="background-color: #EBF5EE; color: #1E6B37; border: 1px solid #C4E2CE;" id="modalProductStockBadge"></span>
                            </div>
                        </div>
                    </div>

                    <!-- Size Selector (Interactive Radio Pills) -->
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <label class="form-label-custom mb-0 fw-bold" style="color: var(--bali-espresso); font-size: 0.85rem;">
                                <i class="bi bi-rulers me-1" style="color: var(--bali-tan);"></i> 1. Pilihan Ukuran (Size):
                            </label>
                            <span class="small text-muted fw-semibold" id="selectedSizeLabel" style="font-size: 0.78rem; color: var(--bali-tan-dark) !important;"></span>
                        </div>
                        <div class="d-flex flex-wrap gap-2" id="modalSizeContainer">
                            <!-- Populated dynamically via JS -->
                        </div>
                    </div>

                    <!-- Color Selector (Interactive Radio Pills) -->
                    <div class="mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <label class="form-label-custom mb-0 fw-bold" style="color: var(--bali-espresso); font-size: 0.85rem;">
                                <i class="bi bi-palette me-1" style="color: var(--bali-tan);"></i> 2. Pilihan Warna (Color):
                            </label>
                            <span class="small text-muted fw-semibold" id="selectedColorLabel" style="font-size: 0.78rem; color: var(--bali-tan-dark) !important;"></span>
                        </div>
                        <div class="d-flex flex-wrap gap-2" id="modalColorContainer">
                            <!-- Populated dynamically via JS -->
                        </div>
                    </div>

                    <!-- Quantity Input (- 1 +) -->
                    <div class="mb-4">
                        <label class="form-label-custom d-block fw-bold mb-2" style="color: var(--bali-espresso); font-size: 0.85rem;">
                            <i class="bi bi-basket2 me-1" style="color: var(--bali-tan);"></i> 3. Kuantitas Pesanan:
                        </label>
                        <div class="d-flex align-items-center gap-3">
                            <div class="input-group" style="max-width: 150px;">
                                <button class="btn btn-sand" type="button" id="modalQtyMinus">-</button>
                                <input type="number" name="quantity" id="modalQtyInput" class="form-control text-center fw-bold" value="1" min="1" max="1">
                                <button class="btn btn-sand" type="button" id="modalQtyPlus">+</button>
                            </div>
                            <span class="text-muted small" id="modalStockHelper"></span>
                        </div>
                    </div>
                </div>

                <!-- Modal Footer / Actions -->
                <div class="modal-footer border-top p-4 d-flex gap-2" style="border-color: var(--bali-border-light) !important; background-color: #FAF7F2;">
                    <button type="submit" name="buy_now" value="0" class="btn btn-sand flex-fill py-2 d-flex align-items-center justify-content-center">
                        <i class="bi bi-bag-plus"></i> Masukkan ke Keranjang
                    </button>
                    <button type="submit" name="buy_now" value="1" class="btn btn-brand-primary flex-fill py-2 d-flex align-items-center justify-content-center">
                        <i class="bi bi-lightning-charge"></i> Beli Sekarang
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
