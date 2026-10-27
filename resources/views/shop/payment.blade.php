@extends('layouts.shop')

@section('title', 'Pembayaran Pesanan ' . $sale->sale_number . ' - Zahiyyah\'s Fit Bali Boutique')

@section('content')
<div class="container py-4 py-md-5">
    <div class="row justify-content-center">
        <div class="col-lg-7">
            <!-- Gateway Card -->
            <div class="card-bali overflow-hidden mb-4">
                <!-- Gateway Header (Warm Linen Surface) -->
                <div class="p-4 text-center border-bottom surface-sand" style="border-radius: 20px 20px 0 0; border-color: var(--bali-border) !important;">
                    @if($sale->status === 'cancelled')
                        <span class="badge px-3 py-1 rounded-pill small fw-semibold mb-2" style="background-color: #FAF0F0; color: #A33A3A; border: 1px solid #E8C1C1;">
                            <i class="bi bi-x-circle me-1"></i> Dibatalkan (Cancelled)
                        </span>
                        <h4 class="fw-bold mb-1" style="font-family: var(--font-serif); font-size: 1.8rem; color: #A33A3A;">
                            Pesanan Telah Dibatalkan
                        </h4>
                        <div class="text-muted small">Nomor Transaksi: <strong>{{ $sale->sale_number }}</strong> &bull; Stok produk telah dikembalikan ke inventori</div>
                    @else
                        <span class="badge px-3 py-1 rounded-pill small fw-semibold mb-2" style="background-color: #FFFFFF; color: var(--bali-tan-dark); border: 1px solid var(--bali-border);">
                            <i class="bi bi-shield-lock me-1"></i> Payment Gateway Interaktif Terverifikasi
                        </span>
                        <h4 class="fw-bold mb-1" style="font-family: var(--font-serif); font-size: 1.8rem; color: var(--bali-espresso);">
                            Menunggu Konfirmasi Pembayaran
                        </h4>
                        <div class="text-muted small">Nomor Transaksi: <strong>{{ $sale->sale_number }}</strong></div>

                        <!-- Countdown Timer -->
                        <div class="mt-3 d-inline-flex align-items-center gap-2 px-3 py-2 rounded-pill bg-white border" style="border-color: var(--bali-border) !important;">
                            <i class="bi bi-hourglass-split" style="color: var(--bali-tan);"></i>
                            <span class="small text-muted">Sisa Waktu Bayar:</span>
                            <strong class="fw-bold" style="color: var(--bali-espresso);" id="paymentTimer">23:59:59</strong>
                        </div>
                    @endif
                </div>

                <div class="p-4 p-md-5 bg-white">
                    <!-- Total Bill Box -->
                    <div class="p-4 rounded-4 text-center mb-3 surface-ivory">
                        <span class="small d-block mb-1 text-muted" style="font-size: 0.72rem; letter-spacing: 0.08em; text-transform: uppercase;">Total Tagihan Busana:</span>
                        <h2 class="display-6 fw-extrabold mb-0" style="color: var(--bali-espresso); font-weight: 800; font-size: 2.2rem;">
                            {{ \App\Support\FormatHelper::rupiah($sale->total) }}
                        </h2>
                    </div>

                    <!-- Items Summary with Size and Color Badges -->
                    <div class="rounded-4 p-3 mb-4 surface-ivory border text-start" style="border-color: var(--bali-border-light) !important;">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="small fw-bold text-uppercase" style="letter-spacing: 0.05em; font-size: 0.75rem; color: var(--bali-taupe);">
                                <i class="bi bi-bag-check me-1" style="color: var(--bali-tan);"></i> Rincian Pesanan ({{ $sale->items->count() }} item):
                            </span>
                            <span class="badge bg-white text-muted border" style="font-size: 0.7rem;">Inv: {{ $sale->sale_number }}</span>
                        </div>
                        <div class="d-flex flex-column gap-2">
                            @foreach($sale->items as $item)
                            <div class="d-flex align-items-center justify-content-between p-2 rounded-3 bg-white border" style="border-color: var(--bali-border-light) !important;">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="rounded-circle d-flex align-items-center justify-content-center text-white flex-shrink-0" style="width: 26px; height: 26px; background-color: var(--bali-tan); font-size: 0.75rem;">
                                        {{ $loop->iteration }}
                                    </div>
                                    <div>
                                        <h6 class="mb-0 fw-semibold text-truncate small" style="color: var(--bali-espresso); max-width: 220px;">
                                            {{ $item->product->name ?? 'Outfit' }}
                                        </h6>
                                        <div class="d-flex gap-1 mt-1">
                                            <span class="badge" style="background-color: var(--bali-bg-linen); color: var(--bali-taupe); border: 1px solid var(--bali-border); font-size: 0.68rem;">Ukuran: {{ $item->selected_size ?: 'All Size' }}</span>
                                            <span class="badge" style="background-color: var(--bali-bg-linen); color: var(--bali-taupe); border: 1px solid var(--bali-border); font-size: 0.68rem;">Warna: {{ $item->selected_color ?: 'Standar' }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-end ps-2">
                                    <span class="small text-muted d-block" style="font-size: 0.7rem;">{{ $item->quantity }} &times; {{ \App\Support\FormatHelper::rupiah($item->unit_price) }}</span>
                                    <span class="fw-bold small" style="color: var(--bali-espresso);">{{ \App\Support\FormatHelper::rupiah($item->subtotal) }}</span>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Payment Method Specific Box -->
                    <div class="rounded-4 p-4 text-center mb-4 border surface-sand" style="border-color: var(--bali-border) !important;">
                        <div class="d-flex align-items-center justify-content-center gap-2 mb-3">
                            <span class="badge px-3 py-1 fs-6" style="background-color: var(--bali-tan); color: #FFFFFF;">
                                <i class="bi {{ $paymentDetails['icon'] ?? 'bi-credit-card' }} me-1"></i>
                                {{ $paymentDetails['name'] }}
                            </span>
                        </div>

                        @if($sale->payment_method === 'qris')
                            <!-- QRIS Code Mock Box -->
                            <div class="p-3 d-inline-block bg-white border rounded-4 mx-auto mb-3" style="border-color: var(--bali-border) !important; box-shadow: 0 8px 20px rgba(44, 34, 30, 0.04);">
                                <div class="py-1 px-3 rounded-2 small fw-bold mb-2 text-white" style="background-color: var(--bali-espresso); font-size: 0.75rem; letter-spacing: 0.05em;">
                                    <i class="bi bi-qr-code me-1"></i> QRIS STANDAR INDONESIA
                                </div>
                                <!-- Visual QR Pattern representation -->
                                <div class="p-3 rounded-3 d-flex align-items-center justify-content-center mx-auto surface-ivory" style="width: 220px; height: 220px;">
                                    <div class="text-center">
                                        <i class="bi bi-qr-code display-1" style="color: var(--bali-espresso);"></i>
                                        <div class="small fw-bold mt-1" style="font-size: 0.65rem; color: var(--bali-taupe); letter-spacing: 0.05em;">SCAN DENGAN APLIKASI PERBANKAN / E-WALLET</div>
                                    </div>
                                </div>
                                <div class="small text-muted mt-2 fw-semibold" style="font-size: 0.72rem;">
                                    NMID: ID1029384756 | Zahiyyah's Fit Official
                                </div>
                            </div>
                            <div class="small text-muted">
                                Referensi Transaksi: <code>{{ $sale->payment_reference }}</code>
                            </div>

                        @elseif(str_starts_with($sale->payment_method, 'va_'))
                            <!-- Virtual Account Number Box -->
                            <span class="small text-muted d-block mb-1">Nomor Virtual Account Resmi:</span>
                            <div class="p-3 rounded-3 d-flex align-items-center justify-content-between border mb-2 max-w-sm mx-auto bg-white" style="border-color: var(--bali-border) !important;">
                                <span class="fs-4 fw-extrabold font-monospace" style="color: var(--bali-espresso);" id="vaNumberDisplay">{{ $sale->payment_reference }}</span>
                                <button type="button" class="btn btn-sm btn-sand rounded-pill px-3" onclick="copyToClipboard('{{ $sale->payment_reference }}', 'Nomor Virtual Account berhasil disalin!')">
                                    <i class="bi bi-clipboard me-1"></i> Salin
                                </button>
                            </div>
                            <div class="small text-muted">Atas Nama: <strong style="color: var(--bali-espresso);">Zahiyyah's Fit Store</strong></div>

                        @else
                            <!-- E-Wallet Box -->
                            <span class="small text-muted d-block mb-1">Kode Transaksi E-Wallet:</span>
                            <div class="p-3 bg-white rounded-3 d-inline-block border mb-2 font-monospace fw-bold fs-5 text-dark" style="border-color: var(--bali-border) !important;">
                                {{ $sale->payment_reference }}
                            </div>
                            <div class="small text-muted">Buka aplikasi E-Wallet Anda untuk mengonfirmasi tagihan.</div>
                        @endif
                    </div>

                    <!-- Accordion Instructions -->
                    <div class="accordion mb-4" id="paymentInstructionAccordion">
                        <div class="accordion-item border rounded-3 overflow-hidden" style="border-color: var(--bali-border) !important;">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed fw-semibold small surface-ivory" type="button" data-bs-toggle="collapse" data-bs-target="#instructionCollapse">
                                    <i class="bi bi-info-circle me-2" style="color: var(--bali-tan);"></i> Panduan Cara Pembayaran
                                </button>
                            </h2>
                            <div id="instructionCollapse" class="accordion-collapse collapse" data-bs-parent="#paymentInstructionAccordion">
                                <div class="accordion-body small text-muted">
                                    <ol class="mb-0 ps-3 d-flex flex-column gap-2">
                                        @foreach($instructions as $inst)
                                            <li>{{ $inst }}</li>
                                        @endforeach
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($sale->status !== 'cancelled')
                        <!-- Interactive Simulation Action Button -->
                        <div class="card p-4 rounded-4 text-center mb-3 surface-sand" style="border: 1px dashed var(--bali-tan);">
                            <div class="fw-bold mb-1" style="color: var(--bali-tan-dark); font-family: var(--font-serif); font-size: 1.15rem;">
                                <i class="bi bi-lightning-charge me-1"></i> Uji Coba Simulasi Payment Gateway
                            </div>
                            <p class="small text-muted mb-3">
                                Tekan tombol di bawah untuk menyimulasikan notifikasi webhook pembayaran lunas dari bank / payment gateway:
                            </p>
                            <form action="{{ route('orders.simulate-pay', $sale) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-brand-primary py-3 px-4 w-100 fs-6">
                                    <i class="bi bi-check2-circle me-1"></i> Simulasikan Pembayaran Berhasil (Bayar Sekarang)
                                </button>
                            </form>
                        </div>

                        <!-- Cancel Order Button -->
                        <div class="text-center mb-3">
                            <form action="{{ route('orders.cancel', $sale) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan pesanan {{ $sale->sale_number }}? Stok produk akan otomatis dikembalikan ke inventori toko.')">
                                @csrf
                                <button type="submit" class="btn btn-outline-danger btn-sm px-4 py-2">
                                    <i class="bi bi-x-circle me-1"></i> Batalkan Pesanan Ini (Kembalikan Stok)
                                </button>
                            </form>
                        </div>
                    @else
                        <div class="alert alert-danger border-0 rounded-4 p-4 text-center mb-4" style="background-color: #FAF0F0; border: 1px solid #E8C1C1 !important;">
                            <i class="bi bi-x-circle-fill fs-2 text-danger d-block mb-2"></i>
                            <h5 class="fw-bold text-danger mb-1">Pesanan Telah Dibatalkan (Cancelled)</h5>
                            <p class="small text-muted mb-3">Pembayaran untuk transaksi ini tidak dapat diproses karena pesanan telah dibatalkan dan stok produk telah dipulihkan.</p>
                            <a href="{{ route('orders.show', $sale) }}" class="btn btn-sand btn-sm px-3">
                                <i class="bi bi-receipt me-1"></i> Lihat Invoice Transaksi
                            </a>
                        </div>
                    @endif

                    <div class="text-center">
                        <a href="{{ route('shop.orders') }}" class="small text-decoration-none" style="color: var(--bali-muted);">
                            <i class="bi bi-arrow-left me-1"></i> Cek Nanti di Menu Pesanan Saya
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Run 24-hours countdown (86400 seconds)
        startPaymentCountdown(86400, 'paymentTimer');
    });
</script>
@endpush
