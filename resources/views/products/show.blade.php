@extends('layouts.app')

@section('title', 'Detail Produk Outfit')
@section('page_title', 'Detail Produk & Analisis Margin')

@section('content')
<div class="row g-4 justify-content-center">
    <div class="col-lg-10">
        <div class="card card-master mb-4">
            <div class="card-header-master d-flex align-items-center justify-content-between">
                <div>
                    <h5 class="fw-bold mb-0 text-dark">Informasi Produk: {{ $product->name }}</h5>
                    <span class="text-muted small">SKU: <code>{{ $product->sku }}</code></span>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('products.index') }}" class="btn btn-sm btn-outline-secondary">
                        <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar
                    </a>
                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('products.edit', $product) }}" class="btn btn-sm btn-brand-primary">
                            <i class="bi bi-pencil me-1"></i> Ubah Data
                        </a>
                    @endif
                </div>
            </div>

            <div class="card-body-master">
                <div class="row g-4 align-items-start">
                    <!-- Photo Column -->
                    <div class="col-md-4 text-center">
                        <div class="p-2 border rounded-4 bg-light mb-3">
                            <img src="{{ $product->image_url ?? 'https://images.unsplash.com/photo-1515886657613-9f3515b0c78f?q=80&w=600&auto=format&fit=crop' }}" 
                                 alt="{{ $product->name }}" 
                                 class="img-fluid rounded-3 w-100 object-fit-cover" 
                                 style="max-height: 340px;"
                                 onerror="this.src='https://images.unsplash.com/photo-1515886657613-9f3515b0c78f?q=80&w=600&auto=format&fit=crop'">
                        </div>
                        <div class="text-center">
                            {!! \App\Support\FormatHelper::stockBadge($product->stock, $product->minimum_stock) !!}
                        </div>
                    </div>

                    <!-- Details Column -->
                    <div class="col-md-8">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <span class="badge bg-secondary">{{ $product->category->name ?? 'Kategori' }}</span>
                            @if($product->isLowStock())
                                <span class="badge bg-warning text-dark"><i class="bi bi-exclamation-triangle"></i> LOW STOCK ALERT</span>
                            @endif
                        </div>

                        <h3 class="fw-bold text-dark mb-2">{{ $product->name }}</h3>
                        <p class="text-muted small mb-4" style="line-height: 1.6;">
                            {{ $product->description ?: 'Belum ada rincian deskripsi bahan pakaian untuk produk ini.' }}
                        </p>

                        <!-- Specifications -->
                        <div class="row g-3 mb-4">
                            <div class="col-sm-6">
                                <div class="p-3 bg-light rounded-3 border">
                                    <span class="text-muted small d-block mb-1">Varian Ukuran (Size)</span>
                                    <strong class="text-dark">{{ $product->size ?: 'All Size' }}</strong>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="p-3 bg-light rounded-3 border">
                                    <span class="text-muted small d-block mb-1">Pilihan Warna</span>
                                    <strong class="text-dark">{{ $product->color ?: 'Standar' }}</strong>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="p-3 bg-light rounded-3 border">
                                    <span class="text-muted small d-block mb-1">Sisa Stok Fisik</span>
                                    <strong class="text-dark fs-5">{{ $product->stock }} pcs</strong>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="p-3 bg-light rounded-3 border">
                                    <span class="text-muted small d-block mb-1">Batas Minimum Restok</span>
                                    <strong class="text-dark fs-5">{{ $product->minimum_stock }} pcs</strong>
                                </div>
                            </div>
                        </div>

                        <!-- Financial & Margin Card -->
                        <div class="card border border-primary border-opacity-25 rounded-3 bg-primary bg-opacity-10 p-3">
                            <h6 class="fw-bold text-primary mb-3"><i class="bi bi-graph-up-arrow me-1"></i> Analisis Finansial & Profit Margin</h6>
                            <div class="row g-3 text-center">
                                <div class="col-sm-3 col-6">
                                    <span class="text-muted small d-block">Harga Beli HPP</span>
                                    <strong class="text-dark">{{ \App\Support\FormatHelper::rupiah($product->purchase_price) }}</strong>
                                </div>
                                <div class="col-sm-3 col-6">
                                    <span class="text-muted small d-block">Harga Jual</span>
                                    <strong class="text-dark">{{ \App\Support\FormatHelper::rupiah($product->selling_price) }}</strong>
                                </div>
                                <div class="col-sm-3 col-6">
                                    <span class="text-muted small d-block">Laba Per Pcs</span>
                                    <strong class="text-success">+{{ \App\Support\FormatHelper::rupiah($metrics['margin']['profit']) }}</strong>
                                </div>
                                <div class="col-sm-3 col-6">
                                    <span class="text-muted small d-block">Margin Laba</span>
                                    <span class="badge bg-success rounded-pill px-3 py-1 fs-6">{{ $metrics['margin']['percentage'] }}%</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
