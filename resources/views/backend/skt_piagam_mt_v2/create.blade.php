@extends('layout.appv2')
@section('title', 'Tambah Data Majelis Ta\'lim')
@section('sub-layanan', 'active')
@section('layanan', 'active')
@section('skt-mt', 'active')

@section('content')
    <div class="content">
        <div class="page-title">
            <h3>Tambah Data Majelis Ta'lim</h3>
            <p>Form untuk menambahkan data majelis ta'lim baru</p>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card card-modern">
                    <div class="card-header">
                        <h4 class="section-title-modern">Form Tambah Majelis Ta'lim</h4>
                    </div>
                    <div class="card-body">
                        <form class="form" method="POST" action="{{ route('skt_piagam_mt_v2.store') }}">
                            @csrf

                            {{-- Nomor Statistik --}}
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="nomor_statistik" class="form-label fw-semibold">Nomor Statistik Majelis
                                        Ta'lim <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="text" id="nomor_statistik"
                                            class="form-control @error('nomor_statistik') is-invalid @enderror"
                                            placeholder="Nomor akan otomatis terisi setelah memilih kecamatan"
                                            name="nomor_statistik" disabled required value="{{ old('nomor_statistik') }}">
                                        <button class="btn btn-outline-secondary" type="button" id="btn-edit-nomor">
                                            <i class="fas fa-edit"></i> Edit
                                        </button>
                                    </div>
                                    @error('nomor_statistik')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Nomor statistik akan otomatis terisi setelah memilih
                                        kecamatan</small>
                                    <div id="nomor-loading" class="mt-1" style="display: none;">
                                        <div class="spinner-border spinner-border-sm text-primary" role="status">
                                            <span class="visually-hidden">Loading...</span>
                                        </div>
                                        <small class="ms-1">Mengambil nomor statistik...</small>
                                    </div>
                                    <div id="nomor-error" class="mt-1 text-danger" style="display: none;"></div>
                                </div>

                                <div class="col-md-6">
                                    <label for="nama_majelis" class="form-label fw-semibold">Nama Majelis Ta'lim <span
                                            class="text-danger">*</span></label>
                                    <input type="text" id="nama_majelis" class="form-control"
                                        placeholder="Nama Majelis Ta'lim" name="nama_majelis" required
                                        value="{{ old('nama_majelis') }}">
                                </div>
                            </div>

                            {{-- Alamat & Wilayah --}}
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="alamat" class="form-label fw-semibold">Alamat <span
                                            class="text-danger">*</span></label>
                                    <input type="text" id="alamat" class="form-control"
                                        placeholder="Alamat (Tanpa Kelurahan dan Kecamatan)" name="alamat" required
                                        value="{{ old('alamat') }}">
                                    <small class="text-muted">Contoh: Jl. Merdeka No. 123, RT. 001</small>
                                </div>

                                <div class="col-md-3">
                                    <label for="kecamatan" class="form-label fw-semibold">Kecamatan <span
                                            class="text-danger">*</span></label>
                                    <select class="form-select" id="kecamatan" name="kecamatan_id" style="width: 100%;"
                                        required>
                                        <option value="" class="text-white">Pilih Kecamatan</option>
                                        @foreach ($kecamatans as $kecamatan)
                                            <option value="{{ $kecamatan->id }}"
                                                {{ old('kecamatan_id') == $kecamatan->id ? 'selected' : '' }}>
                                                {{ ucwords($kecamatan->kecamatan) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-3">
                                    <label for="kelurahan" class="form-label fw-semibold">Kelurahan <span
                                            class="text-danger">*</span></label>
                                    <select class="form-select" id="kelurahan" name="kelurahan_id" style="width: 100%;"
                                        required>
                                        <option value="">Pilih Kelurahan</option>
                                    </select>
                                </div>
                            </div>

                            {{-- Tanggal & Status --}}
                            <div class="row mb-3">
                                <div class="col-md-3">
                                    <label for="tanggal-berdiri" class="form-label fw-semibold">Tanggal Berdiri <span
                                            class="text-danger">*</span></label>
                                    <input type="text" id="tanggal-berdiri" class="form-control datepicker"
                                        name="tanggal_berdiri" required value="{{ old('tanggal_berdiri') }}"
                                        placeholder="Pilih Tanggal">
                                </div>

                                <div class="col-md-3">
                                    <label for="status" class="form-label fw-semibold">Status <span
                                            class="text-danger">*</span></label>
                                    <select class="form-select" id="status" name="status" required>
                                        <option value="">Pilih Status</option>
                                        <option value="aktif" {{ old('status') == 'aktif' ? 'selected' : '' }}>Aktif
                                        </option>
                                        <option value="nonaktif" {{ old('status') == 'nonaktif' ? 'selected' : '' }}>
                                            Nonaktif</option>
                                        <option value="belum_update"
                                            {{ old('status') == 'belum_update' ? 'selected' : '' }}>Belum Update</option>
                                    </select>
                                </div>

                                <div class="col-md-3">
                                    <label for="ketua" class="form-label fw-semibold">Nama Ketua <span
                                            class="text-danger">*</span></label>
                                    <input type="text" id="ketua" class="form-control"
                                        placeholder="Nama Ketua Majelis Ta'lim" name="ketua" required
                                        value="{{ old('ketua') }}">
                                </div>

                                <div class="col-md-3">
                                    <label for="no_hp" class="form-label fw-semibold">Nomor HP <span
                                            class="text-danger">*</span></label>
                                    <input type="text" id="no_hp" class="form-control" placeholder="08xxxxxxxxxx"
                                        name="no_hp" required value="{{ old('no_hp') }}">
                                </div>
                            </div>

                            {{-- Anggota & Materi --}}
                            <div class="row mb-3">
                                <div class="col-md-3">
                                    <label for="jumlah_anggota" class="form-label fw-semibold">Jumlah Anggota</label>
                                    <input type="number" id="jumlah_anggota" class="form-control"
                                        placeholder="Contoh: 50" name="jumlah_anggota" min="0"
                                        value="{{ old('jumlah_anggota') }}">
                                </div>

                                <div class="col-md-9">
                                    <label for="materi" class="form-label fw-semibold">Materi Kajian (Ketik lalu tekan
                                        Enter)</label>
                                    <select class="form-select" id="materi" name="materi[]" multiple="multiple"
                                        style="width: 100%;">
                                        {{-- Options will be handled by Select2 tags --}}
                                        @if (old('materi'))
                                            @foreach (old('materi') as $item)
                                                <option value="{{ $item }}" selected>{{ $item }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                    <small class="text-muted">Ketik materi pembelajaran lalu tekan Enter untuk menambahkan
                                        materi lainnya.</small>
                                </div>
                            </div>

                            {{-- Tanggal Pendaftaran --}}
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <label for="mendaftar" class="form-label fw-semibold">Tanggal Mendaftar <span
                                            class="text-danger">*</span></label>
                                    <input type="text" id="mendaftar" class="form-control datepicker"
                                        name="mendaftar" required value="{{ old('mendaftar') }}"
                                        placeholder="Pilih Tanggal">
                                </div>

                                <div class="col-md-6">
                                    <label for="mendaftar_ulang" class="form-label fw-semibold">Tanggal Mendaftar Ulang
                                        <span class="text-danger">*</span></label>
                                    <input type="text" id="mendaftar_ulang" class="form-control datepicker"
                                        name="mendaftar_ulang" required value="{{ old('mendaftar_ulang') }}"
                                        placeholder="Pilih Tanggal">
                                    <small class="text-muted">Otomatis terisi 5 tahun dari tanggal mendaftar</small>
                                </div>
                            </div>

                            {{-- Action Buttons --}}
                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('skt_piagam_mt_v2.index') }}"
                                    class="btn btn-outline-secondary rounded-pill px-4">
                                    <i class="fas fa-times me-2"></i>Batal
                                </a>
                                <button type="reset" class="btn btn-outline-secondary rounded-pill px-4">
                                    <i class="fas fa-redo me-2"></i>Reset
                                </button>
                                <button type="submit" class="btn btn-primary rounded-pill px-4">
                                    <i class="fas fa-save me-2"></i>Simpan Data
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            $(document).ready(function() {
                // Inisialisasi Select2
                // Inisialisasi Select2
                $('#kecamatan').select2({
                    theme: 'bootstrap-5',
                    placeholder: "Pilih Kecamatan",
                    allowClear: true
                });

                $('#kelurahan').select2({
                    theme: 'bootstrap-5',
                    placeholder: "Pilih Kelurahan",
                    allowClear: true
                });

                $('#materi').select2({
                    theme: 'bootstrap-5',
                    placeholder: "Ketik materi...",
                    tags: true,
                    tokenSeparators: [',']
                });

                // Event handler untuk perubahan kecamatan
                $('#kecamatan').on('change', function() {
                    var kecamatanId = $(this).val();
                    console.log('Kecamatan changed:', kecamatanId);

                    // Reset kelurahan
                    $('#kelurahan').empty().append('<option value="">Pilih Kelurahan</option>').trigger(
                        'change');

                    if (kecamatanId) {
                        // Ambil kelurahan
                        $.ajax({
                            url: "{{ route('get.kelurahan') }}",
                            type: "GET",
                            data: {
                                kecamatan_id: kecamatanId
                            },
                            dataType: 'json',
                            success: function(data) {
                                console.log("Response kelurahan:", data);

                                if (data && data.length > 0) {
                                    $.each(data, function(index, kelurahan) {
                                        $('#kelurahan').append(new Option(
                                            kelurahan.nama_kelurahan,
                                            kelurahan.id,
                                            false,
                                            false
                                        ));
                                    });
                                } else {
                                    $('#kelurahan').append(new Option("Tidak ada kelurahan", "",
                                        false, false));
                                }
                                $('#kelurahan').trigger('change');
                            },
                            error: function(xhr) {
                                console.error("Error ambil kelurahan:", xhr.responseText);
                                $('#kelurahan').append(new Option("Error: Gagal mengambil data", "",
                                    false, false));
                            }
                        });

                        // Loading nomor statistik
                        $('#nomor-loading').show();
                        $('#nomor-error').hide();

                        // Ambil nomor statistik otomatis
                        $.ajax({
                            url: "{{ route('get.next.nomor.statistik') }}",
                            type: "GET",
                            dataType: 'json',
                            data: {
                                kecamatan_id: kecamatanId
                            },
                            success: function(data) {
                                $('#nomor-loading').hide();
                                console.log("Response nomor statistik:", data);

                                if (data && data.nomor_statistik) {
                                    $('#nomor_statistik').val(data.nomor_statistik);
                                } else {
                                    $('#nomor-error').text('Nomor statistik tidak ditemukan')
                                        .show();
                                }
                            },
                            error: function(xhr) {
                                $('#nomor-loading').hide();
                                console.error("Error nomor statistik:", xhr.responseText);
                                $('#nomor-error').text('Gagal ambil nomor statistik').show();
                            }
                        });
                    } else {
                        $('#nomor_statistik').val('');
                    }
                });

                // Inisialisasi data lama jika ada
                const oldKecamatanId = "{{ old('kecamatan_id') }}";
                if (oldKecamatanId) {
                    $('#kecamatan').val(oldKecamatanId).trigger('change');

                    setTimeout(function() {
                        const oldKelurahanId = "{{ old('kelurahan_id') }}";
                        if (oldKelurahanId) {
                            $('#kelurahan').val(oldKelurahanId).trigger('change');
                        }
                    }, 500);
                }

                // Tombol edit nomor statistik
                $('#btn-edit-nomor').html('<i class="fas fa-edit"></i> Edit');

                $('#btn-edit-nomor').on('click', function() {
                    var $input = $('#nomor_statistik');
                    if ($input.prop('disabled')) {
                        $input.prop('disabled', false);
                        $(this).html('<i class="fas fa-check"></i> Selesai');
                    } else {
                        $input.prop('disabled', true);
                        $(this).html('<i class="fas fa-edit"></i> Edit');
                    }
                });

                // PENTING: Enable nomor_statistik saat submit agar data terkirim
                $('form').on('submit', function() {
                    $('#nomor_statistik').prop('disabled', false);
                });

                // Auto-fill tanggal mendaftar ulang (Kompatibel dengan Native & Flatpickr)
                $('#mendaftar').on('change', function() {
                    let tanggalMendaftar;

                    // Cek apakah elemen ini memiliki instance Flatpickr
                    if (this._flatpickr) {
                        const selectedDates = this._flatpickr.selectedDates;
                        if (selectedDates.length > 0) {
                            tanggalMendaftar = selectedDates[0];
                        }
                    } else {
                        // Fallback untuk native input
                        tanggalMendaftar = new Date($(this).val());
                    }

                    if (tanggalMendaftar && !isNaN(tanggalMendaftar.getTime())) {
                        var tanggalMendaftarUlang = new Date(tanggalMendaftar);
                        tanggalMendaftarUlang.setFullYear(tanggalMendaftar.getFullYear() + 5);

                        var tahun = tanggalMendaftarUlang.getFullYear();
                        var bulan = (tanggalMendaftarUlang.getMonth() + 1).toString().padStart(2, '0');
                        var tanggal = tanggalMendaftarUlang.getDate().toString().padStart(2, '0');
                        var formattedDate = `${tahun}-${bulan}-${tanggal}`;

                        // Set value ke target
                        const targetInput = document.getElementById('mendaftar_ulang');
                        if (targetInput._flatpickr) {
                            targetInput._flatpickr.setDate(tanggalMendaftarUlang, true);
                        } else {
                            $(targetInput).val(formattedDate);
                        }
                    }
                });
            });
        </script>
    @endpush


@endsection
