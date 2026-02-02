@extends('layout.appv2')
@section('title', 'Tambah Layanan')
@section('cms', 'active open')
@section('mnj_layanan', 'active')

@section('content')
    <div class="content">
        <div class="page-title mb-4">
            <h3>Tambah Layanan</h3>
            <p class="text-muted">Buat layanan baru beserta SOP dan persyaratannya.</p>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <form action="{{ route('layanan.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-bold">Judul Layanan <span class="text-danger">*</span></label>
                        <input type="text" name="judul" class="form-control" required
                            placeholder="Contoh: Penyuluhan Agama Islam" value="{{ old('judul') }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Ikon (Upload File Lottie .json)</label>
                        <input type="file" name="ikon_file" class="form-control" accept=".json">
                        <div class="form-text">Atau gunakan FontAwesome Class di bawah ini jika tidak ada file JSON.</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Ikon (FontAwesome Class / Link)</label>
                        <input type="text" name="ikon" class="form-control"
                            placeholder="Contoh: fa-solid fa-mosque atau URL https://..." value="{{ old('ikon') }}">
                        <div class="form-text">Jika upload file dipilih, input teks ini akan diabaikan.</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Deskripsi Singkat <span class="text-danger">*</span></label>
                        <textarea name="deskripsi_singkat" class="form-control" rows="3" required
                            placeholder="Deskripsi pendek yang muncul di halaman depan.">{{ old('deskripsi_singkat') }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Konten Lengkap (SOP & Persyaratan)</label>
                        <textarea name="konten" id="summernote" class="form-control">{{ old('konten') }}</textarea>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary px-4">Simpan</button>
                        <a href="{{ route('layanan.index') }}" class="btn btn-secondary px-4">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
    <style>
        .note-editor .note-toolbar {
            background-color: #f8f9fa;
            border-bottom: 1px solid #dee2e6;
        }
    </style>
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#summernote').summernote({
                placeholder: 'Tuliskan detail SOP, langkah-langkah, dan persyaratan layanan di sini...',
                tabsize: 2,
                height: 300,
                toolbar: [
                    ['style', ['style']],
                    ['font', ['bold', 'underline', 'clear']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['table', ['table']],
                    ['insert', ['link']],
                    ['view', ['fullscreen', 'codeview', 'help']]
                ]
            });
        });
    </script>
@endpush
