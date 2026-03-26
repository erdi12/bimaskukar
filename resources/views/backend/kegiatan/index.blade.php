@extends('layout.appv2')
@section('kegiatan', 'active')

@push('styles')
    <!-- FullCalendar CSS -->
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.css" rel="stylesheet" />
    <style>
        /* Modern Calendar Styles */
        .fc {
            background: #fff;
            padding: 1rem;
            border-radius: 1rem;
        }

        .fc-theme-standard .fc-scrollgrid {
            border: 1px solid rgba(0, 0, 0, 0.04);
            border-radius: 0 0 1rem 1rem;
            overflow: hidden;
        }

        .fc-theme-standard td,
        .fc-theme-standard th {
            border: 1px solid rgba(0, 0, 0, 0.04);
        }

        .fc-col-header-cell {
            padding: 12px 0 !important;
            background-color: #f8f9fa;
            color: #495057;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 0.5px;
        }

        .fc-daygrid-day-number {
            font-weight: 500;
            color: #495057;
            padding: 8px !important;
            font-size: 0.95rem;
            text-decoration: none !important;
            transition: all 0.2s ease;
        }

        .fc-daygrid-day-number:hover {
            color: #435ebe;
        }

        .fc .fc-day-today {
            background-color: rgba(67, 94, 190, 0.03) !important;
        }

        .fc .fc-day-today .fc-daygrid-day-number {
            background-color: #435ebe;
            color: white !important;
            border-radius: 50%;
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 4px;
            box-shadow: 0 4px 8px rgba(67, 94, 190, 0.3);
        }

        .fc-event {
            cursor: pointer;
            border: none !important;
            border-radius: 6px;
            padding: 3px 6px;
            font-size: 0.8rem;
            font-weight: 500;
            margin-bottom: 4px;
            transition: all 0.2s ease;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .fc-event:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            filter: brightness(1.05);
        }

        .fc-event-title {
            font-weight: 600 !important;
        }

        .fc .fc-button-primary {
            background-color: #fff;
            color: #495057;
            border: 1px solid rgba(0, 0, 0, 0.1);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.02);
            text-transform: capitalize;
            font-weight: 500;
            border-radius: 8px;
            padding: 8px 16px;
            transition: all 0.2s ease;
        }

        .fc .fc-button-primary:not(:disabled):active,
        .fc .fc-button-primary:not(:disabled).fc-button-active {
            background-color: #435ebe;
            border-color: #435ebe;
            color: white;
            box-shadow: 0 3px 8px rgba(67, 94, 190, 0.25);
        }

        .fc .fc-button-primary:hover {
            background-color: #f8f9fa;
            color: #435ebe;
            border-color: #435ebe;
            transform: translateY(-1px);
        }

        .fc .fc-toolbar-title {
            font-weight: 700;
            color: #2b3990;
            font-size: 1.5rem !important;
        }

        .card-modern {
            border-radius: 1rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05) !important;
        }

        /* Modern Modal Styles */
        .modal-content.custom-rounded {
            border-radius: 1.25rem;
            overflow: hidden;
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
            border: none;
        }

        .modal-header-custom {
            background: rgba(67, 94, 190, 0.05);
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            padding: 1.25rem 1.5rem;
        }

        .modal-body-custom {
            padding: 1.5rem;
        }

        .form-label {
            font-weight: 600;
            color: #495057;
            margin-bottom: 0.5rem;
        }

        .form-control,
        .form-select {
            border-radius: 0.5rem;
            border: 1px solid #dee2e6;
            padding: 0.6rem 1rem;
            background-color: #fcfcfc;
            transition: all 0.2s ease;
        }

        .form-control:focus,
        .form-select:focus {
            background-color: #fff;
            border-color: #435ebe;
            box-shadow: 0 0 0 0.25rem rgba(67, 94, 190, 0.15);
        }

        /* Responsive Improvements */
        @media (min-width: 992px) {
            .border-end-lg {
                border-right: 1px solid #e9ecef;
            }
        }

        @media (max-width: 767.98px) {
            .fc .fc-toolbar.fc-header-toolbar {
                flex-direction: column;
                gap: 15px;
            }

            .fc .fc-toolbar-chunk {
                display: flex;
                justify-content: center;
                flex-wrap: wrap;
                gap: 5px;
            }

            .fc-toolbar-title {
                font-size: 1.25rem !important;
            }

            #btnAddEvent {
                width: 100%;
            }
        }
    </style>
@endpush

