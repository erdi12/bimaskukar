@extends('layout.appv2')
@section('title', 'Rekapan Data - Majelis Ta\'lim')
@section('sub-layanan', 'active')
@section('layanan', 'active')
@section('skt-mt', 'active')

@section('content')
    <style>
        .card-gradient {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card-gradient:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15) !important;
            z-index: 5;
        }
    </style>

    <div class="content">
        <div class="page-title mb-4">
            <h3>Rekapan Data Majelis Ta'lim</h3>
            <p class="text-muted">Statistik dan visualisasi data majelis taklim se-Kabupaten</p>
        </div>

        <!-- Filter Data -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card card-modern">
                    <div class="card-body">
                        <form action="{{ route('skt_piagam_mt_v2.rekap') }}" method="GET" class="row g-3 align-items-end">
                            <div class="col-12 col-md-4">
                                <label for="start_date" class="form-label fw-bold">Tanggal Mulai</label>
                                <input type="date" class="form-control" id="start_date" name="start_date"
                                    value="{{ request('start_date') }}">
                            </div>
                            <div class="col-12 col-md-4">
                                <label for="end_date" class="form-label fw-bold">Tanggal Akhir</label>
                                <input type="date" class="form-control" id="end_date" name="end_date"
                                    value="{{ request('end_date') }}">
                            </div>
                            <div class="col-12 col-md-4">
                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary flex-grow-1">
                                        <i class="fas fa-filter me-1"></i> Filter
                                    </button>
                                    <a href="{{ route('skt_piagam_mt_v2.rekap') }}" class="btn btn-secondary flex-grow-1">
                                        <i class="fas fa-undo me-1"></i> Reset
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistik Cards -->
        <div class="row mb-4">
            <div class="col-12 col-md-3 mb-4">
                <div class="card card-gradient card-gradient-primary h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h5 class="card-title text-white-50">Total Majelis</h5>
                                <h2 class="card-value text-white">{{ count($sktpiagammts) }}</h2>
                            </div>
                            <div class="card-icon-bg">
                                <i class="fas fa-mosque"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-3 mb-4">
                <div class="card card-gradient card-gradient-success h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h5 class="card-title text-white-50">Majelis Aktif</h5>
                                <h2 class="card-value text-white">{{ $totalAktif }}</h2>
                            </div>
                            <div class="card-icon-bg">
                                <i class="fas fa-check-circle"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-3 mb-4">
                <div class="card card-gradient card-gradient-warning h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h5 class="card-title text-white-50">Belum Update</h5>
                                <h2 class="card-value text-white">{{ $totalBelumUpdate }}</h2>
                            </div>
                            <div class="card-icon-bg">
                                <i class="fas fa-exclamation-circle"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-3 mb-4">
                <div class="card card-gradient card-gradient-danger h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h5 class="card-title text-white-50">Non-Aktif</h5>
                                <h2 class="card-value text-white">{{ $totalNonaktif }}</h2>
                            </div>
                            <div class="card-icon-bg">
                                <i class="fas fa-times-circle"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Chart -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card card-modern">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="section-title-modern mb-0">Statistik Per Kecamatan</h5>
                        <button id="downloadChart" class="btn btn-sm btn-outline-primary rounded-pill">
                            <i class="fas fa-download me-1"></i> Download Chart
                        </button>
                    </div>
                    <div class="card-body">
                        <div style="position: relative; height:400px; width:100%;">
                            <canvas id="kecamatanChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pie Charts -->
        <div class="row mb-4">
            <div class="col-md-4 mb-4">
                <div class="card card-modern h-100">
                    <div class="card-header text-center">
                        <h6 class="fw-bold mb-0">Persentase Aktif</h6>
                    </div>
                    <div class="card-body">
                        <div style="height: 250px;">
                            <canvas id="aktifPieChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card card-modern h-100">
                    <div class="card-header text-center">
                        <h6 class="fw-bold mb-0">Persentase Belum Update</h6>
                    </div>
                    <div class="card-body">
                        <div style="height: 250px;">
                            <canvas id="BelumUpdatePieChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card card-modern h-100">
                    <div class="card-header text-center">
                        <h6 class="fw-bold mb-0">Persentase Non-Aktif</h6>
                    </div>
                    <div class="card-body">
                        <div style="height: 250px;">
                            <canvas id="nonaktifPieChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Data Table -->
        <div class="row">
            <div class="col-12">
                <div class="card card-modern">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="section-title-modern mb-0">Detail Data Per Kecamatan</h5>
                        <a href="{{ route('skt_piagam_mt_v2.index') }}"
                            class="btn btn-outline-secondary rounded-pill btn-sm">
                            <i class="fas fa-arrow-left me-1"></i> Kembali ke Data
                        </a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-modern table-bordered table-hover">
                                <thead>
                                    <tr class="text-center align-middle bg-light">
                                        <th>No</th>
                                        <th>Kecamatan</th>
                                        <th>Total Majelis</th>
                                        <th>Aktif</th>
                                        <th>Belum Update</th>
                                        <th>Non-Aktif</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($kecamatanData as $data)
                                        <tr>
                                            <td class="text-center">{{ $loop->iteration }}</td>
                                            <td>{{ ucfirst($data['nama']) }}</td>
                                            <td class="text-center fw-bold">{{ $data['total'] }}</td>
                                            <td class="text-center text-success">{{ $data['aktif'] }}</td>
                                            <td class="text-center text-warning">{{ $data['belum_update'] }}</td>
                                            <td class="text-center text-danger">{{ $data['nonaktif'] }}</td>
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
@endsection

