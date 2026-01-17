@extends('layout.appv2')

@section('title', 'Tambah Data Marbot')

@section('content')
    <div class="container-fluid mt-4">
        <div class="row align-items-center mb-4">
            <div class="col-6">
                <h3 class="mb-0 fw-bold text-dark">Tambah Data Marbot</h3>
                <p class="text-muted mb-0">Input data marbot baru oleh admin/operator (Data Titipan)</p>
            </div>
            <div class="col-6 text-end">
                <a href="{{ route('marbot.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i> Kembali
                </a>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-8">
                <div class="card shadow-sm border-0 rounded-3">
                    <div class="card-header bg-success text-white py-3">
                        <h5 class="mb-0 fw-bold"><i class="fas fa-plus-circle me-2"></i>Form Tambah Data</h5>
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

                        <form id="form-create-marbot" action="{{ route('marbot.store') }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf

                            <!-- Hidden input to indicate admin entry if needed in future logic, though controller handles it -->

                            <h6 class="text-success fw-bold mb-3">Data Pribadi</h6>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="nik" class="form-label">NIK <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" id="nik" name="nik"
                                        value="{{ old('nik') }}" required placeholder="16 digit NIK">
                                </div>
                                <div class="col-md-6">
                                    <label for="nama_lengkap" class="form-label">Nama Lengkap <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap"
                                        value="{{ old('nama_lengkap') }}" required placeholder="Nama Sesuai KTP">
                                </div>
                                <div class="col-md-6">
                                    <label for="tempat_lahir" class="form-label">Tempat Lahir <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="tempat_lahir" name="tempat_lahir"
                                        value="{{ old('tempat_lahir') }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="tanggal_lahir" class="form-label">Tanggal Lahir <span
                                            class="text-danger">*</span></label>
                                    <input type="date" class="form-control datepicker" id="tanggal_lahir"
                                        name="tanggal_lahir" value="{{ old('tanggal_lahir') }}" required
                                        placeholder="Pilih Tanggal">
                                </div>
                                <div class="col-md-6">
                                    <label for="npwp" class="form-label">NPWP <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="npwp" name="npwp"
                                        value="{{ old('npwp') }}" placeholder="Contoh: 12.345.678.9-000.000" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="tanggal_mulai_bekerja" class="form-label">Tanggal Mulai Bekerja <span
                                            class="text-danger">*</span></label>
                                    <input type="date" class="form-control datepicker" id="tanggal_mulai_bekerja"
                                        name="tanggal_mulai_bekerja" value="{{ old('tanggal_mulai_bekerja') }}" required
                                        placeholder="Pilih Tanggal">
                                </div>
                                <div class="col-md-6">
                                    <label for="no_hp" class="form-label">Nomor HP <span
                                            class="text-danger">*</span></label>
                                    <input type="number" class="form-control" id="no_hp" name="no_hp"
                                        value="{{ old('no_hp') }}" required placeholder="08xxxxxxxxxx">
                                </div>
                                <div class="col-12">
                                    <label for="alamat" class="form-label">Alamat <span
                                            class="text-danger">*</span></label>
                                    <textarea class="form-control" id="alamat" name="alamat" rows="2" required placeholder="Alamat Lengkap">{{ old('alamat') }}</textarea>
                                </div>
                                <div class="col-md-6">
                                    <label for="kecamatan_id" class="form-label">Kecamatan <span
                                            class="text-danger">*</span></label>
                                    <select class="form-select" id="kecamatan_id" name="kecamatan_id" required>
                                        <option value="">Pilih Kecamatan</option>
                                        @foreach ($kecamatans as $kec)
                                            <option value="{{ $kec->id }}"
                                                {{ old('kecamatan_id') == $kec->id ? 'selected' : '' }}>
                                                {{ $kec->kecamatan }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="kelurahan_id" class="form-label">Kelurahan <span
                                            class="text-danger">*</span></label>
                                    <select class="form-select" id="kelurahan_id" name="kelurahan_id" required>
                                        <option value="">Pilih Kelurahan</option>
                                    </select>
                                </div>
                            </div>

                            <hr class="my-4">

                            <h6 class="text-success fw-bold mb-3">Data Rumah Ibadah <small
                                    class="text-muted">(Opsional)</small></h6>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="tipe_rumah_ibadah" class="form-label">Tipe Rumah Ibadah</label>
                                    <select class="form-select" id="tipe_rumah_ibadah" name="tipe_rumah_ibadah">
                                        <option value="">Pilih Tipe</option>
                                        <option value="Masjid"
                                            {{ old('tipe_rumah_ibadah') == 'Masjid' ? 'selected' : '' }}>Masjid</option>
                                        <option value="Mushalla"
                                            {{ old('tipe_rumah_ibadah') == 'Mushalla' ? 'selected' : '' }}>Mushalla
                                        </option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="id_rumah_ibadah_input" class="form-label">ID Rumah Ibadah / Nomor
                                        Statistik</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="id_rumah_ibadah_input"
                                            placeholder="Masukkan ID atau Nomor Statistik"
                                            value="{{ old('id_rumah_ibadah_display') }}">
                                        <!-- We use a temp field for display if needed or keep it simple -->
                                        <button class="btn btn-outline-success" type="button" id="btn-check-rm">Cek
                                            Data</button>
                                    </div>
                                    <small class="text-muted">Klik "Cek Data" untuk memvalidasi.</small>
                                </div>

                                <input type="hidden" name="rumah_ibadah_id" id="rumah_ibadah_id"
                                    value="{{ old('rumah_ibadah_id') }}">

                                <div class="col-12" id="detail-rumah-ibadah" style="display: none;">
                                    <div class="card bg-light border-0">
                                        <div class="card-body">
                                            <h6 class="card-title fw-bold text-success">Detail Rumah Ibadah</h6>
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <strong>Nama:</strong> <span id="rm-nama">-</span>
                                                </div>
                                                <div class="col-md-4">
                                                    <strong>Kecamatan:</strong> <span id="rm-kecamatan">-</span>
                                                </div>
                                                <div class="col-md-4">
                                                    <strong>Kelurahan:</strong> <span id="rm-kelurahan">-</span>
                                                </div>
                                                <div class="col-12 mt-2">
                                                    <strong>Alamat:</strong> <span id="rm-alamat">-</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <hr class="my-4">

                            <h6 class="text-success fw-bold mb-3">Upload Berkas</h6>
                            <p class="small text-muted">Format: PDF/JPG/PNG. Maks 2MB per file.</p>
                            <div class="row g-3">
                                @php
                                    $files = [
                                        'file_ktp' => 'Scan KTP',
                                        'file_kk' => 'Scan Kartu Keluarga (KK)',
                                        'file_sk_marbot' => 'Scan SK Marbot',
                                        'file_permohonan' => 'Surat Permohonan',
                                        // 'file_pernyataan' => 'Surat Pernyataan', // Maybe optional? I'll include it.
                                    ];
                                @endphp

                                @foreach ($files as $field => $label)
                                    <div class="col-md-6">
                                        <label for="{{ $field }}" class="form-label">{{ $label }} <span
                                                class="text-danger">*</span></label>
                                        <input class="form-control" type="file" id="{{ $field }}"
                                            name="{{ $field }}" accept=".pdf,.jpg,.jpeg,.png" required>
                                    </div>
                                @endforeach

                                <div class="col-md-6">
                                    <label for="file_npwp" class="form-label">Scan NPWP <span
                                            class="text-danger">*</span></label>
                                    <input class="form-control" type="file" id="file_npwp" name="file_npwp"
                                        accept=".pdf,.jpg,.jpeg,.png" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="file_pernyataan" class="form-label">Surat Pernyataan <span
                                            class="text-danger">*</span></label>
                                    <input class="form-control" type="file" id="file_pernyataan"
                                        name="file_pernyataan" accept=".pdf,.jpg,.jpeg,.png" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="file_buku_rekening" class="form-label">Scan Buku Rekening
                                        <small>(opsional)</small></label>
                                    <input class="form-control" type="file" id="file_buku_rekening"
                                        name="file_buku_rekening" accept=".pdf,.jpg,.jpeg,.png">
                                </div>
                                <div class="col-md-6">
                                    <label for="nomor_rekening" class="form-label">Nomor Rekening
                                        <small>(opsional)</small></label>
                                    <input type="text" class="form-control" id="nomor_rekening" name="nomor_rekening"
                                        value="{{ old('nomor_rekening') }}">
                                </div>
                            </div>

                            <div class="d-grid gap-2 mt-5">
                                <button type="submit" class="btn btn-success btn-lg fw-bold">
                                    <i class="fas fa-save me-2"></i> Simpan & Setujui
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
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Data akan <strong>langsung
                                    disetujui</strong>.</li>
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i>NIM Marbot akan otomatis
                                diterbitkan.</li>
                            <li class="mb-2"><i class="fas fa-exclamation-triangle text-warning me-2"></i>Pastikan data
                                fisik (KK/KTP) sudah valid sebelum input.</li>
                        </ul>
                    </div>
                </div>
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
                // Load existing Kelurahan logic
                var existingKecId = '{{ old('kecamatan_id') }}';
                var existingKelId = '{{ old('kelurahan_id') }}';

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
                            $('#detail-rumah-ibadah').fadeIn();
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
                            $('#detail-rumah-ibadah').hide();
                            $('#rumah_ibadah_id').val('');
                        }
                    });
                });

                // Submit Confirmation
                $('#form-create-marbot').on('submit', function(e) {
                    // Check if rumah_ibadah_id is filled
                    // if ($('#rumah_ibadah_id').val() === '') {
                    //     e.preventDefault();
                    //     Swal.fire({
                    //         icon: 'warning',
                    //         title: 'Validasi Gagal',
                    //         text: 'Silahkan klik "Cek Data" untuk memvalidasi Rumah Ibadah terlebih dahulu.',
                    //     });
                    //     return;
                    // }

                    e.preventDefault();
                    var form = this;

                    Swal.fire({
                        title: 'Simpan Data?',
                        text: "Data akan disimpan dan langsung disetujui (NIM diterbitkan).",
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#198754',
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
