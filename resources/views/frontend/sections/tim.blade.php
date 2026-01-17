    <!-- Tim Section -->
    <section class="py-5 bg-white">
        <div class="container">
            <h3 class="text-center text-secondary-custom fw-semibold mb-5">Tim Bimas Islam</h3>

            {{-- Bagian Kepala Seksi (Paling Atas & Tengah) --}}
            @if (isset($kepala) && $kepala)
                <div class="row justify-content-center mb-5">
                    <div class="col-md-4 col-sm-8">
                        <div class="card h-100 border-0 shadow-sm text-center p-3">
                            <div class="mx-auto mb-3" style="width: 180px; height: 240px;">
                                <!-- Sedikit lebih besar untuk Kepala -->
                                @if ($kepala->foto)
                                    <img src="{{ asset('uploads/pegawai/' . $kepala->foto) }}"
                                        class="img-fluid rounded-4 w-100 h-100 object-fit-cover shadow-sm"
                                        style="object-position: top;" alt="{{ $kepala->nama }}">
                                @else
                                    <div
                                        class="w-100 h-100 rounded-4 bg-light d-flex align-items-center justify-content-center text-muted border">
                                        <i class="bi bi-person-fill fs-1"></i>
                                    </div>
                                @endif
                            </div>
                            <h5 class="fw-bold text-secondary-custom mb-1">{{ $kepala->nama }}</h5>
                            <p class="badge bg-warning text-dark rounded-pill mx-auto">{{ $kepala->jabatan }}</p>
                            {{-- <span class="badge bg-warning text-dark rounded-pill mx-auto px-3">Kepala Seksi</span> --}}
                        </div>
                    </div>
                </div>
            @endif

            {{-- Bagian Staff --}}
            <div class="row g-4 justify-content-center">
                @foreach ($staffs as $pegawai)
                    <div class="col-md-3 col-sm-6">
                        <div class="card h-100 border-0 shadow-sm text-center p-3">
                            <div class="mx-auto mb-3" style="width: 160px; height: 210px;">
                                @if ($pegawai->foto)
                                    <img src="{{ asset('uploads/pegawai/' . $pegawai->foto) }}"
                                        class="img-fluid rounded-4 w-100 h-100 object-fit-cover shadow-sm"
                                        style="object-position: top;" alt="{{ $pegawai->nama }}">
                                @else
                                    <div
                                        class="w-100 h-100 rounded-4 bg-light d-flex align-items-center justify-content-center text-muted border">
                                        <i class="bi bi-person-fill fs-1"></i>
                                    </div>
                                @endif
                            </div>
                            <h6 class="fw-bold text-secondary-custom mb-1">{{ $pegawai->nama }}</h6>
                            <p class="text-muted small mb-1">{{ $pegawai->jabatan }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
