@extends('layout.frontend')

@section('title', $layanan->judul . ' - Bimas Islam Kukar')

@section('content')

    <style>
        .content-body ul,
        .content-body ol {
            margin-bottom: 1rem;
        }

        .content-body li {
            margin-bottom: 0.5rem;
        }

        .content-body table {
            width: 100%;
            margin-bottom: 1rem;
            color: #212529;
            vertical-align: top;
            border-color: #dee2e6;
        }

        .content-body table th,
        .content-body table td {
            padding: 0.75rem;
            border: 1px solid #dee2e6;
        }
    </style>
    <section class="py-5 mt-5 bg-primary-custom text-white position-relative overflow-hidden">
        <div class="container position-relative z-index-1">
            <div class="row justify-content-center text-center">
                <div class="col-lg-8">
                    <div class="mb-3">
                        @if (Str::startsWith($layanan->ikon, 'fa'))
                            <i class="{{ $layanan->ikon }} display-4 display-md-3"></i>
                        @else
                            <span class="display-4 display-md-3">{{ $layanan->ikon }}</span>
                        @endif
                    </div>
                    <h1 class="fw-bold mb-3 fs-2 fs-md-1">{{ $layanan->judul }}</h1>
                    <p class="lead opacity-75 fs-6 fs-md-4">{{ $layanan->deskripsi_singkat }}</p>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb justify-content-center mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}"
                                    class="text-white text-decoration-none small">Beranda</a></li>
                            <li class="breadcrumb-item active text-white opacity-75 small" aria-current="page">Layanan</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        <div class="position-absolute top-0 start-0 translate-middle rounded-circle bg-white opacity-10 d-none d-md-block"
            style="width: 300px; height: 300px;"></div>
        <div class="position-absolute bottom-0 end-0 translate-middle rounded-circle bg-white opacity-10 d-none d-md-block"
            style="width: 200px; height: 200px;"></div>
    </section>

    <section class="py-5 bg-light">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                        <div class="card-body p-4 p-md-5">
                            <div class="d-flex align-items-center mb-4 pb-3 border-bottom">
                                <h3 class="fw-semibold text-secondary-custom mb-0">Standar Operasional Prosedur (SOP) &
                                    Persyaratan</h3>
                            </div>

                            <div class="content-body text-dark" style="font-size: 1.05rem; line-height: 1.8;">
                                {!! $layanan->konten !!}
                            </div>

                            <div class="mt-5 pt-4 border-top text-center">
                                <p class="text-muted mb-3">Butuh bantuan lebih lanjut?</p>
                                <a href="https://wa.me/+6282149614962" class="btn btn-success rounded-pill px-4">
                                    <i class="bi bi-whatsapp me-2"></i> Hubungi Kami via WhatsApp
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
