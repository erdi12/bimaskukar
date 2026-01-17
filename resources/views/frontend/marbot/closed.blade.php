@extends('layout.frontend')

@section('title', 'Pendaftaran Ditutup')

@section('content')
    <div class="container py-5 mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8 text-center">
                <div class="card shadow border-0 rounded-4 p-5">
                    <div class="card-body">
                        <div class="mb-4">
                            <i class="fas fa-calendar-times text-danger fa-4x"></i>
                        </div>
                        <h2 class="fw-bold text-dark mb-3">Registrasi Ditutup</h2>
                        <p class="lead text-muted mb-4">
                            Mohon maaf, pendaftaran Nomor Induk Marbot saat ini sedang <strong class="text-danger">TIDAK
                                AKTIF</strong> atau telah melewati batas waktu yang ditentukan.
                        </p>

                        @if (isset($start) && isset($end))
                            <div class="alert alert-warning d-inline-block px-4 py-3 rounded-3">
                                <i class="fas fa-clock me-2"></i> Jadwal Pendaftaran:
                                <strong>{{ $start->translatedFormat('d F Y') }}</strong> s/d
                                <strong>{{ $end->translatedFormat('d F Y') }}</strong>
                            </div>
                        @endif

                        <div class="mt-4">
                            <a href="{{ url('/') }}" class="btn btn-primary px-4 py-2 rounded-pill">
                                <i class="fas fa-home me-2"></i> Kembali ke Beranda
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
