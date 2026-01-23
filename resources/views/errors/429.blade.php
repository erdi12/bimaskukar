@extends('layout.frontend')

@section('title', 'Terlalu Banyak Permintaan - Bimas Islam Kutai Kartanegara')

@section('content')
    <section class="py-5 mt-5 min-vh-100 d-flex flex-column justify-content-center align-items-center bg-light">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-6 col-md-8 text-center">
                    <div class="mb-4">
                        <i class="bi bi-shield-exclamation text-warning" style="font-size: 5rem;"></i>
                    </div>

                    <h1 class="display-4 fw-bold text-dark mb-3">Terlalu Banyak Permintaan</h1>

                    <div class="alert alert-warning border-0 shadow-sm mb-4" role="alert">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        <strong>Anda telah melebihi batas pencarian.</strong>
                    </div>

                    <p class="lead text-muted mb-4">
                        Untuk keamanan sistem, kami membatasi jumlah pencarian menjadi <strong>10 kali per menit</strong>.
                    </p>

                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-body p-4">
                            <h5 class="card-title fw-bold mb-3">
                                <i class="bi bi-info-circle text-primary me-2"></i>
                                Apa yang harus dilakukan?
                            </h5>
                            <ul class="list-unstyled text-start mb-0">
                                <li class="mb-2">
                                    <i class="bi bi-check-circle text-success me-2"></i>
                                    Tunggu <strong>1 menit</strong> sebelum mencoba lagi
                                </li>
                                <li class="mb-2">
                                    <i class="bi bi-check-circle text-success me-2"></i>
                                    Pastikan data yang Anda masukkan sudah benar
                                </li>
                                <li class="mb-2">
                                    <i class="bi bi-check-circle text-success me-2"></i>
                                    Jika masalah berlanjut, hubungi admin
                                </li>
                            </ul>
                        </div>
                    </div>

                    <div class="d-flex gap-2 justify-content-center">
                        <a href="{{ route('cek_validitas') }}" class="btn btn-primary btn-lg px-4">
                            <i class="bi bi-arrow-clockwise me-2"></i>
                            Coba Lagi
                        </a>
                        <a href="{{ route('home') }}" class="btn btn-outline-secondary btn-lg px-4">
                            <i class="bi bi-house-door me-2"></i>
                            Kembali ke Beranda
                        </a>
                    </div>

                    <div class="mt-4">
                        <small class="text-muted">
                            <i class="bi bi-shield-check me-1"></i>
                            Pembatasan ini diterapkan untuk melindungi data pribadi Anda
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
