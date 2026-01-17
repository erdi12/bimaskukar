@extends('layout.appv2')
@section('title', 'Manajemen User V2')
@section('admin', 'active')
@section('users', 'active')

@section('content')
<div class="content">
    <div class="page-title mb-4">
        <h3>Manajemen User</h3>
        <p class="text-muted">Kelola data pengguna aplikasi</p>
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
                    <h5 class="section-title-modern mb-0 w-100 w-md-auto text-center text-md-start">Daftar Pengguna</h5>
                    <button class="btn btn-primary rounded-pill w-100 w-md-auto" onclick="addUser()">
                        <i class="fas fa-user-plus me-2"></i> Tambah User
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

                    <table class="table table-modern table-hover w-100" id="table-users">
                        <thead class="bg-light text-nowrap">
                            <tr>
                                <th width="50">No</th>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>Role</th>
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
<div class="modal fade" id="modalUser" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">Tambah User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formUser">
                @csrf
                <input type="hidden" id="user_id" name="id">
                <div class="modal-body">
                    <div class="alert alert-danger d-none" id="error-alert"></div>
                    
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" class="form-control text-capitalize" id="name" name="name" required placeholder="Contoh: Ahmad Umar">
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                        <input type="email" class="form-control" id="email" name="email" required placeholder="admin@example.com">
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="password" class="form-label">Password <span class="text-danger" id="pass-required">*</span></label>
                            <input type="password" class="form-control" id="password" name="password" placeholder="Minimal 6 karakter">
                            <small class="text-muted d-none" id="pass-help">Kosongkan jika tidak ingin mengganti password</small>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Ulangi password">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="role_id" class="form-label">Role <span class="text-danger">*</span></label>
                        <select class="form-select" id="role_id" name="role_id" required>
                            <option value="">-- Pilih Role --</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}">{{ $role->name }}</option>
                            @endforeach
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
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        var table = $('#table-users').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('users_v2.index') }}",
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false, className: 'text-center'},
                {data: 'name', name: 'name', className: 'text-capitalize'},
                {data: 'email', name: 'email'},
                {data: 'role', name: 'role', className: 'text-center'},
                {data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center'},
            ],
            language: {
                url: "//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json"
            },
            initComplete: function () {
                $(this).wrap('<div class="table-responsive"></div>');
            }
        });

        $('#formUser').on('submit', function(e) {
            e.preventDefault();
            var id = $('#user_id').val();
            var url = id ? "{{ route('users_v2.update', ':id') }}".replace(':id', id) : "{{ route('users_v2.store') }}";
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
                    $('#modalUser').modal('hide');
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

    function addUser() {
        $('#modalTitle').text('Tambah User');
        $('#formUser')[0].reset();
        $('#user_id').val('');
        $('#error-alert').addClass('d-none');
        
        // Reset Password Field State
        $('#pass-required').removeClass('d-none');
        $('#pass-help').addClass('d-none');
        $('#password').attr('required', true);
        
        $('#modalUser').modal('show');
    }

    function editUser(id) {
        $.get("{{ url('appv2/users') }}/" + id + "/edit", function(data) {
            $('#modalTitle').text('Edit User');
            $('#user_id').val(data.id);
            $('#name').val(data.name);
            $('#email').val(data.email);
            $('#role_id').val(data.role_id);
            
            // Edit Mode: Password not required
            $('#pass-required').addClass('d-none');
            $('#pass-help').removeClass('d-none');
            $('#password').removeAttr('required');
            $('#password').val('');
            $('#password_confirmation').val('');
            
            $('#error-alert').addClass('d-none');
            $('#modalUser').modal('show');
        });
    }

    function deleteUser(id) {
        Swal.fire({
            title: 'Yakin hapus user?',
            text: "Akses user ini akan dicabut permanen.",
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
