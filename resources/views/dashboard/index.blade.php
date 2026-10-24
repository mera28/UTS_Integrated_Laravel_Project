@extends('layouts.app')

@section('title', 'Dashboard Backoffice')
@section('page_title', 'Ringkasan & Dashboard Utama')

@section('content')
<div class="row g-4 mb-4">
    <!-- Stat 1: Total Produk -->
    <div class="col-xl-3 col-sm-6">
        <div class="card card-custom p-4 border-0 shadow-sm">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <span class="text-muted small fw-semibold text-uppercase">Total Produk Outfit</span>
                <div class="bg-primary bg-opacity-10 text-primary rounded-3 p-2">
                    <i class="bi bi-bag-check fs-5"></i>
                </div>
            </div>
            <h3 class="fw-bold mb-1 text-dark">{{ $totalProducts }}</h3>
            <span class="text-muted small"><i class="bi bi-layers me-1"></i> {{ $totalStock }} pcs total stok fisik</span>
        </div>
    </div>

    <!-- Stat 2: Total Penjualan -->
    <div class="col-xl-3 col-sm-6">
        <div class="card card-custom p-4 border-0 shadow-sm">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <span class="text-muted small fw-semibold text-uppercase">Pendapatan Penjualan</span>
                <div class="bg-success bg-opacity-10 text-success rounded-3 p-2">
                    <i class="bi bi-currency-dollar fs-5"></i>
                </div>
            </div>
            <h3 class="fw-bold mb-1 text-dark">Rp {{ number_format($totalSales, 0, ',', '.') }}</h3>
            <span class="text-success small fw-semibold"><i class="bi bi-check-circle me-1"></i> Transaksi Berhasil</span>
        </div>
    </div>

    <!-- Stat 3: Total Pembelian -->
    <div class="col-xl-3 col-sm-6">
        <div class="card card-custom p-4 border-0 shadow-sm">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <span class="text-muted small fw-semibold text-uppercase">Total Pengadaan (Stock-In)</span>
                <div class="bg-warning bg-opacity-10 text-warning rounded-3 p-2">
                    <i class="bi bi-truck fs-5"></i>
                </div>
            </div>
            <h3 class="fw-bold mb-1 text-dark">Rp {{ number_format($totalPurchases, 0, ',', '.') }}</h3>
            <span class="text-muted small"><i class="bi bi-box-seam me-1"></i> Pasokan Konveksi & Supplier</span>
        </div>
    </div>

    <!-- Stat 4: Low Stock Alert -->
    <div class="col-xl-3 col-sm-6">
        <div class="card card-custom p-4 border-0 shadow-sm {{ $lowStockProducts->count() > 0 ? 'border-danger border-opacity-50' : '' }}">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <span class="text-danger small fw-semibold text-uppercase">Peringatan Stok Menipis</span>
                <div class="bg-danger bg-opacity-10 text-danger rounded-3 p-2">
                    <i class="bi bi-exclamation-triangle-fill fs-5"></i>
                </div>
            </div>
            <h3 class="fw-bold mb-1 text-danger">{{ $lowStockProducts->count() }} <span class="fs-6 fw-normal text-muted">Item</span></h3>
            <span class="text-danger small fw-semibold"><i class="bi bi-bell-fill me-1"></i> Perlu Restok Segera</span>
        </div>
    </div>
</div>

