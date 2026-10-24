@extends('layouts.app')

@section('title', 'Laporan & Rekapitulasi')
@section('page_title', 'Laporan Finansial & Transaksi Terpadu')

@section('content')
<!-- Filter Card -->
<div class="card card-custom p-4 border-0 shadow-sm mb-4">
    <form action="{{ route('reports.index') }}" method="GET" class="row g-3 align-items-end">
        <!-- Date Start -->
        <div class="col-md-3 col-sm-6">
            <label class="form-label-custom">Tanggal Awal:</label>
            <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
        </div>

        <!-- Date End -->
        <div class="col-md-3 col-sm-6">
            <label class="form-label-custom">Tanggal Akhir:</label>
            <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
        </div>

        <!-- Report Type -->
        <div class="col-md-2 col-sm-6">
            <label class="form-label-custom">Jenis Laporan:</label>
            <select name="report_type" class="form-select">
                <option value="sales" {{ $reportType === 'sales' ? 'selected' : '' }}>Penjualan (Sales)</option>
                <option value="purchases" {{ $reportType === 'purchases' ? 'selected' : '' }}>Pembelian (Stock-In)</option>
                <option value="stock" {{ $reportType === 'stock' ? 'selected' : '' }}>Valuasi Stok</option>
            </select>
        </div>

        <!-- Status -->
        <div class="col-md-2 col-sm-6">
            <label class="form-label-custom">Status:</label>
            <select name="status" class="form-select">
                <option value="">Semua Status</option>
                <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Selesai / Lunas</option>
                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Menunggu (Pending)</option>
                <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
            </select>
        </div>

        <!-- Payment Method -->
        <div class="col-md-2 col-sm-6">
            <label class="form-label-custom">Metode Bayar:</label>
            <select name="payment_method" class="form-select">
                <option value="">Semua Metode</option>
                <option value="cash" {{ request('payment_method') === 'cash' ? 'selected' : '' }}>Cash POS</option>
                <option value="qris" {{ request('payment_method') === 'qris' ? 'selected' : '' }}>QRIS</option>
                <option value="va_bca" {{ request('payment_method') === 'va_bca' ? 'selected' : '' }}>BCA VA</option>
                <option value="va_mandiri" {{ request('payment_method') === 'va_mandiri' ? 'selected' : '' }}>Mandiri VA</option>
                <option value="va_bri" {{ request('payment_method') === 'va_bri' ? 'selected' : '' }}>BRI VA</option>
                <option value="va_bsi" {{ request('payment_method') === 'va_bsi' ? 'selected' : '' }}>BSI VA</option>
                <option value="ewallet_shopeepay" {{ request('payment_method') === 'ewallet_shopeepay' ? 'selected' : '' }}>ShopeePay</option>
                <option value="ewallet_gopay" {{ request('payment_method') === 'ewallet_gopay' ? 'selected' : '' }}>GoPay</option>
            </select>
        </div>

        <!-- Buttons -->
        <div class="col-12 d-flex flex-wrap gap-2 justify-content-between pt-2">
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary rounded-pill px-4">
                    <i class="bi bi-funnel-fill me-1"></i> Terapkan Filter
                </button>
                <a href="{{ route('reports.index') }}" class="btn btn-outline-secondary rounded-pill px-3">
                    <i class="bi bi-arrow-counterclockwise me-1"></i> Reset
                </a>
            </div>
            <div>
                <a href="{{ route('reports.print', request()->query()) }}" target="_blank" class="btn btn-outline-dark rounded-pill px-4 shadow-sm">
                    <i class="bi bi-printer me-1"></i> Cetak Laporan (Print View)
                </a>
            </div>
        </div>
    </form>
</div>

