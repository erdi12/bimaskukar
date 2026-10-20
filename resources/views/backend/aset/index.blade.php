@extends('layout.appv2')
@section('aset', 'active')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div><h3 class="mb-1">Inventaris Aset</h3><p class="text-muted mb-0">Pendataan aset dan barang milik kantor.</p></div>
        @if(auth()->user()->hasAnyRole(['Admin', 'Editor', 'Operator']))
            <div class="d-flex gap-2"><a class="btn btn-outline-success" href="{{ route('aset.export', request()->query()) }}"><i class="fas fa-file-export me-1"></i> Ekspor CSV</a><a class="btn btn-outline-primary" href="{{ route('aset.categories') }}"><i class="fas fa-tags me-1"></i> Kategori</a><a class="btn btn-primary" href="{{ route('aset.create') }}"><i class="fas fa-plus me-1"></i> Tambah Aset</a></div>
        @endif
    </div>

    @if(session('success'))<div class="alert alert-success alert-dismissible fade show">{{ session('success') }}<button class="btn-close" data-bs-dismiss="alert"></button></div>@endif
    @if(session('error'))<div class="alert alert-danger alert-dismissible fade show">{{ session('error') }}<button class="btn-close" data-bs-dismiss="alert"></button></div>@endif

    <div class="row g-3 mb-4">
        <div class="col-md-4"><div class="card border-0 shadow-sm"><div class="card-body"><div class="text-muted">Jumlah Aset</div><div class="fs-3 fw-semibold">{{ number_format($totalAsets) }}</div></div></div></div>
        <div class="col-md-4"><div class="card border-0 shadow-sm"><div class="card-body"><div class="text-muted">Kondisi Baik</div><div class="fs-3 fw-semibold text-success">{{ number_format($goodAsets) }}</div></div></div></div>
        <div class="col-md-4"><div class="card border-0 shadow-sm"><div class="card-body"><div class="text-muted">Dalam Pemeliharaan</div><div class="fs-3 fw-semibold text-warning">{{ number_format($maintenanceAsets) }}</div></div></div></div>
    </div>

    <div class="card border-0 shadow-sm"><div class="card-body">
        <form method="GET" class="row g-2 mb-3">
            <div class="col-lg-4"><input name="q" class="form-control" value="{{ request('q') }}" placeholder="Cari kode, nama, merek, nomor seri, lokasi"></div>
            <div class="col-lg-2"><select name="category" class="form-select"><option value="">Semua kategori</option>@foreach($categories as $category)<option value="{{ $category->id }}" @selected(request('category') == $category->id)>{{ $category->name }}</option>@endforeach</select></div>
            <div class="col-lg-2"><select name="condition" class="form-select"><option value="">Semua kondisi</option>@foreach($conditions as $condition)<option value="{{ $condition }}" @selected(request('condition') === $condition)>{{ ['baik'=>'Baik','rusak_ringan'=>'Rusak ringan','rusak_berat'=>'Rusak berat'][$condition] }}</option>@endforeach</select></div>
            <div class="col-lg-2"><select name="status" class="form-select"><option value="">Semua status</option>@foreach($statuses as $status)<option value="{{ $status }}" @selected(request('status') === $status)>{{ ['digunakan'=>'Digunakan','tersedia'=>'Tersedia','pemeliharaan'=>'Pemeliharaan','dihapuskan'=>'Dihapuskan'][$status] }}</option>@endforeach</select></div>
            <div class="col-lg-2 d-flex gap-2"><button class="btn btn-outline-primary flex-fill">Filter</button><a class="btn btn-outline-secondary" href="{{ route('aset.index') }}">Reset</a></div>
        </form>
        <div class="table-responsive"><table class="table table-hover align-middle">
            <thead><tr><th>Kode</th><th>Nama Barang</th><th>Kategori</th><th>Lokasi</th><th>Kondisi</th><th>Status</th><th class="text-end">Aksi</th></tr></thead>
            <tbody>
            @forelse($asets as $aset)
                <tr><td class="fw-semibold">{{ $aset->asset_code }}</td><td>{{ $aset->name }}<div class="small text-muted">{{ trim(($aset->brand ?? '').' '.($aset->model ?? '')) ?: '—' }}</div></td><td>{{ $aset->category->name }}</td><td>{{ $aset->location ?: '—' }}</td><td>{{ ['baik'=>'Baik','rusak_ringan'=>'Rusak ringan','rusak_berat'=>'Rusak berat'][$aset->condition] }}</td><td><span class="badge text-bg-{{ $aset->status === 'pemeliharaan' ? 'warning' : ($aset->status === 'dihapuskan' ? 'secondary' : 'success') }}">{{ ['digunakan'=>'Digunakan','tersedia'=>'Tersedia','pemeliharaan'=>'Pemeliharaan','dihapuskan'=>'Dihapuskan'][$aset->status] }}</span></td><td class="text-end text-nowrap"><a href="{{ route('aset.show', $aset) }}" class="btn btn-sm btn-outline-secondary" aria-label="Lihat"><i class="fas fa-eye"></i></a>@if(auth()->user()->hasAnyRole(['Admin','Editor','Operator'])) <a href="{{ route('aset.edit', $aset) }}" class="btn btn-sm btn-outline-primary" aria-label="Edit"><i class="fas fa-pen"></i></a>@endif</td></tr>
            @empty<tr><td colspan="7" class="text-center text-muted py-5">Belum ada data aset. Tambahkan aset pertama untuk mulai mendata inventaris.</td></tr>@endforelse
            </tbody>
        </table></div>
        {{ $asets->links() }}
    </div></div>
</div>
@endsection
