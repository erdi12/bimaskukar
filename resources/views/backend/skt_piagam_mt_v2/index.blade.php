@extends('layout.appv2')
@section('title', 'Surat Keterangan Terdaftar Majelis Ta\'lim')
@section('sub-layanan', 'active')
@section('layanan', 'active')
@section('skt-mt', 'active')

@push('css')
    <style>
        .table td {
            white-space: nowrap;
            max-width: 200px;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .sticky-column {
            position: sticky;
            left: 0;
            background-color: #fff;
            z-index: 1;
        }
    </style>
@endpush

@section('content')
    <div class="content">
        <div class="page-title">
            <h3>Surat Keterangan Terdaftar</h3>
            <p>Manajemen data Majelis Ta'lim</p>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row">
            <div class="col-md-12">
                <div class="card card-modern">
                    <div class="card-header">
                        <h4 class="section-title-modern">Data Majelis Ta'lim</h4>
                    </div>
                    <div class="card-body">
                        {{-- Tombol Aksi --}}
                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="d-flex flex-column flex-md-row justify-content-end gap-2">
                                    @if (auth()->user()->hasRole('Admin') || auth()->user()->hasRole('Editor') || auth()->user()->hasRole('Operator'))
                                        <button type="button" class="btn btn-outline-success rounded-pill w-100 w-md-auto"
                                            data-bs-toggle="modal" data-bs-target="#importExcelModal">
                                            <i class="fas fa-file-excel me-2"></i>Import Excel
                                        </button>
                                        <a href="{{ route('skt_piagam_mt.export') }}"
                                            class="btn btn-outline-info rounded-pill w-100 w-md-auto">
                                            <i class="fas fa-download me-2"></i>Export Excel
                                        </a>
                                        <a href="{{ route('skt_piagam_mt_v2.create') }}"
                                            class="btn btn-primary rounded-pill w-100 w-md-auto">
                                            <i class="fas fa-plus me-2"></i>Tambah Data
                                        </a>
                                    @endif
                                    <a href="{{ route('skt_piagam_mt_v2.rekap') }}"
                                        class="btn btn-outline-secondary rounded-pill w-100 w-md-auto">
                                        <i class="fas fa-chart-bar me-2"></i>Rekapan Data
                                    </a>
                                    @if (auth()->user()->hasRole('Admin'))
                                        <a href="{{ route('skt_piagam_mt_v2.trash') }}"
                                            class="btn btn-outline-danger rounded-pill w-100 w-md-auto">
                                            <i class="fas fa-trash me-2"></i>Sampah
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{-- Filter Data --}}
                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="card bg-light border-0">
                                    <div class="card-body p-3">
                                        <h6 class="fw-bold mb-3"><i class="fas fa-filter me-1"></i> Filter Data</h6>
                                        <form action="{{ route('skt_piagam_mt_v2.index') }}" method="GET">
                                            <div class="row g-2">
                                                {{-- Filter Kecamatan --}}
                                                <div class="col-12 col-md-6">
                                                    <select class="form-select w-100" id="kecamatan_filter"
                                                        name="kecamatan_id" onchange="getKelurahan()">
                                                        <option value="">Semua Kecamatan</option>
                                                        @foreach ($kecamatans as $kecamatan)
                                                            <option value="{{ $kecamatan->id }}"
                                                                {{ request('kecamatan_id') == $kecamatan->id ? 'selected' : '' }}>
                                                                {{ ucwords($kecamatan->kecamatan) }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                {{-- Filter Kelurahan --}}
                                                <div class="col-12 col-md-6">
                                                    <select class="form-select w-100" id="kelurahan_filter"
                                                        name="kelurahan_id">
                                                        <option value="">Semua Kelurahan</option>
                                                        @foreach ($kelurahans as $kelurahan)
                                                            <option value="{{ $kelurahan->id }}"
                                                                {{ request('kelurahan_id') == $kelurahan->id ? 'selected' : '' }}>
                                                                {{ $kelurahan->nama_kelurahan }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                {{-- Filter Search --}}
                                                <div class="col-12 col-md-12">
                                                    <input type="text" class="form-control" id="customSearch"
                                                        placeholder="Cari data majelis ta'lim...">
                                                </div>

                                                {{-- Tombol Filter dan Reset --}}
                                                <div class="col-12 col-md-12 d-flex gap-2">
                                                    <button type="submit" class="btn btn-primary rounded-pill w-100">
                                                        <i class="fas fa-filter me-1"></i> Terapkan
                                                    </button>
                                                    <a href="{{ route('skt_piagam_mt_v2.index') }}"
                                                        class="btn btn-outline-secondary rounded-pill w-100">
                                                        <i class="fas fa-redo me-1"></i> Reset
                                                    </a>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Tabel Data --}}
                        <div>
                            <table class="table table-modern table-bordered table-hover text-center w-100" id="dataTable">
                                <thead class="table-light">
                                    <tr>
                                        <th>No</th>
                                        <th>Nomor Statistik MT</th>
                                        <th>Nama Majelis Ta'lim</th>
                                        <th>Alamat</th>
                                        <th>Status</th>
                                        <th>Ketua</th>
                                        <th>No. HP</th>
                                        <th>Mendaftar</th>
                                        <th>Daftar Ulang</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {{-- Data akan dimuat via AJAX DataTables --}}
                                </tbody>
                            </table>
                        </div>

                        {{-- Pagination --}}
                        {{-- Pagination otomatis dari DataTables --}}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Import Excel -->
    <div class="modal fade" id="importExcelModal" tabindex="-1" aria-labelledby="importExcelModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="importExcelModalLabel">Import Data dari Excel</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('skt_piagam_mt.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="excel_file" class="form-label">Pilih File Excel</label>
                            <input type="file" class="form-control" id="excel_file" name="excel_file"
                                accept=".xlsx, .xls, .csv" required>
                            <div class="form-text">Format yang didukung: .xlsx, .xls, .csv</div>
                        </div>
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-1"></i> Pastikan format Excel sesuai dengan template yang
                            ditentukan.
                            <a href="{{ route('skt_piagam_mt.template') }}" class="alert-link">Download template</a>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Import</button>
                    </div>
                </form>
            </div>
        </div>
    </div>



    @push('scripts')
        <!-- DataTables CSS & JS -->
        <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
        <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

        <script>
            // Pastikan SweetAlert2 tersedia
            document.addEventListener('DOMContentLoaded', function() {
                if (typeof Swal === 'undefined') {
                    console.error('SweetAlert2 tidak dimuat dengan benar!');
                } else {
                    console.log('SweetAlert2 berhasil dimuat');
                }
            });

            // Inisialisasi DataTables
            $(document).ready(function() {
                var table = $('#dataTable').DataTable({
                    processing: true,
                    serverSide: true,
                    // Custom DOM to handle responsiveness and placement
                    // 't' inside 'table-responsive' div
                    // 'i' (info) and 'p' (pagination) in a separate row below
                    dom: '<"table-responsive"t><"row mt-3"<"col-md-5"i><"col-md-7"p>>',
                    ajax: {
                        url: "{{ route('skt_piagam_mt_v2.index') }}",
                        data: function(d) {
                            d.kecamatan_id = $('#kecamatan_filter').val();
                            d.kelurahan_id = $('#kelurahan_filter').val();
                        }
                    },
                    columns: [{
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex',
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'nomor_statistik',
                            name: 'nomor_statistik'
                        },
                        {
                            data: 'nama_majelis',
                            name: 'nama_majelis'
                        },
                        {
                            data: 'alamat_lengkap',
                            name: 'alamat_lengkap',
                            orderable: false
                        },
                        {
                            data: 'status_badge',
                            name: 'status',
                            orderable: false
                        },
                        {
                            data: 'ketua',
                            name: 'ketua'
                        },
                        {
                            data: 'no_hp',
                            name: 'no_hp'
                        },
                        {
                            data: 'mendaftar',
                            name: 'mendaftar'
                        },
                        {
                            data: 'mendaftar_ulang',
                            name: 'mendaftar_ulang'
                        },
                        {
                            data: 'action',
                            name: 'action',
                            orderable: false,
                            searchable: false
                        }
                    ],
                    order: [
                        [7, 'desc']
                    ], // Sort by mendaftar descending
                    language: {
                        processing: '<div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div>',
                        search: "Cari:",
                        lengthMenu: "Tampilkan _MENU_ data per halaman",
                        info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                        infoEmpty: "Menampilkan 0 sampai 0 dari 0 data",
                        infoFiltered: "(disaring dari _MAX_ total data)",
                        loadingRecords: "Memuat...",
                        zeroRecords: "Tidak ada data yang ditemukan",
                        emptyTable: "Tidak ada data",
                        paginate: {
                            first: "Pertama",
                            last: "Terakhir",
                            next: "Selanjutnya",
                            previous: "Sebelumnya"
                        }
                    },
                    pageLength: 25,
                    lengthMenu: [
                        [10, 25, 50, 100, -1],
                        [10, 25, 50, 100, "Semua"]
                    ]
                });

                // Custom Search Event
                $('#customSearch').on('keyup', function() {
                    table.search(this.value).draw();
                });

                // Scroll ke atas saat ganti halaman (pagination)
                table.on('page.dt', function() {
                    $('html, body').animate({
                        scrollTop: $(".card-modern").offset().top - 20
                    }, 500);
                });

                // Reload table when filter changes
                $('#kecamatan_filter, #kelurahan_filter').change(function() {
                    table.ajax.reload();
                });
            });

            // Fungsi untuk mendapatkan kelurahan berdasarkan kecamatan
            function getKelurahan() {
                const kecamatanId = document.getElementById('kecamatan_filter').value;
                const kelurahanSelect = document.getElementById('kelurahan_filter');

                // Reset dropdown kelurahan
                kelurahanSelect.innerHTML = '<option value="">Semua Kelurahan</option>';

                if (kecamatanId) {
                    fetch(`/api/kelurahans/${kecamatanId}`)
                        .then(response => response.json())
                        .then(data => {
                            data.forEach(kelurahan => {
                                const option = document.createElement('option');
                                option.value = kelurahan.id;
                                option.textContent = kelurahan.nama_kelurahan;
                                kelurahanSelect.appendChild(option);
                            });
                        })
                        .catch(error => console.error('Error:', error));
                }
            }

            // Fungsi konfirmasi hapus
            function confirmDelete(id) {
                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: "Data yang dihapus akan masuk ke trash dan dapat dipulihkan kembali!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Submit form berdasarkan ID yang sudah dirender di tabel
                        document.getElementById('delete-form-' + id).submit();
                    }
                });
            }
        </script>
    @endpush
    </div>
    </div>
    </div>
    </div>
    </div>

    <!-- Modal Import Excel -->
    <div class="modal fade" id="importExcelModal" tabindex="-1" aria-labelledby="importExcelModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="importExcelModalLabel">Import Data dari Excel</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('skt_piagam_mt.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="excel_file" class="form-label">Pilih File Excel</label>
                            <input type="file" class="form-control" id="excel_file" name="excel_file"
                                accept=".xlsx, .xls, .csv" required>
                            <div class="form-text">Format yang didukung: .xlsx, .xls, .csv</div>
                        </div>
                        <div class="alert alert-info">
                            <i data-feather="info" class="me-1"></i> Pastikan format Excel sesuai dengan template yang
                            ditentukan.
                            <a href="{{ route('skt_piagam_mt.template') }}" class="alert-link">Download template</a>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Import</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


