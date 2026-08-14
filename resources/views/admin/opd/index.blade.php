@extends('layouts.app')

@section('title', 'Manajemen OPD - SIMODU KMC')
@section('page-title')
    <i class="fa-solid fa-building-user text-primary me-2"></i> Daftar OPD
@endsection

@section('content')
<style>
    /* Focus expand transition for search input */
    .search-input-wrapper input {
        width: 260px;
        height: 42px;
        border-color: #cbd5e1;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    @media (min-width: 768px) {
        .search-input-wrapper input:focus {
            width: 340px;
            border-color: var(--kmc-blue) !important;
            box-shadow: 0 0 0 0.25rem rgba(13, 71, 161, 0.15) !important;
        }
    }
    @media (max-width: 767px) {
        .search-input-wrapper input {
            width: 100%;
        }
    }
    .table-hover tbody tr {
        transition: all 0.2s ease-in-out;
    }
    .table-hover tbody tr:hover {
        background-color: #f8fafc;
    }
</style>

<div class="card border-0 shadow-sm rounded-4">
    <div class="card-header bg-white border-0 py-3 d-flex flex-column flex-md-row justify-content-between align-items-center rounded-top-4 gap-3 border-bottom" style="border-color: #f1f5f9 !important;">
        
        <form action="{{ route('admin.opd.index') }}" method="GET" class="d-flex gap-2 m-0 w-100 flex-md-grow-0" style="max-width: 100%;">
            <div class="position-relative d-flex align-items-center search-input-wrapper flex-grow-1">
                <i class="fa-solid fa-magnifying-glass position-absolute text-muted ms-3" style="z-index: 5;"></i>
                <input type="text" name="search" class="form-control ps-5 pe-5 rounded-pill w-100" placeholder="Cari OPD, username, email..." value="{{ request('search') }}">
                @if(request('search'))
                    <a href="{{ route('admin.opd.index') }}" class="position-absolute end-0 text-muted me-3 text-decoration-none d-flex align-items-center" title="Reset Pencarian" style="z-index: 5;">
                        <i class="fa-solid fa-circle-xmark fs-5"></i>
                    </a>
                @endif
            </div>
            <button type="submit" class="btn btn-primary rounded-pill px-4 flex-shrink-0" style="height: 42px;">
                Cari
            </button>
        </form>
        
        <div class="d-flex align-items-center">
            <a href="{{ route('admin.opd.create') }}" class="btn btn-outline-success rounded-pill fw-bold px-3 d-inline-flex align-items-center justify-content-center gap-2" style="border-width: 2px; height: 42px; transition: all 0.2s ease-in-out;">
                <i class="fa-solid fa-plus-circle"></i> Tambah
            </a>
        </div>
    </div>
    <div class="card-body p-4">

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="bg-light">
                    <tr>
                        <th class="py-3 px-4 text-muted fw-semibold">No</th>
                        <th class="py-3 px-4 text-muted fw-semibold">Nama OPD</th>
                        <th class="py-3 px-4 text-muted fw-semibold">Username</th>
                        <th class="py-3 px-4 text-muted fw-semibold">Email</th>
                        <th class="py-3 px-4 text-muted fw-semibold text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($opds as $opd)
                        <tr>
                            <td class="py-3 px-4">{{ $opds->firstItem() + $loop->index }}</td>
                            <td class="py-3 px-4 fw-medium">{{ $opd->name }}</td>
                            <td class="py-3 px-4 text-muted">{{ $opd->user?->username ?? '-' }}</td>
                            <td class="py-3 px-4 text-muted">{{ $opd->user?->email ?? '-' }}</td>
                            <td class="py-3 px-4 text-center">
                                <div class="d-flex justify-content-center gap-2">
                                    <a href="{{ route('admin.opd.edit', $opd->id) }}" class="btn btn-sm btn-outline-warning rounded-pill px-3">
                                        <i class="fa-solid fa-pen-to-square"></i> Edit
                                    </a>
                                    <form action="{{ route('admin.opd.destroy', $opd->id) }}" method="POST" class="m-0" onsubmit="return confirmAction(event, { title: 'Hapus Akun OPD?', text: 'Yakin ingin menghapus OPD ini?', confirmButtonText: 'Ya, Hapus!' });">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger rounded-pill px-3">
                                            <i class="fa-solid fa-trash"></i> Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">Belum ada data OPD.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($opds->hasPages())
            <div class="mt-4">
                {{ $opds->withQueryString()->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
