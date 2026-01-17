@extends('layout.frontend')

@section('title', 'Profil & Tim - Bimas Islam Kukar')

@section('content')

    <!-- Page Header -->
    <header class="bg-light py-5 mt-5">
        <div class="container text-center pt-4">
            <h1 class="fw-bold text-secondary-custom">Profil Bimas Islam</h1>
            <p class="text-muted">Mengenal lebih dekat struktur dan tim kami.</p>
        </div>
    </header>

    <!-- Gunakan Section yang sudah ada agar konsisten dan DRY (Don't Repeat Yourself) -->

    <!-- Sambutan Kepala Seksi -->


    <!-- Daftar Tim -->
    @include('frontend.sections.tim')

@endsection
