@extends('layout.appv2')
@section('title', 'Detail Mushalla')
@section('sub-layanan', 'active open')
@section('skt-mt', 'show')
@section('mushalla', 'active')

@section('content')
    <div class="content">
        <div class="page-title">
            <h3>Detail Mushalla</h3>
            <p>Informasi detail dan dokumen Mushalla</p>
        </div>

        {{-- Alert Messages --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row">
            <div class="col-md-8">
                <div class="card card-modern h-100">
                    <div class="card-header">
                        <h4 class="section-title-modern">Informasi Mushalla</h4>
                    </div>
                    <div class="card-body">
                        <table class="table table-modern table-bordered">
                            <tr>
                                <th width="35%" class="text-start bg-light">Nomor ID Mushalla</th>
                                <td>{{ $sktMushalla->nomor_id_mushalla }}</td>
                            </tr>
                            <tr>
                                <th class="text-start bg-light">Nama Mushalla</th>
                                <td class="fw-bold text-capitalize">{{ $sktMushalla->nama_mushalla }}</td>
                            </tr>
                            <tr>
                                <th class="text-start bg-light">Tipologi</th>
                                <td>{{ $sktMushalla->tipologiMushalla->nama_tipologi ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th class="text-start bg-light">Nama Marbot</th>
                                <td>
                                    @if ($sktMushalla->marbots->count() > 0)
                                        <ul class="mb-0 ps-3">
                                            @foreach ($sktMushalla->marbots as $marbot)
                                                <li>{{ $marbot->nama_lengkap }}</li>
                                            @endforeach
                                        </ul>
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th class="text-start bg-light">Alamat</th>
                                <td class="text-capitalize">
                                    {{ $sktMushalla->alamat_mushalla }} <br>
                                    {{ $sktMushalla->kelurahan->jenis_kelurahan == 'Desa' ? 'Desa' : 'Kel.' }}
                                    {{ $sktMushalla->kelurahan->nama_kelurahan ?? '-' }}, <br>
                                    Kec. {{ ucwords($sktMushalla->kecamatan->kecamatan ?? '-') }}
                                </td>
                            </tr>
                        </table>

                        <div class="mt-4 d-flex justify-content-between">
                            <a href="{{ route('skt_mushalla.index') }}" class="btn btn-outline-secondary rounded-pill">
                                <i class="fas fa-arrow-left me-1"></i> Kembali
                            </a>
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

                                @if ($sktMushalla->file_skt)
                                    <span class="badge bg-success rounded-pill mb-2">Tersedia (Upload)</span>
                                    <div class="btn-group w-100">
                                        <a href="{{ asset('storage/mushalla_skt/' . $sktMushalla->file_skt) }}"
                                            class="btn btn-sm btn-outline-primary" target="_blank" title="Lihat File">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @if (auth()->user()->hasRole('Admin') || auth()->user()->hasRole('Operator'))
                                            <button type="button" class="btn btn-sm btn-outline-danger"
                                                onclick="confirmDeleteSkt('{{ $sktMushalla->uuid }}')"
                                                title="Hapus File SKT">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        @endif
                                    </div>
                                @else
                                    <span class="badge bg-danger rounded-pill mb-2">Belum Ada Upload</span>
                                    @if (auth()->user()->hasRole('Admin') || auth()->user()->hasRole('Editor') || auth()->user()->hasRole('Operator'))
                                        <button type="button" class="btn btn-sm btn-primary w-100 rounded-pill"
                                            data-bs-toggle="modal" data-bs-target="#uploadSktModal">
                                            <i class="fas fa-upload me-1"></i> Upload SKT
                                        </button>
                                    @endif
                                @endif

                                {{-- Link Generate Draft Always Visible as Helper --}}
                                <div class="mt-2 border-top pt-2">
                                    <small class="text-muted d-block mb-1">Butuh template SKT?</small>
                                    <a href="{{ route('skt_mushalla.cetak_skt', $sktMushalla->uuid) }}"
                                        class="btn btn-sm btn-outline-secondary w-100 rounded-pill" target="_blank">
                                        <i class="fas fa-print me-1"></i> Cetak Draft SKT
                                    </a>
                                </div>
                            </div>

                            {{-- File Barcode --}}
                            @if ($sktMushalla->file_barcode_mushalla)
                                <div class="border rounded p-3 text-center">
                                    <div class="mb-2">
                                        <i class="fas fa-qrcode fa-2x text-dark"></i>
                                        <h6 class="mt-2 text-muted">Barcode / Foto</h6>
                                    </div>
                                    <span class="badge bg-success rounded-pill mb-2">Tersedia</span>
                                    <div class="d-grid">
                                        <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal"
                                            data-bs-target="#viewBarcodeModal">
                                            <i class="fas fa-eye me-1"></i> Lihat File
                                        </button>
                                    </div>
                                </div>
                            @else
                                <div class="border rounded p-3 text-center">
                                    <div class="mb-2">
                                        <i class="fas fa-image fa-2x text-muted"></i>
                                        <h6 class="mt-2 text-muted">Barcode / Foto</h6>
                                    </div>
                                    <span class="badge bg-secondary rounded-pill mb-2">Tidak Ada</span>
                                </div>
                            @endif

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
                    <h5 class="modal-title">Upload File SKT Mushalla</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('skt_mushalla.upload_skt') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="skt_id" value="{{ $sktMushalla->uuid }}">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="skt_file" class="form-label">File SKT (PDF, Max 5MB)</label>
                            <input type="file" class="form-control" id="skt_file" name="skt_file" accept=".pdf"
                                required>
                            <div class="form-text">Pastikan file yang diupload adalah SKT yang sudah ditandatangani dan
                                stempel.</div>
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

    <!-- Modal View Barcode -->
    <div class="modal fade" id="viewBarcodeModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Barcode / Foto Mushalla</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <img src="{{ asset('storage/mushalla_barcodes/' . $sktMushalla->file_barcode_mushalla) }}"
                        class="img-fluid rounded" alt="Barcode Mushalla">
                </div>
                <div class="modal-footer">
                    <a href="{{ asset('storage/mushalla_barcodes/' . $sktMushalla->file_barcode_mushalla) }}"
                        class="btn btn-primary rounded-pill" download>
                        <i class="fas fa-download me-1"></i> Download
                    </a>
                    <button type="button" class="btn btn-light rounded-pill" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Hidden Delete Forms --}}
    <form id="delete-skt-form" method="POST" style="display: none;">
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
                    form.action = "{{ url('appv2/skt-mushalla/delete-skt') }}/" + id;
                    form.submit();
                }
            });
        }
    </script>
@endpush
