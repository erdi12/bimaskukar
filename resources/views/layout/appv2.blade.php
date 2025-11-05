<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - SIMTAK | Sistem Manajemen Informasi Majelis Taklim</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome untuk ikon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Amiri:wght@400;700&display=swap" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('voler/assets/css/simtak.css') }}">
</head>
<body>
    @include('include.sidebarv2')    
    <!-- Main Content -->
    <div class="main-content" id="mainContent">
        @include('include.headerv2')
        <!-- Content -->
        <div class="content">
            <div class="page-title">
                <h1>Dashboard</h1>
                <p>Selamat datang di panel admin SIMTAK</p>
            </div>
            
            <!-- Dashboard Cards -->
            <div class="row g-4 mb-4">
                <div class="col-lg-3 col-md-6">
                    <div class="dashboard-card primary">
                        <div class="card-header">
                            <h3 class="card-title">Total Jemaah</h3>
                            <div class="card-icon">
                                <i class="fas fa-users"></i>
                            </div>
                        </div>
                        <div class="card-value">1,245</div>
                        <div class="card-change positive">
                            <i class="fas fa-arrow-up"></i>
                            <span>12% dari bulan lalu</span>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-3 col-md-6">
                    <div class="dashboard-card warning">
                        <div class="card-header">
                            <h3 class="card-title">Kegiatan Bulan Ini</h3>
                            <div class="card-icon">
                                <i class="fas fa-calendar-alt"></i>
                            </div>
                        </div>
                        <div class="card-value">18</div>
                        <div class="card-change positive">
                            <i class="fas fa-arrow-up"></i>
                            <span>5% dari bulan lalu</span>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-3 col-md-6">
                    <div class="dashboard-card info">
                        <div class="card-header">
                            <h3 class="card-title">Majelis Aktif</h3>
                            <div class="card-icon">
                                <i class="fas fa-mosque"></i>
                            </div>
                        </div>
                        <div class="card-value">7</div>
                        <div class="card-change positive">
                            <i class="fas fa-arrow-up"></i>
                            <span>1 majelis baru</span>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-3 col-md-6">
                    <div class="dashboard-card danger">
                        <div class="card-header">
                            <h3 class="card-title">Donasi Bulan Ini</h3>
                            <div class="card-icon">
                                <i class="fas fa-donate"></i>
                            </div>
                        </div>
                        <div class="card-value">Rp 15.7jt</div>
                        <div class="card-change negative">
                            <i class="fas fa-arrow-down"></i>
                            <span>3% dari bulan lalu</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Charts Section -->
            <div class="row g-4 mb-4 charts-section">
                <div class="col-lg-8">
                    <div class="chart-card">
                        <div class="chart-header">
                            <h3 class="chart-title">Statistik Kehadiran Jemaah</h3>
                            <div class="chart-options">
                                <button class="chart-option active">Mingguan</button>
                                <button class="chart-option">Bulanan</button>
                                <button class="chart-option">Tahunan</button>
                            </div>
                        </div>
                        <div class="chart-container">
                            <canvas id="attendanceChart"></canvas>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-4">
                    <div class="chart-card">
                        <div class="chart-header">
                            <h3 class="chart-title">Distribusi Jemaah</h3>
                        </div>
                        <div class="chart-container">
                            <canvas id="distributionChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Activity Section -->
            <div class="activity-section">
                <div class="activity-header">
                    <h3 class="activity-title">Aktivitas Terkini</h3>
                    <a href="#" class="btn btn-sm btn-outline-success">Lihat Semua</a>
                </div>
                
                <ul class="activity-list">
                    <li class="activity-item">
                        <div class="activity-icon success">
                            <i class="fas fa-user-plus"></i>
                        </div>
                        <div class="activity-content">
                            <div class="activity-text">Jemaah baru bergabung: Budi Santoso</div>
                            <div class="activity-time">2 jam yang lalu</div>
                        </div>
                    </li>
                    
                    <li class="activity-item">
                        <div class="activity-icon info">
                            <i class="fas fa-calendar-plus"></i>
                        </div>
                        <div class="activity-content">
                            <div class="activity-text">Kegiatan baru ditambahkan: Kajian Akhir Pekan</div>
                            <div class="activity-time">5 jam yang lalu</div>
                        </div>
                    </li>
                    
                    <li class="activity-item">
                        <div class="activity-icon warning">
                            <i class="fas fa-donate"></i>
                        </div>
                        <div class="activity-content">
                            <div class="activity-text">Donasi diterima: Rp 500.000 dari Ahmad Fadli</div>
                            <div class="activity-time">1 hari yang lalu</div>
                        </div>
                    </li>
                    
                    <li class="activity-item">
                        <div class="activity-icon success">
                            <i class="fas fa-book"></i>
                        </div>
                        <div class="activity-content">
                            <div class="activity-text">Artikel baru dipublikasikan: "Menjaga Kebersihan Hati"</div>
                            <div class="activity-time">2 hari yang lalu</div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    
    @include('include.footerv2')
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <script>
        // Toggle Sidebar (minimize/maximize)
        document.getElementById('sidebarToggle').addEventListener('click', function() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('mainContent');
            const header = document.getElementById('header'); // Tambahkan ini
            const icon = this.querySelector('i');
            
            sidebar.classList.toggle('collapsed');
            mainContent.classList.toggle('expanded');
            header.classList.toggle('expanded'); // Tambahkan ini untuk mengubah class header
            
            // Change icon based on sidebar state
            if (sidebar.classList.contains('collapsed')) {
                icon.classList.remove('fa-bars');
                icon.classList.add('fa-chevron-right');
            } else {
                icon.classList.remove('fa-chevron-right');
                icon.classList.add('fa-bars');
            }
        });
        
        // Toggle Sidebar (for mobile)
        document.getElementById('menuToggle').addEventListener('click', function() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('active');
        });
        
        // Toggle Submenu
        document.querySelectorAll('.menu-item').forEach(item => {
            if (item.querySelector('.submenu')) {
                item.querySelector('.menu-link').addEventListener('click', function(e) {
                    e.preventDefault();
                    item.classList.toggle('active');
                });
            }
        });
        
        // Auto minimize sidebar on mobile when clicking outside
        document.addEventListener('click', function(event) {
            const sidebar = document.getElementById('sidebar');
            const isMobile = window.innerWidth <= 768;
            const isClickInsideSidebar = sidebar.contains(event.target);
            const isMenuToggle = document.getElementById('menuToggle').contains(event.target);
            
            // If on mobile, sidebar is active, and click is outside sidebar and not on menu toggle
            if (isMobile && sidebar.classList.contains('active') && !isClickInsideSidebar && !isMenuToggle) {
                sidebar.classList.remove('active');
            }
        });
        
        // Attendance Chart
        const attendanceCtx = document.getElementById('attendanceChart').getContext('2d');
        const attendanceChart = new Chart(attendanceCtx, {
            type: 'line',
            data: {
                labels: ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'],
                datasets: [{
                    label: 'Pria',
                    data: [65, 59, 80, 81, 56, 55, 40],
                    borderColor: '#2d7a2d',
                    backgroundColor: 'rgba(45, 122, 45, 0.1)',
                    tension: 0.4,
                    fill: true
                }, {
                    label: 'Wanita',
                    data: [45, 39, 60, 71, 46, 35, 30],
                    borderColor: '#d4af37',
                    backgroundColor: 'rgba(212, 175, 55, 0.1)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
        
        // Distribution Chart
        const distributionCtx = document.getElementById('distributionChart').getContext('2d');
        const distributionChart = new Chart(distributionCtx, {
            type: 'doughnut',
            data: {
                labels: ['Pria', 'Wanita', 'Anak-anak'],
                datasets: [{
                    data: [65, 35, 20],
                    backgroundColor: [
                        '#2d7a2d',
                        '#d4af37',
                        '#4caf50'
                    ],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                    }
                }
            }
        });
        
        // Chart Options Toggle
        document.querySelectorAll('.chart-option').forEach(button => {
            button.addEventListener('click', function() {
                document.querySelectorAll('.chart-option').forEach(btn => btn.classList.remove('active'));
                this.classList.add('active');
                
                // In a real application, you would update the chart data based on the selected option
                // This is just a demonstration
                const option = this.textContent;
                console.log('Chart option selected:', option);
            });
        });
    </script>
</body>
</html>