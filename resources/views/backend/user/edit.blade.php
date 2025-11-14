@extends('layout.app')
@section('title', 'Edit User')

@section('content')
<div class="main-content container-fluid">
    <div class="page-title">
        <h3>Edit User</h3>
    </div>
    
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Form Edit User</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('user.update', $user->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label for="name" class="form-label">Nama <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name', $user->name) }}" 
                                   placeholder="Masukkan nama user" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                   id="email" name="email" value="{{ old('email', $user->email) }}" 
                                   placeholder="Masukkan email user" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Password <span class="text-muted">(Kosongkan jika tidak ingin mengubah)</span></label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                   id="password" name="password" 
                                   placeholder="Masukkan password baru (minimal 8 karakter)">
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                            <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror" 
                                   id="password_confirmation" name="password_confirmation" 
                                   placeholder="Konfirmasi password baru">
                            @error('password_confirmation')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="roles" class="form-label">Role</label>
                            <select class="form-select @error('roles') is-invalid @enderror" 
                                    id="roles" name="roles[]" multiple size="5">
                                @foreach($roles as $role)
                                    <option value="{{ $role->id }}"
                                        {{ $user->roles->contains($role->id) ? 'selected' : '' }}>
                                        {{ $role->name }}
                                    </option>
                                @endforeach
                            </select>
                            <small class="form-text text-muted">Gunakan Ctrl+Click untuk multi-select</small>
                            @error('roles')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3 d-flex gap-2">
                            <button type="submit" class="btn btn-success">
                                <i data-feather="save"></i> Perbarui
                            </button>
                            <a href="{{ route('user.index') }}" class="btn btn-secondary">
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
