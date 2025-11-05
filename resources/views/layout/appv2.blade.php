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
    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <div class="logo-container">
                <h1 class="logo-text">SIMTAK</h1>
                <img src="https://i.ibb.co/6P6sQ2v/kemenag-logo.png" alt="Logo Kemenag" class="logo-image">
            </div>
            <p>Sistem Informasi Manajemen Majelis Taklim</p>
        </div>
        
        <div class="sidebar-menu">
            <div class="menu-item active" data-tooltip="Dashboard">
                <a href="#" class="menu-link">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
            </div>
            
            <div class="menu-item" data-tooltip="Jemaah">
                <a href="#" class="menu-link">
                    <i class="fas fa-users"></i>
                    <span>Jemaah</span>
                </a>
            </div>
            
            <div class="menu-item" data-tooltip="Kegiatan">
                <a href="#" class="menu-link">
                    <i class="fas fa-calendar-alt"></i>
                    <span>Kegiatan</span>
                    <span class="badge">3</span>
                </a>
            </div>
            
            <div class="menu-item" data-tooltip="Majelis">
                <a href="#" class="menu-link">
                    <i class="fas fa-mosque"></i>
                    <span>Majelis</span>
                </a>
            </div>
            
            <div class="menu-item" data-tooltip="Donasi">
                <a href="#" class="menu-link">
                    <i class="fas fa-donate"></i>
                    <span>Donasi</span>
                </a>
            </div>
            
            <div class="menu-item" data-tooltip="Keilmuan">
                <a href="#" class="menu-link">
                    <i class="fas fa-book"></i>
                    <span>Keilmuan</span>
                </a>
                <div class="submenu">
                    <a href="#" class="menu-link">
                        <i class="fas fa-angle-right"></i>
                        <span>Artikel</span>
                    </a>
                    <a href="#" class="menu-link">
                        <i class="fas fa-angle-right"></i>
                        <span>Video Kajian</span>
                    </a>
                    <a href="#" class="menu-link">
                        <i class="fas fa-angle-right"></i>
                        <span>Audio Kajian</span>
                    </a>
                </div>
            </div>
            
            <div class="menu-item" data-tooltip="Laporan">
                <a href="#" class="menu-link">
                    <i class="fas fa-chart-bar"></i>
                    <span>Laporan</span>
                </a>
            </div>
            
            <div class="menu-item" data-tooltip="Pengaturan">
                <a href="#" class="menu-link">
                    <i class="fas fa-cog"></i>
                    <span>Pengaturan</span>
                </a>
            </div>
        </div>
        
        <div class="sidebar-footer">
            <div class="user-info">
                <div class="user-avatar">AU</div>
                <div class="user-details">
                    <p class="user-name">Ahmad Umar</p>
                    <p class="user-role">Administrator</p>
                </div>
            </div>
            <button class="logout-btn">
                <i class="fas fa-sign-out-alt"></i>
                <span>Keluar</span>
            </button>
        </div>
    </div>
    
    <!-- Main Content -->
    <div class="main-content" id="mainContent">
        <!-- Header -->
        <div class="header">
            <div class="header-left">
                <button class="menu-toggle" id="menuToggle">
                    <i class="fas fa-bars"></i>
                </button>
                <button class="sidebar-toggle" id="sidebarToggle">
                    <i class="fas fa-bars"></i>
                </button>
                <div class="search-bar">
                    <i class="fas fa-search"></i>
                    <input type="text" class="form-control" placeholder="Cari...">
                </div>
            </div>
            
            <div class="header-right">
                <div class="notification-icon">
                    <i class="fas fa-bell"></i>
                    <span class="badge">5</span>
                </div>
                
                <div class="header-user dropdown">
                    <div class="user-avatar" data-bs-toggle="dropdown">AU</div>
                    <div class="user-info">
                        <p class="user-name">Ahmad Umar</p>
                        <p class="user-role">Administrator</p>
                    </div>
                    <button class="btn dropdown-toggle" type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-chevron-down"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                        <li><a class="dropdown-item" href="#">Profil</a></li>
                        <li><a class="dropdown-item" href="#">Pengaturan</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="#">Keluar</a></li>
                    </ul>
                </div>
            </div>
        </div>
        
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
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <script>
        // Toggle Sidebar (minimize/maximize)
        document.getElementById('sidebarToggle').addEventListener('click', function() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('mainContent');
            const icon = this.querySelector('i');
            
            sidebar.classList.toggle('collapsed');
            mainContent.classList.toggle('expanded');
            
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