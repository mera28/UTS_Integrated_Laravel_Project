@extends('layouts.app')

@section('title', 'Tambah Produk Outfit')
@section('page_title', 'Tambah Produk Baru')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-9">
        <div class="card card-master">
            <div class="card-header-master d-flex align-items-center justify-content-between">
                <h5 class="fw-bold mb-0 text-dark">Formulir Produk Outfit Baru</h5>
                <a href="{{ route('products.index') }}" class="btn btn-sm btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i> Kembali
                </a>
            </div>
            <div class="card-body-master">
                <form action="{{ route('products.store') }}" method="POST">
                    @csrf
                    <div class="row g-3 mb-3">
                        <div class="col-md-4">
                            <label for="sku" class="form-label-custom">Kode SKU Produk <span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-custom @error('sku') is-invalid @enderror" id="sku" name="sku" value="{{ old('sku') }}" placeholder="Contoh: ZF-GMS-003" required>
                            @error('sku')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-5">
                            <label for="name" class="form-label-custom">Nama Produk Outfit <span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-custom @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" placeholder="Contoh: Aisha Silk Abaya Dress" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-3">
                            <label for="category_id" class="form-label-custom">Kategori <span class="text-danger">*</span></label>
                            <select name="category_id" id="category_id" class="form-select form-select-custom @error('category_id') is-invalid @enderror" required>
                                <option value="">-- Pilih --</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                                        {{ $cat->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label for="size" class="form-label-custom">Pilihan Ukuran (Size)</label>
                            <input type="text" class="form-control form-control-custom @error('size') is-invalid @enderror" id="size" name="size" value="{{ old('size') }}" placeholder="Contoh: S, M, L, XL atau All Size">
                        </div>
                        <div class="col-md-6">
                            <label for="color" class="form-label-custom">Pilihan Warna</label>
                            <input type="text" class="form-control form-control-custom @error('color') is-invalid @enderror" id="color" name="color" value="{{ old('color') }}" placeholder="Contoh: Sage Green, Dusty Pink, Broken White">
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-3">
                            <label for="purchase_price" class="form-label-custom">Harga Beli Modal (HPP) <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-muted">Rp</span>
                                <input type="number" step="1000" min="0" class="form-control form-control-custom @error('purchase_price') is-invalid @enderror" id="purchase_price" name="purchase_price" value="{{ old('purchase_price') }}" placeholder="150000" required>
                            </div>
                            @error('purchase_price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-3">
                            <label for="selling_price" class="form-label-custom">Harga Jual Konsumen <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-muted">Rp</span>
                                <input type="number" step="1000" min="0" class="form-control form-control-custom @error('selling_price') is-invalid @enderror" id="selling_price" name="selling_price" value="{{ old('selling_price') }}" placeholder="265000" required>
                            </div>
                            @error('selling_price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-3">
                            <label for="stock" class="form-label-custom">Stok Awal Fisik <span class="text-danger">*</span></label>
                            <input type="number" min="0" class="form-control form-control-custom @error('stock') is-invalid @enderror" id="stock" name="stock" value="{{ old('stock', 0) }}" required>
                            @error('stock')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-3">
                            <label for="minimum_stock" class="form-label-custom">Batas Minimum Stok <span class="text-danger">*</span></label>
                            <input type="number" min="0" class="form-control form-control-custom @error('minimum_stock') is-invalid @enderror" id="minimum_stock" name="minimum_stock" value="{{ old('minimum_stock', 5) }}" required>
                            <div class="form-text" style="font-size: 0.72rem;">Pemicu Low Stock Alert di dashboard</div>
                            @error('minimum_stock')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="image_url" class="form-label-custom">URL Gambar Produk (Foto Fashion)</label>
                        <input type="url" class="form-control form-control-custom @error('image_url') is-invalid @enderror" id="image_url" name="image_url" value="{{ old('image_url') }}" placeholder="https://images.unsplash.com/...">
                        <div class="form-text" style="font-size: 0.72rem;">Bisa menggunakan link foto Unsplash berkualitas tinggi.</div>
                    </div>

                    <div class="mb-4">
                        <label for="description" class="form-label-custom">Deskripsi Bahan & Detail Jahitan</label>
                        <textarea class="form-control form-control-custom @error('description') is-invalid @enderror" id="description" name="description" rows="3" placeholder="Jelaskan karakteristik bahan, keunggulan, kenyamanan furing, dan wudhu/busui friendly...">{{ old('description') }}</textarea>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('products.index') }}" class="btn btn-light border px-4">Batal</a>
                        <button type="submit" class="btn btn-brand-primary px-4">
                            <i class="bi bi-save me-1"></i> Simpan Produk
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
