@extends('layout.appv2')
@section('aset', 'active')

@section('content')
<div class="container-fluid py-4">
    <div class="mb-4">
        <a href="{{ route('aset.index') }}" class="text-decoration-none"><i class="fas fa-arrow-left me-1"></i> Inventaris</a>
        <h3 class="mt-3 mb-1">Kategori Aset</h3>
        <p class="text-muted mb-0">Kelola kelompok barang, seperti kendaraan, elektronik, atau mebel.</p>
    </div>
    @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
    @if(session('error'))<div class="alert alert-danger">{{ session('error') }}</div>@endif
    @if($errors->any())<div class="alert alert-danger">{{ $errors->first() }}</div>@endif

    <div class="row g-3">
        @if(auth()->user()->hasAnyRole(['Admin','Editor','Operator']))
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm"><div class="card-body">
                    <h5 class="mb-3">Tambah Kategori</h5>
                    <form method="POST" action="{{ route('aset.categories.store') }}">
                        @csrf
                        <div class="mb-3"><label class="form-label">Nama kategori *</label><input name="name" class="form-control" value="{{ old('name') }}" required maxlength="100" placeholder="Contoh: Elektronik"></div>
                        <div class="mb-3"><label class="form-label">Keterangan</label><textarea name="description" class="form-control" rows="3">{{ old('description') }}</textarea></div>
                        <button class="btn btn-primary">Tambah</button>
                    </form>
                </div></div>
            </div>
        @endif

        <div class="col-lg-{{ auth()->user()->hasAnyRole(['Admin','Editor','Operator']) ? '8' : '12' }}">
            <div class="card border-0 shadow-sm">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead><tr><th class="ps-3">Nama kategori</th><th>Keterangan</th><th>Jumlah aset</th>@if(auth()->user()->hasAnyRole(['Admin','Editor','Operator']))<th class="text-end pe-3">Aksi</th>@endif</tr></thead>
                        <tbody>
                            @forelse($categories as $category)
                                <tr>
                                    <td class="ps-3 fw-semibold">{{ $category->name }}</td>
                                    <td>{{ $category->description ?: '-' }}</td>
                                    <td>{{ $category->asets_count }}</td>
                                    @if(auth()->user()->hasAnyRole(['Admin','Editor','Operator']))
                                        <td class="text-end pe-3 text-nowrap">
                                            <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#edit-{{ $category->id }}"><i class="fas fa-pen"></i></button>
                                            <form class="d-inline" method="POST" action="{{ route('aset.categories.destroy', $category) }}" onsubmit="return confirm('Hapus kategori ini?')">
                                                @csrf @method('DELETE')
                                                <button class="btn btn-sm btn-outline-danger" @disabled($category->asets_count > 0) title="Kategori yang masih dipakai tidak dapat dihapus"><i class="fas fa-trash"></i></button>
                                            </form>
                                        </td>
                                    @endif
                                </tr>
                            @empty
                                <tr><td colspan="4" class="text-center py-5 text-muted">Belum ada kategori aset.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @if(auth()->user()->hasAnyRole(['Admin','Editor','Operator']))
        @foreach($categories as $category)
            <div class="modal fade" id="edit-{{ $category->id }}" tabindex="-1">
                <div class="modal-dialog"><div class="modal-content">
                    <form method="POST" action="{{ route('aset.categories.update', $category) }}">
                        @csrf @method('PUT')
                        <div class="modal-header"><h5 class="modal-title">Ubah Kategori</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                        <div class="modal-body">
                            <div class="mb-3"><label class="form-label">Nama kategori</label><input name="name" class="form-control" value="{{ $category->name }}" required maxlength="100"></div>
                            <div><label class="form-label">Keterangan</label><textarea name="description" class="form-control" rows="3">{{ $category->description }}</textarea></div>
                        </div>
                        <div class="modal-footer"><button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button><button class="btn btn-primary">Simpan</button></div>
                    </form>
                </div></div>
            </div>
        @endforeach
    @endif
</div>
@endsection
