@extends('layout.appv2')

@section('title', 'Detail Permohonan Marbot')

@section('content')
    <div class="container-fluid mt-4">

        <!-- Header Section -->
        <div class="row align-items-center mb-4">
            <div class="col-6">
                <h3 class="mb-0 fw-bold text-dark">Detail Permohonan Marbot</h3>
                <p class="text-muted mb-0">Verifikasi dan kelola data marbot masjid</p>
            </div>
            <div class="col-6 text-end">
                @if ($marbot->status == 'disetujui')
                    <a href="{{ route('marbot.edit', $marbot->uuid) }}" class="btn btn-warning me-2">
                        <i class="fas fa-edit me-2"></i> Edit Data
                    </a>
                @endif
                <a href="{{ route('marbot.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i> Kembali ke Daftar
                </a>
            </div>
        </div>

        <div class="row">
            <!-- Left Column: Details -->
            <div class="col-lg-8">

                <!-- Personal Info Card -->
                <div class="card shadow-sm border-0 mb-4 rounded-3">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h5 class="card-title fw-bold text-success mb-0"><i class="fas fa-user-circle me-2"></i> Informasi
                            Pendaftar</h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-4">
                            <!-- NIK -->
                            <div class="col-md-6 verification-item" data-field="nik" data-label="NIK">
                                <div class="d-flex justify-content-between">
                                    <div class="w-100 me-2">
                                        <label class="text-muted small text-uppercase fw-bold">NIK</label>
                                        <div class="fw-bold fs-5 text-dark">{{ $marbot->nik }}</div>
                                        <div class="text-danger small fw-bold mt-1 error-note d-none"></div>
                                    </div>
                                    <div class="flex-shrink-0">
                                        @include('backend.components.verify_btn', ['field' => 'nik'])
                                    </div>
                                </div>
                            </div>

                            <!-- Nama Lengkap -->
                            <div class="col-md-6 verification-item" data-field="nama_lengkap" data-label="Nama Lengkap">
                                <div class="d-flex justify-content-between">
                                    <div class="w-100 me-2">
                                        <label class="text-muted small text-uppercase fw-bold">Nama Lengkap</label>
                                        <div class="fw-bold fs-5 text-dark">{{ $marbot->nama_lengkap }}</div>
                                        <div class="text-danger small fw-bold mt-1 error-note d-none"></div>
                                    </div>
                                    <div class="flex-shrink-0">
                                        @include('backend.components.verify_btn', [
                                            'field' => 'nama_lengkap',
                                        ])
                                    </div>
                                </div>
                            </div>

                            <!-- Tempat, Tanggal Lahir (Combined) -->
                            <div class="col-md-6 verification-item" data-field="tempat_lahir"
                                data-label="Tempat, Tanggal Lahir">
                                <div class="d-flex justify-content-between">
                                    <div class="w-100 me-2">
                                        <label class="text-muted small text-uppercase fw-bold">Tempat, Tanggal Lahir</label>
                                        <div class="fw-bold fs-5 text-dark">
                                            @if ($marbot->tempat_lahir && $marbot->tanggal_lahir)
                                                {{ $marbot->tempat_lahir }},
                                                {{ $marbot->tanggal_lahir->translatedFormat('d F Y') }}
                                            @else
                                                -
                                            @endif
                                        </div>
                                        <div class="text-danger small fw-bold mt-1 error-note d-none"></div>
                                    </div>
                                    <div class="flex-shrink-0">
                                        @include('backend.components.verify_btn', [
                                            'field' => 'tempat_lahir',
                                        ])
                                    </div>
                                </div>
                            </div>

                            <!-- Usia Marbot (Calculated) -->
                            <div class="col-md-6">
                                <div class="d-flex justify-content-between">
                                    <div class="w-100 me-2">
                                        <label class="text-muted small text-uppercase fw-bold">Usia Marbot</label>
                                        <div class="fw-bold fs-5 text-dark">
                                            @if ($marbot->tanggal_lahir)
                                                <span class="badge bg-info text-dark px-3 py-2 fs-6">
                                                    <i class="fas fa-birthday-cake me-1"></i>
                                                    {{ $marbot->tanggal_lahir->age }} Tahun
                                                </span>
                                            @else
                                                -
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- NPWP -->
                            <div class="col-md-6 verification-item" data-field="npwp" data-label="NPWP">
                                <div class="d-flex justify-content-between">
                                    <div class="w-100 me-2">
                                        <label class="text-muted small text-uppercase fw-bold">NPWP</label>
                                        <div class="fw-bold fs-5 text-dark font-monospace">{{ $marbot->npwp ?? '-' }}</div>
                                        <div class="text-danger small fw-bold mt-1 error-note d-none"></div>
                                    </div>
                                    <div class="flex-shrink-0">
                                        @include('backend.components.verify_btn', ['field' => 'npwp'])
                                    </div>
                                </div>
                            </div>

                            <!-- Nomor Rekening -->
                            <div class="col-md-6 verification-item" data-field="nomor_rekening" data-label="Nomor Rekening">
                                <div class="d-flex justify-content-between">
                                    <div class="w-100 me-2">
                                        <label class="text-muted small text-uppercase fw-bold">Nomor Rekening</label>
                                        <div class="fw-bold fs-5 text-dark font-monospace">
                                            {{ $marbot->nomor_rekening ?? '-' }}
                                        </div>
                                        <div class="text-danger small fw-bold mt-1 error-note d-none"></div>
                                    </div>
                                    <div class="flex-shrink-0">
                                        @include('backend.components.verify_btn', [
                                            'field' => 'nomor_rekening',
                                        ])
                                    </div>
                                </div>
                            </div>

                            <!-- No HP -->
                            <div class="col-md-6 verification-item" data-field="no_hp" data-label="Nomor HP">
                                <div class="d-flex justify-content-between">
                                    <div class="w-100 me-2">
                                        <label class="text-muted small text-uppercase fw-bold">Nomor HP</label>
                                        <div class="d-flex align-items-center">
                                            <div class="fw-bold fs-5 text-dark me-2">{{ $marbot->no_hp ?? '-' }}</div>
                                            @if ($marbot->no_hp)
                                                <a href="https://wa.me/{{ preg_replace('/^0/', '62', preg_replace('/[^0-9]/', '', $marbot->no_hp)) }}"
                                                    target="_blank" class="text-success small">
                                                    <i class="fab fa-whatsapp"></i> Chat
                                                </a>
                                            @endif
                                        </div>
                                        <div class="text-danger small fw-bold mt-1 error-note d-none"></div>
                                    </div>
                                    <div class="flex-shrink-0">
                                        @include('backend.components.verify_btn', ['field' => 'no_hp'])
                                    </div>
                                </div>
                            </div>

                            <!-- Tanggal Mulai Bekerja -->
                            <div class="col-md-6 verification-item" data-field="tanggal_mulai_bekerja"
                                data-label="Tanggal Mulai Bekerja">
                                <div class="d-flex justify-content-between">
                                    <div class="w-100 me-2">
                                        <label class="text-muted small text-uppercase fw-bold">Tanggal Mulai Bekerja</label>
                                        <div class="fw-bold fs-5 text-dark">
                                            {{ $marbot->tanggal_mulai_bekerja ? $marbot->tanggal_mulai_bekerja->translatedFormat('d F Y') : '-' }}
                                        </div>
                                        <div class="text-danger small fw-bold mt-1 error-note d-none"></div>
                                    </div>
                                    <div class="flex-shrink-0">
                                        @include('backend.components.verify_btn', [
                                            'field' => 'tanggal_mulai_bekerja',
                                        ])
                                    </div>
                                </div>
                            </div>

                            <!-- Masa Kerja (Calculated) -->
                            <div class="col-md-6">
                                <div class="d-flex justify-content-between">
                                    <div class="w-100 me-2">
                                        <label class="text-muted small text-uppercase fw-bold">Masa Kerja</label>
                                        <div class="fw-bold fs-5 text-dark">
                                            @if ($marbot->tanggal_mulai_bekerja)
                                                @php
                                                    $diff = $marbot->tanggal_mulai_bekerja->diff(now());
                                                    $tahun = $diff->y;
                                                    $bulan = $diff->m;
                                                @endphp
                                                <span class="badge bg-success px-3 py-2 fs-6">
                                                    <i class="fas fa-briefcase me-1"></i>
                                                    @if ($tahun > 0)
                                                        {{ $tahun }} Tahun {{ $bulan }} Bulan
                                                    @else
                                                        {{ $bulan }} Bulan
                                                    @endif
                                                </span>
                                            @else
                                                -
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <!-- Alamat -->
                            <div class="col-12 verification-item" data-field="alamat" data-label="Alamat">
                                <div class="d-flex justify-content-between">
                                    <div class="w-100 me-2">
                                        <label class="text-muted small text-uppercase fw-bold">Alamat</label>
                                        <div class="fs-6 text-dark"><i
                                                class="fas fa-map-marker-alt text-danger me-2"></i>{{ $marbot->alamat }}
                                        </div>
                                        <div class="text-danger small fw-bold mt-1 error-note d-none"></div>
                                    </div>
                                    <div class="flex-shrink-0">
                                        @include('backend.components.verify_btn', ['field' => 'alamat'])
                                    </div>
                                </div>
                            </div>

                            <!-- Kecamatan -->
                            <div class="col-md-6 verification-item" data-field="kecamatan_id" data-label="Kecamatan">
                                <div class="d-flex justify-content-between">
                                    <div class="w-100 me-2">
                                        <label class="text-muted small text-uppercase fw-bold">Kecamatan</label>
                                        <div class="fs-6 text-dark">{{ $marbot->kecamatan->kecamatan ?? '-' }}</div>
                                        <div class="text-danger small fw-bold mt-1 error-note d-none"></div>
                                    </div>
                                    <div class="flex-shrink-0">
                                        @include('backend.components.verify_btn', [
                                            'field' => 'kecamatan_id',
                                        ])
                                    </div>
                                </div>
                            </div>

                            <!-- Kelurahan -->
                            <div class="col-md-6 verification-item" data-field="kelurahan_id" data-label="Kelurahan">
                                <div class="d-flex justify-content-between">
                                    <div class="w-100 me-2">
                                        <label class="text-muted small text-uppercase fw-bold">Kelurahan</label>
                                        <div class="fs-6 text-dark">{{ $marbot->kelurahan->nama_kelurahan ?? '-' }}</div>
                                        <div class="text-danger small fw-bold mt-1 error-note d-none"></div>
                                    </div>
                                    <div class="flex-shrink-0">
                                        @include('backend.components.verify_btn', [
                                            'field' => 'kelurahan_id',
                                        ])
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Worship Place Info Card -->
                <div class="card shadow-sm border-0 mb-4 rounded-3">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h5 class="card-title fw-bold text-success mb-0"><i class="fas fa-mosque me-2"></i> Informasi
                            Rumah
                            Ibadah</h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-4">
                            <div class="col-md-6 verification-item" data-field="tipe_rumah_ibadah"
                                data-label="Tipe Rumah Ibadah">
                                <div class="d-flex justify-content-between">
                                    <div class="w-100 me-2">
                                        <label class="text-muted small text-uppercase fw-bold">Tipe Rumah Ibadah</label>
                                        <div>
                                            @if ($marbot->tipe_rumah_ibadah == 'Masjid')
                                                <span class="badge bg-success">MASJID</span>
                                            @else
                                                <span class="badge bg-info text-dark">MUSHALLA</span>
                                            @endif
                                        </div>
                                        <div class="text-danger small fw-bold mt-1 error-note d-none"></div>
                                    </div>
                                    <div class="flex-shrink-0">
                                        @include('backend.components.verify_btn', [
                                            'field' => 'tipe_rumah_ibadah',
                                        ])
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 verification-item" data-field="nama_rumah_ibadah"
                                data-label="Nama Rumah Ibadah">
                                <div class="d-flex justify-content-between">
                                    <div class="w-100 me-2">
                                        <label class="text-muted small text-uppercase fw-bold">Nama Rumah Ibadah</label>
                                        <div class="fw-bold fs-5 text-dark">
                                            @if ($marbot->rumah_ibadah)
                                                {{ $marbot->tipe_rumah_ibadah == 'Masjid' ? $marbot->rumah_ibadah->nama_masjid : $marbot->rumah_ibadah->nama_mushalla }}
                                            @else
                                                -
                                            @endif
                                        </div>
                                        <div class="text-danger small fw-bold mt-1 error-note d-none"></div>
                                    </div>
                                    <div class="flex-shrink-0">
                                        @include('backend.components.verify_btn', [
                                            'field' => 'nama_rumah_ibadah',
                                        ])
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 verification-item" data-field="nomor_id_rumah_ibadah"
                                data-label="ID Statistik">
                                <div class="d-flex justify-content-between">
                                    <div class="w-100 me-2">
                                        <label class="text-muted small text-uppercase fw-bold">ID / Nomor Statistik</label>
                                        <div class="font-monospace text-secondary fs-6">
                                            @if ($marbot->rumah_ibadah)
                                                {{ $marbot->tipe_rumah_ibadah == 'Masjid' ? $marbot->rumah_ibadah->nomor_id_masjid : $marbot->rumah_ibadah->nomor_id_mushalla ?? '-' }}
                                            @else
                                                -
                                            @endif
                                        </div>
                                        <div class="text-danger small fw-bold mt-1 error-note d-none"></div>
                                    </div>
                                    <div class="flex-shrink-0">
                                        @include('backend.components.verify_btn', [
                                            'field' => 'nomor_id_rumah_ibadah',
                                        ])
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 verification-item" data-field="alamat_rumah_ibadah"
                                data-label="Alamat Rumah Ibadah">
                                <div class="d-flex justify-content-between">
                                    <div class="w-100 me-2">
                                        <label class="text-muted small text-uppercase fw-bold">Alamat Rumah Ibadah</label>
                                        <div class="fs-6 text-dark"><i class="fas fa-map-pin text-danger me-2"></i>
                                            @if ($marbot->rumah_ibadah)
                                                {{ $marbot->tipe_rumah_ibadah == 'Masjid' ? $marbot->rumah_ibadah->alamat_masjid : $marbot->rumah_ibadah->alamat_mushalla ?? '-' }}
                                            @else
                                                -
                                            @endif
                                        </div>
                                        <div class="text-danger small fw-bold mt-1 error-note d-none"></div>
                                    </div>
                                    <div class="flex-shrink-0">
                                        @include('backend.components.verify_btn', [
                                            'field' => 'alamat_rumah_ibadah',
                                        ])
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Documents Card -->
                <div class="card shadow-sm border-0 mb-4 rounded-3">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h5 class="card-title fw-bold text-success mb-0"><i class="fas fa-paperclip me-2"></i> Berkas
                            Pendukung</h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-3">
                            @php
                                $files = [
                                    'file_ktp' => ['label' => 'Scan KTP', 'icon' => 'fa-id-card'],
                                    'file_kk' => ['label' => 'Scan Kartu Keluarga', 'icon' => 'fa-users'],
                                    'file_sk_marbot' => ['label' => 'SK Marbot', 'icon' => 'fa-file-signature'],
                                    'file_permohonan' => [
                                        'label' => 'Surat Permohonan',
                                        'icon' => 'fa-envelope-open-text',
                                    ],
                                    'file_pernyataan' => ['label' => 'Surat Pernyataan', 'icon' => 'fa-file-contract'],
                                    'file_buku_rekening' => [
                                        'label' => 'Scan Buku Rekening',
                                        'icon' => 'fa-book',
                                    ],
                                    'file_npwp' => ['label' => 'Scan NPWP', 'icon' => 'fa-address-card'],
                                ];
                            @endphp

                            @foreach ($files as $field => $meta)
                                <div class="col-md-6 col-lg-4 verification-item" data-field="{{ $field }}"
                                    data-label="{{ $meta['label'] }}">
                                    <div class="p-3 border rounded-3 bg-light h-100 d-flex flex-column position-relative">
                                        <div class="d-flex align-items-center mb-3">
                                            <div class="bg-white rounded-circle p-2 shadow-sm text-primary me-3">
                                                <i class="fas {{ $meta['icon'] }} fa-lg"></i>
                                            </div>
                                            <h6 class="mb-0 fw-bold text-dark">{{ $meta['label'] }}</h6>
                                        </div>
                                        <div class="mt-auto mb-2">
                                            @if ($marbot->$field)
                                                <button type="button"
                                                    class="btn btn-outline-primary btn-sm w-100 btn-view-file"
                                                    data-url="{{ asset('storage/marbot_files/' . $marbot->$field) }}"
                                                    data-title="{{ $meta['label'] }}">
                                                    <i class="fas fa-eye me-1"></i> Lihat File
                                                </button>
                                            @else
                                                <button disabled class="btn btn-outline-secondary btn-sm w-100">
                                                    <i class="fas fa-times me-1"></i> Tidak Ada
                                                </button>
                                            @endif
                                        </div>

                                        <!-- Verification Actions -->
                                        <div class="pt-2 border-top d-flex justify-content-between align-items-center">
                                            <span class="small text-muted fw-bold">Verifikasi:</span>
                                            <div>
                                                @include('backend.components.verify_btn', [
                                                    'field' => $field,
                                                    'small' => true,
                                                ])
                                            </div>
                                        </div>
                                        <div class="text-danger small fw-bold mt-1 error-note d-none"></div>

                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Incentive History Card -->
                <div class="card shadow-sm border-0 mb-4 rounded-3">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h5 class="card-title fw-bold text-success mb-0">
                            <i class="fas fa-hand-holding-usd me-2"></i> Riwayat Insentif Daerah
                        </h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="ps-4 py-3">Bulan</th>
                                        <th class="py-3">Tahun</th>
                                        <th class="py-3">Nominal</th>
                                        <th class="py-3">Tanggal Terima</th>
                                        <th class="pe-4 py-3">Keterangan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($marbot->insentifs->sortByDesc('tanggal_terima') as $insentif)
                                        <tr>
                                            <td class="ps-4 fw-bold text-primary">
                                                {{ \Carbon\Carbon::createFromDate(null, $insentif->bulan ?? 1, 1)->translatedFormat('F') }}
                                            </td>
                                            <td class="fw-bold text-dark">{{ $insentif->tahun_anggaran }}</td>
                                            <td class="text-success fw-bold">
                                                Rp {{ number_format($insentif->nominal, 0, ',', '.') }}
                                            </td>
                                            <td>
                                                {{ \Carbon\Carbon::parse($insentif->tanggal_terima)->translatedFormat('d F Y') }}
                                            </td>
                                            <td class="pe-4 text-muted small">
                                                {{ $insentif->keterangan ?? '-' }}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-4 text-muted">
                                                <div class="d-flex flex-column align-items-center">
                                                    <i
                                                        class="fas fa-search-dollar fa-2x mb-2 text-secondary opacity-50"></i>
                                                    <p class="mb-0 small">Belum ada riwayat insentif yang tercatat.</p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Right Column: Actions -->
            <div class="col-lg-4">

                @if ($marbot->status == 'disetujui')
                    <!-- Approved Widget -->
                    <div class="card bg-success text-white shadow-lg border-0 mb-4 rounded-3 overflow-hidden">
                        <div class="card-body text-center p-5 position-relative">
                            <div class="position-absolute top-0 start-0 w-100 h-100"
                                style="background: url('https://www.transparenttextures.com/patterns/cubes.png'); opacity: 0.1;">
                            </div>
                            <i class="fas fa-check-circle fa-4x mb-3 text-white-50"></i>
                            <h4 class="fw-bold">PERMOHONAN DISETUJUI</h4>
                            <p class="mb-4 text-white-50">Data marbot telah diverifikasi dan aktif.</p>

                            <div class="bg-white text-success rounded-3 p-3 shadow-sm mb-2">
                                <small class="text-uppercase fw-bold text-muted d-block">Nomor Induk Marbot</small>
                                <span class="display-6 fw-bold user-select-all">{{ $marbot->nomor_induk_marbot }}</span>
                            </div>
                            <small>Disetujui pada: {{ $marbot->updated_at->format('d M Y') }}</small>
                        </div>
                    </div>
                @elseif($marbot->status == 'ditolak')
                    <!-- Rejected Widget -->
                    <div class="card bg-dark text-white shadow-lg border-0 mb-4 rounded-3 overflow-hidden">
                        <div class="card-body text-center p-5 position-relative">
                            <div class="position-absolute top-0 start-0 w-100 h-100"
                                style="background: url('https://www.transparenttextures.com/patterns/cubes.png'); opacity: 0.1;">
                            </div>
                            <i class="fas fa-times-circle fa-4x mb-3 text-white-50"></i>
                            <h4 class="fw-bold">PERMOHONAN DITOLAK</h4>
                            <p class="mb-4 text-white-50">Permohonan tidak dapat diproses lebih lanjut.</p>

                            @if ($marbot->catatan)
                                <div class="bg-white text-dark rounded-3 p-3 shadow-sm mb-2">
                                    <small class="text-uppercase fw-bold text-muted d-block">Alasan Penolakan</small>
                                    <p class="mb-0 mt-2 text-start">{{ $marbot->catatan }}</p>
                                </div>
                            @endif
                            <small>Ditolak pada: {{ $marbot->updated_at->format('d M Y') }}</small>
                        </div>
                    </div>
                @else
                    <!-- Verification Widget -->
                    <div class="card shadow-sm border-0 mb-4 rounded-3 border-top border-5 border-warning">
                        <div class="card-header bg-white py-3">
                            <h5 class="card-title fw-bold mb-0 text-dark">Tindakan Verifikasi</h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="mb-3">
                                <label class="text-muted small text-uppercase">Status Saat Ini</label>
                                <div>
                                    @if ($marbot->status == 'diajukan')
                                        <span class="badge bg-warning text-dark px-3 py-2 rounded-pill"><i
                                                class="fas fa-hourglass-half me-1"></i> MENUNGGU VERIFIKASI</span>
                                    @elseif($marbot->status == 'perbaikan')
                                        <span class="badge bg-danger px-3 py-2 rounded-pill"><i
                                                class="fas fa-exclamation-circle me-1"></i> MENUNGGU PERBAIKAN USER</span>
                                        @if ($marbot->deadline_perbaikan)
                                            <div
                                                class="mt-2 small text-danger fw-bold border border-danger rounded p-2 bg-danger bg-opacity-10">
                                                <i class="fas fa-clock me-1"></i> Batas Waktu:
                                                {{ $marbot->deadline_perbaikan->translatedFormat('d F Y') }}
                                                @if ($marbot->deadline_perbaikan->endOfDay()->isPast())
                                                    (Terlewat)
                                                @else
                                                    ({{ $marbot->deadline_perbaikan->endOfDay()->diffForHumans() }})
                                                @endif
                                            </div>
                                        @endif
                                    @elseif($marbot->status == 'ditolak')
                                        <span class="badge bg-dark px-3 py-2 rounded-pill"><i class="fas fa-ban me-1"></i>
                                            DITOLAK</span>
                                    @endif
                                </div>
                            </div>

                            @if ($marbot->catatan)
                                <div
                                    class="alert alert-warning border-0 bg-warning bg-opacity-10 d-flex align-items-start mb-4">
                                    <i class="fas fa-history text-warning me-2 mt-1"></i>
                                    <div>
                                        <div class="fw-bold text-warning-emphasis">Catatan Sebelumnya:</div>
                                        <small class="text-muted">{{ $marbot->catatan }}</small>
                                    </div>
                                </div>
                            @endif

                            <form id="form-approve" action="{{ route('marbot.update', $marbot->uuid) }}" method="POST"
                                class="mb-4">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="action" value="approve">
                                <button type="button"
                                    class="btn btn-success w-100 btn-lg fw-bold shadow-sm btn-approve-confirm">
                                    <i class="fas fa-check-circle me-2"></i> Setujui & Terbitkan NIM
                                </button>
                            </form>

                            <hr class="text-muted my-4">

                            <h6 class="fw-bold text-danger mb-3"><i class="fas fa-exclamation-triangle me-2"></i>Koreksi
                                Data</h6>
                            <form id="form-return" action="{{ route('marbot.update', $marbot->uuid) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="action" value="return">
                                <input type="hidden" id="marbot-phone" value="{{ $marbot->no_hp }}">
                                <input type="hidden" id="marbot-name" value="{{ $marbot->nama_lengkap }}">
                                <input type="hidden" name="verification_details" id="verification_details_input">

                                <div class="form-group mb-3">
                                    <label class="form-label small text-muted fw-bold">Catatan Perbaikan</label>
                                    <textarea name="catatan" id="catatan-return" class="form-control bg-light" rows="4"
                                        placeholder="Jelaskan bagian mana yang perlu diperbaiki oleh pemohon..."></textarea>

                                    @if ($marbot->no_hp)
                                        <div class="mt-2 text-end">
                                            <button type="button" class="btn btn-sm btn-success rounded-pill px-3"
                                                id="btn-wa-draft">
                                                <i class="fab fa-whatsapp me-1"></i> Kirim Catatan ke WA
                                            </button>
                                        </div>
                                    @endif
                                </div>

                                <div class="form-group mb-3">
                                    <label class="form-label small text-muted fw-bold">Batas Waktu Perbaikan</label>
                                    <input type="date" name="deadline_perbaikan"
                                        class="form-control @error('deadline_perbaikan') is-invalid @enderror" required
                                        min="{{ date('Y-m-d') }}">
                                    @error('deadline_perbaikan')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text text-muted small">Jika melewati tanggal ini, status otomatis
                                        menjadi
                                        <strong>Ditolak</strong>.
                                    </div>
                                </div>
                                <button type="button" class="btn btn-outline-danger w-100 fw-bold btn-return-confirm">
                                    <i class="fas fa-undo-alt me-2"></i> Kembalikan untuk Perbaikan
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Reject Card - Separated -->
                    <div class="card shadow-sm border-0 mb-4 rounded-3 border-top border-5 border-danger">
                        <div class="card-header bg-white py-3">
                            <h5 class="card-title fw-bold mb-0 text-danger">
                                <i class="fas fa-ban me-2"></i>Tolak Permohonan
                            </h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="alert alert-danger border-0 bg-danger bg-opacity-10 mb-3">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                <small class="fw-bold">Perhatian:</small> Permohonan yang ditolak bersifat permanen dan
                                tidak dapat diproses kembali.
                            </div>

                            <form id="form-reject" action="{{ route('marbot.update', $marbot->uuid) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="action" value="reject">

                                <div class="form-group mb-3">
                                    <label class="form-label small text-muted fw-bold">Alasan Penolakan</label>
                                    <textarea name="catatan" id="catatan-reject" class="form-control bg-light" rows="4"
                                        placeholder="Jelaskan alasan penolakan permohonan ini..." required></textarea>

                                    @if ($marbot->no_hp)
                                        <div class="mt-2 text-end">
                                            <button type="button" class="btn btn-sm btn-dark rounded-pill px-3"
                                                id="btn-wa-reject">
                                                <i class="fab fa-whatsapp me-1"></i> Kirim Notifikasi ke WA
                                            </button>
                                        </div>
                                    @endif
                                </div>
                                <button type="button" class="btn btn-danger w-100 fw-bold btn-reject-confirm">
                                    <i class="fas fa-times-circle me-2"></i> Tolak Permohonan
                                </button>
                            </form>
                        </div>
                    </div>
                @endif

                <!-- Helper Card -->
                <div class="card shadow-sm border-0 rounded-3 bg-info bg-opacity-10">
                    <div class="card-body d-flex align-items-center">
                        <i class="fas fa-info-circle fa-2x text-info me-3"></i>
                        <div>
                            <small class="text-muted d-block lh-sm">Pastikan semua berkas telah diperiksa dengan seksama
                                sebelum menyetujui permohonan.</small>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- File Viewer Modal -->
    <div class="modal fade" id="fileViewerModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content h-100">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold" id="fileViewerModalLabel">Lihat Dokumen</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-0 text-center bg-light position-relative">
                    <div id="fileContentContainer"
                        style="height: 80vh; display: flex; align-items: center; justify-content: center; overflow: hidden;">
                        <span class="text-muted">Memuat...</span>
                    </div>
                </div>
                <div class="modal-footer">
                    <a href="#" id="downloadLink" class="btn btn-primary" target="_blank"><i
                            class="fas fa-download me-1"></i> Download / Buka Sendiri</a>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            $(document).ready(function() {
                var verificationState = {};

                // Load existing state if available
                @if ($marbot->verification_details)
                    verificationState = @json($marbot->verification_details);
                    // Apply UI states
                    $.each(verificationState, function(field, data) {
                        var container = $('.verification-item[data-field="' + field + '"]');
                        if (data.valid === false) {
                            container.find('.btn-verify-invalid').addClass('active btn-danger').removeClass(
                                'btn-outline-danger');
                            container.find('.error-note').text(data.note).removeClass('d-none');
                            container.addClass('bg-danger bg-opacity-10 rounded-3 p-2 border border-danger');
                        } else {
                            container.find('.btn-verify-valid').addClass('active btn-success').removeClass(
                                'btn-outline-success');
                        }
                    });
                    updateApproveButton();
                @endif

                function updateReturnNotes() {
                    var autoNotes = "Perbaikan diperlukan pada poin-poin berikut:\n";
                    var hasIssues = false;

                    $.each(verificationState, function(key, val) {
                        if (val.valid === false) {
                            var label = $('.verification-item[data-field="' + key + '"]').data('label');
                            autoNotes += "- *" + label + ": " + val.note + "*\n";
                            hasIssues = true;
                        }
                    });

                    if (hasIssues) {
                        $('#catatan-return').val(autoNotes);
                    } else {
                        $('#catatan-return').val('');
                    }
                }

                // Handle Valid Click
                $(document).on('click', '.btn-verify-valid', function() {
                    var container = $(this).closest('.verification-item');
                    var field = container.data('field');

                    // Reset UI
                    container.removeClass('bg-danger bg-opacity-10 rounded-3 p-2 border border-danger');
                    container.find('.error-note').addClass('d-none');
                    container.find('.btn-verify-invalid').removeClass('active btn-danger').addClass(
                        'btn-outline-danger');

                    // Set Active
                    $(this).addClass('active btn-success').removeClass('btn-outline-success');

                    // Update State
                    verificationState[field] = {
                        valid: true
                    };

                    updateApproveButton();
                    updateReturnNotes(); // Auto update notes
                });

                // Handle Invalid Click
                $(document).on('click', '.btn-verify-invalid', function() {
                    var container = $(this).closest('.verification-item');
                    var field = container.data('field');
                    var label = container.data('label');

                    Swal.fire({
                        title: 'Tolak ' + label + '?',
                        input: 'text',
                        inputLabel: 'Alasan Penolakan',
                        inputPlaceholder: 'Misal: Foto buram / Data tidak sesuai',
                        showCancelButton: true,
                        confirmButtonText: 'Simpan',
                        confirmButtonColor: '#dc3545',
                        preConfirm: (value) => {
                            if (!value) {
                                Swal.showValidationMessage('Alasan wajib diisi!')
                            }
                            return value
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Reset Valid Btn
                            container.find('.btn-verify-valid').removeClass('active btn-success')
                                .addClass('btn-outline-success');

                            // Set Active
                            $(this).addClass('active btn-danger').removeClass('btn-outline-danger');

                            // Show Note
                            container.find('.error-note').text(result.value).removeClass('d-none');

                            // Add Styling
                            container.addClass(
                                'bg-danger bg-opacity-10 rounded-3 p-2 border border-danger');

                            // Update State
                            verificationState[field] = {
                                valid: false,
                                note: result.value
                            };

                            updateApproveButton();
                            updateReturnNotes(); // Auto update notes
                        }
                    });
                });

                function updateApproveButton() {
                    var hasInvalid = false;
                    $.each(verificationState, function(key, val) {
                        if (val.valid === false) hasInvalid = true;
                    });

                    if (hasInvalid) {
                        $('.btn-approve-confirm').prop('disabled', true).html(
                            '<i class="fas fa-ban me-2"></i> Perbaiki Dulu');
                    } else {
                        $('.btn-approve-confirm').prop('disabled', false).html(
                            '<i class="fas fa-check-circle me-2"></i> Setujui & Terbitkan NIM');
                    }
                }

                // Populate Return Form with Notes
                $('.btn-return-confirm').click(function(e) {
                    $('#verification_details_input').val(JSON.stringify(verificationState));

                    var catatan = $('#catatan-return').val();
                    if (!catatan.trim()) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Catatan Diperlukan!',
                            text: 'Harap isi catatan perbaikan atau tandai item yang salah (Invalid).',
                        });
                        return;
                    }

                    Swal.fire({
                        title: 'Kembalikan Berkas?',
                        text: "Status akan diubah menjadi 'Perbaikan' dan user akan diberitahu.",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#dc3545',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Ya, Kembalikan!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $('#verification_details_input').val(JSON.stringify(verificationState));
                            $('#form-return').submit();
                        }
                    });
                });

                // --- Existing Scripts ---
                $('.btn-view-file').on('click', function() {
                    var url = $(this).data('url');
                    var title = $(this).data('title');
                    var extension = url.split('.').pop().toLowerCase();

                    $('#fileViewerModalLabel').text(title);
                    $('#downloadLink').attr('href', url);

                    var container = $('#fileContentContainer');
                    container.html(
                        '<div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div>'
                    );

                    var content = '';
                    // Basic check for image extensions
                    if (['jpg', 'jpeg', 'png', 'gif', 'webp'].includes(extension)) {
                        content = '<img src="' + url +
                            '" class="img-fluid" style="max-height: 100%; max-width: 100%; object-fit: contain;">';
                    } else if (extension === 'pdf') {
                        content = '<iframe src="' + url +
                            '" style="width:100%; height:100%; border:none;"></iframe>';
                    } else {
                        content =
                            '<div class="alert alert-warning m-4">Format file tidak mendukung pratinjau langsung. <br>Silakan unduh file untuk melihatnya.</div>';
                    }

                    // Show modal first then load content to ensure sizing is correct
                    var myModal = new bootstrap.Modal(document.getElementById('fileViewerModal'));
                    myModal.show();

                    // Small delay to visual transition
                    setTimeout(function() {
                        container.html(content);
                    }, 300);
                });

                // SweetAlert for Approve
                $('.btn-approve-confirm').click(function() {
                    Swal.fire({
                        title: 'Setujui Permohonan?',
                        text: "Nomor Induk Marbot (NIM) akan digenerate otomatis.",
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#198754',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Ya, Setujui!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $('#form-approve').submit();
                        }
                    });
                });

                // SweetAlert for Return - REMOVED OLD HANDLER TO AVOID DUPLICATE
                // Logic moved to new handler above

                // WhatsApp Draft
                $('#btn-wa-draft').click(function() {
                    var phone = $('#marbot-phone').val();
                    var name = $('#marbot-name').val();
                    var catatan = $('#catatan-return').val();

                    if (!catatan.trim()) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Catatan Kosong',
                            text: 'Tulis catatan perbaikan terlebih dahulu sebelum mengirim WA.',
                        });
                        return;
                    }

                    // Format Phone (08 -> 628)
                    phone = phone.replace(/[^0-9]/g, '');
                    if (phone.startsWith('0')) {
                        phone = '62' + phone.substring(1);
                    }

                    var message = "Assalamu'alaikum Sdr. " + name + ",\n\n" +
                        "Terkait permohonan Nomor Induk Marbot Anda, mohon perbaiki berkas berikut:\n\n" +
                        catatan + "\n\n" +
                        "Silakan akses link berikut:\nhttps://bimas.kemenagkukar.id/cek-validitas\n\n" +
                        "Petunjuk: " + "*" +
                        "Silahkan klik Jenis Lembaga lalu pilih Marbot Masjid, Masukkan NIK Anda, klik Cari, lalu tekan tombol 'Perbaiki Data'." +
                        "*" + "\n\n" +
                        "Terima kasih.";

                    var url = "https://wa.me/" + phone + "?text=" + encodeURIComponent(message);

                    window.open(url, '_blank');
                });

                // WhatsApp Draft untuk Penolakan
                $('#btn-wa-reject').click(function() {
                    var phone = $('#marbot-phone').val();
                    var name = $('#marbot-name').val();
                    var catatan = $('#catatan-reject').val();

                    if (!catatan.trim()) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Alasan Penolakan Kosong',
                            text: 'Tulis alasan penolakan terlebih dahulu sebelum mengirim WA.',
                        });
                        return;
                    }

                    // Format Phone (08 -> 628)
                    phone = phone.replace(/[^0-9]/g, '');
                    if (phone.startsWith('0')) {
                        phone = '62' + phone.substring(1);
                    }

                    var message = "Assalamu'alaikum Wr. Wb.\n\n" +
                        "Yth. Bapak/Ibu *" + name + "*\n" +
                        "NIK: {{ $marbot->nik }}\n\n" +
                        "Dengan hormat, kami sampaikan bahwa permohonan pendaftaran Marbot Anda telah kami proses.\n\n" +
                        "📋 *STATUS: DITOLAK*\n\n" +
                        "📝 *Alasan Penolakan: " +
                        catatan + "*\n\n" +
                        //"Untuk informasi lebih lanjut, silakan hubungi Kantor Kementerian Agama Kabupaten Kutai Kartanegara.\n\n" +
                        "Terima kasih atas perhatian Bapak/Ibu.\n\n" +
                        "Wassalamu'alaikum Wr. Wb."; //+
                    // "_Pesan otomatis dari Sistem Bimas Islam Kukar_";

                    var url = "https://wa.me/" + phone + "?text=" + encodeURIComponent(message);

                    window.open(url, '_blank');
                });

                // SweetAlert for Reject
                $('.btn-reject-confirm').click(function(e) {
                    var catatan = $('#catatan-reject').val();
                    if (!catatan.trim()) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Alasan Penolakan Diperlukan!',
                            text: 'Harap isi alasan penolakan permohonan.',
                        });
                        return;
                    }

                    Swal.fire({
                        title: 'Tolak Permohonan?',
                        html: "Permohonan akan ditolak secara <strong>permanen</strong>.<br>Pemohon tidak dapat mengajukan perbaikan.",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#dc3545',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Ya, Tolak!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $('#form-reject').submit();
                        }
                    });
                });

                // SweetAlert for Session Messages
                @if (session('success'))
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: '{{ session('success') }}',
                        timer: 3000,
                        showConfirmButton: false
                    });
                @endif
            });
        </script>
    @endpush
@endsection
