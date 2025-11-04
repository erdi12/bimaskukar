<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Voler Admin Dashboard</title>

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ asset('voler/assets/images/logo-kemenag.png') }}" type="image/png">
    
    <!-- Core CSS -->
    <link rel="stylesheet" href="{{ asset('voler/assets/css/bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('voler/assets/css/app.css') }}">
    
    <!-- Vendor CSS -->
    <link rel="stylesheet" href="{{ asset('voler/assets/vendors/perfect-scrollbar/perfect-scrollbar.css') }}">
    <link rel="stylesheet" href="{{ asset('voler/assets/vendors/simple-datatables/style.css') }}">
    {{-- <link rel="stylesheet" href="https://cdn.datatables.net/2.3.4/css/dataTables.dataTables.min.css"> --}}
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('voler/assets/vendors/choices.js/choices.min.css') }}" />
    
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
    
    <!-- Chart CSS -->
    <link rel="stylesheet" href="{{ asset('voler/assets/vendors/chartjs/Chart.min.css') }}">
    
    <!-- Additional CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    
    <style>
        .modal-backdrop.show {
            opacity: 0.5 !important;
            z-index: 1040 !important;
        }
        .modal {
            z-index: 1050 !important;
        }
    </style>
</head>
<body>
    @include('include.sidebar')
    @include('include.header')
    @yield('content')
    <!-- Jika jQuery belum dimuat di layout utama, tambahkan ini -->
    @include('include.footer')
    
    <!-- Core JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{ asset('voler/assets/js/app.js') }}"></script>
    
    <!-- Vendor JS -->
    <script src="{{ asset('voler/assets/js/feather-icons/feather.min.js') }}"></script>
    <script src="{{ asset('voler/assets/vendors/perfect-scrollbar/perfect-scrollbar.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://kit.fontawesome.com/8039ce3eb6.js" crossorigin="anonymous"></script>
    <script src="{{ asset('voler/assets/vendors/choices.js/choices.min.js') }}"></script>
    
    <!-- Chart JS -->
    <script src="{{ asset('voler/assets/vendors/chartjs/Chart.min.js') }}"></script>
    <script src="{{ asset('voler/assets/vendors/apexcharts/apexcharts.min.js') }}"></script>
    
    <!-- Data Tables -->
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
    <script src="{{ asset('voler/assets/vendors/simple-datatables/simple-datatables.js') }}"></script>
    
    <!-- Custom JS -->
    <script src="{{ asset('voler/assets/js/vendors.js') }}"></script>
    <script src="{{ asset('voler/assets/js/main.js') }}"></script>
    <script src="{{ asset('voler/assets/js/pages/dashboard.js') }}"></script>
    
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @include('sweetalert::alert')
    
    @stack('scripts')
    
</body>
</html>