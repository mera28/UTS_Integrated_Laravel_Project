@extends('layouts.app')

@section('title', 'Peringatan Stok Rendah')
@section('page_title', 'Monitoring & Peringatan Stok Rendah (Low-Stock Alerts)')

@section('content')
<!-- Header & Actions -->
<div class="row align-items-center mb-4">
    <div class="col-md-7">
        <p class="text-muted mb-0">
            Daftar produk outfit yang stok fisiknya telah mencapai atau berada di bawah batas minimum pengamanan stok (Slide 13 PPT). Segera lakukan pengadaan stok ke supplier konveksi.
        </p>
    </div>
    <div class="col-md-5 text-md-end mt-3 mt-md-0 d-flex justify-content-md-end gap-2">
        <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary rounded-pill px-3">
            <i class="bi bi-arrow-left me-1"></i> Dashboard
        </a>
        <a href="{{ route('purchases.create') }}" class="btn btn-primary rounded-pill px-4 shadow-sm">
            <i class="bi bi-truck me-1"></i> + Buat Pembelian Stok (Restok)
        </a>
    </div>
</div>

<!-- Low Stock Summary Cards -->
@php
    $outOfStockCount = $lowStockProducts->where('stock', '<=', 0)->count();
    $warningStockCount = $lowStockProducts->where('stock', '>', 0)->count();
@endphp
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card card-custom p-3 border-0 bg-danger bg-opacity-10">
            <div class="d-flex align-items-center gap-3">
                <div class="bg-danger text-white rounded-circle p-3">
                    <i class="bi bi-x-circle-fill fs-4"></i>
                </div>
                <div>
                    <span class="text-danger small fw-bold text-uppercase">Stok Habis Total (0 pcs)</span>
                    <h3 class="fw-bold mb-0 text-danger">{{ $outOfStockCount }} <span class="fs-6 fw-normal">Item</span></h3>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card card-custom p-3 border-0 bg-warning bg-opacity-10">
            <div class="d-flex align-items-center gap-3">
                <div class="bg-warning text-dark rounded-circle p-3">
                    <i class="bi bi-exclamation-triangle-fill fs-4"></i>
                </div>
                <div>
                    <span class="text-warning text-dark small fw-bold text-uppercase">Stok Kritis / Menipis</span>
                    <h3 class="fw-bold mb-0 text-dark">{{ $warningStockCount }} <span class="fs-6 fw-normal">Item</span></h3>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card card-custom p-3 border-0 bg-light">
            <div class="d-flex align-items-center gap-3">
                <div class="bg-white text-primary rounded-circle p-3 shadow-sm">
                    <i class="bi bi-bell-fill fs-4"></i>
                </div>
                <div>
                    <span class="text-muted small fw-bold text-uppercase">Total Butuh Restok</span>
                    <h3 class="fw-bold mb-0 text-dark">{{ $lowStockProducts->count() }} <span class="fs-6 fw-normal">Produk</span></h3>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Low Stock Table -->
<div class="card card-custom p-4 border-0 shadow-sm">
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h5 class="fw-bold text-dark mb-0">Tabel Produk Dibawah Stok Minimum</h5>
        <span class="badge bg-danger rounded-pill px-3 py-2">
            <i class="bi bi-exclamation-circle me-1"></i> Prioritas Pengadaan
        </span>
    </div>

    @if($lowStockProducts->isEmpty())
        <div class="text-center py-5">
            <div class="bg-success bg-opacity-10 text-success rounded-circle d-inline-flex p-4 mb-3">
                <i class="bi bi-check2-circle fs-1"></i>
            </div>
            <h4 class="fw-bold text-success">Seluruh Inventori Dalam Kondisi Aman!</h4>
            <p class="text-muted">Tidak ada satupun produk outfit Zahiyyah's Fit yang berada di bawah kuota minimum stok saat ini.</p>
            <a href="{{ route('products.index') }}" class="btn btn-outline-primary rounded-pill px-4">
                Lihat Master Produk
            </a>
        </div>
    @else
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light text-uppercase small">
                    <tr>
                        <th>#</th>
                        <th>Foto & Produk Outfit</th>
                        <th>Kategori</th>
                        <th class="text-center">Stok Fisik</th>
                        <th class="text-center">Batas Minimum</th>
                        <th class="text-center">Status</th>
                        <th class="text-end">Harga Beli</th>
                        <th class="text-center">Tindakan Restok</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($lowStockProducts as $index => $product)
                        @php
                            $isOut = $product->stock <= 0;
                        @endphp
                        <tr class="{{ $isOut ? 'table-danger bg-opacity-25' : '' }}">
                            <td class="text-muted">{{ $index + 1 }}</td>
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    @if($product->image_url)
                                        <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="rounded-3 object-fit-cover" style="width: 44px; height: 44px;">
                                    @else
                                        <div class="bg-light rounded-3 d-flex align-items-center justify-content-center text-muted" style="width: 44px; height: 44px;">
                                            <i class="bi bi-image"></i>
                                        </div>
                                    @endif
                                    <div>
                                        <div class="fw-bold text-dark">{{ $product->name }}</div>
                                        <div class="small text-muted font-monospace">{{ $product->sku }}</div>
                                        @if($product->size || $product->color)
                                            <div class="text-muted" style="font-size: 0.72rem;">
                                                {{ $product->size ?? '-' }} &bull; {{ $product->color ?? '-' }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark border">{{ $product->category->name ?? '-' }}</span>
                            </td>
                            <td class="text-center">
                                <span class="fs-5 fw-extrabold {{ $isOut ? 'text-danger' : 'text-warning text-dark' }}">
                                    {{ $product->stock }}
                                </span>
                                <span class="small text-muted d-block">pcs</span>
                            </td>
                            <td class="text-center">
                                <span class="fw-bold text-dark">{{ $product->minimum_stock }}</span>
                                <span class="small text-muted d-block">pcs</span>
                            </td>
                            <td class="text-center">
                                @if($isOut)
                                    <span class="badge bg-danger rounded-pill px-3 py-1">
                                        <i class="bi bi-x-octagon-fill me-1"></i> Habis
                                    </span>
                                @else
                                    <span class="badge bg-warning text-dark rounded-pill px-3 py-1">
                                        <i class="bi bi-exclamation-triangle-fill me-1"></i> Menipis
                                    </span>
                                @endif
                            </td>
                            <td class="text-end fw-semibold text-dark">
                                {{ \App\Support\FormatHelper::rupiah($product->purchase_price) }}
                            </td>
                            <td class="text-center">
                                <a href="{{ route('purchases.create') }}" class="btn btn-primary btn-sm rounded-pill px-3 shadow-sm">
                                    <i class="bi bi-plus-circle me-1"></i> Pesan ke Supplier
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
