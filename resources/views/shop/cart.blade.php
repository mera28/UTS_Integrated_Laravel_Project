@extends('layouts.shop')

@section('title', 'Keranjang Belanja')

@section('content')
<div class="container py-4 py-md-5">
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
        <div>
            <h4 class="fw-bold text-dark mb-1">Keranjang Belanja</h4>
            <span class="text-muted small">Kelola busana outfit pilihan sebelum melakukan pembayaran</span>
        </div>
        <a href="{{ route('shop.index') }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
            <i class="bi bi-arrow-left me-1"></i> Lanjut Belanja
        </a>
    </div>

    @if(!empty($cart) && count($cart) > 0)
    <div class="row g-4">
        <!-- Cart Items Table Column -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 bg-white overflow-hidden mb-3">
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead class="table-light small">
                            <tr>
                                <th class="ps-4">Produk Pakaian</th>
                                <th>Harga</th>
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
                                             class="rounded-3 border object-fit-cover" 
                                             style="width: 60px; height: 60px;" 
                                             alt="{{ $item['name'] }}">
                                        <div>
                                            <h6 class="fw-bold mb-1 text-dark">{{ $item['name'] }}</h6>
                                            <div class="small text-muted">
                                                <span class="badge bg-light text-secondary border me-1">{{ $item['size'] }}</span>
                                                <span class="badge bg-light text-secondary border">{{ $item['color'] }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="fw-semibold text-dark">{{ \App\Support\FormatHelper::rupiah($item['price']) }}</td>
                                <td class="text-center">
                                    <form action="{{ route('cart.update') }}" method="POST" class="d-inline-flex align-items-center gap-1">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="cart_key" value="{{ $key }}">
                                        <input type="number" name="quantity" value="{{ $item['quantity'] }}" min="1" max="{{ $item['max_stock'] }}" class="form-control form-control-sm text-center" style="width: 65px;" onchange="this.form.submit()">
                                    </form>
                                </td>
                                <td class="text-end fw-bold text-danger">{{ \App\Support\FormatHelper::rupiah($item['subtotal']) }}</td>
                                <td class="text-center pe-3">
                                    <form action="{{ route('cart.remove', $key) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-link text-danger p-0" title="Hapus Item">
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
                    <button type="submit" class="btn btn-sm btn-light border text-danger" onclick="return confirm('Kosongkan keranjang belanja?')">
                        <i class="bi bi-trash me-1"></i> Kosongkan Keranjang
                    </button>
                </form>
            </div>
        </div>

        <!-- Order Summary Column -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 bg-white p-4">
                <h5 class="fw-bold text-dark mb-3">Ringkasan Pesanan</h5>
                <div class="d-flex justify-content-between mb-2 small text-muted">
                    <span>Total Kuantitas:</span>
                    <strong class="text-dark">{{ $totals['total_items'] }} pcs</strong>
                </div>
                <div class="d-flex justify-content-between mb-2 small text-muted">
                    <span>Estimasi Ongkir:</span>
                    <span class="badge bg-success bg-opacity-10 text-success border border-success">GRATIS ONGKIR</span>
                </div>
                <hr class="my-3">
                <div class="d-flex justify-content-between align-items-baseline mb-4">
                    <span class="fw-bold text-dark">Total Belanja:</span>
                    <span class="display-6 fw-extrabold text-danger fs-4" style="font-weight: 800;">
                        {{ \App\Support\FormatHelper::rupiah($totals['grand_total']) }}
                    </span>
                </div>

                <div class="d-grid gap-2">
                    <a href="{{ route('checkout.index') }}" class="btn btn-brand-primary py-3 rounded-pill fw-bold fs-6">
                        Lanjut ke Pembayaran <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                    <a href="{{ route('shop.index') }}" class="btn btn-light border py-2 rounded-pill small text-secondary">
                        Tambah Pakaian Lainnya
                    </a>
                </div>
            </div>
        </div>
    </div>
    @else
    <!-- Empty Cart State -->
    <div class="card border-0 shadow-sm rounded-4 p-5 text-center bg-white my-4">
        <div class="mb-3">
            <div class="bg-danger bg-opacity-10 text-danger rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 90px; height: 90px; font-size: 2.5rem;">
                <i class="bi bi-bag-x"></i>
            </div>
        </div>
        <h4 class="fw-bold text-dark mb-2">Keranjang Belanja Anda Kosong</h4>
        <p class="text-muted small mb-4 max-w-sm mx-auto">
            Yuk, jelajahi koleksi busana muslimah dan modest fashion terkini di butik Zahiyyah's Fit!
        </p>
        <div>
            <a href="{{ route('shop.index') }}" class="btn btn-brand-primary px-4 py-2 rounded-pill fw-semibold">
                <i class="bi bi-arrow-left me-1"></i> Mulai Belanja Sekarang
            </a>
        </div>
    </div>
    @endif
</div>
@endsection
