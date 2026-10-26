@extends('layouts.app')

@section('title', 'Nota Penjualan ' . $sale->sale_number)
@section('page_title', 'Invoice Penjualan & Struk Pembayaran')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-9">
        <!-- Action Header Bar -->
        <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2 d-print-none">
            <a href="{{ route('sales.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar Penjualan
            </a>
            <div class="d-flex gap-2">
                <button type="button" class="btn btn-light border" onclick="window.print()">
                    <i class="bi bi-printer me-1"></i> Cetak Struk (Print)
                </button>
                @if(auth()->user()->isAdmin() && $sale->status !== 'cancelled')
                    <button type="button" class="btn btn-outline-danger" 
                            onclick="confirmAction('cancel-sale-form', 'PERINGATAN: Apakah Anda yakin ingin membatalkan transaksi penjualan [{{ $sale->sale_number }}]? Seluruh stok item pakaian yang telah terjual akan dipulihkan/ditambahkan kembali ke inventori gudang.')">
                        <i class="bi bi-x-circle me-1"></i> Batalkan Transaksi (Pulihkan Stok)
                    </button>
                    <form id="cancel-sale-form" action="{{ route('sales.cancel', $sale) }}" method="POST" class="d-none">
                        @csrf
                        @method('PATCH')
                    </form>
                @endif
            </div>
        </div>

        <!-- Receipt Paper Card -->
        <div class="card card-master p-4 p-md-5 bg-white border shadow-sm">
            <!-- Header Invoice -->
            <div class="d-flex justify-content-between align-items-start border-bottom pb-4 mb-4 flex-wrap gap-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="brand-logo-icon" style="width: 50px; height: 50px; font-size: 1.5rem;">
                        <i class="bi bi-gem"></i>
                    </div>
                    <div>
                        <h4 class="fw-bold mb-0 text-dark">Zahiyyah's Fit</h4>
                        <span class="text-muted small">Modest Fashion Store & Boutique</span>
                        <div class="small text-muted mt-1"><i class="bi bi-geo-alt"></i> Banda Aceh, Indonesia</div>
                    </div>
                </div>
                <div class="text-md-end">
                    <span class="badge bg-light text-secondary border px-3 py-1 mb-2 text-uppercase" style="letter-spacing: 1px;">
                        Nota Penjualan Resmi
                    </span>
                    <h5 class="fw-bold text-dark mb-1">{{ $sale->sale_number }}</h5>
                    <div class="text-muted small">
                        Waktu: {{ \App\Support\FormatHelper::indonesianDate($sale->sold_at, true) }}
                    </div>
                    <div class="mt-2">
                        @if($sale->status === 'completed')
                            <span class="badge bg-success bg-opacity-10 text-success border border-success px-3 py-1">
                                <i class="bi bi-check-circle me-1"></i> Transaksi Berhasil
                            </span>
                        @elseif($sale->status === 'cancelled')
                            <span class="badge bg-danger bg-opacity-10 text-danger border border-danger px-3 py-1">
                                <i class="bi bi-x-circle me-1"></i> Dibatalkan / Refund
                            </span>
                        @else
                            <span class="badge bg-warning bg-opacity-10 text-warning border border-warning px-3 py-1">
                                <i class="bi bi-clock me-1"></i> Menunggu Pembayaran
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Customer & Payment Details -->
            <div class="row g-4 mb-4">
                <div class="col-md-6">
                    <div class="p-3 bg-light rounded-3 border h-100">
                        <span class="text-muted small text-uppercase fw-semibold d-block mb-2">Informasi Pembeli:</span>
                        <h6 class="fw-bold text-dark mb-1">{{ $sale->customer_name ?: ($sale->user->name ?? 'Pelanggan') }}</h6>
                        @if($sale->shipping_address)
                            <div class="small text-muted"><i class="bi bi-geo-alt me-1"></i> {{ $sale->shipping_address }}</div>
                        @endif
                        <div class="small text-muted mt-1"><i class="bi bi-person-badge me-1"></i> Kasir / Operator: {{ $sale->user->name ?? '-' }}</div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="p-3 bg-light rounded-3 border h-100">
                        <span class="text-muted small text-uppercase fw-semibold d-block mb-2">Metode & Referensi Pembayaran:</span>
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <span class="badge {{ $paymentDetails['badge_class'] ?? 'bg-secondary' }} px-3 py-1">
                                <i class="bi {{ $paymentDetails['icon'] ?? 'bi-credit-card' }} me-1"></i>
                                {{ $paymentDetails['name'] ?? strtoupper($sale->payment_method) }}
                            </span>
                        </div>
                        @if($sale->payment_reference)
                            <div class="small text-dark fw-semibold mb-1">
                                Ref/VA: <code>{{ $sale->payment_reference }}</code>
                            </div>
                        @endif
                        <div class="small text-muted">
                            Status Bayar: 
                            <strong class="{{ $sale->payment_status === 'paid' ? 'text-success' : 'text-danger' }}">
                                {{ strtoupper($sale->payment_status) }}
                            </strong>
                            @if($sale->paid_at)
                                ({{ \App\Support\FormatHelper::indonesianDate($sale->paid_at, true) }})
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Items Table -->
            <div class="table-responsive rounded-3 border mb-4">
                <table class="table table-custom align-middle mb-0 w-100">
                    <thead>
                        <tr>
                            <th style="width: 50px;">No.</th>
                            <th>Item Pakaian</th>
                            <th>Varian Ukuran / Warna</th>
                            <th class="text-end">Harga Satuan</th>
                            <th class="text-center">Qty</th>
                            <th class="text-end">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($sale->items as $index => $item)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>
                                <div class="fw-bold text-dark">{{ $item->product->name ?? 'Produk Outfit' }}</div>
                                <div class="small text-muted">SKU: <code>{{ $item->product->sku ?? '-' }}</code></div>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark border me-1">{{ $item->selected_size ?: 'All Size' }}</span>
                                <span class="badge bg-light text-secondary border">{{ $item->selected_color ?: 'Standar' }}</span>
                            </td>
                            <td class="text-end text-muted">{{ \App\Support\FormatHelper::rupiah($item->unit_price) }}</td>
                            <td class="text-center fw-bold text-dark">{{ $item->quantity }} pcs</td>
                            <td class="text-end fw-bold text-dark">{{ \App\Support\FormatHelper::rupiah($item->subtotal) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="table-light">
                        <tr>
                            <td colspan="4" class="text-end fw-bold">Total Kuantitas Pakaian:</td>
                            <td class="text-center fw-extrabold text-primary fs-6">{{ $sale->items->sum('quantity') }} pcs</td>
                            <td class="text-end fw-extrabold text-danger fs-5">{{ \App\Support\FormatHelper::rupiah($sale->total) }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <div class="text-center text-muted small pt-3 border-top">
                Terima kasih telah berbelanja di <strong>Zahiyyah's Fit Official Store</strong>.<br>
                Barang yang sudah dibeli dapat ditukar ukuran dalam waktu 2x24 jam dengan menyertakan nota ini dan tag pakaian utuh.
            </div>
        </div>
    </div>
</div>
@endsection
