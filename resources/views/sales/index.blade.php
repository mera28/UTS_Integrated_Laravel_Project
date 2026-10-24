@extends('layouts.app')

@section('title', 'Daftar Penjualan')
@section('page_title', 'Transaksi Penjualan & Pesanan Kasir')

@section('content')
<div class="card card-master mb-4">
    <div class="card-header-master d-flex align-items-center justify-content-between flex-wrap gap-3">
        <div>
            <h5 class="fw-bold mb-1 text-dark">Data Penjualan (POS & E-Commerce)</h5>
            <span class="text-muted small">Kelola seluruh transaksi kasir toko dan pesanan online pelanggan</span>
        </div>
        <a href="{{ route('sales.create') }}" class="btn btn-brand-primary">
            <i class="bi bi-cart-plus me-1"></i> Buka Kasir POS Baru
        </a>
    </div>

    <div class="card-body-master">
        <!-- Filter Form -->
        <form action="{{ route('sales.index') }}" method="GET" class="mb-4">
            <div class="row g-2 align-items-center">
                <div class="col-md-3">
                    <input type="text" name="search" class="form-control form-control-custom" placeholder="Cari No. Nota, Pelanggan, Ref..." value="{{ request('search') }}">
                </div>
                <div class="col-md-2">
                    <select name="status" class="form-select form-select-custom">
                        <option value="">-- Status Pesanan --</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Selesai</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="payment_status" class="form-select form-select-custom">
                        <option value="">-- Status Bayar --</option>
                        <option value="paid" {{ request('payment_status') == 'paid' ? 'selected' : '' }}>Lunas (Paid)</option>
                        <option value="unpaid" {{ request('payment_status') == 'unpaid' ? 'selected' : '' }}>Belum Bayar</option>
                        <option value="refunded" {{ request('payment_status') == 'refunded' ? 'selected' : '' }}>Refunded</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="payment_method" class="form-select form-select-custom">
                        <option value="">-- Metode Bayar --</option>
                        <option value="cash" {{ request('payment_method') == 'cash' ? 'selected' : '' }}>Cash / Tunai</option>
                        <option value="qris" {{ request('payment_method') == 'qris' ? 'selected' : '' }}>QRIS</option>
                        <option value="va_bca" {{ request('payment_method') == 'va_bca' ? 'selected' : '' }}>BCA VA</option>
                        <option value="va_mandiri" {{ request('payment_method') == 'va_mandiri' ? 'selected' : '' }}>Mandiri VA</option>
                        <option value="va_bri" {{ request('payment_method') == 'va_bri' ? 'selected' : '' }}>BRI VA</option>
                        <option value="va_bsi" {{ request('payment_method') == 'va_bsi' ? 'selected' : '' }}>BSI VA</option>
                        <option value="ewallet_shopeepay" {{ request('payment_method') == 'ewallet_shopeepay' ? 'selected' : '' }}>ShopeePay</option>
                        <option value="ewallet_gopay" {{ request('payment_method') == 'ewallet_gopay' ? 'selected' : '' }}>GoPay</option>
                    </select>
                </div>
                <div class="col-md-2 d-flex gap-1">
                    <input type="date" name="start_date" class="form-control form-control-custom" title="Dari Tanggal" value="{{ request('start_date') }}">
                    <input type="date" name="end_date" class="form-control form-control-custom" title="Sampai Tanggal" value="{{ request('end_date') }}">
                </div>
                <div class="col-md-1 d-flex gap-1">
                    <button type="submit" class="btn btn-dark w-100" title="Filter"><i class="bi bi-funnel"></i></button>
                    @if(request()->hasAny(['search', 'status', 'payment_status', 'payment_method', 'start_date', 'end_date']))
                        <a href="{{ route('sales.index') }}" class="btn btn-light border" title="Reset"><i class="bi bi-x-circle"></i></a>
                    @endif
                </div>
            </div>
        </form>

        <!-- Table -->
        <div class="table-responsive rounded-3 border">
            <table class="table-custom align-middle">
                <thead>
                    <tr>
                        <th style="width: 50px;">No.</th>
                        <th>Nomor Invoice</th>
                        <th>Pelanggan</th>
                        <th>Waktu Transaksi</th>
                        <th class="text-center">Item Baju</th>
                        <th>Total Pembayaran</th>
                        <th>Metode Bayar</th>
                        <th class="text-center">Status Bayar</th>
                        <th class="text-end" style="width: 100px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($sales as $index => $sale)
                    <tr>
                        <td>{{ $sales->firstItem() + $index }}</td>
                        <td>
                            <a href="{{ route('sales.show', $sale) }}" class="fw-bold text-dark text-decoration-none">
                                {{ $sale->sale_number }}
                            </a>
                            @if($sale->payment_reference)
                                <div class="text-muted" style="font-size: 0.72rem;">Ref: {{ $sale->payment_reference }}</div>
                            @endif
                        </td>
                        <td>
                            <div class="fw-semibold text-dark">{{ $sale->customer_name ?: ($sale->user->name ?? 'Pelanggan') }}</div>
                            <div class="small text-muted" style="font-size: 0.75rem;">Input by: {{ $sale->user->name ?? '-' }}</div>
                        </td>
                        <td class="text-muted small">{{ \App\Support\FormatHelper::indonesianDate($sale->sold_at, true) }}</td>
                        <td class="text-center">
                            <span class="badge bg-light text-dark border">{{ $sale->items->sum('quantity') }} pcs ({{ $sale->items->count() }} item)</span>
                        </td>
                        <td class="fw-bold text-dark">{{ \App\Support\FormatHelper::rupiah($sale->total) }}</td>
                        <td>
                            <span class="badge bg-light text-dark border text-uppercase" style="font-size: 0.72rem;">
                                {{ str_replace('_', ' ', $sale->payment_method ?? 'cash') }}
                            </span>
                        </td>
                        <td class="text-center">
                            @if($sale->payment_status === 'paid')
                                <span class="badge bg-success bg-opacity-10 text-success border border-success px-2 py-1">Lunas</span>
                            @elseif($sale->payment_status === 'refunded')
                                <span class="badge bg-danger bg-opacity-10 text-danger border border-danger px-2 py-1">Refunded</span>
                            @else
                                <span class="badge bg-warning bg-opacity-10 text-warning border border-warning px-2 py-1">Belum Bayar</span>
                            @endif
                        </td>
                        <td class="text-end">
                            <a href="{{ route('sales.show', $sale) }}" class="btn btn-sm btn-outline-primary btn-action" title="Lihat Invoice">
                                <i class="bi bi-receipt"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center py-4 text-muted">Belum ada data transaksi penjualan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4 d-flex justify-content-end">
            {{ $sales->links() }}
        </div>
    </div>
</div>
@endsection
