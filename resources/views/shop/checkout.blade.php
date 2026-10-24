@extends('layouts.shop')

@section('title', 'Checkout Pembayaran')

@section('content')
<div class="container py-4 py-md-5">
    <div class="mb-4">
        <h4 class="fw-bold text-dark mb-1">Checkout & Pengiriman Pesanan</h4>
        <span class="text-muted small">Lengkapi alamat pengiriman dan pilih metode pembayaran resmi</span>
    </div>

    <form action="{{ route('checkout.process') }}" method="POST">
        @csrf
        <div class="row g-4">
            <!-- Shipping & Payment Method Column -->
            <div class="col-lg-7">
                <!-- Shipping Details Card -->
                <div class="card border-0 shadow-sm rounded-4 bg-white p-4 mb-4">
                    <h5 class="fw-bold text-dark mb-3"><i class="bi bi-geo-alt me-2 text-danger"></i> 1. Alamat Pengiriman</h5>
                    
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label for="customer_name" class="form-label-custom">Nama Lengkap Penerima <span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-custom @error('customer_name') is-invalid @enderror" id="customer_name" name="customer_name" value="{{ old('customer_name', $user->name ?? '') }}" required>
                            @error('customer_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="customer_phone" class="form-label-custom">Nomor Telepon / WhatsApp <span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-custom @error('customer_phone') is-invalid @enderror" id="customer_phone" name="customer_phone" value="{{ old('customer_phone', $user->phone ?? '') }}" placeholder="0812xxxxxxxx" required>
                            @error('customer_phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-2">
                        <label for="shipping_address" class="form-label-custom">Alamat Lengkap Tujuan <span class="text-danger">*</span></label>
                        <textarea class="form-control form-control-custom @error('shipping_address') is-invalid @enderror" id="shipping_address" name="shipping_address" rows="3" placeholder="Nama Jalan, RT/RW, Kelurahan, Kecamatan, Kota/Kabupaten, Kode Pos..." required>{{ old('shipping_address', $user->address ?? '') }}</textarea>
                        @error('shipping_address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Payment Method Card -->
                <div class="card border-0 shadow-sm rounded-4 bg-white p-4">
                    <h5 class="fw-bold text-dark mb-3"><i class="bi bi-credit-card me-2 text-danger"></i> 2. Pilih Metode Pembayaran (Payment Gateway)</h5>
                    <p class="small text-muted mb-3">Pilih saluran pembayaran resmi yang didukung Zahiyyah's Fit:</p>

                    <div class="d-flex flex-column gap-2">
                        <!-- QRIS -->
                        <div class="p-3 border rounded-3 d-flex align-items-center gap-3">
                            <input class="form-check-input mt-0" type="radio" name="payment_method" id="pay_qris" value="qris" checked>
                            <label class="form-check-label w-100 d-flex justify-content-between align-items-center cursor-pointer" for="pay_qris">
                                <div>
                                    <div class="fw-bold text-dark"><i class="bi bi-qr-code text-warning me-2 fs-5"></i> QRIS Interaktif</div>
                                    <span class="small text-muted">BCA Mobile, Livin, GoPay, ShopeePay, OVO, DANA</span>
                                </div>
                                <span class="badge bg-warning text-dark">INSTANT QR</span>
                            </label>
                        </div>

                        <!-- Virtual Account BCA -->
                        <div class="p-3 border rounded-3 d-flex align-items-center gap-3">
                            <input class="form-check-input mt-0" type="radio" name="payment_method" id="pay_va_bca" value="va_bca" {{ old('payment_method') == 'va_bca' ? 'checked' : '' }}>
                            <label class="form-check-label w-100 d-flex justify-content-between align-items-center cursor-pointer" for="pay_va_bca">
                                <div>
                                    <div class="fw-bold text-dark"><i class="bi bi-bank text-primary me-2 fs-5"></i> BCA Virtual Account</div>
                                    <span class="small text-muted">Transfer otomatis melalui ATM / m-BCA / KlikBCA</span>
                                </div>
                                <span class="badge bg-primary">VIRTUAL ACCOUNT</span>
                            </label>
                        </div>

                        <!-- Virtual Account Mandiri -->
                        <div class="p-3 border rounded-3 d-flex align-items-center gap-3">
                            <input class="form-check-input mt-0" type="radio" name="payment_method" id="pay_va_mandiri" value="va_mandiri" {{ old('payment_method') == 'va_mandiri' ? 'checked' : '' }}>
                            <label class="form-check-label w-100 d-flex justify-content-between align-items-center cursor-pointer" for="pay_va_mandiri">
                                <div>
                                    <div class="fw-bold text-dark"><i class="bi bi-bank text-info me-2 fs-5"></i> Mandiri Virtual Account</div>
                                    <span class="small text-muted">Pembayaran instan via Livin by Mandiri</span>
                                </div>
                                <span class="badge bg-info text-dark">VIRTUAL ACCOUNT</span>
                            </label>
                        </div>

                        <!-- Virtual Account BRI & BSI -->
                        <div class="p-3 border rounded-3 d-flex align-items-center gap-3">
                            <input class="form-check-input mt-0" type="radio" name="payment_method" id="pay_va_bri" value="va_bri" {{ old('payment_method') == 'va_bri' ? 'checked' : '' }}>
                            <label class="form-check-label w-100 d-flex justify-content-between align-items-center cursor-pointer" for="pay_va_bri">
                                <div>
                                    <div class="fw-bold text-dark"><i class="bi bi-bank text-primary me-2 fs-5"></i> BRI Virtual Account (BRIVA)</div>
                                    <span class="small text-muted">Transfer via BRImo / ATM BRI</span>
                                </div>
                                <span class="badge bg-primary">BRIVA</span>
                            </label>
                        </div>

                        <div class="p-3 border rounded-3 d-flex align-items-center gap-3">
                            <input class="form-check-input mt-0" type="radio" name="payment_method" id="pay_va_bsi" value="va_bsi" {{ old('payment_method') == 'va_bsi' ? 'checked' : '' }}>
                            <label class="form-check-label w-100 d-flex justify-content-between align-items-center cursor-pointer" for="pay_va_bsi">
                                <div>
                                    <div class="fw-bold text-dark"><i class="bi bi-bank text-success me-2 fs-5"></i> BSI Virtual Account</div>
                                    <span class="small text-muted">Bank Syariah Indonesia (BSI Mobile)</span>
                                </div>
                                <span class="badge bg-success">BSI VA</span>
                            </label>
                        </div>

                        <!-- ShopeePay -->
                        <div class="p-3 border rounded-3 d-flex align-items-center gap-3">
                            <input class="form-check-input mt-0" type="radio" name="payment_method" id="pay_shopeepay" value="ewallet_shopeepay" {{ old('payment_method') == 'ewallet_shopeepay' ? 'checked' : '' }}>
                            <label class="form-check-label w-100 d-flex justify-content-between align-items-center cursor-pointer" for="pay_shopeepay">
                                <div>
                                    <div class="fw-bold text-dark"><i class="bi bi-wallet2 text-danger me-2 fs-5"></i> ShopeePay</div>
                                    <span class="small text-muted">Verifikasi instan via aplikasi ShopeePay</span>
                                </div>
                                <span class="badge bg-danger">E-WALLET</span>
                            </label>
                        </div>

                        <!-- GoPay -->
                        <div class="p-3 border rounded-3 d-flex align-items-center gap-3">
                            <input class="form-check-input mt-0" type="radio" name="payment_method" id="pay_gopay" value="ewallet_gopay" {{ old('payment_method') == 'ewallet_gopay' ? 'checked' : '' }}>
                            <label class="form-check-label w-100 d-flex justify-content-between align-items-center cursor-pointer" for="pay_gopay">
                                <div>
                                    <div class="fw-bold text-dark"><i class="bi bi-wallet2 text-success me-2 fs-5"></i> GoPay</div>
                                    <span class="small text-muted">Pembayaran cepat terintegrasi Gojek / GoPay</span>
                                </div>
                                <span class="badge bg-success">E-WALLET</span>
                            </label>
                        </div>
                    </div>
                    @error('payment_method')
                        <div class="text-danger small mt-2">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Order Review Sidebar -->
            <div class="col-lg-5">
                <div class="card border-0 shadow-sm rounded-4 bg-white p-4 sticky-top" style="top: 100px;">
                    <h5 class="fw-bold text-dark mb-3">Rincian Belanja ({{ $totals['total_items'] }} item)</h5>

                    <div class="d-flex flex-column gap-3 mb-4 max-h-80 overflow-auto" style="max-height: 280px;">
                        @foreach($cart as $item)
                        <div class="d-flex align-items-center gap-3">
                            <img src="{{ $item['image_url'] ?? 'https://images.unsplash.com/photo-1515886657613-9f3515b0c78f?q=80&w=200&auto=format&fit=crop' }}" 
                                 class="rounded-3 border object-fit-cover" 
                                 style="width: 50px; height: 50px;" 
                                 alt="{{ $item['name'] }}">
                            <div class="flex-grow-1">
                                <h6 class="fw-semibold mb-0 text-dark small text-truncate" style="max-width: 170px;">{{ $item['name'] }}</h6>
                                <span class="text-muted" style="font-size: 0.72rem;">{{ $item['size'] }} | {{ $item['color'] }} &times; {{ $item['quantity'] }}</span>
                            </div>
                            <span class="fw-bold text-dark small">{{ \App\Support\FormatHelper::rupiah($item['subtotal']) }}</span>
                        </div>
                        @endforeach
                    </div>

                    <div class="border-top pt-3">
                        <div class="d-flex justify-content-between mb-2 small text-muted">
                            <span>Subtotal Belanja:</span>
                            <span>{{ \App\Support\FormatHelper::rupiah($totals['grand_total']) }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2 small text-muted">
                            <span>Biaya Pengiriman:</span>
                            <span class="text-success fw-bold">GRATIS</span>
                        </div>
                        <div class="d-flex justify-content-between mb-3 small text-muted">
                            <span>Biaya Layanan Gateway:</span>
                            <span class="text-success fw-bold">Rp 0 (Promo)</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between align-items-baseline mb-4">
                            <span class="fw-bold text-dark">Total Pembayaran:</span>
                            <span class="fw-extrabold text-danger fs-4" style="font-weight: 800;">
                                {{ \App\Support\FormatHelper::rupiah($totals['grand_total']) }}
                            </span>
                        </div>

                        <button type="submit" class="btn btn-brand-primary w-100 py-3 rounded-pill fw-bold fs-6">
                            <i class="bi bi-shield-check me-1"></i> Buat Pesanan & Bayar Sekarang
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
