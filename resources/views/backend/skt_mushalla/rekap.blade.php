@extends('layout.appv2')
@section('title', 'Rekap Data Mushalla')
@section('sub-layanan', 'active open')
@section('skt-mt', 'show')
@section('mushalla', 'active')

@section('content')
    <div class="content">
        <div class="page-title mb-4 d-flex justify-content-between align-items-center">
            <div>
                <h3 class="fw-bold">Dashboard Rekapitulasi Mushalla</h3>
                <p class="text-muted mb-0">Statistik sebaran dan komposisi Mushalla di Kutai Kartanegara</p>
            </div>
            <a href="{{ route('skt_mushalla.index') }}" class="btn btn-outline-secondary rounded-pill">
                <i class="fas fa-arrow-left me-2"></i> Kembali ke Data
            </a>
        </div>

        <!-- Summary Section -->
        <div class="row g-3 mb-4 fade-in-up">
            <!-- Main Total Card -->
            <div class="col-12 col-md-4 col-xl-3">
                <div class="card border-0 shadow-sm h-100 hover-card"
                    style="background: linear-gradient(135deg, #059669 0%, #047857 100%); color: white; border-radius: 1rem;">
                    <div
                        class="card-body p-4 d-flex flex-column justify-content-center align-items-center text-center position-relative overflow-hidden">
                        <i class="fas fa-place-of-worship position-absolute"
                            style="opacity: 0.1; font-size: 8rem; right: -20px; bottom: -20px;"></i>
                        <div class="mb-2 icon-box"
                            style="background: rgba(255,255,255,0.2); padding: 12px; border-radius: 50%;">
                            <i class="fas fa-moon fa-2x"></i>
                        </div>
                        <h6 class="text-uppercase fw-bold letter-spacing-1 mb-1"
                            style="font-size: 0.8rem; letter-spacing: 1px; opacity: 0.9;">Total Mushalla</h6>
                        <h1 class="display-4 fw-bold mb-0">{{ number_format($rekap->sum('total'), 0, ',', '.') }}</h1>
                    </div>
                </div>
            </div>

            <!-- Grid for Tipologi -->
            <div class="col-12 col-md-8 col-xl-9">
                <div class="row g-3">
                    @php
                        // Soft pastel colors for icons - Greenish Theme
                        $styles = [
                            ['bg' => '#d1fae5', 'text' => '#065f46', 'icon' => 'fas fa-star-and-crescent'], // Emerald
                            ['bg' => '#e0f2fe', 'text' => '#0369a1', 'icon' => 'fas fa-mosque'], // Sky
                            ['bg' => '#fef3c7', 'text' => '#92400e', 'icon' => 'fas fa-pray'], // Amber
                            ['bg' => '#fee2e2', 'text' => '#991b1b', 'icon' => 'fas fa-heart'], // Red
                            ['bg' => '#f3e8ff', 'text' => '#6b21a8', 'icon' => 'fas fa-layer-group'], // Purple
                            ['bg' => '#ffedd5', 'text' => '#9a3412', 'icon' => 'fas fa-home'], // Orange
                        ];
                    @endphp

                    @foreach ($tipologis as $index => $t)
                        @php $style = $styles[$index % count($styles)]; @endphp
                        <div class="col-12 col-md-6 col-lg-4">
                            <div class="card border-0 shadow-sm h-100 hover-card" style="border-radius: 1rem;">
                                <div class="card-body p-3 d-flex align-items-center">
                                    <div class="flex-shrink-0 d-flex align-items-center justify-content-center icon-box"
                                        style="width: 50px; height: 50px; border-radius: 12px; background-color: {{ $style['bg'] }}; color: {{ $style['text'] }};">
                                        <i class="{{ $style['icon'] }} fa-lg"></i>
                                    </div>
                                    <div class="ms-3">
                                        <h6 class="text-muted text-uppercase mb-1"
                                            style="font-size: 0.7rem; font-weight: 600;">{{ $t->nama_tipologi }}</h6>
                                        <h4 class="fw-bold mb-0">
                                            {{ number_format($rekap->sum('count_' . $t->id), 0, ',', '.') }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Charts Row -->
        <div class="row mb-4 g-3 fade-in-up" style="animation-delay: 0.1s;">
            <!-- Bar Chart: Kecamatan -->
            <div class="col-12 col-lg-8">
                <div class="card border-0 shadow-sm h-100 hover-card" style="border-radius: 1rem;">
                    <div class="card-header border-bottom-0 py-3 d-flex justify-content-between align-items-center"
                        style="border-radius: 1rem 1rem 0 0;">
                        <h5 class="fw-bold mb-0">Sebaran per Kecamatan</h5>
                        <button class="btn btn-sm btn-light rounded-circle"
                            onclick="downloadChart('kecamatanChart', 'Sebaran_Mushalla.png')" title="Download Grafik">
                            <i class="fas fa-download text-secondary"></i>
                        </button>
                    </div>
                    <div class="card-body pt-0">
                        <div style="position: relative; height: 400px; width: 100%;">
                            <canvas id="kecamatanChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Doughnut Chart: Tipologi -->
            <div class="col-12 col-lg-4">
                <div class="card border-0 shadow-sm h-100 hover-card" style="border-radius: 1rem;">
                    <div class="card-header border-bottom-0 py-3 d-flex justify-content-between align-items-center"
                        style="border-radius: 1rem 1rem 0 0;">
                        <h5 class="fw-bold mb-0">Komposisi Tipologi</h5>
                        <button class="btn btn-sm btn-light rounded-circle"
                            onclick="downloadChart('tipologiChart', 'Tipologi_Mushalla.png')" title="Download Grafik">
                            <i class="fas fa-download text-secondary"></i>
                        </button>
                    </div>
                    <div class="card-body pt-0 d-flex flex-column justify-content-center">
                        <div style="position: relative; height: 300px; width: 100%;">
                            <canvas id="tipologiChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Data Table -->
        <div class="card border-0 shadow-sm fade-in-up" style="border-radius: 1rem; animation-delay: 0.2s;">
            <div class="card-header border-bottom-0 py-3 d-flex justify-content-between align-items-center"
                style="border-radius: 1rem 1rem 0 0;">
                <h5 class="fw-bold mb-0">Tabel Rincian Data</h5>
                <button class="btn btn-sm btn-success rounded-pill px-3"
                    onclick="downloadTable('tableRekap', 'Rekap_Data_Mushalla')" title="Download Excel">
                    <i class="fas fa-file-excel me-2"></i> Download Excel
                </button>
            </div>
            <div class="card-body p-0 table-responsive">
                <table id="tableRekap" class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="py-3 ps-4 text-center" width="50">No</th>
                            <th class="py-3">Kecamatan</th>
                            <th class="py-3 text-center">Total</th>
                            @foreach ($tipologis as $t)
                                <th class="py-3 text-center">{{ $t->nama_tipologi }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($rekap as $index => $row)
                            <tr>
                                <td class="text-center ps-4">{{ $index + 1 }}</td>
                                <td class="fw-bold">{{ ucwords($row->nama) }}</td>
                                <td class="text-center">
                                    <span
                                        class="badge bg-success bg-opacity-10 text-success rounded-pill px-3">{{ $row->total }}</span>
                                </td>
                                @foreach ($tipologis as $t)
                                    <td class="text-center text-muted">{{ $row->{'count_' . $t->id} ?? 0 }}</td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-light fw-bold">
                        <tr>
                            <td colspan="2" class="text-end py-3 pe-4">Total Keseluruhan</td>
                            <td class="text-center text-success py-3">
                                {{ number_format($rekap->sum('total'), 0, ',', '.') }}</td>
                            @foreach ($tipologis as $t)
                                <td class="text-center py-3">
                                    {{ number_format($rekap->sum('count_' . $t->id), 0, ',', '.') }}</td>
                            @endforeach
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // --- 1. THEME HANDLING SETUP ---
            const charts = [];

            // Function to apply theme to all charts
            function updateChartTheme() {
                const isDark = document.documentElement.getAttribute('data-bs-theme') === 'dark';
                const color = isDark ? '#ffffff' : '#495057'; // Consistent dark gray
                const borderColor = isDark ? 'rgba(255, 255, 255, 0.1)' : 'rgba(0, 0, 0, 0.1)';
                const gridColor = isDark ? 'rgba(255, 255, 255, 0.05)' : 'rgba(0, 0, 0, 0.05)';

                // Update Defaults
                Chart.defaults.color = color;
                Chart.defaults.borderColor = borderColor;

                // Robust default scale update
                if (Chart.defaults.scale) Chart.defaults.scale.grid.color = gridColor;
                if (Chart.defaults.scales) {
                    ['x', 'y', 'r'].forEach(axis => {
                        if (Chart.defaults.scales[axis]) {
                            Chart.defaults.scales[axis].grid = Chart.defaults.scales[axis].grid || {};
                            Chart.defaults.scales[axis].grid.color = gridColor;
                        }
                    });
                }

                charts.forEach(chart => {
                    if (!chart) return;

                    // Global
                    chart.options.color = color;
                    chart.options.borderColor = borderColor;

                    // Legend
                    if (chart.options.plugins && chart.options.plugins.legend) {
                        chart.options.plugins.legend.labels = chart.options.plugins.legend.labels || {};
                        chart.options.plugins.legend.labels.color = color;
                    }

                    // Title
                    if (chart.options.plugins && chart.options.plugins.title) {
                        chart.options.plugins.title.color = color;
                    }

                    // Scales (Runtime)
                    if (chart.scales) {
                        Object.keys(chart.scales).forEach(key => {
                            const scale = chart.scales[key];
                            if (scale.options.ticks) scale.options.ticks.color = color;
                            if (scale.options.grid) scale.options.grid.color = gridColor;
                            if (scale.options.title) scale.options.title.color = color;
                        });
                    }

                    // Scales (Config - Critical Fix)
                    if (chart.options.scales) {
                        Object.keys(chart.options.scales).forEach(key => {
                            const scaleOpts = chart.options.scales[key];
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

            // Optimized Observer
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

            // --- Data Setup ---
            const kecamatanLabels = @json($rekap->pluck('nama')).map(n => n.toLowerCase().split(' ').map(w => w
                .charAt(0).toUpperCase() + w.slice(1)).join(' '));
            const totalData = @json($rekap->pluck('total'));

            // Soft Modern Colors - Greenish
            const colors = [
                '#10b981', '#3b82f6', '#f59e0b', '#ef4444', '#8b5cf6', '#6366f1'
            ];

            // --- Bar Chart: Kecamatan ---
            const ctxBar = document.getElementById('kecamatanChart').getContext('2d');
            const barChart = new Chart(ctxBar, {
                type: 'bar',
                data: {
                    labels: kecamatanLabels,
                    datasets: [{
                        label: 'Jumlah Mushalla',
                        data: totalData,
                        backgroundColor: '#4caf50', // Simtak Hijau Muda
                        borderRadius: 6,
                        barPercentage: 0.6
                    }]
                },
                options: {
                    indexAxis: 'y',
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        x: {
                            beginAtZero: true,
                            grid: {
                                display: true,
                                borderDash: [2, 2]
                            }
                        },
                        y: {
                            grid: {
                                display: false
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: '#064e3b',
                            padding: 12,
                            cornerRadius: 8,
                            titleFont: {
                                size: 13
                            },
                            bodyFont: {
                                size: 12
                            }
                        }
                    }
                }
            });
            charts.push(barChart);

            // --- Doughnut Chart: Tipologi ---
            const tipologiValues = [
                @foreach ($tipologis as $t)
                    {{ $rekap->sum('count_' . $t->id) }},
                @endforeach
            ];
            const tipologiNames = @json($tipologis->pluck('nama_tipologi'));

            const ctxDoughnut = document.getElementById('tipologiChart').getContext('2d');
            const doughnutChart = new Chart(ctxDoughnut, {
                type: 'doughnut',
                data: {
                    labels: tipologiNames,
                    datasets: [{
                        data: tipologiValues,
                        backgroundColor: colors,
                        borderWidth: 0,
                        hoverOffset: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '75%',
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                boxWidth: 10,
                                usePointStyle: true,
                                padding: 20,
                                font: {
                                    size: 11
                                }
                            }
                        }
                    }
                }
            });
            charts.push(doughnutChart);

            // Initial Theme Apply
            updateChartTheme();

            // Initialize DataTable with clean styling
            $('#tableRekap').DataTable({
                language: {
                    url: "//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json"
                },
                pageLength: 25,
                dom: '<"d-flex justify-content-end align-items-center p-3"f><"table-responsive"t><"d-flex justify-content-between align-items-center p-3"ip>',
            });
        });

        // Helper Download Chart
        function downloadChart(canvasId, filename) {
            const canvas = document.getElementById(canvasId);
            const link = document.createElement('a');
            link.download = filename;
            link.href = canvas.toDataURL('image/png');
            link.click();
        }

        // Helper Download Table
        function downloadTable(tableId, filename) {
            const table = document.getElementById(tableId);

            // Clone table to avoid modifying original
            const clone = table.cloneNode(true);

            const uri = 'data:application/vnd.ms-excel;base64,';
            const template =
                '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40"><head><!--[if gte mso 9]><xml><' +
                'x:ExcelWorkbook><' + 'x:ExcelWorksheets><' + 'x:ExcelWorksheet><' + 'x:Name>{worksheet}</' + 'x:Name><' +
                'x:WorksheetOptions><' + 'x:DisplayGridlines/></' + 'x:WorksheetOptions></' + 'x:ExcelWorksheet></' +
                'x:ExcelWorksheets></' +
                'x:ExcelWorkbook></xml><![endif]--><meta charset="UTF-8"></head><body><table>{table}</table></body></html>';

            const base64 = (s) => window.btoa(unescape(encodeURIComponent(s)));
            const format = (s, c) => s.replace(/{(\w+)}/g, (m, p) => c[p]);

            const ctx = {
                worksheet: 'Data Rekap',
                table: clone.innerHTML
            };

            const link = document.createElement('a');
            link.download = filename + '.xls';
            link.href = uri + base64(format(template, ctx));
            link.click();
        }
    </script>
    <style>
        /* Custom Animations & Hovers */
        .fade-in-up {
            animation: fadeInUp 0.5s ease-out forwards;
            opacity: 0;
            transform: translateY(20px);
        }

        @keyframes fadeInUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .hover-card {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border: 1px solid transparent;
        }

        .hover-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1) !important;
            border-color: rgba(16, 185, 129, 0.2);
            /* Emerald hint */
        }

        .icon-box {
            transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        .hover-card:hover .icon-box {
            transform: scale(1.15) rotate(5deg);
        }

        /* Table Enhancements */
        #tableRekap tbody tr {
            transition: background-color 0.2s ease;
        }

        #tableRekap tbody tr:hover {
            background-color: #ecfdf5;
            /* Very light emerald */
        }

        /* DataTable Customization */
        .dataTables_filter input {
            border-radius: 20px;
            border: 1px solid #e5e7eb;
            padding: 6px 15px;
            width: 250px;
            margin-left: 10px;
            transition: all 0.2s;
        }

        .dataTables_filter input:focus {
            outline: none;
            border-color: #10b981;
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
        }

        .page-item.active .page-link {
            background-color: #059669;
            border-color: #059669;
        }

        .page-link {
            color: #059669;
            border-radius: 8px;
            margin: 0 3px;
            border: none;
            transition: all 0.2s;
        }

        .page-link:hover {
            background-color: #d1fae5;
            color: #047857;
        }
    </style>
@endpush
