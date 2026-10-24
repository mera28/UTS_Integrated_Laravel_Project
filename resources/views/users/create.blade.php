@extends('layouts.app')

@section('title', 'Tambah Pengguna')
@section('page_title', 'Tambah Pengguna Baru')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card card-master">
            <div class="card-header-master d-flex align-items-center justify-content-between">
                <h5 class="fw-bold mb-0 text-dark">Formulir Tambah Pengguna Baru</h5>
                <a href="{{ route('users.index') }}" class="btn btn-sm btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i> Kembali
                </a>
            </div>
            <div class="card-body-master">
                <form action="{{ route('users.store') }}" method="POST">
                    @csrf
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label for="name" class="form-label-custom">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-custom @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" placeholder="Contoh: Rina Amalia" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="email" class="form-label-custom">Alamat Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control form-control-custom @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" placeholder="user@zahiyyah.com" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label for="password" class="form-label-custom">Kata Sandi Default <span class="text-danger">*</span></label>
                            <input type="password" class="form-control form-control-custom @error('password') is-invalid @enderror" id="password" name="password" placeholder="Min. 6 karakter" required>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="role" class="form-label-custom">Role / Hak Akses Sistem <span class="text-danger">*</span></label>
                            <select name="role" id="role" class="form-select form-select-custom @error('role') is-invalid @enderror" required>
                                <option value="">-- Pilih Role --</option>
                                <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin (Owner - Hak Akses Penuh)</option>
                                <option value="staff" {{ old('role') == 'staff' ? 'selected' : '' }}>Staff (Kasir & Gudang)</option>
                                <option value="customer" {{ old('role') == 'customer' ? 'selected' : '' }}>Customer (Pelanggan E-Commerce)</option>
                            </select>
                            @error('role')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label for="phone" class="form-label-custom">Nomor Telepon / WhatsApp</label>
                            <input type="text" class="form-control form-control-custom @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone') }}" placeholder="0812xxxxxxxx">
                        </div>
                        <div class="col-md-6">
                            <label for="address" class="form-label-custom">Alamat</label>
                            <input type="text" class="form-control form-control-custom @error('address') is-invalid @enderror" id="address" name="address" value="{{ old('address') }}" placeholder="Kota, Wilayah...">
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('users.index') }}" class="btn btn-light border px-4">Batal</a>
                        <button type="submit" class="btn btn-brand-primary px-4">
                            <i class="bi bi-save me-1"></i> Simpan Pengguna
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
