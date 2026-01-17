@extends('layout.appv2')
@section('title', 'Data Terhapus - Majelis Ta\'lim')
@section('sub-layanan', 'active')
@section('layanan', 'active')
@section('skt-mt', 'active')

@section('content')
    <div class="content">
        <div class="page-title">
            <h3>Data Terhapus - Majelis Ta'lim</h3>
            <p>Daftar data Majelis Ta'lim yang telah dihapus ("soft deleted")</p>
        </div>

        <section class="section">
            <div class="card card-modern">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="section-title-modern mb-0">Sampah Data</h4>
                    <a href="{{ route('skt_piagam_mt_v2.index') }}" class="btn btn-soft-secondary rounded-pill">
                        <i class="fas fa-arrow-left me-1"></i> Kembali ke Index
                    </a>
                </div>
                <div class="card-body">
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

                    <div class="table-responsive">
                        <table class="table table-modern table-bordered table-hover w-100" id="tableTrash">
                            <thead>
                                <tr class="text-center align-middle">
                                    <th width="5%">No</th>
                                    <th>Nomor Statistik</th>
                                    <th>Nama Majelis</th>
                                    <th>Alamat</th>
                                    <th>Kecamatan</th>
                                    <th>Kelurahan</th>
                                    <th>Waktu Dihapus</th>
                                    <th width="20%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($sktpiagammts as $index => $item)
                                    <tr>
                                        <td class="text-center">{{ $index + 1 }}</td>
                                        <td>{{ $item->nomor_statistik }}</td>
                                        <td class="fw-bold">{{ $item->nama_majelis }}</td>
                                        <td>{{ $item->alamat }}</td>
                                        <td>{{ ucwords($item->kecamatan->kecamatan ?? '-') }}</td>
                                        <td>{{ $item->kelurahan->nama_kelurahan ?? '-' }}</td>
                                        <td>{{ $item->deleted_at->locale('id')->isoFormat('D MMMM Y HH:mm') }}</td>
                                        <td class="text-center">
                                            <div class="d-flex justify-content-center gap-2">
                                                <!-- Restore Button -->
                                                <form action="{{ route('skt_piagam_mt_v2.restore', $item->uuid) }}"
                                                    method="POST" id="restore-form-{{ $item->uuid }}">
                                                    @csrf
                                                    <button type="button" class="btn btn-soft-success btn-sm"
                                                        onclick="confirmRestore('{{ $item->uuid }}')">
                                                        <i class="fas fa-trash-restore me-1"></i> Pulihkan
                                                    </button>
                                                </form>

                                                <!-- Force Delete Button -->
                                                <form action="{{ route('skt_piagam_mt_v2.force_delete', $item->uuid) }}"
                                                    method="POST" id="force-delete-form-{{ $item->uuid }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" class="btn btn-soft-danger btn-sm"
                                                        onclick="confirmForceDelete('{{ $item->uuid }}')">
                                                        <i class="fas fa-ban me-1"></i> Hapus Permanen
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
        $(document).ready(function() {
            $('#tableTrash').DataTable({
                language: {
                    url: "//cdn.datatables.net/plug-ins/1.10.21/i18n/Indonesian.json"
                }
            });
        });

        // Fungsi konfirmasi restore
        function confirmRestore(id) {
            Swal.fire({
                title: 'Pulihkan data?',
                text: "Data akan dipulihkan dan muncul kembali di daftar aktif!",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#198754',
                cancelButtonColor: '#6c757d',
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
                title: 'Hapus Permanen?',
                text: "Data yang dihapus permanen TIDAK BISA dipulihkan lagi!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
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
