@extends('layout.app')
@section('title', 'Surat Keterangan Terdaftar Majelis Ta\'lim')
@section('sub-layanan', 'active')
@section('layanan', 'active')
@section('skt-mt', 'active')

@push('css')
<style>
    .table td {
        white-space: nowrap;
        max-width: 200px;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .sticky-column {
        position: sticky;
        left: 0;
        background-color: #fff;
        z-index: 1;
    }
</style>
@endpush

@section('content')
<div class="main-content container-fluid">
    <div class="page-title">
        <h3>Surat Keterangan Terdaftar</h3>
    </div>
    
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
    
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Surat Keterangan Terdaftar</h4>
                </div>
                <div class="card-body">
                    {{-- Tombol Aksi --}}
                    <div class="row">
                        <div class="col-12">
                            <div class="d-flex flex-wrap justify-content-center justify-content-md-end gap-2 mb-3">
                                @if(auth()->user()->hasRole('Admin') || auth()->user()->hasRole('Editor') || auth()->user()->hasRole('Operator'))
                                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#importExcelModal">
                                    <i data-feather="file-text"></i> <span class="d-none d-sm-inline">Import Excel</span>
                                </button>
                                <a href="{{ route('skt_piagam_mt.export') }}" class="btn btn-info">
                                    <i data-feather="download"></i> <span class="d-none d-sm-inline">Export Excel</span>
                                </a>
                                <a href="{{ route('skt_piagam_mt.create') }}" class="btn btn-primary">
                                    <i data-feather="plus"></i> <span class="d-none d-sm-inline">Tambah Majelis Ta'lim</span>
                                </a>
                                @endif
                                <a href="{{ route('skt_piagam_mt.rekap') }}" class="btn btn-info">
                                    <i data-feather="file"></i> <span class="d-none d-sm-inline">Rekapan Data</span>
                                </a>
                            </div>
                        </div>
                    </div>

                    {{-- Filter Data --}}
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title">Filter Data</h5>
                                </div>
                                <div class="card-body">
                                    <form action="{{ route('skt_piagam_mt.index') }}" method="GET">
                                        <div class="row">
                                            {{-- Filter Kecamatan --}}
                                            <div class="col-md-5">
                                                <div class="form-group">
                                                    <label for="kecamatan_filter">Kecamatan</label>
                                                    <select class="form-select" id="kecamatan_filter" name="kecamatan_id" onchange="getKelurahan()">
                                                        <option value="">Semua Kecamatan</option>
                                                        @foreach($kecamatans as $kecamatan)
                                                            <option value="{{ $kecamatan->id }}" {{ request('kecamatan_id') == $kecamatan->id ? 'selected' : '' }}>
                                                                {{ ucwords($kecamatan->kecamatan) }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            {{-- Filter Kelurahan --}}
                                            <div class="col-md-5">
                                                <div class="form-group">
                                                    <label for="kelurahan_filter">Kelurahan</label>
                                                    <select class="form-select" id="kelurahan_filter" name="kelurahan_id">
                                                        <option value="">Semua Kelurahan</option>
                                                        @foreach($kelurahans as $kelurahan)
                                                            <option value="{{ $kelurahan->id }}" {{ request('kelurahan_id') == $kelurahan->id ? 'selected' : '' }}>
                                                                {{ $kelurahan->nama_kelurahan }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            {{-- Tombol Filter dan Reset --}}
                                            <div class="col-md-2 d-flex align-items-end">
                                                <div class="form-group">
                                                    <button type="submit" class="btn btn-primary me-2">
                                                        <i data-feather="filter"></i> Filter
                                                    </button>
                                                    <a href="{{ route('skt_piagam_mt.index') }}" class="btn btn-secondary">
                                                        <i data-feather="refresh-cw"></i> Reset
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Tabel Data --}}
                    <div class="table-responsive">
                        <table class="table table-striped table-hover table-bordered text-nowrap" id="table1">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nomor Statistik MT</th>
                                    <th class="sticky-column">Nama Majelis Ta'lim</th>
                                    <th>Alamat</th>
                                    <th>Status</th>
                                    <th>Ketua</th>
                                    <th>No. HP</th>
                                    <th>Mendaftar</th>
                                    <th>Daftar Ulang</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($sktpiagammts as $key => $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item->nomor_statistik }}</td>
                                        <td class="sticky-column">{{ $item->nama_majelis }}</td>
                                        <td>
                                            {{ $item->alamat }}, {{ $item->kelurahan->jenis_kelurahan == 'Kelurahan' ? 'Kel.' : 'Desa' }} {{ $item->kelurahan->nama_kelurahan }}, Kec. {{ ucwords($item->kecamatan->kecamatan) }}
                                        </td>
                                        <td class="text-center">
                                            @if($item->mendaftar_ulang && \Carbon\Carbon::today()->gte(\Carbon\Carbon::parse($item->mendaftar_ulang)))
                                                <span class="badge bg-warning">Belum Update</span>
                                            @elseif($item->status == 'aktif')
                                                <span class="badge bg-success">Aktif</span>
                                            @elseif($item->status == 'nonaktif')
                                                <span class="badge bg-danger">Non-Aktif</span>
                                            @else
                                                <span class="badge bg-warning">Belum Update</span>
                                            @endif
                                        </td>
                                        <td>{{ $item->ketua }}</td>
                                        <td>{{ $item->no_hp }}</td>
                                        <td>{{ $item->mendaftar }}</td>
                                        <td>{{ $item->mendaftar_ulang }}</td>
                                        <td class="text-wrap">
                                            <div class="btn-group btn-group-sm mb-2" role="group">
                                                <a href="{{ route('skt_piagam_mt.cetak_skt', $item->id) }}" 
                                                   class="btn btn-success d-inline-flex align-items-center" 
                                                   target="_blank">
                                                    <i class="fa-regular fa-file-lines me-1"></i> Cetak SKT
                                                </a>

                                                <a href="{{ route('skt_piagam_mt.cetak_piagam', $item->id) }}" class="btn btn-warning d-inline-flex align-items-center" target="_blank">
                                                    <i class="fa-regular fa-file-lines me-1"></i> Cetak Piagam
                                                </a>
                                            </div>
                                            <div class="btn-group btn-group-sm" role="group">
                                                @if(auth()->user()->hasRole('Admin') || auth()->user()->hasRole('Editor') || auth()->user()->hasRole('Operator'))
                                                <a href="{{ route('skt_piagam_mt.show', $item->id) }}" class="btn btn-info d-inline-flex align-items-center">
                                                    <i class="fa-regular fa-eye me-1"></i> Lihat
                                                </a>
                                                <a href="{{ route('skt_piagam_mt.edit', $item->id) }}" class="btn btn-success d-inline-flex align-items-center">
                                                    <i class="fa-regular fa-pen-to-square me-1"></i> Edit
                                                </a>
                                                @endif
                                                @if(auth()->user()->hasRole('Admin') || auth()->user()->hasRole('Operator'))
                                                <button type="button" class="btn btn-danger d-inline-flex align-items-center" onclick="confirmDelete({{ $item->id }})">
                                                    <i class="fa-regular fa-trash-can me-1"></i> Hapus
                                                </button>
                                                @endif
                                            </div>
                                        </td>

                                        {{-- Kolom SKT dan Piagam --}}

                                    </tr>

                                    {{-- Modal Upload SKT --}}
                                    <div class="modal fade" id="uploadSktModal{{ $item->id }}" tabindex="-1" aria-labelledby="uploadSktModalLabel{{ $item->id }}" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="uploadSktModalLabel{{ $item->id }}">Upload Surat Keterangan Terdaftar</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <form action="{{ route('skt_piagam_mt.upload_skt') }}" method="POST" enctype="multipart/form-data">
                                                    @csrf
                                                    <input type="hidden" name="skt_id" value="{{ $item->id }}">
                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <label for="skt_file{{ $item->id }}" class="form-label">Pilih File SKT</label>
                                                            <input type="file" class="form-control" id="skt_file{{ $item->id }}" name="skt_file" accept=".pdf" required>
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

                                    {{-- Modal Upload Piagam --}}
                                    <div class="modal fade" id="uploadPiagamModal{{ $item->id }}" tabindex="-1" aria-labelledby="uploadPiagamModalLabel{{ $item->id }}" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="uploadPiagamModalLabel{{ $item->id }}">Upload Piagam</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <form action="{{ route('skt_piagam_mt.upload_piagam') }}" method="POST" enctype="multipart/form-data">
                                                    @csrf
                                                    <input type="hidden" name="piagam_id" value="{{ $item->id }}">
                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <label for="piagam_file{{ $item->id }}" class="form-label">Pilih File Piagam</label>
                                                            <input type="file" class="form-control" id="piagam_file{{ $item->id }}" name="piagam_file" accept=".pdf" required>
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

                                    {{-- Modal Upload Berkas --}}
                                    <div class="modal fade" id="uploadBerkasModal{{ $item->id }}" tabindex="-1" aria-labelledby="uploadBerkasModalLabel{{ $item->id }}" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="uploadBerkasModalLabel{{ $item->id }}">Upload Berkas</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <form action="{{ route('skt_piagam_mt.upload_berkas') }}" method="POST" enctype="multipart/form-data">
                                                    @csrf
                                                    <input type="hidden" name="berkas_id" value="{{ $item->id }}">
                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <label for="berkas_file{{ $item->id }}" class="form-label">Pilih Berkas</label>
                                                            <input type="file" class="form-control" id="berkas_file{{ $item->id }}" name="berkas_file" accept=".pdf" required>
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


                                @empty
                                    <tr>
                                        <td colspan="14" class="text-center">Tidak ada data</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination --}}
                    {{-- <div class="d-flex justify-content-center mt-3">
                        {{ $sktpiagammts->links() }}
                    </div> --}}
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Import Excel -->
<div class="modal fade" id="importExcelModal" tabindex="-1" aria-labelledby="importExcelModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="importExcelModalLabel">Import Data dari Excel</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('skt_piagam_mt.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="excel_file" class="form-label">Pilih File Excel</label>
                        <input type="file" class="form-control" id="excel_file" name="excel_file" accept=".xlsx, .xls, .csv" required>
                        <div class="form-text">Format yang didukung: .xlsx, .xls, .csv</div>
                    </div>
                    <div class="alert alert-info">
                        <i data-feather="info" class="me-1"></i> Pastikan format Excel sesuai dengan template yang ditentukan.
                        <a href="{{ route('skt_piagam_mt.template') }}" class="alert-link">Download template</a>
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

