@extends('layout.appv2')

@section('title', 'Seleksi Berkas')

@section('content')
<div class="container-fluid mt-4">

    <!-- Header -->
    <div class="row align-items-center mb-4">
        <div class="col-6">
            <h3 class="mb-0 fw-bold text-dark">Seleksi Berkas</h3>
            <p class="text-muted mb-0">Kelola formulir seleksi berkas yang fleksibel</p>
        </div>
        <div class="col-6 text-end">
            <a href="{{ route('seleksi_berkas.create') }}" class="btn btn-success">
                <i class="fas fa-plus me-2"></i>Buat Seleksi Baru
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Cards Grid -->
    <div class="row g-4">
        @forelse($seleksis as $seleksi)
            <div class="col-md-6 col-xl-4">
                <div class="card shadow-sm border-0 rounded-3 h-100 position-relative">
                    <!-- Status ribbon -->
                    <div class="position-absolute top-0 end-0 m-3">
                        @if($seleksi->isBuka())
                            <span class="badge bg-success rounded-pill px-3 py-2">
                                <i class="fas fa-circle fs-xs me-1" style="font-size:8px;"></i>BUKA
                            </span>
                        @else
                            <span class="badge bg-secondary rounded-pill px-3 py-2">TUTUP</span>
                        @endif
                    </div>

                    <div class="card-body p-4">
                        <div class="d-flex align-items-start mb-3">
                            <div class="bg-success bg-opacity-10 rounded-3 p-3 me-3 flex-shrink-0">
                                <i class="fas fa-folder-open text-success fa-lg"></i>
                            </div>
                            <div>
                                <h5 class="fw-bold mb-1 text-dark">{{ $seleksi->judul }}</h5>
                                <small class="text-muted font-monospace">/seleksi-berkas/{{ $seleksi->slug }}</small>
                            </div>
                        </div>

                        @if($seleksi->deskripsi)
                            <p class="text-muted small mb-3">{{ Str::limit($seleksi->deskripsi, 100) }}</p>
                        @endif

                        <div class="row g-2 mb-3">
                            <div class="col-6">
                                <div class="bg-light rounded-2 p-2 text-center">
                                    <div class="fw-bold text-success fs-5">{{ $seleksi->pengajuans_count }}</div>
                                    <div class="text-muted" style="font-size:11px;">PENGAJUAN</div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="bg-light rounded-2 p-2 text-center">
                                    <div class="fw-bold text-primary fs-5">{{ count($seleksi->berkas_configs ?? []) }}</div>
                                    <div class="text-muted" style="font-size:11px;">BERKAS</div>
                                </div>
                            </div>
                        </div>

                        @if($seleksi->tanggal_buka || $seleksi->tanggal_tutup)
                            <div class="d-flex gap-2 mb-3">
                                @if($seleksi->tanggal_buka)
                                    <small class="badge bg-light text-dark border">
                                        <i class="fas fa-calendar-check me-1 text-success"></i>
                                        {{ $seleksi->tanggal_buka->format('d/m/Y') }}
                                    </small>
                                @endif
                                @if($seleksi->tanggal_tutup)
                                    <small class="badge bg-light text-dark border">
                                        <i class="fas fa-calendar-times me-1 text-danger"></i>
                                        {{ $seleksi->tanggal_tutup->format('d/m/Y') }}
                                    </small>
                                @endif
                            </div>
                        @endif

                        <div class="d-flex gap-2 pt-3 border-top">
                            <a href="{{ route('seleksi_berkas.pengajuan', $seleksi) }}"
                               class="btn btn-sm btn-outline-primary flex-fill">
                                <i class="fas fa-list me-1"></i>Pengajuan
                            </a>
                            <a href="{{ route('seleksi_berkas.edit', $seleksi) }}"
                               class="btn btn-sm btn-outline-warning">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="{{ route('seleksi_berkas.show', $seleksi->slug) }}" target="_blank"
                               class="btn btn-sm btn-outline-success">
                                <i class="fas fa-external-link-alt"></i>
                            </a>
                            <form action="{{ route('seleksi_berkas.destroy', $seleksi) }}" method="POST"
                                  onsubmit="return confirm('Hapus seleksi berkas ini? Semua pengajuan akan ikut terhapus.')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="card border-0 shadow-sm text-center py-5">
                    <div class="card-body">
                        <i class="fas fa-folder-open fa-4x text-muted opacity-25 mb-3"></i>
                        <h5 class="text-muted">Belum Ada Seleksi Berkas</h5>
                        <p class="text-muted small">Buat formulir seleksi berkas pertama untuk mulai menerima pengajuan.</p>
                        <a href="{{ route('seleksi_berkas.create') }}" class="btn btn-success mt-2">
                            <i class="fas fa-plus me-2"></i>Buat Sekarang
                        </a>
                    </div>
                </div>
            </div>
        @endforelse
    </div>
</div>
@endsection