@section('content')
    <div class="content">
        <div class="page-title d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
            <h3 class="mb-0">Calendar Kegiatan</h3>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#kegiatanModal" id="btnAddEvent">
                <i class="fas fa-plus"></i> Tambah Kegiatan
            </button>
        </div>

        <div class="card card-modern border-0 shadow-sm">
            <div class="card-body">
                <div id="calendar"></div>
            </div>
        </div>
    </div>

    <!-- Modal Kegiatan -->
    <div class="modal fade" id="kegiatanModal" tabindex="-1" aria-labelledby="kegiatanModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content custom-rounded">
                <form id="formKegiatan">
                    <div class="modal-header-custom d-flex justify-content-between align-items-center">
                        <h5 class="modal-title fw-bold text-primary" id="kegiatanModalLabel"><i
                                class="fas fa-calendar-plus me-2"></i> Detail Kegiatan</h5>
                        <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body-custom">
                        <input type="hidden" id="kegiatan_id" name="kegiatan_id">

                        <div class="row">
                            <!-- Left Column -->
                            <div class="col-lg-6 pe-lg-4 border-end-lg mb-3 mb-lg-0">
                                <div class="mb-3">
                                    <label class="form-label">Nama Kegiatan <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="title" name="title" required
                                        placeholder="Contoh: Rapat Koordinasi Tahunan">
                                </div>

                                <div class="row">
                                    <div class="col-sm-6 mb-3">
                                        <label class="form-label">Waktu Mulai <span class="text-danger">*</span></label>
                                        <input type="datetime-local" class="form-control" id="start_date" name="start_date" required>
                                    </div>
                                    <div class="col-sm-6 mb-3">
                                        <label class="form-label">Waktu Selesai <span class="text-danger">*</span></label>
                                        <input type="datetime-local" class="form-control" id="end_date" name="end_date" required>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Jenis Kegiatan <span class="text-danger">*</span></label>
                                    <select class="form-select" id="type" name="type" required>
                                        <option value="">Pilih Jenis</option>
                                        <option value="Rapat">Rapat</option>
                                        <option value="Perjalanan Dinas">Perjalanan Dinas</option>
                                        <option value="Seminar">Seminar</option>
                                        <option value="Lainnya">Lainnya</option>
                                    </select>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label">Warna Label Kalender</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-white border-end-0"><i
                                                class="fas fa-palette text-muted"></i></span>
                                        <input type="color" class="form-control form-control-color border-start-0 ps-0 w-100"
                                            id="color" name="color" value="#435ebe"
                                            title="Pilih warna untuk membedakan kegiatan" style="height: 45px; padding: 5px;">
                                    </div>
                                </div>
                            </div>

                            <!-- Right Column -->
                            <div class="col-lg-6 ps-lg-4">
                                <div class="mb-3">
                                    <label class="form-label">Lokasi / Tempat</label>
                                    <input type="text" class="form-control" id="location" name="location"
                                        placeholder="Lokasi Pelaksanaan">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Petugas / PIC</label>
                                    <input type="text" class="form-control" id="petugas" name="petugas"
                                        placeholder="Nama Petugas atau Penanggung Jawab">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Keterangan Tambahan</label>
                                    <textarea class="form-control" id="description" name="description" rows="5"
                                        placeholder="Informasi tambahan terkait kegiatan ini..." style="height: 135px;"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0 px-4 pb-4 pt-0 bg-white">
                        <button type="button" class="btn btn-outline-danger me-auto d-none px-4 rounded-pill"
                            id="btnDeleteEvent"><i class="fas fa-trash-alt me-1"></i> Hapus</button>
                        <button type="button" class="btn btn-secondary px-4 rounded-pill"
                            data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary px-4 rounded-pill shadow-sm" id="btnSaveEvent"><i
                                class="fas fa-save me-1"></i> Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <!-- FullCalendar JS -->
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/locales/id.global.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
            var modal = new bootstrap.Modal(document.getElementById('kegiatanModal'));
            var form = document.getElementById('formKegiatan');

            var isMobile = window.innerWidth < 768;

            var calendar = new FullCalendar.Calendar(calendarEl, {
                locale: 'id',
                initialView: isMobile ? 'listMonth' : 'dayGridMonth',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: isMobile ? 'dayGridMonth,listMonth' :
                        'dayGridMonth,timeGridWeek,timeGridDay,listMonth'
                },
                themeSystem: 'bootstrap5',
                events: '{{ route('kegiatan.index') }}',
                editable: true,
                selectable: true,
                displayEventTime: false, // Menyembunyikan tampilan waktu pada event di kalender
                select: function(info) {
                    // Formatting dates for datetime-local input
                    var start = info.startStr.length > 10 ? info.startStr.slice(0, 16) : info.startStr +
                        'T08:00';
                    var end = info.endStr.length > 10 ? info.endStr.slice(0, 16) : info.endStr +
                        'T09:00';

                    resetForm();
                    document.getElementById('start_date').value = start;
                    document.getElementById('end_date').value = end;

                    document.getElementById('kegiatanModalLabel').innerText = 'Tambah Kegiatan Baru';
                    document.getElementById('btnDeleteEvent').classList.add('d-none');
                    modal.show();
                },
                eventClick: function(info) {
                    var eventObj = info.event;

                    resetForm();

                    document.getElementById('kegiatan_id').value = eventObj.id;
                    document.getElementById('title').value = eventObj.title;

                    // Parse date for datetime-local input safely
                    var sDate = eventObj.start ? eventObj.start.toISOString().slice(0, 16) : '';
                    var eDate = eventObj.end ? eventObj.end.toISOString().slice(0, 16) : sDate;

                    document.getElementById('start_date').value = sDate;
                    document.getElementById('end_date').value = eDate;

                    document.getElementById('type').value = eventObj.extendedProps.type || '';
                    document.getElementById('petugas').value = eventObj.extendedProps.petugas || '';
                    document.getElementById('location').value = eventObj.extendedProps.location || '';
                    document.getElementById('description').value = eventObj.extendedProps.description ||
                        '';
                    document.getElementById('color').value = eventObj.backgroundColor || '#3788d8';

                    document.getElementById('kegiatanModalLabel').innerText = 'Edit Kegiatan';
                    document.getElementById('btnDeleteEvent').classList.remove('d-none');
                    modal.show();
                },
                eventDrop: function(info) {
                    updateEventDates(info.event);
                },
                eventResize: function(info) {
                    updateEventDates(info.event);
                }
            });
            calendar.render();

            function resetForm() {
                form.reset();
                document.getElementById('kegiatan_id').value = '';
                document.getElementById('color').value = '#3788d8';
            }

            document.getElementById('btnAddEvent').addEventListener('click', function() {
                resetForm();
                document.getElementById('kegiatanModalLabel').innerText = 'Tambah Kegiatan Baru';
                document.getElementById('btnDeleteEvent').classList.add('d-none');

                // Set default date to today 08:00
                var now = new Date();
                var year = now.getFullYear();
                var month = String(now.getMonth() + 1).padStart(2, '0');
                var day = String(now.getDate()).padStart(2, '0');
                document.getElementById('start_date').value = `${year}-${month}-${day}T08:00`;
                document.getElementById('end_date').value = `${year}-${month}-${day}T09:00`;
            });

            form.addEventListener('submit', function(e) {
                e.preventDefault();

                var id = document.getElementById('kegiatan_id').value;
                var formData = new FormData(form);
                var data = Object.fromEntries(formData.entries());

                var url = id ? '/appv2/kegiatan/' + id : '/appv2/kegiatan';
                var method = id ? 'PUT' : 'POST';

                var token = '{{ csrf_token() }}';

                fetch(url, {
                        method: method,
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': token,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify(data)
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.id) {
                            calendar.refetchEvents();
                            modal.hide();
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: 'Kegiatan berhasil disimpan',
                                timer: 1500,
                                showConfirmButton: false
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: 'Terjadi kesalahan saat menyimpan data.'
                        });
                    });
            });

            document.getElementById('btnDeleteEvent').addEventListener('click', function() {
                var id = document.getElementById('kegiatan_id').value;
                if (!id) return;

                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: "Data kegiatan akan dihapus secara permanen!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        var token = '{{ csrf_token() }}';

                        fetch('/appv2/kegiatan/' + id, {
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': token,
                                    'Accept': 'application/json'
                                }
                            })
                            .then(response => response.json())
                            .then(data => {
                                calendar.refetchEvents();
                                modal.hide();
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Terhapus!',
                                    text: 'Kegiatan berhasil dihapus.',
                                    timer: 1500,
                                    showConfirmButton: false
                                });
                            });
                    }
                });
            });

            function pad(n) {
                return n < 10 ? '0' + n : n;
            }

            function getLocalIsoString(date) {
                if (!date) return '';
                return date.getFullYear() + '-' + pad(date.getMonth() + 1) + '-' + pad(date.getDate()) + ' ' + pad(
                    date.getHours()) + ':' + pad(date.getMinutes()) + ':' + pad(date.getSeconds());
            }

            function updateEventDates(event) {
                var sDate = event.start ? getLocalIsoString(event.start) : '';
                var eDate = event.end ? getLocalIsoString(event.end) : sDate;

                var data = {
                    title: event.title,
                    start_date: sDate,
                    end_date: eDate,
                    type: event.extendedProps.type,
                    petugas: event.extendedProps.petugas,
                    location: event.extendedProps.location,
                    description: event.extendedProps.description,
                    color: event.backgroundColor
                };

                var token = '{{ csrf_token() }}';

                fetch('/appv2/kegiatan/' + event.id, {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': token,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify(data)
                    })
                    .then(response => {
                        if (!response.ok) {
                            event.revert();
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal mengubah jadwal.'
                            });
                        }
                    });
            }
        });
    </script>
@endpush
