@extends('layouts.shop')

@section('title', 'Koleksi Busana Modest & Fashion Muslimah')

@section('content')
<!-- Hero Showcase Banner -->
<section class="py-4 py-md-5" style="background: linear-gradient(135deg, #FFF3F4 0%, #F8E8EA 50%, #F5F0EE 100%);">
    <div class="container">
        <div class="row align-items-center g-4">
            <div class="col-lg-7">
                <span class="badge bg-white text-danger px-3 py-2 rounded-pill shadow-sm fw-bold mb-3 border">
                    <i class="bi bi-stars text-warning me-1"></i> New Season Modest Collection 2026
                </span>
                <h1 class="display-5 fw-extrabold text-dark mb-3" style="letter-spacing: -1px; font-weight: 800;">
                    Tampil Percaya Diri dengan Busana Modest Anggun & Elegan
                </h1>
                <p class="lead text-muted mb-4 small fs-6" style="line-height: 1.8;">
                    Temukan ragam koleksi Gamis Maxy, Blouse Peplum, Tunik Cantik, Celana Kulot, Rok Plisket, dan Hijab Voal Shimmer berkelas dari <strong>Zahiyyah's Fit</strong>. Nyaman dipakai sepanjang hari dengan material pilihan grade A.
                </p>
                <div class="d-flex flex-wrap gap-3">
                    <a href="#katalog" class="btn btn-brand-primary px-4 py-2 fw-bold">
                        <i class="bi bi-bag-heart me-1"></i> Belanja Koleksi Sekarang
                    </a>
                    @auth
                        @if(auth()->user()->isAdmin() || auth()->user()->isStaff())
                            <a href="{{ route('dashboard') }}" class="btn btn-outline-brand px-4 py-2">
                                <i class="bi bi-speedometer2 me-1"></i> Panel Backoffice
                            </a>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="btn btn-outline-brand px-4 py-2">
                            <i class="bi bi-shield-lock me-1"></i> Masuk Staff / Admin
                        </a>
                    @endauth
                </div>
            </div>
            <div class="col-lg-5 text-center">
                <div class="position-relative d-inline-block">
                    <img src="https://images.unsplash.com/photo-1583391733956-3750e0ff4e8b?q=80&w=600&auto=format&fit=crop" 
                         alt="Zahiyyah Fashion Model" 
                         class="img-fluid rounded-4 shadow-lg border border-4 border-white" 
                         style="max-height: 380px; width: 100%; object-fit: cover;">
                    <div class="position-absolute bottom-0 start-0 translate-middle-y bg-white p-3 rounded-4 shadow-sm border m-3 text-start">
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-check-circle-fill text-success fs-4"></i>
                            <div>
                                <div class="fw-bold small text-dark">Original Quality</div>
                                <div class="text-muted" style="font-size: 0.75rem;">100% Bahan Terverifikasi</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Filter & Search Section -->
<section class="py-4 bg-white border-bottom" id="katalog">
    <div class="container">
        <div class="d-flex align-items-center justify-content-between mb-3 flex-wrap gap-2">
            <div>
                <h5 class="fw-bold text-dark mb-0">Katalog Produk Outfit</h5>
                <small class="text-muted">Menampilkan busana fashion muslimah terkini</small>
            </div>
            <div class="text-muted small">
                Total <strong>{{ $products->total() }}</strong> Produk Ditemukan
            </div>
        </div>

        <!-- Filter Chips & Form -->
        <form action="{{ route('shop.index') }}" method="GET" class="row g-2 align-items-center mb-3">
            <input type="hidden" name="category" value="{{ request('category') }}">
            <div class="col-md-4">
                <input type="text" name="q" class="form-control search-input" placeholder="Cari nama pakaian atau warna..." value="{{ request('q') }}">
            </div>
            <div class="col-md-3 col-6">
                <select name="sort" class="form-select form-select-custom small" onchange="this.form.submit()">
                    <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Urutkan: Terbaru</option>
                    <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Harga: Terendah ke Tertinggi</option>
                    <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Harga: Tertinggi ke Terendah</option>
                    <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Nama: A ke Z</option>
                </select>
            </div>
            <div class="col-md-3 col-6">
                <div class="form-check form-switch pt-2">
                    <input class="form-check-input" type="checkbox" name="in_stock" value="1" id="inStockCheck" {{ request('in_stock') == '1' ? 'checked' : '' }} onchange="this.form.submit()">
                    <label class="form-check-label small text-secondary" for="inStockCheck">Hanya Stok Tersedia</label>
                </div>
            </div>
            <div class="col-md-2 text-end">
                @if(request()->hasAny(['q', 'category', 'sort', 'in_stock']))
                    <a href="{{ route('shop.index') }}" class="btn btn-sm btn-light border w-100"><i class="bi bi-x-circle"></i> Reset</a>
                @endif
            </div>
        </form>

        <!-- Category Buttons -->
        <div class="d-flex gap-2 overflow-auto pb-2 text-nowrap">
            <a href="{{ route('shop.index', request()->except('category')) }}" class="btn btn-sm {{ !request('category') ? 'btn-dark' : 'btn-light border' }} rounded-pill px-3">
                Semua
            </a>
            @foreach($categories as $category)
                <a href="{{ route('shop.index', array_merge(request()->query(), ['category' => $category->slug])) }}" 
                   class="btn btn-sm {{ request('category') == $category->slug ? 'btn-dark' : 'btn-light border' }} rounded-pill px-3">
                    {{ $category->name }} ({{ $category->products_count }})
                </a>
            @endforeach
        </div>
    </div>
