<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Bimas Islam - Kemenag Kutai Kartanegara')</title>
    <link rel="icon" href="{{ asset('voler/assets/images/logo-kemenag.png') }}">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">

    <!-- Font Awesome (for backend compatibility) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        html {
            scroll-behavior: smooth;
            scroll-padding-top: 80px;
            /* Adjust based on navbar height */
        }

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

        .arabesque-bg {
            background: linear-gradient(135deg, rgba(42, 157, 143, 0.15), rgba(233, 196, 106, 0.15), #ffffff);
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

        .text-primary-custom {
            color: var(--primary) !important;
        }

        .text-secondary-custom {
            color: var(--secondary) !important;
        }

        .bg-primary-custom {
            background-color: var(--primary) !important;
        }

        .bg-secondary-custom {
            background-color: var(--secondary) !important;
        }

        .border-accent {
            border-color: var(--accent) !important;
        }
    </style>

    @stack('styles')
</head>

<body>

    <!-- Navbar -->
    @include('frontend.sections.navbar')

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    @include('frontend.sections.footer')

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


    @stack('scripts')

</body>

</html>
