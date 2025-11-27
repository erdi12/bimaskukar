@extends('layout.app')
@section('title', 'Data Kecamatan')

@section('content')
<div class="main-content container-fluid">
    <div class="page-title">
        <h3>Data Kecamatan</h3>
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
                    <h4 class="card-title">Daftar Kecamatan</h4>
                    @if(auth()->user()->hasRole('Admin') || auth()->user()->hasRole('Editor'))
                    <a href="{{ route('kecamatan.create') }}" class="btn btn-success">
                        <i data-feather="plus"></i> Tambah Kecamatan
                    </a>
                    @endif
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th width="5%">No</th>
                                    <th>Nama Kecamatan</th>
                                    <th width="20%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($kecamatans as $kecamatan)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $kecamatan->kecamatan }}</td>
                                    <td>
                                        <div class="btn-group btn-group-sm" role="group">
                                            <a href="{{ route('kecamatan.show', $kecamatan->id) }}" class="btn btn-info">
                                                <i data-feather="eye"></i> Lihat
                                            </a>
                                            @if(auth()->user()->hasRole('Admin') || auth()->user()->hasRole('Editor'))
                                            <a href="{{ route('kecamatan.edit', $kecamatan->id) }}" class="btn btn-warning">
                                                <i data-feather="edit"></i> Edit
                                            </a>
                                            <button type="button" class="btn btn-danger" data-bs-toggle="modal" 
                                                    data-bs-target="#deleteModal{{ $kecamatan->id }}">
                                                <i data-feather="trash"></i> Hapus
                                            </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>

                                <!-- Delete Modal -->
                                <div class="modal fade" id="deleteModal{{ $kecamatan->id }}" tabindex="-1">
                                    <div class="modal-dialog modal-sm">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Konfirmasi Hapus</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                Apakah Anda yakin ingin menghapus data kecamatan <strong>{{ $kecamatan->kecamatan }}</strong>?
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                <form action="{{ route('kecamatan.destroy', $kecamatan->id) }}" method="POST" style="display: inline;">
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
                                    <td colspan="3" class="text-center text-muted py-4">Tidak ada data kecamatan</td>
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
