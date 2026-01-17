    <!-- Sambutan Section -->
    <section id="profil" class="py-5" style="background-color: rgba(42, 157, 143, 0.05);">
        <div class="container">
            <div class="row align-items-center gy-4">
                <div class="col-md-4">
                    @if ($kepala && $kepala->foto)
                        <img src="{{ asset('uploads/pegawai/' . $kepala->foto) }}" class="img-fluid rounded shadow"
                            alt="{{ $kepala->nama }}">
                    @else
                        <img src="{{ asset('voler/assets/images/kepala_seksi.png') }}" class="img-fluid rounded shadow"
                            alt="Kepala Seksi Bimas Islam">
                    @endif
                </div>
                <div class="col-md-8">
                    <h2 class="text-secondary-custom fw-semibold mb-3">Sambutan Kepala Seksi</h2>
                    @if ($kepala && $kepala->sambutan)
                        <div class="text-muted mb-4" style="white-space: pre-line;">{!! nl2br(e($kepala->sambutan)) !!}</div>
                    @else
                        <p class="text-muted mb-4">
                            Puji syukur ke hadirat Allah SWT atas tersedianya media informasi ini sebagai sarana
                            komunikasi, publikasi, dan transparansi layanan Bimbingan Masyarakat Islam di lingkungan
                            Kementerian Agama Kabupaten Kutai Kartanegara.
                        </p>
                    @endif

                    <div class="border-start border-4 ps-3" style="border-color: var(--accent) !important;">
                        <p class="text-primary-custom fw-semibold mb-0">{{ $kepala->nama ?? 'Nama Kepala Seksi' }}</p>
                        <small class="text-muted">{{ $kepala->jabatan ?? 'Kepala Seksi Bimas Islam' }}</small>
                    </div>

                    @if (request()->routeIs('home'))
                        <div class="mt-4">
                            <a href="{{ route('profil') }}" class="btn btn-outline-secondary rounded-pill px-4">
                                Lihat Profil & Tim Lengkap <i class="bi bi-arrow-right ms-1"></i>
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
