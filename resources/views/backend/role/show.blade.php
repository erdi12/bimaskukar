@extends('layout.app')
@section('title', 'Detail Role')

@section('content')
<div class="main-content container-fluid">
    <div class="page-title">
        <h3>Detail Role: {{ $role->name }}</h3>
    </div>
    
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif
    
    <div class="row">
        <!-- Info Role -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Informasi Role</h4>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label"><strong>Nama Role</strong></label>
                        <p class="form-control-plaintext">{{ $role->name }}</p>
                    </div>

                    <div class="mb-3">
                        <label class="form-label"><strong>Deskripsi</strong></label>
                        <p class="form-control-plaintext">{{ $role->description ?? '-' }}</p>
                    </div>

                    <div class="mb-3">
                        <label class="form-label"><strong>Total User</strong></label>
                        <p class="form-control-plaintext">
                            <span class="badge bg-primary" style="font-size: 16px;">{{ $role->users->count() }}</span>
                        </p>
                    </div>

                    <div class="mb-3 d-flex gap-2">
                        <a href="{{ route('role.edit', $role->id) }}" class="btn btn-warning">
                            <i data-feather="edit"></i> Edit
                        </a>
                        <a href="{{ route('role.index') }}" class="btn btn-secondary">
                            <i data-feather="arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Assign Users to Role -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Assign User ke Role</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('role.assignToUsers', $role->id) }}" method="POST">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="user_ids" class="form-label">Pilih User <span class="text-danger">*</span></label>
                            <select class="form-select @error('user_ids') is-invalid @enderror" 
                                    id="user_ids" name="user_ids[]" multiple required size="8">
                                @foreach($allUsers as $user)
                                    <option value="{{ $user->id }}"
                                        {{ $role->users->contains($user->id) ? 'selected' : '' }}>
                                        {{ $user->name }} ({{ $user->email }})
                                    </option>
                                @endforeach
                            </select>
                            <small class="form-text text-muted">Gunakan Ctrl+Click untuk multi-select</small>
                            @error('user_ids')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <button type="submit" class="btn btn-primary">
                                <i data-feather="save"></i> Assign Users
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Daftar User dengan Role ini -->
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">User dengan Role: {{ $role->name }}</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th width="5%">No</th>
                                    <th>Nama</th>
                                    <th>Email</th>
                                    <th>Dibuat pada</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($role->users as $user)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->created_at->format('d-m-Y H:i') }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-4">Tidak ada user dengan role ini</td>
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
