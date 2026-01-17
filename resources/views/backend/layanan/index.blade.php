@extends('layout.appv2')
@section('title', 'Manajemen Layanan')
@section('cms', 'active open')
@section('mnj_layanan', 'active')

@section('content')
    <div class="content">
        <div class="page-title mb-4">
            <h3>Manajemen Layanan</h3>
            <p class="text-muted">Kelola konten layanan, SOP, dan persyaratan.</p>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Daftar Layanan</h5>
                <a href="{{ route('layanan.create') }}" class="btn btn-primary rounded-pill">
                    <i class="fas fa-plus me-1"></i> Tambah Layanan
                </a>
            </div>
            <div class="card-body">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th width="50">No</th>
                                <th>Judul Layanan</th>
                                <th>Ikon</th>
                                <th>Deskripsi Singkat</th>
                                <th width="150">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($layanans as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td class="fw-semibold">{{ $item->judul }}</td>
                                    <td class="fs-4">
                                        @if (Str::startsWith($item->ikon, 'fa'))
                                            <i class="{{ $item->ikon }}"></i>
                                        @else
                                            {{ $item->ikon }}
                                        @endif
                                    </td>
                                    <td>{{ Str::limit($item->deskripsi_singkat, 60) }}</td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="{{ route('layanan.edit', $item->id) }}"
                                                class="btn btn-sm btn-outline-warning">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('layanan.destroy', $item->id) }}" method="POST"
                                                class="d-inline"
                                                onsubmit="return confirm('Yakin ingin menghapus layanan ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-sm btn-outline-danger" type="submit">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted">Belum ada data layanan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
