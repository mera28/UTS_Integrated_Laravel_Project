@extends('layouts.app')

@section('title', 'Dashboard Analitik')
@section('page_title', 'Real-Time Analytics & Dashboard Utama')

@section('content')
<!-- KPI Cards Row -->
<div class="row g-4 mb-4">
    <!-- Stat 1: Penjualan Bulan Ini & Hari Ini -->
    <div class="col-xl-3 col-sm-6">
        <div class="card card-custom p-4 h-100 border-0 shadow-sm">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <span class="text-muted small fw-semibold text-uppercase">Pendapatan Bulan Ini</span>
                <div class="bg-success bg-opacity-10 text-success rounded-3 p-2">
                    <i class="bi bi-wallet2 fs-5"></i>
                </div>
            </div>
            <h4 class="fw-extrabold mb-1 text-dark" style="font-weight: 800;">
                {{ \App\Support\FormatHelper::rupiah($metrics['month_sales']) }}
            </h4>
            <div class="d-flex align-items-center justify-content-between small text-muted mt-2">
                <span>Hari Ini: <strong>{{ \App\Support\FormatHelper::rupiah($metrics['today_sales']) }}</strong></span>
                <span class="badge bg-success bg-opacity-10 text-success rounded-pill">{{ $metrics['today_sales_count'] }} Nota</span>
            </div>
        </div>
    </div>

    <!-- Stat 2: Total Belanja Stok Bulan Ini -->
    <div class="col-xl-3 col-sm-6">
        <div class="card card-custom p-4 h-100 border-0 shadow-sm">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <span class="text-muted small fw-semibold text-uppercase">Pengadaan Stok (Stock-In)</span>
                <div class="bg-primary bg-opacity-10 text-primary rounded-3 p-2">
                    <i class="bi bi-truck fs-5"></i>
                </div>
            </div>
            <h4 class="fw-extrabold mb-1 text-dark" style="font-weight: 800;">
                {{ \App\Support\FormatHelper::rupiah($metrics['month_purchases']) }}
            </h4>
            <div class="d-flex align-items-center justify-content-between small text-muted mt-2">
                <span>Total: <strong>{{ $metrics['month_purchases_count'] }} PO</strong></span>
                <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill">Konveksi & Supplier</span>
            </div>
        </div>
    </div>

    <!-- Stat 3: Estimasi Margin Bersih -->
    <div class="col-xl-3 col-sm-6">
        <div class="card card-custom p-4 h-100 border-0 shadow-sm">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <span class="text-muted small fw-semibold text-uppercase">Estimasi Margin Bersih</span>
                <div class="bg-info bg-opacity-10 text-info rounded-3 p-2">
                    <i class="bi bi-graph-up-arrow fs-5"></i>
                </div>
            </div>
            <h4 class="fw-extrabold mb-1 {{ $metrics['net_profit_estimate'] >= 0 ? 'text-success' : 'text-danger' }}" style="font-weight: 800;">
                {{ \App\Support\FormatHelper::rupiah($metrics['net_profit_estimate']) }}
            </h4>
            <div class="small text-muted mt-2">
                <i class="bi bi-info-circle me-1"></i> Penjualan dikurangi Pembelian Bulan Ini
            </div>
        </div>
    </div>

    <!-- Stat 4: Low Stock Alert -->
    <div class="col-xl-3 col-sm-6">
        <div class="card card-custom p-4 h-100 border-0 shadow-sm {{ $metrics['low_stock_count'] > 0 ? 'border-danger border-opacity-50' : '' }}">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <span class="text-danger small fw-semibold text-uppercase">Peringatan Stok Menipis</span>
                <div class="bg-danger bg-opacity-10 text-danger rounded-3 p-2">
                    <i class="bi bi-exclamation-triangle-fill fs-5"></i>
                </div>
            </div>
            <h4 class="fw-extrabold mb-1 text-danger" style="font-weight: 800;">
                {{ $metrics['low_stock_count'] }} <span class="fs-6 fw-normal text-muted">Produk</span>
            </h4>
            <div class="d-flex align-items-center justify-content-between small text-muted mt-2">
                <span class="text-danger fw-semibold"><i class="bi bi-bell-fill me-1"></i> Perlu Restok</span>
                <a href="{{ route('dashboard.low-stock') }}" class="text-danger text-decoration-none fw-semibold">Lihat Semua &rarr;</a>
            </div>
        </div>
    </div>
</div>

