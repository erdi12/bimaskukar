@extends('layout.appv2')
@section('aset', 'active')

@section('content')
<div class="container-fluid py-4">
    @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
    <div class="d-flex justify-content-between align-items-start mb-4">
        <div>
            <a href="{{ route('aset.index') }}" class="text-decoration-none"><i class="fas fa-arrow-left me-1"></i> Inventaris</a>
            <h3 class="mt-3 mb-1">{{ $aset->name }}</h3>
            <div class="text-muted">{{ $aset->asset_code }} - {{ $aset->category->name }}</div>
        </div>
        @if(auth()->user()->hasAnyRole(['Admin','Editor','Operator']))<a class="btn btn-primary" href="{{ route('aset.edit', $aset) }}"><i class="fas fa-pen me-1"></i> Ubah</a>@endif
    </div>

    <div class="row g-3">
        <div class="col-lg-6"><div class="card border-0 shadow-sm h-100"><div class="card-body">
            <h5>Informasi Barang</h5>
            <dl class="row mt-3">
                <dt class="col-sm-5">Merek / tipe</dt><dd class="col-sm-7">{{ trim(($aset->brand ?? '').' '.($aset->model ?? '')) ?: '-' }}</dd>
                <dt class="col-sm-5">Nomor seri</dt><dd class="col-sm-7">{{ $aset->serial_number ?: '-' }}</dd>
                <dt class="col-sm-5">Kondisi</dt><dd class="col-sm-7">{{ ['baik'=>'Baik','rusak_ringan'=>'Rusak ringan','rusak_berat'=>'Rusak berat'][$aset->condition] }}</dd>
                <dt class="col-sm-5">Status</dt><dd class="col-sm-7">{{ ['digunakan'=>'Digunakan','tersedia'=>'Tersedia','pemeliharaan'=>'Pemeliharaan','dihapuskan'=>'Dihapuskan'][$aset->status] }}</dd>
                <dt class="col-sm-5">Lokasi</dt><dd class="col-sm-7">{{ $aset->location ?: '-' }}</dd>
                <dt class="col-sm-5">Penanggung jawab</dt><dd class="col-sm-7">{{ $aset->responsible_person ?: '-' }}</dd>
            </dl>
        </div></div></div>

        <div class="col-lg-6"><div class="card border-0 shadow-sm h-100"><div class="card-body">
            <h5>Perolehan dan Spesifikasi</h5>
            <dl class="row mt-3">
                <dt class="col-sm-5">Tanggal perolehan</dt><dd class="col-sm-7">{{ $aset->acquisition_date?->format('d/m/Y') ?: '-' }}</dd>
                <dt class="col-sm-5">Sumber</dt><dd class="col-sm-7">{{ $aset->acquisition_source ?: '-' }}</dd>
                <dt class="col-sm-5">Nilai</dt><dd class="col-sm-7">{{ $aset->acquisition_value ? 'Rp '.number_format((float)$aset->acquisition_value, 0, ',', '.') : '-' }}</dd>
            </dl>
            @if($aset->specifications)<hr><h6>Spesifikasi tambahan</h6><dl class="row">@foreach($aset->specifications as $key => $value)<dt class="col-sm-5">{{ $key }}</dt><dd class="col-sm-7">{{ $value }}</dd>@endforeach</dl>@endif
        </div></div></div>

        @if($aset->notes)<div class="col-12"><div class="card border-0 shadow-sm"><div class="card-body"><h5>Catatan</h5><p class="mb-0">{{ $aset->notes }}</p></div></div></div>@endif

        <div class="col-12"><div class="card border-0 shadow-sm"><div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div><h5 class="mb-1">Dokumen Barang</h5><div class="small text-muted">{{ $aset->documents->count() }} dokumen tersimpan privat</div></div>
                @if(auth()->user()->hasAnyRole(['Admin','Editor','Operator']))<a href="{{ route('aset.edit', $aset) }}" class="btn btn-sm btn-outline-primary"><i class="fas fa-upload me-1"></i> Unggah dokumen</a>@endif
            </div>
            @forelse($aset->documents as $document)
                <div class="d-flex flex-wrap justify-content-between align-items-center border rounded p-3 mb-2 gap-2">
                    <div><div class="fw-semibold">{{ $document->original_name }}</div><div class="small text-muted">{{ number_format($document->file_size / 1024, 0) }} KB - Diunggah {{ $document->created_at->format('d/m/Y H:i') }}</div></div>
                    <div class="d-flex gap-2">
                        @if(in_array($document->mime_type, ['application/pdf', 'image/jpeg', 'image/png'], true))
                            <a class="btn btn-sm btn-outline-primary" href="{{ route('aset.documents.preview', [$aset, $document]) }}" target="_blank" rel="noopener"><i class="fas fa-eye me-1"></i> Preview</a>
                        @endif
                        <a class="btn btn-sm btn-outline-secondary" href="{{ route('aset.documents.download', [$aset, $document]) }}"><i class="fas fa-download me-1"></i> Unduh</a>
                        @if(auth()->user()->hasAnyRole(['Admin','Editor','Operator']))
                            <form method="POST" action="{{ route('aset.documents.destroy', [$aset, $document]) }}" onsubmit="return confirm('Hapus dokumen ini?')">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button></form>
                        @endif
                    </div>
                </div>
            @empty
                <div class="text-muted py-3">Belum ada dokumen untuk barang ini.</div>
            @endforelse
        </div></div></div>
    </div>
</div>
@endsection
