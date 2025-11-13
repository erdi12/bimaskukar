@extends('layout.app')
@section('title', 'Detail Kecamatan')

@section('content')
<div class="main-content container-fluid">
    <div class="page-title">
        <h3>Detail Kecamatan</h3>
    </div>
    
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Detail Kecamatan</h4>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label"><strong>Nama Kecamatan</strong></label>
                        <p class="form-control-plaintext">{{ $kecamatan->kecamatan }}</p>
                    </div>

                    <div class="mb-3">
                        <label class="form-label"><strong>Dibuat pada</strong></label>
                        <p class="form-control-plaintext">{{ $kecamatan->created_at->format('d-m-Y H:i') }}</p>
                    </div>

                    <div class="mb-3">
                        <label class="form-label"><strong>Diperbarui pada</strong></label>
                        <p class="form-control-plaintext">{{ $kecamatan->updated_at->format('d-m-Y H:i') }}</p>
                    </div>

                    <div class="mb-3 d-flex gap-2">
                        <a href="{{ route('kecamatan.edit', $kecamatan->id) }}" class="btn btn-warning">
                            <i data-feather="edit"></i> Edit
                        </a>
                        <a href="{{ route('kecamatan.index') }}" class="btn btn-secondary">
                            <i data-feather="arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
