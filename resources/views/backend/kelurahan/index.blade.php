@extends('layout.app')
@section('title', 'Data Kelurahan')

@section('content')
<div class="main-content container-fluid">
    <div class="page-title">
        <h3>Data Kelurahan</h3>
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
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title">Daftar Kelurahan</h4>
                    @if(auth()->user()->hasRole('Admin') || auth()->user()->hasRole('Editor'))
                    <a href="{{ route('kelurahan.create') }}" class="btn btn-success">
                        <i data-feather="plus"></i> Tambah Kelurahan
                    </a>
                    @endif
                </div>
                <div class="card-body">
                    <form action="{{ route('kelurahan.index') }}" method="GET" class="mb-3">
                        <div class="row align-items-end">
                            <div class="col-md-4">
                                <label for="kecamatan_filter" class="form-label">Filter Kecamatan</label>
                                <select class="form-select" id="kecamatan_filter" name="kecamatan_filter" onchange="this.form.submit()">
                                    <option value="">-- Semua Kecamatan --</option>
                                    @foreach($kecamatans as $kec)
                                        <option value="{{ $kec->id }}" {{ request('kecamatan_filter') == $kec->id ? 'selected' : '' }}>
                                            {{ $kec->kecamatan }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </form>
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th width="5%">No</th>
                                    <th>Kecamatan</th>
                                    <th>Nama Kelurahan</th>
                                    <th>Jenis</th>
                                    <th width="20%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($kelurahans as $kelurahan)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        <span class="badge bg-info">{{ $kelurahan->kecamatan->kecamatan }}</span>
                                    </td>
                                    <td>{{ $kelurahan->nama_kelurahan }}</td>
                                    <td>
                                        <span class="badge bg-{{ $kelurahan->jenis_kelurahan == 'Desa' ? 'success' : 'primary' }}">
                                            {{ $kelurahan->jenis_kelurahan }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm" role="group">
                                            <a href="{{ route('kelurahan.show', $kelurahan->id) }}" class="btn btn-info">
                                                <i data-feather="eye"></i> Lihat
                                            </a>
                                            @if(auth()->user()->hasRole('Admin') || auth()->user()->hasRole('Editor'))
                                            <a href="{{ route('kelurahan.edit', $kelurahan->id) }}" class="btn btn-warning">
                                                <i data-feather="edit"></i> Edit
                                            </a>
                                            <button type="button" class="btn btn-danger" data-bs-toggle="modal" 
                                                    data-bs-target="#deleteModal{{ $kelurahan->id }}">
                                                <i data-feather="trash"></i> Hapus
                                            </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>

                                <!-- Delete Modal -->
                                <div class="modal fade" id="deleteModal{{ $kelurahan->id }}" tabindex="-1">
                                    <div class="modal-dialog modal-sm">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Konfirmasi Hapus</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                Apakah Anda yakin ingin menghapus data kelurahan <strong>{{ $kelurahan->nama_kelurahan }}</strong>?
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                <form action="{{ route('kelurahan.destroy', $kelurahan->id) }}" method="POST" style="display: inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger">Hapus</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">Tidak ada data kelurahan</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
