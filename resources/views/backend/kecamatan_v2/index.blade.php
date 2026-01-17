@extends('layout.appv2')
@section('title', 'Data Kecamatan V2')
@section('data-master', 'active')
@section('kecamatan', 'active')

@section('content')
    <div class="content">
        <div class="page-title mb-4">
            <h3>Data Kecamatan</h3>
            <p class="text-muted">Kelola data kecamatan di Kabupaten Kutai Kartanegara</p>
        </div>

        <style>
            /* Fix Pagination Mobile Overflow */
            @media (max-width: 767.98px) {
                .dataTables_wrapper .dataTables_paginate .pagination {
                    flex-wrap: wrap;
                    justify-content: center !important;
                }

                .dataTables_wrapper .dataTables_info {
                    text-align: center !important;
                    margin-bottom: 10px;
                }

                .dataTables_wrapper .dataTables_length,
                .dataTables_wrapper .dataTables_filter {
                    text-align: center !important;
                    margin-bottom: 15px;
                }
            }
        </style>

        <div class="row">
            <div class="col-12">
                <div class="card card-modern">
                    <div class="card-header d-flex flex-column flex-md-row justify-content-between align-items-center gap-2">
                        <h5 class="section-title-modern mb-0 w-100 w-md-auto text-center text-md-start">Daftar Kecamatan</h5>
                        @if (auth()->user()->hasRole('Admin') || auth()->user()->hasRole('Operator'))
                            <button class="btn btn-primary rounded-pill w-100 w-md-auto" onclick="addKecamatan()">
                                <i class="fas fa-plus me-2"></i> Tambah Kecamatan
                            </button>
                        @endif
                    </div>
                    <div class="card-body">
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif
                        @if (session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif

                        <table class="table table-modern table-hover w-100" id="table-kecamatan">
                            <thead class="bg-light text-nowrap">
                                <tr>
                                    <th width="50">No</th>
                                    <th>Nama Kecamatan</th>
                                    <th width="150" class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Create/Edit -->
    <div class="modal fade" id="modalKecamatan" tabindex="-1" aria-hidden="true">
        <!-- ... same content ... -->
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">Tambah Kecamatan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="formKecamatan">
                    @csrf
                    <input type="hidden" id="kecamatan_id" name="id">
                    <div class="modal-body">
                        <div class="alert alert-danger d-none" id="error-alert"></div>
                        <div class="mb-3">
                            <label for="kecamatan" class="form-label">Nama Kecamatan <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control text-capitalize" id="kecamatan" name="kecamatan"
                                required placeholder="Contoh: Tenggarong">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary" id="btn-save">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Detail -->
    <div class="modal fade" id="modalDetailKecamatan" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detail Kecamatan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <table class="table table-bordered">
                        <tr>
                            <th width="40%">ID Kecamatan</th>
                            <td id="detail-id"></td>
                        </tr>
                        <tr>
                            <th>Nama Kecamatan</th>
                            <td id="detail-nama" class="text-capitalize"></td>
                        </tr>
                        <tr>
                            <th>Jumlah Kelurahan</th>
                            <td><span class="badge bg-primary rounded-pill" id="detail-kelurahan-count">0</span></td>
                        </tr>
                        <tr>
                            <th>Total Majelis Taklim</th>
                            <td><span class="badge bg-success rounded-pill" id="detail-mt-count">0</span></td>
                        </tr>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // Init DataTable
            var table = $('#table-kecamatan').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('kecamatan_v2.index') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false,
                        className: 'text-center'
                    },
                    {
                        data: 'kecamatan',
                        name: 'kecamatan',
                        className: 'text-capitalize'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        className: 'text-center'
                    },
                ],
                language: {
                    url: "//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json"
                },
                initComplete: function() {
                    $(this).wrap('<div class="table-responsive"></div>');
                }
            });

            // Scroll to top on pagination
            table.on('page.dt', function() {
                $('html, body').animate({
                    scrollTop: 0
                }, 'slow');
            });

            // Form Submit Handler
            $('#formKecamatan').on('submit', function(e) {
                e.preventDefault();
                var id = $('#kecamatan_id').val();
                var url = id ? "{{ route('kecamatan_v2.update', ':id') }}".replace(':id', id) :
                    "{{ route('kecamatan_v2.store') }}";
                var method = id ? 'PUT' : 'POST';

                // Tambahkan method spoofing jika PUT
                var formData = $(this).serialize();
                if (id) formData += '&_method=PUT';

                $.ajax({
                    url: url,
                    type: method,
                    data: formData,
                    beforeSend: function() {
                        $('#btn-save').prop('disabled', true).text('Menyimpan...');
                        $('#error-alert').addClass('d-none').text('');
                    },
                    success: function(res) {
                        $('#modalKecamatan').modal('hide');
                        table.ajax.reload();
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: res.success,
                            timer: 1500,
                            showConfirmButton: false
                        });
                    },
                    error: function(xhr) {
                        var errors = xhr.responseJSON.errors;
                        var errorMessage = '';
                        if (errors) {
                            $.each(errors, function(key, value) {
                                errorMessage += value[0] + '<br>';
                            });
                        } else {
                            errorMessage = 'Terjadi kesalahan pada server.';
                        }
                        $('#error-alert').html(errorMessage).removeClass('d-none');
                    },
                    complete: function() {
                        $('#btn-save').prop('disabled', false).text('Simpan');
                    }
                });
            });
        });

        function addKecamatan() {
            $('#modalTitle').text('Tambah Kecamatan');
            $('#formKecamatan')[0].reset();
            $('#kecamatan_id').val('');
            $('#error-alert').addClass('d-none');
            $('#modalKecamatan').modal('show');
        }

        function editKecamatan(id) {
            $.get("{{ url('appv2/kecamatan') }}/" + id + "/edit", function(data) {
                $('#modalTitle').text('Edit Kecamatan');
                $('#kecamatan_id').val(data.uuid);
                $('#kecamatan').val(data.kecamatan);
                $('#error-alert').addClass('d-none');
                $('#modalKecamatan').modal('show');
            });
        }

        function showKecamatan(id) {
            $.get("{{ url('appv2/kecamatan') }}/" + id, function(data) {
                $('#detail-id').text(data.uuid);
                $('#detail-nama').text(data.kecamatan);
                $('#detail-kelurahan-count').text(data.kelurahans_count);
                $('#detail-mt-count').text(data.sktpiagammts_count);
                $('#modalDetailKecamatan').modal('show');
            });
        }

        function deleteKecamatan(id) {
            Swal.fire({
                title: 'Yakin hapus data?',
                text: "Data yang dihapus tidak bisa dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Hapus!'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + id).submit();
                }
            });
        }
    </script>
@endpush
