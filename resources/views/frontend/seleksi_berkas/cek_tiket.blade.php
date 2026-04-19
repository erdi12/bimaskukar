@extends('layout.frontend')

@section('title', 'Cek Status Berkas - Bimas Kemenag Kukar')

@push('styles')
<style>
    .card:hover { transform: none !important; box-shadow: 0 .125rem .25rem rgba(0,0,0,.075) !important; }
    .status-timeline { position: relative; }
    .status-step {
        display: flex; align-items: flex-start; gap: 12px; padding: 12px 0;
        opacity: .35;
        transition: opacity .3s;
    }
    .status-step.active { opacity: 1; }
    .status-step.passed { opacity: .6; }
    .status-dot {
        width: 36px; height: 36px; border-radius: 50%; flex-shrink: 0;
        display: flex; align-items: center; justify-content: center;
        font-size: 14px; font-weight: 700;
        background: #dee2e6; color: #6c757d;
    }
    .status-step.active .status-dot { background: var(--primary); color: white; }
    .status-step.passed .status-dot { background: #198754; color: white; }
    .status-step.rejected .status-dot { background: #dc3545; color: white; }
</style>
@endpush

@section('content')
<div class="container py-5 mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">

            <div class="text-center mb-5">
                <div class="d-inline-flex align-items-center justify-content-center bg-success bg-opacity-10 rounded-circle mb-3"
                     style="width:80px;height:80px;">
                    <i class="fas fa-ticket-alt text-success" style="font-size:2rem;"></i>
                </div>
                <h3 class="fw-bold text-dark">Cek Status Berkas</h3>
                <p class="text-muted">Masukkan kode tiket yang diterima untuk melihat status pengajuan berkas Anda.</p>
            </div>

            <!-- Search Form -->
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body p-4">
                    <form method="GET" action="{{ route('cek_tiket') }}">
                        <label class="form-label fw-bold">Kode Tiket</label>
                        <div class="input-group input-group-lg">
                            <span class="input-group-text bg-white border-end-0">
                                <i class="fas fa-ticket-alt text-success"></i>
                            </span>
                            <input type="text" name="kode" class="form-control border-start-0 text-uppercase letter-spacing-2"
                                   value="{{ $kode ?? '' }}"
                                   placeholder="Contoh: ABC-DEFG-HIJ"
                                   style="letter-spacing: 2px;"
                                   required>
                            <button class="btn btn-success px-4 fw-bold" type="submit">
                                <i class="fas fa-search me-1"></i>Cek
                            </button>
                        </div>
                        <div class="form-text">Kode tiket dikirim ke nomor WhatsApp Anda saat pengajuan.</div>
                    </form>
                </div>
            </div>

            @if(isset($kode) && $kode)
                @if($pengajuan)
                    <!-- Result Found -->
                    <div class="card border-0 shadow-sm rounded-4 mb-4">
                        <div class="card-header bg-white py-3 border-bottom rounded-top-4">
                            <div class="d-flex align-items-center gap-3">
                                <div class="bg-success bg-opacity-10 rounded-3 p-2">
                                    <i class="fas fa-folder-open text-success"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0 fw-bold">{{ $pengajuan->seleksiBerkas->judul ?? 'Seleksi Berkas' }}</h6>
                                    <small class="text-muted">Kode: <code>{{ $pengajuan->kode_tiket }}</code></small>
                                </div>
                            </div>
                        </div>
                        <div class="card-body p-4">
                            <!-- Identity -->
                            <div class="row g-3 mb-4">
                                <div class="col-6">
                                    <label class="text-muted small text-uppercase fw-bold">Nama Pengaju</label>
                                    <div class="fw-bold text-dark">{{ $pengajuan->nama_pengaju }}</div>
                                </div>
                                <div class="col-6">
                                    <label class="text-muted small text-uppercase fw-bold">Tanggal Pengajuan</label>
                                    <div class="fw-bold text-dark">{{ $pengajuan->created_at->translatedFormat('d F Y') }}</div>
                                </div>
                            </div>

                            <!-- Status Badge -->
                            <div class="text-center mb-4 p-4 bg-light rounded-3">
                                <div class="text-muted small mb-2">STATUS SAAT INI</div>
                                <span class="badge bg-{{ $pengajuan->status_badge }} fs-5 px-4 py-2 rounded-pill">
                                    @if($pengajuan->status === 'menunggu') ⏳
                                    @elseif($pengajuan->status === 'diproses') ⚙️
                                    @elseif($pengajuan->status === 'diterima') ✅
                                    @else ❌ @endif
                                    {{ $pengajuan->status_label }}
                                </span>
                            </div>

                            <!-- Status Timeline -->
                            <div class="status-timeline">
                                @php
                                    $steps = [
                                        'menunggu' => ['label' => 'Menunggu Verifikasi', 'sub' => 'Pengajuan Anda telah diterima sistem', 'icon' => 'fa-hourglass-half'],
                                        'diproses' => ['label' => 'Sedang Diproses', 'sub' => 'Admin sedang meninjau berkas Anda', 'icon' => 'fa-cog fa-spin'],
                                        'diterima' => ['label' => 'Diterima', 'sub' => 'Selamat! Pengajuan Anda diterima', 'icon' => 'fa-check-circle'],
                                    ];
                                    $statusOrder = ['menunggu', 'diproses', 'diterima'];
                                    $currentIdx = array_search($pengajuan->status, $statusOrder);
                                @endphp

                                @if($pengajuan->status !== 'ditolak')
                                    @foreach($steps as $key => $step)
                                        @php
                                            $stepIdx = array_search($key, $statusOrder);
                                            $class = 'status-step';
                                            if ($stepIdx < $currentIdx) $class .= ' passed';
                                            elseif ($stepIdx === $currentIdx) $class .= ' active';
                                        @endphp
                                        <div class="{{ $class }}">
                                            <div class="status-dot">
                                                @if($stepIdx < $currentIdx)
                                                    <i class="fas fa-check"></i>
                                                @elseif($stepIdx === $currentIdx)
                                                    <i class="fas {{ $step['icon'] }}"></i>
                                                @else
                                                    {{ $loop->iteration }}
                                                @endif
                                            </div>
                                            <div class="pt-1">
                                                <div class="fw-bold">{{ $step['label'] }}</div>
                                                <small class="text-muted">{{ $step['sub'] }}</small>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="status-step active rejected">
                                        <div class="status-dot"><i class="fas fa-times"></i></div>
                                        <div class="pt-1">
                                            <div class="fw-bold text-danger">Pengajuan Ditolak</div>
                                            <small class="text-muted">Mohon maaf, pengajuan Anda tidak dapat diproses lebih lanjut.</small>
                                        </div>
                                    </div>
                                @endif
                            </div>

                            @if($pengajuan->catatan_admin)
                                <div class="alert alert-{{ $pengajuan->status === 'ditolak' ? 'danger' : 'info' }} border-0 mt-4">
                                    <strong><i class="fas fa-comment-alt me-2"></i>Catatan dari Admin:</strong>
                                    <p class="mb-0 mt-1">{{ $pengajuan->catatan_admin }}</p>
                                </div>
                            @endif

                            <div class="text-muted small text-center mt-4">
                                <i class="fas fa-sync-alt me-1"></i>Terakhir diperbarui: {{ $pengajuan->updated_at->translatedFormat('d F Y, H:i') }}
                            </div>
                        </div>
                    </div>
                @else
                    <!-- Not Found -->
                    <div class="card border-0 shadow-sm rounded-4 text-center p-5">
                        <i class="fas fa-search fa-3x text-muted opacity-25 mb-3"></i>
                        <h5 class="fw-bold text-dark">Kode Tiket Tidak Ditemukan</h5>
                        <p class="text-muted">Tiket dengan kode <code>{{ strtoupper($kode) }}</code> tidak ditemukan di sistem kami.</p>
                        <small class="text-muted">Pastikan kode tiket yang dimasukkan sudah benar (termasuk tanda strip).</small>
                    </div>
                @endif
            @endif

        </div>
    </div>
</div>
@endsection
