@extends('layout.app')
@section('dashboard', 'active')

@section('content')
<div class="main-content container-fluid">
    <div class="page-title">
        <h3>Dashboard</h3>
    </div>
    <section class="section">
        <div class="row mb-2">
            <div class="col-12 col-md-4">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <div class="d-flex flex-column">
                            <div class='px-3 py-3 d-flex justify-content-between'>
                                <h3 class='card-title text-white'>Total Majelis Ta'lim</h3>
                                <div class="card-right d-flex align-items-center">
                                    <h2 class="text-white">{{ count($sktpiagammts) }}</h2>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-4">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <div class="d-flex flex-column">
                            <div class='px-3 py-3 d-flex justify-content-between'>
                                <h3 class='card-title text-white'>Majelis Ta'lim Aktif</h3>
                                <div class="card-right d-flex align-items-center">
                                    <h2 class="text-white">{{ $totalAktif }}</h2>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-4">
                <div class="card bg-danger text-white">
                    <div class="card-body">
                        <div class="d-flex flex-column">
                            <div class='px-3 py-3 d-flex justify-content-between'>
                                <h3 class='card-title text-white'>Majelis Ta'lim Non-Aktif</h3>
                                <div class="card-right d-flex align-items-center">
                                    <h2 class="text-white">{{ $totalNonaktif }}</h2>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mb-4">
            <!-- Pie Chart untuk persentase aktif per kecamatan -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5>Persentase Majelis Ta'lim Aktif</h5>
                    </div>
                    <div class="card-body">
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
                    <div class="card-header">
                        <h5>Persentase Majelis Ta'lim Non-Aktif</h5>
                    </div>
                    <div class="card-body">
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
        <div class="row mb-4">
            <!-- Bar Chart per kecamatan -->
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5>Rekapitulasi Per Kecamatan</h5>
                    </div>
                    <div class="card-body">
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
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="card-title">Detail Data</h4>
                        <div class="d-flex">
                            <i data-feather="download"></i>
                        </div>
                    </div>
                    <div class="card-body px-0 pb-0">
                        <div class="table-responsive">
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
                                    @foreach($kecamatanData as $index => $data)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ ucwords($data['nama']) }}</td>
                                        <td>{{ $data['total'] }}</td>
                                        <td>{{ $data['aktif'] }}</td>
                                        <td>{{ $data['nonaktif'] }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @endsection
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Ambil data mentah dari server (array objek: { nama, total, aktif, nonaktif })
    const rawData = @json($kecamatanData);

    // Fungsi untuk mengkapitalisasi nama
    const capitalize = str => str.split(' ')
        .map(word => word.charAt(0).toUpperCase() + word.slice(1).toLowerCase())
        .join(' ');

    // Hanya tampilkan kecamatan yang memiliki total > 0
    const filteredData = rawData.filter(item => item.total && item.total > 0)
        .map(item => ({...item, nama: capitalize(item.nama)}));

    // Jika tidak ada data, tampilkan pesan sederhana dan hentikan inisialisasi chart
    if (!filteredData.length) {
        // kosong => tidak ada chart untuk ditampilkan
        return;
    }

    // Urutkan dari terbesar ke terkecil untuk tampilannya lebih rapi
    filteredData.sort((a, b) => b.total - a.total);

    // --- Bar chart horizontal (indexAxis: 'y') ---
    const ctxBar = document.getElementById('kecamatanChart').getContext('2d');
    const kecamatanChart = new Chart(ctxBar, {
        type: 'bar',
        data: {
            labels: filteredData.map(i => i.nama),
            datasets: [
                {
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
                    label: 'Non-Aktif',
                    data: filteredData.map(i => i.nonaktif),
                    backgroundColor: 'rgba(255, 99, 132, 0.7)',
                    borderColor: 'rgba(255, 99, 132, 1)',
                    borderWidth: 1
                }
            ]
        },
        options: {
            indexAxis: 'y', // horizontal bars (ke kanan)
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                x: {
                    beginAtZero: true,
                    ticks: { precision: 0 },
                    title: { display: true, text: 'Jumlah Majelis Ta\'lim' }
                }
            },
            plugins: {
                title: { display: true, text: 'Jumlah Majelis Ta\'lim per Kecamatan', font: { size: 16 } },
                legend: { position: 'top' }
            }
        }
    });

    // --- Pie chart Aktif (hanya kecamatan dengan aktif > 0) ---
    const aktifData = filteredData.filter(i => i.aktif && i.aktif > 0);
    if (aktifData.length) {
        const ctxAktif = document.getElementById('aktifPieChart').getContext('2d');
        const totalAktifShown = aktifData.reduce((s, i) => s + i.aktif, 0);
        new Chart(ctxAktif, {
            type: 'pie',
            data: {
                labels: aktifData.map(i => i.nama),
                datasets: [{
                    data: aktifData.map(i => i.aktif),
                    backgroundColor: [
                        'rgba(75, 192, 192, 0.7)','rgba(54, 162, 235, 0.7)','rgba(153, 102, 255, 0.7)',
                        'rgba(255, 159, 64, 0.7)','rgba(201, 203, 207, 0.7)','rgba(255, 205, 86, 0.7)',
                        'rgba(75, 192, 192, 0.5)','rgba(54, 162, 235, 0.5)','rgba(153, 102, 255, 0.5)','rgba(255, 159, 64, 0.5)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.raw || 0;
                                const pct = totalAktifShown ? Math.round((value / totalAktifShown) * 100) : 0;
                                return `${label}: ${value} (${pct}%)`;
                            }
                        }
                    },
                    legend: { position: 'bottom', labels: { boxWidth: 12, font: { size: 10 } } }
                }
            }
        });
    } else {
        // kosongkan canvas jika tidak ada data
        document.getElementById('aktifPieChart').style.display = 'none';
    }

    // --- Pie chart Non-Aktif (hanya kecamatan dengan nonaktif > 0) ---
    const nonaktifData = filteredData.filter(i => i.nonaktif && i.nonaktif > 0);
    if (nonaktifData.length) {
        const ctxNonaktif = document.getElementById('nonaktifPieChart').getContext('2d');
        const totalNonaktifShown = nonaktifData.reduce((s, i) => s + i.nonaktif, 0);
        new Chart(ctxNonaktif, {
            type: 'pie',
            data: {
                labels: nonaktifData.map(i => i.nama),
                datasets: [{
                    data: nonaktifData.map(i => i.nonaktif),
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.7)','rgba(255, 159, 64, 0.7)','rgba(255, 205, 86, 0.7)',
                        'rgba(201, 203, 207, 0.7)','rgba(54, 162, 235, 0.7)','rgba(153, 102, 255, 0.7)',
                        'rgba(255, 99, 132, 0.5)','rgba(255, 159, 64, 0.5)','rgba(255, 205, 86, 0.5)','rgba(201, 203, 207, 0.5)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.raw || 0;
                                const pct = totalNonaktifShown ? Math.round((value / totalNonaktifShown) * 100) : 0;
                                return `${label}: ${value} (${pct}%)`;
                            }
                        }
                    },
                    legend: { position: 'bottom', labels: { boxWidth: 12, font: { size: 10 } } }
                }
            }
        });
    } else {
        document.getElementById('nonaktifPieChart').style.display = 'none';
    }

    // Download handlers (cek keberadaan chart terlebih dahulu)
    const dlBar = document.getElementById('downloadChart');
    if (dlBar) dlBar.addEventListener('click', function() {
        const link = document.createElement('a');
        link.download = 'Rekapitulasi_Majelis_Talim_Per_Kecamatan.png';
        link.href = kecamatanChart.toBase64Image();
        link.click();
    });

    const dlAktif = document.getElementById('downloadAktifPieChart');
    if (dlAktif) dlAktif.addEventListener('click', function() {
        const c = document.getElementById('aktifPieChart');
        if (c && c.toDataURL) {
            const link = document.createElement('a');
            link.download = 'Persentase_Majelis_Talim_Aktif_Per_Kecamatan.png';
            // get canvas from Chart.js instance: easiest is to select canvas element
            link.href = c.toDataURL('image/png');
            link.click();
        }
    });

    const dlNon = document.getElementById('downloadNonaktifPieChart');
    if (dlNon) dlNon.addEventListener('click', function() {
        const c = document.getElementById('nonaktifPieChart');
        if (c && c.toDataURL) {
            const link = document.createElement('a');
            link.download = 'Persentase_Majelis_Talim_Nonaktif_Per_Kecamatan.png';
            link.href = c.toDataURL('image/png');
            link.click();
        }
    });
});
</script>
@endpush
