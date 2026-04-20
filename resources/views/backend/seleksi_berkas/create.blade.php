@extends('layout.appv2')

@section('title', 'Buat Seleksi Berkas')

@push('styles')
<style>
    .field-card { background: #f8f9fa; border: 1px solid #dee2e6; border-radius: 8px; }
    .field-card:hover { border-color: #2A9D8F; }
    .drag-handle { cursor: grab; color: #adb5bd; }
    .drag-handle:active { cursor: grabbing; }
    .btn-remove-field { opacity: 0; transition: opacity .2s; }
    .field-card:hover .btn-remove-field { opacity: 1; }
</style>
@endpush

@section('content')
<div class="container-fluid mt-4">

    <div class="row align-items-center mb-4">
        <div class="col-6">
            <h3 class="mb-0 fw-bold text-dark">Buat Seleksi Berkas</h3>
            <p class="text-muted mb-0">Definisikan formulir dan berkas yang diperlukan</p>
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

    <form action="{{ route('seleksi_berkas.store_admin') }}" method="POST" id="form-seleksi">
        @csrf

        <div class="row g-4">
            <!-- Left: Basic Info -->
            <div class="col-lg-8">

                <!-- Info Dasar -->
                <div class="card shadow-sm border-0 rounded-3 mb-4">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h5 class="fw-bold mb-0 text-success"><i class="fas fa-info-circle me-2"></i>Informasi Dasar</h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Judul Seleksi <span class="text-danger">*</span></label>
                            <input type="text" name="judul" id="input-judul" class="form-control form-control-lg"
                                   value="{{ old('judul') }}" placeholder="Contoh: Seleksi Berkas Marbot 2026" required>
                            <div class="form-text">
                                Slug URL: <code id="preview-slug">/seleksi-berkas/{{ Str::slug(old('judul', 'judul-seleksi')) }}</code>
                            </div>
                        </div>
                        <div class="mb-0">
                            <label class="form-label fw-bold">Deskripsi / Keterangan</label>
                            <textarea name="deskripsi" class="form-control" rows="3"
                                      placeholder="Jelaskan tujuan seleksi berkas ini...">{{ old('deskripsi') }}</textarea>
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
                            Field <strong>Nama Lengkap</strong> dan <strong>Nomor WhatsApp</strong> sudah tersedia secara default. Tambahkan field tambahan di bawah ini.
                        </p>
                        <div id="field-container">
                            <!-- Fields will be dynamically added here -->
                        </div>
                        <div id="field-empty" class="text-center py-3 text-muted small">
                            <i class="fas fa-mouse-pointer me-1"></i>Klik "Tambah Field" untuk menambahkan field inputan tambahan
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
                        <div id="berkas-container">
                            <!-- Berkas items added here -->
                        </div>
                        <div id="berkas-empty" class="text-center py-3 text-muted small">
                            <i class="fas fa-paperclip me-1"></i>Klik "Tambah Berkas" untuk menentukan berkas yang harus diupload
                        </div>
                        <input type="hidden" name="berkas_configs" id="berkas_configs_input">
                    </div>
                </div>

            </div>

            <!-- Right: Settings -->
            <div class="col-lg-4">
                <div class="card shadow-sm border-0 rounded-3 mb-4">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h5 class="fw-bold mb-0 text-success"><i class="fas fa-cog me-2"></i>Pengaturan</h5>
                    </div>
                    <div class="card-body p-4">

                        <div class="mb-3">
                            <label class="form-label fw-bold">Tanggal Buka</label>
                            <input type="date" name="tanggal_buka" class="form-control" value="{{ old('tanggal_buka') }}">
                            <div class="form-text">Kosongkan jika tidak ada batas buka.</div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Tanggal Tutup</label>
                            <input type="date" name="tanggal_tutup" class="form-control" value="{{ old('tanggal_tutup') }}">
                            <div class="form-text">Kosongkan jika tidak ada batas tutup.</div>
                        </div>

                        <div class="form-check form-switch mb-2">
                            <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1"
                                   {{ old('is_active', '1') ? 'checked' : '' }}>
                            <label class="form-check-label fw-bold" for="is_active">Aktifkan Seleksi</label>
                        </div>
                        <div class="form-text mb-4">Jika nonaktif, form tidak bisa diakses publik.</div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-success btn-lg fw-bold">
                                <i class="fas fa-save me-2"></i>Simpan Seleksi
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Preview Info -->
                <div class="card border-0 bg-success bg-opacity-10 rounded-3">
                    <div class="card-body p-4">
                        <h6 class="fw-bold text-success mb-2"><i class="fas fa-lightbulb me-2"></i>Tips</h6>
                        <ul class="small text-muted mb-0 ps-3">
                            <li class="mb-1">Gunakan nama yang jelas & deskriptif</li>
                            <li class="mb-1">Field "Wajib" harus diisi oleh pemohon</li>
                            <li class="mb-1">Setelah submit, pemohon mendapat kode tiket WA</li>
                            <li>Kamu bisa ubah status pengajuan dari menu Pengajuan</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
let fieldIndex = 0;
let berkasIndex = 0;
const fieldContainer = document.getElementById('field-container');
const berkasContainer = document.getElementById('berkas-container');
const fieldEmpty = document.getElementById('field-empty');
const berkasEmpty = document.getElementById('berkas-empty');

// Slug preview
document.getElementById('input-judul').addEventListener('input', function() {
    const slug = this.value.toLowerCase()
        .replace(/[^a-z0-9\s-]/g, '')
        .replace(/\s+/g, '-')
        .replace(/-+/g, '-')
        .replace(/^-+|-+$/g, '');
    document.getElementById('preview-slug').textContent = '/seleksi-berkas/' + (slug || 'judul-seleksi');
});

// Add Field
document.getElementById('btn-add-field').addEventListener('click', function() {
    fieldEmpty.style.display = 'none';
    const idx = fieldIndex++;
    const div = document.createElement('div');
    div.className = 'field-card p-3 mb-2';
    div.dataset.idx = idx;
    div.innerHTML = `
        <div class="row g-2 align-items-center">
            <div class="col-md-4">
                <label class="form-label small fw-bold mb-1">Label Field</label>
                <input type="text" class="form-control form-control-sm field-label" placeholder="Contoh: Nomor KTP" required>
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-bold mb-1">Nama (key)</label>
                <input type="text" class="form-control form-control-sm field-name" placeholder="nomor_ktp" required>
            </div>
                <div class="col-md-2">
                 <label class="form-label small fw-bold mb-1">Tipe</label>
                 <select class="form-select form-select-sm field-type" onchange="onFieldTypeChange(this)">
                     <option value="text">Teks</option>
                     <option value="number">Angka</option>
                     <option value="date">Tanggal</option>
                     <option value="textarea">Textarea</option>
                     <option value="email">Email</option>
                     <option value="select">📌 Select / Dropdown</option>
                     <option value="checkbox">☑️ Checkbox</option>
                     <option value="signature">✍️ Tanda Tangan</option>
                     <option value="kecamatan">🗺️ Kecamatan</option>
                     <option value="kelurahan">🏘️ Kelurahan</option>
                 </select>
             </div>
            <div class="col-md-2">
                <label class="form-label small fw-bold mb-1">Wajib?</label>
                <div class="form-check mt-1">
                    <input class="form-check-input field-required" type="checkbox" checked>
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
    fieldContainer.appendChild(div);

    // Auto-generate key from label
    div.querySelector('.field-label').addEventListener('input', function() {
        div.querySelector('.field-name').value = this.value.toLowerCase()
            .replace(/[^a-z0-9\s_]/g, '')
            .replace(/\s+/g, '_');
    });
});

function onFieldTypeChange(select) {
    const card = select.closest('.field-card');
    let optionsRow = card.querySelector('.checkbox-options-row');
    if (select.value === 'checkbox' || select.value === 'select') {
        if (!optionsRow) {
            optionsRow = document.createElement('div');
            optionsRow.className = 'row g-2 mt-1 checkbox-options-row';
            card.querySelector('.row').insertAdjacentElement('afterend', optionsRow);
        }
        const isSelect = select.value === 'select';
        optionsRow.innerHTML = `
            <div class="col-12">
                <label class="form-label small fw-bold mb-1">
                    ${isSelect ? 'Opsi Dropdown' : 'Opsi Checkbox'}
                    <small class="text-muted fw-normal">(pisahkan dengan koma)</small>
                </label>
                <input type="text" class="form-control form-control-sm field-options"
                       placeholder="${isSelect ? 'Opsi A, Opsi B, Opsi C' : 'Pilihan 1, Pilihan 2, Pilihan 3'}">
            </div>
        `;
        optionsRow.style.display = '';
    } else if (optionsRow) {
        optionsRow.style.display = 'none';
    }
}

function removeField(btn) {
    btn.closest('.field-card').remove();
    if (fieldContainer.children.length === 0) fieldEmpty.style.display = '';
}

// Add Berkas
document.getElementById('btn-add-berkas').addEventListener('click', function() {
    berkasEmpty.style.display = 'none';
    const idx = berkasIndex++;
    const div = document.createElement('div');
    div.className = 'field-card p-3 mb-2';
    div.innerHTML = `
        <div class="row g-2 align-items-center">
            <div class="col-md-5">
                <label class="form-label small fw-bold mb-1">Nama Berkas</label>
                <input type="text" class="form-control form-control-sm berkas-label" placeholder="Contoh: Scan KTP" required>
            </div>
            <div class="col-md-4">
                <label class="form-label small fw-bold mb-1">Nama (key)</label>
                <input type="text" class="form-control form-control-sm berkas-name" placeholder="scan_ktp" required>
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-bold mb-1">Wajib?</label>
                <div class="form-check mt-1">
                    <input class="form-check-input berkas-required" type="checkbox" checked>
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
    berkasContainer.appendChild(div);

    div.querySelector('.berkas-label').addEventListener('input', function() {
        div.querySelector('.berkas-name').value = this.value.toLowerCase()
            .replace(/[^a-z0-9\s_]/g, '')
            .replace(/\s+/g, '_');
    });
});

function removeBerkas(btn) {
    btn.closest('.field-card').remove();
    if (berkasContainer.children.length === 0) berkasEmpty.style.display = '';
}

// Serialize before submit
document.getElementById('form-seleksi').addEventListener('submit', function(e) {
    // Serialize fields
    const fields = [];
    fieldContainer.querySelectorAll('.field-card').forEach(card => {
        const label   = card.querySelector('.field-label').value.trim();
        const name    = card.querySelector('.field-name').value.trim();
        const type    = card.querySelector('.field-type').value;
        const req     = card.querySelector('.field-required').checked;
        const optEl   = card.querySelector('.field-options');
        const options = (optEl && (type === 'checkbox' || type === 'select'))
            ? optEl.value.split(',').map(s => s.trim()).filter(Boolean)
            : [];
        if (label && name) {
            fields.push({ label, name, type, required: req, options });
        }
    });
    document.getElementById('field_configs_input').value = JSON.stringify(fields);

    // Serialize berkas
    const berkas = [];
    berkasContainer.querySelectorAll('.field-card').forEach(card => {
        const label = card.querySelector('.berkas-label').value.trim();
        const name  = card.querySelector('.berkas-name').value.trim();
        const req   = card.querySelector('.berkas-required').checked;
        if (label && name) {
            berkas.push({ label, name, required: req });
        }
    });
    document.getElementById('berkas_configs_input').value = JSON.stringify(berkas);
});
</script>
@endpush
