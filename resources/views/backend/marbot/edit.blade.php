@extends('layout.appv2')

@section('title', 'Edit Data Marbot')

@section('content')
    <div class="container-fluid mt-4">
        <div class="row align-items-center mb-4">
            <div class="col-6">
                <h3 class="mb-0 fw-bold text-dark">Edit Data Marbot</h3>
                <p class="text-muted mb-0">Perbaikan data marbot oleh administrator</p>
            </div>
            <div class="col-6 text-end">
                <a href="{{ route('marbot.show', $marbot->uuid) }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i> Kembali
                </a>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-8">
                <div class="card shadow-sm border-0 rounded-3">
                    <div class="card-header bg-warning text-dark py-3">
                        <h5 class="mb-0 fw-bold"><i class="fas fa-edit me-2"></i>Form Edit Data</h5>
                    </div>
                    <div class="card-body p-4">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form id="form-edit-marbot" action="{{ route('marbot.update', $marbot->uuid) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="action" value="edit_data">

                            <h6 class="text-success fw-bold mb-3">Data Pribadi</h6>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="nik" class="form-label">NIK</label>
                                    <input type="number" class="form-control" id="nik" name="nik"
                                        value="{{ old('nik', $marbot->nik) }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="nama_lengkap" class="form-label">Nama Lengkap</label>
                                    <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap"
                                        value="{{ old('nama_lengkap', $marbot->nama_lengkap) }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="tempat_lahir" class="form-label">Tempat Lahir</label>
                                    <input type="text" class="form-control" id="tempat_lahir" name="tempat_lahir"
                                        value="{{ old('tempat_lahir', $marbot->tempat_lahir) }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="tanggal_lahir" class="form-label">Tanggal Lahir</label>
                                    <input type="date" class="form-control datepicker" id="tanggal_lahir"
                                        name="tanggal_lahir"
                                        value="{{ old('tanggal_lahir', $marbot->tanggal_lahir?->format('Y-m-d')) }}"
                                        required>
                                </div>
                                <div class="col-md-6">
                                    <label for="npwp" class="form-label">NPWP <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="npwp" name="npwp"
                                        value="{{ old('npwp', $marbot->npwp) }}" required
                                        placeholder="Contoh: 12.345.678.9-000.000">
                                </div>
                                <div class="col-md-6">
                                    <label for="nomor_rekening" class="form-label">Nomor Rekening <small
                                            class="text-muted">(opsional)</small></label>
                                    <input type="text" class="form-control" id="nomor_rekening" name="nomor_rekening"
                                        value="{{ old('nomor_rekening', $marbot->nomor_rekening) }}">
                                </div>
                                <div class="col-md-6">
                                    <label for="tanggal_mulai_bekerja" class="form-label">Tanggal Mulai Bekerja</label>
                                    <input type="date" class="form-control datepicker" id="tanggal_mulai_bekerja"
                                        name="tanggal_mulai_bekerja"
                                        value="{{ old('tanggal_mulai_bekerja', $marbot->tanggal_mulai_bekerja?->format('Y-m-d')) }}"
                                        required>
                                </div>
                                <div class="col-md-6">
                                    <label for="no_hp" class="form-label">Nomor HP</label>
                                    <input type="number" class="form-control" id="no_hp" name="no_hp"
                                        value="{{ old('no_hp', $marbot->no_hp) }}" required>
                                </div>
                                <div class="col-12">
                                    <label for="alamat" class="form-label">Alamat</label>
                                    <textarea class="form-control" id="alamat" name="alamat" rows="2" required>{{ old('alamat', $marbot->alamat) }}</textarea>
                                </div>
                                <div class="col-md-6">
                                    <label for="kecamatan_id" class="form-label">Kecamatan</label>
                                    <select class="form-select" id="kecamatan_id" name="kecamatan_id" required>
                                        <option value="">Pilih Kecamatan</option>
                                        @foreach ($kecamatans as $kec)
                                            <option value="{{ $kec->id }}"
                                                {{ old('kecamatan_id', $marbot->kecamatan_id) == $kec->id ? 'selected' : '' }}>
                                                {{ $kec->kecamatan }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="kelurahan_id" class="form-label">Kelurahan</label>
                                    <select class="form-select" id="kelurahan_id" name="kelurahan_id" required>
                                        <option value="">Pilih Kelurahan</option>
                                    </select>
                                </div>
                            </div>

                            <hr class="my-4">

                            <h6 class="text-success fw-bold mb-3">Data Rumah Ibadah</h6>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="tipe_rumah_ibadah" class="form-label">Tipe Rumah Ibadah</label>
                                    <select class="form-select" id="tipe_rumah_ibadah" name="tipe_rumah_ibadah" required>
                                        <option value="">Pilih Tipe</option>
                                        <option value="Masjid"
                                            {{ old('tipe_rumah_ibadah', $marbot->tipe_rumah_ibadah) == 'Masjid' ? 'selected' : '' }}>
                                            Masjid</option>
                                        <option value="Mushalla"
                                            {{ old('tipe_rumah_ibadah', $marbot->tipe_rumah_ibadah) == 'Mushalla' ? 'selected' : '' }}>
                                            Mushalla</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="id_rumah_ibadah_input" class="form-label">ID Rumah Ibadah / Nomor
                                        Statistik</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="id_rumah_ibadah_input"
                                            placeholder="Masukkan ID atau Nomor Statistik"
                                            value="{{ $marbot->rumah_ibadah ? ($marbot->tipe_rumah_ibadah == 'Masjid' ? $marbot->rumah_ibadah->nomor_id_masjid : $marbot->rumah_ibadah->nomor_id_mushalla) : '' }}">
                                        <button class="btn btn-outline-success" type="button" id="btn-check-rm">Cek
                                            Data</button>
                                    </div>
                                </div>

                                <input type="hidden" name="rumah_ibadah_id" id="rumah_ibadah_id"
                                    value="{{ old('rumah_ibadah_id', $marbot->rumah_ibadah_id) }}">

                                <div class="col-12" id="detail-rumah-ibadah">
                                    <div class="card bg-light border-0">
                                        <div class="card-body">
                                            <h6 class="card-title fw-bold text-success">Detail Rumah Ibadah</h6>
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <strong>Nama:</strong> <span id="rm-nama">
                                                        @if ($marbot->rumah_ibadah)
                                                            {{ $marbot->tipe_rumah_ibadah == 'Masjid' ? $marbot->rumah_ibadah->nama_masjid : $marbot->rumah_ibadah->nama_mushalla }}
                                                        @else
                                                            -
                                                        @endif
                                                    </span>
                                                </div>
                                                <div class="col-md-4">
                                                    <strong>Kecamatan:</strong> <span id="rm-kecamatan">
                                                        {{ $marbot->rumah_ibadah?->kecamatan?->kecamatan ?? '-' }}
                                                    </span>
                                                </div>
                                                <div class="col-md-4">
                                                    <strong>Kelurahan:</strong> <span id="rm-kelurahan">
                                                        {{ $marbot->rumah_ibadah?->kelurahan?->nama_kelurahan ?? '-' }}
                                                    </span>
                                                </div>
                                                <div class="col-12 mt-2">
                                                    <strong>Alamat:</strong> <span id="rm-alamat">
                                                        @if ($marbot->rumah_ibadah)
                                                            {{ $marbot->tipe_rumah_ibadah == 'Masjid' ? $marbot->rumah_ibadah->alamat_masjid : $marbot->rumah_ibadah->alamat_mushalla }}
                                                        @else
                                                            -
                                                        @endif
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <hr class="my-4">

                            <h6 class="text-success fw-bold mb-3">Upload Berkas (Upload ulang untuk mengganti)</h6>
                            <div class="row g-3">
                                @php
                                    $files = [
                                        'file_ktp' => 'Scan KTP',
                                        'file_kk' => 'Scan Kartu Keluarga (KK)',
                                        'file_sk_marbot' => 'Scan SK Marbot',
                                        'file_permohonan' => 'Surat Permohonan',
                                        'file_pernyataan' => 'Surat Pernyataan',
                                        'file_npwp' => 'Scan NPWP',
                                        'file_buku_rekening' => 'Scan Buku Rekening',
                                    ];
                                @endphp

                                @foreach ($files as $field => $label)
                                    <div class="col-md-6">
                                        <label for="{{ $field }}" class="form-label">{{ $label }}</label>
                                        @if ($marbot->$field)
                                            <div class="mb-2">
                                                <a href="{{ asset('storage/marbot_files/' . $marbot->$field) }}"
                                                    target="_blank" class="badge bg-info text-decoration-none">
                                                    <i class="fas fa-eye me-1"></i> Lihat File Saat Ini
                                                </a>
                                            </div>
                                        @endif
                                        <input class="form-control" type="file" id="{{ $field }}"
                                            name="{{ $field }}" accept=".pdf,.jpg,.jpeg,.png">
                                        <small class="text-muted">Biarkan kosong jika tidak ingin mengubah</small>
                                    </div>
                                @endforeach
                            </div>

                            <div class="d-grid gap-2 mt-5">
                                <button type="submit" class="btn btn-warning btn-lg fw-bold">
                                    <i class="fas fa-save me-2"></i> Simpan Perubahan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <!-- Info Card -->
                <div class="card shadow-sm border-0 rounded-3 mb-4">
                    <div class="card-body">
                        <h6 class="fw-bold text-success mb-3"><i class="fas fa-info-circle me-2"></i>Informasi</h6>
                        <ul class="list-unstyled mb-0 small text-muted">
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Edit data marbot yang sudah
                                disetujui</li>
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i>NIM tidak akan berubah</li>
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Status tetap disetujui</li>
                            <li><i class="fas fa-exclamation-triangle text-warning me-2"></i>Perubahan akan tercatat di
                                audit log</li>
                        </ul>
                    </div>
                </div>

                @if ($marbot->nomor_induk_marbot)
                    <div class="card bg-success text-white shadow-sm border-0 rounded-3">
                        <div class="card-body text-center">
                            <small class="text-uppercase text-white-50">Nomor Induk Marbot</small>
                            <div class="display-6 fw-bold">{{ $marbot->nomor_induk_marbot }}</div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    @push('styles')
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    @endpush

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
        <script src="https://npmcdn.com/flatpickr/dist/l10n/id.js"></script>
        <script>
            $(document).ready(function() {
                // Initialize Flatpickr (Matching Majelis Taklim Style)
                $(".datepicker").flatpickr({
                    dateFormat: "Y-m-d",
                    locale: "id",
                    altInput: true,
                    altFormat: "j F Y",
                    allowInput: true,
                    disableMobile: "true", // Force custom picker on mobile for consistency
                    theme: "material_green" // Ensure clean look
                });
                // Load existing Kelurahan
                var existingKecId = '{{ old('kecamatan_id', $marbot->kecamatan_id) }}';
                var existingKelId = '{{ old('kelurahan_id', $marbot->kelurahan_id) }}';

                function loadKelurahan(kecId, selectedKelId) {
                    if (kecId) {
                        $.get("{{ url('api/kelurahans') }}/" + kecId, function(data) {
                            $('#kelurahan_id').empty();
                            $('#kelurahan_id').append('<option value="">Pilih Kelurahan</option>');
                            $.each(data, function(key, value) {
                                var isSelected = (value.id == selectedKelId) ? 'selected' : '';
                                $('#kelurahan_id').append('<option value="' + value.id + '" ' +
                                    isSelected + '>' +
                                    value.nama_kelurahan + '</option>');
                            });
                        });
                    } else {
                        $('#kelurahan_id').empty();
                        $('#kelurahan_id').append('<option value="">Pilih Kecamatan Terlebih Dahulu</option>');
                    }
                }

                // Initial Load
                if (existingKecId) {
                    loadKelurahan(existingKecId, existingKelId);
                }

                // Fetch Kelurahan based on Kecamatan
                $('#kecamatan_id').change(function() {
                    var kecId = $(this).val();
                    loadKelurahan(kecId, null);
                });

                // Check Rumah Ibadah
                $('#btn-check-rm').click(function() {
                    var type = $('#tipe_rumah_ibadah').val();
                    var id = $('#id_rumah_ibadah_input').val();

                    if (!type || !id) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Data Belum Lengkap',
                            text: 'Pilih Tipe dan masukkan ID Rumah Ibadah!',
                        });
                        return;
                    }

                    $.get('{{ route('check.rumah_ibadah') }}', {
                        type: type,
                        id: id
                    }, function(response) {
                        if (response.status == 'success') {
                            $('#detail-rumah-ibadah').show();
                            $('#rm-nama').text(response.data.nama);
                            $('#rm-alamat').text(response.data.alamat);
                            $('#rm-kecamatan').text(response.data.kecamatan);
                            $('#rm-kelurahan').text(response.data.kelurahan);
                            $('#rumah_ibadah_id').val(response.data.id);
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: response.message,
                            });
                        }
                    });
                });

                // Submit Confirmation
                $('#form-edit-marbot').on('submit', function(e) {
                    e.preventDefault();
                    var form = this;

                    Swal.fire({
                        title: 'Simpan Perubahan?',
                        text: "Data marbot akan diperbarui.",
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#ffc107',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Ya, Simpan!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });
        </script>
    @endpush
@endsection
