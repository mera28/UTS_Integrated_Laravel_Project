@extends('layouts.shop')

@section('title', $product->name . ' - Zahiyyah\'s Fit Bali Boutique')

@section('content')
<div class="container py-4 py-md-5">
    <!-- Breadcrumb -->
    <nav class="mb-4 small" aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('shop.index') }}" class="text-decoration-none" style="color: var(--bali-muted);">Koleksi Butik</a></li>
            <li class="breadcrumb-item">
                <a href="{{ route('shop.index', ['category' => $product->category->slug ?? '']) }}" class="text-decoration-none" style="color: var(--bali-muted);">
                    {{ $product->category->name ?? 'Kategori' }}
                </a>
            </li>
            <li class="breadcrumb-item active fw-semibold" style="color: var(--bali-espresso);" aria-current="page">{{ $product->name }}</li>
        </ol>
    </nav>

    <div class="row g-4 g-lg-5 mb-5 align-items-start">
        <!-- Photo Gallery Column (Portrait Aspect Ratio) -->
        <div class="col-lg-5 text-center">
            <div class="card border-0 rounded-4 overflow-hidden bg-white p-2" style="border: 1px solid var(--bali-border-light) !important; box-shadow: 0 16px 36px rgba(44, 34, 30, 0.04);">
                <img src="{{ $product->image_url ?? 'https://images.unsplash.com/photo-1515886657613-9f3515b0c78f?q=80&w=800&auto=format&fit=crop' }}" 
                     alt="{{ $product->name }}" 
                     class="img-fluid rounded-4 w-100 object-fit-cover" 
                     style="max-height: 520px; aspect-ratio: 3/4;"
                     onerror="this.src='https://images.unsplash.com/photo-1515886657613-9f3515b0c78f?q=80&w=800&auto=format&fit=crop'">
            </div>
        </div>

        <!-- Product Details Column -->
        <div class="col-lg-7">
            <div class="ps-lg-2">
                <div class="d-flex align-items-center gap-2 mb-2 flex-wrap">
                    <span class="badge" style="background-color: var(--bali-bg-linen); color: var(--bali-tan-dark); border: 1px solid var(--bali-border); font-size: 0.72rem; letter-spacing: 0.08em; text-transform: uppercase;">
                        <i class="bi bi-flower1 me-1"></i> Bali Resort Collection
                    </span>
                    <span class="badge" style="background-color: var(--bali-bg-ivory); color: var(--bali-taupe); border: 1px solid var(--bali-border-light); font-size: 0.72rem;">{{ $product->category->name ?? 'Outfit' }}</span>
                    <span class="text-muted small ms-auto">SKU: <code>{{ $product->sku }}</code></span>
                </div>

                <h1 class="mb-3" style="font-family: var(--font-serif); font-size: 2.3rem; font-weight: 500; color: var(--bali-espresso); line-height: 1.2;">
                    {{ $product->name }}
                </h1>

                <!-- Rating & Stock Summary -->
                <div class="d-flex align-items-center gap-3 mb-3 small flex-wrap">
                    <div class="d-flex align-items-center gap-1" style="color: var(--bali-tan);">
                        <i class="bi bi-star-fill"></i>
                        <span class="fw-bold" style="color: var(--bali-espresso);">4.9</span>
                        <span class="text-muted">(128 Ulasan Butik)</span>
                    </div>
                    <span class="text-muted">&bull;</span>
                    <div class="text-muted">Terjual 250+ pcs</div>
                    <span class="text-muted">&bull;</span>
                    <div>
                        @if($product->stock <= 0)
                            <span class="badge-stock-bali empty">Stok Habis</span>
                        @elseif($product->isLowStock())
                            <span class="badge-stock-bali low"><i class="bi bi-clock me-1"></i> Sisa {{ $product->stock }} pcs</span>
                        @else
                            <span class="badge-stock-bali">Ready {{ $product->stock }} pcs</span>
                        @endif
                    </div>
                </div>

                <!-- Price Banner (Warm Linen Surface) -->
                <div class="p-4 rounded-4 mb-4 d-flex align-items-baseline gap-3 surface-sand">
                    <div>
                        <span class="small d-block text-muted" style="font-size: 0.72rem; letter-spacing: 0.08em; text-transform: uppercase;">Harga Khusus Butik</span>
                        <span class="display-6 fw-extrabold" style="color: var(--bali-espresso); font-weight: 800; font-size: 2rem;">
                            {{ \App\Support\FormatHelper::rupiah($product->selling_price) }}
                        </span>
                    </div>
                    <span class="text-muted text-decoration-line-through small ms-2">
                        {{ \App\Support\FormatHelper::rupiah($product->selling_price * 1.2) }}
                    </span>
                    <span class="badge rounded-pill px-3 py-1 small ms-auto" style="background-color: var(--bali-tan); color: #FFFFFF;">Pilihan Terbaik</span>
                </div>

                <!-- Add to Cart / Buy Form -->
                <form action="{{ route('cart.add', $product) }}" method="POST" class="mb-4">
                    @csrf
                    <!-- Size Selection -->
                    @php
                        $sizes = $product->size ? explode(',', $product->size) : ['All Size'];
                    @endphp
                    <div class="mb-3">
                        <label class="form-label-custom d-block">Pilihan Ukuran Modest:</label>
                        <div class="d-flex flex-wrap gap-2">
                            @foreach($sizes as $idx => $s)
                                <input type="radio" class="btn-check" name="size" id="size_{{ $idx }}" value="{{ trim($s) }}" {{ $idx == 0 ? 'checked' : '' }}>
                                <label class="btn btn-outline-brand btn-sm px-3 py-1" for="size_{{ $idx }}">{{ trim($s) }}</label>
                            @endforeach
                        </div>
                    </div>

                    <!-- Color Selection -->
                    @php
                        $colors = $product->color ? explode(',', $product->color) : ['Standar'];
                    @endphp
                    <div class="mb-4">
                        <label class="form-label-custom d-block">Pilihan Warna Natural:</label>
                        <div class="d-flex flex-wrap gap-2">
                            @foreach($colors as $cIdx => $c)
                                <input type="radio" class="btn-check" name="color" id="color_{{ $cIdx }}" value="{{ trim($c) }}" {{ $cIdx == 0 ? 'checked' : '' }}>
                                <label class="btn btn-outline-brand btn-sm px-3 py-1" for="color_{{ $cIdx }}">{{ trim($c) }}</label>
                            @endforeach
                        </div>
                    </div>

                    <!-- Quantity & Action Buttons -->
                    <div class="row g-3 align-items-center mb-4">
                        <div class="col-sm-4 col-6">
                            <label class="form-label-custom d-block">Kuantitas:</label>
                            <div class="input-group">
                                <button class="btn btn-sand" type="button" onclick="const q = document.getElementById('productQty'); if(q.value > 1) q.value--;">-</button>
                                <input type="number" name="quantity" id="productQty" class="form-control text-center" value="1" min="1" max="{{ $product->stock }}">
                                <button class="btn btn-sand" type="button" onclick="const q = document.getElementById('productQty'); if(q.value < {{ $product->stock }}) q.value++;">+</button>
                            </div>
                        </div>
                        <div class="col-sm-8 col-12 d-flex gap-2 align-items-end pt-sm-4">
                            @if($product->stock > 0)
                                <button type="submit" name="buy_now" value="0" class="btn btn-sand flex-fill py-2">
                                    <i class="bi bi-bag-plus me-1"></i> + Keranjang
                                </button>
                                <button type="submit" name="buy_now" value="1" class="btn btn-brand-primary flex-fill py-2">
                                    Beli Sekarang
                                </button>
                            @else
                                <button type="button" class="btn btn-sand w-100 py-2" disabled>
                                    Stok Habis (Sold Out)
                                </button>
                            @endif
                        </div>
                    </div>
                </form>

                <!-- Product Description & Fabric Spec Card -->
                <div class="rounded-4 p-4 surface-ivory mb-4">
                    <h6 class="fw-bold mb-3" style="font-family: var(--font-serif); font-size: 1.15rem; color: var(--bali-espresso);">
                        <i class="bi bi-feather me-2" style="color: var(--bali-tan);"></i> Rincian Busana &amp; Karakteristik Bahan
                    </h6>
                    <p class="text-muted small mb-3" style="line-height: 1.8;">
                        {{ $product->description ?: 'Busana modest Zahiyyah\'s Fit dirancang khusus dengan bahan serat alami bertekstur lembut, adem di kulit, dan tidak terawang sehingga sangat nyaman untuk aktivitas sehari-hari maupun acara resmi.' }}
                    </p>
                    <ul class="list-unstyled small text-secondary mb-0 d-flex flex-column gap-2">
                        <li><i class="bi bi-check2 me-2" style="color: var(--bali-tan);"></i> Jahitan halus &amp; rapi standar butik resort Seminyak</li>
                        <li><i class="bi bi-check2 me-2" style="color: var(--bali-tan);"></i> Material berpori adem, lembut, tidak mudah kusut, dan wudhu friendly</li>
                        <li><i class="bi bi-check2 me-2" style="color: var(--bali-tan);"></i> Garansi keaslian 100% dari Zahiyyah's Fit Official</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Related Products -->
    @if($relatedProducts->count() > 0)
    <div class="pt-5 border-top" style="border-color: var(--bali-border-light) !important;">
        <div class="d-flex justify-content-between align-items-end mb-4">
            <div>
                <span class="text-uppercase small fw-semibold" style="letter-spacing: 0.15em; color: var(--bali-tan); font-size: 0.75rem;">Complete The Look</span>
                <h4 class="mb-0" style="font-family: var(--font-serif); font-size: 1.8rem; color: var(--bali-espresso);">
                    Koleksi Terkait Dalam Kategori Ini
                </h4>
            </div>
            <a href="{{ route('shop.index', ['category' => $product->category->slug ?? '']) }}" class="small text-decoration-none" style="color: var(--bali-tan);">
                Lihat Semua &rarr;
            </a>
        </div>
        <div class="row g-4">
            @foreach($relatedProducts as $rel)
            <div class="col-xl-3 col-md-6">
                <div class="product-card-bali">
                    <div class="product-card-img-wrap">
                        <a href="{{ route('shop.products.show', $rel) }}">
                            <img src="{{ $rel->image_url ?? 'https://images.unsplash.com/photo-1515886657613-9f3515b0c78f?q=80&w=400&auto=format&fit=crop' }}" 
                                 alt="{{ $rel->name }}">
                        </a>
                    </div>
                    <div class="p-3 d-flex flex-column justify-content-between flex-grow-1">
                        <div>
                            <span class="product-tag-overline">{{ $rel->category->name ?? 'Outfit' }}</span>
                            <h6 class="product-title-serif">
                                <a href="{{ route('shop.products.show', $rel) }}">{{ $rel->name }}</a>
                            </h6>
                            <div class="product-price-bali mb-3">
                                {{ \App\Support\FormatHelper::rupiah($rel->selling_price) }}
                            </div>
                        </div>
                        <a href="{{ route('shop.products.show', $rel) }}" class="btn btn-sand w-100 py-1" style="font-size: 0.82rem;">
                            Lihat Pilihan
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>
@endsection
