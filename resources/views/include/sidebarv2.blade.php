<!-- Sidebar -->
<style>
    /* Pastikan sidebar menggunakan Flexbox untuk layout penuh ke bawah */
    .sidebar {
        display: flex;
        flex-direction: column;
        height: 100vh;
        /* Tinggi penuh layar */
        overflow: hidden;
        /* Hindari scroll ganda pada container utama */
    }

    /* Header tetap di atas */
    .sidebar-header {
        flex-shrink: 0;
    }

    /* Footer tetap di bawah, tapi tidak boleh tertimpa */
    .sidebar-footer {
        flex-shrink: 0 !important;
        position: relative !important;
        /* Paksa ambil ruang fisik, bukan ngambang */
        bottom: auto !important;
        background-color: #277748;
        /* Hijau solid yang mendekati warna sidebar agar tidak tembus pandang */
        z-index: 50 !important;
        width: 100%;
        padding-top: 10px;
        box-shadow: 0 -4px 10px rgba(0, 0, 0, 0.1);
    }

    /* Menu Area */
    .sidebar-menu {
        flex: 1 !important;
        /* Ambil sisa ruang */
        overflow-y: auto !important;
        overflow-x: hidden !important;
        height: auto !important;
        padding-bottom: 20px;
        /* Jarak aman */
    }

    /* Custom Scrollbar untuk Webkit (Chrome, Safari, Edge) */
    .sidebar-menu::-webkit-scrollbar {
        width: 6px;
    }

    .sidebar-menu::-webkit-scrollbar-track {
        background: transparent;
    }

    .sidebar-menu::-webkit-scrollbar-thumb {
        background-color: rgba(255, 255, 255, 0.2);
        border-radius: 20px;
    }

    .sidebar-menu:hover::-webkit-scrollbar-thumb {
        background-color: rgba(255, 255, 255, 0.4);
    }
</style>
<div class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <div class="logo-container">
            <h1 class="logo-text">S I B E R K A T</h1>
            <img src="{{ asset('voler/assets/images/logo-kemenag.png') }}" alt="Logo Kemenag" class="logo-image">
        </div>
        <p>Sistem Informasi Bimas Islam Kutai Kartanegara Berbasis Digital</p>
    </div>

    <div class="sidebar-menu">
        {{-- Dashboard --}}
        <div class="menu-item @yield('dashboard')" data-tooltip="Dashboard">
            <a href="{{ route('dashboard_v2') }}" class="menu-link">
                <i class="fas fa-tachometer-alt"></i>
                <span>Dashboard</span>
            </a>
        </div>

        {{-- Layanan --}}
        <div class="sidebar-title">Layanan</div>
        <div class="menu-item @yield('sub-layanan')" data-tooltip="Layanan">
            <a href="#" class="menu-link @yield('layanan')">
                <i class="fas fa-file-alt"></i>
                <span class="flex-grow-1">Layanan SKT & Piagam</span>
                <i class="fas fa-chevron-right arrow-icon" style="font-size: 0.8rem; transition: transform 0.3s;"></i>
            </a>
            <div class="submenu @yield('skt-mt')">
                <a href="{{ route('skt_piagam_mt_v2.index') }}" class="menu-link">
                    <i class="fas fa-file-contract"></i>
                    <span>SKT Majelis Taklim</span>
                </a>
                <a href="{{ route('skt_masjid.index') }}" class="menu-link @yield('masjid')">
                    <i class="fas fa-mosque"></i>
                    <span>SKT Masjid</span>
                </a>
                <a href="{{ route('skt_mushalla.index') }}" class="menu-link @yield('mushalla')">
                    <i class="fas fa-place-of-worship"></i>
                    <span>SKT Mushalla</span>
                </a>
                @if (auth()->check() &&
                        auth()->user()->hasAnyRole(['Admin', 'Editor', 'Operator']))
                    <a href="{{ route('marbot.index') }}" class="menu-link @yield('marbot')">
                        <i class="fas fa-user-check"></i>
                        <span>Data Marbot</span>
                    </a>
                @endif
            </div>
        </div>

        {{-- Data Master --}}
        <div class="sidebar-title">DATA MASTER</div>
        <div class="menu-item @yield('data-master')" data-tooltip="Data Master">
            <a href="#" class="menu-link">
                <i class="fas fa-database"></i>
                <span class="flex-grow-1">Data Master</span>
                <i class="fas fa-chevron-right arrow-icon" style="font-size: 0.8rem; transition: transform 0.3s;"></i>
            </a>
            <div class="submenu">
                <a href="{{ route('kecamatan_v2.index') }}" class="menu-link @yield('kecamatan')">
                    <i class="fas fa-circle fa-2xs"></i>
                    <span>Kecamatan</span>
                </a>
                <a href="{{ route('kelurahan_v2.index') }}" class="menu-link @yield('kelurahan')">
                    <i class="fas fa-circle fa-2xs"></i>
                    <span>Kelurahan</span>
                </a>
            </div>
        </div>

        {{-- CMS / Web Info --}}
        <div class="sidebar-title">CMS / Info Web</div>
        <div class="menu-item @yield('cms')" data-tooltip="Pengaturan Website">
            <a href="#" class="menu-link">
                <i class="fas fa-globe"></i>
                <span class="flex-grow-1">Pengaturan Web</span>
                <i class="fas fa-chevron-right arrow-icon" style="font-size: 0.8rem; transition: transform 0.3s;"></i>
            </a>
            <div class="submenu">
                <a href="{{ route('pegawai.index') }}" class="menu-link @yield('pegawai')">
                    <i class="fas fa-user-tie"></i>
                    <span>Data Pegawai</span>
                </a>
                <a href="{{ route('layanan.index') }}" class="menu-link @yield('mnj_layanan')">
                    <i class="fas fa-hand-holding-heart"></i>
                    <span>Layanan & SOP</span>
                </a>
            </div>
        </div>

        {{-- Admin Menu --}}
        @if (auth()->check() && auth()->user()->hasRole('Admin'))
            <div class="sidebar-title">Admin</div>
            <div class="menu-item @yield('admin')" data-tooltip="Manajemen User">
                <a href="#" class="menu-link">
                    <i class="fas fa-users-cog"></i>
                    <span class="flex-grow-1">Admin</span>
                    <i class="fas fa-chevron-right arrow-icon"
                        style="font-size: 0.8rem; transition: transform 0.3s;"></i>
                </a>
                <div class="submenu">
                    <a href="{{ route('roles_v2.index') }}" class="menu-link @yield('roles')">
                        <i class="fas fa-user-shield"></i>
                        <span>Manajemen Role</span>
                    </a>
                    <a href="{{ route('users_v2.index') }}" class="menu-link @yield('users')">
                        <i class="fas fa-users"></i>
                        <span>Manajemen User</span>
                    </a>
                    <a href="{{ route('audit_log.index') }}" class="menu-link @yield('audit-log')">
                        <i class="fas fa-history"></i>
                        <span>Audit Log</span>
                    </a>
                </div>
            </div>
        @endif

        {{-- Tong Sampah --}}
        @if (auth()->check() &&
                auth()->user()->hasAnyRole(['Admin', 'Editor', 'Operator']))
            <div class="sidebar-title">Tong Sampah</div>
            <div class="menu-item @yield('trash')" data-tooltip="Tong Sampah">
                <a href="{{ route('skt_piagam_mt_v2.trash') }}" class="menu-link">
                    <i class="fas fa-trash"></i>
                    <span>Sampah Majelis Taklim</span>
                </a>
            </div>
        @endif

    </div>


</div>
