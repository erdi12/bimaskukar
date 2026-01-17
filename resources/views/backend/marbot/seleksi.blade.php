@extends('layout.appv2')

@section('title', 'Seleksi Marbot Umroh')

@section('content')
    <div class="container-fluid mt-4">
        <div
            class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center mb-4 gap-3">
            <div>
                <h3 class="mb-1 fw-bold text-dark">Seleksi Calon Jamaah Umroh</h3>
                <p class="text-muted mb-0">Fitur penyeleksian otomatis dan pendataan marbot umroh bantuan Gubernur.</p>
            </div>
            <div>
                <a href="{{ route('marbot.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Kembali ke Data Marbot
                </a>
            </div>
        </div>

        <!-- Filter Card -->
        <div class="card shadow-sm border-0 rounded-3 mb-4">
            <div class="card-header py-3 border-0">
                <h5 class="card-title fw-bold text-primary mb-0"><i class="fas fa-filter me-2"></i> Filter Kriteria Otomatis
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('marbot.seleksi') }}" method="GET" class="row g-3 align-items-end">
                    <div class="col-md-3">
                        <label class="form-label fw-bold">Minimal Usia (Tahun)</label>
                        <input type="number" name="min_usia" class="form-control" value="{{ $minUsia }}"
                            min="0">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-bold">Min. Pengabdian (Tahun)</label>
                        <input type="number" name="min_lama_kerja" class="form-control" value="{{ $minLamaKerja }}"
                            min="0">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-bold">Status Seleksi</label>
                        <select name="status_umroh" class="form-select">
                            <option value="all" {{ $statusUmroh == 'all' ? 'selected' : '' }}>Semua Status</option>
                            <option value="belum_terpilih" {{ $statusUmroh == 'belum_terpilih' ? 'selected' : '' }}>Belum
                                Terpilih</option>
                            <option value="kandidat" {{ $statusUmroh == 'kandidat' ? 'selected' : '' }}>Kandidat</option>
                            <option value="terverifikasi" {{ $statusUmroh == 'terverifikasi' ? 'selected' : '' }}>
                                Terverifikasi</option>
                            <option value="berangkat" {{ $statusUmroh == 'berangkat' ? 'selected' : '' }}>Sudah Berangkat
                            </option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-search me-1"></i> Terapkan Filter
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Candidates List -->
        <div class="card shadow-sm border-0 rounded-3">
            <div class="card-header py-3 border-0 d-flex justify-content-between align-items-center">
                <h5 class="card-title fw-bold text-success mb-0">
                    <i class="fas fa-users me-2"></i> Hasil Penjaringan ({{ $candidates->count() }})
                </h5>
                <div>
                    <a href="{{ route('marbot.seleksi.export') }}" class="btn btn-sm btn-outline-success">
                        <i class="fas fa-file-excel me-1"></i> Download Data Jamaah
                    </a>
                    <!-- Bulk Actions handled by JS/Form -->
                </div>
            </div>

            <div class="card-body">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <form action="{{ route('marbot.seleksi.proses') }}" method="POST" id="processForm">
                    @csrf

                    <div class="mb-3 p-3 bg-light rounded border d-flex align-items-center justify-content-between sticky-top"
                        style="z-index: 10;">
                        <div class="d-flex align-items-center gap-2">
                            <i class="fas fa-info-circle text-info"></i>
                            <span class="small text-muted">Pilih marbot di bawah untuk mengubah status seleksi masal.</span>
                        </div>
                        <div class="d-flex gap-2">
                            <button type="submit" name="action" value="kandidat" class="btn btn-sm btn-info text-white">
                                <i class="fas fa-user-tag me-1"></i> Tandai Kandidat
                            </button>
                            <button type="submit" name="action" value="terverifikasi" class="btn btn-sm btn-success">
                                <i class="fas fa-check-double me-1"></i> Verifikasi Final
                            </button>
                            <!-- Changed to type button -->
                            <button type="button" name="action" value="berangkat" class="btn btn-sm btn-dark"
                                id="btnTriggerBerangkat">
                                <i class="fas fa-plane-departure me-1"></i> Set Berangkat
                            </button>
                            <button type="submit" name="action" value="batal" class="btn btn-sm btn-outline-danger">
                                <i class="fas fa-times me-1"></i> Reset Status
                            </button>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle" id="table-seleksi">
                            <thead class="table-light">
                                <tr>
                                    <th width="5%" class="text-center">
                                        <input type="checkbox" class="form-check-input" id="checkAll">
                                    </th>
                                    <th>Nama Marbot</th>
                                    <th>Usia</th>
                                    <th>Masa Pengabdian</th>
                                    <th>Lokasi Tugas</th>
                                    <th class="text-center">Status Saat Ini</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($candidates as $marbot)
                                    <tr class="{{ $marbot->status_umroh ? 'bg-success bg-opacity-10' : '' }}">
                                        <td class="text-center">
                                            <input type="checkbox" name="marbot_uuids[]" value="{{ $marbot->uuid }}"
                                                data-status="{{ $marbot->status_umroh }}"
                                                class="form-check-input check-item">
                                        </td>
                                        <td>
                                            <div class="fw-bold">{{ $marbot->nama_lengkap }}</div>
                                            <div class="small text-muted">{{ $marbot->nik }}</div>
                                            @if ($marbot->nomor_induk_marbot)
                                                <span
                                                    class="badge bg-light text-dark border">{{ $marbot->nomor_induk_marbot }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span
                                                class="fw-bold {{ $marbot->usia >= $minUsia ? 'text-success' : 'text-danger' }}">
                                                {{ $marbot->usia }} Tahun
                                            </span>
                                            <div class="small text-muted">
                                                {{ \Carbon\Carbon::parse($marbot->tanggal_lahir)->format('d M Y') }}</div>
                                        </td>
                                        <td>
                                            <span
                                                class="fw-bold {{ $marbot->lama_kerja >= $minLamaKerja ? 'text-success' : 'text-danger' }}">
                                                {{ floor($marbot->lama_kerja) }} Tahun
                                            </span>
                                            <div class="small text-muted">Sejak:
                                                {{ \Carbon\Carbon::parse($marbot->tanggal_mulai_bekerja)->format('Y') }}
                                            </div>
                                        </td>
                                        <td>
                                            @if ($marbot->rumah_ibadah)
                                                <div class="fw-bold small">
                                                    {{ $marbot->tipe_rumah_ibadah == 'Masjid' ? $marbot->rumah_ibadah->nama_masjid : $marbot->rumah_ibadah->nama_mushalla }}
                                                </div>
                                            @else
                                                <div class="text-danger small">-</div>
                                            @endif
                                            <div class="small text-muted">Kec. {{ $marbot->kecamatan->kecamatan ?? '-' }}
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            @if ($marbot->status_umroh == 'kandidat')
                                                <span class="badge bg-info">Kandidat</span>
                                            @elseif($marbot->status_umroh == 'terverifikasi')
                                                <span class="badge bg-success">Terverifikasi</span>
                                            @elseif($marbot->status_umroh == 'berangkat')
                                                <span class="badge bg-dark">Berangkat
                                                    {{ $marbot->jadwal_keberangkatan }}</span>
                                            @else
                                                <span class="badge bg-secondary bg-opacity-25 text-secondary">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('marbot.show', $marbot->uuid) }}"
                                                class="btn btn-sm btn-light border" target="_blank" title="Lihat Profil">
                                                <i class="fas fa-external-link-alt"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-5">
                                            <img src="https://cdni.iconscout.com/illustration/premium/thumb/empty-state-2130362-1800926.png"
                                                alt="Empty" style="width: 150px; opacity: 0.5;">
                                            <p class="text-muted mt-3">Tidak ada data marbot yang memenuhi kriteria filter
                                                saat ini.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modal Pilih Tanggal Keberangkatan (Moved inside Content) -->
        <div class="modal fade" id="modalBerangkat" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title fw-bold">Jadwalkan Keberangkatan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p class="text-muted small">Silakan pilih bulan dan tahun keberangkatan untuk marbot yang terpilih.
                        </p>
                        <div class="form-group mb-3">
                            <label class="form-label fw-bold">Bulan Keberangkatan</label>
                            <select id="inputBulan" class="form-select">
                                <option value="">-- Pilih Bulan --</option>
                                @foreach (range(1, 12) as $m)
                                    <option value="{{ $m }}">
                                        {{ \Carbon\Carbon::create()->month($m)->locale('id')->monthName }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group mb-3">
                            <label class="form-label fw-bold">Tahun Keberangkatan</label>
                            <select id="inputTahun" class="form-select">
                                @foreach (range(date('Y'), date('Y') + 5) as $y)
                                    <option value="{{ $y }}">{{ $y }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="button" class="btn btn-primary" id="btnConfirmBerangkat">
                            <i class="fas fa-check me-1"></i> Simpan & Proses
                        </button>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection

@push('scripts')
    <script>
        $(function() {
            // Track selected IDs across pages
            let selectedMarbots = [];
            let marbotStatus = {}; // Store status for validation

            // Initialize DataTable
            var table = $("#table-seleksi").DataTable({
                "responsive": true,
                "lengthChange": true,
                "autoWidth": false,
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json"
                },
                "columnDefs": [{
                    "orderable": false,
                    "targets": [0, 6]
                }],
                "drawCallback": function(settings) {
                    // Update checkboxes on current page based on selectedMarbots
                    $('.check-item').each(function() {
                        let id = $(this).val();
                        if (selectedMarbots.includes(id)) {
                            $(this).prop('checked', true);
                        }
                    });

                    // Update "Check All" state
                    updateCheckAllState();
                }
            });

            // Handle individual checkbox change
            $('#table-seleksi tbody').on('change', '.check-item', function() {
                let id = $(this).val();
                let status = $(this).data('status');

                if (this.checked) {
                    if (!selectedMarbots.includes(id)) {
                        selectedMarbots.push(id);
                        marbotStatus[id] = status;
                    }
                } else {
                    selectedMarbots = selectedMarbots.filter(item => item !== id);
                    delete marbotStatus[id];
                }
                updateCheckAllState();
            });

            // Handle "Check All" change
            $('#checkAll').on('change', function() {
                let isChecked = this.checked;

                // Only affect visible rows on current page
                let rows = table.rows({
                    'page': 'current'
                }).nodes();
                $('input[type="checkbox"]', rows).each(function() {
                    let id = $(this).val();
                    let status = $(this).data('status');

                    $(this).prop('checked', isChecked);

                    if (isChecked) {
                        if (!selectedMarbots.includes(id)) {
                            selectedMarbots.push(id);
                            marbotStatus[id] = status;
                        }
                    } else {
                        selectedMarbots = selectedMarbots.filter(item => item !== id);
                        delete marbotStatus[id];
                    }
                });
            });

            function updateCheckAllState() {
                // Logic to update main checkbox based on generic check state if needed
                // For simplicity, we just uncheck master if any sub is unchecked
                // Not strictly critical, but good for UX
            }

            // Handle "Set Berangkat" trigger
            const btnTrigger = document.getElementById('btnTriggerBerangkat');
            if (btnTrigger) {
                btnTrigger.addEventListener('click', function(e) {
                    e.preventDefault();

                    if (selectedMarbots.length === 0) {
                        Swal.fire('Perhatian', 'Pilih setidaknya satu marbot untuk diproses.', 'warning');
                        return;
                    }

                    // Validation using stored status map
                    let allVerified = true;
                    selectedMarbots.forEach(id => {
                        if (marbotStatus[id] !== 'terverifikasi') {
                            allVerified = false;
                        }
                    });

                    if (!allVerified) {
                        Swal.fire('Gagal',
                            'Hanya marbot yang statusnya "Terverifikasi" yang bisa diset "Berangkat". Mohon cek kembali pilihan Anda.',
                            'error');
                        return;
                    }

                    // Show Modal
                    const modalEl = document.getElementById('modalBerangkat');
                    if (modalEl) {
                        if (typeof bootstrap !== 'undefined') {
                            new bootstrap.Modal(modalEl).show();
                        } else {
                            $(modalEl).modal('show');
                        }
                    }
                });
            }

            // Handle Modal Confirmation
            const btnConfirm = document.getElementById('btnConfirmBerangkat');
            if (btnConfirm) {
                btnConfirm.addEventListener('click', function() {
                    const bulan = document.getElementById('inputBulan').value;
                    const tahun = document.getElementById('inputTahun').value;

                    if (!bulan || !tahun) {
                        Swal.fire('Perhatian', 'Mohon pilih bulan dan tahun keberangkatan.', 'warning');
                        return;
                    }

                    submitFormWithSelection('berangkat', {
                        bulan: bulan,
                        tahun: tahun
                    });
                });
            }

            // Form Submit prevent default & handle generic buttons
            const processForm = document.getElementById('processForm');
            if (processForm) {
                // Intercept buttons with type="submit" inside the form
                // Because we need to inject the hidden inputs for ALL pages

                // We remove default submit types from buttons and make them triggers
                // But since they are already rendered, we hook onto the click event of submit buttons
                // Or easier: hook onto form submit

                processForm.addEventListener('submit', function(e) {
                    e.preventDefault(); // Stop default submission

                    if (selectedMarbots.length === 0) {
                        Swal.fire('Perhatian', 'Pilih setidaknya satu marbot untuk diproses.', 'warning');
                        return;
                    }

                    // Note: 'action' is usually passed by the clicked button.
                    // This is tricky with JS submit.
                    // Solution: We will inject data based on which button was clicked.
                    // However, 'e.submitter' gives us the button that triggered submit.

                    let action = e.submitter ? e.submitter.value : null;
                    if (action && action !== 'berangkat') { // Berangkat is handled separately
                        submitFormWithSelection(action);
                    }
                });
            }

            function submitFormWithSelection(actionValue, extraData = {}) {
                const form = document.getElementById('processForm');

                // Remove existing temp inputs
                $(form).find('.temp-input').remove();

                // Add selected UUIDs
                selectedMarbots.forEach(uuid => {
                    let input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'marbot_uuids[]'; // Must match controller expectation
                    input.value = uuid;
                    input.className = 'temp-input';
                    form.appendChild(input);
                });

                // Add Action
                let inputAction = document.createElement('input');
                inputAction.type = 'hidden';
                inputAction.name = 'action';
                inputAction.value = actionValue;
                inputAction.className = 'temp-input';
                form.appendChild(inputAction);

                // Add Extras (Bulan/Tahun)
                for (const [key, value] of Object.entries(extraData)) {
                    let input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = key;
                    input.value = value;
                    input.className = 'temp-input';
                    form.appendChild(input);
                }

                form.submit();
            }
        });
    </script>
@endpush
