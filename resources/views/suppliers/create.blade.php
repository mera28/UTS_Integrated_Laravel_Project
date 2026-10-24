@extends('layouts.app')

@section('title', 'Tambah Supplier')
@section('page_title', 'Tambah Supplier / Konveksi')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card card-master">
            <div class="card-header-master d-flex align-items-center justify-content-between">
                <h5 class="fw-bold mb-0 text-dark">Formulir Pemasok Baru</h5>
                <a href="{{ route('suppliers.index') }}" class="btn btn-sm btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i> Kembali
                </a>
            </div>
            <div class="card-body-master">
                <form action="{{ route('suppliers.store') }}" method="POST">
                    @csrf
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label for="company_name" class="form-label-custom">Nama Perusahaan / Konveksi</label>
                            <input type="text" class="form-control form-control-custom @error('company_name') is-invalid @enderror" id="company_name" name="company_name" value="{{ old('company_name') }}" placeholder="Contoh: CV Busana Muslimah Bandung">
                        </div>
                        <div class="col-md-6">
                            <label for="name" class="form-label-custom">Nama Kontak PIC <span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-custom @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" placeholder="Contoh: Haji Ridwan" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label for="phone" class="form-label-custom">No. Telepon / WhatsApp <span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-custom @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone') }}" placeholder="0813xxxxxxxx" required>
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="email" class="form-label-custom">Alamat Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control form-control-custom @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" placeholder="order@supplier.com" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="address" class="form-label-custom">Alamat Gudang / Kantor Supplier</label>
                        <textarea class="form-control form-control-custom @error('address') is-invalid @enderror" id="address" name="address" rows="3" placeholder="Alamat lengkap...">{{ old('address') }}</textarea>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('suppliers.index') }}" class="btn btn-light border px-4">Batal</a>
                        <button type="submit" class="btn btn-brand-primary px-4">
                            <i class="bi bi-save me-1"></i> Simpan Supplier
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
