@extends('layout.app')
@section('title', 'Tambah Kecamatan')

@section('content')
<div class="main-content container-fluid">
    <div class="page-title">
        <h3>Tambah Kecamatan</h3>
    </div>
    
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Form Tambah Kecamatan</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('kecamatan.store') }}" method="POST">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="kecamatan" class="form-label">Nama Kecamatan <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('kecamatan') is-invalid @enderror" 
                                   id="kecamatan" name="kecamatan" value="{{ old('kecamatan') }}" 
                                   placeholder="Masukkan nama kecamatan" required>
                            @error('kecamatan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3 d-flex gap-2">
                            <button type="submit" class="btn btn-success">
                                <i data-feather="save"></i> Simpan
                            </button>
                            <a href="{{ route('kecamatan.index') }}" class="btn btn-secondary">
                                <i data-feather="arrow-left"></i> Kembali
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
