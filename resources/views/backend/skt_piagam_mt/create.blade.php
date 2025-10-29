@extends('layout.app')

@section('content')
<div class="main-content container-fluid">
    <div class="page-title">
        <h3>Tambah Data Majelis Ta'lim</h3>
    </div>
    <section id="multiple-column-form">
        <div class="row match-height">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Form Tambah Majelis Ta'lim</h4>
                    </div>
                    <div class="card-content">
                        <div class="card-body">
                            <form class="form" method="POST" action="{{ route('skt_piagam_mt.store') }}">
                                @csrf
                                <div class="row">
                                    <div class="col-md-6 col-6">
                                        <div class="form-group">
                                            <label for="nomor_statistik">Nomor Statistik Majelis Ta'lim</label>
                                            <div class="input-group">
                                                <input type="text" id="nomor_statistik" class="form-control @error('nomor_statistik') is-invalid @enderror" placeholder="Nomor akan otomatis terisi setelah memilih kecamatan"
                                                    name="nomor_statistik" readonly required value="{{ old('nomor_statistik') }}">
                                                <button class="btn btn-outline-secondary" type="button" id="btn-edit-nomor">
                                                    <i class="bi bi-pencil"></i> Edit
                                                </button>
                                            </div>
                                            @error('nomor_statistik')
                                                <div class="invalid-feedback d-block">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                            <small class="text-muted">Nomor statistik akan otomatis terisi setelah memilih kecamatan</small>
                                            <div id="nomor-loading" class="mt-1" style="display: none;">
                                                <div class="spinner-border spinner-border-sm text-primary" role="status">
                                                    <span class="visually-hidden">Loading...</span>
                                                </div>
                                                <small class="ms-1">Mengambil nomor statistik...</small>
                                            </div>
                                            <div id="nomor-error" class="mt-1 text-danger" style="display: none;"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-md-6 col-6">
                                        <div class="form-group">
                                            <label for="nama_majelis">Nama Majelis Ta'lim</label>
                                            <input type="text" id="nama_majelis" class="form-control" placeholder="Nama Majelis Ta'lim"
                                                name="nama_majelis" required value="{{ old('nama_majelis') }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label for="alamat">Alamat Majelis Ta'lim (Tanpa Kelurahan dan Kecamatan)</label>
                                            <input type="text" id="alamat" class="form-control" placeholder="Alamat"
                                                name="alamat" required value="{{ old('alamat') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label for="kecamatan">Kecamatan</label>
                                            <select class="form-select" id="kecamatan" name="kecamatan_id" style="width: 100%;" required>
                                                <option value="">Pilih Kecamatan</option>
                                                @foreach($kecamatans as $kecamatan)
                                                    <option value="{{ $kecamatan->id }}" {{ old('kecamatan_id') == $kecamatan->id ? 'selected' : '' }}>{{ucwords($kecamatan->kecamatan) }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label for="kelurahan">Kelurahan</label>
                                            <select class="form-select" id="kelurahan" name="kelurahan_id" style="width: 100%;" required>
                                                <option value="">Pilih Kelurahan</option>
                                                <!-- Kelurahan options will be populated via AJAX -->
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label for="tanggal-berdiri">Tanggal Berdiri</label>
                                            <input type="date" id="tanggal-berdiri" class="form-control" name="tanggal_berdiri" required value="{{ old('tanggal_berdiri') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label for="status">Status</label>
                                            <fieldset class="form-group">
                                                <select class="form-select" id="status" name="status" required>
                                                    <option value="">Pilih Status</option>
                                                    <option value="aktif" {{ old('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                                    <option value="nonaktif" {{ old('status') == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                                                </select>
                                            </fieldset>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label for="ketua">Nama Ketua</label>
                                            <input type="text" id="ketua" class="form-control" placeholder="Nama Ketua Majelis Ta'lim"
                                                name="ketua" required value="{{ old('ketua') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label for="no_hp">Nomor Handphone</label>
                                            <input type="text" id="no_hp" class="form-control" placeholder="Nomor Handphone Ketua Majelis Ta'lim"
                                                name="no_hp" required value="{{ old('no_hp') }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label for="mendaftar">Tanggal Mendaftar</label>
                                            <input type="date" id="mendaftar" class="form-control" name="mendaftar" required value="{{ old('mendaftar') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label for="mendaftar_ulang">Tanggal Mendaftar Ulang</label>
                                            <input type="date" id="mendaftar_ulang" class="form-control" name="mendaftar_ulang" required value="{{ old('mendaftar_ulang') }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 d-flex justify-content-end">
                                    <button type="submit" class="btn btn-primary me-1 mb-1">Submit</button>
                                    <button type="reset" class="btn btn-light-secondary me-1 mb-1">Reset</button>
                                    <a href="{{ route('skt_piagam_mt.index') }}" class="btn btn-danger me-1 mb-1">Cancel</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>


@push('scripts')
<script>
$(document).ready(function() {
    // Inisialisasi Select2
    $('#kecamatan').select2({
        placeholder: "Pilih Kecamatan",
        allowClear: true
    });

    $('#kelurahan').select2({
        placeholder: "Pilih Kelurahan",
        allowClear: true
    });

    // Event handler untuk perubahan kecamatan
    $('#kecamatan').on('change', function() {
        var kecamatanId = $(this).val();
        console.log('Kecamatan changed:', kecamatanId);

        // Reset kelurahan
        $('#kelurahan').empty().append('<option value="">Pilih Kelurahan</option>').trigger('change');

        if (kecamatanId) {
            // Ambil kelurahan
            $.ajax({
                url: "/api/kelurahans/" + kecamatanId,
                type: "GET",
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
                        $('#kelurahan').append(new Option("Tidak ada kelurahan", "", false, false));
                    }
                    $('#kelurahan').trigger('change');
                },
                error: function(xhr) {
                    console.error("Error ambil kelurahan:", xhr.responseText);
                    $('#kelurahan').append(new Option("Error: Gagal mengambil data", "", false, false));
                }
            });

            // Loading nomor statistik
            $('#nomor-loading').show();
            $('#nomor-error').hide();

            // Ambil nomor statistik otomatis
            $.ajax({
                url: "/get-next-nomor-statistik",
                type: "GET",
                dataType: 'json',
                data: { kecamatan_id: kecamatanId },
                success: function(data) {
                    $('#nomor-loading').hide();
                    console.log("Response nomor statistik:", data);

                    if (data && data.nomor_statistik) {
                        $('#nomor_statistik').val(data.nomor_statistik);
                    } else {
                        $('#nomor-error').text('Nomor statistik tidak ditemukan').show();
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
    $('#btn-edit-nomor').html('<i class="bi bi-pencil"></i> Edit');
    
    $('#btn-edit-nomor').on('click', function() {
        var $input = $('#nomor_statistik');
        if ($input.prop('readonly')) {
            $input.prop('readonly', false);
            $(this).html('<i class="bi bi-check"></i> Selesai');
        } else {
            $input.prop('readonly', true);
            $(this).html('<i class="bi bi-pencil"></i> Edit');
        }
    });
    
    // Auto-fill tanggal mendaftar ulang
    $('#mendaftar').on('change', function() {
        var tanggalMendaftar = new Date($(this).val());
        if (!isNaN(tanggalMendaftar.getTime())) {
            var tanggalMendaftarUlang = new Date(tanggalMendaftar);
            tanggalMendaftarUlang.setFullYear(tanggalMendaftar.getFullYear() + 5);
            
            var tahun = tanggalMendaftarUlang.getFullYear();
            var bulan = (tanggalMendaftarUlang.getMonth() + 1).toString().padStart(2, '0');
            var tanggal = tanggalMendaftarUlang.getDate().toString().padStart(2, '0');
            
            $('#mendaftar_ulang').val(`${tahun}-${bulan}-${tanggal}`);
        }
    });
});
</script>
@endpush


@endsection