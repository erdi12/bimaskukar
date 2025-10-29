@extends('layout.app')
@section('sub-layanan', 'active')
@section('layanan', 'active')
@section('skt-mt', 'active')

@section('content')
<div class="main-content container-fluid">
    <div class="page-title">
        <h3>Rekapan Data Majelis Ta'lim</h3>
    </div>
    
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Rekapitulasi Data Majelis Ta'lim</h4>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <div class="card bg-primary text-white">
                                <div class="card-body">
                                    <h5 class="card-title text-white">Total Majelis Ta'lim</h5>
                                    <h2 class="text-white">{{ count($sktpiagammts) }}</h2>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-success text-white">
                                <div class="card-body">
                                    <h5 class="card-title text-white">Majelis Ta'lim Aktif</h5>
                                    <h2 class="text-white">{{ $totalAktif }}</h2>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-danger text-white">
                                <div class="card-body">
                                    <h5 class="card-title text-white">Majelis Ta'lim Non-Aktif</h5>
                                    <h2 class="text-white">{{ $totalNonaktif }}</h2>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Tambahkan row baru untuk pie charts -->                    
                    <div class="row mb-4">
                        <!-- Pie Chart untuk persentase aktif per kecamatan -->
                        <div class="col-md-6">
                            <div class="card shadow">
                                <div class="card-body">
                                    <h5>Persentase Majelis Ta'lim Aktif</h5>
                                    <div class="chart-container" style="position: relative; height:250px; width:100%;">
                                        <canvas id="aktifPieChart"></canvas>
                                    </div>
                                    <div class="text-center mt-2">
                                        <button id="downloadAktifPieChart" class="btn btn-success btn-sm">
                                            <i data-feather="download"></i> Download
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Pie Chart untuk persentase non-aktif per kecamatan -->
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-body">
                                    <h5>Persentase Majelis Ta'lim Non-Aktif</h5>
                                <div class="chart-container" style="position: relative; height:250px; width:100%;">
                                    <canvas id="nonaktifPieChart"></canvas>
                                </div>
                                <div class="text-center mt-2">
                                    <button id="downloadNonaktifPieChart" class="btn btn-danger btn-sm">
                                        <i data-feather="download"></i> Download
                                    </button>
                                </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Row untuk bar chart -->
                    <div class="row mb-4">
                        <!-- Bar Chart per kecamatan -->
                        <div class="col-md-12">
                            <div class="card shadow">
                                <div class="card-body">
                                    <h5>Rekapitulasi Per Kecamatan</h5>
                                    <div class="chart-container" style="position: relative; height:300px; width:100%;">
                                        <canvas id="kecamatanChart"></canvas>
                                    </div>
                                    <div class="text-center mt-2">
                                        <button id="downloadChart" class="btn btn-primary btn-sm">
                                            <i data-feather="download"></i> Download Bar Chart
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Tabel tetap dipertahankan sebagai referensi data -->
                    <div class="table-responsive mt-4">
                        <h6>Detail Data</h6>
                        <table class="table table-hover table-bordered">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Kecamatan</th>
                                    <th>Total</th>
                                    <th>Aktif</th>
                                    <th>Non-Aktif</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($totalPerKecamatan as $kecamatan => $data)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ ucfirst($kecamatan) }}</td>
                                    <td>{{ $data['total'] }}</td>
                                    <td>{{ $data['aktif'] }}</td>
                                    <td>{{ $data['nonaktif'] }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-4">
                        <a href="{{ route('skt_piagam_mt.index') }}" class="btn btn-secondary">
                            <i data-feather="arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Script untuk Chart.js -->
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Data dari PHP ke JavaScript
        const kecamatanData = @json($totalPerKecamatan);
        const totalAktif = {{ $totalAktif }};
        const totalNonaktif = {{ $totalNonaktif }};
        const totalMajelis = {{ count($sktpiagammts) }};
        
        // Persiapkan data untuk bar chart
        const labels = Object.keys(kecamatanData).map(kecamatan => {
            // Kapitalisasi nama kecamatan (huruf pertama kapital)
            return kecamatan.charAt(0).toUpperCase() + kecamatan.slice(1).toLowerCase();
        });
        const totalData = [];
        const aktifData = [];
        const nonaktifData = [];
        
        Object.keys(kecamatanData).forEach((kecamatan, index) => {
            totalData.push(kecamatanData[kecamatan].total);
            aktifData.push(kecamatanData[kecamatan].aktif);
            nonaktifData.push(kecamatanData[kecamatan].nonaktif);
        });
        
        // Buat bar chart
        const ctxBar = document.getElementById('kecamatanChart').getContext('2d');
        const kecamatanChart = new Chart(ctxBar, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Total',
                        data: totalData,
                        backgroundColor: 'rgba(54, 162, 235, 0.7)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    },
                    {
                        label: 'Aktif',
                        data: aktifData,
                        backgroundColor: 'rgba(75, 192, 192, 0.7)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1
                    },
                    {
                        label: 'Non-Aktif',
                        data: nonaktifData,
                        backgroundColor: 'rgba(255, 99, 132, 0.7)',
                        borderColor: 'rgba(255, 99, 132, 1)',
                        borderWidth: 1
                    }
                ]
            },
            options: {
                indexAxis: 'y', // Ini akan membuat bar chart menjadi horizontal
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    x: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        }
                    }
                },
                plugins: {
                    title: {
                        display: true,
                        text: 'Jumlah Majelis Ta\'lim per Kecamatan',
                        font: {
                            size: 16
                        }
                    },
                    legend: {
                        position: 'top'
                    }
                }
            }
        });
        
        // Persiapkan data untuk pie chart aktif
        const aktifLabels = [];
        const aktifValues = [];
        const aktifColors = [
            'rgba(75, 192, 192, 0.7)',   // Hijau
            'rgba(54, 162, 235, 0.7)',   // Biru
            'rgba(153, 102, 255, 0.7)',  // Ungu
            'rgba(255, 159, 64, 0.7)',   // Oranye
            'rgba(201, 203, 207, 0.7)',  // Abu-abu
            'rgba(255, 205, 86, 0.7)',   // Kuning
            'rgba(75, 192, 192, 0.5)',   // Hijau muda
            'rgba(54, 162, 235, 0.5)',   // Biru muda
            'rgba(153, 102, 255, 0.5)',  // Ungu muda
            'rgba(255, 159, 64, 0.5)',   // Oranye muda
        ];
        const aktifBorderColors = aktifColors.map(color => color.replace('0.7', '1').replace('0.5', '1'));
        
        // Persiapkan data untuk pie chart non-aktif
        const nonaktifLabels = [];
        const nonaktifValues = [];
        const nonaktifColors = [
            'rgba(255, 99, 132, 0.7)',    // Merah
            'rgba(255, 159, 64, 0.7)',   // Oranye
            'rgba(255, 205, 86, 0.7)',   // Kuning
            'rgba(201, 203, 207, 0.7)',  // Abu-abu
            'rgba(54, 162, 235, 0.7)',   // Biru
            'rgba(153, 102, 255, 0.7)',  // Ungu
            'rgba(255, 99, 132, 0.5)',   // Merah muda
            'rgba(255, 159, 64, 0.5)',   // Oranye muda
            'rgba(255, 205, 86, 0.5)',   // Kuning muda
            'rgba(201, 203, 207, 0.5)',  // Abu-abu muda
        ];
        const nonaktifBorderColors = nonaktifColors.map(color => color.replace('0.7', '1').replace('0.5', '1'));
        
        // Isi data untuk kedua pie chart
        Object.keys(kecamatanData).forEach((kecamatan, index) => {
            const capitalizedKecamatan = kecamatan.charAt(0).toUpperCase() + kecamatan.slice(1).toLowerCase();
            
            // Hanya tambahkan ke chart aktif jika ada data aktif
            if (kecamatanData[kecamatan].aktif > 0) {
                aktifLabels.push(capitalizedKecamatan);
                aktifValues.push(kecamatanData[kecamatan].aktif);
            }
            
            // Hanya tambahkan ke chart non-aktif jika ada data non-aktif
            if (kecamatanData[kecamatan].nonaktif > 0) {
                nonaktifLabels.push(capitalizedKecamatan);
                nonaktifValues.push(kecamatanData[kecamatan].nonaktif);
            }
        });
        
        // Buat pie chart untuk majelis ta'lim aktif
        const ctxAktif = document.getElementById('aktifPieChart').getContext('2d');
        const aktifPieChart = new Chart(ctxAktif, {
            type: 'pie',
            data: {
                labels: aktifLabels,
                datasets: [{
                    data: aktifValues,
                    backgroundColor: aktifColors.slice(0, aktifLabels.length),
                    borderColor: aktifBorderColors.slice(0, aktifLabels.length),
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    title: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.raw || 0;
                                const percentage = Math.round((value / totalAktif) * 100);
                                return `${label}: ${value} (${percentage}%)`;
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
        });
        
        // Buat pie chart untuk majelis ta'lim non-aktif
        const ctxNonaktif = document.getElementById('nonaktifPieChart').getContext('2d');
        const nonaktifPieChart = new Chart(ctxNonaktif, {
            type: 'pie',
            data: {
                labels: nonaktifLabels,
                datasets: [{
                    data: nonaktifValues,
                    backgroundColor: nonaktifColors.slice(0, nonaktifLabels.length),
                    borderColor: nonaktifBorderColors.slice(0, nonaktifLabels.length),
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    title: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.raw || 0;
                                const percentage = Math.round((value / totalNonaktif) * 100);
                                return `${label}: ${value} (${percentage}%)`;
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
        });
        
        // Fungsi untuk download bar chart
        document.getElementById('downloadChart').addEventListener('click', function() {
            const link = document.createElement('a');
            link.download = 'Rekapitulasi_Majelis_Talim_Per_Kecamatan.png';
            link.href = kecamatanChart.toBase64Image();
            link.click();
        });
        
        // Fungsi untuk download pie chart aktif
        document.getElementById('downloadAktifPieChart').addEventListener('click', function() {
            const link = document.createElement('a');
            link.download = 'Persentase_Majelis_Talim_Aktif_Per_Kecamatan.png';
            link.href = aktifPieChart.toBase64Image();
            link.click();
        });
        
        // Fungsi untuk download pie chart non-aktif
        document.getElementById('downloadNonaktifPieChart').addEventListener('click', function() {
            const link = document.createElement('a');
            link.download = 'Persentase_Majelis_Talim_Nonaktif_Per_Kecamatan.png';
            link.href = nonaktifPieChart.toBase64Image();
            link.click();
        });
    });
</script>
@endpush
@endsection