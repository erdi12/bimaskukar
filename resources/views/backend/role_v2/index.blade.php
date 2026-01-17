@extends('layout.appv2')
@section('title', 'Manajemen Role V2')
@section('admin', 'active')
@section('roles', 'active')

@section('content')
<div class="content">
    <div class="page-title mb-4">
        <h3>Manajemen Role</h3>
        <p class="text-muted">Kelola hak akses dan peran pengguna</p>
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
                    <h5 class="section-title-modern mb-0 w-100 w-md-auto text-center text-md-start">Daftar Role</h5>
                    <button class="btn btn-primary rounded-pill w-100 w-md-auto" onclick="addRole()">
                        <i class="fas fa-shield-alt me-2"></i> Tambah Role
                    </button>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <table class="table table-modern table-hover w-100" id="table-roles">
                        <thead class="bg-light text-nowrap">
                            <tr>
                                <th width="50">No</th>
                                <th>Nama Role</th>
                                <th>Jumlah User</th>
                                <th>Deskripsi</th>
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
<div class="modal fade" id="modalRole" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">Tambah Role</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formRole">
                @csrf
                <input type="hidden" id="role_id" name="id">
                <div class="modal-body">
                    <div class="alert alert-danger d-none" id="error-alert"></div>
                    
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama Role <span class="text-danger">*</span></label>
                        <input type="text" class="form-control text-capitalize" id="name" name="name" required placeholder="Contoh: Supervisor">
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Deskripsi</label>
                        <textarea class="form-control" id="description" name="description" rows="3" placeholder="Deskripsi singkat role ini..."></textarea>
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
        var table = $('#table-roles').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('roles_v2.index') }}",
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false, className: 'text-center'},
                {data: 'name', name: 'name', className: 'text-capitalize'},
                {data: 'users_count', name: 'users_count', className: 'text-center', searchable: false},
                {data: 'description', name: 'description'},
                {data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center'},
            ],
            language: {
                url: "//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json"
            },
            initComplete: function () {
                $(this).wrap('<div class="table-responsive"></div>');
            }
        });

        $('#formRole').on('submit', function(e) {
            e.preventDefault();
            var id = $('#role_id').val();
            var url = id ? "{{ route('roles_v2.update', ':id') }}".replace(':id', id) : "{{ route('roles_v2.store') }}";
            var method = id ? 'PUT' : 'POST';
            
            var formData = $(this).serialize();
            if(id) formData += '&_method=PUT';

            $.ajax({
                url: url,
                type: method,
                data: formData,
                beforeSend: function() {
                    $('#btn-save').prop('disabled', true).text('Menyimpan...');
                    $('#error-alert').addClass('d-none').text('');
                },
                success: function(res) {
                    $('#modalRole').modal('hide');
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
                    if(errors) {
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

    function addRole() {
        $('#modalTitle').text('Tambah Role');
        $('#formRole')[0].reset();
        $('#role_id').val('');
        $('#error-alert').addClass('d-none');
        $('#modalRole').modal('show');
    }

    function editRole(id) {
        $.get("{{ url('appv2/roles') }}/" + id + "/edit", function(data) {
            $('#modalTitle').text('Edit Role');
            $('#role_id').val(data.id);
            $('#name').val(data.name);
            $('#description').val(data.description);
            $('#error-alert').addClass('d-none');
            $('#modalRole').modal('show');
        });
    }

    function deleteRole(id) {
        Swal.fire({
            title: 'Yakin hapus role?',
            text: "Pastikan tidak ada user yang menggunakan role ini.",
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
