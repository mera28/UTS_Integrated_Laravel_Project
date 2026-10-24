@extends('layouts.app')

@section('title', 'Kasir POS Baru')
@section('page_title', 'Kasir Penjualan Butik (Point of Sale)')

@section('content')
<!-- Hidden product options template for POS dynamic rows -->
<select class="d-none pos-product-template">
    <option value="">-- Pilih Produk Outfit --</option>
    @foreach($products as $prod)
        <option value="{{ $prod->id }}" 
                data-price="{{ (int) $prod->selling_price }}" 
                data-stock="{{ $prod->stock }}" 
                data-sku="{{ $prod->sku }}"
                data-size="{{ $prod->size }}"
                data-color="{{ $prod->color }}">
            [{{ $prod->sku }}] {{ $prod->name }} - Rp {{ number_format($prod->selling_price, 0, ',', '.') }} (Stok: {{ $prod->stock }})
        </option>
    @endforeach
</select>

<form action="{{ route('sales.store') }}" method="POST">
    @csrf
    <div class="row g-4">
        <!-- Kasir Order Table -->
        <div class="col-lg-8">
            <div class="card card-master mb-4">
                <div class="card-header-master d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div>
                        <h6 class="fw-bold mb-0 text-dark"><i class="bi bi-cart3 me-2 text-primary"></i> Keranjang Kasir Penjualan</h6>
                        <span class="text-muted small">Pilih pakaian, varian, dan kuantitas pesanan</span>
                    </div>
                    <button type="button" class="btn btn-sm btn-outline-primary" onclick="addSaleRow()">
                        <i class="bi bi-plus-lg me-1"></i> Tambah Item Baju
                    </button>
                </div>

                <div class="card-body-master">
                    @error('items')
                        <div class="alert alert-danger small mb-3 py-2">{{ $message }}</div>
                    @enderror

                    <div class="table-responsive rounded-3 border">
                        <table class="table-custom align-middle mb-0" id="saleItemsTable">
                            <thead>
                                <tr>
                                    <th style="width: 40px;" class="text-center">No</th>
                                    <th>Produk Outfit</th>
                                    <th>Ukuran</th>
                                    <th>Warna</th>
                                    <th class="text-end">Harga Satuan</th>
                                    <th class="text-center">Qty</th>
                                    <th class="text-end">Subtotal</th>
                                    <th class="text-center">Hapus</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="sale-item-row">
                                    <td class="text-center pos-row-number">1</td>
                                    <td>
                                        <select name="items[0][product_id]" class="form-select form-select-custom pos-product-selector" onchange="onSaleProductChange(this)" required>
                                            <option value="">-- Pilih Produk Outfit --</option>
                                            @foreach($products as $prod)
                                                <option value="{{ $prod->id }}" 
                                                        data-price="{{ (int) $prod->selling_price }}" 
                                                        data-stock="{{ $prod->stock }}" 
                                                        data-sku="{{ $prod->sku }}"
                                                        data-size="{{ $prod->size }}"
                                                        data-color="{{ $prod->color }}">
                                                    [{{ $prod->sku }}] {{ $prod->name }} - Rp {{ number_format($prod->selling_price, 0, ',', '.') }} (Stok: {{ $prod->stock }})
                                                </option>
                                            @endforeach
                                        </select>
                                        <div class="d-flex align-items-center gap-2 mt-1 small">
                                            <span class="text-muted pos-stock-info">Pilih produk pakaian</span>
                                            <span class="text-danger fw-semibold d-none pos-stock-alert">Stok tidak cukup!</span>
                                        </div>
                                    </td>
                                    <td style="width: 130px;">
                                        <input type="text" name="items[0][selected_size]" class="form-control form-control-custom pos-item-size" placeholder="S / M / L / XL">
                                    </td>
                                    <td style="width: 130px;">
                                        <input type="text" name="items[0][selected_color]" class="form-control form-control-custom pos-item-color" placeholder="Warna">
                                    </td>
                                    <td class="text-end fw-bold text-dark pos-item-price" style="width: 140px;">Rp 0</td>
                                    <td style="width: 100px;">
                                        <input type="number" min="1" name="items[0][quantity]" class="form-control form-control-custom pos-item-qty text-center" value="1" oninput="calculateSaleTotals()" required>
                                    </td>
                                    <td class="text-end fw-extrabold text-danger pos-item-subtotal" style="width: 150px;">Rp 0</td>
                                    <td class="text-center" style="width: 50px;">
                                        <button type="button" class="btn btn-sm btn-outline-danger btn-action" onclick="removeSaleRow(this)" title="Hapus">
                                            <i class="bi bi-x-lg"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3 text-end">
                        <button type="button" class="btn btn-sm btn-light border" onclick="addSaleRow()">
                            <i class="bi bi-plus-circle me-1"></i> Tambah Item Lain
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Checkout Summary Panel -->
        <div class="col-lg-4">
            <div class="card card-master mb-4">
                <div class="card-header-master">
                    <h6 class="fw-bold mb-0 text-dark"><i class="bi bi-credit-card me-2 text-primary"></i> Pembayaran Kasir</h6>
                </div>
                <div class="card-body-master">
                    <div class="mb-3">
                        <label for="customer_name" class="form-label-custom">Nama Pelanggan (Walk-in / Member)</label>
                        <input type="text" class="form-control form-control-custom @error('customer_name') is-invalid @enderror" id="customer_name" name="customer_name" value="{{ old('customer_name', 'Pelanggan Butik (Walk-in)') }}">
                    </div>

                    <div class="mb-3">
                        <label for="payment_method" class="form-label-custom">Metode Pembayaran <span class="text-danger">*</span></label>
                        <select name="payment_method" id="payment_method" class="form-select form-select-custom @error('payment_method') is-invalid @enderror" required>
                            <option value="cash" {{ old('payment_method') == 'cash' ? 'selected' : '' }}>Cash / Tunai</option>
                            <option value="qris" {{ old('payment_method') == 'qris' ? 'selected' : '' }}>QRIS Interaktif</option>
                            <option value="va_bca" {{ old('payment_method') == 'va_bca' ? 'selected' : '' }}>BCA Virtual Account</option>
                            <option value="va_mandiri" {{ old('payment_method') == 'va_mandiri' ? 'selected' : '' }}>Mandiri Virtual Account</option>
                            <option value="va_bri" {{ old('payment_method') == 'va_bri' ? 'selected' : '' }}>BRI Virtual Account</option>
                            <option value="va_bsi" {{ old('payment_method') == 'va_bsi' ? 'selected' : '' }}>BSI Virtual Account</option>
                            <option value="ewallet_shopeepay" {{ old('payment_method') == 'ewallet_shopeepay' ? 'selected' : '' }}>ShopeePay</option>
                            <option value="ewallet_gopay" {{ old('payment_method') == 'ewallet_gopay' ? 'selected' : '' }}>GoPay</option>
                        </select>
                    </div>

                    <!-- Payment Calculation Box -->
                    <div class="p-3 bg-light rounded-3 border mb-3">
                        <div class="d-flex justify-content-between mb-2 small">
                            <span class="text-muted">Total Jumlah Pakaian:</span>
                            <span class="fw-bold text-primary" id="posTotalItems">0 pcs</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="fw-bold text-dark">Total Tagihan:</span>
                            <span class="fw-extrabold text-danger fs-5" id="posGrandTotal">Rp 0</span>
                        </div>
                        <hr class="my-2">
                        <div class="mb-2">
                            <label for="posCashGiven" class="form-label small text-muted mb-1">Nominal Uang Diterima (Rp):</label>
                            <input type="number" step="1000" id="posCashGiven" class="form-control form-control-custom" placeholder="Contoh: 300000" oninput="calculateSaleTotals()">
                        </div>
                        <div class="d-flex justify-content-between align-items-baseline pt-1">
                            <span class="small fw-semibold text-secondary">Uang Kembalian:</span>
                            <span class="fw-bold fs-6 text-success" id="posChangeAmount">Rp 0</span>
                        </div>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-brand-primary py-2 fw-bold">
                            <i class="bi bi-printer me-1"></i> Simpan Transaksi & Cetak Struk
                        </button>
                        <a href="{{ route('sales.index') }}" class="btn btn-light border mt-2">Batal</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection
