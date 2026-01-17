@extends('layout.appv2')
@section('title', 'Edit Data Majelis Ta\'lim')
@section('sub-layanan', 'active')
@section('layanan', 'active')
@section('skt-mt', 'active')

@section('content')
    <div class="content">
        <div class="page-title">
            <h3>Edit Data Majelis Ta'lim</h3>
            <p>Form untuk memperbarui data majelis ta'lim</p>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card card-modern">
                    <div class="card-header">
                        <h4 class="section-title-modern">Form Edit Majelis Ta'lim</h4>
                    </div>
                    <div class="card-body">
                        <form class="form" method="POST"
                            action="{{ route('skt_piagam_mt_v2.update', $sktpiagammt->uuid) }}">
                            @csrf
                            @method('PUT')

                            {{-- Nomor Statistik --}}
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="nomor_statistik" class="form-label fw-semibold">Nomor Statistik Majelis
                                        Ta'lim <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="text" id="nomor_statistik"
                                            class="form-control @error('nomor_statistik') is-invalid @enderror"
                                            placeholder="Nomor Statistik" name="nomor_statistik" disabled required
                                            value="{{ old('nomor_statistik', $sktpiagammt->nomor_statistik) }}">
                                        <button class="btn btn-outline-secondary" type="button" id="btn-edit-nomor">
                                            <i class="fas fa-edit"></i> Edit
                                        </button>
                                    </div>
                                    @error('nomor_statistik')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Klik tombol edit jika ingin mengubah nomor statistik secara
                                        manual</small>
                                </div>

                                <div class="col-md-6">
                                    <label for="nama_majelis" class="form-label fw-semibold">Nama Majelis Ta'lim <span
                                            class="text-danger">*</span></label>
                                    <input type="text" id="nama_majelis" class="form-control"
                                        placeholder="Nama Majelis Ta'lim" name="nama_majelis" required
                                        value="{{ old('nama_majelis', $sktpiagammt->nama_majelis) }}">
                                </div>
                            </div>

                            {{-- Alamat & Wilayah --}}
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="alamat" class="form-label fw-semibold">Alamat <span
                                            class="text-danger">*</span></label>
                                    <input type="text" id="alamat" class="form-control"
                                        placeholder="Alamat (Tanpa Kelurahan dan Kecamatan)" name="alamat" required
                                        value="{{ old('alamat', $sktpiagammt->alamat) }}">
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
                                                {{ old('kecamatan_id', $sktpiagammt->kecamatan_id) == $kecamatan->id ? 'selected' : '' }}>
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
                                        {{-- Kelurahan will be populated via AJAX or on load --}}
                                    </select>
                                </div>
                            </div>

                            {{-- Tanggal & Status --}}
                            <div class="row mb-3">
                                <div class="col-md-3">
                                    <label for="tanggal-berdiri" class="form-label fw-semibold">Tanggal Berdiri <span
                                            class="text-danger">*</span></label>
                                    <input type="text" id="tanggal-berdiri" class="form-control datepicker"
                                        name="tanggal_berdiri" required
                                        value="{{ old('tanggal_berdiri', $sktpiagammt->tanggal_berdiri) }}"
                                        placeholder="Pilih Tanggal">
                                </div>

                                <div class="col-md-3">
                                    <label for="status" class="form-label fw-semibold">Status <span
                                            class="text-danger">*</span></label>
                                    <select class="form-select" id="status" name="status" required>
                                        <option value="">Pilih Status</option>
                                        <option value="aktif"
                                            {{ old('status', $sktpiagammt->status) == 'aktif' ? 'selected' : '' }}>
                                            Aktif</option>
                                        <option value="nonaktif"
                                            {{ old('status', $sktpiagammt->status) == 'nonaktif' ? 'selected' : '' }}>
                                            Nonaktif</option>
                                        <option value="belum_update"
                                            {{ old('status', $sktpiagammt->status) == 'belum_update' ? 'selected' : '' }}>
                                            Belum Update</option>
                                    </select>
                                </div>

                                <div class="col-md-3">
                                    <label for="ketua" class="form-label fw-semibold">Nama Ketua <span
                                            class="text-danger">*</span></label>
                                    <input type="text" id="ketua" class="form-control"
                                        placeholder="Nama Ketua Majelis Ta'lim" name="ketua" required
                                        value="{{ old('ketua', $sktpiagammt->ketua) }}">
                                </div>

                                <div class="col-md-3">
                                    <label for="no_hp" class="form-label fw-semibold">Nomor HP <span
                                            class="text-danger">*</span></label>
                                    <input type="text" id="no_hp" class="form-control" placeholder="08xxxxxxxxxx"
                                        name="no_hp" required value="{{ old('no_hp', $sktpiagammt->no_hp) }}">
                                </div>
                            </div>

                            {{-- Anggota & Materi --}}
                            <div class="row mb-3">
                                <div class="col-md-3">
                                    <label for="jumlah_anggota" class="form-label fw-semibold">Jumlah Anggota</label>
                                    <input type="number" id="jumlah_anggota" class="form-control"
                                        placeholder="Contoh: 50" name="jumlah_anggota" min="0"
                                        value="{{ old('jumlah_anggota', $sktpiagammt->jumlah_anggota) }}">
                                </div>

                                <div class="col-md-9">
                                    <label for="materi" class="form-label fw-semibold">Materi Kajian (Ketik lalu tekan
                                        Enter)</label>
                                    <select class="form-select" id="materi" name="materi[]" multiple="multiple"
                                        style="width: 100%;">
                                        @php
                                            $materiList = old('materi')
                                                ? old('materi')
                                                : ($sktpiagammt->materi
                                                    ? explode(', ', $sktpiagammt->materi)
                                                    : []);
                                        @endphp
                                        @foreach ($materiList as $item)
                                            <option value="{{ $item }}" selected>{{ $item }}</option>
                                        @endforeach
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
                                        name="mendaftar" required value="{{ old('mendaftar', $sktpiagammt->mendaftar) }}"
                                        placeholder="Pilih Tanggal">
                                </div>

                                <div class="col-md-6">
                                    <label for="mendaftar_ulang" class="form-label fw-semibold">Tanggal Mendaftar Ulang
                                        <span class="text-danger">*</span></label>
                                    <input type="text" id="mendaftar_ulang" class="form-control datepicker"
                                        name="mendaftar_ulang" required
                                        value="{{ old('mendaftar_ulang', $sktpiagammt->mendaftar_ulang) }}"
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
                                {{-- Reset button removed typically in edit as it might clear all pre-filled data or reset to original --}}
                                <button type="submit" class="btn btn-primary rounded-pill px-4">
                                    <i class="fas fa-save me-2"></i>Simpan Perubahan
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

                // Function to load Kelurahan
                function loadKelurahan(kecamatanId, selectedKelurahanId = null) {
                    $('#kelurahan').empty().append('<option value="">Pilih Kelurahan</option>').trigger('change');

                    if (kecamatanId) {
                        $.ajax({
                            url: "{{ route('get.kelurahan') }}",
                            type: "GET",
                            data: {
                                kecamatan_id: kecamatanId
                            },
                            dataType: 'json',
                            success: function(data) {
                                if (data && data.length > 0) {
                                    $.each(data, function(index, kelurahan) {
                                        var option = new Option(
                                            kelurahan.nama_kelurahan,
                                            kelurahan.id,
                                            false,
                                            // Set selected if matches
                                            selectedKelurahanId && kelurahan.id ==
                                            selectedKelurahanId
                                        );
                                        $('#kelurahan').append(option);
                                    });
                                } else {
                                    $('#kelurahan').append(new Option("Tidak ada kelurahan", "", false,
                                        false));
                                }
                                // Trigger change to update Select2 display
                                $('#kelurahan').trigger('change');
                            },
                            error: function(xhr) {
                                console.error("Error ambil kelurahan:", xhr.responseText);
                                $('#kelurahan').append(new Option("Error: Gagal mengambil data", "", false,
                                    false));
                            }
                        });
                    }
                }

                // Event handler untuk perubahan kecamatan
                $('#kecamatan').on('change', function() {
                    var kecamatanId = $(this).val();
                    // Saat kecamatan berubah manual, kita reset kelurahan (tanpa selected value)
                    // Kecuali ini trigger dari initial load, tapi initial load kita handle terpisah
                    loadKelurahan(kecamatanId);
                });

                // Inisialisasi data awal (Kecamatan & Kelurahan)
                const initialKecamatanId = "{{ old('kecamatan_id', $sktpiagammt->kecamatan_id) }}";
                const initialKelurahanId = "{{ old('kelurahan_id', $sktpiagammt->kelurahan_id) }}";

                if (initialKecamatanId) {
                    // Set kecamatan value (sudah di HTML selected, tapi buat memastikan Select2 sync)
                    // Note: select2 biasanya otomatis sync dengan atribut 'selected' HTML
                    // Panggil fungsi loadKelurahan dengan selected ID
                    loadKelurahan(initialKecamatanId, initialKelurahanId);
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

                // Auto-fill tanggal mendaftar ulang
                $('#mendaftar').on('change', function() {
                    let tanggalMendaftar;

                    if (this._flatpickr) {
                        const selectedDates = this._flatpickr.selectedDates;
                        if (selectedDates.length > 0) {
                            tanggalMendaftar = selectedDates[0];
                        }
                    } else {
                        tanggalMendaftar = new Date($(this).val());
                    }

                    if (tanggalMendaftar && !isNaN(tanggalMendaftar.getTime())) {
                        var tanggalMendaftarUlang = new Date(tanggalMendaftar);
                        tanggalMendaftarUlang.setFullYear(tanggalMendaftar.getFullYear() + 5);

                        var tahun = tanggalMendaftarUlang.getFullYear();
                        var bulan = (tanggalMendaftarUlang.getMonth() + 1).toString().padStart(2, '0');
                        var tanggal = tanggalMendaftarUlang.getDate().toString().padStart(2, '0');
                        var formattedDate = `${tahun}-${bulan}-${tanggal}`;

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
