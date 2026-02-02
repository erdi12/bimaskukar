<!DOCTYPE html>
<html lang="id" data-bs-theme="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dashboard Admin - SIBERKAT | Sistem Informasi Bimas Islam Kutai Kartanegara Berbasis Digital</title>
    <link rel="icon" href="{{ asset('voler/assets/images/logo-kemenag.png') }}">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome untuk ikon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Google Fonts -->
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Amiri:wght@400;700&display=swap"
        rel="stylesheet">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('voler/assets/css/simtak.css') }}?v={{ time() }}">

    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />

    <!-- DataTables CSS -->
    <link href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css" rel="stylesheet">

    <script>
        // Init Theme
        (function() {
            const storedTheme = localStorage.getItem('theme');
            if (storedTheme) {
                document.documentElement.setAttribute('data-bs-theme', storedTheme);
            }
        })();
    </script>

    <!-- Flatpickr CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/themes/material_green.css">
    <style>
        .flatpickr-wrapper {
            width: 100%;
        }
    </style>

    @stack('styles')
</head>

<body>
    @include('include.sidebarv2')
    <!-- Main Content -->
    <div class="main-content" id="mainContent">
        @include('include.headerv2')
        <!-- Content -->
        @yield('content')
    </div>

    @include('include.footerv2')

    <!-- jQuery (required for DataTables) -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- SweetAlert2 -->
    <script src="https://cdns.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Lottie Player -->
    <script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>

    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        // Theme Toggle Logic
        document.addEventListener('DOMContentLoaded', function() {
            const themeToggle = document.getElementById('themeToggle');
            const themeIcon = document.getElementById('themeIcon');
            const html = document.documentElement;

            // Initialize icon state
            if (html.getAttribute('data-bs-theme') === 'dark') {
                if (themeIcon) {
                    themeIcon.classList.remove('fa-moon');
                    themeIcon.classList.add('fa-sun');
                    themeIcon.style.color = '#ffc107';
                }
            }

            if (themeToggle) {
                themeToggle.addEventListener('click', function() {
                    const currentTheme = html.getAttribute('data-bs-theme');
                    const newTheme = currentTheme === 'dark' ? 'light' : 'dark';

                    html.setAttribute('data-bs-theme', newTheme);
                    localStorage.setItem('theme', newTheme);

                    // Update Icon
                    if (themeIcon) {
                        if (newTheme === 'dark') {
                            themeIcon.classList.remove('fa-moon');
                            themeIcon.classList.add('fa-sun');
                            themeIcon.style.color = '#ffc107';
                        } else {
                            themeIcon.classList.remove('fa-sun');
                            themeIcon.classList.add('fa-moon');
                            themeIcon.style.color = '#607080';
                        }
                    }
                });
            }
        });

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
        const attendanceChartEl = document.getElementById('attendanceChart');
        if (attendanceChartEl) {
            const attendanceCtx = attendanceChartEl.getContext('2d');
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
        }

        // Distribution Chart
        const distributionChartEl = document.getElementById('distributionChart');
        if (distributionChartEl) {
            const distributionCtx = distributionChartEl.getContext('2d');
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
        }

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
    <!-- SweetAlert2 Directive -->
    @include('sweetalert::alert')

    <!-- Flatpickr JS -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://npmcdn.com/flatpickr/dist/l10n/id.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            flatpickr(".datepicker", {
                dateFormat: "Y-m-d",
                altInput: true,
                altFormat: "j F Y",
                locale: "id",
                allowInput: true,
                theme: "material_green",
                disableMobile: true,
                onChange: function(selectedDates, dateStr, instance) {
                    // Trigger standard change event for other scripts (like auto-fill)
                    instance.element.dispatchEvent(new Event('change', {
                        bubbles: true
                    }));
                }
            });
        });
    </script>

    @stack('scripts')
</body>

</html>
