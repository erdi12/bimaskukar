@extends('layout.appv2')

@section('title', 'Edit Pegawai')

@section('content')
    <div class="content">
        <div class="page-title mb-4">
            <h3>Edit Pegawai</h3>
            <p class="text-muted">Perbarui data pegawai atau pejabat.</p>
        </div>

        <div class="row">
            <div class="col-md-8 mx-auto">
                <div class="card card-modern">
                    <div class="card-header">
                        <h5 class="section-title-modern mb-0">Form Edit Pegawai</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('pegawai.update', $pegawai->id) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label class="form-label">Nama Lengkap</label>
                                <input type="text" name="nama"
                                    class="form-control @error('nama') is-invalid @enderror"
                                    value="{{ old('nama', $pegawai->nama) }}" required>
                                @error('nama')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">NIP (Opsional)</label>
                                    <input type="text" name="nip" class="form-control"
                                        value="{{ old('nip', $pegawai->nip) }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Jabatan</label>
                                    <input type="text" name="jabatan"
                                        class="form-control @error('jabatan') is-invalid @enderror"
                                        value="{{ old('jabatan', $pegawai->jabatan) }}" required>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Foto Profil</label>
                                @if ($pegawai->foto)
                                    <div class="mb-2">
                                        <img src="{{ asset('uploads/pegawai/' . $pegawai->foto) }}"
                                            class="img-thumbnail rounded" width="100">
                                    </div>
                                @endif
                                <input type="file" name="foto"
                                    class="form-control @error('foto') is-invalid @enderror" accept="image/*">
                                <div class="form-text">Biarkan kosong jika tidak ingin mengubah foto.</div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Urutan Tampil</label>
                                <input type="number" name="urutan" class="form-control"
                                    value="{{ old('urutan', $pegawai->urutan) }}">
                            </div>

                            <div class="mb-3 bg-light p-3 rounded border">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" role="switch" id="is_kepala"
                                        name="is_kepala" {{ old('is_kepala', $pegawai->is_kepala) ? 'checked' : '' }}>
                                    <label class="form-check-label fw-bold" for="is_kepala">Tandai sebagai Kepala Seksi /
                                        Pimpinan</label>
                                </div>

                                <div class="mt-3 {{ old('is_kepala', $pegawai->is_kepala) ? '' : 'd-none' }}"
                                    id="sambutan_area">
                                    <label class="form-label">Isi Sambutan</label>
                                    <textarea name="sambutan" class="form-control" rows="5">{{ old('sambutan', $pegawai->sambutan) }}</textarea>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end gap-2 mt-4">
                                <a href="{{ route('pegawai.index') }}" class="btn btn-secondary rounded-pill">Batal</a>
                                <button type="submit" class="btn btn-primary rounded-pill px-4">Simpan Perubahan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Show/Hide Sambutan based on switch
        const toggleSambutan = () => {
            if ($('#is_kepala').is(':checked')) {
                $('#sambutan_area').removeClass('d-none');
            } else {
                $('#sambutan_area').addClass('d-none');
            }
        }

        $('#is_kepala').on('change', toggleSambutan);
    </script>
@endpush
