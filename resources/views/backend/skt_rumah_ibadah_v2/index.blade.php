@extends('layout.appv2')
@section('title', 'Data Rumah Ibadah V2')
@section('sub-layanan', 'active open')
@section('skt-mt', 'show')
@section('rumah-ibadah', 'active')

@section('content')
    <div class="content">
        <div class="page-title mb-4">
            <h3>Data Rumah Ibadah</h3>
            <p class="text-muted">Kelola data Masjid dan Musholla di Wilayah Kutai Kartanegara</p>
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
                        <h5 class="section-title-modern mb-0 w-100 w-md-auto text-center text-md-start">Daftar Rumah Ibadah
                        </h5>
                        <button class="btn btn-primary rounded-pill w-100 w-md-auto" onclick="addData()">
                            <i class="fas fa-plus me-2"></i> Tambah Data
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label class="form-label">Filter Kecamatan:</label>
                                <select id="filter_kecamatan" class="form-select">
                                    <option value="">Semua Kecamatan</option>
                                    @foreach ($kecamatans as $kec)
                                        <option value="{{ $kec->id }}">{{ $kec->kecamatan }}</option>
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
                                    <th>Nama Rumah Ibadah</th>
                                    <th>Lokasi (Kel/Kec)</th>
                                    <th>Jenis & Tipologi</th>
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
                    <h5 class="modal-title" id="modalTitle">Tambah Rumah Ibadah</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="formData">
                    @csrf
                    <input type="hidden" id="data_id" name="id">
                    <div class="modal-body">
                        <div class="alert alert-danger d-none" id="error-alert"></div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="nama_rumah_ibadah" class="form-label">Nama Rumah Ibadah <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control text-capitalize" id="nama_rumah_ibadah"
                                    name="nama_rumah_ibadah" required placeholder="Contoh: Masjid Agung">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="nomor_statistik" class="form-label">Nomor Statistik</label>
                                <input type="text" class="form-control" id="nomor_statistik" name="nomor_statistik"
                                    placeholder="Opsional">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="alamat" class="form-label">Alamat Lengkap</label>
                            <textarea class="form-control" id="alamat" name="alamat" rows="2" placeholder="Jalan, RT, RW..."></textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="kecamatan_id" class="form-label">Kecamatan <span
                                        class="text-danger">*</span></label>
                                <select class="form-select" id="kecamatan_id" name="kecamatan_id" required>
                                    <option value="">-- Pilih Kecamatan --</option>
                                    @foreach ($kecamatans as $kec)
                                        <option value="{{ $kec->id }}">{{ $kec->kecamatan }}</option>
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
                                <label for="jenis_rumah_ibadah_id" class="form-label">Jenis Rumah Ibadah <span
                                        class="text-danger">*</span></label>
                                <select class="form-select" id="jenis_rumah_ibadah_id" name="jenis_rumah_ibadah_id"
                                    required>
                                    <option value="">-- Pilih Jenis --</option>
                                    @foreach ($jenisRumahIbadah as $jenis)
                                        <option value="{{ $jenis->id }}">{{ $jenis->jenis_rumah_ibadah }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="tipologi_rumah_ibadah_id" class="form-label">Tipologi</label>
                                <select class="form-select" id="tipologi_rumah_ibadah_id" name="tipologi_rumah_ibadah_id"
                                    disabled>
                                    <option value="">-- Pilih Jenis Dulu (Opsional) --</option>
                                </select>
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
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            var table = $('#table-data').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('rumah_ibadah_v2.index') }}",
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
                        data: 'nama_rumah_ibadah',
                        name: 'nama_rumah_ibadah',
                        className: 'text-capitalize fw-bold'
                    },
                    {
                        data: 'lokasi',
                        name: 'lokasi'
                    },
                    {
                        data: 'jenis_tipologi',
                        name: 'jenis_tipologi'
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

            // Chained Dropdown: Jenis -> Tipologi
            $('#jenis_rumah_ibadah_id').change(function() {
                var jenisId = $(this).val();
                var tipeSelect = $('#tipologi_rumah_ibadah_id');
                tipeSelect.empty().append('<option value="">Loading...</option>').prop('disabled', true);

                if (jenisId) {
                    $.get("{{ url('appv2/get-tipologi') }}/" + jenisId, function(res) {
                        tipeSelect.empty().append(
                            '<option value="">-- Pilih Tipologi (Opsional) --</option>');
                        if (res.length > 0) {
                            $.each(res, function(key, value) {
                                tipeSelect.append('<option value="' + value.id + '">' +
                                    value.nama_tipologi + '</option>');
                            });
                            tipeSelect.prop('disabled', false);
                        } else {
                            tipeSelect.append('<option value="">Tidak ada tipologi</option>');
                        }
                    });
                } else {
                    tipeSelect.empty().append('<option value="">-- Pilih Jenis Dulu --</option>');
                }
            });

            $('#formData').on('submit', function(e) {
                e.preventDefault();
                var id = $('#data_id').val();
                var url = id ? "{{ route('rumah_ibadah_v2.update', ':id') }}".replace(':id', id) :
                    "{{ route('rumah_ibadah_v2.store') }}";
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

        function addData() {
            $('#modalTitle').text('Tambah Rumah Ibadah');
            $('#formData')[0].reset();
            $('#data_id').val('');
            $('#error-alert').addClass('d-none');

            // Reset chained dropdowns
            $('#kelurahan_id').empty().append('<option value="">-- Pilih Kecamatan Dulu --</option>').prop('disabled',
            true);
            $('#tipologi_rumah_ibadah_id').empty().append('<option value="">-- Pilih Jenis Dulu --</option>').prop(
                'disabled', true);

            $('#modalData').modal('show');
        }

        function editData(id) {
            $.get("{{ url('appv2/skt-rumah-ibadah') }}/" + id + "/edit", function(data) {
                $('#modalTitle').text('Edit Rumah Ibadah');
                $('#data_id').val(data.id);
                $('#nama_rumah_ibadah').val(data.nama_rumah_ibadah);
                $('#nomor_statistik').val(data.nomor_statistik);
                $('#alamat').val(data.alamat);
                $('#kecamatan_id').val(data.kecamatan_id);
                $('#jenis_rumah_ibadah_id').val(data.jenis_rumah_ibadah_id);

                // Trigger Change manual untuk load Kelurahan & Tipologi, lalu set selected value
                // Note: Ini agak tricky karena ASYNC. Kita pakai setTimeout atau logic khusus.
                // Cara paling aman: Load dropdown manual di sini.

                loadKelurahan(data.kecamatan_id, data.kelurahan_id);
                loadTipologi(data.jenis_rumah_ibadah_id, data.tipologi_rumah_ibadah_id);

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

        function loadTipologi(jenisId, selectedId = null) {
            var tipeSelect = $('#tipologi_rumah_ibadah_id');
            if (!jenisId) return;
            $.get("{{ url('appv2/get-tipologi') }}/" + jenisId, function(res) {
                tipeSelect.empty().append('<option value="">-- Pilih Tipologi (Opsional) --</option>');
                if (res.length > 0) {
                    $.each(res, function(key, value) {
                        var selected = (value.id == selectedId) ? 'selected' : '';
                        tipeSelect.append('<option value="' + value.id + '" ' + selected + '>' + value
                            .nama_tipologi + '</option>');
                    });
                    tipeSelect.prop('disabled', false);
                }
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
