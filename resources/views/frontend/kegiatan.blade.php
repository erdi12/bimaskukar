@extends('layout.frontend')

@section('title', 'Agenda Kegiatan - Bimas Islam Kemenag Kukar')

@push('styles')
    <!-- FullCalendar CSS -->
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.css" rel="stylesheet" />
    <style>
        .kegiatan-header {
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            color: white;
            padding: 60px 0;
            margin-top: 76px; /* Offset for fixed navbar */
            border-bottom-left-radius: 2rem;
            border-bottom-right-radius: 2rem;
            box-shadow: 0 10px 30px rgba(42, 157, 143, 0.2);
            margin-bottom: -30px; /* Pull the container up */
            position: relative;
            z-index: 1;
        }

        /* Modern Calendar Styles */
        .fc {
            font-family: 'Poppins', sans-serif;
            background: #fff;
            padding: 1rem;
            border-radius: 1.5rem;
        }
        
        .fc-theme-standard .fc-scrollgrid {
            border: 1px solid rgba(0,0,0,0.04);
            border-radius: 0 0 1.25rem 1.25rem;
            overflow: hidden;
        }
        
        .fc-theme-standard td, .fc-theme-standard th {
            border: 1px solid rgba(0,0,0,0.04);
        }
        
        .fc-col-header-cell {
            padding: 16px 0 !important;
            background-color: #f8f9fa;
            color: var(--secondary);
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 1px;
        }
        
        .fc-daygrid-day-number {
            font-weight: 500;
            color: #495057;
            padding: 10px !important;
            font-size: 0.95rem;
            text-decoration: none !important;
            transition: all 0.2s ease;
        }
        
        .fc-daygrid-day-number:hover {
            color: var(--primary);
        }
        
        .fc .fc-day-today {
            background-color: rgba(42, 157, 143, 0.03) !important;
        }
        
        .fc .fc-day-today .fc-daygrid-day-number {
            background-color: var(--primary);
            color: white !important;
            border-radius: 50%;
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 6px;
            box-shadow: 0 4px 10px rgba(42, 157, 143, 0.3);
        }

        .fc-event {
            cursor: pointer;
            border: none !important;
            border-radius: 8px;
            padding: 4px 8px;
            font-size: 0.8rem;
            font-weight: 500;
            margin-bottom: 5px;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 2px 5px rgba(0,0,0,0.06);
        }
        
        .fc-event:hover {
            transform: translateY(-3px) scale(1.02);
            box-shadow: 0 6px 15px rgba(0,0,0,0.12);
            filter: brightness(1.05);
            z-index: 5;
        }
        
        .fc-event-title {
            font-weight: 600 !important;
        }
        
        .fc .fc-button-primary {
            background-color: #fff;
            color: var(--secondary);
            border: 1px solid rgba(0,0,0,0.08);
            box-shadow: 0 2px 6px rgba(0,0,0,0.03);
            text-transform: capitalize;
            font-weight: 600;
            border-radius: 10px;
            padding: 8px 18px;
            transition: all 0.2s ease;
        }
        
        .fc .fc-button-primary:not(:disabled):active,
        .fc .fc-button-primary:not(:disabled).fc-button-active {
            background-color: var(--primary);
            border-color: var(--primary);
            color: white;
            box-shadow: 0 4px 12px rgba(42, 157, 143, 0.25);
        }
        
        .fc .fc-button-primary:hover {
            background-color: #f8f9fa;
            color: var(--primary);
            border-color: var(--primary);
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
            transform: translateY(-1px);
        }
        
        .fc .fc-button-primary:not(:disabled).fc-button-active:hover {
            background-color: var(--primary);
            color: white;
            box-shadow: 0 6px 15px rgba(42, 157, 143, 0.3);
        }

        .fc .fc-toolbar-title {
            font-weight: 700;
            color: var(--secondary);
            font-size: 1.6rem !important;
            letter-spacing: -0.5px;
        }

        .fc-theme-standard td, .fc-theme-standard th {
            border-color: #f1f3f5;
        }
        
        .card.shadow-sm.border-0.rounded-4 {
            box-shadow: 0 15px 40px rgba(0,0,0,0.06) !important;
            position: relative;
            z-index: 2;
        }

        /* Responsive Improvements */
        @media (max-width: 767.98px) {
            .fc .fc-toolbar.fc-header-toolbar {
                flex-direction: column;
                gap: 15px;
            }

            .fc .fc-toolbar-chunk {
                display: flex;
                justify-content: center;
                flex-wrap: wrap;
                gap: 10px;
            }

            .fc-toolbar-title {
                font-size: 1.35rem !important;
            }
            
            .kegiatan-header {
                padding: 40px 0 60px;
                border-bottom-left-radius: 1.5rem;
                border-bottom-right-radius: 1.5rem;
            }
            
            .fc {
                padding: 0.5rem;
            }
        }
        /* Modern Modal Styles */
        .modal-content.custom-rounded {
            border-radius: 1.5rem;
            overflow: hidden;
            box-shadow: 0 20px 50px rgba(0,0,0,0.15);
            border: none;
        }
        
        .modal-header-custom {
            background: rgba(42, 157, 143, 0.05); /* very light primary variant */
            border-bottom: 1px solid rgba(0,0,0,0.05);
            padding: 1.5rem 1.5rem 1rem;
            position: relative;
        }
        
        .event-accent-line {
            height: 6px;
            width: 100%;
            background: var(--primary); /* Will be overridden by event color */
            position: absolute;
            top: 0;
            left: 0;
        }
        
        .modal-body-custom {
            padding: 1.5rem;
        }
        
        .detail-item-card {
            background: #fff;
            border: 1px solid rgba(0,0,0,0.06);
            border-radius: 1rem;
            padding: 1rem;
            margin-bottom: 0.75rem;
            display: flex;
            align-items: center;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        
        .detail-item-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            border-color: rgba(42, 157, 143, 0.2);
        }
        
        .icon-box {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 1.25rem;
            font-size: 1.35rem;
            flex-shrink: 0;
        }
        
        .icon-box.time-icon { background: rgba(42, 157, 143, 0.1); color: var(--primary); }
        .icon-box.location-icon { background: rgba(233, 196, 106, 0.15); color: #e09f3e; }
        .icon-box.person-icon { background: rgba(38, 70, 83, 0.1); color: var(--secondary); }
        
        .badge-type {
            padding: 0.6em 1.2em;
            border-radius: 50rem;
            font-weight: 500;
            letter-spacing: 0.5px;
            font-size: 0.85rem;
            background: var(--secondary);
            color: white;
            box-shadow: 0 3px 8px rgba(38, 70, 83, 0.3);
        }

        .desc-box {
            background: #f8f9fa;
            border-radius: 1rem;
            padding: 1.25rem;
            margin-top: 1rem;
            border-left: 4px solid var(--primary);
        }
    </style>
@endpush

@section('content')
    <!-- Header Section -->
    <section class="kegiatan-header text-center">
        <div class="container">
            <h2 class="display-6 fw-bold">Agenda Kegiatan</h2>
            <p class="lead mb-0">Jadwal Acara dan Kegiatan Bimas Islam Kemenag Kutai Kartanegara</p>
        </div>
    </section>

    <!-- Calendar Section -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-body p-4">
                    <div id="calendar"></div>
                </div>
            </div>
        </div>
    </section>

    <!-- Modal Detail Kegiatan -->
    <div class="modal fade" id="kegiatanModal" tabindex="-1" aria-labelledby="kegiatanModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content custom-rounded">
                <div class="modal-header-custom">
                    <div class="event-accent-line" id="modalAccentLine"></div>
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <span id="detail_type" class="badge-type d-inline-block mb-3"></span>
                            <h4 id="detail_title" class="fw-bold mb-0 text-secondary-custom" style="line-height: 1.3;"></h4>
                        </div>
                        <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close" style="padding: 1rem; margin: -1rem;"></button>
                    </div>
                </div>
                
                <div class="modal-body-custom">
                    
                    <div class="detail-item-card mt-3">
                        <div class="icon-box time-icon"><i class="bi bi-clock"></i></div>
                        <div>
                            <small class="text-muted d-block fw-semibold mb-1">Waktu Pelaksanaan</small>
                            <span id="detail_time" class="fw-medium text-dark"></span>
                        </div>
                    </div>
                    
                    <div class="detail-item-card">
                        <div class="icon-box location-icon"><i class="bi bi-geo-alt"></i></div>
                        <div>
                            <small class="text-muted d-block fw-semibold mb-1">Lokasi</small>
                            <span id="detail_location" class="fw-medium text-dark"></span>
                        </div>
                    </div>

                    <div class="detail-item-card">
                        <div class="icon-box person-icon"><i class="bi bi-person-badge"></i></div>
                        <div>
                            <small class="text-muted d-block fw-semibold mb-1">PIC / Petugas</small>
                            <span id="detail_petugas" class="fw-medium text-dark"></span>
                        </div>
                    </div>

                    <div class="desc-box">
                        <small class="text-primary-custom d-block fw-bold mb-2"><i class="bi bi-info-circle me-1"></i> Keterangan</small>
                        <p id="detail_description" class="mb-0 text-secondary" style="line-height: 1.6;"></p>
                    </div>
                </div>
                
                <div class="modal-footer border-0 pt-0 pb-4 px-4 bg-white justify-content-center">
                    <button type="button" class="btn btn-primary px-5 py-2 rounded-pill shadow-sm" data-bs-dismiss="modal" style="font-weight: 500;">Tutup Jendela</button>
                </div>
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

            var isMobile = window.innerWidth < 768;

            var calendar = new FullCalendar.Calendar(calendarEl, {
                locale: 'id',
                initialView: isMobile ? 'listMonth' : 'dayGridMonth',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: isMobile ? 'dayGridMonth,listMonth' : 'dayGridMonth,timeGridWeek,timeGridDay,listMonth'
                },
                themeSystem: 'bootstrap5',
                events: '{{ route('agenda.kegiatan') }}', // Route name for frontend agenda
                editable: false,
                selectable: false,
                displayEventTime: false,
                eventClick: function(info) {
                    var eventObj = info.event;
                    
                    document.getElementById('detail_title').innerText = eventObj.title;
                    
                    // Set accent line color based on event color
                    var eventColor = eventObj.backgroundColor || 'var(--primary)';
                    document.getElementById('modalAccentLine').style.backgroundColor = eventColor;
                    document.getElementById('detail_type').style.backgroundColor = eventColor;
                    
                    // Format waktu
                    var optionsDate = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit' };
                    var sDate = eventObj.start ? eventObj.start.toLocaleDateString('id-ID', optionsDate) : '-';
                    var eDate = eventObj.end ? eventObj.end.toLocaleDateString('id-ID', optionsDate) : sDate;
                    
                    var timeStr = sDate;
                    if (eventObj.end && eventObj.start.getTime() !== eventObj.end.getTime()) {
                        timeStr += ' s/d ' + eDate;
                    }
                    document.getElementById('detail_time').innerText = timeStr;
                    
                    document.getElementById('detail_location').innerText = eventObj.extendedProps.location || '-';
                    document.getElementById('detail_petugas').innerText = eventObj.extendedProps.petugas || '-';
                    document.getElementById('detail_type').innerText = eventObj.extendedProps.type || '-';
                    document.getElementById('detail_description').innerText = eventObj.extendedProps.description || '-';

                    modal.show();
                }
            });
            calendar.render();
        });
    </script>
@endpush
