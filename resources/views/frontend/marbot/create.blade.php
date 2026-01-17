@extends('layout.frontend')

@section('title', 'Pendaftaran Marbot Masjid')

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

        /* Flatpickr Customization to match theme */
        .flatpickr-calendar {
            font-family: 'Poppins', sans-serif;
        }

        .flatpickr-day.selected,
        .flatpickr-day.startRange,
        .flatpickr-day.endRange,
        .flatpickr-day.selected.inRange,
        .flatpickr-day.startRange.inRange,
        .flatpickr-day.endRange.inRange,
        .flatpickr-day.selected:focus,
        .flatpickr-day.startRange:focus,
        .flatpickr-day.endRange:focus,
        .flatpickr-day.selected:hover,
        .flatpickr-day.startRange:hover,
        .flatpickr-day.endRange:hover,
        .flatpickr-day.selected.prevMonthDay,
        .flatpickr-day.startRange.prevMonthDay,
        .flatpickr-day.endRange.prevMonthDay,
        .flatpickr-day.selected.nextMonthDay,
        .flatpickr-day.startRange.nextMonthDay,
        .flatpickr-day.endRange.nextMonthDay {
            background: #2A9D8F;
            border-color: #2A9D8F;
        }
    </style>
    <!-- Flatpickr CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
@endpush

