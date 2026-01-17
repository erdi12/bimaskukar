@extends('layout.appv2')
@section('title', 'Detail Majelis Ta\'lim')
@section('sub-layanan', 'active')
@section('layanan', 'active')
@section('skt-mt', 'active')

@section('content')
    <div class="content">
        <div class="page-title">
            <h3>Detail Majelis Ta'lim</h3>
            <p>Informasi detail dan dokumen majelis ta'lim</p>
        </div>

        <div class="row">
            <div class="col-md-8">
                <div class="card card-modern h-100">
                    <div class="card-header">
                        <h4 class="section-title-modern">Informasi Majelis Ta'lim</h4>
                    </div>
                    <div class="card-body">
                        <table class="table table-modern table-bordered">
                            <tr>
                                <th width="35%" class="text-start bg-light">Nomor Statistik</th>
                                <td>{{ $sktpiagammt->nomor_statistik }}</td>
                            </tr>
                            <tr>
                                <th class="text-start bg-light">Nama Majelis</th>
                                <td class="fw-bold">{{ $sktpiagammt->nama_majelis }}</td>
                            </tr>
                            <tr>
                                <th class="text-start bg-light">Alamat</th>
                                <td>
                                    {{ $sktpiagammt->alamat }}, <br>
                                    {{ $sktpiagammt->kelurahan->jenis_kelurahan == 'Desa' ? 'Desa' : 'Kel.' }}
                                    {{ $sktpiagammt->kelurahan->nama_kelurahan }}, <br>
                                    Kec. {{ ucwords($sktpiagammt->kecamatan->kecamatan) }}
                                </td>
                            </tr>
                            <tr>
                                <th class="text-start bg-light">Ketua</th>
                                <td>{{ $sktpiagammt->ketua }}</td>
                            </tr>
                            <tr>
                                <th class="text-start bg-light">No. HP</th>
                                <td>{{ $sktpiagammt->no_hp }}</td>
                            </tr>
                            <tr>
                                <th class="text-start bg-light">Jumlah Anggota</th>
                                <td>{{ $sktpiagammt->jumlah_anggota ?? '-' }} Orang</td>
                            </tr>
                            <tr>
                                <th class="text-start bg-light align-middle">Materi Kajian</th>
                                <td>
                                    @if ($sktpiagammt->materi)
                                        <ol class="mb-0 ps-3">
                                            @foreach (explode(', ', $sktpiagammt->materi) as $materi)
                                                <li class="mb-1">{{ $materi }}</li>
                                            @endforeach
                                        </ol>
                                    @else
                                        <span class="text-muted fst-italic">Tidak ada data materi</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th class="text-start bg-light">Tanggal Berdiri</th>
                                <td>{{ \Carbon\Carbon::parse($sktpiagammt->tanggal_berdiri)->locale('id')->isoFormat('D MMMM Y') }}
                                </td>
                            </tr>
                            <tr>
                                <th class="text-start bg-light">Tanggal Mendaftar</th>
                                <td>{{ \Carbon\Carbon::parse($sktpiagammt->mendaftar)->locale('id')->isoFormat('D MMMM Y') }}
                                </td>
                            </tr>
                            <tr>
                                <th class="text-start bg-light">Tanggal Daftar Ulang</th>
                                <td>{{ \Carbon\Carbon::parse($sktpiagammt->mendaftar_ulang)->locale('id')->isoFormat('D MMMM Y') }}
                                </td>
                            </tr>
                            <tr>
                                <th class="text-start bg-light">Status</th>
                                <td>
                                    @if ($sktpiagammt->status == 'aktif')
                                        <span class="badge bg-success rounded-pill">Aktif</span>
                                    @elseif($sktpiagammt->status == 'nonaktif')
                                        <span class="badge bg-danger rounded-pill">Non-Aktif</span>
                                    @else
                                        <span class="badge bg-warning rounded-pill">Belum Update</span>
                                    @endif
                                </td>
                            </tr>
                        </table>

                        <div class="mt-4 d-flex justify-content-between">
                            <a href="{{ route('skt_piagam_mt_v2.index') }}" class="btn btn-outline-secondary rounded-pill">
                                <i class="fas fa-arrow-left me-1"></i> Kembali
                            </a>
                            @if (auth()->user()->hasRole('Admin') || auth()->user()->hasRole('Editor') || auth()->user()->hasRole('Operator'))
                                <a href="{{ route('skt_piagam_mt_v2.edit', $sktpiagammt->uuid) }}"
                                    class="btn btn-primary rounded-pill">
                                    <i class="fas fa-edit me-1"></i> Edit Data
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card card-modern h-100">
                    <div class="card-header">
                        <h4 class="section-title-modern">Dokumen</h4>
                    </div>
                    <div class="card-body">
                        <div class="vstack gap-3">

                            {{-- File SKT --}}
                            <div class="border rounded p-3 text-center">
                                <div class="mb-2">
                                    <i class="fas fa-file-pdf fa-2x text-danger"></i>
                                    <h6 class="mt-2 text-muted">File SKT</h6>
                                </div>
                                @if ($sktpiagammt->file_skt)
                                    <span class="badge bg-success rounded-pill mb-2">Tersedia</span>
                                    <div class="btn-group w-100">
                                        <a href="{{ asset('storage/skt/' . $sktpiagammt->file_skt) }}"
                                            class="btn btn-sm btn-outline-primary" target="_blank">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @if (auth()->user()->hasRole('Admin') || auth()->user()->hasRole('Operator'))
                                            <button type="button" class="btn btn-sm btn-outline-danger"
                                                onclick="confirmDeleteSkt('{{ $sktpiagammt->uuid }}')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        @endif
                                    </div>
                                @else
                                    <span class="badge bg-danger rounded-pill mb-2">Belum Ada</span>
                                    @if (auth()->user()->hasRole('Admin') || auth()->user()->hasRole('Editor') || auth()->user()->hasRole('Operator'))
                                        <button type="button" class="btn btn-sm btn-primary w-100 rounded-pill"
                                            data-bs-toggle="modal" data-bs-target="#uploadSktModal">
                                            <i class="fas fa-upload me-1"></i> Upload
                                        </button>
                                    @endif
                                @endif
                            </div>

                            {{-- File Piagam --}}
                            <div class="border rounded p-3 text-center">
                                <div class="mb-2">
                                    <i class="fas fa-certificate fa-2x text-warning"></i>
                                    <h6 class="mt-2 text-muted">File Piagam</h6>
                                </div>
                                @if ($sktpiagammt->file_piagam)
                                    <span class="badge bg-success rounded-pill mb-2">Tersedia</span>
                                    <div class="btn-group w-100">
                                        <a href="{{ asset('storage/piagam/' . $sktpiagammt->file_piagam) }}"
                                            class="btn btn-sm btn-outline-primary" target="_blank">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @if (auth()->user()->hasRole('Admin') || auth()->user()->hasRole('Operator'))
                                            <button type="button" class="btn btn-sm btn-outline-danger"
                                                onclick="confirmDeletePiagam('{{ $sktpiagammt->uuid }}')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        @endif
                                    </div>
                                @else
                                    <span class="badge bg-danger rounded-pill mb-2">Belum Ada</span>
                                    @if (auth()->user()->hasRole('Admin') || auth()->user()->hasRole('Editor') || auth()->user()->hasRole('Operator'))
                                        <button type="button" class="btn btn-sm btn-primary w-100 rounded-pill"
                                            data-bs-toggle="modal" data-bs-target="#uploadPiagamModal">
                                            <i class="fas fa-upload me-1"></i> Upload
                                        </button>
                                    @endif
                                @endif
                            </div>

                            {{-- File Berkas --}}
                            <div class="border rounded p-3 text-center">
                                <div class="mb-2">
                                    <i class="fas fa-folder-open fa-2x text-info"></i>
                                    <h6 class="mt-2 text-muted">Berkas Pendukung</h6>
                                </div>
                                @if ($sktpiagammt->file_berkas)
                                    <span class="badge bg-success rounded-pill mb-2">Tersedia</span>
                                    <div class="btn-group w-100">
                                        <a href="{{ asset('storage/berkas/' . $sktpiagammt->file_berkas) }}"
                                            class="btn btn-sm btn-outline-primary" target="_blank">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @if (auth()->user()->hasRole('Admin') || auth()->user()->hasRole('Editor') || auth()->user()->hasRole('Operator'))
                                            <button type="button" class="btn btn-sm btn-outline-danger"
                                                onclick="confirmDeleteBerkas('{{ $sktpiagammt->uuid }}')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        @endif
                                    </div>
                                @else
                                    <span class="badge bg-danger rounded-pill mb-2">Belum Ada</span>
                                    @if (auth()->user()->hasRole('Admin') || auth()->user()->hasRole('Editor') || auth()->user()->hasRole('Operator'))
                                        <button type="button" class="btn btn-sm btn-primary w-100 rounded-pill"
                                            data-bs-toggle="modal" data-bs-target="#uploadBerkasModal">
                                            <i class="fas fa-upload me-1"></i> Upload
                                        </button>
                                    @endif
                                @endif
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Upload SKT -->
    <div class="modal fade" id="uploadSktModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Upload File SKT</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('skt_piagam_mt_v2.upload_skt') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="skt_id" value="{{ $sktpiagammt->uuid }}">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="skt_file" class="form-label">File SKT (PDF, Max 5MB)</label>
                            <input type="file" class="form-control" id="skt_file" name="skt_file" accept=".pdf"
                                required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light rounded-pill" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary rounded-pill">Upload</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Upload Piagam -->
    <div class="modal fade" id="uploadPiagamModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Upload File Piagam</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('skt_piagam_mt_v2.upload_piagam') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="piagam_id" value="{{ $sktpiagammt->uuid }}">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="piagam_file" class="form-label">File Piagam (PDF, Max 5MB)</label>
                            <input type="file" class="form-control" id="piagam_file" name="piagam_file"
                                accept=".pdf" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light rounded-pill" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary rounded-pill">Upload</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Upload Berkas -->
    <div class="modal fade" id="uploadBerkasModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Upload Berkas Pendukung</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('skt_piagam_mt_v2.upload_berkas') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="berkas_id" value="{{ $sktpiagammt->uuid }}">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="berkas_file" class="form-label">File Berkas (PDF, Max 5MB)</label>
                            <input type="file" class="form-control" id="berkas_file" name="berkas_file"
                                accept=".pdf" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light rounded-pill" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary rounded-pill">Upload</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Hidden Delete Forms --}}
    <form id="delete-skt-form" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>
    <form id="delete-piagam-form" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>
    <form id="delete-berkas-form" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>

@endsection

@push('scripts')
    <script>
        function confirmDeleteSkt(id) {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "File SKT akan dihapus permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    var form = document.getElementById('delete-skt-form');
                    form.action = '/skt-piagam-mt-v2/delete-skt/' + id;
                    form.submit();
                }
            });
        }

        function confirmDeletePiagam(id) {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "File Piagam akan dihapus permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    var form = document.getElementById('delete-piagam-form');
                    form.action = '/skt-piagam-mt-v2/delete-piagam/' + id;
                    form.submit();
                }
            });
        }

        function confirmDeleteBerkas(id) {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "File Berkas akan dihapus permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    var form = document.getElementById('delete-berkas-form');
                    form.action = '/skt-piagam-mt-v2/delete-berkas/' + id;
                    form.submit();
                }
            });
        }
    </script>
@endpush
