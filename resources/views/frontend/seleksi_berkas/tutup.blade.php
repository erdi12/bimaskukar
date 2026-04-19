@extends('layout.frontend')

@section('title', $seleksi->judul . ' - Pendaftaran Ditutup')

@section('content')
<div class="container py-5 mt-4">
    <div class="row justify-content-center">
        <div class="col-md-7 col-lg-5 text-center">
            <div class="card border-0 shadow-sm rounded-4 p-5">
                <div class="mb-4">
                    <div class="d-inline-flex align-items-center justify-content-center bg-secondary bg-opacity-10 rounded-circle"
                         style="width:100px;height:100px;">
                        <i class="fas fa-lock text-secondary" style="font-size:2.5rem;"></i>
                    </div>
                </div>
                <h3 class="fw-bold text-dark">Seleksi Ditutup</h3>
                <h5 class="text-muted mb-3">{{ $seleksi->judul }}</h5>

                @if($seleksi->deskripsi)
                    <p class="text-muted mb-4">{{ $seleksi->deskripsi }}</p>
                @endif

                @if(!$seleksi->is_active)
                    <div class="alert alert-secondary border-0 rounded-3 mb-4">
                        <i class="fas fa-info-circle me-2"></i>
                        Seleksi berkas ini saat ini tidak aktif.
                    </div>
                @elseif($seleksi->tanggal_buka && now()->lt($seleksi->tanggal_buka))
                    <div class="alert alert-info border-0 rounded-3 mb-4">
                        <i class="fas fa-calendar-check me-2"></i>
                        Pendaftaran akan dibuka pada:
                        <br><strong>{{ $seleksi->tanggal_buka->translatedFormat('d F Y') }}</strong>
                    </div>
                @elseif($seleksi->tanggal_tutup && now()->gt($seleksi->tanggal_tutup))
                    <div class="alert alert-warning border-0 rounded-3 mb-4">
                        <i class="fas fa-calendar-times me-2"></i>
                        Pendaftaran telah ditutup pada:
                        <br><strong>{{ $seleksi->tanggal_tutup->translatedFormat('d F Y') }}</strong>
                    </div>
                @endif

                <div class="d-grid gap-2">
                    <a href="{{ route('cek_tiket') }}" class="btn btn-success rounded-pill fw-bold">
                        <i class="fas fa-search me-2"></i>Cek Status Berkas Saya
                    </a>
                    <a href="{{ route('home') }}" class="btn btn-outline-secondary rounded-pill">
                        <i class="fas fa-home me-2"></i>Kembali ke Beranda
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