@section('content')
    <div class="container py-5 mt-4">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card shadow-sm border-0 rounded-3">
                    <div class="card-header bg-success text-white py-3">
                        <h5 class="mb-0 fw-bold"><i class="fas fa-file-alt me-2"></i>Formulir Pendaftaran Nomor Induk Marbot
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        @if (session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form id="form-marbot-create" action="{{ route('marbot.frontend.store') }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf

                            <h6 class="text-success fw-bold mb-3">Data Pribadi</h6>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="nik" class="form-label">NIK (Nomor Induk Kependudukan) <span
                                            class="text-danger">*</span></label>
                                    <input type="number" class="form-control" id="nik" name="nik"
                                        value="{{ old('nik') }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="nama_lengkap" class="form-label">Nama Lengkap <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap"
                                        value="{{ old('nama_lengkap') }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="tempat_lahir" class="form-label">Tempat Lahir <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="tempat_lahir" name="tempat_lahir"
                                        value="{{ old('tempat_lahir') }}" placeholder="Contoh: Samarinda" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="tanggal_lahir" class="form-label">Tanggal Lahir <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control bg-white" id="tanggal_lahir"
                                        name="tanggal_lahir" value="{{ old('tanggal_lahir') }}"
                                        placeholder="Pilih Tanggal Lahir" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="npwp" class="form-label">NPWP <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="npwp" name="npwp"
                                        value="{{ old('npwp') }}" placeholder="Contoh: 12.345.678.9-012.345" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="tanggal_mulai_bekerja" class="form-label">Tanggal Mulai Bekerja Sebagai
                                        Marbot <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control bg-white" id="tanggal_mulai_bekerja"
                                        name="tanggal_mulai_bekerja" value="{{ old('tanggal_mulai_bekerja') }}"
                                        placeholder="Pilih Tanggal Mulai Bekerja" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="no_hp" class="form-label">Nomor WhatsApp Aktif<span
                                            class="text-danger">*</span></label>
                                    <input type="number" class="form-control" id="no_hp" name="no_hp"
                                        value="{{ old('no_hp') }}" placeholder="Contoh: 081234567890" required>
                                </div>
                                <div class="col-12">
                                    <label for="alamat" class="form-label">Alamat Sesuai KTP <span
                                            class="text-danger">*</span></label>
                                    <textarea class="form-control" id="alamat" name="alamat" rows="2" required>{{ old('alamat') }}</textarea>
                                </div>
                                <div class="col-md-6">
                                    <label for="kecamatan_id" class="form-label">Kecamatan (Tempat Tinggal) <span
                                            class="text-danger">*</span></label>
                                    <select class="form-select" id="kecamatan_id" name="kecamatan_id" required>
                                        <option value="">Pilih Kecamatan</option>
                                        @foreach ($kecamatans as $kec)
                                            <option value="{{ $kec->id }}"
                                                {{ old('kecamatan_id') == $kec->id ? 'selected' : '' }}>
                                                {{ $kec->kecamatan }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="kelurahan_id" class="form-label">Kelurahan (Tempat Tinggal) <span
                                            class="text-danger">*</span></label>
                                    <select class="form-select" id="kelurahan_id" name="kelurahan_id" required>
                                        <option value="">Pilih Kecamatan Terlebih Dahulu</option>
                                    </select>
                                </div>
                            </div>

                            <hr class="my-4">

                            <h6 class="text-success fw-bold mb-3">Data Rumah Ibadah</h6>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="tipe_rumah_ibadah" class="form-label">Tipe Rumah Ibadah <span
                                            class="text-danger">*</span></label>
                                    <select class="form-select" id="tipe_rumah_ibadah" name="tipe_rumah_ibadah" required>
                                        <option value="">Pilih Tipe</option>
                                        <option value="Masjid"
                                            {{ old('tipe_rumah_ibadah') == 'Masjid' ? 'selected' : '' }}>
                                            Masjid</option>
                                        <option value="Mushalla"
                                            {{ old('tipe_rumah_ibadah') == 'Mushalla' ? 'selected' : '' }}>Mushalla
                                        </option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="id_rumah_ibadah_input" class="form-label">ID Rumah Ibadah / Nomor
                                        Statistik <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="id_rumah_ibadah_input"
                                            placeholder="Masukkan ID atau Nomor Statistik" required>
                                        <button class="btn btn-outline-success" type="button" id="btn-check-rm">Cek
                                            Data</button>
                                    </div>
                                    <small class="text-muted">Klik "Cek Data" untuk mengisi otomatis detail rumah
                                        ibadah.</small>
                                </div>

                                <!-- Hidden input for real ID -->
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
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="file_ktp" class="form-label">Scan KTP <span
                                            class="text-danger">*</span></label>
                                    <input class="form-control" type="file" id="file_ktp" name="file_ktp"
                                        accept=".pdf,.jpg,.jpeg,.png" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="file_kk" class="form-label">Scan Kartu Keluarga (KK) <span
                                            class="text-danger">*</span></label>
                                    <input class="form-control" type="file" id="file_kk" name="file_kk"
                                        accept=".pdf,.jpg,.jpeg,.png" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="file_sk_marbot" class="form-label">Scan SK Marbot <span
                                            class="text-danger">*</span></label>
                                    <input class="form-control" type="file" id="file_sk_marbot" name="file_sk_marbot"
                                        accept=".pdf,.jpg,.jpeg,.png" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="file_permohonan" class="form-label">Surat Permohonan Nomor Induk
                                        Marbot <span class="text-danger">*</span></label>
                                    <input class="form-control" type="file" id="file_permohonan"
                                        name="file_permohonan" accept=".pdf,.jpg,.jpeg,.png" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="file_pernyataan" class="form-label">Surat Pernyataan Kebenaran
                                        Data <span class="text-danger">*</span></label>
                                    <input class="form-control" type="file" id="file_pernyataan"
                                        name="file_pernyataan" accept=".pdf,.jpg,.jpeg,.png" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="file_npwp" class="form-label">Scan NPWP <span
                                            class="text-danger">*</span></label>
                                    <input class="form-control" type="file" id="file_npwp" name="file_npwp"
                                        accept=".pdf,.jpg,.jpeg,.png" required>
                                </div>
                            </div>

                            <div class="d-grid gap-2 mt-5">
                                <button type="submit" class="btn btn-success btn-lg">Kirim Permohonan</button>
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
            // Submit Confirmation
            $('#form-marbot-create').on('submit', function(e) {
                e.preventDefault();
                var form = this;

                Swal.fire({
                    title: 'Kirim Permohonan?',
                    text: "Pastikan data yang Anda masukkan sudah benar.",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#2A9D8F',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, Kirim!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Mengirim Permohonan...',
                            text: 'Mohon tunggu, berkas sedang diunggah.',
                            showConfirmButton: false,
                            allowOutsideClick: false,
                            willOpen: () => {
                                Swal.showLoading();
                            }
                        });
                        form.submit();
                    }
                });
            });

            // Session Messages via SweetAlert
            @if (session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: '{{ session('success') }}',
                    confirmButtonColor: '#2A9D8F'
                });
            @endif

            @if (session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: '{{ session('error') }}',
                    confirmButtonColor: '#2A9D8F'
                });
            @endif

            // Fetch Kelurahan based on Kecamatan (for Marbot address)
            $('#kecamatan_id').change(function() {
                var kecId = $(this).val();
                if (kecId) {
                    $.get("{{ url('api/kelurahans') }}/" + kecId, function(data) {
                        $('#kelurahan_id').empty();
                        $('#kelurahan_id').append('<option value="">Pilih Kelurahan</option>');
                        $.each(data, function(key, value) {
                            $('#kelurahan_id').append('<option value="' + value.id + '">' +
                                value.nama_kelurahan + '</option>');
                        });
                    });
                } else {
                    $('#kelurahan_id').empty();
                    $('#kelurahan_id').append('<option value="">Pilih Kecamatan Terlebih Dahulu</option>');
                }
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

    @push('scripts')
        <!-- Flatpickr JS -->
        <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
        <script src="https://npmcdn.com/flatpickr/dist/l10n/id.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Konfigurasi umum Flatpickr
                const flatpickrConfig = {
                    locale: 'id',
                    altInput: true,
                    altFormat: "j F Y",
                    dateFormat: "Y-m-d",
                    allowInput: true,
                    disableMobile: "true" // Force flatpickr on mobile
                };

                // Inisialisasi Tanggal Lahir
                flatpickr("#tanggal_lahir", flatpickrConfig);

                // Inisialisasi Tanggal Mulai Bekerja
                flatpickr("#tanggal_mulai_bekerja", flatpickrConfig);
            });
        </script>
    @endpush
@endsection
