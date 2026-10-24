@extends('layouts.app')

@section('title', 'Master Supplier')
@section('page_title', 'Master Supplier & Konveksi')

@section('content')
<div class="card card-master mb-4">
    <div class="card-header-master d-flex align-items-center justify-content-between flex-wrap gap-3">
        <div>
            <h5 class="fw-bold mb-1 text-dark">Daftar Supplier & Mitra Konveksi</h5>
            <span class="text-muted small">Kelola data vendor pemasok bahan dan pakaian Zahiyyah's Fit</span>
        </div>
        <a href="{{ route('suppliers.create') }}" class="btn btn-brand-primary">
            <i class="bi bi-plus-circle me-1"></i> Tambah Supplier
        </a>
    </div>

    <div class="card-body-master">
        <form action="{{ route('suppliers.index') }}" method="GET" class="mb-4">
            <div class="row g-2">
                <div class="col-md-6 col-lg-4">
                    <div class="input-group">
                        <input type="text" name="search" class="form-control form-control-custom" placeholder="Cari nama, perusahaan, no. HP..." value="{{ request('search') }}">
                        <button class="btn btn-dark" type="submit"><i class="bi bi-search"></i></button>
                    </div>
                </div>
                @if(request('search'))
                    <div class="col-auto">
                        <a href="{{ route('suppliers.index') }}" class="btn btn-light border"><i class="bi bi-x-circle"></i> Reset</a>
                    </div>
                @endif
            </div>
        </form>

        <div class="table-responsive rounded-3 border">
            <table class="table-custom align-middle">
                <thead>
                    <tr>
                        <th style="width: 60px;">No.</th>
                        <th>Perusahaan / Mitra</th>
                        <th>Kontak PIC</th>
                        <th>No. Telepon / WA</th>
                        <th>Email</th>
                        <th class="text-center">Riwayat PO</th>
                        <th class="text-end" style="width: 120px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($suppliers as $index => $supplier)
                    <tr>
                        <td>{{ $suppliers->firstItem() + $index }}</td>
                        <td class="fw-bold text-dark">{{ $supplier->company_name ?: $supplier->name }}</td>
                        <td>{{ $supplier->name }}</td>
                        <td><a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $supplier->phone) }}" target="_blank" class="text-decoration-none text-success fw-semibold"><i class="bi bi-whatsapp me-1"></i>{{ $supplier->phone }}</a></td>
                        <td class="text-muted small">{{ $supplier->email }}</td>
                        <td class="text-center">
                            <span class="badge bg-light text-dark border px-2 py-1">{{ $supplier->purchases_count }} transaksi</span>
                        </td>
                        <td class="text-end">
                            <a href="{{ route('suppliers.edit', $supplier) }}" class="btn btn-sm btn-outline-primary btn-action me-1" title="Edit">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <button type="button" class="btn btn-sm btn-outline-danger btn-action" title="Hapus" 
                                    onclick="confirmDelete('del-sup-{{ $supplier->id }}', '{{ $supplier->company_name ?? $supplier->name }}')">
                                <i class="bi bi-trash"></i>
                            </button>
                            <form id="del-sup-{{ $supplier->id }}" action="{{ route('suppliers.destroy', $supplier) }}" method="POST" class="d-none">
                                @csrf
                                @method('DELETE')
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted">Belum ada data supplier pemasok.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4 d-flex justify-content-end">
            {{ $suppliers->links() }}
        </div>
    </div>
</div>
@endsection
