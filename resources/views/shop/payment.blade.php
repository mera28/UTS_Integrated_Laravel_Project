@extends('layouts.shop')

@section('title', 'Pembayaran Pesanan ' . $sale->sale_number)

@section('content')
<div class="container py-4 py-md-5">
    <div class="row justify-content-center">
        <div class="col-lg-7">
            <!-- Gateway Card -->
            <div class="card border-0 shadow-sm rounded-4 bg-white overflow-hidden mb-4">
                <!-- Gateway Header -->
                <div class="p-4 text-center border-bottom" style="background: linear-gradient(135deg, #FFF6F7 0%, #FAECEE 100%);">
                    <span class="badge bg-white text-danger border px-3 py-1 rounded-pill small fw-bold mb-2">
                        <i class="bi bi-shield-lock-fill me-1"></i> Payment Gateway Interaktif Terverifikasi
                    </span>
                    <h5 class="fw-bold text-dark mb-1">Menunggu Pembayaran Pesanan</h5>
                    <div class="text-muted small">Nomor Transaksi: <strong>{{ $sale->sale_number }}</strong></div>

                    <!-- Countdown Timer -->
                    <div class="mt-3 d-inline-flex align-items-center gap-2 bg-white px-3 py-2 rounded-pill border shadow-sm">
                        <i class="bi bi-hourglass-split text-danger fs-6"></i>
                        <span class="small text-muted">Sisa Waktu Bayar:</span>
                        <strong class="text-danger fw-extrabold" id="paymentTimer">23:59:59</strong>
                    </div>
                </div>

                <div class="p-4 p-md-5">
                    <!-- Total Bill Box -->
                    <div class="p-3 bg-light rounded-4 text-center mb-4 border">
                        <span class="small text-muted d-block mb-1">Total Tagihan Pakaian:</span>
                        <h2 class="display-6 fw-extrabold text-danger mb-0" style="font-weight: 800;">
                            {{ \App\Support\FormatHelper::rupiah($sale->total) }}
                        </h2>
                    </div>

                    <!-- Payment Method Specific Box -->
                    <div class="card border rounded-4 p-4 text-center mb-4 bg-white shadow-sm">
                        <div class="d-flex align-items-center justify-content-center gap-2 mb-3">
                            <span class="badge {{ $paymentDetails['badge_class'] ?? 'bg-secondary' }} px-3 py-1 fs-6">
                                <i class="bi {{ $paymentDetails['icon'] ?? 'bi-credit-card' }} me-1"></i>
                                {{ $paymentDetails['name'] }}
                            </span>
                        </div>

                        @if($sale->payment_method === 'qris')
                            <!-- QRIS Code Mock Box -->
                            <div class="p-3 d-inline-block bg-white border border-2 border-dark rounded-4 shadow-sm mx-auto mb-3">
                                <div class="bg-dark text-white py-1 px-3 rounded-2 small fw-bold mb-2">
                                    <i class="bi bi-qr-code me-1"></i> QRIS STANDAR INDONESIA
                                </div>
                                <!-- Visual QR Pattern representation -->
                                <div class="p-3 bg-light rounded-3 d-flex align-items-center justify-content-center mx-auto" style="width: 220px; height: 220px;">
                                    <div class="text-center">
                                        <i class="bi bi-qr-code text-dark display-1"></i>
                                        <div class="small fw-bold text-dark mt-1" style="font-size: 0.65rem;">SCAN DENGAN APLIKASI APAPUN</div>
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
                            <div class="p-3 bg-light rounded-3 d-flex align-items-center justify-content-between border mb-2 max-w-sm mx-auto">
                                <span class="fs-4 fw-extrabold text-primary font-monospace" id="vaNumberDisplay">{{ $sale->payment_reference }}</span>
                                <button type="button" class="btn btn-sm btn-outline-primary rounded-pill px-3" onclick="copyToClipboard('{{ $sale->payment_reference }}', 'Nomor Virtual Account berhasil disalin!')">
                                    <i class="bi bi-clipboard me-1"></i> Salin
                                </button>
                            </div>
                            <div class="small text-muted">Atas Nama: <strong>Zahiyyah's Fit Store</strong></div>

                        @else
                            <!-- E-Wallet Box -->
                            <span class="small text-muted d-block mb-1">Kode Transaksi E-Wallet:</span>
                            <div class="p-3 bg-light rounded-3 d-inline-block border mb-2 font-monospace fw-bold fs-5 text-dark">
                                {{ $sale->payment_reference }}
                            </div>
                            <div class="small text-muted">Buka aplikasi E-Wallet Anda untuk mengonfirmasi tagihan.</div>
                        @endif
                    </div>

                    <!-- Accordion Instructions -->
                    <div class="accordion mb-4" id="paymentInstructionAccordion">
                        <div class="accordion-item border rounded-3 overflow-hidden">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed fw-semibold small bg-light" type="button" data-bs-toggle="collapse" data-bs-target="#instructionCollapse">
                                    <i class="bi bi-info-circle me-2 text-primary"></i> Panduan Cara Pembayaran
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

                    <!-- Interactive Simulation Action Button -->
                    <div class="card bg-success bg-opacity-10 border border-success p-4 rounded-4 text-center mb-3">
                        <div class="fw-bold text-success mb-1">
                            <i class="bi bi-lightning-charge-fill me-1"></i> Uji Coba Simulasi Payment Gateway
                        </div>
                        <p class="small text-muted mb-3">
                            Tekan tombol di bawah untuk menyimulasikan notifikasi webhook pembayaran lunas dari bank / payment gateway:
                        </p>
                        <form action="{{ route('orders.simulate-pay', $sale) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-success py-3 px-4 rounded-pill fw-bold shadow-sm w-100 fs-6">
                                <i class="bi bi-check2-circle me-1"></i> Simulasikan Pembayaran Berhasil (Bayar Sekarang)
                            </button>
                        </form>
                    </div>

                    <div class="text-center">
                        <a href="{{ route('shop.orders') }}" class="text-secondary small text-decoration-none">
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
