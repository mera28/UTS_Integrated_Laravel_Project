@extends('layouts.app')

@section('title', 'Dashboard Backoffice')
@section('page_title', 'Ringkasan & Dashboard Utama')

@section('content')
<!-- Row 1: Exactly 4 Minimalist KPI Cards -->
<div class="row g-4 mb-4">
    <!-- KPI 1: Total Produk Outfit -->
    <div class="col-xl-3 col-sm-6">
        <div class="card card-clean p-4 h-100">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="text-muted small fw-semibold text-uppercase" style="letter-spacing: 0.5px;">Total Produk Outfit</span>
                <div class="bg-secondary bg-opacity-10 text-secondary rounded-3 p-2" style="width: 36px; height: 36px; display: flex; align-items: center; justify-content: center;">
                    <i class="bi bi-bag"></i>
                </div>
            </div>
            <h3 class="fw-bold mb-1 text-dark">{{ $metrics['total_products'] }}</h3>
            <span class="text-muted small">
                <i class="bi bi-box-seam me-1"></i> {{ number_format($metrics['total_stock'], 0, ',', '.') }} pcs stok fisik
            </span>
        </div>
    </div>

    <!-- KPI 2: Penjualan Hari Ini -->
    <div class="col-xl-3 col-sm-6">
        <div class="card card-clean p-4 h-100">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="text-muted small fw-semibold text-uppercase" style="letter-spacing: 0.5px;">Penjualan Hari Ini</span>
                <div class="bg-success bg-opacity-10 text-success rounded-3 p-2" style="width: 36px; height: 36px; display: flex; align-items: center; justify-content: center;">
                    <i class="bi bi-receipt"></i>
                </div>
            </div>
            <h3 class="fw-bold mb-1 text-dark">{{ \App\Support\FormatHelper::rupiah($metrics['today_sales']) }}</h3>
            <span class="text-muted small">
                <i class="bi bi-check2-circle me-1 text-success"></i> {{ $metrics['today_sales_count'] }} transaksi selesai
            </span>
        </div>
    </div>

    <!-- KPI 3: Penjualan Bulan Ini -->
    <div class="col-xl-3 col-sm-6">
        <div class="card card-clean p-4 h-100">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="text-muted small fw-semibold text-uppercase" style="letter-spacing: 0.5px;">Penjualan Bulan Ini</span>
                <div class="bg-primary bg-opacity-10 text-primary rounded-3 p-2" style="width: 36px; height: 36px; display: flex; align-items: center; justify-content: center;">
                    <i class="bi bi-graph-up"></i>
                </div>
            </div>
            <h3 class="fw-bold mb-1 text-dark">{{ \App\Support\FormatHelper::rupiah($metrics['month_sales']) }}</h3>
            <span class="text-muted small">
                {{ $metrics['month_sales_count'] }} nota &bull; Belanja: {{ \App\Support\FormatHelper::rupiahShort($metrics['month_purchases']) }}
            </span>
        </div>
    </div>

    <!-- KPI 4: Stok Rendah -->
    <div class="col-xl-3 col-sm-6">
        <div class="card card-clean p-4 h-100 {{ $metrics['low_stock_count'] > 0 ? 'border-warning' : '' }}">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="text-muted small fw-semibold text-uppercase" style="letter-spacing: 0.5px;">Peringatan Stok Rendah</span>
                <div class="bg-warning bg-opacity-15 text-warning rounded-3 p-2" style="width: 36px; height: 36px; display: flex; align-items: center; justify-content: center;">
                    <i class="bi bi-exclamation-triangle"></i>
                </div>
            </div>
            <h3 class="fw-bold mb-1 {{ $metrics['low_stock_count'] > 0 ? 'text-danger' : 'text-dark' }}">
                {{ $metrics['low_stock_count'] }} <span class="fs-6 fw-normal text-muted">Item</span>
            </h3>
            <div class="d-flex align-items-center justify-content-between small">
                <span class="text-muted">Perlu restok pasokan</span>
                <a href="{{ route('dashboard.low-stock') }}" class="text-decoration-none fw-semibold" style="color: var(--brand-primary); font-size: 0.8rem;">
                    Detail &rarr;
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Row 2: 2 Clean Columns (Left: Transactions, Right: Alerts & Top Products) -->
<div class="row g-4">
    <!-- Left Column: Recent Transactions -->
    <div class="col-lg-7">
        <!-- Sales Transactions -->
        <div class="card card-clean mb-4">
            <div class="card-header-clean d-flex align-items-center justify-content-between">
                <div>
                    <h6 class="fw-bold mb-0 text-dark">Transaksi Penjualan Terbaru</h6>
                    <span class="text-muted small">Aktivitas kasir POS & order customer</span>
                </div>
                <a href="{{ route('sales.index') }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3" style="font-size: 0.78rem;">
                    Lihat Semua
                </a>
            </div>
            <div class="table-responsive">
                <table class="table align-middle w-100">
                    <thead>
                        <tr>
                            <th>No. Nota</th>
                            <th>Pelanggan</th>
                            <th>Status</th>
                            <th class="text-end">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentTransactions['recent_sales'] as $sale)
                            <tr>
                                <td>
                                    <a href="{{ route('sales.show', $sale) }}" class="fw-semibold text-decoration-none font-monospace small" style="color: var(--brand-primary);">
                                        {{ $sale->sale_number }}
                                    </a>
                                    <div class="text-muted" style="font-size: 0.72rem;">{{ $sale->sold_at->format('d/m/Y H:i') }}</div>
                                </td>
                                <td>
                                    <div class="fw-medium text-dark small">{{ $sale->customer_name }}</div>
                                    <span class="text-muted text-uppercase" style="font-size: 0.7rem;">{{ $sale->payment_method ?? 'CASH' }}</span>
                                </td>
                                <td>
                                    @if($sale->status === 'completed')
                                        <span class="badge bg-success bg-opacity-10 text-success rounded-pill" style="font-size: 0.72rem;">Selesai</span>
                                    @elseif($sale->status === 'pending')
                                        <span class="badge bg-warning bg-opacity-10 text-warning rounded-pill" style="font-size: 0.72rem;">Pending</span>
                                    @else
                                        <span class="badge bg-secondary rounded-pill" style="font-size: 0.72rem;">Batal</span>
                                    @endif
                                </td>
                                <td class="text-end fw-semibold text-dark small">
                                    {{ \App\Support\FormatHelper::rupiah($sale->total) }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-4 text-muted small">Belum ada transaksi penjualan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Purchase Transactions -->
        <div class="card card-clean">
            <div class="card-header-clean d-flex align-items-center justify-content-between">
                <div>
                    <h6 class="fw-bold mb-0 text-dark">Pengadaan Stok Terbaru (Stock-In)</h6>
                    <span class="text-muted small">Pasokan kain dan konveksi pakaian</span>
                </div>
                <a href="{{ route('purchases.index') }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3" style="font-size: 0.78rem;">
                    Lihat Semua
                </a>
            </div>
            <div class="table-responsive">
                <table class="table align-middle w-100">
                    <thead>
                        <tr>
                            <th>No. PO</th>
                            <th>Supplier</th>
                            <th>Status</th>
                            <th class="text-end">Total Biaya</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentTransactions['recent_purchases'] as $pur)
                            <tr>
                                <td>
                                    <a href="{{ route('purchases.show', $pur) }}" class="fw-semibold text-decoration-none font-monospace small" style="color: var(--brand-primary);">
                                        {{ $pur->purchase_number }}
                                    </a>
                                    <div class="text-muted" style="font-size: 0.72rem;">{{ $pur->purchased_at->format('d/m/Y H:i') }}</div>
                                </td>
                                <td>
                                    <div class="fw-medium text-dark small">{{ $pur->supplier->name ?? '-' }}</div>
                                    <span class="text-muted" style="font-size: 0.7rem;">{{ $pur->supplier->company_name ?? '' }}</span>
                                </td>
                                <td>
                                    @if($pur->status === 'completed')
                                        <span class="badge bg-success bg-opacity-10 text-success rounded-pill" style="font-size: 0.72rem;">Diterima</span>
                                    @elseif($pur->status === 'pending')
                                        <span class="badge bg-warning bg-opacity-10 text-warning rounded-pill" style="font-size: 0.72rem;">Pending</span>
                                    @else
                                        <span class="badge bg-secondary rounded-pill" style="font-size: 0.72rem;">Batal</span>
                                    @endif
                                </td>
                                <td class="text-end fw-semibold text-dark small">
                                    {{ \App\Support\FormatHelper::rupiah($pur->total) }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-4 text-muted small">Belum ada transaksi pengadaan stok.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Right Column: Alerts & Top Products -->
    <div class="col-lg-5">
        <!-- Low Stock Alert Box -->
        <div class="card card-clean mb-4">
            <div class="card-header-clean d-flex align-items-center justify-content-between">
                <div>
                    <h6 class="fw-bold mb-0 text-dark">Peringatan Stok Rendah</h6>
                    <span class="text-muted small">Stok fisik &le; batas minimum</span>
                </div>
                <a href="{{ route('dashboard.low-stock') }}" class="btn btn-outline-danger btn-sm rounded-pill px-3" style="font-size: 0.78rem;">
                    Kelola ({{ $metrics['low_stock_count'] }})
                </a>
            </div>
            <div class="card-body-clean p-0">
                @if($lowStockProducts->isEmpty())
                    <div class="text-center py-4 text-muted small">
                        <i class="bi bi-check-circle text-success fs-3 d-block mb-1"></i>
                        Semua stok outfit dalam kuota aman.
                    </div>
                @else
                    <div class="list-group list-group-flush">
                        @foreach($lowStockProducts->take(4) as $prod)
                            <div class="list-group-item d-flex align-items-center justify-content-between py-3 px-4 border-0 border-bottom">
                                <div>
                                    <div class="fw-semibold text-dark small">{{ $prod->name }}</div>
                                    <span class="text-muted font-monospace" style="font-size: 0.72rem;">{{ $prod->sku }} &bull; Min: {{ $prod->minimum_stock }} pcs</span>
                                </div>
                                <div class="text-end">
                                    <span class="badge {{ $prod->stock <= 0 ? 'badge-stock-empty' : 'badge-stock-low' }} rounded-pill px-2 py-1 small">
                                        {{ $prod->stock }} pcs
                                    </span>
                                    <div class="mt-1">
                                        <a href="{{ route('purchases.create') }}" class="text-decoration-none small fw-semibold" style="color: var(--brand-primary); font-size: 0.75rem;">
                                            + Restok
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        <!-- Top Selling Outfits -->
        <div class="card card-clean mb-4">
            <div class="card-header-clean">
                <h6 class="fw-bold mb-0 text-dark">Top 5 Produk Terlaris</h6>
                <span class="text-muted small">Berdasarkan total kuantitas penjualan</span>
            </div>
            <div class="card-body-clean p-3">
                @if(empty($topSellingProducts))
                    <div class="text-center py-3 text-muted small">Belum ada data outfit terjual.</div>
                @else
                    <div class="d-flex flex-column gap-2">
                        @foreach($topSellingProducts as $idx => $item)
                            <div class="d-flex align-items-center justify-content-between p-2 rounded-3 bg-light">
                                <div class="d-flex align-items-center gap-2">
                                    <span class="badge bg-secondary bg-opacity-25 text-dark rounded-circle" style="width: 24px; height: 24px; display: flex; align-items: center; justify-content: center; font-size: 0.72rem;">
                                        {{ $idx + 1 }}
                                    </span>
                                    <div>
                                        <div class="fw-semibold text-dark small text-truncate" style="max-width: 180px;">{{ $item['product']->name }}</div>
                                        <span class="text-muted" style="font-size: 0.7rem;">{{ $item['product']->category->name ?? 'Outfit' }}</span>
                                    </div>
                                </div>
                                <div class="text-end">
                                    <span class="fw-bold text-success small">{{ $item['total_sold'] }} pcs</span>
                                    <div class="text-muted" style="font-size: 0.7rem;">{{ \App\Support\FormatHelper::rupiahShort($item['total_revenue']) }}</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        <!-- Payment Method Breakdown (Clean) -->
        <div class="card card-clean">
            <div class="card-header-clean">
                <h6 class="fw-bold mb-0 text-dark">Metode Pembayaran</h6>
                <span class="text-muted small">Payment Gateway & Kasir</span>
            </div>
            <div class="card-body-clean p-3">
                @if(empty($paymentBreakdown))
                    <div class="text-center py-3 text-muted small">Belum ada transaksi pembayaran.</div>
                @else
                    <div class="d-flex flex-column gap-2">
                        @foreach(array_slice($paymentBreakdown, 0, 4) as $pay)
                            <div>
                                <div class="d-flex justify-content-between small mb-1">
                                    <span class="text-dark fw-medium" style="font-size: 0.8rem;">{{ $pay['label'] }}</span>
                                    <span class="text-muted" style="font-size: 0.75rem;">{{ $pay['percentage'] }}% ({{ $pay['total_transactions'] }} tx)</span>
                                </div>
                                <div class="progress" style="height: 5px;">
                                    <div class="progress-bar rounded-pill" style="width: {{ $pay['percentage'] }}%; background-color: {{ $pay['color'] }};"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
