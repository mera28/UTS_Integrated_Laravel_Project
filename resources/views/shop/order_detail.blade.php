@extends('layouts.shop')

@section('title', 'Detail Pesanan ' . $sale->sale_number . ' - Zahiyyah\'s Fit Bali Boutique')

@section('content')
<div class="container py-4 py-md-5">
    <div class="row justify-content-center">
        <div class="col-lg-9">
            <!-- Navigation -->
            <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2 d-print-none">
                <a href="{{ route('shop.orders') }}" class="btn btn-sand btn-sm">
                    <i class="bi bi-arrow-left me-1"></i> Kembali ke Pesanan Saya
                </a>
                <div class="d-flex align-items-center gap-2">
                    <button type="button" class="btn btn-outline-brand btn-sm" onclick="window.print()">
                        <i class="bi bi-printer me-1"></i> Cetak Bukti Pesanan
                    </button>
                    @if($sale->status !== 'cancelled')
                        <form action="{{ route('orders.cancel', $sale) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan pesanan {{ $sale->sale_number }}? Stok produk akan otomatis dikembalikan ke inventori toko.')">
                            @csrf
                            <button type="submit" class="btn btn-outline-danger btn-sm">
                                <i class="bi bi-x-circle me-1"></i> Batalkan Pesanan
                            </button>
                        </form>
                    @endif
                </div>
            </div>

            <!-- Receipt Card -->
            <div class="card-bali p-4 p-md-5 bg-white">
                @if($sale->status === 'cancelled')
                    <div class="alert alert-danger border-0 rounded-4 p-3 mb-4 d-flex align-items-center gap-3" style="background-color: #FAF0F0; border: 1px solid #E8C1C1 !important;" role="alert">
                        <i class="bi bi-x-circle-fill fs-3 text-danger"></i>
                        <div>
                            <div class="fw-bold text-danger">Status: Pesanan Dibatalkan (Cancelled)</div>
                            <div class="small text-muted">Pesanan ini telah dibatalkan dan seluruh kuantitas pakaian telah otomatis dikembalikan ke inventori toko.</div>
                        </div>
                    </div>
                @endif

                <div class="d-flex justify-content-between align-items-start border-bottom pb-4 mb-4 flex-wrap gap-3" style="border-color: var(--bali-border-light) !important;">
                    <div class="d-flex align-items-center gap-3">
                        <div class="brand-badge-icon" style="width: 44px; height: 44px; font-size: 1.25rem;">
                            <i class="bi bi-flower1"></i>
                        </div>
                        <div>
                            <h4 class="fw-bold mb-0" style="font-family: var(--font-serif); font-size: 1.6rem; color: var(--bali-espresso);">Zahiyyah's Fit</h4>
                            <span class="text-muted small" style="font-size: 0.75rem; letter-spacing: 0.1em; text-transform: uppercase;">Bali &bull; Modest &amp; Resort Boutique</span>
                        </div>
                    </div>
                    <div class="text-md-end">
                        <span class="badge mb-1" style="background-color: var(--bali-bg-linen); color: var(--bali-taupe); border: 1px solid var(--bali-border); font-size: 0.72rem; letter-spacing: 0.08em;">INVOICE BUTIK</span>
                        <h5 class="fw-bold mb-1" style="font-family: var(--font-serif); color: var(--bali-espresso);">{{ $sale->sale_number }}</h5>
                        <div class="small text-muted">{{ \App\Support\FormatHelper::indonesianDate($sale->sold_at, true) }}</div>
                        <div class="mt-2">
                            @if($sale->status === 'cancelled')
                                <span class="badge px-3 py-1" style="background-color: #FAF0F0; color: #A33A3A; border: 1px solid #E8C1C1;">
                                    <i class="bi bi-x-circle me-1"></i> Dibatalkan (Cancelled)
                                </span>
                            @elseif($sale->payment_status === 'paid')
                                <span class="badge px-3 py-1" style="background-color: #EBF5EE; color: #1E6B37; border: 1px solid #C4E2CE;">
                                    <i class="bi bi-check-circle me-1"></i> Pembayaran Lunas
                                </span>
                            @elseif($sale->payment_status === 'refunded')
                                <span class="badge px-3 py-1" style="background-color: #FAF0F0; color: #A33A3A; border: 1px solid #E8C1C1;">
                                    <i class="bi bi-arrow-counterclockwise me-1"></i> Transaksi Refunded
                                </span>
                            @else
                                <a href="{{ route('orders.payment', $sale) }}" class="badge text-decoration-none px-3 py-1" style="background-color: var(--bali-tan); color: #FFFFFF;">
                                    <i class="bi bi-clock me-1"></i> Bayar Sekarang &rarr;
                                </a>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Info Grid -->
                <div class="row g-4 mb-4">
                    <div class="col-md-6">
                        <div class="p-3 rounded-3 border h-100 small surface-ivory" style="border-color: var(--bali-border) !important;">
                            <span class="text-muted d-block mb-1 fw-semibold text-uppercase" style="font-size: 0.72rem; letter-spacing: 0.05em;">Tujuan Pengiriman:</span>
                            <strong class="d-block fs-6 mb-1" style="color: var(--bali-espresso);">{{ $sale->customer_name }}</strong>
                            <div class="text-muted">{{ $sale->shipping_address ?: 'Alamat tidak dicantumkan' }}</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3 rounded-3 border h-100 small surface-ivory" style="border-color: var(--bali-border) !important;">
                            <span class="text-muted d-block mb-1 fw-semibold text-uppercase" style="font-size: 0.72rem; letter-spacing: 0.05em;">Metode &amp; Referensi:</span>
                            <div class="fw-bold fs-6 mb-1" style="color: var(--bali-espresso);">
                                <i class="bi {{ $paymentDetails['icon'] ?? 'bi-credit-card' }} me-1" style="color: var(--bali-tan);"></i>
                                {{ $paymentDetails['name'] ?? strtoupper($sale->payment_method) }}
                            </div>
                            @if($sale->payment_reference)
                                <div class="text-muted">Kode Referensi: <code>{{ $sale->payment_reference }}</code></div>
                            @endif
                            @if($sale->paid_at)
                                <div class="mt-1" style="color: #1E6B37;"><i class="bi bi-check-circle me-1"></i> Terverifikasi: {{ \App\Support\FormatHelper::indonesianDate($sale->paid_at, true) }}</div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Items Table -->
                <div class="table-responsive rounded-3 border mb-4" style="border-color: var(--bali-border) !important;">
                    <table class="table align-middle mb-0 small w-100">
                        <thead>
                            <tr>
                                <th class="ps-3" style="width: 50px;">No.</th>
                                <th>Koleksi Outfit</th>
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
                                    <div class="fw-semibold" style="color: var(--bali-espresso); font-family: var(--font-serif); font-size: 1.05rem;">{{ $item->product->name ?? 'Produk' }}</div>
                                    <div class="text-muted" style="font-size: 0.72rem;">SKU: {{ $item->product->sku ?? '-' }}</div>
                                </td>
                                <td>
                                    <span class="badge" style="background-color: var(--bali-bg-linen); color: var(--bali-taupe); border: 1px solid var(--bali-border);">{{ $item->selected_size ?: 'All Size' }}</span>
                                    <span class="badge" style="background-color: var(--bali-bg-linen); color: var(--bali-taupe); border: 1px solid var(--bali-border);">{{ $item->selected_color ?: 'Standar' }}</span>
                                </td>
                                <td class="text-end text-muted">{{ \App\Support\FormatHelper::rupiah($item->unit_price) }}</td>
                                <td class="text-center fw-bold" style="color: var(--bali-espresso);">{{ $item->quantity }} pcs</td>
                                <td class="text-end fw-bold pe-3" style="color: var(--bali-espresso);">{{ \App\Support\FormatHelper::rupiah($item->subtotal) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="surface-sand">
                                <td colspan="4" class="text-end fw-bold" style="color: var(--bali-espresso);">Total Pembayaran:</td>
                                <td class="text-center fw-bold" style="color: var(--bali-tan-dark);">{{ $sale->items->sum('quantity') }} pcs</td>
                                <td class="text-end fw-extrabold fs-6 pe-3" style="color: var(--bali-espresso); font-weight: 800;">{{ \App\Support\FormatHelper::rupiah($sale->total) }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <div class="text-center pt-3 border-top d-print-none" style="border-color: var(--bali-border-light) !important;">
                    <a href="{{ route('shop.index') }}" class="btn btn-brand-primary px-4 py-2">
                        <i class="bi bi-compass me-1"></i> Jelajahi Koleksi Lainnya
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
