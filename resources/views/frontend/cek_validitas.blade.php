@extends('layout.frontend')

@section('title', 'Cek Validitas SKT - Bimas Islam Kutai Kartanegara')

@section('content')
    <section class="py-5 mt-5 arabesque-bg min-vh-100 d-flex flex-column justify-content-center align-items-center">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8 col-md-10">
                    <div class="text-center mb-5">
                        <span class="badge bg-primary-custom bg-opacity-10 text-white px-3 py-2 rounded-pill mb-3">
                            <i class="bi bi-shield-check me-2"></i>Verifikasi Dokumen
                        </span>
                        <h1 class="display-5 fw-bold text-secondary-custom mb-3">Cek Validitas SKT</h1>
                        <p class="text-muted lead">
                            Pastikan keaslian Surat Keterangan Terdaftar (SKT) dan Piagam Majelis Taklim, Masjid, atau
                            Mushalla.
                        </p>
                    </div>

                    <div class="card border-0 shadow-lg rounded-4 overflow-hidden mb-5">
                        <div class="card-body p-4 p-md-5">
                            <form action="{{ route('cek_validitas') }}" method="GET">
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <label for="type" class="form-label fw-semibold">Jenis Lembaga</label>
                                        <select class="form-select border-2" id="type" name="type" required>
                                            <option value="" disabled {{ request('type') ? '' : 'selected' }}>Pilih
                                                Jenis...</option>
                                            <option value="majelis_taklim"
                                                {{ request('type') == 'majelis_taklim' ? 'selected' : '' }}>Majelis Taklim
                                            </option>
                                            <option value="masjid" {{ request('type') == 'masjid' ? 'selected' : '' }}>
                                                Masjid</option>
                                            <option value="mushalla" {{ request('type') == 'mushalla' ? 'selected' : '' }}>
                                                Mushalla</option>
                                            <option value="marbot" {{ request('type') == 'marbot' ? 'selected' : '' }}>
                                                Marbot Masjid</option>
                                        </select>
                                    </div>
                                    <div class="col-md-8">
                                        <label for="keyword" class="form-label fw-semibold">Nomor Statistik / ID /
                                            NIK</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control border-2" id="keyword"
                                                name="keyword" placeholder="Masukkan Nomor Statistik, ID, atau NIK..."
                                                value="{{ request('keyword') }}" required>
                                            <button class="btn bg-primary-custom text-white fw-bold px-4" type="submit">
                                                <i class="bi bi-search me-2"></i>Cari
                                            </button>
                                        </div>
                                        <div class="form-text mt-2"><i class="bi bi-info-circle me-1"></i> Masukkan nomor
                                            identitas yang sesuai dengan dokumen.</div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    @if (request()->has('type'))
                        @if ($result)
                            <div class="card border-0 shadow rounded-4 border-start border-5 border-success position-relative overflow-hidden fade-in-up"
                                style="cursor: default !important;">
                                <div class="card-body p-4">
                                    <div class="d-flex align-items-center mb-4">
                                        <div class="bg-success bg-opacity-10 text-success rounded-circle p-3 me-3">
                                            <i class="bi bi-check-circle-fill fs-3"></i>
                                        </div>
                                        <div>
                                            <h4 class="mb-1 text-success fw-bold">Data Ditemukan</h4>
                                            <p class="mb-0 text-muted">Data tercatat dalam database kami.</p>
                                        </div>
                                    </div>

                                    <div class="row g-4">
                                        <div class="col-md-6">
                                            <h6 class="text-uppercase text-muted fs-7 fw-bold mb-2">
                                                @if ($type == 'majelis_taklim')
                                                    Nama Majelis Taklim
                                                @elseif($type == 'masjid')
                                                    Nama Masjid
                                                @elseif($type == 'mushalla')
                                                    Nama Mushalla
                                                @elseif($type == 'marbot')
                                                    Nama Marbot
                                                @endif
                                            </h6>
                                            @if ($type == 'majelis_taklim')
                                                <h5 class="fw-bold text-dark">{{ $result->nama_majelis }}</h5>
                                            @elseif($type == 'masjid')
                                                <h5 class="fw-bold text-dark">{{ $result->nama_masjid }}</h5>
                                            @elseif($type == 'mushalla')
                                                <h5 class="fw-bold text-dark">{{ $result->nama_mushalla }}</h5>
                                            @elseif($type == 'marbot')
                                                <h5 class="fw-bold text-dark">{{ $result->nama_lengkap }}</h5>
                                            @endif
                                        </div>
                                        <div class="col-md-6">
                                            <h6 class="text-uppercase text-muted fs-7 fw-bold mb-2">ID / Nomor
                                            </h6>
                                            @if ($type == 'majelis_taklim')
                                                <h5 class="font-monospace text-secondary">{{ $result->nomor_statistik }}
                                                </h5>
                                            @elseif($type == 'masjid')
                                                <h5 class="font-monospace text-secondary">{{ $result->nomor_id_masjid }}
                                                </h5>
                                            @elseif($type == 'mushalla')
                                                <h5 class="font-monospace text-secondary">{{ $result->nomor_id_mushalla }}
                                                </h5>
                                            @elseif($type == 'marbot')
                                                <h5 class="font-monospace text-secondary">NIK: {{ $result->nik }}</h5>
                                                @if ($result->nomor_induk_marbot)
                                                    <span class="badge bg-success">NIM:
                                                        {{ $result->nomor_induk_marbot }}</span>
                                                @endif
                                            @endif
                                        </div>
                                        <div class="col-12">
                                            <h6 class="text-uppercase text-muted fs-7 fw-bold mb-2">Alamat Lengkap</h6>
                                            <p class="mb-0 text-dark">
                                                @if ($type == 'majelis_taklim')
                                                    {{ $result->alamat }}
                                                @elseif($type == 'masjid')
                                                    {{ $result->alamat_masjid }}
                                                @elseif($type == 'mushalla')
                                                    {{ $result->alamat_mushalla }}
                                                @elseif($type == 'marbot')
                                                    {{ $result->alamat }}
                                                @endif
                                                <br>
                                                <small class="text-muted">
                                                    {{ $result->kelurahan->nama_kelurahan ?? '-' }}, Kec.
                                                    {{ $result->kecamatan->kecamatan ?? '-' }}
                                                </small>
                                            </p>
                                        </div>
                                        <div class="col-md-6">
                                            <h6 class="text-uppercase text-muted fs-7 fw-bold mb-2">Status</h6>
                                            @if ($type == 'marbot')
                                                @if ($result->status == 'disetujui')
                                                    <span class="badge bg-success rounded-pill px-3 py-2"><i
                                                            class="bi bi-check-circle me-1"></i> Disetujui</span>
                                                @elseif ($result->status == 'perbaikan')
                                                    <span class="badge bg-danger rounded-pill px-3 py-2"><i
                                                            class="bi bi-exclamation-circle me-1"></i> Perlu
                                                        Perbaikan</span>
                                                    <div class="alert alert-warning mt-2 small">
                                                        <strong>Catatan Admin:</strong><br>
                                                        {{ $result->catatan }}
                                                    </div>
                                                    <div class="mt-2">
                                                        <a href="{{ route('marbot.frontend.edit', ['id' => $result->uuid, 'nik' => $result->nik]) }}"
                                                            class="btn btn-sm btn-warning fw-bold w-100">
                                                            <i class="bi bi-pencil-square me-1"></i> Perbaiki Data
                                                        </a>
                                                    </div>
                                                @else
                                                    <span class="badge bg-warning text-dark rounded-pill px-3 py-2"><i
                                                            class="bi bi-hourglass-split me-1"></i> Sedang Diajukan</span>
                                                @endif
                                            @elseif ($type == 'majelis_taklim')
                                                @if ($result->status == 'aktif')
                                                    <span class="badge bg-success rounded-pill px-3 py-2"><i
                                                            class="bi bi-check-circle me-1"></i> Aktif</span>
                                                @elseif ($result->status == 'belum_update')
                                                    <span class="badge bg-warning text-dark rounded-pill px-3 py-2"><i
                                                            class="bi bi-exclamation-triangle me-1"></i> Belum Update</span>
                                                @else
                                                    <span class="badge bg-danger rounded-pill px-3 py-2"><i
                                                            class="bi bi-x-circle me-1"></i>
                                                        {{ ucfirst($result->status) }}</span>
                                                @endif
                                                <div class="mt-2 text-muted small">
                                                    Masa Berlaku SD:
                                                    {{ \Carbon\Carbon::parse($result->mendaftar_ulang)->isoFormat('D MMMM Y') }}
                                                </div>
                                            @else
                                                <span class="badge bg-success rounded-pill px-3 py-2"><i
                                                        class="bi bi-check-circle me-1"></i> Terdaftar</span>
                                                <div class="mt-2 text-muted small">
                                                    Tipologi:
                                                    {{ $type == 'masjid' ? $result->tipologiMasjid->nama_tipologi ?? '-' : $result->tipologiMushalla->nama_tipologi ?? '-' }}
                                            @endif

                                            <div class="mt-4 pt-3 border-top">
                                                <h6 class="text-uppercase text-muted fs-7 fw-bold mb-2">Dokumen Digital
                                                </h6>
                                                <div class="d-flex flex-wrap gap-2">
                                                    @if ($type == 'majelis_taklim')
                                                        @if ($result->status == 'nonaktif' || $result->status == 'belum_update')
                                                            <div class="alert alert-warning border-0 d-flex align-items-center mb-0 w-100 bg-warning bg-opacity-10 text-warning-emphasis"
                                                                role="alert">
                                                                <i class="bi bi-exclamation-circle-fill fs-5 me-2"></i>
                                                                <div class="fw-semibold">
                                                                    Tidak dapat mendownload file, silahkan mengurus
                                                                    keaktifan
                                                                    Majelis Taklim terlebih dahulu.
                                                                </div>
                                                            </div>
                                                        @else
                                                            @if ($result->file_skt)
                                                                <a href="{{ asset('storage/skt/' . $result->file_skt) }}"
                                                                    target="_blank" class="text-decoration-none">
                                                                    <span
                                                                        class="badge bg-primary bg-opacity-10 text-primary border border-primary hover-scale">
                                                                        <i class="bi bi-download me-1"></i> Download SKT
                                                                    </span>
                                                                </a>
                                                            @else
                                                                <span
                                                                    class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary"><i
                                                                        class="bi bi-x-circle me-1"></i> SKT Belum
                                                                    Ada</span>
                                                            @endif

                                                            @if ($result->file_piagam)
                                                                <a href="{{ asset('storage/piagam/' . $result->file_piagam) }}"
                                                                    target="_blank" class="text-decoration-none">
                                                                    <span
                                                                        class="badge bg-primary bg-opacity-10 text-primary border border-primary hover-scale">
                                                                        <i class="bi bi-download me-1"></i> Download Piagam
                                                                    </span>
                                                                </a>
                                                            @else
                                                                <span
                                                                    class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary"><i
                                                                        class="bi bi-x-circle me-1"></i> Piagam Belum
                                                                    Ada</span>
                                                            @endif
                                                        @endif
                                                    @else
                                                        {{-- Untuk Masjid & Mushalla --}}
                                                        @if (isset($result->file_skt_masjid) || isset($result->file_skt) || isset($result->file_skt_mushalla))
                                                            {{-- Cek field file_skt yang umum atau spesifik --}}
                                                            @php
                                                                $fileSkt = null;
                                                                $folder = '';
                                                                if ($type == 'masjid' && $result->file_skt) {
                                                                    $fileSkt = $result->file_skt;
                                                                    $folder = 'masjid_skt';
                                                                }
                                                                if ($type == 'mushalla' && $result->file_skt) {
                                                                    $fileSkt = $result->file_skt;
                                                                    $folder = 'mushalla_skt';
                                                                }
                                                            @endphp

                                                            @if ($fileSkt)
                                                                <a href="{{ asset('storage/' . $folder . '/' . $fileSkt) }}"
                                                                    target="_blank" class="text-decoration-none">
                                                                    <span
                                                                        class="badge bg-primary bg-opacity-10 text-primary border border-primary hover-scale">
                                                                        <i class="bi bi-download me-1"></i> Download SKT
                                                                    </span>
                                                                </a>
                                                            @else
                                                                <span
                                                                    class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary"><i
                                                                        class="bi bi-x-circle me-1"></i> SKT Belum
                                                                    Ada</span>
                                                            @endif
                                                        @endif
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="alert alert-danger border-0 shadow rounded-4 d-flex align-items-center p-4 fade-in-up"
                                role="alert">
                                <div class="bg-danger bg-opacity-10 text-danger rounded-circle p-3 me-3">
                                    <i class="bi bi-exclamation-triangle-fill fs-3"></i>
                                </div>
                                <div>
                                    <h4 class="alert-heading fw-bold mb-1">Data Tidak Ditemukan!</h4>
                                    <p class="mb-0">Mohon periksa kembali jenis lembaga dan nomor statistik/ID yang Anda
                                        masukkan. Pastikan tidak ada kesalahan pengetikan.</p>
                                </div>
                            </div>
                        @endif
                    @endif

                </div>
            </div>
        </div>
    </section>

    @push('styles')
        <style>
            .bg-primary-custom {
                background-color: #2A9D8F;
            }

            .text-primary-custom {
                color: #2A9D8F;
            }

            .text-secondary-custom {
                color: #264653;
            }

            .arabesque-bg {
                background-color: #f8f9fa;
                background-image: radial-gradient(#2A9D8F 0.5px, transparent 0.5px), radial-gradient(#2A9D8F 0.5px, #f8f9fa 0.5px);
                background-size: 20px 20px;
                background-position: 0 0, 10px 10px;
                opacity: 1;
            }

            .fade-in-up {
                animation: fadeInUp 0.5s ease-out;
            }

            @keyframes fadeInUp {
                from {
                    opacity: 0;
                    transform: translateY(20px);
                }

                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }
        </style>
    @endpush
@endsection
