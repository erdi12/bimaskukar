@extends('layout.appv2')
@section('title', 'Data Mushalla V2')
@section('sub-layanan', 'active open')
@section('skt-mt', 'show')
@section('mushalla', 'active')

@section('content')
    <div class="content">
        <div class="page-title mb-4">
            <h3>Data Mushalla</h3>
            <p class="text-muted">Kelola data Musholla di Wilayah Kutai Kartanegara</p>
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
                        <h5 class="section-title-modern mb-0 w-100 w-md-auto text-center text-md-start">Daftar Mushalla</h5>
                        @if (auth()->user()->hasRole('Admin') || auth()->user()->hasRole('Operator') || auth()->user()->hasRole('Editor'))
                            <div class="d-flex flex-column flex-md-row gap-2 w-100 w-md-auto justify-content-end">
                                <a href="{{ route('skt_mushalla.rekap') }}"
                                    class="btn btn-sm btn-warning rounded-pill w-100 w-md-auto text-white">
                                    <i class="fas fa-chart-pie me-1"></i> Rekap
                                </a>
                                <a href="{{ route('skt_mushalla.export') }}"
                                    class="btn btn-sm btn-success rounded-pill w-100 w-md-auto" target="_blank">
                                    <i class="fas fa-file-excel me-1"></i> Export
                                </a>
                                <button class="btn btn-sm btn-info rounded-pill w-100 w-md-auto text-white"
                                    data-bs-toggle="modal" data-bs-target="#modalImport">
                                    <i class="fas fa-file-import me-1"></i> Import
                                </button>
                                <button class="btn btn-sm btn-primary rounded-pill w-100 w-md-auto" onclick="addData()">
                                    <i class="fas fa-plus me-1"></i> Tambah
                                </button>
                            </div>
                        @endif
                    </div>
                    <div class="card-body">
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

                        <table class="table table-modern table-hover w-100" id="table-data">
                            <thead class="bg-light text-nowrap">
                                <tr>
                                    <th width="50">No</th>
                                    <th>ID Mushalla</th>
                                    <th>Nama Mushalla</th>
                                    <th>Lokasi (Kel/Kec)</th>
                                    <th>Tipologi</th>
                                    <th>Nama Marbot</th>
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
    <div class="modal fade" id="modalData" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">Tambah Mushalla</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="formData" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" id="data_id" name="id">
                    <div class="modal-body">
                        <div class="alert alert-danger d-none" id="error-alert"></div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="nama_mushalla" class="form-label">Nama Mushalla <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control text-capitalize" id="nama_mushalla"
                                    name="nama_mushalla" required placeholder="Contoh: Mushalla Al-Ikhlas">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="nomor_id_mushalla" class="form-label">Nomor ID Mushalla</label>
                                <input type="text" class="form-control" id="nomor_id_mushalla" name="nomor_id_mushalla"
                                    placeholder="Opsional">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="alamat_mushalla" class="form-label">Alamat Lengkap</label>
                            <textarea class="form-control" id="alamat_mushalla" name="alamat_mushalla" rows="2"
                                placeholder="Jalan, RT, RW..."></textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="kecamatan_id" class="form-label">Kecamatan <span
                                        class="text-danger">*</span></label>
                                <select class="form-select" id="kecamatan_id" name="kecamatan_id" required>
                                    <option value="">-- Pilih Kecamatan --</option>
                                    @foreach ($kecamatans as $kec)
                                        <option value="{{ $kec->id }}">{{ ucwords($kec->kecamatan) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="kelurahan_id" class="form-label">Kelurahan/Desa <span
                                        class="text-danger">*</span></label>
                                <select class="form-select" id="kelurahan_id" name="kelurahan_id" required disabled>
                                    <option value="">-- Pilih Kecamatan Dulu --</option>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="tipologi_mushalla_id" class="form-label">Tipologi <span
                                        class="text-danger">*</span></label>
                                <select class="form-select" id="tipologi_mushalla_id" name="tipologi_mushalla_id"
                                    required>
                                    <option value="">-- Pilih Tipologi --</option>
                                    @foreach ($tipologis as $t)
                                        <option value="{{ $t->id }}">{{ $t->nama_tipologi }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="file_barcode_mushalla" class="form-label">Upload QR Code / Foto
                                    (Opsional)</label>
                                <input type="file" class="form-control" id="file_barcode_mushalla"
                                    name="file_barcode_mushalla" accept="image/*">
                                <div class="form-text">Format: JPG, PNG, GIF. Maks: 2MB.</div>
                            </div>
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

    <!-- Modal Import -->
    <div class="modal fade" id="modalImport" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Import Data Excel</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('skt_mushalla.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="file_excel" class="form-label">Upload File Excel (.xlsx)</label>
                            <input type="file" class="form-control" id="file_excel" name="file_excel" required
                                accept=".xlsx, .xls">
                            <div class="form-text">
                                Pastikan format kolom sesuai dengan data export. <br>
                                <a href="{{ route('skt_mushalla.template') }}" class="text-decoration-none">
                                    <i class="fas fa-download"></i> Download Template Excel
                                </a>
                            </div>
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
        $(document).ready(function() {
            var table = $('#table-data').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('skt_mushalla.index') }}",
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
                        data: 'nomor_id_mushalla',
                        name: 'nomor_id_mushalla',
                        className: 'text-center'
                    },
                    {
                        data: 'nama_mushalla',
                        name: 'nama_mushalla',
                        className: 'text-capitalize fw-bold'
                    },
                    {
                        data: 'lokasi',
                        name: 'lokasi',
                        className: 'text-capitalize'
                    },
                    {
                        data: 'tipologi',
                        name: 'tipologi'
                    },
                    {
                        data: 'marbot',
                        name: 'marbot',
                        orderable: false,
                        searchable: false,
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

            $('#filter_kecamatan').change(function() {
                table.draw();
            });

            // Chained Dropdown: Kecamatan -> Kelurahan
            $('#kecamatan_id').change(function() {
                var kecId = $(this).val();
                var kelSelect = $('#kelurahan_id');
                kelSelect.empty().append('<option value="">Loading...</option>').prop('disabled', true);

                if (kecId) {
                    $.get("{{ route('get.kelurahan') }}", {
                        kecamatan_id: kecId
                    }, function(res) {
                        kelSelect.empty().append('<option value="">-- Pilih Kelurahan --</option>');
                        $.each(res, function(key, value) {
                            kelSelect.append('<option value="' + value.id + '">' + value
                                .nama_kelurahan + '</option>');
                        });
                        kelSelect.prop('disabled', false);
                    });
                } else {
                    kelSelect.empty().append('<option value="">-- Pilih Kecamatan Dulu --</option>');
                }
            });

            $('#formData').on('submit', function(e) {
                e.preventDefault();
                var id = $('#data_id').val();
                var url = id ? "{{ route('skt_mushalla.update', ':id') }}".replace(':id', id) :
                    "{{ route('skt_mushalla.store') }}";
                // var method = id ? 'PUT' : 'POST';

                var formData = new FormData(this);
                if (id) formData.append('_method', 'PUT');

                $.ajax({
                    url: url,
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    beforeSend: function() {
                        $('#btn-save').prop('disabled', true).text('Menyimpan...');
                        $('#error-alert').addClass('d-none').text('');
                    },
                    success: function(res) {
                        $('#modalData').modal('hide');
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
                        var errors = xhr.responseJSON && xhr.responseJSON.errors;
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

        function addData() {
            $('#modalTitle').text('Tambah Mushalla');
            $('#formData')[0].reset();
            $('#data_id').val('');
            $('#error-alert').addClass('d-none');
            $('#btn-save').prop('disabled', false).text('Simpan');

            // Reset chained dropdowns
            $('#kelurahan_id').empty().append('<option value="">-- Pilih Kecamatan Dulu --</option>').prop('disabled',
                true);
            $('#file_barcode_mushalla').val('');

            $('#modalData').modal('show');
        }

        function editData(id) {
            $.get("{{ url('appv2/skt-mushalla') }}/" + id + "/edit", function(data) {
                $('#modalTitle').text('Edit Mushalla');
                $('#data_id').val(data.uuid);
                $('#nama_mushalla').val(data.nama_mushalla);
                $('#nomor_id_mushalla').val(data.nomor_id_mushalla);
                $('#alamat_mushalla').val(data.alamat_mushalla);
                $('#kecamatan_id').val(data.kecamatan_id);
                $('#tipologi_mushalla_id').val(data.tipologi_mushalla_id);

                // Trigger Change manual untuk load Kelurahan
                loadKelurahan(data.kecamatan_id, data.kelurahan_id);

                $('#error-alert').addClass('d-none');
                $('#modalData').modal('show');
            });
        }

        // Helper Loaders
        function loadKelurahan(kecId, selectedId = null) {
            var kelSelect = $('#kelurahan_id');
            if (!kecId) return;
            $.get("{{ route('get.kelurahan') }}", {
                kecamatan_id: kecId
            }, function(res) {
                kelSelect.empty().append('<option value="">-- Pilih Kelurahan --</option>');
                $.each(res, function(key, value) {
                    var selected = (value.id == selectedId) ? 'selected' : '';
                    kelSelect.append('<option value="' + value.id + '" ' + selected + '>' + value
                        .nama_kelurahan + '</option>');
                });
                kelSelect.prop('disabled', false);
            });
        }

        function deleteData(id) {
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