<!-- Financial Summary KPI Cards -->
<div class="row g-4 mb-4">
    <!-- Sales Revenue -->
    <div class="col-xl-3 col-sm-6">
        <div class="card card-custom p-4 border-0 shadow-sm h-100">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="text-muted small fw-semibold text-uppercase">Pemasukan Penjualan</span>
                <div class="bg-success bg-opacity-10 text-success rounded-circle p-2">
                    <i class="bi bi-arrow-down-left-circle fs-5"></i>
                </div>
            </div>
            <h4 class="fw-extrabold text-success mb-1" style="font-weight: 800;">
                {{ \App\Support\FormatHelper::rupiah($summary['total_sales_revenue']) }}
            </h4>
            <div class="small text-muted">
                {{ $summary['total_sales_count'] }} Transaksi Selesai &bull; {{ $summary['total_units_sold'] }} Pcs Terjual
            </div>
        </div>
    </div>

    <!-- Purchases Expense -->
    <div class="col-xl-3 col-sm-6">
        <div class="card card-custom p-4 border-0 shadow-sm h-100">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="text-muted small fw-semibold text-uppercase">Belanja Stok (Pengadaan)</span>
                <div class="bg-primary bg-opacity-10 text-primary rounded-circle p-2">
                    <i class="bi bi-arrow-up-right-circle fs-5"></i>
                </div>
            </div>
            <h4 class="fw-extrabold text-primary mb-1" style="font-weight: 800;">
                {{ \App\Support\FormatHelper::rupiah($summary['total_purchases_expense']) }}
            </h4>
            <div class="small text-muted">
                {{ $summary['total_purchases_count'] }} PO Diterima &bull; {{ $summary['total_units_purchased'] }} Pcs Dipasok
            </div>
        </div>
    </div>

    <!-- Net Margin -->
    <div class="col-xl-3 col-sm-6">
        <div class="card card-custom p-4 border-0 shadow-sm h-100">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="text-muted small fw-semibold text-uppercase">Selisih Bersih (Margin)</span>
                <div class="bg-info bg-opacity-10 text-info rounded-circle p-2">
                    <i class="bi bi-pie-chart fs-5"></i>
                </div>
            </div>
            <h4 class="fw-extrabold {{ $summary['net_margin'] >= 0 ? 'text-dark' : 'text-danger' }} mb-1" style="font-weight: 800;">
                {{ \App\Support\FormatHelper::rupiah($summary['net_margin']) }}
            </h4>
            <div class="small text-muted">
                Rasio Margin: <strong>{{ $summary['profit_margin_percentage'] }}%</strong>
            </div>
        </div>
    </div>

    <!-- Stock Asset Valuation -->
    <div class="col-xl-3 col-sm-6">
        <div class="card card-custom p-4 border-0 shadow-sm h-100">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="text-muted small fw-semibold text-uppercase">Nilai Aset Stok Fisik</span>
                <div class="bg-warning bg-opacity-10 text-warning rounded-circle p-2">
                    <i class="bi bi-box-seam fs-5"></i>
                </div>
            </div>
            <h4 class="fw-extrabold text-dark mb-1" style="font-weight: 800;">
                {{ \App\Support\FormatHelper::rupiah($stockSummary['total_cost_value']) }}
            </h4>
            <div class="small text-muted">
                {{ $stockSummary['total_units'] }} Pcs Total &bull; Potensi Omset: {{ \App\Support\FormatHelper::rupiahShort($stockSummary['total_retail_value']) }}
            </div>
        </div>
    </div>
</div>

