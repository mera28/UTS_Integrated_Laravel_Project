@extends('layouts.shop')

@section('title', 'Keranjang Belanja - Zahiyyah\'s Fit Bali Boutique')

@section('content')
<div class="container py-4 py-md-5">
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
        <div>
            <span class="text-uppercase small fw-semibold" style="letter-spacing: 0.15em; color: var(--bali-tan); font-size: 0.72rem;">Shopping Bag</span>
            <h3 class="fw-bold mb-1" style="font-family: var(--font-serif); font-size: 2.1rem; color: var(--bali-espresso);">Keranjang Belanja</h3>
            <span class="text-muted small">Kelola busana outfit pilihan sebelum menyelesaikan pesanan</span>
        </div>
        <a href="{{ route('shop.index') }}" class="btn btn-sand btn-sm">
            <i class="bi bi-arrow-left me-1"></i> Lanjut Belanja Koleksi
        </a>
    </div>

    @if(!empty($cart) && count($cart) > 0)
    <div class="row g-4">
        <!-- Cart Items Table Column -->
        <div class="col-lg-8">
            <div class="card-bali overflow-hidden mb-3">
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead>
                            <tr>
                                <th class="ps-4">Koleksi Pakaian</th>
                                <th>Harga Butik</th>
                                <th class="text-center" style="width: 140px;">Kuantitas</th>
                                <th class="text-end">Subtotal</th>
                                <th class="text-center" style="width: 60px;">Hapus</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($cart as $key => $item)
                            <tr>
                                <td class="ps-4">
                                    <div class="d-flex align-items-center gap-3">
                                        <img src="{{ $item['image_url'] ?? 'https://images.unsplash.com/photo-1515886657613-9f3515b0c78f?q=80&w=200&auto=format&fit=crop' }}" 
                                             class="rounded-3 object-fit-cover" 
                                             style="width: 64px; height: 64px; border: 1px solid var(--bali-border);" 
                                             alt="{{ $item['name'] }}">
                                        <div>
                                            <h6 class="mb-1 fw-semibold" style="font-family: var(--font-serif); font-size: 1.05rem; color: var(--bali-espresso);">{{ $item['name'] }}</h6>
                                            <div class="small text-muted">
                                                <span class="badge" style="background-color: var(--bali-bg-linen); color: var(--bali-taupe); border: 1px solid var(--bali-border); font-size: 0.7rem;">{{ $item['size'] }}</span>
                                                <span class="badge" style="background-color: var(--bali-bg-linen); color: var(--bali-taupe); border: 1px solid var(--bali-border); font-size: 0.7rem;">{{ $item['color'] }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="fw-medium" style="color: var(--bali-taupe);">{{ \App\Support\FormatHelper::rupiah($item['price']) }}</td>
                                <td class="text-center">
                                    <form action="{{ route('cart.update') }}" method="POST" class="d-inline-flex align-items-center gap-1">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="cart_key" value="{{ $key }}">
                                        <input type="number" name="quantity" value="{{ $item['quantity'] }}" min="1" max="{{ $item['max_stock'] }}" class="form-control form-control-sm text-center" style="width: 65px; border-radius: 8px;" onchange="this.form.submit()">
                                    </form>
                                </td>
                                <td class="text-end fw-bold" style="color: var(--bali-espresso);">{{ \App\Support\FormatHelper::rupiah($item['subtotal']) }}</td>
                                <td class="text-center pe-3">
                                    <form action="{{ route('cart.remove', $key) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-link p-0" style="color: var(--bali-sand-tag);" title="Hapus Item">
                                            <i class="bi bi-trash fs-6"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="d-flex justify-content-between align-items-center">
                <form action="{{ route('cart.clear') }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-sand text-muted" onclick="return confirm('Kosongkan keranjang belanja?')">
                        <i class="bi bi-trash me-1"></i> Kosongkan Keranjang
                    </button>
                </form>
            </div>
        </div>

        <!-- Order Summary Column -->
        <div class="col-lg-4">
            <div class="card-bali p-4 surface-ivory">
                <h5 class="fw-bold mb-3" style="font-family: var(--font-serif); font-size: 1.3rem; color: var(--bali-espresso);">Ringkasan Pesanan</h5>
                <div class="d-flex justify-content-between mb-2 small text-muted">
                    <span>Total Kuantitas:</span>
                    <strong style="color: var(--bali-espresso);">{{ $totals['total_items'] }} pcs</strong>
                </div>
                <div class="d-flex justify-content-between mb-2 small text-muted">
                    <span>Estimasi Ongkir:</span>
                    <span class="badge" style="background-color: var(--bali-bg-linen); color: var(--bali-tan-dark); border: 1px solid var(--bali-border);">GRATIS ONGKIR</span>
                </div>
                <hr class="my-3" style="border-color: var(--bali-border);">
                <div class="d-flex justify-content-between align-items-baseline mb-4">
                    <span class="fw-bold" style="color: var(--bali-espresso);">Total Belanja:</span>
                    <span class="display-6 fw-extrabold" style="color: var(--bali-espresso); font-weight: 800; font-size: 1.8rem;">
                        {{ \App\Support\FormatHelper::rupiah($totals['grand_total']) }}
                    </span>
                </div>

                <div class="d-grid gap-2">
                    <a href="{{ route('checkout.index') }}" class="btn btn-brand-primary py-3 fs-6">
                        Lanjut ke Pembayaran <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                    <a href="{{ route('shop.index') }}" class="btn btn-sand py-2 small">
                        Tambah Busana Lainnya
                    </a>
                </div>
            </div>
        </div>
    </div>
    @else
    <!-- Empty Cart State -->
    <div class="card-bali p-5 text-center my-4 surface-ivory">
        <div class="mb-3">
            <div class="brand-badge-icon mx-auto" style="width: 80px; height: 80px; font-size: 2.2rem;">
                <i class="bi bi-bag"></i>
            </div>
        </div>
        <h4 class="fw-bold mb-2" style="font-family: var(--font-serif); font-size: 1.8rem; color: var(--bali-espresso);">Keranjang Belanja Anda Kosong</h4>
        <p class="text-muted small mb-4 max-w-sm mx-auto">
            Temukan koleksi busana modest dan island resort wear yang menyejukkan di butik Zahiyyah's Fit.
        </p>
        <div>
            <a href="{{ route('shop.index') }}" class="btn btn-brand-primary px-4 py-2">
                <i class="bi bi-compass me-1"></i> Mulai Jelajahi Koleksi
            </a>
        </div>
    </div>
    @endif
</div>
@endsection
