<style>
    .card-gradient,
    .card-modern {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        cursor: default;
        /* Indikator visual halus */
    }

    .card-gradient:hover,
    .card-modern:hover {
        transform: translateY(-10px);
        box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1) !important;
        z-index: 5;
    }

    #dashboardTabs .nav-link {
        color: #198754;
    }

    #dashboardTabs .nav-link.active {
        color: #000000 !important;
    }
</style>
<div class="content">
    <div class="page-title">
        <h3>Dashboard</h3>
    </div>

    <!-- Real data provided by DashboardController@v2 -->

    <section class="section">
        <!-- Early Warning System -->
        @if (isset($earlyWarnings) && count($earlyWarnings) > 0)
            <div class="row mb-4">
                <div class="col-12">
                    <div class="alert alert-warning border-0 shadow-sm d-flex align-items-center" role="alert">
                        <div class="bg-warning bg-opacity-25 p-3 rounded-circle me-3">
                            <i class="fas fa-exclamation-triangle text-warning fa-2x"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h4 class="alert-heading fw-bold mb-1">Peringatan Dini!</h4>
                            <p class="mb-0">Terdapat <strong>{{ count($earlyWarnings) }}</strong> Majelis Taklim yang
                                masa berlaku SKT akan habis dalam waktu kurang dari 30 hari.</p>
                        </div>
                        <div>
                            <button class="btn btn-warning btn-sm fw-bold shadow-sm" type="button"
                                data-bs-toggle="collapse" data-bs-target="#collapseWarning" aria-expanded="false"
                                aria-controls="collapseWarning">
                                <i class="fas fa-eye me-1"></i> Lihat Detail
                            </button>
                        </div>
                    </div>

                    <div class="collapse" id="collapseWarning">
                        <div class="card border-0 shadow-sm">
                            <div class="card-header bg-warning bg-opacity-10">
                                <h5 class="mb-0 fw-bold text-dark">Daftar Majelis Taklim Segera Habis Masa Berlaku</h5>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-hover mb-0 align-middle">
                                        <thead class="bg-light">
                                            <tr>
                                                <th class="px-4 py-3">Nama Majelis</th>
                                                <th class="py-3">Nomor Statistik</th>
                                                <th class="py-3">Kecamatan</th>
                                                <th class="py-3">Tgl Berakhir</th>
                                                <th class="py-3">Sisa Waktu</th>
                                                <th class="px-4 py-3 text-end">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($earlyWarnings as $warning)
                                                @php
                                                    $sisaHari = \Carbon\Carbon::now()->diffInDays(
                                                        \Carbon\Carbon::parse($warning->mendaftar_ulang),
                                                        false,
                                                    );
                                                    $sisaHari = ceil($sisaHari);
                                                @endphp
                                                <tr>
                                                    <td class="px-4 fw-bold">{{ $warning->nama_majelis }}</td>
                                                    <td><span
                                                            class="font-monospace">{{ $warning->nomor_statistik }}</span>
                                                    </td>
                                                    <td>{{ ucwords($warning->kecamatan->kecamatan) ?? '-' }}</td>
                                                    <td>{{ \Carbon\Carbon::parse($warning->mendaftar_ulang)->format('d M Y') }}
                                                    </td>
                                                    <td>
                                                        @if ($sisaHari < 0)
                                                            <span class="badge bg-danger">Lewat {{ abs($sisaHari) }}
                                                                hari</span>
                                                        @else
                                                            <span class="badge bg-warning text-dark">{{ $sisaHari }}
                                                                hari lagi</span>
                                                        @endif
                                                    </td>
                                                    <td class="px-4 text-end">
                                                        @if ($warning->no_hp)
                                                            @php
                                                                $hp = preg_replace(
                                                                    '/^0/',
                                                                    '62',
                                                                    preg_replace('/[^0-9]/', '', $warning->no_hp),
                                                                );
                                                                $nama = $warning->nama_majelis;
                                                                $tgl = $warning->mendaftar_ulang
                                                                    ? \Carbon\Carbon::parse(
                                                                        $warning->mendaftar_ulang,
                                                                    )->format('d-m-Y')
                                                                    : '-';
                                                                $pesan = rawurlencode(
                                                                    "Assalamu'alaikum, Admin SIBERKAT menginformasikan bahwa masa berlaku SKT Majelis Taklim *$nama* akan/telah habis pada tanggal *$tgl*. Mohon segera lakukan perpanjangan/daftar ulang. Terimakasih.",
                                                                );
                                                            @endphp
                                                            <a href="https://wa.me/{{ $hp }}?text={{ $pesan }}"
                                                                class="btn btn-sm btn-success me-1" target="_blank">
                                                                <i class="fab fa-whatsapp me-1"></i> Ingatkan WA
                                                            </a>
                                                        @endif
                                                        <a href="{{ route('skt_piagam_mt_v2.edit', $warning->id) }}"
                                                            class="btn btn-sm btn-outline-primary">
                                                            <i class="fas fa-edit me-1"></i> Perbarui
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        <!-- Statistik Sistem (Kecamatan, Kelurahan, User) -->
        <div class="row mb-2">
            <div class="col-12 col-md-4 mb-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body d-flex align-items-center p-3">
                        <div class="bg-primary bg-opacity-10 p-3 rounded-circle me-3">
                            <i class="fas fa-map-marked-alt text-primary fa-2x"></i>
                        </div>
                        <div>
                            <p class="text-muted mb-0 small text-uppercase fw-bold">Total Kecamatan</p>
                            <h4 class="mb-0 fw-bold text-dark">{{ $totalKecamatan ?? 0 }}</h4>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-4 mb-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body d-flex align-items-center p-3">
                        <div class="bg-success bg-opacity-10 p-3 rounded-circle me-3">
                            <i class="fas fa-map-signs text-success fa-2x"></i>
                        </div>
                        <div>
                            <p class="text-muted mb-0 small text-uppercase fw-bold">Total Kelurahan</p>
                            <h4 class="mb-0 fw-bold text-dark">{{ $totalKelurahan ?? 0 }}</h4>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-4 mb-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body d-flex align-items-center p-3">
                        <div class="bg-info bg-opacity-10 p-3 rounded-circle me-3">
                            <i class="fas fa-users text-info fa-2x"></i>
                        </div>
                        <div>
                            <p class="text-muted mb-0 small text-uppercase fw-bold">User System</p>
                            <h4 class="mb-0 fw-bold text-dark">{{ $totalUser ?? 0 }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistik Rumah Ibadah -->
        <div class="row mb-4">
            <div class="col-12 col-md-6 mb-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body d-flex align-items-center p-3">
                        <div class="bg-primary bg-opacity-10 p-3 rounded-circle me-3">
                            <i class="fas fa-mosque text-primary fa-2x"></i>
                        </div>
                        <div>
                            <p class="text-muted mb-0 small text-uppercase fw-bold">Total Masjid</p>
                            <h3 class="mb-0 fw-bold text-dark">{{ $totalMasjid ?? 0 }}</h3>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-6 mb-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body d-flex align-items-center p-3">
                        <div class="bg-success bg-opacity-10 p-3 rounded-circle me-3">
                            <i class="fas fa-place-of-worship text-success fa-2x"></i>
                        </div>
                        <div>
                            <p class="text-muted mb-0 small text-uppercase fw-bold">Total Mushalla</p>
                            <h3 class="mb-0 fw-bold text-dark">{{ $totalMushalla ?? 0 }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- PERBAIKAN 1: Menambahkan kartu "Belum Update" dan menyesuaikan grid -->
        <div class="row mb-4">
            <!-- Total Majelis -->
            <div class="col-12 col-md-3 mb-4">
                <div class="card card-gradient card-gradient-primary">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h5 class="card-title">Total Majelis</h5>
                                <h2 class="card-value">
                                    {{ is_countable($sktpiagammts) ? count($sktpiagammts) : $sktpiagammts }}
                                </h2>
                            </div>
                            <!-- Icon Background -->
                            <div class="card-icon-bg">
                                <i class="fas fa-mosque"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Majelis Aktif -->
            <div class="col-12 col-md-3 mb-4">
                <div class="card card-gradient card-gradient-success">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h5 class="card-title">Majelis Aktif</h5>
                                <h2 class="card-value">{{ $totalAktif }}</h2>
                            </div>
                            <div class="card-icon-bg">
                                <i class="fas fa-check-circle"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Belum Update -->
            <div class="col-12 col-md-3 mb-4">
                <div class="card card-gradient card-gradient-warning">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h5 class="card-title">Belum Update</h5>
                                <h2 class="card-value">{{ $totalBelumUpdate ?? 0 }}</h2>
                            </div>
                            <div class="card-icon-bg">
                                <i class="fas fa-exclamation-circle"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Majelis Non-Aktif -->
            <div class="col-12 col-md-3 mb-4">
                <div class="card card-gradient card-gradient-danger">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h5 class="card-title">Majelis Non-Aktif</h5>
                                <h2 class="card-value">{{ $totalNonaktif }}</h2>
                            </div>
                            <div class="card-icon-bg">
                                <i class="fas fa-times-circle"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <ul class="nav nav-tabs nav-fill mb-4" id="dashboardTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active fw-bold" id="mt-tab" data-bs-toggle="tab"
                    data-bs-target="#mt-content" type="button" role="tab" aria-controls="mt-content"
                    aria-selected="true">
                    <i class="fas fa-users me-2"></i><span>Statistik Majelis Taklim</span>
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link fw-bold" id="rumah-ibadah-tab" data-bs-toggle="tab"
                    data-bs-target="#rumah-ibadah-content" type="button" role="tab"
                    aria-controls="rumah-ibadah-content" aria-selected="false">
                    <i class="fas fa-mosque me-2"></i><span>Statistik Rumah Ibadah</span>
                </button>
            </li>
        </ul>

        <div class="tab-content" id="dashboardTabsContent">
            <!-- Tab Content: Majelis Taklim -->
            <div class="tab-pane fade show active" id="mt-content" role="tabpanel" aria-labelledby="mt-tab">
                <div class="row mb-4">
                    <!-- Pie Chart untuk persentase aktif per kecamatan -->
                    <div class="col-md-4 mb-3">
                        <div class="card card-modern h-100">
                            <div class="card-header">
                                <h5 class="section-title-modern">Majelis Ta'lim Aktif</h5>
                            </div>
                            <div class="card-body">
                                <div class="chart-container" style="position: relative; height:250px; width:100%;">
                                    <canvas id="aktifPieChart"></canvas>
                                </div>
                                <div class="text-center mt-3">
                                    <button id="downloadAktifPieChart"
                                        class="btn btn-outline-success btn-sm rounded-pill px-3">
                                        <i class="fas fa-download me-1"></i> Download
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4 mb-3">
                        <div class="card card-modern h-100">
                            <div class="card-header">
                                <h5 class="section-title-modern">Belum Update</h5>
                            </div>
                            <div class="card-body">
                                <div class="chart-container" style="position: relative; height:250px; width:100%;">
                                    <canvas id="BelumUpdatePieChart"></canvas>
                                </div>
                                <div class="text-center mt-3">
                                    <button id="downloadBelumUpdatePieChart"
                                        class="btn btn-outline-warning btn-sm rounded-pill px-3">
                                        <i class="fas fa-download me-1"></i> Download
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Pie Chart untuk persentase non-aktif per kecamatan -->
                    <div class="col-md-4 mb-3">
                        <div class="card card-modern h-100">
                            <div class="card-header">
                                <h5 class="section-title-modern">Majelis Non-Aktif</h5>
                            </div>
                            <div class="card-body">
                                <div class="chart-container" style="position: relative; height:250px; width:100%;">
                                    <canvas id="nonaktifPieChart"></canvas>
                                </div>
                                <div class="text-center mt-3">
                                    <button id="downloadNonaktifPieChart"
                                        class="btn btn-outline-danger btn-sm rounded-pill px-3">
                                        <i class="fas fa-download me-1"></i> Download
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mb-4">
                    <!-- Bar Chart per kecamatan -->
                    <div class="col-md-12 mb-3">
                        <div class="card card-modern">
                            <div class="card-header">
                                <h5 class="section-title-modern">Rekapitulasi MT Per Kecamatan</h5>
                            </div>
                            <div class="card-body">
                                <div class="chart-container" style="position: relative; height:700px; width:100%;">
                                    <canvas id="kecamatanChart"></canvas>
                                </div>
                                <div class="text-center mt-3">
                                    <button id="downloadChart"
                                        class="btn btn-outline-primary btn-sm rounded-pill px-3">
                                        <i class="fas fa-download me-1"></i> Download Bar Chart
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Detail Data Table (Moved here) -->
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <div class="card card-modern">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h4 class="section-title-modern">Detail Data</h4>
                                <div class="d-flex">
                                    <button id="downloadMtTable" class="btn btn-light btn-sm rounded-circle shadow-sm"
                                        title="Download Data">
                                        <i class="fas fa-download text-muted"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="card-body px-0 pb-0">
                                <div class="table-responsive p-3">
                                    <table id="tableMajelisTaklim" class="table table-modern text-center">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Kecamatan</th>
                                                <th>Total</th>
                                                <th>Aktif</th>
                                                <th>Belum Update</th>
                                                <th>Non-Aktif</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($kecamatanData as $index => $data)
                                                <tr>
                                                    <td>{{ $index + 1 }}</td>
                                                    <td class="text-start fw-bold text-dark">
                                                        {{ ucwords(is_array($data) ? $data['nama'] : $data->nama) }}
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-primary rounded-pill px-3">
                                                            {{ is_array($data) ? $data['total'] : $data->total }}
                                                        </span>
                                                    </td>
                                                    <td class="text-success fw-bold">
                                                        {{ is_array($data) ? $data['aktif'] : $data->aktif }}</td>
                                                    <td class="text-warning fw-bold">
                                                        {{ is_array($data) ? $data['belum_update'] ?? 0 : $data->belum_update ?? 0 }}
                                                    </td>
                                                    <td class="text-danger fw-bold">
                                                        {{ is_array($data) ? $data['nonaktif'] : $data->nonaktif }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tab Content: Rumah Ibadah -->
            <div class="tab-pane fade" id="rumah-ibadah-content" role="tabpanel" aria-labelledby="rumah-ibadah-tab">
                <div class="row mb-4">
                    <!-- Chart Tipologi Masjid -->
                    <div class="col-md-6 mb-3">
                        <div class="card card-modern h-100">
                            <div class="card-header">
                                <h5 class="section-title-modern">Tipologi Masjid</h5>
                            </div>
                            <div class="card-body">
                                <div class="chart-container" style="position: relative; height:300px; width:100%;">
                                    <canvas id="masjidTipologiChart"></canvas>
                                </div>
                                <div class="text-center mt-3">
                                    <button id="downloadMasjidTipologi"
                                        class="btn btn-outline-primary btn-sm rounded-pill px-3">
                                        <i class="fas fa-download me-1"></i> Download
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Chart Tipologi Mushalla -->
                    <div class="col-md-6 mb-3">
                        <div class="card card-modern h-100">
                            <div class="card-header">
                                <h5 class="section-title-modern">Tipologi Mushalla</h5>
                            </div>
                            <div class="card-body">
                                <div class="chart-container" style="position: relative; height:300px; width:100%;">
                                    <canvas id="mushallaTipologiChart"></canvas>
                                </div>
                                <div class="text-center mt-3">
                                    <button id="downloadMushallaTipologi"
                                        class="btn btn-outline-success btn-sm rounded-pill px-3">
                                        <i class="fas fa-download me-1"></i> Download
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mb-4">
                    <!-- Bar Chart Rumah Ibadah per Kecamatan -->
                    <div class="col-md-12 mb-3">
                        <div class="card card-modern">
                            <div class="card-header">
                                <h5 class="section-title-modern">Sebaran Rumah Ibadah Per Kecamatan</h5>
                            </div>
                            <div class="card-body">
                                <div class="chart-container" style="position: relative; height:700px; width:100%;">
                                    <canvas id="rumahIbadahKecamatanChart"></canvas>
                                </div>
                                <div class="text-center mt-3">
                                    <button id="downloadRumahIbadahChart"
                                        class="btn btn-outline-info btn-sm rounded-pill px-3">
                                        <i class="fas fa-download me-1"></i> Download Chart
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Data Masjid Table -->
                <div class="row mb-4">
                    <div class="col-md-12 mb-3">
                        <div class="card card-modern">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h5 class="section-title-modern">Data Masjid Per Kecamatan</h5>
                                <div class="d-flex">
                                    <button id="downloadMasjidTable"
                                        class="btn btn-light btn-sm rounded-circle shadow-sm" title="Download Excel">
                                        <i class="fas fa-download text-muted"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="card-body px-0 pb-0">
                                <div class="table-responsive p-3">
                                    <table id="tableMasjid" class="table table-modern text-center">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Kecamatan</th>
                                                <th>Total Masjid</th>
                                                @foreach ($listTipologiMasjid ?? [] as $tm)
                                                    <th>{{ $tm->nama_tipologi }}</th>
                                                @endforeach
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($rumahIbadahPerKecamatan ?? [] as $idx => $row)
                                                <tr>
                                                    <td>{{ $idx + 1 }}</td>
                                                    <td class="text-start fw-bold text-dark">{{ ucwords($row->nama) }}
                                                    </td>
                                                    <td><span
                                                            class="badge bg-primary rounded-pill px-3">{{ $row->total_masjid }}</span>
                                                    </td>
                                                    @foreach ($listTipologiMasjid ?? [] as $tm)
                                                        <td>{{ $row->{'count_masjid_' . $tm->id} ?? 0 }}</td>
                                                    @endforeach
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Data Mushalla Table -->
                <div class="row mb-4">
                    <div class="col-md-12 mb-3">
                        <div class="card card-modern">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h5 class="section-title-modern">Data Mushalla Per Kecamatan</h5>
                                <div class="d-flex">
                                    <button id="downloadMushallaTable"
                                        class="btn btn-light btn-sm rounded-circle shadow-sm" title="Download Excel">
                                        <i class="fas fa-download text-muted"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="card-body px-0 pb-0">
                                <div class="table-responsive p-3">
                                    <table id="tableMushalla" class="table table-modern text-center">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Kecamatan</th>
                                                <th>Total Mushalla</th>
                                                @foreach ($listTipologiMushalla ?? [] as $tm)
                                                    <th>{{ $tm->nama_tipologi }}</th>
                                                @endforeach
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($rumahIbadahPerKecamatan ?? [] as $idx => $row)
                                                <tr>
                                                    <td>{{ $idx + 1 }}</td>
                                                    <td class="text-start fw-bold text-dark">{{ ucwords($row->nama) }}
                                                    </td>
                                                    <td><span
                                                            class="badge bg-success rounded-pill px-3">{{ $row->total_mushalla }}</span>
                                                    </td>
                                                    @foreach ($listTipologiMushalla ?? [] as $tm)
                                                        <td>{{ $row->{'count_mushalla_' . $tm->id} ?? 0 }}</td>
                                                    @endforeach
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</div>

</section>
</div>

@push('scripts')
    {{-- Pastikan Chart.js sudah dimuat di layout utama atau muat di sini --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Store all chart instances
            const charts = [];

            // Function to apply theme to all charts
            function updateChartTheme() {
                const isDark = document.documentElement.getAttribute('data-bs-theme') === 'dark';
                const color = isDark ? '#ffffff' : '#495057'; // Dark gray for light mode
                const borderColor = isDark ? 'rgba(255, 255, 255, 0.1)' : 'rgba(0, 0, 0, 0.1)';
                const gridColor = isDark ? 'rgba(255, 255, 255, 0.05)' : 'rgba(0, 0, 0, 0.05)';

                // Update Defaults for future charts
                Chart.defaults.color = color;
                Chart.defaults.borderColor = borderColor;
                Chart.defaults.scale.grid.color = gridColor;

                // Update Active Charts
                charts.forEach(chart => {
                    if (!chart) return;

                    // 1. Update Global Options
                    chart.options.color = color;
                    chart.options.borderColor = borderColor;

                    // 2. Update Legend Labels
                    if (chart.options.plugins && chart.options.plugins.legend) {
                        if (!chart.options.plugins.legend.labels) chart.options.plugins.legend.labels = {};
                        chart.options.plugins.legend.labels.color = color;
                    }

                    // 3. Update Title Options
                    if (chart.options.plugins && chart.options.plugins.title) {
                        chart.options.plugins.title.color = color;
                    }

                    // 4. Update Scales (Axes) if they exist
                    if (chart.scales) {
                        Object.keys(chart.scales).forEach(key => {
                            const scale = chart.scales[key];
                            // Update options directly on the scale instance
                            if (scale.options.ticks) scale.options.ticks.color = color;
                            if (scale.options.grid) scale.options.grid.color = gridColor;
                            if (scale.options.title) scale.options.title.color = color;
                        });
                    }

                    // 5. Explicitly update scales config in options (needed for some charts to pick it up on re-render)
                    if (chart.options.scales) {
                        Object.keys(chart.options.scales).forEach(key => {
                            const scaleOpts = chart.options.scales[key];
                            if (scaleOpts.ticks) scaleOpts.ticks.color = color;
                            if (scaleOpts.grid) scaleOpts.grid.color = gridColor;
                            if (scaleOpts.title) scaleOpts.title.color = color;
                        });
                    }

                    chart.update();
                });
            }


            // Watch for theme changes
            const observer = new MutationObserver(function(mutations) {
                mutations.forEach(function(mutation) {
                    if (mutation.type === "attributes" && mutation.attributeName ===
                        "data-bs-theme") {
                        updateChartTheme();
                    }
                });
            });

            observer.observe(document.documentElement, {
                attributes: true
            });

            // Initial Apply
            updateChartTheme();

            // ============================================
            // 1. FUNGSI INISIALISASI CHART MAJELIS TAKLIM
            // ============================================
            let mtChartsInitialized = false;

            function initMTCharts() {
                if (mtChartsInitialized) return;

                const rawData = @json($kecamatanData ?? []);

                // Helper Kapitalisasi
                const capitalize = str => (str || '').toString().split(' ').map(w => w.charAt(0).toUpperCase() + w
                    .slice(1).toLowerCase()).join(' ');

                // Process Data (Ensure name is safe)
                const processedData = rawData.map(item => ({
                    ...item,
                    nama: capitalize(item.nama)
                }));

                // Filter & Sort (Only if total > 0)
                const filteredData = processedData.filter(item => item.total && item.total > 0)
                    .sort((a, b) => b.total - a.total);

                console.log("MT Charts Data:", filteredData);

                // --- Bar Chart Kecamatan ---
                const ctxBar = document.getElementById('kecamatanChart');
                if (ctxBar && filteredData.length > 0) {
                    charts.push(new Chart(ctxBar.getContext('2d'), {
                        type: 'bar',
                        data: {
                            labels: filteredData.map(i => i.nama),
                            datasets: [{
                                    label: 'Total',
                                    data: filteredData.map(i => i.total),
                                    backgroundColor: 'rgba(54, 162, 235, 0.7)',
                                    borderColor: 'rgba(54, 162, 235, 1)',
                                    borderWidth: 1
                                },
                                {
                                    label: 'Aktif',
                                    data: filteredData.map(i => i.aktif),
                                    backgroundColor: 'rgba(75, 192, 192, 0.7)',
                                    borderColor: 'rgba(75, 192, 192, 1)',
                                    borderWidth: 1
                                },
                                {
                                    label: 'Belum Update',
                                    data: filteredData.map(i => i.belum_update),
                                    backgroundColor: 'rgba(255, 205, 86, 0.7)',
                                    borderColor: 'rgba(255, 205, 86, 1)',
                                    borderWidth: 1
                                },
                                {
                                    label: 'Non-Aktif',
                                    data: filteredData.map(i => i.nonaktif),
                                    backgroundColor: 'rgba(255, 99, 132, 0.7)',
                                    borderColor: 'rgba(255, 99, 132, 1)',
                                    borderWidth: 1
                                }
                            ]
                        },
                        options: {
                            indexAxis: 'y',
                            responsive: true,
                            maintainAspectRatio: false,
                            scales: {
                                x: {
                                    beginAtZero: true,
                                    title: {
                                        display: true,
                                        text: 'Jumlah MT'
                                    }
                                },
                                y: {
                                    stacked: false
                                }
                            },
                            plugins: {
                                title: {
                                    display: true,
                                    text: 'Jumlah Majelis Taklim per Kecamatan',
                                    font: {
                                        size: 16
                                    }
                                },
                                legend: {
                                    position: 'top'
                                }
                            }
                        }
                    }));
                    // Setup Download
                    setupDownload('downloadChart', ctxBar, 'Rekap_MT_Kecamatan.png');
                }

                // --- Pie Chart Aktif ---
                renderPieChart('aktifPieChart', filteredData, 'aktif', 'Aktif', 'downloadAktifPieChart');

                // --- Pie Chart Belum Update ---
                renderPieChart('BelumUpdatePieChart', filteredData, 'belum_update', 'Belum Update',
                    'downloadBelumUpdatePieChart');

                // --- Pie Chart Non-Aktif ---
                renderPieChart('nonaktifPieChart', filteredData, 'nonaktif', 'Non-Aktif',
                    'downloadNonaktifPieChart');

                mtChartsInitialized = true;
            }

            // Helper render Pie Chart
            function renderPieChart(canvasId, data, key, label, btnId) {
                const canvas = document.getElementById(canvasId);
                if (!canvas) return;

                const chartData = data.filter(i => i[key] > 0);
                if (chartData.length === 0) {
                    canvas.style.display = 'none';
                    return;
                }

                const total = chartData.reduce((acc, curr) => acc + curr[key], 0);
                charts.push(new Chart(canvas.getContext('2d'), {
                    type: 'pie',
                    data: {
                        labels: chartData.map(i => i.nama),
                        datasets: [{
                            data: chartData.map(i => i[key]),
                            backgroundColor: generateColors(chartData.length),
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            tooltip: {
                                callbacks: {
                                    label: function(ctx) {
                                        const val = ctx.raw || 0;
                                        const pct = total ? Math.round((val / total) * 100) : 0;
                                        return `${ctx.label}: ${val} (${pct}%)`;
                                    }
                                }
                            },
                            legend: {
                                position: 'bottom',
                                labels: {
                                    boxWidth: 12,
                                    font: {
                                        size: 10
                                    }
                                }
                            }
                        }
                    }
                }));
                setupDownload(btnId, canvas, `MT_${label}.png`);
            }

            // Helper Random Consistent Colors
            function generateColors(count) {
                const baseColors = [
                    'rgba(54, 162, 235, 0.7)', 'rgba(75, 192, 192, 0.7)', 'rgba(255, 205, 86, 0.7)',
                    'rgba(255, 99, 132, 0.7)', 'rgba(153, 102, 255, 0.7)', 'rgba(255, 159, 64, 0.7)',
                    'rgba(201, 203, 207, 0.7)'
                ];
                let colors = [];
                for (let i = 0; i < count; i++) colors.push(baseColors[i % baseColors.length]);
                return colors;
            }

            // Helper Download
            function setupDownload(btnId, canvas, filename) {
                const btn = document.getElementById(btnId);
                if (btn && canvas) {
                    btn.addEventListener('click', () => {
                        const link = document.createElement('a');
                        link.download = filename;
                        link.href = canvas.toDataURL('image/png');
                        link.click();
                    });
                }
            }

            // Helper Download (Table Excel)
            const downloadTable = (btnId, tableId, filename) => {
                const btn = document.getElementById(btnId);
                const table = document.getElementById(tableId);
                if (btn && table) {
                    btn.addEventListener('click', () => {
                        const uri = 'data:application/vnd.ms-excel;base64,';
                        const template =
                            '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40"><head><!--[if gte mso 9]><xml><x' +
                            ':ExcelWorkbook><x' + ':ExcelWorksheets><x' + ':ExcelWorksheet><x' +
                            ':Name>{worksheet}</x' + ':Name><x' + ':WorksheetOptions><x' +
                            ':DisplayGridlines/></x' + ':WorksheetOptions></x' + ':ExcelWorksheet></x' +
                            ':ExcelWorksheets></x' +
                            ':ExcelWorkbook></xml><![endif]--></head><body><table>{table}</table></body></html>';
                        const base64 = (s) => window.btoa(unescape(encodeURIComponent(s)));
                        const format = (s, c) => s.replace(/{(\w+)}/g, (m, p) => c[p]);

                        const ctx = {
                            worksheet: 'Data',
                            table: table.innerHTML
                        };
                        const link = document.createElement('a');
                        link.download = filename + '.xls';
                        link.href = uri + base64(format(template, ctx));
                        link.click();
                    });
                }
            };

            // ============================================
            // 2. FUNGSI INISIALISASI CHART RUMAH IBADAH
            // ============================================
            let riChartsInitialized = false;

            function initRICharts() {
                if (riChartsInitialized) return;

                // Tipologi Masjid
                const masjidData = @json($masjidByTipologi ?? []);
                if (masjidData.length) {
                    charts.push(new Chart(document.getElementById('masjidTipologiChart').getContext('2d'), {
                        type: 'doughnut',
                        data: {
                            labels: masjidData.map(i => i.nama),
                            datasets: [{
                                data: masjidData.map(i => i.total),
                                backgroundColor: generateColors(masjidData.length),
                                borderWidth: 1
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    position: 'right'
                                }
                            }
                        }
                    }));
                } else {
                    document.getElementById('masjidTipologiChart').parentNode.innerHTML =
                        '<div class="d-flex justify-content-center align-items-center h-100 text-muted">Belum ada data</div>';
                }

                // Tipologi Mushalla
                const mushallaData = @json($mushallaByTipologi ?? []);
                if (mushallaData.length) {
                    charts.push(new Chart(document.getElementById('mushallaTipologiChart').getContext('2d'), {
                        type: 'doughnut',
                        data: {
                            labels: mushallaData.map(i => i.nama),
                            datasets: [{
                                data: mushallaData.map(i => i.total),
                                backgroundColor: generateColors(mushallaData.length),
                                borderWidth: 1
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    position: 'right'
                                }
                            }
                        }
                    }));
                } else {
                    document.getElementById('mushallaTipologiChart').parentNode.innerHTML =
                        '<div class="d-flex justify-content-center align-items-center h-100 text-muted">Belum ada data</div>';
                }

                // Bar Chart Rumah Ibadah
                const riData = @json($rumahIbadahPerKecamatan ?? []);

                // Helper Kapitalisasi
                const capitalize = str => (str || '').toString().split(' ').map(w => w.charAt(0).toUpperCase() + w
                    .slice(1).toLowerCase()).join(' ');

                const filteredRI = riData.filter(i => (i.total_masjid + i.total_mushalla) > 0)
                    .sort((a, b) => (b.total_masjid + b.total_mushalla) - (a.total_masjid + a.total_mushalla));

                if (filteredRI.length) {
                    charts.push(new Chart(document.getElementById('rumahIbadahKecamatanChart').getContext('2d'), {
                        type: 'bar',
                        data: {
                            labels: filteredRI.map(i => capitalize(i.nama)),
                            datasets: [{

                                label: 'Masjid',
                                data: filteredRI.map(i => i.total_masjid),
                                backgroundColor: 'rgba(54, 162, 235, 0.7)',
                                borderColor: 'rgba(54, 162, 235, 1)',
                                borderWidth: 1
                            }, {
                                label: 'Mushalla',
                                data: filteredRI.map(i => i.total_mushalla),
                                backgroundColor: 'rgba(75, 192, 192, 0.7)',
                                borderColor: 'rgba(75, 192, 192, 1)',
                                borderWidth: 1
                            }]
                        },
                        options: {
                            indexAxis: 'y',
                            responsive: true,
                            maintainAspectRatio: false,
                            scales: {
                                x: {
                                    stacked: false
                                },
                                y: {
                                    stacked: false
                                }
                            },
                            plugins: {
                                legend: {
                                    position: 'top'
                                }
                            }
                        }
                    }));
                } else {
                    document.getElementById('rumahIbadahKecamatanChart').parentNode.innerHTML =
                        '<div class="d-flex justify-content-center align-items-center h-100 text-muted">Belum ada data</div>';
                }

                // Setup Downloads
                setupDownload('downloadMasjidTipologi', document.getElementById('masjidTipologiChart'),
                    'Tipologi_Masjid.png');
                setupDownload('downloadMushallaTipologi', document.getElementById('mushallaTipologiChart'),
                    'Tipologi_Mushalla.png');
                setupDownload('downloadRumahIbadahChart', document.getElementById('rumahIbadahKecamatanChart'),
                    'Sebaran_Rumah_Ibadah.png');

                // Calls to global downloadTable logic

                downloadTable('downloadMasjidTable', 'tableMasjid', 'Data_Masjid_Per_Kecamatan');
                downloadTable('downloadMushallaTable', 'tableMushalla', 'Data_Mushalla_Per_Kecamatan');

                riChartsInitialized = true;
            }

            // ============================================
            // 3. EXECUTION
            // ============================================

            // Tab Listener
            const triggerTabList = [].slice.call(document.querySelectorAll(
                '#dashboardTabs button[data-bs-toggle="tab"]'));
            triggerTabList.forEach(function(triggerEl) {
                triggerEl.addEventListener('shown.bs.tab', function(event) {
                    if (event.target.id === 'mt-tab') initMTCharts();
                    if (event.target.id === 'rumah-ibadah-tab') initRICharts();
                });
            });

            // Init Default Tab (MT)
            initMTCharts();

            // Init Downloads (MT)
            downloadTable('downloadMtTable', 'tableMajelisTaklim', 'Detail_Data_MT_Per_Kecamatan');
        });
    </script>
@endpush
