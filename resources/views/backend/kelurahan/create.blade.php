@extends('layout.app')
@section('title', 'Tambah Kelurahan')

@section('content')
<div class="main-content container-fluid">
    <div class="page-title">
        <h3>Tambah Kelurahan</h3>
    </div>
    
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Form Tambah Kelurahan</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('kelurahan.store') }}" method="POST">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="kecamatan_id" class="form-label">Kecamatan <span class="text-danger">*</span></label>
                            <select class="form-select @error('kecamatan_id') is-invalid @enderror" 
                                    id="kecamatan_id" name="kecamatan_id" required>
                                <option value="">-- Pilih Kecamatan --</option>
                                @foreach($kecamatans as $kecamatan)
                                    <option value="{{ $kecamatan->id }}" 
                                        {{ old('kecamatan_id') == $kecamatan->id ? 'selected' : '' }}>
                                        {{ $kecamatan->kecamatan }}
                                    </option>
                                @endforeach
                            </select>
                            @error('kecamatan_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="kelurahan" class="form-label">Nama Kelurahan <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('nama_kelurahan') is-invalid @enderror" 
                                   id="kelurahan" name="nama_kelurahan" value="{{ old('nama_kelurahan') }}" 
                                   placeholder="Masukkan nama kelurahan" required>
                            @error('nama_kelurahan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="jenis_kelurahan" class="form-label">Jenis Kelurahan <span class="text-danger">*</span></label>
                            <select class="form-select @error('jenis_kelurahan') is-invalid @enderror" 
                                    id="jenis_kelurahan" name="jenis_kelurahan" required>
                                <option value="Desa" {{ old('jenis_kelurahan') == 'Desa' ? 'selected' : '' }}>Desa</option>
                                <option value="Kelurahan" {{ old('jenis_kelurahan') == 'Kelurahan' ? 'selected' : '' }}>Kelurahan</option>
                            </select>
                            @error('jenis_kelurahan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3 d-flex gap-2">
                            <button type="submit" class="btn btn-success">
                                <i data-feather="save"></i> Simpan
                            </button>
                            <a href="{{ route('kelurahan.index') }}" class="btn btn-secondary">
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
