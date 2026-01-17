@extends('layout.frontend')

@section('title', 'Kontak Kami - Bimas Islam Kemenag Kukar')

@section('content')

    <!-- Header Section -->
    <section class="py-5 mt-5 bg-primary-custom text-white position-relative overflow-hidden">
        <div class="arabesque-bg position-absolute top-0 start-0 w-100 h-100 opacity-10"></div>
        <div class="container position-relative z-1 text-center">
            <h1 class="font-weight-bold display-5">Hubungi Kami</h1>
            <p class="lead mb-0">Layanan Informasi & Pengaduan Masyarakat</p>
        </div>
    </section>

    <!-- Breadcrumb -->
    <div class="bg-light py-2">
        <div class="container">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}"
                            class="text-decoration-none text-primary-custom">Beranda</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Kontak</li>
                </ol>
            </nav>
        </div>
    </div>

    @include('frontend.sections.kontak')

@endsection
