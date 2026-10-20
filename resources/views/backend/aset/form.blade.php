@extends('layout.appv2')
@section('aset', 'active')

@section('content')
<div class="container-fluid py-4">
    <div class="mb-4"><a href="{{ route('aset.index') }}" class="text-decoration-none"><i class="fas fa-arrow-left me-1"></i> Kembali ke inventaris</a><h3 class="mt-3 mb-1">{{ $aset->exists ? 'Ubah Data Aset' : 'Tambah Aset' }}</h3><p class="text-muted">Catat setiap unit barang sebagai satu aset. Spesifikasi tambahan bisa disesuaikan dengan jenis barang.</p></div>
    @if($categories->isEmpty())<div class="alert alert-warning">Tambahkan kategori terlebih dahulu di <a href="{{ route('aset.categories') }}">halaman kategori</a>.</div>@endif
    <form method="POST" action="{{ $aset->exists ? route('aset.update', $aset) : route('aset.store') }}">
        @csrf @if($aset->exists) @method('PUT') @endif
        <div class="card border-0 shadow-sm mb-3"><div class="card-body"><h5 class="mb-3">Identitas Barang</h5><div class="row g-3">
            <div class="col-md-4"><label class="form-label">Kategori *</label><select name="aset_category_id" class="form-select" required><option value="">Pilih kategori</option>@foreach($categories as $category)<option value="{{ $category->id }}" @selected(old('aset_category_id', $aset->aset_category_id) == $category->id)>{{ $category->name }}</option>@endforeach</select>@error('aset_category_id')<div class="text-danger small">{{ $message }}</div>@enderror</div>
            <div class="col-md-4"><label class="form-label">Kode Inventaris *</label><input name="asset_code" class="form-control" value="{{ old('asset_code', $aset->asset_code) }}" placeholder="Contoh: INV-001" required>@error('asset_code')<div class="text-danger small">{{ $message }}</div>@enderror</div>
            <div class="col-md-4"><label class="form-label">Nama Barang *</label><input name="name" class="form-control" value="{{ old('name', $aset->name) }}" placeholder="Contoh: Sepeda Motor" required>@error('name')<div class="text-danger small">{{ $message }}</div>@enderror</div>
            <div class="col-md-4"><label class="form-label">Merek</label><input name="brand" class="form-control" value="{{ old('brand', $aset->brand) }}"></div><div class="col-md-4"><label class="form-label">Tipe / Model</label><input name="model" class="form-control" value="{{ old('model', $aset->model) }}"></div><div class="col-md-4"><label class="form-label">Nomor Seri</label><input name="serial_number" class="form-control" value="{{ old('serial_number', $aset->serial_number) }}"></div>
        </div></div></div>
        <div class="card border-0 shadow-sm mb-3"><div class="card-body"><h5 class="mb-3">Perolehan dan Penempatan</h5><div class="row g-3">
            <div class="col-md-4"><label class="form-label">Tanggal Perolehan</label><input type="date" name="acquisition_date" class="form-control" value="{{ old('acquisition_date', optional($aset->acquisition_date)->format('Y-m-d')) }}"></div><div class="col-md-4"><label class="form-label">Sumber Perolehan</label><input name="acquisition_source" class="form-control" value="{{ old('acquisition_source', $aset->acquisition_source) }}" placeholder="Contoh: DIPA / hibah"></div><div class="col-md-4"><label class="form-label">Nilai Perolehan (Rp)</label><input type="number" min="0" step="0.01" name="acquisition_value" class="form-control" value="{{ old('acquisition_value', $aset->acquisition_value) }}"></div>
            <div class="col-md-4"><label class="form-label">Lokasi</label><input name="location" class="form-control" value="{{ old('location', $aset->location) }}" placeholder="Contoh: Ruang Tata Usaha"></div><div class="col-md-4"><label class="form-label">Penanggung Jawab</label><input name="responsible_person" class="form-control" value="{{ old('responsible_person', $aset->responsible_person) }}"></div>
            <div class="col-md-4"><label class="form-label">Kondisi *</label><select name="condition" class="form-select" required>@foreach($conditions as $condition)<option value="{{ $condition }}" @selected(old('condition', $aset->condition ?: 'baik') === $condition)>{{ ['baik'=>'Baik','rusak_ringan'=>'Rusak ringan','rusak_berat'=>'Rusak berat'][$condition] }}</option>@endforeach</select></div>
            <div class="col-md-4"><label class="form-label">Status *</label><select name="status" class="form-select" required>@foreach($statuses as $status)<option value="{{ $status }}" @selected(old('status', $aset->status ?: 'digunakan') === $status)>{{ ['digunakan'=>'Digunakan','tersedia'=>'Tersedia','pemeliharaan'=>'Pemeliharaan','dihapuskan'=>'Dihapuskan'][$status] }}</option>@endforeach</select></div>
        </div></div></div>
        <div class="card border-0 shadow-sm mb-3"><div class="card-body"><div class="d-flex justify-content-between align-items-center mb-2"><div><h5 class="mb-1">Spesifikasi Tambahan</h5><div class="small text-muted">Misalnya nomor polisi untuk motor atau kapasitas untuk printer.</div></div><button type="button" id="add-spec" class="btn btn-sm btn-outline-primary"><i class="fas fa-plus me-1"></i>Tambah baris</button></div><div id="spec-list">
            @php($specs = old('specifications', collect($aset->specifications ?? [])->map(fn($value, $key) => ['key' => $key, 'value' => $value])->values()->all()))
            @foreach($specs as $i => $spec)<div class="row g-2 mb-2 spec-row"><div class="col-md-5"><input name="specifications[{{ $i }}][key]" class="form-control" placeholder="Nama spesifikasi" value="{{ $spec['key'] ?? '' }}"></div><div class="col-md-6"><input name="specifications[{{ $i }}][value]" class="form-control" placeholder="Nilai" value="{{ $spec['value'] ?? '' }}"></div><div class="col-md-1"><button type="button" class="btn btn-outline-danger remove-spec" aria-label="Hapus baris"><i class="fas fa-times"></i></button></div></div>@endforeach
        </div></div></div>
        <div class="card border-0 shadow-sm mb-3"><div class="card-body"><label class="form-label">Catatan</label><textarea name="notes" rows="3" class="form-control">{{ old('notes', $aset->notes) }}</textarea></div></div>
        <div class="d-flex justify-content-end gap-2 mb-4"><a href="{{ route('aset.index') }}" class="btn btn-light">Batal</a><button class="btn btn-primary" @disabled($categories->isEmpty())>Simpan Aset</button></div>
    </form>
</div>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const list = document.getElementById('spec-list');
    document.getElementById('add-spec').addEventListener('click', () => {
        const index = list.querySelectorAll('.spec-row').length;
        list.insertAdjacentHTML('beforeend', `<div class="row g-2 mb-2 spec-row"><div class="col-md-5"><input name="specifications[${index}][key]" class="form-control" placeholder="Nama spesifikasi"></div><div class="col-md-6"><input name="specifications[${index}][value]" class="form-control" placeholder="Nilai"></div><div class="col-md-1"><button type="button" class="btn btn-outline-danger remove-spec" aria-label="Hapus baris"><i class="fas fa-times"></i></button></div></div>`);
    });
    list.addEventListener('click', event => { if (event.target.closest('.remove-spec')) event.target.closest('.spec-row').remove(); });
});
</script>
@endsection
