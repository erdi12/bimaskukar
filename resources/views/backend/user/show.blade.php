@extends('layout.app')
@section('title', 'Detail User')

@section('content')
<div class="main-content container-fluid">
    <div class="page-title">
        <h3>Detail User</h3>
    </div>
    
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Informasi User</h4>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label"><strong>Nama</strong></label>
                        <p class="form-control-plaintext">{{ $user->name }}</p>
                    </div>

                    <div class="mb-3">
                        <label class="form-label"><strong>Email</strong></label>
                        <p class="form-control-plaintext">{{ $user->email }}</p>
                    </div>

                    <div class="mb-3">
                        <label class="form-label"><strong>Role</strong></label>
                        <p class="form-control-plaintext">
                            @forelse($user->roles as $role)
                                <span class="badge bg-primary">{{ $role->name }}</span>
                            @empty
                                <span class="badge bg-secondary">Tidak Ada Role</span>
                            @endforelse
                        </p>
                    </div>

                    <div class="mb-3">
                        <label class="form-label"><strong>Dibuat pada</strong></label>
                        <p class="form-control-plaintext">{{ $user->created_at->format('d-m-Y H:i') }}</p>
                    </div>

                    <div class="mb-3">
                        <label class="form-label"><strong>Diperbarui pada</strong></label>
                        <p class="form-control-plaintext">{{ $user->updated_at->format('d-m-Y H:i') }}</p>
                    </div>

                    <div class="mb-3 d-flex gap-2">
                        <a href="{{ route('user.edit', $user->id) }}" class="btn btn-warning">
                            <i data-feather="edit"></i> Edit
                        </a>
                        <a href="{{ route('user.index') }}" class="btn btn-secondary">
                            <i data-feather="arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