<!-- Secondary Stats Row (Master Counts) -->
<div class="row g-3 mb-4">
    <div class="col-md-3 col-6">
        <div class="card card-custom p-3 border-0 bg-light">
            <div class="d-flex align-items-center gap-3">
                <div class="bg-white rounded-circle p-2 text-dark shadow-sm">
                    <i class="bi bi-bag-check fs-5 text-secondary"></i>
                </div>
                <div>
                    <div class="text-muted small">Total Produk</div>
                    <div class="fw-bold fs-5 text-dark">{{ $metrics['total_products'] }} <span class="fs-6 fw-normal text-muted">({{ $metrics['total_stock'] }} pcs)</span></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="card card-custom p-3 border-0 bg-light">
            <div class="d-flex align-items-center gap-3">
                <div class="bg-white rounded-circle p-2 text-dark shadow-sm">
                    <i class="bi bi-tags fs-5 text-secondary"></i>
                </div>
                <div>
                    <div class="text-muted small">Kategori Outfit</div>
                    <div class="fw-bold fs-5 text-dark">{{ $metrics['total_categories'] }} Kategori</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="card card-custom p-3 border-0 bg-light">
            <div class="d-flex align-items-center gap-3">
                <div class="bg-white rounded-circle p-2 text-dark shadow-sm">
                    <i class="bi bi-truck fs-5 text-secondary"></i>
                </div>
                <div>
                    <div class="text-muted small">Mitra Supplier</div>
                    <div class="fw-bold fs-5 text-dark">{{ $metrics['total_suppliers'] }} Supplier</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="card card-custom p-3 border-0 bg-light">
            <div class="d-flex align-items-center gap-3">
                <div class="bg-white rounded-circle p-2 text-dark shadow-sm">
                    <i class="bi bi-people fs-5 text-secondary"></i>
                </div>
                <div>
                    <div class="text-muted small">Customer Terdaftar</div>
                    <div class="fw-bold fs-5 text-dark">{{ $metrics['total_customers'] }} Pelanggan</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- 7-Day Sales Trend & Payment Gateway Breakdown -->
