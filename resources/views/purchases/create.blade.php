@extends('layouts.app')

@section('title', 'Buat Pengadaan Stok Baru')
@section('page_title', 'Input Pembelian Pakaian (Stock-In)')

@section('content')
<!-- Hidden product options template for dynamic rows -->
<select class="d-none product-select-template">
    <option value="">-- Pilih Produk Outfit --</option>
    @foreach($products as $prod)
        <option value="{{ $prod->id }}" 
                data-price="{{ (int) $prod->purchase_price }}" 
                data-stock="{{ $prod->stock }}" 
                data-sku="{{ $prod->sku }}">
            [{{ $prod->sku }}] {{ $prod->name }} (Size: {{ $prod->size ?: '-' }})
        </option>
    @endforeach
</select>

<form action="{{ route('purchases.store') }}" method="POST">
    @csrf
    <div class="row g-4">
        <!-- Supplier & Metadata Card -->
        <div class="col-lg-4">
            <div class="card card-master mb-4">
                <div class="card-header-master">
                    <h6 class="fw-bold mb-0 text-dark"><i class="bi bi-truck me-2 text-primary"></i> Data Pemasok & Waktu</h6>
                </div>
                <div class="card-body-master">
                    <div class="mb-3">
                        <label for="supplier_id" class="form-label-custom">Supplier / Konveksi <span class="text-danger">*</span></label>
                        <select name="supplier_id" id="supplier_id" class="form-select form-select-custom @error('supplier_id') is-invalid @enderror" required>
                            <option value="">-- Pilih Supplier --</option>
                            @foreach($suppliers as $sup)
                                <option value="{{ $sup->id }}" {{ old('supplier_id') == $sup->id ? 'selected' : '' }}>
                                    {{ $sup->company_name ?: $sup->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('supplier_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="purchased_at" class="form-label-custom">Tanggal & Waktu Transaksi</label>
                        <input type="datetime-local" class="form-control form-control-custom @error('purchased_at') is-invalid @enderror" id="purchased_at" name="purchased_at" value="{{ old('purchased_at', now()->format('Y-m-d\TH:i')) }}">
                        @error('purchased_at')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="notes" class="form-label-custom">Catatan Faktur / No. Surat Jalan</label>
                        <textarea class="form-control form-control-custom @error('notes') is-invalid @enderror" id="notes" name="notes" rows="3" placeholder="Contoh: Pengadaan batch 1 Gamis Silk & Blouse Peplum...">{{ old('notes') }}</textarea>
                    </div>

                    <div class="p-3 bg-light rounded-3 border">
                        <div class="d-flex justify-content-between mb-2 small">
                            <span class="text-muted">Petugas Penerima:</span>
                            <span class="fw-bold text-dark">{{ auth()->user()->name }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2 small">
                            <span class="text-muted">Total Jumlah Baju:</span>
                            <span class="fw-bold text-primary" id="purchaseTotalItems">0 pcs</span>
                        </div>
                        <hr class="my-2">
                        <div class="d-flex justify-content-between align-items-baseline">
                            <span class="fw-bold small text-dark">Total Biaya:</span>
                            <span class="fw-extrabold text-danger fs-5" id="purchaseGrandTotal">Rp 0</span>
                        </div>
                    </div>

                    <div class="d-grid mt-4">
                        <button type="submit" class="btn btn-brand-primary py-2 fw-bold">
                            <i class="bi bi-box-arrow-in-down me-1"></i> Simpan Transaksi & Tambah Stok
                        </button>
                        <a href="{{ route('purchases.index') }}" class="btn btn-light border mt-2">Batal</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Dynamic Items Table Card -->
        <div class="col-lg-8">
            <div class="card card-master mb-4">
                <div class="card-header-master d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div>
                        <h6 class="fw-bold mb-0 text-dark"><i class="bi bi-box-seam me-2 text-primary"></i> Rincian Pakaian yang Dibeli</h6>
                        <span class="text-muted small">Tambahkan item pakaian yang masuk dari supplier</span>
                    </div>
                    <button type="button" class="btn btn-sm btn-outline-primary" onclick="addPurchaseRow()">
                        <i class="bi bi-plus-lg me-1"></i> Tambah Baris Baju
                    </button>
                </div>

                <div class="card-body-master">
                    @error('items')
                        <div class="alert alert-danger small mb-3 py-2">{{ $message }}</div>
                    @enderror

                    <div class="table-responsive rounded-3 border">
                        <table class="table-custom align-middle mb-0" id="purchaseItemsTable">
                            <thead>
                                <tr>
                                    <th style="width: 40px;" class="text-center">No</th>
                                    <th>Produk Outfit</th>
                                    <th style="width: 170px;">Harga Modal (HPP)</th>
                                    <th style="width: 110px;" class="text-center">Kuantitas</th>
                                    <th style="width: 150px;" class="text-end">Subtotal</th>
                                    <th style="width: 50px;" class="text-center">Hapus</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="purchase-item-row">
                                    <td class="text-center row-number">1</td>
                                    <td>
                                        <select name="items[0][product_id]" class="form-select form-select-custom product-selector" onchange="onPurchaseProductChange(this)" required>
                                            <option value="">-- Pilih Produk Outfit --</option>
                                            @foreach($products as $prod)
                                                <option value="{{ $prod->id }}" 
                                                        data-price="{{ (int) $prod->purchase_price }}" 
                                                        data-stock="{{ $prod->stock }}" 
                                                        data-sku="{{ $prod->sku }}">
                                                    [{{ $prod->sku }}] {{ $prod->name }} (Size: {{ $prod->size ?: '-' }})
                                                </option>
                                            @endforeach
                                        </select>
                                        <div class="small text-muted current-stock-info mt-1">Pilih pakaian untuk melihat info stok</div>
                                    </td>
                                    <td>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light text-muted">Rp</span>
                                            <input type="number" step="1000" min="0" name="items[0][unit_cost]" class="form-control form-control-custom item-unit-cost" value="0" oninput="calculatePurchaseTotals()" required>
                                        </div>
                                    </td>
                                    <td>
                                        <input type="number" min="1" name="items[0][quantity]" class="form-control form-control-custom item-quantity text-center" value="1" oninput="calculatePurchaseTotals()" required>
                                    </td>
                                    <td class="text-end fw-bold text-dark item-subtotal">Rp 0</td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-sm btn-outline-danger btn-action" onclick="removePurchaseRow(this)" title="Hapus Baris">
                                            <i class="bi bi-x-lg"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3 text-end">
                        <button type="button" class="btn btn-sm btn-light border" onclick="addPurchaseRow()">
                            <i class="bi bi-plus-circle me-1"></i> Tambah Baris Produk Lainnya
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection
