@extends('layout.app')
@section('title', 'Detail Majelis Ta\'lim')
@section('sub-layanan', 'active')
@section('layanan', 'active')
@section('skt-mt', 'active')

@section('content')
<div class="main-content container-fluid">
    <div class="page-title">
        <h3>Detail Majelis Ta'lim</h3>
    </div>
    
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Informasi Majelis Ta'lim</h4>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <tr>
                            <th width="30%">Nomor Statistik</th>
                            <td>{{ $sktpiagammt->nomor_statistik }}</td>
                        </tr>
                        <tr>
                            <th>Nama Majelis</th>
                            <td>{{ $sktpiagammt->nama_majelis }}</td>
                        </tr>
                        <tr>
                            <th>Alamat</th>
                            <td>
                                {{ $sktpiagammt->alamat }}, 
                                {{ $sktpiagammt->kelurahan->jenis_kelurahan == 'Desa' ? 'Desa' : 'Kel.' }} {{ $sktpiagammt->kelurahan->nama_kelurahan }}, 
                                Kec. {{ ucwords($sktpiagammt->kecamatan->kecamatan) }}
                            </td>
                        </tr>
                        <tr>
                            <th>Ketua</th>
                            <td>{{ $sktpiagammt->ketua }}</td>
                        </tr>
                        <tr>
                            <th>No. HP</th>
                            <td>{{ $sktpiagammt->no_hp }}</td>
                        </tr>
                        <tr>
                            <th>Tanggal Berdiri</th>
                            <td>{{ \Carbon\Carbon::parse($sktpiagammt->tanggal_berdiri)->locale('id')->isoFormat('D MMMM Y') }}</td>
                        </tr>
                        <tr>
                            <th>Tanggal Mendaftar</th>
                            <td>{{ \Carbon\Carbon::parse($sktpiagammt->mendaftar)->locale('id')->isoFormat('D MMMM Y') }}</td>
                        </tr>
                        <tr>
                            <th>Tanggal Daftar Ulang</th>
                            <td>{{ \Carbon\Carbon::parse($sktpiagammt->mendaftar_ulang)->locale('id')->isoFormat('D MMMM Y') }}</td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td>
                                @if($sktpiagammt->status == 'aktif')
                                    <span class="badge bg-success">Aktif</span>
                                @elseif($sktpiagammt->status == 'nonaktif')
                                    <span class="badge bg-danger">Non-Aktif</span>
                                @else
                                    <span class="badge bg-warning">Belum Update</span>
                                @endif
                            </td>
                        </tr>
                    </table>

                    <div class="mt-4 d-flex justify-content-between">
                        <a href="{{ route('skt_piagam_mt.index') }}" class="btn btn-secondary">
                            <i data-feather="arrow-left"></i> Kembali
                        </a>
                        @if(auth()->user()->hasRole('Admin') || auth()->user()->hasRole('Editor') || auth()->user()->hasRole('Operator'))
                        <a href="{{ route('skt_piagam_mt.edit', $sktpiagammt->id) }}" class="btn btn-success">
                            <i data-feather="edit"></i> Edit Data
                        </a>
                        @endif
                    </div>
                </div>
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-header">
                    <h4 class="card-title">Dokumen Majelis Ta'lim</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Dokumen</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- SKT --}}
                                <tr>
                                    <td>File SKT</td>
                                    <td>
                                        @if($sktpiagammt->file_skt)
                                            <span class="badge bg-success">Tersedia</span>
                                        @else
                                            <span class="badge bg-danger">Belum Ada</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if(!$sktpiagammt->file_skt)
                                            @if(auth()->user()->hasRole('Admin') || auth()->user()->hasRole('Editor') || auth()->user()->hasRole('Operator'))
                                            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#uploadSktModal">
                                                <i class="fa-solid fa-arrow-up-from-bracket me-1"></i> Upload
                                            </button>
                                            @endif
                                        @else
                                            <div class="btn-group btn-group-sm">
                                                <a href="{{ asset('storage/skt/' . $sktpiagammt->file_skt) }}" class="btn btn-success" target="_blank">
                                                    <i class="fa-regular fa-eye me-1"></i> Lihat
                                                </a>
                                                @if(auth()->user()->hasRole('Admin') || auth()->user()->hasRole('Operator'))
                                                <button type="button" class="btn btn-danger" onclick="confirmDeleteSkt('{{ $sktpiagammt->id }}')">
                                                    <i class="fa-regular fa-trash-can me-1"></i> Hapus
                                                </button>
                                                @endif
                                            </div>
                                        @endif
                                    </td>
                                </tr>

                                {{-- Piagam --}}
                                <tr>
                                    <td>File Piagam</td>
                                    <td>
                                        @if($sktpiagammt->file_piagam)
                                            <span class="badge bg-success">Tersedia</span>
                                        @else
                                            <span class="badge bg-danger">Belum Ada</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if(!$sktpiagammt->file_piagam)
                                            @if(auth()->user()->hasRole('Admin') || auth()->user()->hasRole('Editor') || auth()->user()->hasRole('Operator'))
                                            <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#uploadPiagamModal">
                                                <i class="fa-solid fa-arrow-up-from-bracket me-1"></i> Upload
                                            </button>
                                            @endif
                                        @else
                                            <div class="btn-group btn-group-sm">
                                                <a href="{{ asset('storage/piagam/' . $sktpiagammt->file_piagam) }}" class="btn btn-success" target="_blank">
                                                    <i class="fa-regular fa-eye me-1"></i> Lihat
                                                </a>
                                                @if(auth()->user()->hasRole('Admin') || auth()->user()->hasRole('Operator'))
                                                <button type="button" class="btn btn-danger" onclick="confirmDeletePiagam('{{ $sktpiagammt->id }}')">
                                                    <i class="fa-regular fa-trash-can me-1"></i> Hapus
                                                </button>
                                                @endif
                                            </div>
                                        @endif
                                    </td>
                                </tr>

                                {{-- Berkas --}}
                                <tr>
                                    <td>File Berkas Pendukung</td>
                                    <td>
                                        @if($sktpiagammt->file_berkas)
                                            <span class="badge bg-success">Tersedia</span>
                                        @else
                                            <span class="badge bg-danger">Belum Ada</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if(!$sktpiagammt->file_berkas)
                                            @if(auth()->user()->hasRole('Admin') || auth()->user()->hasRole('Editor') || auth()->user()->hasRole('Operator'))
                                            <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#uploadBerkasModal">
                                                <i class="fa-solid fa-arrow-up-from-bracket me-1"></i> Upload
                                            </button>
                                            @endif
                                        @else
                                            <div class="btn-group btn-group-sm">
                                                <a href="{{ asset('storage/berkas/' . $sktpiagammt->file_berkas) }}" class="btn btn-success" target="_blank">
                                                    <i class="fa-regular fa-eye me-1"></i> Lihat
                                                </a>
                                                @if(auth()->user()->hasRole('Admin') || auth()->user()->hasRole('Editor') || auth()->user()->hasRole('Operator'))
                                                <button type="button" class="btn btn-danger" onclick="confirmDeleteBerkas('{{ $sktpiagammt->id }}')">
                                                    <i class="fa-regular fa-trash-can me-1"></i> Hapus
                                                </button>
                                                @endif
                                            </div>
                                        @endif
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- Modal Upload SKT -->
            <div class="modal fade" id="uploadSktModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Upload File SKT</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form action="{{ route('skt_piagam_mt.upload_skt') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="skt_id" value="{{ $sktpiagammt->id }}">
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label for="skt_file" class="form-label">File SKT (PDF, Max 5MB)</label>
                                    <input type="file" class="form-control" id="skt_file" name="skt_file" accept=".pdf" required>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-primary">Upload</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Modal Upload Piagam -->
            <div class="modal fade" id="uploadPiagamModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Upload File Piagam</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form action="{{ route('skt_piagam_mt.upload_piagam') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="piagam_id" value="{{ $sktpiagammt->id }}">
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label for="piagam_file" class="form-label">File Piagam (PDF, Max 5MB)</label>
                                    <input type="file" class="form-control" id="piagam_file" name="piagam_file" accept=".pdf" required>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-primary">Upload</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Modal Upload Berkas -->
            <div class="modal fade" id="uploadBerkasModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Upload Berkas Pendukung</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form action="{{ route('skt_piagam_mt.upload_berkas') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="berkas_id" value="{{ $sktpiagammt->id }}">
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label for="berkas_file" class="form-label">File Berkas (PDF, Max 5MB)</label>
                                    <input type="file" class="form-control" id="berkas_file" name="berkas_file" accept=".pdf" required>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-primary">Upload</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>


<script>
    function confirmDeleteSkt(id) {
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "File SKT akan dihapus permanen!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "/skt-piagam-mt/" + id + "/delete-skt";
            }
        })
    }

    function confirmDeletePiagam(id) {
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "File Piagam akan dihapus permanen!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "/skt-piagam-mt/" + id + "/delete-piagam";
            }
        })
    }

    function confirmDeleteBerkas(id) {
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "File Berkas akan dihapus permanen!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "/skt-piagam-mt/" + id + "/delete-berkas";
            }
        })
    }
</script>
@endsection
