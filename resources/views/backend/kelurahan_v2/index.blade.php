@extends('layout.appv2')
@section('title', 'Data Kelurahan V2')
@section('data-master', 'active')
@section('kelurahan', 'active')

@section('content')
    <div class="content">
        <div class="page-title mb-4">
            <h3>Data Kelurahan</h3>
            <p class="text-muted">Kelola data kelurahan/desa di Kabupaten Kutai Kartanegara</p>
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
                        <h5 class="section-title-modern mb-0 w-100 w-md-auto text-center text-md-start">Daftar Kelurahan</h5>
                        @if (auth()->user()->hasRole('Admin') || auth()->user()->hasRole('Operator'))
                            <button class="btn btn-primary rounded-pill w-100 w-md-auto" onclick="addKelurahan()">
                                <i class="fas fa-plus me-2"></i> Tambah Kelurahan
                            </button>
                        @endif
                    </div>
                    <div class="card-body">
                        {{-- Filter --}}
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label class="form-label">Filter Kecamatan:</label>
                                <select id="filter_kecamatan" class="form-select">
                                    <option value="">Semua Kecamatan</option>
                                    @foreach ($kecamatans as $kec)
                                        <option value="{{ $kec->id }}">{{ ucwords($kec->kecamatan) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

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

                        <table class="table table-modern table-hover w-100" id="table-kelurahan">
                            <thead class="bg-light text-nowrap">
                                <tr>
                                    <th width="50">No</th>
                                    <th>Kecamatan</th>
                                    <th>Nama Kelurahan/Desa</th>
                                    <th>Jenis</th>
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
    <div class="modal fade" id="modalKelurahan" tabindex="-1" aria-hidden="true">
        <!-- ... same content ... -->
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">Tambah Kelurahan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="formKelurahan">
                    @csrf
                    <input type="hidden" id="kelurahan_id" name="id">
                    <div class="modal-body">
                        <div class="alert alert-danger d-none" id="error-alert"></div>

                        <div class="mb-3">
                            <label for="kecamatan_id" class="form-label">Kecamatan <span
                                    class="text-danger">*</span></label>
                            <select class="form-select" id="kecamatan_id" name="kecamatan_id" required>
                                <option value="">-- Pilih Kecamatan --</option>
                                @foreach ($kecamatans as $kec)
                                    <option value="{{ $kec->id }}">{{ ucwords($kec->kecamatan) }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="nama_kelurahan" class="form-label">Nama Kelurahan/Desa <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control text-capitalize" id="nama_kelurahan"
                                name="nama_kelurahan" required placeholder="Contoh: Panji">
                        </div>

                        <div class="mb-3">
                            <label for="jenis_kelurahan" class="form-label">Jenis <span class="text-danger">*</span></label>
                            <select class="form-select" id="jenis_kelurahan" name="jenis_kelurahan" required>
                                <option value="Kelurahan">Kelurahan</option>
                                <option value="Desa">Desa</option>
                            </select>
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
    <div class="modal fade" id="modalDetailKelurahan" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detail Kelurahan/Desa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <table class="table table-bordered">
                        <tr>
                            <th width="40%">ID</th>
                            <td id="detail-id"></td>
                        </tr>
                        <tr>
                            <th>Kecamatan</th>
                            <td id="detail-kecamatan" class="text-capitalize"></td>
                        </tr>
                        <tr>
                            <th>Nama Kelurahan/Desa</th>
                            <td id="detail-nama" class="text-capitalize"></td>
                        </tr>
                        <tr>
                            <th>Jenis</th>
                            <td><span class="badge bg-secondary" id="detail-jenis"></span></td>
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
            var table = $('#table-kelurahan').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('kelurahan_v2.index') }}",
                    data: function(d) {
                        d.kecamatan_id = $('#filter_kecamatan').val();
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false,
                        className: 'text-center'
                    },
                    {
                        data: 'nama_kecamatan',
                        name: 'nama_kecamatan',
                        className: 'text-capitalize'
                    },
                    {
                        data: 'nama_kelurahan',
                        name: 'nama_kelurahan',
                        className: 'text-capitalize'
                    },
                    {
                        data: 'jenis_kelurahan',
                        name: 'jenis_kelurahan'
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

            // ... filter & form submit ...
            $('#filter_kecamatan').change(function() {
                table.draw();
            });

            $('#formKelurahan').on('submit', function(e) {
                e.preventDefault();
                var id = $('#kelurahan_id').val();
                var url = id ? "{{ route('kelurahan_v2.update', ':id') }}".replace(':id', id) :
                    "{{ route('kelurahan_v2.store') }}";
                var method = id ? 'PUT' : 'POST';

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
                        $('#modalKelurahan').modal('hide');
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

        // ... global functions ...
        function addKelurahan() {
            $('#modalTitle').text('Tambah Kelurahan');
            $('#formKelurahan')[0].reset();
            $('#kelurahan_id').val('');
            $('#error-alert').addClass('d-none');
            $('#modalKelurahan').modal('show');
        }

        function editKelurahan(id) {
            $.get("{{ url('appv2/kelurahan') }}/" + id + "/edit", function(data) {
                $('#modalTitle').text('Edit Kelurahan');
                $('#kelurahan_id').val(data.uuid);
                $('#kecamatan_id').val(data.kecamatan_id);
                $('#nama_kelurahan').val(data.nama_kelurahan);
                $('#jenis_kelurahan').val(data.jenis_kelurahan);
                $('#error-alert').addClass('d-none');
                $('#modalKelurahan').modal('show');
            });
        }

        function showKelurahan(id) {
            $.get("{{ url('appv2/kelurahan') }}/" + id, function(data) {
                $('#detail-id').text(data.uuid);
                $('#detail-kecamatan').text(data.kecamatan ? data.kecamatan.kecamatan : '-');
                $('#detail-nama').text(data.nama_kelurahan);
                $('#detail-jenis').text(data.jenis_kelurahan);
                $('#detail-mt-count').text(data.sktpiagammts_count);
                $('#modalDetailKelurahan').modal('show');
            });
        }

        function deleteKelurahan(id) {
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
