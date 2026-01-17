@extends('layout.frontend')

@section('content')
    <!-- Hero Section (Small) -->
    <section class="py-5 bg-primary-custom text-white" style="margin-top: 50px;">
        <div class="container text-center">
            <h1 class="fw-bold mb-3" data-aos="fade-up">Data Keagamaan</h1>
            <p class="lead mb-0" data-aos="fade-up" data-aos-delay="100">Direktori Majelis Taklim, Masjid, dan Mushalla di
                Kabupaten Kutai Kartanegara</p>
        </div>
    </section>

    <!-- Content Section -->
    <section id="data-keagamaan" class="py-5 bg-light">
        <div class="container">
            <!-- Dashboard Stats -->
            <div class="row g-4 mb-5">
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="card border-0 shadow-sm h-100 py-3">
                        <div class="card-body d-flex align-items-center justify-content-between px-4">
                            <div>
                                <h6 class="text-muted text-uppercase fw-semibold mb-2">Majelis Taklim</h6>
                                <h2 class="fw-bold text-primary-custom mb-0">{{ number_format($totalMajelis ?? 0) }}</h2>
                            </div>
                            <div class="icon-box bg-primary-subtle-custom text-primary-custom rounded-circle p-3">
                                <i class="fas fa-users fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="card border-0 shadow-sm h-100 py-3">
                        <div class="card-body d-flex align-items-center justify-content-between px-4">
                            <div>
                                <h6 class="text-muted text-uppercase fw-semibold mb-2">Masjid</h6>
                                <h2 class="fw-bold text-primary-custom mb-0">{{ number_format($totalMasjid ?? 0) }}</h2>
                            </div>
                            <div class="icon-box bg-primary-subtle-custom text-primary-custom rounded-circle p-3">
                                <i class="fas fa-mosque fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="300">
                    <div class="card border-0 shadow-sm h-100 py-3">
                        <div class="card-body d-flex align-items-center justify-content-between px-4">
                            <div>
                                <h6 class="text-muted text-uppercase fw-semibold mb-2">Mushalla</h6>
                                <h2 class="fw-bold text-primary-custom mb-0">{{ number_format($totalMushalla ?? 0) }}</h2>
                            </div>
                            <div class="icon-box bg-primary-subtle-custom text-primary-custom rounded-circle p-3">
                                <i class="fas fa-building fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabs & Search -->
            <div class="row mb-4 align-items-center">
                <div class="col-lg-8 mb-3 mb-lg-0">
                    <ul class="nav nav-pills" id="pills-tab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link {{ $tab == 'majelis' ? 'active' : '' }} rounded-pill px-4"
                                href="{{ route('data_keagamaan', ['tab' => 'majelis']) }}">
                                <i class="fas fa-users me-2"></i>Majelis Taklim
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link {{ $tab == 'masjid' ? 'active' : '' }} rounded-pill px-4"
                                href="{{ route('data_keagamaan', ['tab' => 'masjid']) }}">
                                <i class="fas fa-mosque me-2"></i>Masjid
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link {{ $tab == 'mushalla' ? 'active' : '' }} rounded-pill px-4"
                                href="{{ route('data_keagamaan', ['tab' => 'mushalla']) }}">
                                <i class="fas fa-building me-2"></i>Mushalla
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="col-lg-4">
                    <form action="{{ route('data_keagamaan') }}" method="GET">
                        <input type="hidden" name="tab" value="{{ $tab }}">
                        <div class="input-group">
                            <input type="text" class="form-control rounded-start-pill border-0 shadow-sm"
                                placeholder="Cari nama..." name="search" value="{{ $search }}">
                            <button class="btn btn-primary-custom rounded-end-pill px-4 shadow-sm" type="submit">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Data Grid -->
            <div class="row g-4">
                @forelse($data as $item)
                    <div class="col-md-6 col-lg-4" data-aos="fade-up">
                        <div class="card h-100 border-0 shadow-sm hover-shadow transition-all">
                            <div class="card-body p-4">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="icon-shape bg-primary-subtle-custom text-primary-custom rounded-3 p-3 me-3">
                                        @if ($tab == 'majelis')
                                            <i class="fas fa-users fa-2x"></i>
                                        @elseif($tab == 'masjid')
                                            <i class="fas fa-mosque fa-2x"></i>
                                        @else
                                            <i class="fas fa-building fa-2x"></i>
                                        @endif
                                    </div>
                                    <div>
                                        <h5 class="card-title fw-bold mb-1 text-dark">
                                            @if ($tab == 'majelis')
                                                {{ $item->nama_majelis }}
                                            @elseif($tab == 'masjid')
                                                {{ $item->nama_masjid }}
                                            @else
                                                {{ $item->nama_mushalla }}
                                            @endif
                                        </h5>
                                        <small class="text-muted">
                                            @if ($tab == 'majelis')
                                                No. Statistik: {{ $item->nomor_statistik ?? '-' }}
                                            @elseif($tab == 'masjid')
                                                ID Masjid: {{ $item->nomor_id_masjid ?? '-' }}
                                            @else
                                                ID Mushalla: {{ $item->nomor_id_mushalla ?? '-' }}
                                            @endif
                                        </small>
                                    </div>
                                </div>

                                <hr class="my-3 opacity-10">

                                <div class="mb-3">
                                    <p class="mb-1 text-muted small"><i
                                            class="fas fa-map-marker-alt me-2 text-danger"></i>Alamat</p>
                                    <p class="mb-0 fw-medium text-dark">
                                        @if ($tab == 'majelis')
                                            {{ $item->alamat }}
                                        @elseif($tab == 'masjid')
                                            {{ $item->alamat_masjid }}
                                        @else
                                            {{ $item->alamat_mushalla }}
                                        @endif
                                    </p>
                                </div>

                                <div class="d-flex justify-content-between align-items-center mt-3 pt-3 border-top">
                                    <div>
                                        <span class="badge bg-light text-dark border mb-2">
                                            {{ ucwords($item->kecamatan->kecamatan ?? 'Kec. -') }}
                                        </span>
                                        <span class="badge bg-light text-dark border">
                                            {{ ucwords($item->kelurahan->nama_kelurahan ?? 'Kel. -') }}
                                        </span>
                                    </div>
                                    @if ($tab == 'masjid' && $item->tipologiMasjid)
                                        <span class="badge bg-success-subtle text-success">
                                            {{ $item->tipologiMasjid->nama_tipologi }}
                                        </span>
                                    @elseif($tab == 'mushalla' && $item->tipologiMushalla)
                                        <span class="badge bg-info-subtle text-info">
                                            {{ $item->tipologiMushalla->nama_tipologi }}
                                        </span>
                                    @elseif($tab == 'majelis')
                                        <span class="badge bg-primary-subtle-custom text-primary-custom">Majelis
                                            Taklim</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center py-5">
                        <img src="{{ asset('voler/assets/images/samples/error-404.png') }}" alt="Empty"
                            class="img-fluid mb-3" style="max-height: 200px; opacity: 0.5;">
                        <h5 class="text-muted">Belum ada data ditemukan untuk kategori ini.</h5>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            <div class="mt-5 d-flex justify-content-center">
                {{ $data->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </section>

    <style>
        .hover-shadow:hover {
            transform: translateY(-5px);
            box-shadow: 0 .5rem 1rem rgba(0, 0, 0, .15) !important;
        }

        .transition-all {
            transition: all 0.3s ease;
        }

        /* Custom Theme Colors */
        .btn-primary-custom {
            background-color: var(--primary);
            border-color: var(--primary);
            color: white;
        }

        .btn-primary-custom:hover {
            background-color: var(--secondary);
            border-color: var(--secondary);
            color: white;
        }

        .bg-primary-subtle-custom {
            background-color: rgba(42, 157, 143, 0.15);
            /* #2A9D8F with opacity */
        }

        /* Custom Tab Colors */
        .nav-pills .nav-link.active {
            background-color: var(--primary) !important;
            color: white !important;
        }

        .nav-pills .nav-link {
            color: var(--primary);
        }

        .nav-pills .nav-link:hover {
            background-color: #e9ecef;
            color: var(--secondary);
        }

        #data-keagamaan .card {
            cursor: default !important;
        }
    </style>
@endsection
