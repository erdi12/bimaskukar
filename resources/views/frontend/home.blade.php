<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bimbingan Masyarakat Islam - Kemenag Kukar</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        :root {
            --primary-color: #198754; /* Hijau sebagai warna utama */
            --secondary-color: #6c757d;
            --success-color: #20c997;
            --light-bg: #f8f9fa;
            --dark-green: #0f5132; /* Hijau gelap untuk hover */
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        /* Navbar Styles */
        .navbar {
            background-color: var(--primary-color) !important;
            box-shadow: 0 2px 4px rgba(0,0,0,.1);
        }
        
        .navbar-brand {
            font-weight: 700;
            font-size: 1.3rem;
        }
        
        .navbar-nav .nav-link {
            color: rgba(255,255,255,.85) !important;
            font-weight: 500;
            transition: all 0.3s;
        }
        
        .navbar-nav .nav-link:hover {
            color: white !important;
            transform: translateY(-2px);
        }
        
        /* Carousel Styles */
        .carousel-item {
            height: 60vh;
            min-height: 300px;
            background: no-repeat center center scroll;
            -webkit-background-size: cover;
            -moz-background-size: cover;
            -o-background-size: cover;
            background-size: cover;
        }
        
        .carousel-caption {
            background-color: rgba(0, 0, 0, 0.6);
            padding: 20px;
            border-radius: 10px;
            max-width: 80%;
            margin: 0 auto;
        }
        
        /* Service Cards */
        .service-card {
            border: none;
            border-radius: 15px;
            overflow: hidden;
            transition: all 0.3s ease;
            height: 100%;
            box-shadow: 0 4px 6px rgba(0,0,0,.1);
        }
        
        .service-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 10px 20px rgba(0,0,0,.15);
        }
        
        .service-icon {
            font-size: 3rem;
            color: var(--primary-color);
            margin-bottom: 15px;
        }
        
        .service-card .card-body {
            padding: 1.5rem;
        }
        
        .service-card .card-title {
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 15px;
        }
        
        .btn-outline-primary {
            color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .btn-outline-primary:hover {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        /* Welcome Section */
        .welcome-section {
            background-color: var(--light-bg);
            padding: 60px 0;
        }
        
        .welcome-img {
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,.1);
            max-width: 300px;
        }
        
        .welcome-content {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,.05);
        }
        
        .welcome-title {
            color: var(--primary-color);
            font-weight: 700;
            margin-bottom: 20px;
        }
        
        /* Footer */
        footer {
            background-color: var(--dark-green);
            color: white;
            padding: 30px 0;
            margin-top: 50px;
        }
        
        footer a {
            color: #a3cfbb;
            text-decoration: none;
        }
        
        footer a:hover {
            color: white;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .carousel-item {
                height: 40vh;
            }
            
            .welcome-img {
                margin-bottom: 30px;
            }
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark sticky-top">
        <div class="container">
            <a class="navbar-brand" href="#">
                <i class="bi bi-mosque me-2"></i>
                Bimbingan Masyarakat Islam
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="#"><i class="bi bi-house-door me-1"></i> Beranda</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#"><i class="bi bi-info-circle me-1"></i> Profil</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#"><i class="bi bi-card-list me-1"></i> Layanan</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#"><i class="bi bi-newspaper me-1"></i> Berita</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#"><i class="bi bi-telephone me-1"></i> Kontak</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Carousel -->
    <div id="mainCarousel" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-indicators">
            <button type="button" data-bs-target="#mainCarousel" data-bs-slide-to="0" class="active"></button>
            <button type="button" data-bs-target="#mainCarousel" data-bs-slide-to="1"></button>
            <button type="button" data-bs-target="#mainCarousel" data-bs-slide-to="2"></button>
        </div>
        <div class="carousel-inner">
            <div class="carousel-item active" style="background-image: url('https://picsum.photos/seed/mosque1/1200/600.jpg');">
                <div class="carousel-caption d-none d-md-block">
                    <h2>Selamat Datang di Bimbingan Masyarakat Islam</h2>
                    <p class="lead">Melayani umat dengan profesional dan berkesinambungan</p>
                </div>
            </div>
            <div class="carousel-item" style="background-image: url('https://picsum.photos/seed/islamic2/1200/600.jpg');">
                <div class="carousel-caption d-none d-md-block">
                    <h2>Pembinaan Keagamaan yang Berkualitas</h2>
                    <p class="lead">Mewujudkan masyarakat Islami yang rahmatan lil 'alamin</p>
                </div>
            </div>
            <div class="carousel-item" style="background-image: url('https://picsum.photos/seed/community3/1200/600.jpg');">
                <div class="carousel-caption d-none d-md-block">
                    <h2>Pelayanan Terbaik untuk Masyarakat</h2>
                    <p class="lead">Mendukung pembangunan karakter bangsa yang religius</p>
                </div>
            </div>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#mainCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon"></span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#mainCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon"></span>
        </button>
    </div>

    <!-- Layanan Section -->
    <section class="py-5">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="fw-bold">Layanan Kami</h2>
                <p class="lead">Berbagai layanan bimbingan masyarakat Islam yang tersedia untuk Anda</p>
            </div>
            
            <div class="row g-4">
                <!-- Layanan 1 -->
                <div class="col-md-6 col-lg-4">
                    <div class="card service-card">
                        <div class="card-body text-center">
                            <div class="service-icon">
                                <i class="bi bi-book"></i>
                            </div>
                            <h5 class="card-title">Bimbingan Perkawinan</h5>
                            <p class="card-text">Pelayanan bimbingan pra nikah, konseling keluarga, dan pembinaan rumah tangga Islami</p>
                            <a href="#" class="btn btn-sm btn-outline-primary">Detail Layanan</a>
                        </div>
                    </div>
                </div>
                
                <!-- Layanan 2 -->
                <div class="col-md-6 col-lg-4">
                    <div class="card service-card">
                        <div class="card-body text-center">
                            <div class="service-icon">
                                <i class="bi bi-moon-stars"></i>
                            </div>
                            <h5 class="card-title">Bimbingan Ibadah</h5>
                            <p class="card-text">Pembinaan ibadah harian, shalat berjamaah, dan manajemen masjid/mushola</p>
                            <a href="#" class="btn btn-sm btn-outline-primary">Detail Layanan</a>
                        </div>
                    </div>
                </div>
                
                <!-- Layanan 3 -->
                <div class="col-md-6 col-lg-4">
                    <div class="card service-card">
                        <div class="card-body text-center">
                            <div class="service-icon">
                                <i class="bi bi-people"></i>
                            </div>
                            <h5 class="card-title">Bimbingan Zakat & Wakaf</h5>
                            <p class="card-text">Pembinaan pengelolaan zakat, wakaf, dan distribusi kepada mustahik</p>
                            <a href="#" class="btn btn-sm btn-outline-primary">Detail Layanan</a>
                        </div>
                    </div>
                </div>
                
                <!-- Layanan 4 -->
                <div class="col-md-6 col-lg-4">
                    <div class="card service-card">
                        <div class="card-body text-center">
                            <div class="service-icon">
                                <i class="bi bi-calendar-event"></i>
                            </div>
                            <h5 class="card-title">Bimbingan Hari Besar Islam</h5>
                            <p class="card-text">Koordinasi peringatan hari besar Islam, PHBI, dan kegiatan keagamaan</p>
                            <a href="#" class="btn btn-sm btn-outline-primary">Detail Layanan</a>
                        </div>
                    </div>
                </div>
                
                <!-- Layanan 5 -->
                <div class="col-md-6 col-lg-4">
                    <div class="card service-card">
                        <div class="card-body text-center">
                            <div class="service-icon">
                                <i class="bi bi-mortarboard"></i>
                            </div>
                            <h5 class="card-title">Bimbingan Keagamaan</h5>
                            <p class="card-text">Pembinaan keagamaan untuk remaja, pemuda, dan masyarakat umum</p>
                            <a href="#" class="btn btn-sm btn-outline-primary">Detail Layanan</a>
                        </div>
                    </div>
                </div>
                
                <!-- Layanan 6 -->
                <div class="col-md-6 col-lg-4">
                    <div class="card service-card">
                        <div class="card-body text-center">
                            <div class="service-icon">
                                <i class="bi bi-shield-check"></i>
                            </div>
                            <h5 class="card-title">Bimbingan Kerukunan</h5>
                            <p class="card-text">Pembinaan kerukunan umat beragama dan penanganan konflik keagamaan</p>
                            <a href="#" class="btn btn-sm btn-outline-primary">Detail Layanan</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Sambutan Ketua Section -->
    <section class="welcome-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-4 text-center">
                    <img src="https://picsum.photos/seed/leader/300/300.jpg" alt="Ketua Bimbingan Masyarakat Islam" class="welcome-img img-fluid">
                </div>
                <div class="col-lg-8">
                    <div class="welcome-content">
                        <h2 class="welcome-title">Sambutan Ketua Bimbingan Masyarakat Islam</h2>
                        <p class="lead">Assalamu'alaikum warahmatullahi wabarakatuh</p>
                        <p>Salam sejahtera untuk kita semua. Puji syukur kehadirat Allah SWT atas segala rahmat dan karunia-Nya, sehingga kita dapat hadir dalam portal resmi Bimbingan Masyarakat Islam Kementerian Agama Kabupaten Kutai Kartanegara.</p>
                        <p>Sebagai bagian dari upaya mewujudkan pelayanan prima kepada masyarakat, kami hadir dengan berbagai program dan layanan bimbingan keagamaan yang komprehensif. Kami berkomitmen untuk menjadi mitra strategis dalam pembinaan kehidupan beragama yang moderat, toleran, dan rahmatan lil 'alamin.</p>
                        <p>Melalui website ini, kami berharap dapat memberikan informasi yang akurat, transparan, dan mudah diakses oleh seluruh lapisan masyarakat. Mari bersama kita wujudkan masyarakat Kutai Kartanegara yang religius, harmonis, dan berkualitas.</p>
                        <p class="mt-4">
                            <strong>Wassalamu'alaikum warahmatullahi wabarakatuh</strong><br>
                            <strong>Drs. H. Ahmad Fauzi, M.Ag</strong><br>
                            <em>Kepala Seksi Bimbingan Masyarakat Islam</em>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5>Kantor Bimbingan Masyarakat Islam</h5>
                    <p>Kementerian Agama Kabupaten Kutai Kartanegara<br>
                    Jl. APT. Pranoto No. 12, Tenggarong<br>
                    Kutai Kartanegara, Kalimantan Timur 75511</p>
                    <p><i class="bi bi-telephone me-2"></i> (0541) 123456</p>
                    <p><i class="bi bi-envelope me-2"></i> bimmas@kemenagkukar.go.id</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <h5>Link Terkait</h5>
                    <ul class="list-unstyled">
                        <li><a href="#" class="text-white">Kemenag RI</a></li>
                        <li><a href="#" class="text-white">Kemenag Prov. Kaltim</a></li>
                        <li><a href="#" class="text-white">Pemkab Kutai Kartanegara</a></li>
                        <li><a href="#" class="text-white">LPMA Kaltim</a></li>
                    </ul>
                </div>
            </div>
            <hr class="bg-white">
            <div class="text-center">
                <p class="mb-0">&copy; 2023 Bimbingan Masyarakat Islam - Kemenag Kukar. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</script>
</body>
</html>