<!-- Report Detail Table -->
<div class="card card-custom p-4 border-0 shadow-sm">
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h5 class="fw-bold text-dark mb-0">
            @if($reportType === 'purchases')
                Tabel Rincian Pembelian Stok (Purchases)
            @elseif($reportType === 'stock')
                Tabel Valuasi & Inventori Stok Outfit
            @else
                Tabel Rincian Transaksi Penjualan (Sales)
            @endif
        </h5>
        <span class="badge bg-light text-dark border px-3 py-2">
            Total Data: {{ $data->total() }} Baris
        </span>
    </div>

    @if($reportType === 'purchases')
        <!-- PURCHASES TABLE -->
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light small text-uppercase">
                    <tr>
                        <th>No. Pembelian</th>
                        <th>Tanggal</th>
                        <th>Supplier</th>
                        <th>Operator</th>
                        <th>Status</th>
                        <th class="text-end">Total Biaya</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data as $purchase)
                        <tr>
                            <td>
                                <a href="{{ route('purchases.show', $purchase) }}" class="fw-bold text-primary text-decoration-none">
                                    {{ $purchase->purchase_number }}
                                </a>
                            </td>
                            <td>{{ $purchase->purchased_at->format('d/m/Y H:i') }}</td>
                            <td>
                                <div class="fw-semibold text-dark">{{ $purchase->supplier->name ?? '-' }}</div>
                                <span class="text-muted small">{{ $purchase->supplier->company_name ?? '' }}</span>
                            </td>
                            <td>{{ $purchase->user->name ?? '-' }}</td>
                            <td>
                                @if($purchase->status === 'completed')
                                    <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-2 py-1">Selesai</span>
                                @elseif($purchase->status === 'pending')
                                    <span class="badge bg-warning bg-opacity-10 text-warning rounded-pill px-2 py-1">Pending</span>
                                @else
                                    <span class="badge bg-danger bg-opacity-10 text-danger rounded-pill px-2 py-1">Dibatalkan</span>
                                @endif
                            </td>
                            <td class="text-end fw-bold text-dark">
                                {{ \App\Support\FormatHelper::rupiah($purchase->total) }}
                            </td>
                            <td class="text-end">
                                <a href="{{ route('purchases.show', $purchase) }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
                                    Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">Tidak ada data pembelian pada periode filter ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    @elseif($reportType === 'stock')
        <!-- STOCK VALUATION TABLE -->
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light small text-uppercase">
                    <tr>
                        <th>SKU</th>
                        <th>Nama Produk Outfit</th>
                        <th>Kategori</th>
                        <th class="text-center">Stok Fisik</th>
                        <th class="text-end">Harga Beli</th>
                        <th class="text-end">Harga Jual</th>
                        <th class="text-end">Total Nilai Pokok</th>
                        <th class="text-end">Total Nilai Jual</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data as $prod)
                        @php
                            $costSubtotal = $prod->stock * $prod->purchase_price;
                            $retailSubtotal = $prod->stock * $prod->selling_price;
                        @endphp
                        <tr>
                            <td class="font-monospace text-muted">{{ $prod->sku }}</td>
                            <td>
                                <div class="fw-bold text-dark">{{ $prod->name }}</div>
                                <span class="text-muted small">{{ $prod->size ?? '-' }} &bull; {{ $prod->color ?? '-' }}</span>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark border">{{ $prod->category->name ?? '-' }}</span>
                            </td>
                            <td class="text-center fw-bold {{ $prod->stock <= $prod->minimum_stock ? 'text-danger' : 'text-dark' }}">
                                {{ $prod->stock }} pcs
                            </td>
                            <td class="text-end">{{ \App\Support\FormatHelper::rupiah($prod->purchase_price) }}</td>
                            <td class="text-end">{{ \App\Support\FormatHelper::rupiah($prod->selling_price) }}</td>
                            <td class="text-end fw-bold text-primary">{{ \App\Support\FormatHelper::rupiah($costSubtotal) }}</td>
                            <td class="text-end fw-bold text-success">{{ \App\Support\FormatHelper::rupiah($retailSubtotal) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-4 text-muted">Tidak ada data produk outfit.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    @else
        <!-- SALES TABLE -->
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light small text-uppercase">
                    <tr>
                        <th>No. Nota</th>
                        <th>Tanggal</th>
                        <th>Pelanggan</th>
                        <th>Metode Bayar</th>
                        <th>Status</th>
                        <th>Pembayaran</th>
                        <th class="text-end">Total Tagihan</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data as $sale)
                        <tr>
                            <td>
                                <a href="{{ route('sales.show', $sale) }}" class="fw-bold text-primary text-decoration-none">
                                    {{ $sale->sale_number }}
                                </a>
                            </td>
                            <td>{{ $sale->sold_at->format('d/m/Y H:i') }}</td>
                            <td>
                                <div class="fw-semibold text-dark">{{ $sale->customer_name }}</div>
                                <span class="text-muted small">Operator: {{ $sale->user->name ?? '-' }}</span>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark border text-uppercase">{{ $sale->payment_method ?? 'CASH' }}</span>
                            </td>
                            <td>
                                @if($sale->status === 'completed')
                                    <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-2 py-1">Selesai</span>
                                @elseif($sale->status === 'pending')
                                    <span class="badge bg-warning bg-opacity-10 text-warning rounded-pill px-2 py-1">Pending</span>
                                @else
                                    <span class="badge bg-danger bg-opacity-10 text-danger rounded-pill px-2 py-1">Dibatalkan</span>
                                @endif
                            </td>
                            <td>
                                @if($sale->payment_status === 'paid')
                                    <span class="badge bg-success rounded-pill px-2 py-1">Lunas</span>
                                @elseif($sale->payment_status === 'refunded')
                                    <span class="badge bg-secondary rounded-pill px-2 py-1">Refund</span>
                                @else
                                    <span class="badge bg-warning text-dark rounded-pill px-2 py-1">Belum Bayar</span>
                                @endif
                            </td>
                            <td class="text-end fw-bold text-dark">
                                {{ \App\Support\FormatHelper::rupiah($sale->total) }}
                            </td>
                            <td class="text-end">
                                <a href="{{ route('sales.show', $sale) }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
                                    Invoice
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-4 text-muted">Tidak ada transaksi penjualan pada periode filter ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    @endif

    <div class="mt-4">
        {{ $data->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection
