@extends('layout.app')
@section('trash', 'active')

@section('title', 'Data Terhapus - Majelis Ta\'lim')

@section('content')
<div class="main-content container-fluid">
    <div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Data Terhapus - Majelis Ta'lim</h3>
                <p class="text-subtitle text-muted">Daftar data Majelis Ta'lim yang telah dihapus</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('skt_piagam_mt.index') }}">Majelis Ta'lim</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Data Terhapus</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <section class="section">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h4 class="card-title">Data Majelis Ta'lim Terhapus</h4>
                <a href="{{ route('skt_piagam_mt.index') }}" class="btn btn-primary">
                    <i data-feather="arrow-left"></i> Kembali
                </a>
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

                <div class="table-responsive">
                    <table class="table table-striped" id="table1">
                        <thead>
                            <tr>
                                <th width="5%">No</th>
                                <th>Nomor Statistik</th>
                                <th>Nama Majelis Ta'lim</th>
                                <th>Alamat</th>
                                <th>Kecamatan</th>
                                <th>Kelurahan</th>
                                <th>Tanggal Dihapus</th>
                                <th width="15%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($trashedData as $index => $item)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $item->nomor_statistik }}</td>
                                    <td>{{ $item->nama_majelis }}</td>
                                    <td>{{ $item->alamat }}</td>
                                    <td>{{ ucwords($item->kecamatan->kecamatan) ?? '-' }}</td>
                                    <td>{{ $item->kelurahan->nama_kelurahan ?? '-' }}</td>
                                    <td>{{ $item->deleted_at->format('d-m-Y H:i') }}</td>
                                    <td>
                                        <div class="d-inline-flex gap-2">
                                            <form action="{{ route('skt_piagam_mt.restore', $item->id) }}" method="POST" id="restore-form-{{ $item->id }}">
                                                @csrf
                                                <button type="button" class="btn btn-success d-inline-flex align-items-center" onclick="confirmRestore({{ $item->id }})">
                                                    <i data-feather="refresh-cw" class="me-1" style="width: 24px; height: 24px;"></i> Pulihkan Data
                                                </button>
                                            </form>
                                            <!-- Ganti form hapus permanen yang menggunakan confirm JavaScript biasa -->
                                            <form action="{{ route('skt_piagam_mt.force_delete', $item->id) }}" method="POST" id="force-delete-form-{{ $item->id }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="btn btn-danger d-inline-flex align-items-center" onclick="confirmForceDelete({{ $item->id }})">
                                                    <i data-feather="trash-2" class="me-1" style="width: 24px; height: 24px;"></i> Hapus Permanen
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
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
    
    $(document).ready(function() {
        $('#table1').DataTable();
    });

    // Fungsi konfirmasi restore
    function confirmRestore(id) {
        Swal.fire({
            title: 'Pulihkan data?',
            text: "Data akan dipulihkan ke daftar aktif!",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, pulihkan!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById(`restore-form-${id}`).submit();
            }
        });
    }
    
    // Fungsi konfirmasi hapus permanen
    function confirmForceDelete(id) {
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Data akan dihapus secara permanen dan tidak dapat dipulihkan kembali!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, hapus permanen!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById(`force-delete-form-${id}`).submit();
            }
        });
    }
</script>
@endpush