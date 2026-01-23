@extends('layout.appv2')

@section('title', 'Data Marbot Masjid')

@section('content')
    <div class="container-fluid mt-4">
        <!-- Header & Toolbar -->
        <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center mb-4 gap-3">
            <div>
                <h3 class="fw-bold text-dark mb-1">Data Permohonan Marbot</h3>
                <p class="text-muted mb-0">Kelola pendaftaran, verifikasi, dan insentif marbot masjid.</p>
            </div>

            <!-- Toolbar Actions -->
            <div class="d-flex flex-column align-items-lg-end gap-2">
                <!-- Info Jadwal -->
                @if ($startDate && $endDate)
                    <div class="mb-1">
                        <span
                            class="badge bg-primary bg-opacity-10 text-primary border border-primary-subtle rounded-pill px-3 py-2">
                            <i class="fas fa-calendar-alt me-2"></i>
                            Pendaftaran: {{ \Carbon\Carbon::parse($startDate)->format('d M Y') }} -
                            {{ \Carbon\Carbon::parse($endDate)->format('d M Y') }}
                        </span>
                    </div>
                @endif

                <div class="d-flex flex-wrap gap-2 justify-content-lg-end">
                    <!-- Group 1: Data Management -->
                    <div class="btn-group shadow-sm">
                        <button type="button" class="btn btn-outline-success" data-bs-toggle="modal"
                            data-bs-target="#exportModal" title="Export Excel">
                            <i class="fas fa-file-excel"></i>
                        </button>
                        <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal"
                            data-bs-target="#importModal" title="Import Data">
                            <i class="fas fa-file-upload"></i>
                        </button>
                        <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal"
                            data-bs-target="#downloadArchiveModal" title="Arsip Berkas">
                            <i class="fas fa-file-archive"></i>
                        </button>
                    </div>

                    <!-- Group 2: Features -->
                    <button type="button" class="btn btn-info text-white shadow-sm" id="btn-insentif-modal">
                        <i class="fas fa-hand-holding-usd me-1"></i> Insentif
                    </button>

                    <a href="{{ route('marbot.seleksi') }}" class="btn btn-dark shadow-sm">
                        <i class="fas fa-kaaba me-1"></i> Seleksi Umroh
                    </a>

                    <!-- Group 3: Settings & Create -->
                    <button type="button" class="btn btn-warning shadow-sm" data-bs-toggle="modal"
                        data-bs-target="#settingsModal" title="Atur Jadwal">
                        <i class="fas fa-cog"></i>
                    </button>
                    <a href="{{ route('marbot.create') }}" class="btn btn-primary shadow-sm fw-bold">
                        <i class="fas fa-plus me-1"></i> Tambah Data
                    </a>
                </div>
            </div>
        </div>

        <!-- Filter Card -->
        <div class="card shadow-sm border-0 rounded-3 mb-4">
            <div class="card-body py-3">
                <form action="{{ route('marbot.index') }}" method="GET" class="row g-3 align-items-end">
                    <div class="col-md-4">
                        <label class="form-label small fw-bold text-muted">Kecamatan</label>
                        <select class="form-select" name="kecamatan_id" id="filter_kecamatan_id">
                            <option value="">Semua Kecamatan</option>
                            @foreach ($kecamatans as $kec)
                                <option value="{{ $kec->id }}"
                                    {{ request('kecamatan_id') == $kec->id ? 'selected' : '' }}>
                                    {{ $kec->kecamatan }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-bold text-muted">Kelurahan</label>
                        <select class="form-select" name="kelurahan_id" id="filter_kelurahan_id">
                            <option value="">Semua Kelurahan</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary fw-bold">
                                <i class="fas fa-filter me-1"></i> Filter
                            </button>
                            <a href="{{ route('marbot.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-sync-alt me-1"></i> Reset
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Main Card -->
        <div class="card shadow-sm border-0 rounded-3">
            <div class="card-body">
                <!-- Alerts -->
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                @if (session('info'))
                    <div class="alert alert-info alert-dismissible fade show" role="alert">
                        <i class="fas fa-info-circle me-2"></i> {{ session('info') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <ul class="mb-0 ps-3">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <!-- Table -->
                <div class="table-responsive">
                    <table id="table-marbot" class="table align-middle w-100">
                        <thead class="bg-light text-secondary">
                            <tr>
                                <th class="text-center py-3" width="40">
                                    <div class="form-check d-flex justify-content-center">
                                        <input type="checkbox" id="check-all" class="form-check-input shadow-sm"
                                            style="cursor: pointer; width: 18px; height: 18px;">
                                    </div>
                                </th>
                                <th class="text-center py-3 text-uppercase small fw-bold" width="50">No</th>
                                <th class="py-3 text-uppercase small fw-bold">Identitas</th>
                                <th class="py-3 text-uppercase small fw-bold">NIK / Kontak</th>
                                <th class="py-3 text-uppercase small fw-bold">Rumah Ibadah</th>
                                <th class="py-3 text-uppercase small fw-bold">Wilayah</th>
                                <th class="text-center py-3 text-uppercase small fw-bold">Status</th>
                                <th class="text-center py-3 text-uppercase small fw-bold">Status Insentif
                                    {{ date('Y') }}</th>
                                <th class="text-center py-3 text-uppercase small fw-bold" width="100">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="border-top-0">
                            @foreach ($marbots as $marbot)
                                @php
                                    $hasIncentive = $marbot->insentifs
                                        ->where('tahun_anggaran', date('Y'))
                                        ->isNotEmpty();
                                @endphp
                                <tr class="border-bottom hover-bg-light">
                                    <td class="text-center">
                                        <div class="form-check d-flex justify-content-center">
                                            <input type="checkbox" value="{{ $marbot->uuid }}"
                                                class="form-check-input marbot-checkbox shadow-sm"
                                                style="cursor: pointer; width: 18px; height: 18px;">
                                        </div>
                                    </td>
                                    <td class="text-center fw-medium text-muted">{{ $loop->iteration }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-initials bg-soft-primary text-primary me-3 rounded-circle fw-bold d-flex align-items-center justify-content-center"
                                                style="width: 40px; height: 40px; background-color: rgba(13, 110, 253, 0.1);">
                                                {{ substr($marbot->nama_lengkap, 0, 1) }}
                                            </div>
                                            <div>
                                                <span class="d-block fw-bold text-dark">{{ $marbot->nama_lengkap }}</span>
                                                @if ($marbot->nomor_induk_marbot)
                                                    <span
                                                        class="badge bg-success bg-opacity-10 text-success border border-success-subtle rounded-pill"
                                                        style="font-size: 0.65rem;">
                                                        NIM: {{ $marbot->nomor_induk_marbot }}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column">
                                            <span class="font-monospace text-dark fw-medium lh-sm"
                                                style="font-size: 0.9rem;">{{ $marbot->nik }}</span>
                                            <span class="text-muted small mt-1"><i class="fas fa-phone-alt me-1"
                                                    style="font-size: 0.75rem;"></i> {{ $marbot->no_hp ?? '-' }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column">
                                            <span class="fw-bold text-dark text-truncate" style="max-width: 200px;">
                                                @if ($marbot->rumah_ibadah)
                                                    {{ $marbot->tipe_rumah_ibadah == 'Masjid' ? $marbot->rumah_ibadah->nama_masjid : $marbot->rumah_ibadah->nama_mushalla }}
                                                @else
                                                    <span class="text-muted fst-italic">-</span>
                                                @endif
                                            </span>
                                            <span class="badge bg-light text-secondary border rounded-pill mt-1"
                                                style="width: fit-content; font-size: 0.7rem;">
                                                {{ $marbot->tipe_rumah_ibadah }}
                                            </span>
                                        </div>
                                    </td>
                                    <td>
                                        <span
                                            class="d-inline-block text-secondary small fw-medium px-2 py-1 rounded bg-light border border-light-subtle">
                                            {{ ucwords(strtolower($marbot->kecamatan->kecamatan ?? '-')) }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        @if ($marbot->status == 'diajukan')
                                            <span
                                                class="badge bg-warning bg-opacity-10 text-warning border border-warning-subtle rounded-pill px-3 py-2">
                                                Diajukan
                                            </span>
                                        @elseif($marbot->status == 'disetujui')
                                            <span
                                                class="badge bg-success bg-opacity-10 text-success border border-success-subtle rounded-pill px-3 py-2">
                                                Disetujui
                                            </span>
                                        @elseif($marbot->status == 'perbaikan')
                                            <span
                                                class="badge bg-danger bg-opacity-10 text-danger border border-danger-subtle rounded-pill px-3 py-2">
                                                Perbaikan
                                            </span>
                                        @elseif($marbot->status == 'ditolak')
                                            <span
                                                class="badge bg-dark bg-opacity-10 text-dark border border-dark-subtle rounded-pill px-3 py-2">
                                                Ditolak
                                            </span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if ($hasIncentive)
                                            <span
                                                class="btn btn-sm btn-icon btn-soft-success rounded-circle cursor-default"
                                                title="Sudah Menerima"
                                                style="background-color: rgba(25, 135, 84, 0.1); color: #198754; width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center;">
                                                <i class="fas fa-check"></i>
                                            </span>
                                        @else
                                            <span
                                                class="btn btn-sm btn-icon btn-light text-muted rounded-circle cursor-default border"
                                                title="Belum Menerima"
                                                style="width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center;">
                                                <i class="fas fa-times"></i>
                                            </span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center gap-1">
                                            <a href="{{ route('marbot.show', $marbot->uuid) }}"
                                                class="btn btn-sm btn-info text-white shadow-sm" data-bs-toggle="tooltip"
                                                title="Lihat Detail">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <button type="button" class="btn btn-sm btn-danger shadow-sm btn-delete"
                                                data-id="{{ $marbot->uuid }}" data-bs-toggle="tooltip"
                                                title="Hapus Data">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </div>
                                        <form id="delete-form-{{ $marbot->uuid }}"
                                            action="{{ route('marbot.destroy', $marbot->uuid) }}" method="POST"
                                            class="d-none">
                                            @csrf @method('DELETE')
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            $(function() {
                var table = $("#table-marbot").DataTable({
                    "responsive": true,
                    "lengthChange": true,
                    "autoWidth": false,
                    "language": {
                        "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json"
                    },
                    "columnDefs": [{
                        "orderable": false,
                        "targets": [0, 8]
                    }]
                });

                // Select All Checkbox
                $('#check-all').click(function() {
                    $('.marbot-checkbox').prop('checked', this.checked);
                });

                // Open Incentive Modal
                $('#btn-insentif-modal').click(function() {
                    let selected = [];
                    $('.marbot-checkbox:checked').each(function() {
                        selected.push($(this).val());
                    });

                    if (selected.length === 0) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Pilih Data',
                            text: 'Pilih minimal satu data marbot untuk proses insentif.'
                        });
                        return;
                    }

                    // Populate hidden inputs
                    $('#insentif-uuids-container').html('');
                    selected.forEach(function(uuid) {
                        $('#insentif-uuids-container').append(
                            '<input type="hidden" name="marbot_uuids[]" value="' + uuid + '">'
                        );
                    });

                    $('#selected-count').text(selected.length);
                    var insentifModal = new bootstrap.Modal(document.getElementById('insentifModal'));
                    insentifModal.show();
                });

                // SweetAlert for Delete
                $(document).on('click', '.btn-delete', function() {
                    let id = $(this).data('id');
                    Swal.fire({
                        title: 'Hapus Data?',
                        text: "Data akan dihapus permanen.",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        confirmButtonText: 'Ya Hapus',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $('#delete-form-' + id).submit();
                        }
                    });
                });

                // Flash Messages
                @if (session('success'))
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: '{{ session('success') }}',
                        timer: 2000,
                        showConfirmButton: false
                    });
                @endif
                @if (session('error'))
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: '{{ session('error') }}'
                    });
                @endif
                @if (session('info'))
                    Swal.fire({
                        icon: 'info',
                        title: 'Info',
                        text: '{{ session('info') }}'
                    });
                @endif
                @error('file_excel')
                    var importModal = new bootstrap.Modal(document.getElementById('importModal'));
                    importModal.show();
                @enderror

                // Filter Logic
                var filterKecId = '{{ request('kecamatan_id') }}';
                var filterKelId = '{{ request('kelurahan_id') }}';

                function loadFilterKelurahan(kecId, selectedKelId) {
                    if (kecId) {
                        $.get("{{ url('api/kelurahans') }}/" + kecId, function(data) {
                            $('#filter_kelurahan_id').empty();
                            $('#filter_kelurahan_id').append('<option value="">Semua Kelurahan</option>');
                            $.each(data, function(key, value) {
                                var isSelected = (value.id == selectedKelId) ? 'selected' : '';
                                $('#filter_kelurahan_id').append('<option value="' + value.id + '" ' +
                                    isSelected + '>' + value.nama_kelurahan + '</option>');
                            });
                        });
                    } else {
                        $('#filter_kelurahan_id').empty();
                        $('#filter_kelurahan_id').append('<option value="">Semua Kelurahan</option>');
                    }
                }

                if (filterKecId) {
                    loadFilterKelurahan(filterKecId, filterKelId);
                }

                $('#filter_kecamatan_id').change(function() {
                    var kecId = $(this).val();
                    loadFilterKelurahan(kecId, null);
                });
            });
        </script>
    @endpush

    <!-- Insentif Modal -->
    <div class="modal fade" id="insentifModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Proses Insentif Marbot</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('marbot.insentif.process') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="alert alert-info">
                            <i class="fas fa-users me-1"></i> Memproses untuk <strong id="selected-count">0</strong>
                            marbot.
                        </div>
                        <div id="insentif-uuids-container"></div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Bulan</label>
                                <select class="form-select" name="bulan" required>
                                    @foreach (range(1, 12) as $m)
                                        <option value="{{ $m }}" {{ date('n') == $m ? 'selected' : '' }}>
                                            {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Tahun</label>
                                <input type="number" class="form-control" name="tahun_anggaran"
                                    value="{{ date('Y') }}" required min="2024" max="2030">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Nominal (Rp)</label>
                            <input type="number" class="form-control" name="nominal" placeholder="Contoh: 1500000"
                                required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Tanggal Terima</label>
                            <input type="date" class="form-control" name="tanggal_terima"
                                value="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Keterangan</label>
                            <textarea class="form-control" name="keterangan" rows="2"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-success">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Export Modal -->
    <div class="modal fade" id="exportModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Export Excel</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('marbot.export') }}" method="GET">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select class="form-select" name="status">
                                <option value="">Semua</option>
                                <option value="disetujui">Disetujui</option>
                                <option value="diajukan">Diajukan</option>
                            </select>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <label class="form-label">Dari</label>
                                <input type="date" class="form-control" name="start_date">
                            </div>
                            <div class="col-6">
                                <label class="form-label">Sampai</label>
                                <input type="date" class="form-control" name="end_date">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Download</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Import Modal -->
    <div class="modal fade" id="importModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Import Data</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('marbot.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body text-center">
                        <p class="small text-muted mb-3">Upload file Excel (.xlsx).</p>
                        <input type="file" class="form-control mb-3" name="file_excel" accept=".xlsx, .xls" required>
                        <a href="{{ route('marbot.template') }}" class="btn btn-link btn-sm">Download Template</a>
                    </div>
                    <div class="modal-footer justify-content-center">
                        <button type="submit" class="btn btn-primary">Import</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Archive Modal -->
    <div class="modal fade" id="downloadArchiveModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Download Arsip (ZIP)</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('marbot.download_archive') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-6">
                                <label class="form-label">Dari</label>
                                <input type="date" class="form-control" name="start_date" required>
                            </div>
                            <div class="col-6">
                                <label class="form-label">Sampai</label>
                                <input type="date" class="form-control" name="end_date" required>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Download ZIP</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Settings Modal -->
    <div class="modal fade" id="settingsModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Jadwal Pendaftaran</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('marbot.settings.update') }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="mb-2">
                            <label class="form-label">Mulai</label>
                            <input type="date" class="form-control" name="start_date" value="{{ $startDate }}"
                                required>
                        </div>
                        <div class="mb-2">
                            <label class="form-label">Selesai</label>
                            <input type="date" class="form-control" name="end_date" value="{{ $endDate }}"
                                required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
