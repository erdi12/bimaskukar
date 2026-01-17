@extends('layout.frontend')

@section('title', 'Perbaikan Data Marbot')

@push('styles')
    <style>
        /* Fix for dropdown visibility issue */
        .form-select,
        .form-select option {
            color: #000 !important;
            background-color: #fff !important;
        }

        /* Disable hover effect for this specific card */
        .card:hover {
            transform: none !important;
            box-shadow: 0 .125rem .25rem rgba(0, 0, 0, .075) !important;
            /* Reset to bootstrap shadow-sm */
        }

        .card:hover h5 {
            color: white !important;
            /* Keep header text white */
        }
    </style>
@endpush

@section('content')
    @php
        $v = $marbot->verification_details;
        $isLocked = function ($field) use ($v) {
            // If no verification history, everything is editable (false)
            if (!$v) {
                return false;
            }
            // If field exists and is valid -> Locked (true)
            if (isset($v[$field]) && $v[$field]['valid'] === true) {
                return true;
            }
            // If field exists and is invalid -> Unlocked (false)
            if (isset($v[$field]) && $v[$field]['valid'] === false) {
                return false;
            }
            // Default (if missing from verification list) -> Locked (true)
            return true;
        };

        $getNote = function ($field) use ($v) {
            if (isset($v[$field]) && $v[$field]['valid'] === false) {
                return $v[$field]['note'] ?? 'Data tidak sesuai.';
            }
            return null;
        };
    @endphp

    <div class="container py-5 mt-4">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card shadow-sm border-0 rounded-3">
                    <div class="card-header bg-warning text-dark py-3">
                        <h5 class="mb-0 fw-bold"><i class="fas fa-edit me-2"></i>Perbaikan Data Permohonan Marbot</h5>
                    </div>
                    <div class="card-body p-4">

                        <div class="alert alert-danger">
                            <h6 class="fw-bold"><i class="fas fa-exclamation-circle me-1"></i> Catatan Perbaikan:</h6>
                            <p class="mb-0">{{ $marbot->catatan }}</p>
                        </div>

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('marbot.frontend.update', $marbot->uuid) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <h6 class="text-success fw-bold mb-3">Data Pribadi</h6>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="nik" class="form-label">NIK (Nomor Induk Kependudukan) <span
                                            class="text-danger">*</span></label>
                                    <input type="number"
                                        class="form-control {{ $isLocked('nik') ? 'bg-light' : 'border-danger' }}"
                                        id="nik" name="nik" value="{{ old('nik', $marbot->nik) }}" required
                                        {{ $isLocked('nik') ? 'readonly' : '' }}>
                                    @if ($isLocked('nik'))
                                        <i class="fas fa-check-circle text-success small mt-1"></i> Valid
                                    @endif
                                    @if ($note = $getNote('nik'))
                                        <div class="text-danger small fw-bold mt-1">{{ $note }}</div>
                                    @endif
                                </div>
                                <div class="col-md-6">
                                    <label for="nama_lengkap" class="form-label">Nama Lengkap <span
                                            class="text-danger">*</span></label>
                                    <input type="text"
                                        class="form-control {{ $isLocked('nama_lengkap') ? 'bg-light' : 'border-danger' }}"
                                        id="nama_lengkap" name="nama_lengkap"
                                        value="{{ old('nama_lengkap', $marbot->nama_lengkap) }}" required
                                        {{ $isLocked('nama_lengkap') ? 'readonly' : '' }}>
                                    @if ($isLocked('nama_lengkap'))
                                        <i class="fas fa-check-circle text-success small mt-1"></i> Valid
                                    @endif
                                    @if ($note = $getNote('nama_lengkap'))
                                        <div class="text-danger small fw-bold mt-1">{{ $note }}</div>
                                    @endif
                                </div>
                                <div class="col-md-6">
                                    <label for="tempat_lahir" class="form-label">Tempat Lahir <span
                                            class="text-danger">*</span></label>
                                    <input type="text"
                                        class="form-control {{ $isLocked('tempat_lahir') ? 'bg-light' : 'border-danger' }}"
                                        id="tempat_lahir" name="tempat_lahir"
                                        value="{{ old('tempat_lahir', $marbot->tempat_lahir) }}"
                                        placeholder="Contoh: Samarinda" required
                                        {{ $isLocked('tempat_lahir') ? 'readonly' : '' }}>
                                    @if ($isLocked('tempat_lahir'))
                                        <i class="fas fa-check-circle text-success small mt-1"></i> Valid
                                    @endif
                                    @if ($note = $getNote('tempat_lahir'))
                                        <div class="text-danger small fw-bold mt-1">{{ $note }}</div>
                                    @endif
                                </div>
                                <div class="col-md-6">
                                    <label for="tanggal_lahir" class="form-label">Tanggal Lahir <span
                                            class="text-danger">*</span></label>
                                    <input type="date"
                                        class="form-control {{ $isLocked('tanggal_lahir') ? 'bg-light' : 'border-danger' }}"
                                        id="tanggal_lahir" name="tanggal_lahir"
                                        value="{{ old('tanggal_lahir', $marbot->tanggal_lahir?->format('Y-m-d')) }}"
                                        required {{ $isLocked('tanggal_lahir') ? 'readonly' : '' }}>
                                    @if ($isLocked('tanggal_lahir'))
                                        <i class="fas fa-check-circle text-success small mt-1"></i> Valid
                                    @endif
                                    @if ($note = $getNote('tanggal_lahir'))
                                        <div class="text-danger small fw-bold mt-1">{{ $note }}</div>
                                    @endif
                                </div>
                                <div class="col-md-6">
                                    <label for="npwp" class="form-label">NPWP <span class="text-danger">*</span></label>
                                    <input type="text"
                                        class="form-control {{ $isLocked('npwp') ? 'bg-light' : 'border-danger' }}"
                                        id="npwp" name="npwp" value="{{ old('npwp', $marbot->npwp) }}"
                                        placeholder="Contoh: 12.345.678.9-012.345" required
                                        {{ $isLocked('npwp') ? 'readonly' : '' }}>
                                    @if ($isLocked('npwp'))
                                        <i class="fas fa-check-circle text-success small mt-1"></i> Valid
                                    @endif
                                    @if ($note = $getNote('npwp'))
                                        <div class="text-danger small fw-bold mt-1">{{ $note }}</div>
                                    @endif
                                </div>
                                <div class="col-md-6">
                                    <label for="tanggal_mulai_bekerja" class="form-label">Tanggal Mulai Bekerja Sebagai
                                        Marbot <span class="text-danger">*</span></label>
                                    <input type="date"
                                        class="form-control {{ $isLocked('tanggal_mulai_bekerja') ? 'bg-light' : 'border-danger' }}"
                                        id="tanggal_mulai_bekerja" name="tanggal_mulai_bekerja"
                                        value="{{ old('tanggal_mulai_bekerja', $marbot->tanggal_mulai_bekerja?->format('Y-m-d')) }}"
                                        required {{ $isLocked('tanggal_mulai_bekerja') ? 'readonly' : '' }}>
                                    @if ($isLocked('tanggal_mulai_bekerja'))
                                        <i class="fas fa-check-circle text-success small mt-1"></i> Valid
                                    @endif
                                    @if ($note = $getNote('tanggal_mulai_bekerja'))
                                        <div class="text-danger small fw-bold mt-1">{{ $note }}</div>
                                    @endif
                                </div>
                                <div class="col-md-6">
                                    <label for="no_hp" class="form-label">Nomor WhatsApp Aktif<span
                                            class="text-danger">*</span></label>
                                    <input type="number"
                                        class="form-control {{ $isLocked('no_hp') ? 'bg-light' : 'border-danger' }}"
                                        id="no_hp" name="no_hp" value="{{ old('no_hp', $marbot->no_hp) }}"
                                        placeholder="Contoh: 081234567890" required
                                        {{ $isLocked('no_hp') ? 'readonly' : '' }}>
                                    @if ($isLocked('no_hp'))
                                        <i class="fas fa-check-circle text-success small mt-1"></i> Valid
                                    @endif
                                    @if ($note = $getNote('no_hp'))
                                        <div class="text-danger small fw-bold mt-1">{{ $note }}</div>
                                    @endif
                                </div>
                                <div class="col-12">
                                    <label for="alamat" class="form-label">Alamat Sesuai KTP <span
                                            class="text-danger">*</span></label>
                                    <textarea class="form-control {{ $isLocked('alamat') ? 'bg-light' : 'border-danger' }}" id="alamat"
                                        name="alamat" rows="2" required {{ $isLocked('alamat') ? 'readonly' : '' }}>{{ old('alamat', $marbot->alamat) }}</textarea>
                                    @if ($isLocked('alamat'))
                                        <i class="fas fa-check-circle text-success small mt-1"></i> Valid
                                    @endif
                                    @if ($note = $getNote('alamat'))
                                        <div class="text-danger small fw-bold mt-1">{{ $note }}</div>
                                    @endif
                                </div>
                                <div class="col-md-6">
                                    <label for="kecamatan_id" class="form-label">Kecamatan (Tempat Tinggal) <span
                                            class="text-danger">*</span></label>
                                    @if ($isLocked('kecamatan_id'))
                                        <input type="hidden" name="kecamatan_id" value="{{ $marbot->kecamatan_id }}">
                                        <input type="text" class="form-control bg-light"
                                            value="{{ $marbot->kecamatan->kecamatan ?? '-' }}" readonly>
                                        <i class="fas fa-check-circle text-success small mt-1"></i> Valid
                                    @else
                                        <select class="form-select border-danger" id="kecamatan_id" name="kecamatan_id"
                                            required>
                                            <option value="">Pilih Kecamatan</option>
                                            @foreach ($kecamatans as $kec)
                                                <option value="{{ $kec->id }}"
                                                    {{ old('kecamatan_id', $marbot->kecamatan_id) == $kec->id ? 'selected' : '' }}>
                                                    {{ $kec->kecamatan }}</option>
                                            @endforeach
                                        </select>
                                        @if ($note = $getNote('kecamatan_id'))
                                            <div class="text-danger small fw-bold mt-1">{{ $note }}</div>
                                        @endif
                                    @endif
                                </div>
                                <div class="col-md-6">
                                    <label for="kelurahan_id" class="form-label">Kelurahan (Tempat Tinggal) <span
                                            class="text-danger">*</span></label>
                                    @if ($isLocked('kelurahan_id'))
                                        <input type="hidden" name="kelurahan_id" value="{{ $marbot->kelurahan_id }}">
                                        <input type="text" class="form-control bg-light"
                                            value="{{ $marbot->kelurahan->nama_kelurahan ?? '-' }}" readonly>
                                        <i class="fas fa-check-circle text-success small mt-1"></i> Valid
                                    @else
                                        <select class="form-select border-danger" id="kelurahan_id" name="kelurahan_id"
                                            required>
                                            <option value="">Pilih Kecamatan Terlebih Dahulu</option>
                                        </select>
                                        @if ($note = $getNote('kelurahan_id'))
                                            <div class="text-danger small fw-bold mt-1">{{ $note }}</div>
                                        @endif
                                    @endif
                                </div>
                            </div>

                            <hr class="my-4">

                            <h6 class="text-success fw-bold mb-3">Data Rumah Ibadah</h6>
                            @php
                                // Check if ANY rumah ibadah related field is invalid
                                $riFields = [
                                    'tipe_rumah_ibadah',
                                    'nama_rumah_ibadah',
                                    'nomor_id_rumah_ibadah',
                                    'alamat_rumah_ibadah',
                                ];
                                $riLocked = true;
                                if (!$v) {
                                    $riLocked = false;
                                } else {
                                    foreach ($riFields as $f) {
                                        if (isset($v[$f]) && $v[$f]['valid'] === false) {
                                            $riLocked = false;
                                            break;
                                        }
                                    }
                                }
                            @endphp

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="tipe_rumah_ibadah" class="form-label">Tipe Rumah Ibadah <span
                                            class="text-danger">*</span></label>
                                    @if ($riLocked)
                                        <input type="hidden" name="tipe_rumah_ibadah"
                                            value="{{ $marbot->tipe_rumah_ibadah }}">
                                        <input type="text" class="form-control bg-light"
                                            value="{{ $marbot->tipe_rumah_ibadah }}" readonly>
                                        <i class="fas fa-check-circle text-success small mt-1"></i> Valid
                                    @else
                                        <select class="form-select border-danger" id="tipe_rumah_ibadah"
                                            name="tipe_rumah_ibadah" required>
                                            <option value="">Pilih Tipe</option>
                                            <option value="Masjid"
                                                {{ old('tipe_rumah_ibadah', $marbot->tipe_rumah_ibadah) == 'Masjid' ? 'selected' : '' }}>
                                                Masjid</option>
                                            <option value="Mushalla"
                                                {{ old('tipe_rumah_ibadah', $marbot->tipe_rumah_ibadah) == 'Mushalla' ? 'selected' : '' }}>
                                                Mushalla
                                            </option>
                                        </select>
                                        @if ($note = $getNote('tipe_rumah_ibadah'))
                                            <div class="text-danger small fw-bold mt-1">Tipe: {{ $note }}</div>
                                        @endif
                                    @endif
                                </div>
                                <div class="col-md-6">
                                    <label for="id_rumah_ibadah_input" class="form-label">ID Rumah Ibadah / Nomor
                                        Statistik <span class="text-danger">*</span></label>
                                    @if ($riLocked)
                                        <div class="input-group">
                                            <input type="text" class="form-control bg-light"
                                                value="{{ $marbot->rumah_ibadah->nomor_id_masjid ?? ($marbot->rumah_ibadah->nomor_id_mushalla ?? '-') }}"
                                                readonly>
                                            <button class="btn btn-outline-secondary" type="button" disabled><i
                                                    class="fas fa-lock"></i></button>
                                        </div>
                                        <i class="fas fa-check-circle text-success small mt-1"></i> Valid
                                    @else
                                        <div class="input-group">
                                            <input type="text" class="form-control border-danger"
                                                id="id_rumah_ibadah_input" placeholder="Masukkan ID atau Nomor Statistik"
                                                value="{{ $marbot->rumah_ibadah_id }}" required>
                                            <!-- Note: Value here is ID PK, not Statistic Number, but user expects Stat Number usually.
                                                                                                     However, in edit mode we might wanna show Stat Number if possible.
                                                                                                     But let's stick to simple clear logic for now or leave empty to force re-search?
                                                                                                     Actually re-searching is safer if invalid. -->
                                            <button class="btn btn-outline-danger" type="button" id="btn-check-rm">Cek
                                                Data</button>
                                        </div>
                                        <small class="text-muted">Klik "Cek Data" ulang jika ada perubahan rumah
                                            ibadah.</small>
                                        @if ($note = $getNote('nama_rumah_ibadah'))
                                            <div class="text-danger small fw-bold mt-1">Nama: {{ $note }}</div>
                                        @endif
                                        @if ($note = $getNote('nomor_id_rumah_ibadah'))
                                            <div class="text-danger small fw-bold mt-1">ID: {{ $note }}</div>
                                        @endif
                                        @if ($note = $getNote('alamat_rumah_ibadah'))
                                            <div class="text-danger small fw-bold mt-1">Alamat: {{ $note }}</div>
                                        @endif
                                    @endif
                                </div>

                                <!-- Hidden input for real ID -->
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
                                                        @if ($marbot->rumah_ibadah && $marbot->rumah_ibadah->kecamatan)
                                                            {{ $marbot->rumah_ibadah->kecamatan->kecamatan }}
                                                        @else
                                                            -
                                                        @endif
                                                    </span>
                                                </div>
                                                <div class="col-md-4">
                                                    <strong>Kelurahan:</strong> <span id="rm-kelurahan">
                                                        @if ($marbot->rumah_ibadah && $marbot->rumah_ibadah->kelurahan)
                                                            {{ $marbot->rumah_ibadah->kelurahan->nama_kelurahan }}
                                                        @else
                                                            -
                                                        @endif
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

                            <h6 class="text-success fw-bold mb-3">Upload Berkas (Upload ulang jika ingin mengganti)</h6>
                            <div class="row g-3">
                                @php
                                    $files = [
                                        'file_ktp' => 'Scan KTP',
                                        'file_kk' => 'Scan Kartu Keluarga (KK)',
                                        'file_sk_marbot' => 'Scan SK Marbot',
                                        'file_npwp' => 'Scan NPWP',
                                        'file_permohonan' => 'Surat Permohonan',
                                        'file_pernyataan' => 'Surat Pernyataan',
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

                                        @if ($isLocked($field))
                                            <div class="alert alert-success py-1 px-3 small border-0 mb-0">
                                                <i class="fas fa-check-circle me-1"></i> Berkas Valid
                                            </div>
                                            <!-- Hidden input to ensure validation passes on server if required, IF the server requires re-upload.
                                                                                                 But controller uses nullable for file updates. So no input needed if not changing. -->
                                        @else
                                            <input class="form-control border-danger" type="file"
                                                id="{{ $field }}" name="{{ $field }}"
                                                accept=".pdf,.jpg,.jpeg,.png" required>
                                            <div class="form-text text-danger fw-bold">Perlu Diganti.</div>
                                            @if ($note = $getNote($field))
                                                <div class="text-danger small fw-bold mt-1">Catatan: {{ $note }}
                                                </div>
                                            @endif
                                        @endif
                                    </div>
                                @endforeach
                            </div>

                            <div class="d-grid gap-2 mt-5">
                                <button type="submit" class="btn btn-warning btn-lg fw-bold"><i
                                        class="fas fa-paper-plane me-2"></i> Kirim Perbaikan Data</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // Load existing Kelurahan logic
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

            // Fetch Kelurahan based on Kecamatan (for Marbot address)
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
                        confirmButtonColor: '#2A9D8F'
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
                        $('#detail-rumah-ibadah').hide();
                        $('#rumah_ibadah_id').val('');

                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: response.message,
                            confirmButtonColor: '#2A9D8F'
                        });
                    }
                });
            });
        });
    </script>
@endsection
