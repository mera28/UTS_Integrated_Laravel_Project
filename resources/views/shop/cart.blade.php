@extends('layouts.shop')

@section('title', 'Keranjang Belanja')

@section('content')
<div class="container py-5">
    <div class="row g-4 justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 p-4 p-md-5 text-center bg-white">
                <div class="mb-3">
                    <div class="bg-danger bg-opacity-10 text-danger rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 80px; height: 80px; font-size: 2.2rem;">
                        <i class="bi bi-bag-heart"></i>
                    </div>
                </div>
                <h4 class="fw-bold text-dark mb-2">Keranjang Belanja</h4>
                <p class="text-muted small mb-4">
                    Koleksi outfit pilihan Anda akan tampil di sini saat melakukan pemesanan.
                </p>
                <div>
                    <a href="{{ route('shop.index') }}" class="btn btn-brand-primary px-4 py-2 rounded-pill fw-semibold">
                        <i class="bi bi-arrow-left me-1"></i> Jelajahi Katalog Outfit
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