<!-- Form untuk delete dengan ID dinamis -->
@foreach($sktpiagammts as $item)
<form id="delete-form-{{ $item->id }}" action="/skt_piagam_mt/{{ $item->id }}" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>
<form id="delete-skt-form-{{ $item->id }}" action="/skt-piagam-mt/delete-skt/{{ $item->id }}" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>
<form id="delete-piagam-form-{{ $item->id }}" action="/skt-piagam-mt/delete-piagam/{{ $item->id }}" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>
<form id="delete-berkas-form-{{ $item->id }}" action="/skt-piagam-mt/delete-berkas/{{ $item->id }}" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>
@endforeach
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
    
    // Inisialisasi Select2 untuk dropdown filter
    $(document).ready(function() {
        $('#kecamatan_filter').select2({
            placeholder: "Pilih Kecamatan",
            allowClear: true
        });
        
        $('#kelurahan_filter').select2({
            placeholder: "Pilih Kelurahan",
            allowClear: true
        });
    });
    
    // Fungsi untuk mendapatkan kelurahan berdasarkan kecamatan
    function getKelurahan() {
        const kecamatanId = document.getElementById('kecamatan_filter').value;
        const kelurahanSelect = document.getElementById('kelurahan_filter');
        
        // Reset dropdown kelurahan
        kelurahanSelect.innerHTML = '<option value="">Semua Kelurahan</option>';
        
        if (kecamatanId) {
            fetch(`/api/kelurahans/${kecamatanId}`)
                .then(response => response.json())
                .then(data => {
                    data.forEach(kelurahan => {
                        const option = document.createElement('option');
                        option.value = kelurahan.id;
                        option.textContent = kelurahan.nama_kelurahan;
                        kelurahanSelect.appendChild(option);
                    });
                    
                    // Reinisialisasi Select2 setelah mengupdate opsi
                    $('#kelurahan_filter').trigger('change');
                })
                .catch(error => console.error('Error:', error));
        }
    }
    
    // Fungsi konfirmasi hapus
    function confirmDelete(id) {
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Data yang dihapus akan masuk ke trash dan dapat dipulihkan kembali!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById(`delete-form-${id}`).submit();
            }
        });
    }
    
    // Fungsi konfirmasi hapus file SKT
    function confirmDeleteSkt(id) {
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "File SKT akan dihapus secara permanen!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById(`delete-skt-form-${id}`).submit();
            }
        });
    }
    
    // Fungsi konfirmasi hapus file Piagam
    function confirmDeletePiagam(id) {
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "File Piagam akan dihapus secara permanen!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById(`delete-piagam-form-${id}`).submit();
            }
        });
    }
    
    // Fungsi konfirmasi hapus file Berkas
    function confirmDeleteBerkas(id) {
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "File Berkas akan dihapus secara permanen!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById(`delete-berkas-form-${id}`).submit();
            }
        });
    }
    
    // Fungsi cetak SKT dengan tipe wilayah
    // function cetakSkt(id) {
    //     const tipeKelurahan = document.getElementById(`tipeKelurahan${id}`).checked;
    //     const tipeDesa = document.getElementById(`tipeDesa${id}`).checked;
    //     let tipeWilayah = 'kelurahan';
        
    //     if (tipeDesa) {
    //         tipeWilayah = 'desa';
    //     }
        
    //     // Simpan URL yang akan dibuka di tab baru
    //     const url = `/skt_piagam_mt/${id}/cetak-skt?tipe=${tipeWilayah}`;
        
    //     // Dapatkan referensi modal
    //     const modalElement = document.getElementById(`wilayahModal${id}`);
        
    //     // Tambahkan event listener untuk mendeteksi saat modal selesai ditutup
    //     $(modalElement).on('hidden.bs.modal', function (e) {
    //         // Buka tab baru setelah modal benar-benar tertutup
    //         window.open(url, '_blank');
    //         // Hapus event listener setelah digunakan
    //         $(modalElement).off('hidden.bs.modal');
    //     });
        
    //     // Tutup modal menggunakan jQuery (lebih konsisten)
    //     $(modalElement).modal('hide');
        
    //     // Fallback jika event tidak terpicu
    //     setTimeout(() => {
    //         if ($(modalElement).hasClass('show')) {
    //             $(modalElement).modal('hide');
    //             window.open(url, '_blank');
    //         }
    //     }, 300);
        
    //     // Mencegah event default
    //     return false;
    // }

