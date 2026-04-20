@extends('layout.appv2')

@section('title', 'Detail Pengajuan - ' . $pengajuan->kode_tiket)

@section('content')
<div class="container-fluid mt-4">

    <div class="row align-items-center mb-4">
        <div class="col-md-7">
            <h3 class="mb-0 fw-bold text-dark">Detail Pengajuan</h3>
            <p class="text-muted mb-0">
                <code class="text-dark fw-bold">{{ $pengajuan->kode_tiket }}</code>
                &mdash; {{ $seleksi->judul }}
            </p>
        </div>
        <div class="col-md-5 text-end">
            <a href="{{ route('seleksi_berkas.pengajuan', $seleksi) }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>Kembali
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row g-4">
        <!-- Left: Data Pengaju & Berkas -->
        <div class="col-lg-8">

            <!-- Info Pengaju -->
            <div class="card shadow-sm border-0 rounded-3 mb-4">
                <div class="card-header bg-white py-3 border-bottom">
                    <h5 class="fw-bold mb-0 text-success"><i class="fas fa-user-circle me-2"></i>Informasi Pengaju</h5>
                </div>
                <div class="card-body p-4">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="text-muted small text-uppercase fw-bold">Kode Tiket</label>
                            <div class="fw-bold fs-5">
                                <code class="text-dark bg-light px-3 py-1 rounded">{{ $pengajuan->kode_tiket }}</code>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small text-uppercase fw-bold">Tanggal Pengajuan</label>
                            <div class="fw-bold text-dark">{{ $pengajuan->created_at->translatedFormat('d F Y, H:i') }}</div>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small text-uppercase fw-bold">Nama Pengaju</label>
                            <div class="fw-bold fs-5 text-dark">{{ $pengajuan->nama_pengaju }}</div>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small text-uppercase fw-bold">Nomor WhatsApp</label>
                            <div class="d-flex align-items-center gap-2">
                                <span class="fw-bold fs-5 text-dark" id="pengaju-hp">{{ $pengajuan->no_hp }}</span>
                                @php
                                    $phone = preg_replace('/[^0-9]/', '', $pengajuan->no_hp);
                                    if (str_starts_with($phone, '0')) $phone = '62' . substr($phone, 1);
                                @endphp
                                <a href="https://wa.me/{{ $phone }}" target="_blank" class="btn btn-sm btn-success rounded-pill px-3">
                                    <i class="fab fa-whatsapp me-1"></i>Chat WA
                                </a>
                            </div>
                        </div>

                        @if($pengajuan->data_isian)
                            @foreach($pengajuan->data_isian as $key => $field)
                                @php
                                    $fVal  = $field['value'] ?? '-';
                                    $fType = $field['type'] ?? 'text';
                                @endphp
                                @if($fType === 'signature')
                                    {{-- Tanda tangan: tampilkan full-width --}}
                                    <div class="col-12">
                                        <label class="text-muted small text-uppercase fw-bold">{{ $field['label'] ?? $key }}</label>
                                        @if($fVal && $fVal !== '-' && str_starts_with($fVal, 'data:image'))
                                            <div class="border rounded-3 p-2 bg-light d-inline-block mt-1">
                                                <img src="{{ $fVal }}" alt="Tanda Tangan" style="max-height:120px; max-width:100%; display:block;">
                                            </div>
                                        @else
                                            <div class="fw-semibold text-muted fst-italic">Tidak ada tanda tangan</div>
                                        @endif
                                    </div>
                                @elseif($fType === 'checkbox' && is_array($fVal))
                                    {{-- Checkbox multi-pilih --}}
                                    <div class="col-md-6">
                                        <label class="text-muted small text-uppercase fw-bold">{{ $field['label'] ?? $key }}</label>
                                        <div class="mt-1 d-flex flex-wrap gap-1">
                                            @foreach($fVal as $cbItem)
                                                <span class="badge bg-success rounded-pill px-2 py-1">
                                                    <i class="fas fa-check me-1"></i>{{ $cbItem }}
                                                </span>
                                            @endforeach
                                        </div>
                                    </div>
                                @else
                                    <div class="col-md-6">
                                        <label class="text-muted small text-uppercase fw-bold">{{ $field['label'] ?? $key }}</label>
                                        <div class="fw-semibold text-dark">
                                            @if(is_array($fVal))
                                                {{ implode(', ', $fVal) }}
                                            @else
                                                {{ $fVal }}
                                            @endif
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        @endif

                    </div>
                </div>
            </div>

            <!-- Berkas -->
            <div class="card shadow-sm border-0 rounded-3 mb-4">
                <div class="card-header bg-white py-3 border-bottom">
                    <h5 class="fw-bold mb-0 text-success"><i class="fas fa-paperclip me-2"></i>Berkas Pendukung</h5>
                </div>
                <div class="card-body p-4">
                    @if($pengajuan->berkas_files && count($pengajuan->berkas_files) > 0)
                        <div class="row g-3">
                            @foreach($pengajuan->berkas_files as $key => $berkas)
                                <div class="col-md-6">
                                    <div class="p-3 border rounded-3 bg-light h-100 d-flex flex-column">
                                        <div class="d-flex align-items-center mb-3">
                                            <div class="bg-white rounded-circle p-2 shadow-sm text-success me-3">
                                                <i class="fas fa-file-alt fa-lg"></i>
                                            </div>
                                            <h6 class="mb-0 fw-bold text-dark">{{ $berkas['label'] ?? $key }}</h6>
                                        </div>
                                        <div class="mt-auto">
                                            <button type="button" class="btn btn-outline-primary btn-sm w-100 btn-view-file"
                                                    data-url="{{ asset('storage/berkas_seleksi/' . $berkas['filename']) }}"
                                                    data-title="{{ $berkas['label'] ?? $key }}">
                                                <i class="fas fa-eye me-1"></i>Lihat File
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted text-center py-3">Tidak ada berkas yang diunggah.</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Right: Update Status & WA Notif -->
        <div class="col-lg-4">

            <!-- Status Card -->
            <div class="card shadow-sm border-0 rounded-3 mb-4 border-top border-5 border-{{ $pengajuan->status_badge }}">
                <div class="card-header bg-white py-3 border-bottom">
                    <h5 class="fw-bold mb-0 text-dark"><i class="fas fa-flag me-2"></i>Status Pengajuan</h5>
                </div>
                <div class="card-body p-4">
                    <div class="mb-3">
                        <label class="text-muted small text-uppercase fw-bold">Status Saat Ini</label>
                        <div class="mt-1">
                            <span class="badge bg-{{ $pengajuan->status_badge }} px-3 py-2 rounded-pill fs-6">
                                {{ $pengajuan->status_label }}
                            </span>
                        </div>
                    </div>

                    @if($pengajuan->catatan_admin)
                        <div class="alert alert-warning border-0 bg-warning bg-opacity-10 mb-3">
                            <small class="fw-bold text-dark d-block mb-1">Catatan Admin:</small>
                            <small>{{ $pengajuan->catatan_admin }}</small>
                        </div>
                    @endif

                    <form action="{{ route('pengajuan_berkas.update_status', $pengajuan) }}" method="POST" id="form-update-status">
                        @csrf
                        <input type="hidden" id="hidden-phone" value="{{ $phone }}">
                        <input type="hidden" id="hidden-name" value="{{ $pengajuan->nama_pengaju }}">
                        <input type="hidden" id="hidden-tiket" value="{{ $pengajuan->kode_tiket }}">
                        <input type="hidden" id="hidden-judul" value="{{ $seleksi->judul }}">

                        <div class="mb-3">
                            <label class="form-label small fw-bold">Ubah Status</label>
                            <select name="status" class="form-select" id="select-status">
                                <option value="menunggu"  {{ $pengajuan->status==='menunggu' ?'selected':'' }}>⏳ Menunggu Verifikasi</option>
                                <option value="diproses"  {{ $pengajuan->status==='diproses' ?'selected':'' }}>⚙️ Sedang Diproses</option>
                                <option value="diterima"  {{ $pengajuan->status==='diterima' ?'selected':'' }}>✅ Diterima</option>
                                <option value="ditolak"   {{ $pengajuan->status==='ditolak'  ?'selected':'' }}>❌ Ditolak</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Catatan untuk Pemohon</label>
                            <textarea name="catatan_admin" id="catatan-admin" class="form-control" rows="4"
                                      placeholder="Tulis catatan atau alasan keputusan...">{{ $pengajuan->catatan_admin }}</textarea>

                            @if($pengajuan->no_hp)
                                <div class="mt-2 text-end">
                                    <button type="button" class="btn btn-sm btn-success rounded-pill px-3" id="btn-wa-notif">
                                        <i class="fab fa-whatsapp me-1"></i>Kirim Notifikasi WA
                                    </button>
                                </div>
                            @endif
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-success fw-bold">
                                <i class="fas fa-save me-2"></i>Simpan Status
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Info Card -->
            <div class="card border-0 bg-info bg-opacity-10 rounded-3">
                <div class="card-body d-flex align-items-start p-4">
                    <i class="fas fa-info-circle fa-lg text-info me-3 mt-1"></i>
                    <small class="text-muted lh-sm">
                        Kode tiket <strong>{{ $pengajuan->kode_tiket }}</strong> dikirim ke pemohon saat pengajuan.
                        Pemohon dapat cek status di <a href="{{ route('cek_tiket') }}" target="_blank">/cek-tiket</a>.
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- File Viewer Modal -->
<div class="modal fade" id="fileViewerModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="fileViewerModalLabel">Lihat Dokumen</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0 bg-light text-center">
                <div id="fileContentContainer" style="height:80vh;display:flex;align-items:center;justify-content:center;overflow:hidden;">
                    <span class="text-muted">Memuat...</span>
                </div>
            </div>
            <div class="modal-footer">
                <a href="#" id="downloadLink" class="btn btn-primary" target="_blank">
                    <i class="fas fa-download me-1"></i>Download
                </a>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {

    // File viewer
    $('.btn-view-file').on('click', function() {
        const url   = $(this).data('url');
        const title = $(this).data('title');
        const ext   = url.split('.').pop().toLowerCase();
        const container = $('#fileContentContainer');

        $('#fileViewerModalLabel').text(title);
        $('#downloadLink').attr('href', url);
        container.html('<span class="text-muted"><i class="fas fa-spinner fa-spin me-2"></i>Memuat...</span>');

        if (['jpg','jpeg','png','gif','webp'].includes(ext)) {
            container.html('<img src="' + url + '" style="max-height:80vh;max-width:100%;object-fit:contain;">');
        } else if (ext === 'pdf') {
            container.html('<iframe src="' + url + '" width="100%" height="100%" style="border:none;min-height:80vh;"></iframe>');
        } else {
            container.html('<a href="' + url + '" target="_blank" class="btn btn-primary"><i class="fas fa-download me-1"></i>Unduh File</a>');
        }

        new bootstrap.Modal(document.getElementById('fileViewerModal')).show();
    });

    // WA Notif Button
    $('#btn-wa-notif').on('click', function() {
        const phone   = $('#hidden-phone').val();
        const name    = $('#hidden-name').val();
        const tiket   = $('#hidden-tiket').val();
        const judul   = $('#hidden-judul').val();
        const status  = $('#select-status option:selected').text().trim();
        const catatan = $('#catatan-admin').val().trim();

        let message = "Assalamu'alaikum Sdr/i. " + name + ",\n\n";
        message += "Kami memberitahukan bahwa pengajuan berkas Anda untuk *" + judul + "* telah diperbarui.\n\n";
        message += "📋 *Kode Tiket:* " + tiket + "\n";
        message += "📌 *Status:* " + status + "\n";

        if (catatan) {
            message += "\n📝 *Catatan:*\n" + catatan + "\n";
        }

        message += "\nCek status berkas Anda di:\n{{ url('/cek-tiket') }}?kode=" + tiket;
        message += "\n\nTerima kasih.";

        const url = "https://wa.me/" + phone + "?text=" + encodeURIComponent(message);
        window.open(url, '_blank');
    });
});
</script>
@endpush
