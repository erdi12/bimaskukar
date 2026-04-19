@extends('layout.appv2')

@section('title', 'Edit Seleksi Berkas')

@push('styles')
<style>
    .field-card { background: #f8f9fa; border: 1px solid #dee2e6; border-radius: 8px; }
    .field-card:hover { border-color: #2A9D8F; }
    .btn-remove-field { opacity: 0; transition: opacity .2s; }
    .field-card:hover .btn-remove-field { opacity: 1; }
</style>
@endpush

@section('content')
<div class="container-fluid mt-4">

    <div class="row align-items-center mb-4">
        <div class="col-6">
            <h3 class="mb-0 fw-bold text-dark">Edit Seleksi Berkas</h3>
            <p class="text-muted mb-0">{{ $seleksi->judul }}</p>
        </div>
        <div class="col-6 text-end">
            <a href="{{ route('seleksi_berkas.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>Kembali
            </a>
        </div>
    </div>

    @if($errors->any())
        <div class="alert alert-danger border-0 shadow-sm">
            <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
    @endif

    <form action="{{ route('seleksi_berkas.update', $seleksi) }}" method="POST" id="form-seleksi">
        @csrf @method('PUT')

        <div class="row g-4">
            <div class="col-lg-8">

                <div class="card shadow-sm border-0 rounded-3 mb-4">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h5 class="fw-bold mb-0 text-success"><i class="fas fa-info-circle me-2"></i>Informasi Dasar</h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Judul Seleksi <span class="text-danger">*</span></label>
                            <input type="text" name="judul" id="input-judul" class="form-control form-control-lg"
                                   value="{{ old('judul', $seleksi->judul) }}" required>
                            <div class="form-text">
                                Slug saat ini: <code>/seleksi-berkas/{{ $seleksi->slug }}</code>
                            </div>
                        </div>
                        <div class="mb-0">
                            <label class="form-label fw-bold">Deskripsi</label>
                            <textarea name="deskripsi" class="form-control" rows="3">{{ old('deskripsi', $seleksi->deskripsi) }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Field Builder -->
                <div class="card shadow-sm border-0 rounded-3 mb-4">
                    <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                        <h5 class="fw-bold mb-0 text-success"><i class="fas fa-list-ul me-2"></i>Field Inputan</h5>
                        <button type="button" class="btn btn-success btn-sm" id="btn-add-field">
                            <i class="fas fa-plus me-1"></i>Tambah Field
                        </button>
                    </div>
                    <div class="card-body p-4">
                        <p class="text-muted small mb-3">
                            <i class="fas fa-info-circle me-1"></i>
                            Field <strong>Nama Lengkap</strong> dan <strong>Nomor WhatsApp</strong> sudah tersedia secara default.
                        </p>
                        <div id="field-container"></div>
                        <div id="field-empty" class="text-center py-3 text-muted small" style="{{ count($seleksi->field_configs ?? []) ? 'display:none' : '' }}">
                            <i class="fas fa-mouse-pointer me-1"></i>Klik "Tambah Field" untuk menambahkan field tambahan
                        </div>
                        <input type="hidden" name="field_configs" id="field_configs_input">
                    </div>
                </div>

                <!-- Berkas Builder -->
                <div class="card shadow-sm border-0 rounded-3 mb-4">
                    <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                        <h5 class="fw-bold mb-0 text-success"><i class="fas fa-paperclip me-2"></i>Berkas yang Diperlukan</h5>
                        <button type="button" class="btn btn-success btn-sm" id="btn-add-berkas">
                            <i class="fas fa-plus me-1"></i>Tambah Berkas
                        </button>
                    </div>
                    <div class="card-body p-4">
                        <div id="berkas-container"></div>
                        <div id="berkas-empty" class="text-center py-3 text-muted small" style="{{ count($seleksi->berkas_configs ?? []) ? 'display:none' : '' }}">
                            <i class="fas fa-paperclip me-1"></i>Klik "Tambah Berkas"
                        </div>
                        <input type="hidden" name="berkas_configs" id="berkas_configs_input">
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card shadow-sm border-0 rounded-3 mb-4">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h5 class="fw-bold mb-0 text-success"><i class="fas fa-cog me-2"></i>Pengaturan</h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Tanggal Buka</label>
                            <input type="date" name="tanggal_buka" class="form-control"
                                   value="{{ old('tanggal_buka', $seleksi->tanggal_buka?->format('Y-m-d')) }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Tanggal Tutup</label>
                            <input type="date" name="tanggal_tutup" class="form-control"
                                   value="{{ old('tanggal_tutup', $seleksi->tanggal_tutup?->format('Y-m-d')) }}">
                        </div>
                        <div class="form-check form-switch mb-4">
                            <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1"
                                   {{ old('is_active', $seleksi->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label fw-bold" for="is_active">Aktifkan Seleksi</label>
                        </div>
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-success btn-lg fw-bold">
                                <i class="fas fa-save me-2"></i>Simpan Perubahan
                            </button>
                            <a href="{{ route('seleksi_berkas.pengajuan', $seleksi) }}" class="btn btn-outline-primary">
                                <i class="fas fa-list me-2"></i>Lihat Pengajuan ({{ $seleksi->pengajuans()->count() }})
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
// Existing data from server
const existingFields  = @json($seleksi->field_configs ?? []);
const existingBerkas  = @json($seleksi->berkas_configs ?? []);

let fieldIndex = existingFields.length;
let berkasIndex = existingBerkas.length;
const fieldContainer  = document.getElementById('field-container');
const berkasContainer = document.getElementById('berkas-container');
const fieldEmpty      = document.getElementById('field-empty');
const berkasEmpty     = document.getElementById('berkas-empty');

function buildFieldCard(field, existing = false) {
    const div = document.createElement('div');
    div.className = 'field-card p-3 mb-2';
    div.innerHTML = `
        <div class="row g-2 align-items-center">
            <div class="col-md-4">
                <label class="form-label small fw-bold mb-1">Label Field</label>
                <input type="text" class="form-control form-control-sm field-label" value="${field.label || ''}" placeholder="Contoh: Nomor KTP" required>
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-bold mb-1">Nama (key)</label>
                <input type="text" class="form-control form-control-sm field-name" value="${field.name || ''}" placeholder="nomor_ktp" required>
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-bold mb-1">Tipe</label>
                <select class="form-select form-select-sm field-type">
                    <option value="text" ${(field.type||'text')==='text'?'selected':''}>Teks</option>
                    <option value="number" ${field.type==='number'?'selected':''}>Angka</option>
                    <option value="date" ${field.type==='date'?'selected':''}>Tanggal</option>
                    <option value="textarea" ${field.type==='textarea'?'selected':''}>Textarea</option>
                    <option value="email" ${field.type==='email'?'selected':''}>Email</option>
                    <option value="kecamatan" ${field.type==='kecamatan'?'selected':''}>🗺️ Kecamatan</option>
                    <option value="kelurahan" ${field.type==='kelurahan'?'selected':''}>🏘️ Kelurahan</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-bold mb-1">Wajib?</label>
                <div class="form-check mt-1">
                    <input class="form-check-input field-required" type="checkbox" ${field.required ? 'checked' : ''}>
                    <label class="form-check-label small">Wajib</label>
                </div>
            </div>
            <div class="col-md-1 text-end">
                <button type="button" class="btn btn-sm btn-outline-danger btn-remove-field mt-3" onclick="removeField(this)">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
    `;
    div.querySelector('.field-label').addEventListener('input', function() {
        div.querySelector('.field-name').value = this.value.toLowerCase().replace(/[^a-z0-9\s_]/g,'').replace(/\s+/g,'_');
    });
    return div;
}

function buildBerkasCard(berkas) {
    const div = document.createElement('div');
    div.className = 'field-card p-3 mb-2';
    div.innerHTML = `
        <div class="row g-2 align-items-center">
            <div class="col-md-5">
                <label class="form-label small fw-bold mb-1">Nama Berkas</label>
                <input type="text" class="form-control form-control-sm berkas-label" value="${berkas.label || ''}" placeholder="Contoh: Scan KTP" required>
            </div>
            <div class="col-md-4">
                <label class="form-label small fw-bold mb-1">Nama (key)</label>
                <input type="text" class="form-control form-control-sm berkas-name" value="${berkas.name || ''}" placeholder="scan_ktp" required>
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-bold mb-1">Wajib?</label>
                <div class="form-check mt-1">
                    <input class="form-check-input berkas-required" type="checkbox" ${berkas.required ? 'checked' : ''}>
                    <label class="form-check-label small">Wajib</label>
                </div>
            </div>
            <div class="col-md-1 text-end">
                <button type="button" class="btn btn-sm btn-outline-danger btn-remove-field mt-3" onclick="removeBerkas(this)">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
    `;
    div.querySelector('.berkas-label').addEventListener('input', function() {
        div.querySelector('.berkas-name').value = this.value.toLowerCase().replace(/[^a-z0-9\s_]/g,'').replace(/\s+/g,'_');
    });
    return div;
}

// Load existing fields & berkas
existingFields.forEach(f => fieldContainer.appendChild(buildFieldCard(f, true)));
existingBerkas.forEach(b => berkasContainer.appendChild(buildBerkasCard(b)));

document.getElementById('btn-add-field').addEventListener('click', function() {
    fieldEmpty.style.display = 'none';
    fieldContainer.appendChild(buildFieldCard({}));
});

document.getElementById('btn-add-berkas').addEventListener('click', function() {
    berkasEmpty.style.display = 'none';
    berkasContainer.appendChild(buildBerkasCard({}));
});

function removeField(btn) {
    btn.closest('.field-card').remove();
    if (fieldContainer.children.length === 0) fieldEmpty.style.display = '';
}

function removeBerkas(btn) {
    btn.closest('.field-card').remove();
    if (berkasContainer.children.length === 0) berkasEmpty.style.display = '';
}

document.getElementById('form-seleksi').addEventListener('submit', function() {
    const fields = [];
    fieldContainer.querySelectorAll('.field-card').forEach(card => {
        const label = card.querySelector('.field-label').value.trim();
        const name  = card.querySelector('.field-name').value.trim();
        const type  = card.querySelector('.field-type').value;
        const req   = card.querySelector('.field-required').checked;
        if (label && name) fields.push({ label, name, type, required: req });
    });
    document.getElementById('field_configs_input').value = JSON.stringify(fields);

    const berkas = [];
    berkasContainer.querySelectorAll('.field-card').forEach(card => {
        const label = card.querySelector('.berkas-label').value.trim();
        const name  = card.querySelector('.berkas-name').value.trim();
        const req   = card.querySelector('.berkas-required').checked;
        if (label && name) berkas.push({ label, name, required: req });
    });
    document.getElementById('berkas_configs_input').value = JSON.stringify(berkas);
});
</script>
@endpush
