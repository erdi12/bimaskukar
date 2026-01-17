  <nav class="navbar navbar-expand-lg bg-white shadow-sm fixed-top">
      <div class="container">
          <a class="navbar-brand d-flex align-items-center" href="#">
              <img src="{{ asset('voler/assets/images/logo-kemenag.png') }}" alt="Logo Kemenag" height="48">
          </a>
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
              <span class="navbar-toggler-icon"></span>
          </button>
          <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
              <ul class="navbar-nav align-items-center">
                  <li class="nav-item"><a class="nav-link text-primary-custom" href="{{ route('home') }}">Beranda</a>
                  </li>
                  <li class="nav-item"><a class="nav-link text-primary-custom" href="{{ url('/#layanan') }}">Layanan</a>
                  </li>
                  <li class="nav-item"><a
                          class="nav-link text-primary-custom {{ request()->routeIs('profil') ? 'fw-bold' : '' }}"
                          href="{{ route('profil') }}">Profil & Tim</a></li>
                  <li class="nav-item"><a
                          class="nav-link text-primary-custom {{ request()->routeIs('data_keagamaan') ? 'fw-bold' : '' }}"
                          href="{{ route('data_keagamaan') }}">Data Keagamaan</a></li>
                  <li class="nav-item"><a
                          class="nav-link text-primary-custom {{ request()->routeIs('kontak') ? 'active fw-bold' : '' }}"
                          href="{{ route('kontak') }}">Kontak</a>
                  </li>
                  <li class="nav-item"><a
                          class="nav-link text-primary-custom {{ request()->routeIs('cek_validitas') ? 'active fw-bold' : '' }}"
                          href="{{ route('cek_validitas') }}">Cek Validitas</a>
                  </li>
                  <li class="nav-item ms-lg-3">
                      <a href="{{ route('login') }}" class="btn btn-sm btn-outline-success rounded-pill px-3">
                          <i class="bi bi-box-arrow-in-right me-1"></i> Login
                      </a>
                  </li>
              </ul>
          </div>
      </div>
  </nav>