</section>

<!-- Product Grid Section -->
<section class="py-5">
    <div class="container">
        <div class="row g-4">
            @forelse($products as $product)
            <div class="col-xl-3 col-lg-4 col-sm-6">
                <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden position-relative product-card bg-white" style="transition: transform 0.2s, box-shadow 0.2s;">
                    <!-- Stock Badge -->
                    <div class="position-absolute top-0 start-0 m-3 z-1">
                        @if($product->stock <= 0)
                            <span class="badge bg-danger rounded-pill px-3 py-1">Habis</span>
                        @elseif($product->isLowStock())
                            <span class="badge bg-warning text-dark rounded-pill px-3 py-1"><i class="bi bi-clock-history me-1"></i> Sisa {{ $product->stock }} pcs</span>
                        @else
                            <span class="badge bg-success bg-opacity-90 rounded-pill px-3 py-1">Ready {{ $product->stock }} pcs</span>
                        @endif
                    </div>

                    <!-- Category Badge -->
                    <div class="position-absolute top-0 end-0 m-3 z-1">
                        <span class="badge bg-white text-dark shadow-sm rounded-pill px-2 py-1 border small">
                            {{ $product->category->name ?? 'Outfit' }}
                        </span>
                    </div>

                    <!-- Product Image linking to detail -->
                    <a href="{{ route('shop.products.show', $product) }}" class="overflow-hidden bg-light text-center d-block position-relative" style="height: 270px;">
                        <img src="{{ $product->image_url ?? 'https://images.unsplash.com/photo-1515886657613-9f3515b0c78f?q=80&w=800&auto=format&fit=crop' }}" 
                             class="w-100 h-100 object-fit-cover" 
                             alt="{{ $product->name }}"
                             onerror="this.src='https://images.unsplash.com/photo-1515886657613-9f3515b0c78f?q=80&w=800&auto=format&fit=crop'">
                    </a>

                    <!-- Card Body -->
                    <div class="card-body p-3 d-flex flex-column justify-content-between">
                        <div>
                            <div class="small text-muted mb-1">{{ $product->sku }}</div>
                            <h6 class="fw-bold text-dark text-truncate mb-2" title="{{ $product->name }}">
                                <a href="{{ route('shop.products.show', $product) }}" class="text-decoration-none text-dark">
                                    {{ $product->name }}
                                </a>
                            </h6>
                            <p class="text-muted small mb-2 text-truncate-2" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; line-height: 1.4; font-size: 0.8rem;">
                                {{ $product->description }}
                            </p>
                            <div class="d-flex flex-wrap gap-1 mb-2">
                                @if($product->size)
                                    <span class="badge bg-light text-secondary border small">{{ $product->size }}</span>
                                @endif
                                @if($product->color)
                                    <span class="badge bg-light text-secondary border small">{{ $product->color }}</span>
                                @endif
                            </div>
                        </div>

                        <div>
                            <div class="d-flex align-items-baseline justify-content-between mb-3">
                                <div>
                                    <span class="small text-muted d-block" style="font-size: 0.72rem;">Harga Terbaik</span>
                                    <span class="fw-extrabold text-danger fs-5" style="font-weight: 800;">
                                        {{ \App\Support\FormatHelper::rupiah($product->selling_price) }}
                                    </span>
                                </div>
                            </div>

                            <div class="d-grid gap-2">
                                <a href="{{ route('shop.products.show', $product) }}" class="btn btn-outline-danger btn-sm rounded-pill fw-semibold py-2">
                                    <i class="bi bi-eye me-1"></i> Lihat Detail & Beli
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12 text-center py-5">
                <div class="p-5 bg-white rounded-4 shadow-sm border max-w-md mx-auto">
                    <i class="bi bi-search text-muted display-4 mb-3 d-block"></i>
                    <h5 class="fw-bold text-dark">Tidak Ada Produk Ditemukan</h5>
                    <p class="text-muted small mb-3">Coba gunakan kata kunci pencarian lain atau pilih kategori yang tersedia.</p>
                    <a href="{{ route('shop.index') }}" class="btn btn-brand-primary btn-sm rounded-pill px-4">
                        Tampilkan Semua Produk
                    </a>
                </div>
            </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="mt-5 d-flex justify-content-center">
            {{ $products->links() }}
        </div>
    </div>
</section>
@endsection
