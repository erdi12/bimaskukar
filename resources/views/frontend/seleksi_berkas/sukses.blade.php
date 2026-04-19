@extends('layout.frontend')

@section('title', 'Pengajuan Berhasil - ' . $seleksi->judul)

@push('styles')
<style>
    .ticket-box {
        background: linear-gradient(135deg, #2A9D8F, #264653);
        border-radius: 20px;
        position: relative;
        overflow: hidden;
    }
    .ticket-box::before {
        content: '';
        position: absolute;
        top: -50%; left: -50%;
        width: 200%; height: 200%;
        background: url('https://www.transparenttextures.com/patterns/cubes.png');
        opacity: 0.05;
    }
    .ticket-code {
        font-family: 'Courier New', monospace;
        font-size: 2.2rem;
        font-weight: 700;
        letter-spacing: 4px;
        color: white;
        text-shadow: 0 2px 4px rgba(0,0,0,0.2);
    }
    .step-item { opacity: .5; transition: opacity .3s; }
    .step-item.active { opacity: 1; }
    .wa-pulse { animation: pulse 2s infinite; }
    @keyframes pulse {
        0%, 100% { box-shadow: 0 0 0 0 rgba(37, 211, 102, 0.4); }
        50%       { box-shadow: 0 0 0 12px rgba(37, 211, 102, 0); }
    }
</style>
@endpush

@section('content')
<div class="container py-5 mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6 text-center">

            <!-- Success Icon -->
            <div class="mb-4">
                <div class="d-inline-flex align-items-center justify-content-center bg-success bg-opacity-10 rounded-circle"
                     style="width:100px;height:100px;">
                    <i class="fas fa-check-circle text-success" style="font-size:3rem;"></i>
                </div>
            </div>

            <h2 class="fw-bold text-dark mb-2">Pengajuan Berhasil!</h2>
            <p class="text-muted mb-4">
                Data berkas Anda untuk <strong>{{ $seleksi->judul }}</strong> telah berhasil diterima.
                Simpan kode tiket berikut untuk melacak status pengajuan Anda.
            </p>

            <!-- Ticket Box -->
            <div class="ticket-box p-5 mb-4 text-center position-relative">
                <small class="text-white-50 text-uppercase fw-bold d-block mb-2 letter-spacing-1">Kode Tiket Anda</small>
                <div class="ticket-code mb-3" id="kode-tiket">{{ $pengajuan->kode_tiket }}</div>
                <button type="button" class="btn btn-light btn-sm rounded-pill px-4" id="btn-copy"
                        data-kode="{{ $pengajuan->kode_tiket }}">
                    <i class="fas fa-copy me-1"></i>Salin Kode
                </button>
            </div>

            <!-- WA Info -->
            <div class="card border-0 shadow-sm rounded-4 mb-4 border-start border-4 border-success">
                <div class="card-body p-4 d-flex align-items-center gap-3 text-start">
                    <div class="wa-pulse bg-success text-white rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                         style="width:52px;height:52px;">
                        <i class="fab fa-whatsapp fa-lg"></i>
                    </div>
                    <div>
                        <div class="fw-bold text-dark">Kode Tiket Dikirim via WhatsApp</div>
                        <div class="text-muted small">
                            Kode tiket telah dikirim ke nomor
                            <strong>{{ $pengajuan->no_hp }}</strong>.
                            Jika tidak menerima, silakan screenshot halaman ini.
                        </div>
                    </div>
                </div>
            </div>

            @php
                // Format phone for wa.me
                $phone = preg_replace('/[^0-9]/', '', $pengajuan->no_hp);
                if (str_starts_with($phone, '0')) $phone = '62' . substr($phone, 1);

                $message = "Assalamu'alaikum,\n\nPengajuan berkas Anda untuk *{$seleksi->judul}* telah berhasil diterima.\n\n";
                $message .= "📋 *Kode Tiket:* {$pengajuan->kode_tiket}\n";
                $message .= "👤 *Nama:* {$pengajuan->nama_pengaju}\n";
                $message .= "📅 *Tanggal:* " . $pengajuan->created_at->translatedFormat('d F Y, H:i') . "\n";
                $message .= "📌 *Status:* Menunggu Verifikasi\n\n";
                $message .= "Gunakan kode tiket di atas untuk cek status berkas Anda di:\n";
                $message .= url('/cek-tiket') . "?kode={$pengajuan->kode_tiket}\n\n";
                $message .= "Terima kasih.";
            @endphp

            <!-- Action Buttons -->
            <div class="d-grid gap-3 mb-4">
                <a href="https://wa.me/{{ $phone }}?text={{ urlencode($message) }}" target="_blank"
                   class="btn btn-success btn-lg fw-bold rounded-pill py-3">
                    <i class="fab fa-whatsapp me-2 fa-lg"></i>Kirim Tiket ke WhatsApp Saya
                </a>
                <a href="{{ route('cek_tiket') }}?kode={{ $pengajuan->kode_tiket }}"
                   class="btn btn-outline-success btn-lg rounded-pill">
                    <i class="fas fa-search me-2"></i>Cek Status Berkas
                </a>
            </div>

            <div class="text-muted small">
                <i class="fas fa-info-circle me-1"></i>
                Proses verifikasi memerlukan waktu. Anda akan dihubungi melalui WhatsApp jika ada pemberitahuan.
            </div>

        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.getElementById('btn-copy').addEventListener('click', function() {
    const kode = this.dataset.kode;
    navigator.clipboard.writeText(kode).then(() => {
        this.innerHTML = '<i class="fas fa-check me-1"></i>Tersalin!';
        this.classList.replace('btn-light', 'btn-warning');
        setTimeout(() => {
            this.innerHTML = '<i class="fas fa-copy me-1"></i>Salin Kode';
            this.classList.replace('btn-warning', 'btn-light');
        }, 2000);
    });
});
</script>
@endpush
