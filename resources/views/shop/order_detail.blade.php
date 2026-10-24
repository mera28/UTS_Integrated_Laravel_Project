@extends('layouts.shop')

@section('title', 'Detail Pesanan ' . $sale->sale_number)

@section('content')
<div class="container py-4 py-md-5">
    <div class="row justify-content-center">
        <div class="col-lg-9">
            <!-- Navigation -->
            <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2 d-print-none">
                <a href="{{ route('shop.orders') }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
                    <i class="bi bi-arrow-left me-1"></i> Kembali ke Pesanan Saya
                </a>
                <button type="button" class="btn btn-light border btn-sm rounded-pill px-3" onclick="window.print()">
                    <i class="bi bi-printer me-1"></i> Cetak Bukti Pesanan
                </button>
            </div>

            <!-- Receipt Card -->
            <div class="card border-0 shadow-sm rounded-4 bg-white p-4 p-md-5">
                <div class="d-flex justify-content-between align-items-start border-bottom pb-4 mb-4 flex-wrap gap-3">
                    <div class="d-flex align-items-center gap-3">
                        <div class="brand-badge-icon" style="width: 48px; height: 48px; font-size: 1.4rem;">
                            <i class="bi bi-gem"></i>
                        </div>
                        <div>
                            <h4 class="fw-bold mb-0 text-dark">Zahiyyah's Fit</h4>
                            <span class="text-muted small">Official Fashion Store</span>
                        </div>
                    </div>
                    <div class="text-md-end">
                        <span class="badge bg-light text-secondary border mb-1">INVOICE PESANAN</span>
                        <h5 class="fw-bold text-dark mb-1">{{ $sale->sale_number }}</h5>
                        <div class="small text-muted">{{ \App\Support\FormatHelper::indonesianDate($sale->sold_at, true) }}</div>
                        <div class="mt-2">
                            @if($sale->payment_status === 'paid')
                                <span class="badge bg-success bg-opacity-10 text-success border border-success px-3 py-1">
                                    <i class="bi bi-check-circle me-1"></i> Pembayaran Lunas
                                </span>
                            @elseif($sale->payment_status === 'refunded')
                                <span class="badge bg-danger bg-opacity-10 text-danger border border-danger px-3 py-1">
                                    <i class="bi bi-x-circle me-1"></i> Transaksi Refunded
                                </span>
                            @else
                                <a href="{{ route('orders.payment', $sale) }}" class="badge bg-warning text-dark text-decoration-none px-3 py-1">
                                    <i class="bi bi-clock me-1"></i> Bayar Sekarang &rarr;
                                </a>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Info Grid -->
                <div class="row g-4 mb-4">
                    <div class="col-md-6">
                        <div class="p-3 bg-light rounded-3 border h-100 small">
                            <span class="text-muted d-block mb-1 fw-semibold text-uppercase">Tujuan Pengiriman:</span>
                            <strong class="text-dark d-block fs-6 mb-1">{{ $sale->customer_name }}</strong>
                            <div class="text-muted">{{ $sale->shipping_address ?: 'Alamat tidak dicantumkan' }}</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3 bg-light rounded-3 border h-100 small">
                            <span class="text-muted d-block mb-1 fw-semibold text-uppercase">Metode & Referensi:</span>
                            <div class="fw-bold text-dark fs-6 mb-1">
                                <i class="bi {{ $paymentDetails['icon'] ?? 'bi-credit-card' }} me-1 text-primary"></i>
                                {{ $paymentDetails['name'] ?? strtoupper($sale->payment_method) }}
                            </div>
                            @if($sale->payment_reference)
                                <div class="text-muted">Kode Referensi: <code>{{ $sale->payment_reference }}</code></div>
                            @endif
                            @if($sale->paid_at)
                                <div class="text-success mt-1"><i class="bi bi-check-circle me-1"></i> Terverifikasi: {{ \App\Support\FormatHelper::indonesianDate($sale->paid_at, true) }}</div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Items Table -->
                <div class="table-responsive rounded-3 border mb-4">
                    <table class="table align-middle mb-0 small">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-3" style="width: 50px;">No.</th>
                                <th>Item Outfit</th>
                                <th>Ukuran / Warna</th>
                                <th class="text-end">Harga Satuan</th>
                                <th class="text-center">Jumlah</th>
                                <th class="text-end pe-3">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($sale->items as $index => $item)
                            <tr>
                                <td class="ps-3">{{ $index + 1 }}</td>
                                <td>
                                    <div class="fw-bold text-dark">{{ $item->product->name ?? 'Produk' }}</div>
                                    <div class="text-muted" style="font-size: 0.72rem;">SKU: {{ $item->product->sku ?? '-' }}</div>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border">{{ $item->selected_size ?: 'All Size' }}</span>
                                    <span class="badge bg-light text-secondary border">{{ $item->selected_color ?: 'Standar' }}</span>
                                </td>
                                <td class="text-end text-muted">{{ \App\Support\FormatHelper::rupiah($item->unit_price) }}</td>
                                <td class="text-center fw-bold text-dark">{{ $item->quantity }} pcs</td>
                                <td class="text-end fw-bold text-dark pe-3">{{ \App\Support\FormatHelper::rupiah($item->subtotal) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="table-light">
                            <tr>
                                <td colspan="4" class="text-end fw-bold">Total Pembayaran:</td>
                                <td class="text-center fw-bold text-primary">{{ $sale->items->sum('quantity') }} pcs</td>
                                <td class="text-end fw-extrabold text-danger fs-6 pe-3">{{ \App\Support\FormatHelper::rupiah($sale->total) }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <div class="text-center pt-3 border-top d-print-none">
                    <a href="{{ route('shop.index') }}" class="btn btn-brand-primary px-4 py-2 rounded-pill fw-semibold">
                        <i class="bi bi-bag-heart me-1"></i> Belanja Koleksi Lainnya
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