// function cetakSkt(id) {
//     // Dapatkan referensi elemen
//     const modalElement = document.getElementById(`wilayahModal${id}`);
//     const tipeDesa = document.getElementById(`tipeDesa${id}`);
    
//     // Tentukan tipe wilayah
//     const tipeWilayah = tipeDesa && tipeDesa.checked ? 'desa' : 'kelurahan';
//     const url = `/skt_piagam_mt/${id}/cetak-skt?tipe=${tipeWilayah}`;
    
//     let tabOpened = false;
    
//     // Fungsi untuk membersihkan modal dan backdrop
//     const cleanupModal = () => {
//         // Hapus semua backdrop
//         $('.modal-backdrop').remove();
//         // Reset body
//         $('body').removeClass('modal-open').css('padding-right', '');
//         // Reset modal
//         $(modalElement).removeClass('show').attr('style', '');
//     };
    
//     // Fungsi untuk membuka tab baru
//     const openNewTab = () => {
//         if (!tabOpened) {
//             tabOpened = true;
//             window.open(url, '_blank');
//         }
//     };
    
//     // Event listener untuk modal tertutup
//     $(modalElement).one('hidden.bs.modal', function() {
//         cleanupModal();
//         openNewTab();
//     });
    
//     // Tutup modal
//     $(modalElement).modal('hide');
    
