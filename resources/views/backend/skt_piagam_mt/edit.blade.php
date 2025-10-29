@extends('layout.app')

@section('title', 'Edit SKT/Piagam MT')

@section('content')
<div class="main-content container-fluid">
    <div class="page-title">
        <h3>Edit Data Majelis Ta'lim</h3>
    </div>
    <section id="multiple-column-form">
        <div class="row match-height">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Form Edit Majelis Ta'lim</h4>
                    </div>
                    <div class="card-content">
                        <div class="card-body">
                            <form class="form" method="POST" action="{{ route('skt_piagam_mt.update', $sktpiagammt->id) }}" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                
                                <div class="row">
                                    <div class="col-md-6 col-6">
                                        <div class="form-group">
                                            <label for="nomor_statistik">Nomor Statistik Majelis Ta'lim</label>
                                            <input readonly type="text" id="nomor_statistik" class="form-control @error('nomor_statistik') is-invalid @enderror" 
                                                name="nomor_statistik" value="{{ old('nomor_statistik', $sktpiagammt->nomor_statistik) }}">
                                            @error('nomor_statistik')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row mt-3">
                                    <div class="col-md-6 col-6">
                                        <div class="form-group">
                                            <label for="nama_majelis">Nama Majelis Ta'lim</label>
                                            <input type="text" id="nama_majelis" class="form-control @error('nama_majelis') is-invalid @enderror" 
                                                name="nama_majelis" value="{{ old('nama_majelis', $sktpiagammt->nama_majelis) }}">
                                            @error('nama_majelis')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label for="alamat">Alamat Majelis Ta'lim (Tanpa Kelurahan dan Kecamatan)</label>
                                            <input type="text" id="alamat" class="form-control @error('alamat') is-invalid @enderror" 
                                                name="alamat" value="{{ old('alamat', $sktpiagammt->alamat) }}">
                                            @error('alamat')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label for="kecamatan">Kecamatan</label>
                                            <select class="form-select @error('kecamatan_id') is-invalid @enderror" id="kecamatan" name="kecamatan_id" style="width: 100%;">
                                                @foreach($kecamatans as $kecamatan)
                                                    <option value="{{ $kecamatan->id }}" {{ old('kecamatan_id', $sktpiagammt->kecamatan_id) == $kecamatan->id ? 'selected' : '' }}>
                                                        {{ ucwords($kecamatan->kecamatan) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('kecamatan_id')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label for="kelurahan">Kelurahan</label>
                                            <select class="form-select @error('kelurahan_id') is-invalid @enderror" id="kelurahan" name="kelurahan_id" style="width: 100%;">
                                                @foreach(\App\Models\Kelurahan::where('kecamatan_id', old('kecamatan_id', $sktpiagammt->kecamatan_id))->get() as $kelurahan)
                                                    <option value="{{ $kelurahan->id }}" {{ old('kelurahan_id', $sktpiagammt->kelurahan_id) == $kelurahan->id ? 'selected' : '' }}>
                                                        {{ $kelurahan->nama_kelurahan }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('kelurahan_id')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label for="tanggal-berdiri">Tanggal Berdiri</label>
                                            <input type="date" id="tanggal-berdiri" class="form-control @error('tanggal_berdiri') is-invalid @enderror" 
                                                name="tanggal_berdiri" value="{{ old('tanggal_berdiri', $sktpiagammt->tanggal_berdiri) }}">
                                            @error('tanggal_berdiri')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label for="status">Status</label>
                                            <fieldset class="form-group">
                                                <select class="form-select @error('status') is-invalid @enderror" id="status" name="status">
                                                    <option value="aktif" {{ old('status', $sktpiagammt->status) == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                                    <option value="nonaktif" {{ old('status', $sktpiagammt->status) == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                                                </select>
                                                @error('status')
                                                    <span class="invalid-feedback">{{ $message }}</span>
                                                @enderror
                                            </fieldset>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label for="ketua">Nama Ketua</label>
                                            <input type="text" id="ketua" class="form-control @error('ketua') is-invalid @enderror" 
                                                name="ketua" value="{{ old('ketua', $sktpiagammt->ketua) }}">
                                            @error('ketua')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label for="no_hp">Nomor Handphone</label>
                                            <input type="text" id="no_hp" class="form-control @error('no_hp') is-invalid @enderror" 
                                                name="no_hp" value="{{ old('no_hp', $sktpiagammt->no_hp) }}">
                                            @error('no_hp')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label for="mendaftar">Tanggal Mendaftar</label>
                                            <input type="date" id="mendaftar" class="form-control @error('mendaftar') is-invalid @enderror" 
                                                name="mendaftar" value="{{ old('mendaftar', $sktpiagammt->mendaftar) }}">
                                            @error('mendaftar')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label for="mendaftar_ulang">Tanggal Mendaftar Ulang</label>
                                            <input type="date" id="mendaftar_ulang" class="form-control @error('mendaftar_ulang') is-invalid @enderror" 
                                                name="mendaftar_ulang" value="{{ old('mendaftar_ulang', $sktpiagammt->mendaftar_ulang) }}">
                                            @error('mendaftar_ulang')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-12 d-flex justify-content-end">
                                    <button type="submit" class="btn btn-primary me-1 mb-1">Update</button>
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
@endsection

@push('scripts')
<script>
    // Script untuk mengambil kelurahan berdasarkan kecamatan yang dipilih
    $(document).ready(function() {
        $('#kecamatan').on('change', function() {
            var kecamatanId = $(this).val();
            if (kecamatanId) {
                $.ajax({
                    url: "{{ route('get.kelurahan') }}",
                    type: "GET",
                    data: { kecamatan_id: kecamatanId },
                    dataType: "json",
                    success: function(data) {
                        $('#kelurahan').empty();
                        $('#kelurahan').append('<option value="">Pilih Kelurahan</option>');
                        $.each(data, function(key, value) {
                            $('#kelurahan').append('<option value="' + value.id + '">' + value.nama_kelurahan + '</option>');
                        });
                    },
                    error: function(xhr) {
                        console.error("Error ambil kelurahan:", xhr.responseText);
                        $('#kelurahan').append('<option value="">Error: Gagal mengambil data</option>');
                    }
                });
            } else {
                $('#kelurahan').empty();
                $('#kelurahan').append('<option value="">Pilih Kelurahan</option>');
            }
        });
    });
</script>
@endpush