<div class="row g-4 mb-4">
    <!-- 7-Day Trend -->
    <div class="col-lg-7">
        <div class="card card-custom p-4 border-0 shadow-sm h-100">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <div>
                    <h5 class="fw-bold text-dark mb-0">Tren Penjualan 7 Hari Terakhir</h5>
                    <span class="text-muted small">Performa akumulasi omset harian toko</span>
                </div>
                <span class="badge bg-light text-dark border"><i class="bi bi-calendar3 me-1"></i> 7 Hari</span>
            </div>

            <div class="row g-2 align-items-end" style="min-height: 180px;">
                @php
                    $maxVal = max(array_column($salesTrend, 'total_amount')) ?: 1;
                @endphp
                @foreach($salesTrend as $day)
                    @php
                        $pct = round(($day['total_amount'] / $maxVal) * 100);
                        $height = max(15, $pct);
                    @endphp
                    <div class="col text-center">
                        <div class="d-flex flex-column align-items-center justify-content-end" style="height: 140px;">
                            <span class="small fw-bold text-dark mb-1" style="font-size: 0.7rem;">
                                {{ $day['total_amount'] > 0 ? \App\Support\FormatHelper::rupiahShort($day['total_amount']) : '0' }}
                            </span>
                            <div class="w-100 rounded-3 {{ $day['total_amount'] > 0 ? 'bg-primary' : 'bg-light border' }}" 
                                 style="height: {{ $height }}%; min-height: 8px; transition: all 0.3s ease;"
                                 title="{{ $day['day_name'] }} ({{ $day['formatted_date'] }}): {{ \App\Support\FormatHelper::rupiah($day['total_amount']) }} ({{ $day['total_orders'] }} Transaksi)">
                            </div>
                        </div>
                        <div class="mt-2 text-muted fw-semibold" style="font-size: 0.72rem;">
                            {{ $day['formatted_date'] }}
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Payment Gateway Breakdown -->
    <div class="col-lg-5">
        <div class="card card-custom p-4 border-0 shadow-sm h-100">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div>
                    <h5 class="fw-bold text-dark mb-0">Metode Pembayaran Terpopuler</h5>
                    <span class="text-muted small">Statistik Payment Gateway & Kasir POS</span>
                </div>
                <div class="bg-primary bg-opacity-10 text-primary rounded-circle p-2">
                    <i class="bi bi-credit-card-2-front"></i>
                </div>
            </div>

            @if(empty($paymentBreakdown))
                <div class="text-center py-4 text-muted">
                    <i class="bi bi-credit-card fs-1 d-block mb-2 text-secondary"></i>
                    Belum ada data transaksi pembayaran lunas.
                </div>
            @else
                <div class="d-flex flex-column gap-3 mt-2">
                    @foreach($paymentBreakdown as $pay)
                        <div>
                            <div class="d-flex align-items-center justify-content-between mb-1">
                                <span class="small fw-bold text-dark d-flex align-items-center gap-2">
                                    <i class="bi {{ $pay['icon'] }}" style="color: {{ $pay['color'] }};"></i>
                                    {{ $pay['label'] }}
                                </span>
                                <span class="small fw-semibold text-muted">
                                    {{ \App\Support\FormatHelper::rupiah($pay['total_amount']) }} ({{ $pay['percentage'] }}%)
                                </span>
                            </div>
                            <div class="progress" style="height: 6px;">
                                <div class="progress-bar rounded-pill" role="progressbar" 
                                     style="width: {{ $pay['percentage'] }}%; background-color: {{ $pay['color'] }};">
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Low Stock Alerts & Best-Selling Outfits -->
<div class="row g-4 mb-4">
    <!-- Low Stock Table (Slide 13 PPT) -->
    <div class="col-lg-7">
        <div class="card card-custom p-4 border-0 shadow-sm h-100">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div class="d-flex align-items-center gap-2">
                    <span class="badge bg-danger rounded-circle p-2"><i class="bi bi-bell-fill text-white"></i></span>
                    <div>
                        <h5 class="fw-bold text-dark mb-0">Peringatan Stok Rendah (Low-Stock Alert)</h5>
                        <span class="text-muted small">Stok fisik &le; batas minimum restok</span>
                    </div>
                </div>
                <a href="{{ route('dashboard.low-stock') }}" class="btn btn-outline-danger btn-sm rounded-pill px-3">
                    Lihat Semua ({{ $metrics['low_stock_count'] }})
                </a>
            </div>

            @if($lowStockProducts->isEmpty())
                <div class="text-center py-4">
                    <i class="bi bi-check-circle-fill text-success fs-1 mb-2 d-block"></i>
                    <h6 class="fw-bold text-success mb-0">Semua Stok Aman!</h6>
                    <span class="text-muted small">Tidak ada produk outfit yang berada di bawah stok minimum.</span>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light small text-uppercase">
                            <tr>
                                <th>Produk Outfit</th>
                                <th>Kategori</th>
                                <th class="text-center">Sisa Stok</th>
                                <th class="text-center">Min. Stok</th>
                                <th class="text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($lowStockProducts as $prod)
                                <tr>
                                    <td>
                                        <div class="fw-bold text-dark">{{ $prod->name }}</div>
                                        <div class="small text-muted font-monospace">{{ $prod->sku }}</div>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark border">{{ $prod->category->name ?? '-' }}</span>
                                    </td>
                                    <td class="text-center">
                                        @if($prod->stock <= 0)
                                            <span class="badge bg-danger rounded-pill px-2 py-1">Habis (0 pcs)</span>
                                        @else
                                            <span class="badge bg-warning text-dark rounded-pill px-2 py-1 fw-bold">{{ $prod->stock }} pcs</span>
                                        @endif
                                    </td>
                                    <td class="text-center text-muted small">
                                        {{ $prod->minimum_stock }} pcs
                                    </td>
                                    <td class="text-end">
                                        <a href="{{ route('purchases.create') }}" class="btn btn-primary btn-sm rounded-pill px-2 py-1" style="font-size: 0.78rem;">
                                            <i class="bi bi-plus-circle me-1"></i> Restok
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    <!-- Top 5 Best-Selling Outfits -->
    <div class="col-lg-5">
        <div class="card card-custom p-4 border-0 shadow-sm h-100">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div>
                    <h5 class="fw-bold text-dark mb-0">Top 5 Produk Terlaris</h5>
                    <span class="text-muted small">Paling banyak terjual di pesanan & kasir</span>
                </div>
                <div class="bg-warning bg-opacity-20 text-warning rounded-circle p-2">
                    <i class="bi bi-trophy-fill text-warning"></i>
                </div>
            </div>

            @if(empty($topSellingProducts))
                <div class="text-center py-4 text-muted">
                    <i class="bi bi-bag-x fs-1 d-block mb-2 text-secondary"></i>
                    Belum ada produk outfit yang terjual.
                </div>
            @else
                <div class="d-flex flex-column gap-3">
                    @foreach($topSellingProducts as $idx => $item)
                        <div class="d-flex align-items-center justify-content-between p-2 rounded-3 bg-light">
                            <div class="d-flex align-items-center gap-3">
                                <div class="badge rounded-circle p-2 {{ $idx == 0 ? 'bg-warning text-dark' : ($idx == 1 ? 'bg-secondary text-white' : 'bg-light border text-dark') }}" style="width: 28px; height: 28px; display: flex; align-items: center; justify-content: center; font-weight: bold;">
                                    {{ $idx + 1 }}
                                </div>
                                <div>
                                    <div class="fw-bold text-dark small">{{ $item['product']->name }}</div>
                                    <span class="text-muted" style="font-size: 0.75rem;">
                                        {{ $item['product']->category->name ?? 'Outfit' }} &bull; Sisa: {{ $item['product']->stock }} pcs
                                    </span>
                                </div>
                            </div>
                            <div class="text-end">
                                <div class="fw-bold text-success small">{{ $item['total_sold'] }} pcs</div>
                                <span class="text-muted" style="font-size: 0.72rem;">{{ \App\Support\FormatHelper::rupiahShort($item['total_revenue']) }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Recent Transactions (5 Latest Sales & 5 Latest Purchases) -->
<div class="row g-4">
    <!-- Recent Sales -->
    <div class="col-lg-6">
        <div class="card card-custom p-4 border-0 shadow-sm">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <h5 class="fw-bold text-dark mb-0">Transaksi Penjualan Terbaru</h5>
                <a href="{{ route('sales.index') }}" class="small text-decoration-none fw-semibold">Lihat Semua &rarr;</a>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" style="font-size: 0.85rem;">
                    <thead class="table-light">
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
                                    <a href="{{ route('sales.show', $sale) }}" class="fw-bold text-primary text-decoration-none">
                                        {{ $sale->sale_number }}
                                    </a>
                                    <div class="text-muted" style="font-size: 0.75rem;">{{ $sale->sold_at->format('d/m/Y H:i') }}</div>
                                </td>
                                <td>
                                    <div class="fw-semibold text-dark">{{ $sale->customer_name }}</div>
                                    <span class="badge bg-light text-dark border text-uppercase" style="font-size: 0.68rem;">{{ $sale->payment_method ?? 'CASH' }}</span>
                                </td>
                                <td>
                                    @if($sale->status === 'completed')
                                        <span class="badge bg-success bg-opacity-10 text-success rounded-pill">Selesai</span>
                                    @elseif($sale->status === 'pending')
                                        <span class="badge bg-warning bg-opacity-10 text-warning rounded-pill">Menunggu</span>
                                    @else
                                        <span class="badge bg-danger bg-opacity-10 text-danger rounded-pill">Batal</span>
                                    @endif
                                </td>
                                <td class="text-end fw-bold text-dark">
                                    {{ \App\Support\FormatHelper::rupiah($sale->total) }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-3 text-muted">Belum ada transaksi penjualan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Recent Purchases -->
    <div class="col-lg-6">
        <div class="card card-custom p-4 border-0 shadow-sm">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <h5 class="fw-bold text-dark mb-0">Pengadaan Stok Terbaru</h5>
                <a href="{{ route('purchases.index') }}" class="small text-decoration-none fw-semibold">Lihat Semua &rarr;</a>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" style="font-size: 0.85rem;">
                    <thead class="table-light">
                        <tr>
                            <th>No. PO</th>
                            <th>Supplier</th>
                            <th>Status</th>
                            <th class="text-end">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentTransactions['recent_purchases'] as $pur)
                            <tr>
                                <td>
                                    <a href="{{ route('purchases.show', $pur) }}" class="fw-bold text-primary text-decoration-none">
                                        {{ $pur->purchase_number }}
                                    </a>
                                    <div class="text-muted" style="font-size: 0.75rem;">{{ $pur->purchased_at->format('d/m/Y H:i') }}</div>
                                </td>
                                <td>
                                    <div class="fw-semibold text-dark">{{ $pur->supplier->name ?? '-' }}</div>
                                    <span class="text-muted" style="font-size: 0.72rem;">{{ $pur->supplier->company_name ?? '' }}</span>
                                </td>
                                <td>
                                    @if($pur->status === 'completed')
                                        <span class="badge bg-success bg-opacity-10 text-success rounded-pill">Diterima</span>
                                    @elseif($pur->status === 'pending')
                                        <span class="badge bg-warning bg-opacity-10 text-warning rounded-pill">Menunggu</span>
                                    @else
                                        <span class="badge bg-danger bg-opacity-10 text-danger rounded-pill">Batal</span>
                                    @endif
                                </td>
                                <td class="text-end fw-bold text-dark">
                                    {{ \App\Support\FormatHelper::rupiah($pur->total) }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-3 text-muted">Belum ada transaksi pembelian.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
