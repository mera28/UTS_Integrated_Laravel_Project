@extends('layouts.app')

@section('title', 'Edit Kategori')
@section('page_title', 'Ubah Kategori Outfit')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-7">
        <div class="card card-master">
            <div class="card-header-master d-flex align-items-center justify-content-between">
                <h5 class="fw-bold mb-0 text-dark">Ubah Data Kategori</h5>
                <a href="{{ route('categories.index') }}" class="btn btn-sm btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i> Kembali
                </a>
            </div>
            <div class="card-body-master">
                <form action="{{ route('categories.update', $category) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label for="name" class="form-label-custom">Nama Kategori <span class="text-danger">*</span></label>
                        <input type="text" class="form-control form-control-custom @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $category->name) }}" required autofocus>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="description" class="form-label-custom">Deskripsi Kategori</label>
                        <textarea class="form-control form-control-custom @error('description') is-invalid @enderror" id="description" name="description" rows="3">{{ old('description', $category->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('categories.index') }}" class="btn btn-light border px-4">Batal</a>
                        <button type="submit" class="btn btn-brand-primary px-4">
                            <i class="bi bi-check2-circle me-1"></i> Perbarui Kategori
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
