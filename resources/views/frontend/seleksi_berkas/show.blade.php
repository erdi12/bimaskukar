@extends('layout.frontend')

@section('title', $seleksi->judul . ' - Bimas Kemenag Kukar')

@push('styles')
<style>
    .card:hover { transform: none !important; box-shadow: 0 .125rem .25rem rgba(0,0,0,.075) !important; }
    .card:hover h5 { color: white !important; }

    .upload-zone {
        border: 2px dashed #dee2e6;
        border-radius: 12px;
        padding: 20px;
        text-align: center;
        cursor: pointer;
        transition: all .3s ease;
        background: #fafafa;
    }
    .upload-zone:hover, .upload-zone.dragover {
        border-color: var(--primary);
        background: rgba(42, 157, 143, 0.05);
    }
    .upload-zone .file-icon { font-size: 2rem; color: #adb5bd; }
    .upload-zone.has-file { border-color: var(--primary); background: rgba(42, 157, 143, 0.05); }
    .upload-zone.has-file .file-icon { color: var(--primary); }
    .step-badge {
        width: 32px; height: 32px;
        background: var(--primary);
        color: white;
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-weight: 700; font-size: 14px;
        flex-shrink: 0;
    }
</style>
@endpush

@section('content')
<div class="container py-5 mt-4">

    <!-- Header -->
    <div class="row justify-content-center mb-4">
        <div class="col-md-10">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="card-body p-0">
                    <div class="bg-success text-white p-4">
                        <div class="d-flex align-items-start gap-3">
                            <div class="bg-white bg-opacity-20 rounded-3 p-3">
                                <i class="fas fa-folder-open fa-2x"></i>
                            </div>
                            <div>
                                <h4 class="fw-bold mb-1">{{ $seleksi->judul }}</h4>
                                @if($seleksi->deskripsi)
                                    <p class="mb-0 text-white-50">{{ $seleksi->deskripsi }}</p>
                                @endif
                                @if($seleksi->tanggal_tutup)
                                    <div class="mt-2">
                                        <span class="badge bg-white text-success rounded-pill px-3">
                                            <i class="fas fa-calendar-times me-1"></i>
                                            Tutup: {{ $seleksi->tanggal_tutup->translatedFormat('d F Y') }}
                                        </span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Steps Guide -->
                    <div class="p-4 bg-light border-bottom">
                        <div class="d-flex align-items-center gap-3 flex-wrap">
                            <div class="d-flex align-items-center gap-2">
                                <div class="step-badge">1</div>
                                <small class="fw-semibold text-muted">Isi Data</small>
                            </div>
                            <i class="fas fa-chevron-right text-muted small"></i>
                            <div class="d-flex align-items-center gap-2">
                                <div class="step-badge">2</div>
                                <small class="fw-semibold text-muted">Upload Berkas</small>
                            </div>
                            <i class="fas fa-chevron-right text-muted small"></i>
                            <div class="d-flex align-items-center gap-2">
                                <div class="step-badge">3</div>
                                <small class="fw-semibold text-muted">Terima Kode Tiket WA</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-10">

            @if($errors->any())
                <div class="alert alert-danger border-0 shadow-sm rounded-3 mb-4">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>Terdapat kesalahan:</strong>
                    <ul class="mb-0 mt-2">
                        @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
                    </ul>
                </div>
            @endif

            <form id="form-seleksi-berkas"
                  action="{{ route('seleksi_berkas.store', $seleksi->slug) }}"
                  method="POST"
                  enctype="multipart/form-data">
                @csrf

                <!-- Identitas Pengaju -->
                <div class="card border-0 shadow-sm rounded-4 mb-4">
                    <div class="card-header bg-white py-3 border-bottom rounded-top-4">
                        <h5 class="mb-0 fw-bold text-success">
                            <i class="fas fa-user me-2"></i>Identitas Pengaju
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Nama Lengkap <span class="text-danger">*</span></label>
                                <input type="text" name="nama_pengaju" class="form-control"
                                       value="{{ old('nama_pengaju') }}" placeholder="Masukkan nama lengkap" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">
                                    Nomor WhatsApp Aktif <span class="text-danger">*</span>
                                    <span class="badge bg-success ms-1 small">Tiket akan dikirim ke sini</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fab fa-whatsapp text-success"></i></span>
                                    <input type="text" name="no_hp" class="form-control"
                                           value="{{ old('no_hp') }}" placeholder="Contoh: 081234567890" required>
                                </div>
                            </div>

                            @php $fieldConfigs = $seleksi->field_configs ?? []; @endphp
                            @foreach($fieldConfigs as $field)
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">
                                        {{ $field['label'] }}
                                        @if(!empty($field['required']))<span class="text-danger">*</span>@endif
                                    </label>
                                    @if(($field['type'] ?? 'text') === 'textarea')
                                        <textarea name="field_{{ $field['name'] }}" class="form-control" rows="3"
                                                  {{ !empty($field['required']) ? 'required' : '' }}>{{ old('field_' . $field['name']) }}</textarea>
                                    @elseif(($field['type'] ?? 'text') === 'date')
                                        <input type="date" name="field_{{ $field['name'] }}" class="form-control"
                                               value="{{ old('field_' . $field['name']) }}"
                                               {{ !empty($field['required']) ? 'required' : '' }}>
                                    @elseif(($field['type'] ?? 'text') === 'kecamatan')
                                        <select name="field_{{ $field['name'] }}"
                                                class="form-select field-kecamatan"
                                                id="kec_{{ $field['name'] }}"
                                                data-field="{{ $field['name'] }}"
                                                {{ !empty($field['required']) ? 'required' : '' }}>
                                            <option value="">-- Pilih Kecamatan --</option>
                                            @foreach($kecamatans as $kec)
                                                <option value="{{ $kec->id }}"
                                                    {{ old('field_' . $field['name']) == $kec->id ? 'selected' : '' }}>
                                                    {{ $kec->kecamatan }}
                                                </option>
                                            @endforeach
                                        </select>
                                    @elseif(($field['type'] ?? 'text') === 'kelurahan')
                                        <select name="field_{{ $field['name'] }}"
                                                class="form-select field-kelurahan"
                                                id="kel_{{ $field['name'] }}"
                                                {{ !empty($field['required']) ? 'required' : '' }}
                                                disabled>
                                            <option value="">-- Pilih Kecamatan Dulu --</option>
                                        </select>
                                        <div class="form-text text-muted">
                                            <i class="fas fa-info-circle me-1"></i>Pilih kecamatan terlebih dahulu
                                        </div>
                                    @else
                                        <input type="{{ $field['type'] ?? 'text' }}" name="field_{{ $field['name'] }}"
                                               class="form-control"
                                               value="{{ old('field_' . $field['name']) }}"
                                               {{ !empty($field['required']) ? 'required' : '' }}>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Upload Berkas -->
                @php $berkasConfigs = $seleksi->berkas_configs ?? []; @endphp
                @if(count($berkasConfigs) > 0)
                    <div class="card border-0 shadow-sm rounded-4 mb-4">
                        <div class="card-header bg-white py-3 border-bottom">
                            <h5 class="mb-0 fw-bold text-success">
                                <i class="fas fa-paperclip me-2"></i>Upload Berkas Pendukung
                            </h5>
                            <small class="text-muted">Format yang diterima: PDF, JPG, JPEG, PNG (Maks. 5MB per file)</small>
                        </div>
                        <div class="card-body p-4">
                            <div class="row g-4">
                                @foreach($berkasConfigs as $berkas)
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">
                                            <i class="fas fa-file-alt text-success me-1"></i>
                                            {{ $berkas['label'] }}
                                            @if(!empty($berkas['required']))
                                                <span class="text-danger">*</span>
                                            @else
                                                <span class="badge bg-secondary ms-1" style="font-size:10px;">Opsional</span>
                                            @endif
                                        </label>
                                        <div class="upload-zone" id="zone-{{ $berkas['name'] }}" onclick="document.getElementById('berkas_{{ $berkas['name'] }}').click()">
                                            <div class="file-icon mb-2"><i class="fas fa-cloud-upload-alt"></i></div>
                                            <div class="fw-semibold text-muted" id="label-{{ $berkas['name'] }}">Klik atau drag file ke sini</div>
                                            <small class="text-muted">PDF, JPG, PNG — Maks. 5MB</small>
                                        </div>
                                        <input type="file" id="berkas_{{ $berkas['name'] }}"
                                               name="berkas_{{ $berkas['name'] }}"
                                               accept=".pdf,.jpg,.jpeg,.png"
                                               class="d-none berkas-input"
                                               data-zone="zone-{{ $berkas['name'] }}"
                                               data-label="label-{{ $berkas['name'] }}"
                                               {{ !empty($berkas['required']) ? 'required' : '' }}>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Submit -->
                <div class="card border-0 shadow-sm rounded-4 mb-4 bg-success text-white">
                    <div class="card-body p-4 d-flex align-items-center gap-4 flex-wrap">
                        <div class="flex-grow-1">
                            <h5 class="fw-bold mb-1">Siap Mengajukan?</h5>
                            <p class="mb-0 text-white-50 small">Pastikan semua data sudah benar dan berkas sudah diunggah sebelum mengirim.</p>
                        </div>
                        <button type="submit" class="btn btn-light text-success fw-bold px-4 py-2 rounded-pill" id="btn-submit">
                            <i class="fas fa-paper-plane me-2"></i>Kirim Pengajuan
                        </button>
                    </div>
                </div>
            </form>

            <!-- Cek Tiket Link -->
            <div class="text-center text-muted small mb-5">
                Sudah punya kode tiket sebelumnya?
                <a href="{{ route('cek_tiket') }}" class="text-success fw-bold">Cek Status Berkas</a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// ==========================================
// Cascading Kecamatan → Kelurahan
// ==========================================
document.querySelectorAll('.field-kecamatan').forEach(function(kecSelect) {
    kecSelect.addEventListener('change', function() {
        const kecId = this.value;

        // Cari semua field-kelurahan di form yang sama
        document.querySelectorAll('.field-kelurahan').forEach(function(kelSelect) {
            kelSelect.innerHTML = '<option value="">-- Memuat... --</option>';
            kelSelect.disabled = true;

            if (!kecId) {
                kelSelect.innerHTML = '<option value="">-- Pilih Kecamatan Dulu --</option>';
                return;
            }

            fetch("{{ url('api/kelurahans') }}/" + kecId)
                .then(res => res.json())
                .then(data => {
                    kelSelect.innerHTML = '<option value="">-- Pilih Kelurahan --</option>';
                    data.forEach(function(kel) {
                        const opt = document.createElement('option');
                        opt.value = kel.id;
                        opt.textContent = kel.nama_kelurahan;
                        kelSelect.appendChild(opt);
                    });
                    kelSelect.disabled = false;
                })
                .catch(() => {
                    kelSelect.innerHTML = '<option value="">Gagal memuat. Coba lagi.</option>';
                });
        });
    });
});

// ==========================================
// Upload zone file preview
// ==========================================
document.querySelectorAll('.berkas-input').forEach(input => {
    const zone  = document.getElementById(input.dataset.zone);
    const label = document.getElementById(input.dataset.label);

    input.addEventListener('change', function() {
        if (this.files.length > 0) {
            const file = this.files[0];
            if (file.size > 5 * 1024 * 1024) {
                Swal.fire({ icon: 'warning', title: 'File Terlalu Besar', text: 'Ukuran file maksimal 5MB.' });
                this.value = '';
                return;
            }
            zone.classList.add('has-file');
            zone.querySelector('.file-icon i').className = 'fas fa-file-check text-success';
            label.innerHTML = '<span class="text-success fw-bold"><i class="fas fa-check-circle me-1"></i>' + file.name + '</span>';
        }
    });

    // Drag & drop
    zone.addEventListener('dragover', e => { e.preventDefault(); zone.classList.add('dragover'); });
    zone.addEventListener('dragleave', () => zone.classList.remove('dragover'));
    zone.addEventListener('drop', e => {
        e.preventDefault();
        zone.classList.remove('dragover');
        if (e.dataTransfer.files.length > 0) {
            const dt = new DataTransfer();
            dt.items.add(e.dataTransfer.files[0]);
            input.files = dt.files;
            input.dispatchEvent(new Event('change'));
        }
    });
});

// ==========================================
// Submit confirmation
// ==========================================
document.getElementById('form-seleksi-berkas').addEventListener('submit', function(e) {
    e.preventDefault();
    const form = this;
    Swal.fire({
        title: 'Kirim Pengajuan?',
        html: 'Kode tiket akan dikirimkan ke nomor WhatsApp yang Anda masukkan.<br><small class="text-muted">Pastikan nomor WA aktif dan benar.</small>',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#2A9D8F',
        cancelButtonColor: '#6c757d',
        confirmButtonText: '<i class="fas fa-paper-plane me-1"></i>Ya, Kirim!',
        cancelButtonText: 'Periksa Lagi'
    }).then(result => {
        if (result.isConfirmed) {
            Swal.fire({
                title: 'Mengunggah Berkas...',
                text: 'Mohon tunggu, proses mungkin memerlukan beberapa saat.',
                allowOutsideClick: false,
                showConfirmButton: false,
                willOpen: () => Swal.showLoading()
            });
            form.submit();
        }
    });
});
</script>
@endpush

