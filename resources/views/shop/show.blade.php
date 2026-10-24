@extends('layouts.shop')

@section('title', $product->name . ' - Zahiyyah\'s Fit')

@section('content')
<div class="container py-4 py-md-5">
    <!-- Breadcrumb -->
    <nav class="mb-4 small" aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('shop.index') }}" class="text-decoration-none text-muted">Katalog</a></li>
            <li class="breadcrumb-item">
                <a href="{{ route('shop.index', ['category' => $product->category->slug ?? '']) }}" class="text-decoration-none text-muted">
                    {{ $product->category->name ?? 'Kategori' }}
                </a>
            </li>
            <li class="breadcrumb-item active text-dark fw-semibold" aria-current="page">{{ $product->name }}</li>
        </ol>
    </nav>

    <div class="row g-4 g-lg-5 mb-5">
        <!-- Photo Gallery Column -->
        <div class="col-lg-5 text-center">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white p-2">
                <img src="{{ $product->image_url ?? 'https://images.unsplash.com/photo-1515886657613-9f3515b0c78f?q=80&w=800&auto=format&fit=crop' }}" 
                     alt="{{ $product->name }}" 
                     class="img-fluid rounded-4 w-100 object-fit-cover" 
                     style="max-height: 480px;"
                     onerror="this.src='https://images.unsplash.com/photo-1515886657613-9f3515b0c78f?q=80&w=800&auto=format&fit=crop'">
            </div>
        </div>

        <!-- Product Details Column -->
        <div class="col-lg-7">
            <div class="ps-lg-2">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <span class="badge bg-danger bg-opacity-10 text-danger border border-danger small">Official Store</span>
                    <span class="badge bg-light text-secondary border small">{{ $product->category->name ?? 'Outfit' }}</span>
                    <span class="text-muted small ms-auto">SKU: <code>{{ $product->sku }}</code></span>
                </div>

                <h2 class="fw-bold text-dark mb-3">{{ $product->name }}</h2>

                <!-- Rating & Sold Summary -->
                <div class="d-flex align-items-center gap-3 mb-3 small">
                    <div class="text-warning d-flex align-items-center gap-1">
                        <i class="bi bi-star-fill"></i>
                        <span class="fw-bold text-dark">4.9</span>
                        <span class="text-muted">(128 Ulasan)</span>
                    </div>
                    <span class="text-muted">&bull;</span>
                    <div class="text-muted">Terjual 250+ pcs</div>
                    <span class="text-muted">&bull;</span>
                    <div>
                        @if($product->stock <= 0)
                            <span class="badge bg-danger rounded-pill">Stok Habis</span>
                        @elseif($product->isLowStock())
                            <span class="badge bg-warning text-dark rounded-pill"><i class="bi bi-clock-history me-1"></i> Sisa {{ $product->stock }} pcs</span>
                        @else
                            <span class="badge bg-success bg-opacity-10 text-success border border-success rounded-pill">Ready {{ $product->stock }} pcs</span>
                        @endif
                    </div>
                </div>

                <!-- Price Banner -->
                <div class="p-3 bg-light rounded-4 mb-4 d-flex align-items-baseline gap-3">
                    <span class="display-6 fw-extrabold text-danger" style="font-weight: 800;">
                        {{ \App\Support\FormatHelper::rupiah($product->selling_price) }}
                    </span>
                    <span class="text-muted text-decoration-line-through small">
                        {{ \App\Support\FormatHelper::rupiah($product->selling_price * 1.25) }}
                    </span>
                    <span class="badge bg-danger text-white rounded-pill px-2 py-1 small">Diskon 20%</span>
                </div>

                <!-- Add to Cart / Buy Form -->
                <form action="{{ route('cart.add', $product) }}" method="POST" class="mb-4">
                    @csrf
                    <!-- Size Selection -->
                    @php
                        $sizes = $product->size ? explode(',', $product->size) : ['All Size'];
                    @endphp
                    <div class="mb-3">
                        <label class="form-label-custom d-block">Pilihan Ukuran:</label>
                        <div class="d-flex flex-wrap gap-2">
                            @foreach($sizes as $idx => $s)
                                <input type="radio" class="btn-check" name="size" id="size_{{ $idx }}" value="{{ trim($s) }}" {{ $idx == 0 ? 'checked' : '' }}>
                                <label class="btn btn-outline-dark btn-sm rounded-pill px-3 py-1" for="size_{{ $idx }}">{{ trim($s) }}</label>
                            @endforeach
                        </div>
                    </div>

                    <!-- Color Selection -->
                    @php
                        $colors = $product->color ? explode(',', $product->color) : ['Standar'];
                    @endphp
                    <div class="mb-4">
                        <label class="form-label-custom d-block">Pilihan Warna:</label>
                        <div class="d-flex flex-wrap gap-2">
                            @foreach($colors as $cIdx => $c)
                                <input type="radio" class="btn-check" name="color" id="color_{{ $cIdx }}" value="{{ trim($c) }}" {{ $cIdx == 0 ? 'checked' : '' }}>
                                <label class="btn btn-outline-secondary btn-sm rounded-pill px-3 py-1" for="color_{{ $cIdx }}">{{ trim($c) }}</label>
                            @endforeach
                        </div>
                    </div>

                    <!-- Quantity & Action Buttons -->
                    <div class="row g-3 align-items-center mb-4">
                        <div class="col-sm-4 col-6">
                            <label class="form-label-custom d-block">Kuantitas:</label>
                            <div class="input-group">
                                <button class="btn btn-light border" type="button" onclick="const q = document.getElementById('productQty'); if(q.value > 1) q.value--;">-</button>
                                <input type="number" name="quantity" id="productQty" class="form-control text-center" value="1" min="1" max="{{ $product->stock }}">
                                <button class="btn btn-light border" type="button" onclick="const q = document.getElementById('productQty'); if(q.value < {{ $product->stock }}) q.value++;">+</button>
                            </div>
                        </div>
                        <div class="col-sm-8 col-12 d-flex gap-2 align-items-end pt-sm-4">
                            @if($product->stock > 0)
                                <button type="submit" name="buy_now" value="0" class="btn btn-outline-danger flex-fill py-2 rounded-pill fw-semibold">
                                    <i class="bi bi-cart-plus me-1"></i> + Keranjang
                                </button>
                                <button type="submit" name="buy_now" value="1" class="btn btn-brand-primary flex-fill py-2 rounded-pill fw-bold">
                                    Beli Sekarang
                                </button>
                            @else
                                <button type="button" class="btn btn-secondary w-100 py-2 rounded-pill" disabled>
                                    Stok Habis (Sold Out)
                                </button>
                            @endif
                        </div>
                    </div>
                </form>

                <!-- Product Description Accordion -->
                <div class="border rounded-4 p-4 bg-white mb-4">
                    <h6 class="fw-bold text-dark mb-3"><i class="bi bi-info-circle me-1 text-primary"></i> Rincian Produk & Deskripsi Bahan</h6>
                    <p class="text-muted small mb-3" style="line-height: 1.8;">
                        {{ $product->description ?: 'Busana modest Zahiyyah\'s Fit dirancang khusus dengan bahan berkualitas tinggi, bertekstur lembut, adem, dan tidak terawang sehingga sangat nyaman untuk aktivitas sehari-hari maupun acara resmi.' }}
                    </p>
                    <ul class="list-unstyled small text-secondary mb-0 d-flex flex-column gap-2">
                        <li><i class="bi bi-check2 text-success me-2"></i> Jahitan rapi standar butik premium</li>
                        <li><i class="bi bi-check2 text-success me-2"></i> Material adem, lembut di kulit, dan wudhu friendly</li>
                        <li><i class="bi bi-check2 text-success me-2"></i> Garansi originalitas 100% dari Zahiyyah's Fit</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Related Products -->
    @if($relatedProducts->count() > 0)
    <div class="pt-4 border-top">
        <h5 class="fw-bold text-dark mb-4">Produk Terkait Dalam Kategori Ini</h5>
        <div class="row g-4">
            @foreach($relatedProducts as $rel)
            <div class="col-xl-3 col-md-6">
                <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden bg-white">
                    <a href="{{ route('shop.products.show', $rel) }}">
                        <img src="{{ $rel->image_url ?? 'https://images.unsplash.com/photo-1515886657613-9f3515b0c78f?q=80&w=400&auto=format&fit=crop' }}" 
                             class="w-100 object-fit-cover" style="height: 220px;" alt="{{ $rel->name }}">
                    </a>
                    <div class="card-body p-3">
                        <h6 class="fw-bold text-dark text-truncate mb-1">
                            <a href="{{ route('shop.products.show', $rel) }}" class="text-decoration-none text-dark">{{ $rel->name }}</a>
                        </h6>
                        <div class="fw-extrabold text-danger mb-2">
                            {{ \App\Support\FormatHelper::rupiah($rel->selling_price) }}
                        </div>
                        <a href="{{ route('shop.products.show', $rel) }}" class="btn btn-sm btn-outline-danger w-100 rounded-pill">
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
