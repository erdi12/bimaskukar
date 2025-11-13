@extends('layout.app')
@section('title', 'Detail Kelurahan')

@section('content')
<div class="main-content container-fluid">
    <div class="page-title">
        <h3>Detail Kelurahan</h3>
    </div>
    
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Detail Kelurahan</h4>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label"><strong>Kecamatan</strong></label>
                        <p class="form-control-plaintext">
                            <span class="badge bg-info">{{ $kelurahan->kecamatan->kecamatan }}</span>
                        </p>
                    </div>

                    <div class="mb-3">
                        <label class="form-label"><strong>Nama Kelurahan</strong></label>
                        <p class="form-control-plaintext">{{ $kelurahan->nama_kelurahan }}</p>
                    </div>

                    <div class="mb-3">
                        <label class="form-label"><strong>Dibuat pada</strong></label>
                        <p class="form-control-plaintext">{{ $kelurahan->created_at->format('d-m-Y H:i') }}</p>
                    </div>

                    <div class="mb-3">
                        <label class="form-label"><strong>Diperbarui pada</strong></label>
                        <p class="form-control-plaintext">{{ $kelurahan->updated_at->format('d-m-Y H:i') }}</p>
                    </div>

                    <div class="mb-3 d-flex gap-2">
                        <a href="{{ route('kelurahan.edit', $kelurahan->id) }}" class="btn btn-warning">
                            <i data-feather="edit"></i> Edit
                        </a>
                        <a href="{{ route('kelurahan.index') }}" class="btn btn-secondary">
                            <i data-feather="arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
