@extends('layouts.app')

@section('title', 'Kelola Pengguna')
@section('page_title', 'Kelola Pengguna & Hak Akses')

@section('content')
<div class="card card-master mb-4">
    <div class="card-header-master d-flex align-items-center justify-content-between flex-wrap gap-3">
        <div>
            <h5 class="fw-bold mb-1 text-dark">Daftar Pengguna Sistem & Pelanggan</h5>
            <span class="text-muted small">Atur hak akses admin, staff gudang/kasir, dan akun customer Zahiyyah's Fit</span>
        </div>
        <a href="{{ route('users.create') }}" class="btn btn-brand-primary">
            <i class="bi bi-person-plus me-1"></i> Tambah Pengguna
        </a>
    </div>

    <div class="card-body-master">
        <form action="{{ route('users.index') }}" method="GET" class="mb-4">
            <div class="row g-2 align-items-center">
                <div class="col-md-5">
                    <input type="text" name="search" class="form-control form-control-custom" placeholder="Cari nama, email, no. telepon..." value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <select name="role" class="form-select form-select-custom">
                        <option value="">-- Semua Role --</option>
                        <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin (Owner)</option>
                        <option value="staff" {{ request('role') == 'staff' ? 'selected' : '' }}>Staff (Kasir & Gudang)</option>
                        <option value="customer" {{ request('role') == 'customer' ? 'selected' : '' }}>Customer (Pelanggan)</option>
                    </select>
                </div>
                <div class="col-md-2 d-flex gap-2">
                    <button type="submit" class="btn btn-dark w-100"><i class="bi bi-funnel me-1"></i> Filter</button>
                    @if(request()->hasAny(['search', 'role']))
                        <a href="{{ route('users.index') }}" class="btn btn-light border"><i class="bi bi-x-circle"></i></a>
                    @endif
                </div>
            </div>
        </form>

        <div class="table-responsive rounded-3 border">
            <table class="table-custom align-middle">
                <thead>
                    <tr>
                        <th style="width: 50px;">No.</th>
                        <th>Nama Pengguna</th>
                        <th>Email</th>
                        <th class="text-center">Role / Hak Akses</th>
                        <th>Telepon / WA</th>
                        <th>Terdaftar Sejak</th>
                        <th class="text-end" style="width: 120px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $index => $u)
                    <tr>
                        <td>{{ $users->firstItem() + $index }}</td>
                        <td>
                            <div class="fw-bold text-dark">{{ $u->name }}</div>
                            @if($u->id === auth()->id())
                                <span class="badge bg-primary bg-opacity-10 text-primary border border-primary small" style="font-size: 0.68rem;">Akun Anda</span>
                            @endif
                        </td>
                        <td class="text-muted">{{ $u->email }}</td>
                        <td class="text-center">
                            @if($u->role === 'admin')
                                <span class="badge bg-danger px-2 py-1"><i class="bi bi-shield-check me-1"></i> OWNER / ADMIN</span>
                            @elseif($u->role === 'staff')
                                <span class="badge bg-info text-dark px-2 py-1"><i class="bi bi-person-badge me-1"></i> STAFF KASIR</span>
                            @else
                                <span class="badge bg-secondary px-2 py-1"><i class="bi bi-bag-heart me-1"></i> CUSTOMER</span>
                            @endif
                        </td>
                        <td>{{ $u->phone ?: '-' }}</td>
                        <td class="text-muted small">{{ $u->created_at->format('d M Y') }}</td>
                        <td class="text-end">
                            <a href="{{ route('users.edit', $u) }}" class="btn btn-sm btn-outline-primary btn-action me-1" title="Edit">
                                <i class="bi bi-pencil"></i>
                            </a>
                            @if($u->id !== auth()->id())
                                <button type="button" class="btn btn-sm btn-outline-danger btn-action" title="Hapus" 
                                        onclick="confirmDelete('del-user-{{ $u->id }}', '{{ $u->name }}')">
                                    <i class="bi bi-trash"></i>
                                </button>
                                <form id="del-user-{{ $u->id }}" action="{{ route('users.destroy', $u) }}" method="POST" class="d-none">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            @else
                                <button class="btn btn-sm btn-light btn-action disabled text-muted" title="Tidak dapat menghapus diri sendiri">
                                    <i class="bi bi-lock"></i>
                                </button>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted">Belum ada pengguna yang sesuai filter.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4 d-flex justify-content-end">
            {{ $users->links() }}
        </div>
    </div>
</div>
@endsection
