@extends('layouts.shop')

@section('title', 'Checkout Pembayaran - Zahiyyah\'s Fit Bali Boutique')

@section('content')
<div class="container py-4 py-md-5">
    <div class="mb-4">
        <span class="text-uppercase small fw-semibold" style="letter-spacing: 0.15em; color: var(--bali-tan); font-size: 0.72rem;">Final Step</span>
        <h3 class="fw-bold mb-1" style="font-family: var(--font-serif); font-size: 2.1rem; color: var(--bali-espresso);">Checkout &amp; Pengiriman Pesanan</h3>
        <span class="text-muted small">Lengkapi alamat pengiriman dan pilih metode pembayaran resmi</span>
    </div>

    <form action="{{ route('checkout.process') }}" method="POST">
        @csrf
        <div class="row g-4">
            <!-- Shipping & Payment Method Column -->
            <div class="col-lg-7">
                <!-- Shipping Details Card -->
                <div class="card-bali p-4 mb-4">
                    <h5 class="fw-bold mb-3" style="font-family: var(--font-serif); font-size: 1.3rem; color: var(--bali-espresso);">
                        <i class="bi bi-geo-alt me-2" style="color: var(--bali-tan);"></i> 1. Alamat Pengiriman Butik
                    </h5>
                    
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
                <div class="card-bali p-4">
                    <h5 class="fw-bold mb-3" style="font-family: var(--font-serif); font-size: 1.3rem; color: var(--bali-espresso);">
                        <i class="bi bi-credit-card me-2" style="color: var(--bali-tan);"></i> 2. Pilih Metode Pembayaran (Payment Gateway)
                    </h5>
                    <p class="small text-muted mb-3">Pilih saluran pembayaran resmi yang didukung Zahiyyah's Fit:</p>

                    <div class="d-flex flex-column gap-2">
                        <!-- QRIS -->
                        <div class="p-3 border rounded-3 d-flex align-items-center gap-3 surface-ivory" style="border-color: var(--bali-border) !important;">
                            <input class="form-check-input mt-0" type="radio" name="payment_method" id="pay_qris" value="qris" checked>
                            <label class="form-check-label w-100 d-flex justify-content-between align-items-center cursor-pointer" for="pay_qris">
                                <div>
                                    <div class="fw-semibold text-dark"><i class="bi bi-qr-code me-2 fs-5" style="color: var(--bali-tan);"></i> QRIS Interaktif</div>
                                    <span class="small text-muted">BCA Mobile, Livin, GoPay, ShopeePay, OVO, DANA</span>
                                </div>
                                <span class="badge" style="background-color: var(--bali-bg-linen); color: var(--bali-tan-dark); border: 1px solid var(--bali-border);">INSTAN</span>
                            </label>
                        </div>

                        <!-- BCA Virtual Account -->
                        <div class="p-3 border rounded-3 d-flex align-items-center gap-3 surface-ivory" style="border-color: var(--bali-border) !important;">
                            <input class="form-check-input mt-0" type="radio" name="payment_method" id="pay_bca" value="va_bca" {{ old('payment_method') == 'va_bca' ? 'checked' : '' }}>
                            <label class="form-check-label w-100 d-flex justify-content-between align-items-center cursor-pointer" for="pay_bca">
                                <div>
                                    <div class="fw-semibold text-dark"><i class="bi bi-bank me-2 fs-5" style="color: var(--bali-tan);"></i> BCA Virtual Account</div>
                                    <span class="small text-muted">Verifikasi instan otomatis 24 jam</span>
                                </div>
                                <span class="badge" style="background-color: var(--bali-bg-linen); color: var(--bali-taupe); border: 1px solid var(--bali-border);">VIRTUAL ACCOUNT</span>
                            </label>
                        </div>

                        <!-- Mandiri Virtual Account -->
                        <div class="p-3 border rounded-3 d-flex align-items-center gap-3 surface-ivory" style="border-color: var(--bali-border) !important;">
                            <input class="form-check-input mt-0" type="radio" name="payment_method" id="pay_mandiri" value="va_mandiri" {{ old('payment_method') == 'va_mandiri' ? 'checked' : '' }}>
                            <label class="form-check-label w-100 d-flex justify-content-between align-items-center cursor-pointer" for="pay_mandiri">
                                <div>
                                    <div class="fw-semibold text-dark"><i class="bi bi-bank me-2 fs-5" style="color: var(--bali-tan);"></i> Mandiri Virtual Account</div>
                                    <span class="small text-muted">Transfer via Livin' by Mandiri &amp; ATM</span>
                                </div>
                                <span class="badge" style="background-color: var(--bali-bg-linen); color: var(--bali-taupe); border: 1px solid var(--bali-border);">VIRTUAL ACCOUNT</span>
                            </label>
                        </div>

                        <!-- BRI Virtual Account -->
                        <div class="p-3 border rounded-3 d-flex align-items-center gap-3 surface-ivory" style="border-color: var(--bali-border) !important;">
                            <input class="form-check-input mt-0" type="radio" name="payment_method" id="pay_bri" value="va_bri" {{ old('payment_method') == 'va_bri' ? 'checked' : '' }}>
                            <label class="form-check-label w-100 d-flex justify-content-between align-items-center cursor-pointer" for="pay_bri">
                                <div>
                                    <div class="fw-semibold text-dark"><i class="bi bi-bank me-2 fs-5" style="color: var(--bali-tan);"></i> BRI Virtual Account (BRIVA)</div>
                                    <span class="small text-muted">Transfer via BRImo &amp; ATM BRI</span>
                                </div>
                                <span class="badge" style="background-color: var(--bali-bg-linen); color: var(--bali-taupe); border: 1px solid var(--bali-border);">VIRTUAL ACCOUNT</span>
                            </label>
                        </div>

                        <!-- BSI Virtual Account -->
                        <div class="p-3 border rounded-3 d-flex align-items-center gap-3 surface-ivory" style="border-color: var(--bali-border) !important;">
                            <input class="form-check-input mt-0" type="radio" name="payment_method" id="pay_bsi" value="va_bsi" {{ old('payment_method') == 'va_bsi' ? 'checked' : '' }}>
                            <label class="form-check-label w-100 d-flex justify-content-between align-items-center cursor-pointer" for="pay_bsi">
                                <div>
                                    <div class="fw-semibold text-dark"><i class="bi bi-bank me-2 fs-5" style="color: var(--bali-tan);"></i> BSI Virtual Account (Syariah)</div>
                                    <span class="small text-muted">Transfer via BSI Mobile &amp; ATM</span>
                                </div>
                                <span class="badge" style="background-color: var(--bali-bg-linen); color: var(--bali-taupe); border: 1px solid var(--bali-border);">SYARIAH</span>
                            </label>
                        </div>

                        <!-- ShopeePay -->
                        <div class="p-3 border rounded-3 d-flex align-items-center gap-3 surface-ivory" style="border-color: var(--bali-border) !important;">
                            <input class="form-check-input mt-0" type="radio" name="payment_method" id="pay_shopeepay" value="ewallet_shopeepay" {{ old('payment_method') == 'ewallet_shopeepay' ? 'checked' : '' }}>
                            <label class="form-check-label w-100 d-flex justify-content-between align-items-center cursor-pointer" for="pay_shopeepay">
                                <div>
                                    <div class="fw-semibold text-dark"><i class="bi bi-wallet2 me-2 fs-5" style="color: var(--bali-tan);"></i> ShopeePay</div>
                                    <span class="small text-muted">Pembayaran instan via dompet ShopeePay</span>
                                </div>
                                <span class="badge" style="background-color: var(--bali-bg-linen); color: var(--bali-taupe); border: 1px solid var(--bali-border);">E-WALLET</span>
                            </label>
                        </div>

                        <!-- GoPay -->
                        <div class="p-3 border rounded-3 d-flex align-items-center gap-3 surface-ivory" style="border-color: var(--bali-border) !important;">
                            <input class="form-check-input mt-0" type="radio" name="payment_method" id="pay_gopay" value="ewallet_gopay" {{ old('payment_method') == 'ewallet_gopay' ? 'checked' : '' }}>
                            <label class="form-check-label w-100 d-flex justify-content-between align-items-center cursor-pointer" for="pay_gopay">
                                <div>
                                    <div class="fw-semibold text-dark"><i class="bi bi-wallet2 me-2 fs-5" style="color: var(--bali-tan);"></i> GoPay</div>
                                    <span class="small text-muted">Pembayaran cepat terintegrasi Gojek / GoPay</span>
                                </div>
                                <span class="badge" style="background-color: var(--bali-bg-linen); color: var(--bali-taupe); border: 1px solid var(--bali-border);">E-WALLET</span>
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
                <div class="card-bali p-4 sticky-top surface-ivory" style="top: 100px;">
                    <h5 class="fw-bold mb-3" style="font-family: var(--font-serif); font-size: 1.3rem; color: var(--bali-espresso);">
                        Rincian Pesanan ({{ $totals['total_items'] }} item)
                    </h5>

                    <div class="d-flex flex-column gap-3 mb-4 max-h-80 overflow-auto" style="max-height: 280px;">
                        @foreach($cart as $item)
                        <div class="d-flex align-items-center gap-3">
                            <img src="{{ $item['image_url'] ?? 'https://images.unsplash.com/photo-1515886657613-9f3515b0c78f?q=80&w=200&auto=format&fit=crop' }}" 
                                 class="rounded-3 object-fit-cover" 
                                 style="width: 50px; height: 50px; border: 1px solid var(--bali-border);" 
                                 alt="{{ $item['name'] }}">
                            <div class="flex-grow-1 min-w-0">
                                <h6 class="fw-semibold mb-0 text-dark small text-truncate" style="max-width: 170px;">{{ $item['name'] }}</h6>
                                <div class="d-flex flex-wrap gap-1 mt-1">
                                    <span class="badge" style="background-color: var(--bali-bg-linen); color: var(--bali-taupe); border: 1px solid var(--bali-border); font-size: 0.68rem;">Ukuran: {{ $item['size'] }}</span>
                                    <span class="badge" style="background-color: var(--bali-bg-linen); color: var(--bali-taupe); border: 1px solid var(--bali-border); font-size: 0.68rem;">Warna: {{ $item['color'] }}</span>
                                    <span class="badge bg-white text-muted border" style="font-size: 0.68rem;">&times; {{ $item['quantity'] }} pcs</span>
                                </div>
                            </div>
                            <span class="fw-bold text-dark small">{{ \App\Support\FormatHelper::rupiah($item['subtotal']) }}</span>
                        </div>
                        @endforeach
                    </div>

                    <div class="border-top pt-3" style="border-color: var(--bali-border) !important;">
                        <div class="d-flex justify-content-between mb-2 small text-muted">
                            <span>Subtotal Busana:</span>
                            <span>{{ \App\Support\FormatHelper::rupiah($totals['grand_total']) }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2 small text-muted">
                            <span>Biaya Pengiriman:</span>
                            <span class="fw-bold" style="color: var(--bali-tan-dark);">GRATIS (Promo Butik)</span>
                        </div>
                        <div class="d-flex justify-content-between mb-3 small text-muted">
                            <span>Biaya Layanan Gateway:</span>
                            <span class="fw-bold" style="color: var(--bali-tan-dark);">Rp 0</span>
                        </div>
                        <hr style="border-color: var(--bali-border);">
                        <div class="d-flex justify-content-between align-items-baseline mb-4">
                            <span class="fw-bold" style="color: var(--bali-espresso);">Total Pembayaran:</span>
                            <span class="display-6 fw-extrabold" style="color: var(--bali-espresso); font-weight: 800; font-size: 1.8rem;">
                                {{ \App\Support\FormatHelper::rupiah($totals['grand_total']) }}
                            </span>
                        </div>

                        <button type="submit" class="btn btn-brand-primary w-100 py-3 fs-6">
                            <i class="bi bi-shield-check me-1"></i> Buat Pesanan &amp; Lanjut Bayar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
