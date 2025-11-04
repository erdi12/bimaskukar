<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Bimas Islam - Kemenag Kutai Kartanegara</title>

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

  <style>
    body {
      font-family: 'Poppins', sans-serif;
    }

    :root {
      --primary: #2A9D8F;
      --secondary: #264653;
      --accent: #E9C46A;
    }

    .arabic {
      font-family: 'Amiri', serif;
    }

    /* .arabesque-bg {
        background: linear-gradient(135deg, #ffffff 0%, var(--primary) 100%);
    } */

    .arabesque-bg {
  background: linear-gradient(135deg, rgba(42,157,143,0.15), rgba(233,196,106,0.15), #ffffff);
}
.card {
  transition: all 0.3s ease;
  cursor: pointer;
}

.card:hover {
  transform: translateY(-8px);
  box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
}

.card:hover .display-5 {
  transform: scale(1.2);
  transition: transform 0.3s ease;
}

.card:hover h5 {
  color: var(--primary);
  transition: color 0.3s ease;
}

    .text-primary-custom { color: var(--primary) !important; }
    .text-secondary-custom { color: var(--secondary) !important; }
    .bg-primary-custom { background-color: var(--primary) !important; }
    .bg-secondary-custom { background-color: var(--secondary) !important; }
    .border-accent { border-color: var(--accent) !important; }
  </style>
</head>

<body>

  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg bg-white shadow-sm fixed-top">
    <div class="container">
      <a class="navbar-brand d-flex align-items-center" href="#">
        <img src="{{ asset('voler/assets/images/logo-kemenag.png') }}" alt="Logo Kemenag" height="48">
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
        <ul class="navbar-nav">
          <li class="nav-item"><a class="nav-link text-primary-custom" href="#beranda">Beranda</a></li>
          <li class="nav-item"><a class="nav-link text-primary-custom" href="#profil">Profil</a></li>
          <li class="nav-item"><a class="nav-link text-primary-custom" href="#layanan">Layanan Bimas Islam</a></li>
          <li class="nav-item"><a class="nav-link text-primary-custom" href="#berita">Berita & Kegiatan</a></li>
          <li class="nav-item"><a class="nav-link text-primary-custom" href="#kontak">Kontak</a></li>
        </ul>
      </div>
    </div>
  </nav>

  <!-- Hero Section -->
  <section id="beranda" class="min-vh-100 d-flex align-items-center arabesque-bg bg-light" style="padding-top: 100px;">
    <div class="container text-center py-5">
      <h1 class="arabic text-secondary-custom mb-3 fs-1">السَّلاَمُ عَلَيْكُمْ وَرَحْمَةُ اللهِ وَبَرَكَاتُهُ</h1>
      <p class="fs-4 text-primary-custom mb-3">Selamat datang di Website Bimbingan Masyarakat Islam</p>
      <h2 class="fs-3 text-secondary-custom fw-semibold mb-4">Kementerian Agama Kabupaten Kutai Kartanegara</h2>
      <a href="#layanan" class="btn btn-lg text-white shadow-sm" style="background-color: var(--primary); border-radius: 10px;">
        Lihat Layanan Kami
      </a>
    </div>
  </section>

  <!-- Layanan Section -->
  <section id="layanan" class="py-5 bg-white">
    <div class="container">
      <h2 class="text-center text-secondary-custom fw-semibold mb-5 fs-2">Layanan Bimas Islam</h2>

      <div class="row g-4">
        <div class="col-md-4">
          <div class="card border-0 shadow-sm h-100 text-center p-4">
            <div class="display-5 text-primary-custom mb-3">🕌</div>
            <h5 class="fw-semibold text-secondary-custom">Penyuluhan Agama Islam</h5>
            <p class="text-muted">Memberikan bimbingan dan penyuluhan agama Islam kepada masyarakat.</p>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card border-0 shadow-sm h-100 text-center p-4">
            <div class="display-5 text-primary-custom mb-3">🕋</div>
            <h5 class="fw-semibold text-secondary-custom">Pemberdayaan Masjid dan Mushalla</h5>
            <p class="text-muted">Pembinaan dan pengembangan sarana ibadah umat Islam.</p>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card border-0 shadow-sm h-100 text-center p-4">
            <div class="display-5 text-primary-custom mb-3">🤲</div>
            <h5 class="fw-semibold text-secondary-custom">Penyelenggaraan Haji dan Umrah</h5>
            <p class="text-muted">Pembinaan dan pelayanan jamaah haji dan umrah.</p>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card border-0 shadow-sm h-100 text-center p-4">
            <div class="display-5 text-primary-custom mb-3">💖</div>
            <h5 class="fw-semibold text-secondary-custom">Pemberdayaan Zakat dan Wakaf</h5>
            <p class="text-muted">Pengelolaan dan pemberdayaan zakat dan wakaf.</p>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card border-0 shadow-sm h-100 text-center p-4">
            <div class="display-5 text-primary-custom mb-3">🏠</div>
            <h5 class="fw-semibold text-secondary-custom">Pembinaan Keluarga Sakinah</h5>
            <p class="text-muted">Bimbingan dan pembinaan keluarga Muslim menuju keluarga sakinah.</p>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card border-0 shadow-sm h-100 text-center p-4">
            <div class="display-5 text-primary-custom mb-3">🤝</div>
            <h5 class="fw-semibold text-secondary-custom">Kerukunan Umat Beragama</h5>
            <p class="text-muted">Membangun dan menjaga kerukunan antar umat beragama.</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Sambutan Section -->
  <section class="py-5" style="background-color: rgba(42, 157, 143, 0.05);">
    <div class="container">
      <div class="row align-items-center gy-4">
        <div class="col-md-4">
          <img src="{{ asset('voler/assets/images/kepala_seksi.png') }}" class="img-fluid rounded shadow" alt="Kepala Seksi Bimas Islam">
        </div>
        <div class="col-md-8">
          <h2 class="text-secondary-custom fw-semibold mb-3">Sambutan Kepala Seksi</h2>
          <p class="text-muted mb-4">
            Puji syukur ke hadirat Allah SWT atas tersedianya media informasi ini sebagai sarana komunikasi, publikasi, dan transparansi layanan Bimbingan Masyarakat Islam di lingkungan Kementerian Agama Kabupaten Kutai Kartanegara.
          </p>
          <div class="border-start border-4 ps-3" style="border-color: var(--accent) !important;">
            <p class="text-primary-custom fw-semibold mb-0">Nama Kepala Seksi</p>
            <small class="text-muted">Kepala Seksi Bimas Islam</small>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Footer -->
  <footer class="bg-secondary-custom text-white py-5">
    <div class="container">
      <div class="row g-4">
        <div class="col-md-4">
          <img src="{{ asset('voler/assets/images/logo-kemenag.png') }}" alt="Logo Kemenag" height="60" class="mb-3">
          <p class="text-light opacity-75">
            Melayani dengan sepenuh hati untuk mewujudkan masyarakat yang beragama dan berakhlak mulia.
          </p>
        </div>
        <div class="col-md-4">
          <h5 class="fw-semibold mb-3">Kontak Kami</h5>
          <ul class="list-unstyled text-light opacity-75">
            <li><i class="bi bi-geo-alt-fill text-warning me-2"></i>Jl. Muso bin Salim No. 22, Tenggarong</li>
            <li><i class="bi bi-telephone-fill text-warning me-2"></i>(0541) 123456</li>
            <li><i class="bi bi-envelope-fill text-warning me-2"></i>bimas.kukar@kemenag.go.id</li>
          </ul>
        </div>
        <div class="col-md-4">
          <h5 class="fw-semibold mb-3">Media Sosial</h5>
          <div class="d-flex gap-3">
            <a href="#" class="text-white fs-4"><i class="bi bi-facebook"></i></a>
            <a href="#" class="text-white fs-4"><i class="bi bi-twitter"></i></a>
            <a href="#" class="text-white fs-4"><i class="bi bi-instagram"></i></a>
            <a href="#" class="text-white fs-4"><i class="bi bi-youtube"></i></a>
          </div>
        </div>
      </div>
      <div class="border-top border-secondary mt-4 pt-3 text-center small text-light opacity-75">
        © 2025 Kementerian Agama Kabupaten Kutai Kartanegara. Semua Hak Dilindungi.
      </div>
    </div>
  </footer>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

  <!-- Bootstrap Icons -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

</body>
</html>
