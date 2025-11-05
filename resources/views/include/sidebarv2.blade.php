<!-- Sidebar -->
<div class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <div class="logo-container">
            <h1 class="logo-text">SIMTAK</h1>
            <img src="{{ asset('voler/assets/images/logo-kemenag.png') }}" alt="Logo Kemenag" class="logo-image">
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