<!-- Low Stock Alert Banner if items exist -->
@if($lowStockProducts->count() > 0)
<div class="card card-custom border-danger border-opacity-25 mb-4 shadow-sm bg-danger bg-opacity-10">
    <div class="card-body p-4">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
            <div class="d-flex align-items-center gap-2">
                <i class="bi bi-shield-exclamation text-danger fs-4"></i>
                <div>
                    <h6 class="fw-bold text-danger mb-0">Perhatian: Stok Produk di Bawah Ambang Minimum</h6>
                    <small class="text-muted">Item berikut memerlukan pengadaan (Stock-In) baru agar tidak kehabisan stok penjualan.</small>
                </div>
            </div>
            <a href="{{ url('/purchases') }}" class="btn btn-sm btn-danger rounded-pill px-3">
                <i class="bi bi-plus-circle me-1"></i> Buat Pengadaan Baru
            </a>
        </div>
        <div class="table-responsive bg-white rounded-3 border">
            <table class="table table-hover align-middle mb-0 small">
                <thead class="table-light">
                    <tr>
                        <th>SKU</th>
                        <th>Nama Produk</th>
                        <th>Kategori</th>
                        <th class="text-center">Sisa Stok</th>
                        <th class="text-center">Batas Minimum</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($lowStockProducts as $low)
                    <tr>
                        <td class="fw-semibold">{{ $low->sku }}</td>
                        <td>{{ $low->name }}</td>
                        <td><span class="badge bg-light text-dark border">{{ $low->category->name ?? '-' }}</span></td>
                        <td class="text-center"><span class="badge bg-danger rounded-pill px-2">{{ $low->stock }} pcs</span></td>
                        <td class="text-center text-muted">{{ $low->minimum_stock }} pcs</td>
                        <td><span class="badge bg-warning text-dark"><i class="bi bi-exclamation-circle me-1"></i> LOW STOCK</span></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endif

<div class="row g-4">
    <!-- Recent Sales -->
    <div class="col-lg-6">
        <div class="card card-custom p-4 border-0 shadow-sm h-100">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <h6 class="fw-bold mb-0 text-dark"><i class="bi bi-receipt me-2 text-primary"></i> Transaksi Penjualan Terakhir</h6>
                <a href="{{ url('/sales') }}" class="small text-decoration-none text-primary fw-semibold">Lihat Semua &rarr;</a>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 small">
                    <thead class="table-light">
                        <tr>
                            <th>No. Nota</th>
                            <th>Pelanggan</th>
                            <th>Total</th>
                            <th>Metode</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentSales as $sale)
                        <tr>
                            <td class="fw-semibold text-primary">{{ $sale->sale_number }}</td>
                            <td>{{ $sale->customer_name ?? $sale->user->name ?? 'Pelanggan' }}</td>
                            <td class="fw-bold">Rp {{ number_format($sale->total, 0, ',', '.') }}</td>
                            <td><span class="badge bg-light text-secondary border text-uppercase">{{ str_replace('_', ' ', $sale->payment_method ?? 'cash') }}</span></td>
                            <td>
                                @if($sale->payment_status === 'paid')
                                    <span class="badge bg-success bg-opacity-10 text-success border border-success">Lunas</span>
                                @else
                                    <span class="badge bg-warning bg-opacity-10 text-warning border border-warning">Pending</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-3">Belum ada transaksi penjualan.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Recent Purchases -->
    <div class="col-lg-6">
        <div class="card card-custom p-4 border-0 shadow-sm h-100">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <h6 class="fw-bold mb-0 text-dark"><i class="bi bi-box-arrow-in-down me-2 text-warning"></i> Pengadaan Terakhir (Stock-In)</h6>
                <a href="{{ url('/purchases') }}" class="small text-decoration-none text-warning fw-semibold">Lihat Semua &rarr;</a>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 small">
                    <thead class="table-light">
                        <tr>
                            <th>No. PO</th>
                            <th>Supplier</th>
                            <th>Total Biaya</th>
                            <th>Tanggal</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentPurchases as $purchase)
                        <tr>
                            <td class="fw-semibold text-dark">{{ $purchase->purchase_number }}</td>
                            <td>{{ $purchase->supplier->company_name ?? $purchase->supplier->name ?? '-' }}</td>
                            <td class="fw-bold">Rp {{ number_format($purchase->total, 0, ',', '.') }}</td>
                            <td class="text-muted">{{ $purchase->purchased_at->format('d M Y') }}</td>
                            <td><span class="badge bg-success">Selesai</span></td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-3">Belum ada transaksi pembelian stok.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
