<body>
    <div id="app">
        <div id="sidebar" class='active'>
            <div class="sidebar-wrapper active">
    <div class="sidebar-header">
        <img src="{{ asset('voler/assets/images/logo-bimas.svg') }}" alt="" srcset="" class="img-fluid">
    </div>
    <div class="sidebar-menu">
        <ul class="menu">
            
                
            
            
            
            <li class="sidebar-item @yield('dashboard') ">
                
                <a href="{{ route('dashboard') }}" class='sidebar-link'>
                    <i data-feather="home" width="20"></i> 
                    <span>Dashboard</span>
                </a>
                
            </li>
            
            
            <li class='sidebar-title'>Layanan</li>
                
                <li class="sidebar-item has-sub @yield('sub-layanan')">

                    <a href="#" class='sidebar-link @yield('layanan')'>
                        <i data-feather="droplet" width="20"></i> 
                        <span>Layanan SKT dan Piagam Majelis Ta'lim</span>
                    </a>

                    
                    <ul class="submenu @yield('skt-mt')">
                        
                        <li>
                            <a href="{{ route('skt_piagam_mt.index') }}">
                                    Surat Keterangan Terdaftar
                            </a>
                        </li>
                        
                        {{-- <li>
                            <a href="{{ route('skt_piagam_mt.create') }}">Piagam</a>
                        </li> --}}
                        
                    </ul>
                    
                </li>
                <li class='sidebar-title'>DATA MASTER</li>
                    <li class="sidebar-item  has-sub">

                        <a href="#" class='sidebar-link'>
                            <i data-feather="server" width="20"></i> 
                            <span>Data Kecamatan dan Kelurahan</span>
                        </a>

                        
                        <ul class="submenu ">
                            
                            <li>
                                <a href="ui-chatbox.html">Kecamatan</a>
                            </li>
                            
                            <li>
                                <a href="ui-pricing.html">Kelurahan</a>
                            </li>                            
                        </ul>                        
                    </li>

                <li class='sidebar-title'>Tong Sampah</li>
                <li class="sidebar-item @yield('trash')">
                    <a href="{{ route('skt_piagam_mt.trash') }}" class='sidebar-link'>
                            <i data-feather="book-open"></i>
                            <span>Majelis Taklim</span>
                    </a>
                </li>                     
        </ul>
    </div>
    <button class="sidebar-toggler btn x"><i data-feather="x"></i></button>
</div>
        </div>