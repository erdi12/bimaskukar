@extends('layout.app')
@section('title', 'Tambah Role')

@section('content')
<div class="main-content container-fluid">
    <div class="page-title">
        <h3>Tambah Role</h3>
    </div>
    
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Form Tambah Role</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('role.store') }}" method="POST">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="name" class="form-label">Nama Role <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name') }}" 
                                   placeholder="Contoh: Admin, Editor, Viewer" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Deskripsi</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="4"
                                      placeholder="Deskripsi role (opsional)">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3 d-flex gap-2">
                            <button type="submit" class="btn btn-success">
                                <i data-feather="save"></i> Simpan
                            </button>
                            <a href="{{ route('role.index') }}" class="btn btn-secondary">
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