@endsection

@push('scripts')
    <script>
        // Pastikan SweetAlert2 tersedia
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof Swal === 'undefined') {
                console.error('SweetAlert2 tidak dimuat dengan benar!');
            } else {
                console.log('SweetAlert2 berhasil dimuat');
            }
        });

        // Inisialisasi Select2 untuk dropdown filter
        $(document).ready(function() {
            $('#kecamatan_filter').select2({
                theme: 'bootstrap-5',
                placeholder: "Pilih Kecamatan",
                allowClear: true
            });

            $('#kelurahan_filter').select2({
                theme: 'bootstrap-5',
                placeholder: "Pilih Kelurahan",
                allowClear: true
            });
        });

        // Fungsi untuk mendapatkan kelurahan berdasarkan kecamatan
        function getKelurahan() {
            const kecamatanId = document.getElementById('kecamatan_filter').value;
            const kelurahanSelect = document.getElementById('kelurahan_filter');

            // Reset dropdown kelurahan
            kelurahanSelect.innerHTML = '<option value="">Semua Kelurahan</option>';

            if (kecamatanId) {
                fetch("{{ url('api/kelurahans') }}/" + kecamatanId)
                    .then(response => response.json())
                    .then(data => {
                        data.forEach(kelurahan => {
                            const option = document.createElement('option');
                            option.value = kelurahan.id;
                            option.textContent = kelurahan.nama_kelurahan;
                            kelurahanSelect.appendChild(option);
                        });

                        // Reinisialisasi Select2 setelah mengupdate opsi
                        $('#kelurahan_filter').trigger('change');
                    })
                    .catch(error => console.error('Error:', error));
            }
        }

        // Fungsi konfirmasi hapus
        function confirmDelete(id) {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Data yang dihapus akan masuk ke trash dan dapat dipulihkan kembali!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById(`delete-form-${id}`).submit();
                }
            });
        }

        // Fungsi konfirmasi hapus file SKT
        function confirmDeleteSkt(id) {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "File SKT akan dihapus secara permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById(`delete-skt-form-${id}`).submit();
                }
            });
        }

        // Fungsi konfirmasi hapus file Piagam
        function confirmDeletePiagam(id) {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "File Piagam akan dihapus secara permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById(`delete-piagam-form-${id}`).submit();
                }
            });
        }

        // Fungsi konfirmasi hapus file Berkas
        function confirmDeleteBerkas(id) {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "File Berkas akan dihapus secara permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById(`delete-berkas-form-${id}`).submit();
                }
            });
        }

        // Fungsi cetak SKT dengan tipe wilayah
        // function cetakSkt(id) {
        //     const tipeKelurahan = document.getElementById(`tipeKelurahan${id}`).checked;
        //     const tipeDesa = document.getElementById(`tipeDesa${id}`).checked;
        //     let tipeWilayah = 'kelurahan';

        //     if (tipeDesa) {
        //         tipeWilayah = 'desa';
        //     }

        //     // Simpan URL yang akan dibuka di tab baru
        //     const url = `/skt_piagam_mt/${id}/cetak-skt?tipe=${tipeWilayah}`;

        //     // Dapatkan referensi modal
        //     const modalElement = document.getElementById(`wilayahModal${id}`);

        //     // Tambahkan event listener untuk mendeteksi saat modal selesai ditutup
        //     $(modalElement).on('hidden.bs.modal', function (e) {
        //         // Buka tab baru setelah modal benar-benar tertutup
        //         window.open(url, '_blank');
        //         // Hapus event listener setelah digunakan
        //         $(modalElement).off('hidden.bs.modal');
        //     });

        //     // Tutup modal menggunakan jQuery (lebih konsisten)
        //     $(modalElement).modal('hide');

        //     // Fallback jika event tidak terpicu
        //     setTimeout(() => {
        //         if ($(modalElement).hasClass('show')) {
        //             $(modalElement).modal('hide');
        //             window.open(url, '_blank');
        //         }
        //     }, 300);

        //     // Mencegah event default
        //     return false;
        // }

        // function cetakSkt(id) {
        //     // Dapatkan referensi elemen
        //     const modalElement = document.getElementById(`wilayahModal${id}`);
        //     const tipeDesa = document.getElementById(`tipeDesa${id}`);

        //     // Tentukan tipe wilayah
        //     const tipeWilayah = tipeDesa && tipeDesa.checked ? 'desa' : 'kelurahan';
        //     const url = `/skt_piagam_mt/${id}/cetak-skt?tipe=${tipeWilayah}`;

        //     let tabOpened = false;

        //     // Fungsi untuk membersihkan modal dan backdrop
        //     const cleanupModal = () => {
        //         // Hapus semua backdrop
        //         $('.modal-backdrop').remove();
        //         // Reset body
        //         $('body').removeClass('modal-open').css('padding-right', '');
        //         // Reset modal
        //         $(modalElement).removeClass('show').attr('style', '');
        //     };

        //     // Fungsi untuk membuka tab baru
        //     const openNewTab = () => {
        //         if (!tabOpened) {
        //             tabOpened = true;
        //             window.open(url, '_blank');
        //         }
        //     };

        //     // Event listener untuk modal tertutup
        //     $(modalElement).one('hidden.bs.modal', function() {
        //         cleanupModal();
        //         openNewTab();
        //     });

        //     // Tutup modal
        //     $(modalElement).modal('hide');

        //     // Fallback jika modal tidak menutup dengan benar
        //     setTimeout(() => {
        //         if ($(modalElement).hasClass('show') || $('.modal-backdrop').length) {
        //             cleanupModal();
        //             openNewTab();
        //         }
        //     }, 500);

        //     return false;
        // }

        // function cetakSkt(id) {
        //     const modalElement = document.getElementById(`wilayahModal${id}`);
        //     const tipeDesa = document.getElementById(`tipeDesa${id}`);
        //     const tipeWilayah = tipeDesa && tipeDesa.checked ? 'desa' : 'kelurahan';
        //     const url = `/skt_piagam_mt/${id}/cetak-skt?tipe=${tipeWilayah}`;

        //     let tabOpened = false;

        //     // Fungsi membersihkan manual khusus Bootstrap 5.0.0
        //     const cleanupModal = () => {
        //         document.body.classList.remove('modal-open');
        //         document.body.style.overflow = 'auto';
        //         document.documentElement.style.overflow = 'auto';
        //         document.body.style.paddingRight = '';
        //         // Hapus semua backdrop sisa
        //         document.querySelectorAll('.modal-backdrop').forEach(el => el.remove());
        //         // Reset properti modal agar bisa dibuka ulang
        //         modalElement.classList.remove('show');
        //         modalElement.style.display = '';
        //         modalElement.setAttribute('aria-hidden', 'true');
        //     };

        //     const openNewTab = () => {
        //         if (!tabOpened) {
        //             tabOpened = true;
        //             window.open(url, '_blank');
        //         }
        //     };

        //     // Tutup modal manual dulu
        //     const modalInstance = bootstrap.Modal.getInstance(modalElement) 
        //         || new bootstrap.Modal(modalElement);
        //     modalInstance.hide();

        //     // Tunggu sedikit biar animasi selesai, baru bersihkan & buka tab
        //     setTimeout(() => {
        //         cleanupModal();
        //         openNewTab();
        //     }, 400);
        // }
    </script>
@endpush
