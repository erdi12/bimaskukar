@extends('layout.appv2')

@section('title', 'Pengajuan Berkas - ' . $seleksi->judul)

@section('content')
<div class="container-fluid mt-4">

    <div class="row align-items-center mb-4">
        <div class="col-md-7">
            <h3 class="mb-0 fw-bold text-dark">Pengajuan Berkas</h3>
            <p class="text-muted mb-0">{{ $seleksi->judul }}</p>
        </div>
        <div class="col-md-5 text-end d-flex justify-content-end gap-2">
            @if($seleksi->isBuka())
                <span class="badge bg-success rounded-pill px-3 py-2 d-flex align-items-center">
                    <i class="fas fa-circle me-1" style="font-size:8px;"></i>BUKA
                </span>
            @else
                <span class="badge bg-secondary rounded-pill px-3 py-2 d-flex align-items-center">TUTUP</span>
            @endif
            <a href="{{ route('seleksi_berkas.show', $seleksi->slug) }}" target="_blank"
               class="btn btn-outline-success btn-sm">
                <i class="fas fa-external-link-alt me-1"></i>Buka Form Publik
            </a>
            <a href="{{ route('seleksi_berkas.index') }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left me-1"></i>Kembali
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Stats Row -->
    <div class="row g-3 mb-4">
        @php
            $counts = $pengajuans->groupBy('status');
            $statuses = [
                'menunggu' => ['label'=>'Menunggu', 'color'=>'warning', 'icon'=>'fa-hourglass-half'],
                'diproses' => ['label'=>'Diproses', 'color'=>'info', 'icon'=>'fa-spinner'],
                'diterima' => ['label'=>'Diterima', 'color'=>'success', 'icon'=>'fa-check-circle'],
                'ditolak'  => ['label'=>'Ditolak',  'color'=>'danger', 'icon'=>'fa-times-circle'],
            ];
        @endphp
        @foreach($statuses as $key => $info)
            <div class="col-6 col-md-3">
                <div class="card border-0 shadow-sm text-center py-3">
                    <div class="text-{{ $info['color'] }} fs-3 fw-bold">{{ ($counts[$key] ?? collect())->count() }}</div>
                    <div class="text-muted small"><i class="fas {{ $info['icon'] }} me-1"></i>{{ $info['label'] }}</div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Table -->
    <div class="card shadow-sm border-0 rounded-3">
        <div class="card-header bg-white py-3 border-bottom">
            <h5 class="fw-bold mb-0 text-dark"><i class="fas fa-inbox me-2 text-success"></i>Daftar Pengajuan</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="table-pengajuan">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4 py-3">#</th>
                            <th class="py-3">Kode Tiket</th>
                            <th class="py-3">Nama Pengaju</th>
                            <th class="py-3">No HP</th>
                            <th class="py-3">Status</th>
                            <th class="py-3">Tanggal</th>
                            <th class="py-3 pe-4 text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pengajuans as $i => $p)
                            <tr>
                                <td class="ps-4 text-muted small">{{ $i + 1 }}</td>
                                <td>
                                    <code class="bg-light px-2 py-1 rounded text-dark fw-bold">{{ $p->kode_tiket }}</code>
                                </td>
                                <td class="fw-bold">{{ $p->nama_pengaju }}</td>
                                <td>
                                    {{ $p->no_hp }}
                                    @php
                                        $phone = preg_replace('/[^0-9]/', '', $p->no_hp);
                                        if (str_starts_with($phone, '0')) $phone = '62' . substr($phone, 1);
                                    @endphp
                                    <a href="https://wa.me/{{ $phone }}" target="_blank" class="text-success ms-1 small">
                                        <i class="fab fa-whatsapp"></i>
                                    </a>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $p->status_badge }} rounded-pill px-3">{{ $p->status_label }}</span>
                                </td>
                                <td class="text-muted small">{{ $p->created_at->translatedFormat('d M Y, H:i') }}</td>
                                <td class="pe-4 text-end">
                                    <a href="{{ route('seleksi_berkas.show_pengajuan', [$seleksi, $p]) }}"
                                       class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-eye me-1"></i>Detail
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5 text-muted">
                                    <i class="fas fa-inbox fa-2x mb-2 d-block opacity-25"></i>
                                    Belum ada pengajuan yang masuk.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