@push('scripts')
    {{-- Load Chart.js with Script fallback if needed --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // --- 1. THEME HANDLING SETUP ---
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

                // Robust default scale update for v3/v4
                if (Chart.defaults.scale) Chart.defaults.scale.grid.color = gridColor;
                if (Chart.defaults.scales) {
                    ['x', 'y', 'r'].forEach(axis => {
                        if (Chart.defaults.scales[axis]) {
                            Chart.defaults.scales[axis].grid = Chart.defaults.scales[axis].grid || {};
                            Chart.defaults.scales[axis].grid.color = gridColor;
                        }
                    });
                }

                // Update Active Charts
                charts.forEach(chart => {
                    if (!chart) return;

                    // 1. Update Global Options
                    chart.options.color = color;
                    chart.options.borderColor = borderColor;

                    // 2. Update Legend Labels
                    if (chart.options.plugins && chart.options.plugins.legend) {
                        chart.options.plugins.legend.labels = chart.options.plugins.legend.labels || {};
                        chart.options.plugins.legend.labels.color = color;
                    }

                    // 3. Update Title Options
                    if (chart.options.plugins && chart.options.plugins.title) {
                        chart.options.plugins.title.color = color;
                    }

                    // 4. Update Scales (Axes) if they exist (Runtime)
                    if (chart.scales) {
                        Object.keys(chart.scales).forEach(key => {
                            const scale = chart.scales[key];
                            if (scale.options.ticks) scale.options.ticks.color = color;
                            if (scale.options.grid) scale.options.grid.color = gridColor;
                            if (scale.options.title) scale.options.title.color = color;
                        });
                    }

                    // 5. Explicitly update scales config in options (Config)
                    if (chart.options.scales) {
                        Object.keys(chart.options.scales).forEach(key => {
                            const scaleOpts = chart.options.scales[key];

                            // Initialize objects if they don't exist (Critical fix)
                            scaleOpts.ticks = scaleOpts.ticks || {};
                            scaleOpts.grid = scaleOpts.grid || {};

                            scaleOpts.ticks.color = color;
                            scaleOpts.grid.color = gridColor;

                            if (scaleOpts.title) scaleOpts.title.color = color;
                        });
                    }

                    chart.update();
                });
            }

            // Watch for            // Optimized Observer
            const observer = new MutationObserver(() => {
                updateChartTheme();
            });

            observer.observe(document.documentElement, {
                attributes: true,
                attributeFilter: ['data-bs-theme']
            });

            // Backup Trigger: Click Listener on Theme Toggle
            const themeToggleBtn = document.getElementById('themeToggle');
            if (themeToggleBtn) {
                themeToggleBtn.addEventListener('click', () => {
                    setTimeout(updateChartTheme, 50);
                    setTimeout(updateChartTheme, 300); // Double check
                });
            }


            // --- 2. DATA PREPARATION ---
            const kecamatanData = @json($kecamatanData);

            const labels = [];
            const totalData = [];
            const aktifData = [];
            const belumUpdateData = [];
            const nonAktifData = [];

            Object.values(kecamatanData).forEach(item => {
                labels.push(item.nama.toLowerCase().split(' ').map(w => w.charAt(0).toUpperCase() + w.slice(
                    1)).join(' '));
                totalData.push(item.total);
                aktifData.push(item.aktif);
                belumUpdateData.push(item.belum_update);
                nonAktifData.push(item.nonaktif);
            });

            // --- 3. CHART INITIALIZATION ---

            // Bar Chart (Main)
            const ctxBar = document.getElementById('kecamatanChart').getContext('2d');
            const mainChart = new Chart(ctxBar, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                            label: 'Aktif',
                            data: aktifData,
                            backgroundColor: '#4caf50', // Simtak Hijau Muda
                            borderRadius: 4
                        },
                        {
                            label: 'Belum Update',
                            data: belumUpdateData,
                            backgroundColor: '#ffc107', // warning
                            borderRadius: 4
                        },
                        {
                            label: 'Non-Aktif',
                            data: nonAktifData,
                            backgroundColor: '#fc544b', // danger
                            borderRadius: 4
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        x: {
                            stacked: true
                        },
                        y: {
                            stacked: true,
                            beginAtZero: true
                        }
                    },
                    plugins: {
                        legend: {
                            position: 'top'
                        },
                        tooltip: {
                            mode: 'index',
                            intersect: false
                        }
                    }
                }
            });
            charts.push(mainChart);

            // Helper func for Pie Charts
            function createPieChart(elementId, dataValues, labelName, colorTheme) {
                const ctx = document.getElementById(elementId).getContext('2d');

                // Filter non-zero values for cleaner charts
                const validLabels = [];
                const validData = [];

                labels.forEach((label, index) => {
                    if (dataValues[index] > 0) {
                        validLabels.push(label);
                        validData.push(dataValues[index]);
                    }
                });

                // Auto-generate colors based on theme
                const bgColors = validData.map((_, i) => {
                    // Generate varied hsl colors
                    const hue = (i * 137.508) % 360;
                    return `hsla(${hue}, 70%, 60%, 0.8)`;
                });

                const chart = new Chart(ctx, {
                    type: 'doughnut',
                    data: {
                        labels: validLabels,
                        datasets: [{
                            data: validData,
                            backgroundColor: bgColors,
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {
                                    boxWidth: 10,
                                    font: {
                                        size: 10
                                    }
                                }
                            }
                        }
                    }
                });
                charts.push(chart);
                return chart;
            }

            // Create Pie Charts
            // Ensure elements exist before creating charts to avoid errors
            if (document.getElementById('aktifPieChart')) createPieChart('aktifPieChart', aktifData, 'Aktif',
                'green');
            if (document.getElementById('BelumUpdatePieChart')) createPieChart('BelumUpdatePieChart',
                belumUpdateData, 'Belum Update', 'yellow');
            if (document.getElementById('nonaktifPieChart')) createPieChart('nonaktifPieChart', nonAktifData,
                'Non-Aktif', 'red');

            // Initial Theme Apply
            updateChartTheme();

            // Download Listener
            const btnDownload = document.getElementById('downloadChart');
            if (btnDownload) {
                btnDownload.addEventListener('click', function() {
                    const link = document.createElement('a');
                    link.download = 'Statistik-Majelis-Taklim.png';
                    link.href = mainChart.toBase64Image();
                    link.click();
                });
            }
        });
    </script>
@endpush