//     // Fallback jika modal tidak menutup dengan benar
//     setTimeout(() => {
//         if ($(modalElement).hasClass('show') || $('.modal-backdrop').length) {
//             cleanupModal();
//             openNewTab();
//         }
//     }, 500);
    
//     return false;
// }

// function cetakSkt(id) {
//     const modalElement = document.getElementById(`wilayahModal${id}`);
//     const tipeDesa = document.getElementById(`tipeDesa${id}`);
//     const tipeWilayah = tipeDesa && tipeDesa.checked ? 'desa' : 'kelurahan';
//     const url = `/skt_piagam_mt/${id}/cetak-skt?tipe=${tipeWilayah}`;

//     let tabOpened = false;

//     // Fungsi membersihkan manual khusus Bootstrap 5.0.0
//     const cleanupModal = () => {
//         document.body.classList.remove('modal-open');
//         document.body.style.overflow = 'auto';
//         document.documentElement.style.overflow = 'auto';
//         document.body.style.paddingRight = '';
//         // Hapus semua backdrop sisa
//         document.querySelectorAll('.modal-backdrop').forEach(el => el.remove());
//         // Reset properti modal agar bisa dibuka ulang
//         modalElement.classList.remove('show');
//         modalElement.style.display = '';
//         modalElement.setAttribute('aria-hidden', 'true');
//     };

//     const openNewTab = () => {
//         if (!tabOpened) {
//             tabOpened = true;
//             window.open(url, '_blank');
//         }
//     };

//     // Tutup modal manual dulu
//     const modalInstance = bootstrap.Modal.getInstance(modalElement) 
//         || new bootstrap.Modal(modalElement);
//     modalInstance.hide();

//     // Tunggu sedikit biar animasi selesai, baru bersihkan & buka tab
//     setTimeout(() => {
//         cleanupModal();
//         openNewTab();
//     }, 400);
// }

</script>
@endpush
