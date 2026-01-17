<!-- Header -->
<div class="header" id="header">
    <div class="header-left">
        <button class="menu-toggle" id="menuToggle">
            <i class="fas fa-bars"></i>
        </button>
        <button class="sidebar-toggle" id="sidebarToggle">
            <i class="fas fa-bars"></i>
        </button>
        <div class="search-bar">
            <i class="fas fa-search"></i>
            <input type="text" class="form-control" placeholder="Cari...">
        </div>
    </div>

    <div class="header-right">
        <!-- Theme Toggle -->
        <div class="theme-toggle" style="margin-right: 20px; cursor: pointer;" id="themeToggle">
            <i class="fas fa-moon" id="themeIcon" style="font-size: 1.2rem; color: #607080;"></i>
        </div>

        <div class="notification-icon dropdown">
            <a href="#" class="dropdown-toggle text-decoration-none" id="notificationDropdown" role="button"
                data-bs-toggle="dropdown" aria-expanded="false" style="color: inherit;">
                <div class="position-relative">
                    <i class="fas fa-bell fa-lg" style="color: #277748;"></i>
                    <span id="notif-badge"
                        class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-success text-light border border-2 border-white"
                        style="font-size: 0.6rem; padding: 0.25em 0.5em; display: {{ $notification_count > 0 ? 'inline-block' : 'none' }};">
                        {{ $notification_count }}
                    </span>
                    <!-- Pulse indicator for attention -->
                    <span id="notif-pulse" class="pulse-ring"
                        style="display: {{ $notification_count > 0 ? 'block' : 'none' }};"></span>
                </div>
            </a>

            <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg notification-menu mt-3"
                aria-labelledby="notificationDropdown">
                <li class="notification-header d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="fw-bold mb-0 text-white">Notifikasi</h6>
                        <small class="text-white-50" style="font-size: 0.7rem;" id="notif-count-text">Anda memiliki
                            {{ $notification_count }}
                            pesan belum
                            dibaca</small>
                    </div>
                    <a href="#" class="text-white text-decoration-none"
                        style="font-size: 0.75rem; background: rgba(255,255,255,0.2); padding: 4px 10px; border-radius: 20px;">Bersihkan</a>
                </li>

                <div class="notification-list custom-scrollbar" id="notification-list-container">

                    @if ($notification_count == 0)
                        <div class="text-center py-4 text-muted">
                            <i class="fas fa-bell-slash fa-2x mb-2 text-secondary opacity-25"></i>
                            <p class="mb-0 small">Belum ada notifikasi baru</p>
                        </div>
                    @endif

                    <!-- Notifikasi Marbot (Permohonan Baru) -->
                    @foreach ($notif_marbot_list as $notif)
                        <li>
                            <a class="dropdown-item notification-item unread d-flex gap-3 align-items-start"
                                href="{{ route('notifications.go', ['id' => $notif->id, 'url' => route('marbot.show', $notif->data['uuid'])]) }}">
                                <div class="icon-box bg-primary-subtle text-primary">
                                    <i class="fas fa-user-plus"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <span class="fw-bold text-dark fs-sm">Permohonan Marbot</span>
                                        <span class="notification-time">{{ $notif->created_at->diffForHumans() }}</span>
                                    </div>
                                    <p class="text-muted mb-0 fs-xs line-clamp-2">
                                        <span class="fw-medium text-dark">{{ $notif->data['nama_lengkap'] }}</span>
                                        mendaftar
                                        untuk
                                        {{ $notif->data['rumah_ibadah'] ?? 'Rumah Ibadah' }}.
                                    </p>
                                </div>
                            </a>
                        </li>
                    @endforeach

                    <!-- Notifikasi Majelis Taklim (Expired) -->
                    @foreach ($notif_mt_expired as $mt)
                        <li>
                            <a class="dropdown-item notification-item unread d-flex gap-3 align-items-start"
                                href="#">
                                <div class="icon-box bg-danger-subtle text-danger">
                                    <i class="fas fa-calendar-times"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <span class="fw-bold text-dark fs-sm">Masa Berlaku Habis</span>
                                        <span class="notification-time">Expired</span>
                                    </div>
                                    <p class="text-muted mb-0 fs-xs line-clamp-2">
                                        Surat keterangan <span
                                            class="fw-medium text-dark">{{ $mt->nama_majelis }}</span> telah habis masa
                                        berlakunya.
                                    </p>
                                </div>
                            </a>
                        </li>
                    @endforeach

                    <!-- Notifikasi Majelis Taklim (Warning) -->
                    @foreach ($notif_mt_warning as $mt)
                        <li>
                            <a class="dropdown-item notification-item d-flex gap-3 align-items-start" href="#">
                                <div class="icon-box bg-warning-subtle text-warning">
                                    <i class="fas fa-exclamation-triangle"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <span class="fw-bold text-dark fs-sm">Peringatan Expired</span>
                                        <span class="notification-time">Segera</span>
                                    </div>
                                    <p class="text-muted mb-0 fs-xs line-clamp-2">
                                        Masa berlaku <span class="fw-medium text-dark">{{ $mt->nama_majelis }}</span>
                                        akan segera habis.
                                    </p>
                                </div>
                            </a>
                        </li>
                    @endforeach
                </div>

                <li>
                    <a href="#" class="dropdown-item view-all-btn text-center py-3 border-top">
                        Lihat Semua Notifikasi <i class="fas fa-arrow-right ms-1" style="font-size: 0.8rem;"></i>
                    </a>
                </li>
            </ul>

            <style>
                /* Animation & Base */
                .dropdown-toggle::after {
                    display: none;
                }

                @keyframes pulse {
                    0% {
                        box-shadow: 0 0 0 0 rgba(39, 119, 72, 0.7);
                    }

                    70% {
                        box-shadow: 0 0 0 6px rgba(39, 119, 72, 0);
                    }

                    100% {
                        box-shadow: 0 0 0 0 rgba(39, 119, 72, 0);
                    }
                }

                .pulse-ring {
                    position: absolute;
                    top: 2px;
                    right: 0px;
                    width: 8px;
                    height: 8px;
                    border-radius: 50%;
                    background: transparent;
                    animation: pulse 2s infinite;
                    pointer-events: none;
                }

                /* Dropdown Container */
                .notification-menu {
                    width: 360px;
                    max-width: 90vw;
                    border-radius: 16px;
                    overflow: hidden;
                    transform-origin: top right;
                    animation: slideDownFade 0.2s ease-out forwards;
                }

                @keyframes slideDownFade {
                    from {
                        opacity: 0;
                        transform: translateY(10px) scale(0.98);
                    }

                    to {
                        opacity: 1;
                        transform: translateY(0) scale(1);
                    }
                }

                /* Mobile Optimization */
                @media (max-width: 576px) {
                    .notification-menu {
                        width: 94vw !important;
                        position: fixed !important;
                        top: 70px !important;
                        left: 3vw !important;
                        right: 3vw !important;
                        transform: none !important;
                        margin: 0 !important;
                    }
                }

                /* Header */
                .notification-header {
                    background: linear-gradient(135deg, #277748 0%, #174226 100%);
                    padding: 16px 20px;
                }

                /* List Items */
                .notification-list {
                    max-height: 380px;
                    overflow-y: auto;
                    background: #fff;
                    scrollbar-width: none;
                    /* Firefox */
                    -ms-overflow-style: none;
                    /* IE 10+ */
                }

                .notification-list::-webkit-scrollbar {
                    display: none;
                    /* Chrome/Safari */
                }

                .notification-item {
                    padding: 12px 20px;
                    border-left: 3px solid transparent;
                    transition: all 0.2s ease;
                    white-space: normal;
                }

                .notification-item:hover {
                    background-color: #f8f9fa;
                    border-left-color: #277748;
                    transform: translateX(2px);
                }

                .notification-item.unread {
                    background-color: #f0f9f4;
                    /* Light green tint */
                }

                .notification-item.unread:hover {
                    background-color: #e2f5e9;
                }

                /* Icon Styling */
                .icon-box {
                    width: 42px;
                    height: 42px;
                    border-radius: 12px;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    font-size: 1.1rem;
                    flex-shrink: 0;
                }

                /* Typography */
                .fs-xs {
                    font-size: 0.8rem;
                    line-height: 1.4;
                }

                .fs-sm {
                    font-size: 0.9rem;
                }

                .line-clamp-2 {
                    display: -webkit-box;
                    -webkit-line-clamp: 2;
                    -webkit-box-orient: vertical;
                    overflow: hidden;
                }

                .notification-time {
                    font-size: 0.7rem;
                    color: #9ca3af;
                    font-weight: 500;
                }

                /* Footer */
                .view-all-btn {
                    font-weight: 600;
                    color: #277748;
                    font-size: 0.9rem;
                    transition: background 0.2s;
                }

                .view-all-btn:hover {
                    background-color: #f8f9fa;
                    color: #174226;
                }

                .view-all-btn:active {
                    background-color: #e9ecef;
                }
            </style>
        </div>

        <div class="header-user dropdown">
            <div class="user-avatar" data-bs-toggle="dropdown"
                style="cursor: pointer; background-color: #277748; color: white; display: flex; align-items: center; justify-content: center;">
                {{ substr(auth()->user()->name ?? 'GU', 0, 2) }}
            </div>
            <div class="user-info">
                <p class="user-name">{{ auth()->user()->name ?? 'Guest' }}</p>
                <p class="user-role">{{ auth()->user()->roles->pluck('name')[0] ?? 'User' }}</p>
            </div>
            <button class="btn dropdown-toggle" type="button" id="userDropdown" data-bs-toggle="dropdown"
                aria-expanded="false">
                <i class="fas fa-chevron-down"></i>
            </button>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                {{-- <li><a class="dropdown-item" href="#">Profil</a></li> --}}
                {{-- <li><a class="dropdown-item" href="#">Pengaturan</a></li> --}}
                {{-- <li><hr class="dropdown-divider"></li> --}}
                <li>
                    <div class="px-4 py-2">
                        <small class="text-muted d-block text-uppercase fw-semibold"
                            style="font-size: 0.65rem; letter-spacing: 0.5px;">Akses Level</small>
                        <div class="fw-bold text-dark mt-1">
                            @auth
                                <span class="badge bg-primary rounded-pill">
                                    {{ optional(auth()->user()->roles->first())->name ?? 'User' }}
                                </span>
                            @else
                                <span class="badge bg-secondary rounded-pill">Pengunjung</span>
                            @endauth
                        </div>
                    </div>
                </li>
                <li>
                    <hr class="dropdown-divider">
                </li>
                <li>
                    <a class="dropdown-item text-danger py-2" href="{{ route('logout') }}"
                        onclick="event.preventDefault(); document.getElementById('logout-form-header').submit();">
                        <i class="fas fa-sign-out-alt me-2"></i> Keluar Aplikasi
                    </a>
                    <form id="logout-form-header" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </li>
            </ul>
        </div>
        <!-- JS for Realtime Notifications -->
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const notifBadge = document.getElementById('notif-badge');
                const notifPulse = document.getElementById('notif-pulse');
                const notifCountText = document.getElementById('notif-count-text');
                const notifListContainer = document.getElementById('notification-list-container');
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                // 1. Polling Function
                function fetchNotifications() {
                    console.log('Polling notifications...', new Date().toLocaleTimeString());
                    fetch("{{ route('notifications.index') }}?t=" + new Date().getTime())
                        .then(response => response.json())
                        .then(data => {
                            updateNotificationUI(data);
                        })
                        .catch(error => console.error('Error fetching notifications:', error));
                }

                // 2. Poll every 3 seconds
                setInterval(fetchNotifications, 3000);

                // Initial fetch
                // fetchNotifications(); // Optional: Fetch immediately on load (Blade handles first state, but this syncs immediately)

                // 3. Update UI Function
                function updateNotificationUI(data) {
                    // Update Badge
                    if (data.count > 0) {
                        if (notifBadge) {
                            notifBadge.innerText = data.count;
                            notifBadge.style.display = 'inline-block';
                        }
                        if (notifPulse) notifPulse.style.display = 'block';
                    } else {
                        if (notifBadge) notifBadge.style.display = 'none';
                        if (notifPulse) notifPulse.style.display = 'none';
                    }

                    // Update Header Text
                    if (notifCountText) {
                        notifCountText.innerText = `Anda memiliki ${data.count} pesan belum dibaca`;
                    }

                    // Update List (Only if Dropdown is NOT open to prevent jarring UX, OR simple prepend?)
                    // Actually, full replace is simplest for Sync.
                    // But let's check if the list content has changed hash?
                    // For simplicity, we just rebuild HTML if count changed or every time?
                    renderNotificationList(data.notifications, data.count);
                }

                function renderNotificationList(notifications, count) {
                    let html = '';

                    if (count === 0) {
                        html = `
                        <div class="text-center py-4 text-muted">
                            <i class="fas fa-bell-slash fa-2x mb-2 text-secondary opacity-25"></i>
                            <p class="mb-0 small">Belum ada notifikasi baru</p>
                        </div>
                    `;
                    } else {
                        notifications.forEach(n => {
                            // Determine Icon & Color based on Type
                            let icon = 'fa-info-circle';
                            let colorClass = 'text-info bg-info-subtle';
                            let title = 'Info';
                            let text = '';
                            let link = '#';

                            if (n.type === 'marbot_new') {
                                icon = 'fa-user-plus';
                                colorClass = 'text-primary bg-primary-subtle';
                                title = 'Permohonan Marbot';
                                text =
                                    `<span class="fw-medium text-dark">${n.data.nama_lengkap}</span> mendaftar untuk ${n.data.rumah_ibadah}.`;
                                // Use Backend Redirect for robust 'Mark as Read'
                                link = `/notifications/${n.id}/go?url=` + encodeURIComponent(
                                    `/appv2/marbot/${n.data.uuid}`);
                            } else if (n.type === 'mt_expired') {
                                icon = 'fa-calendar-times';
                                colorClass = 'text-danger bg-danger-subtle';
                                title = 'Masa Berlaku Habis';
                                text =
                                    `Surat keterangan <span class="fw-medium text-dark">${n.data.nama_majelis}</span> telah habis masa berlakunya.`;
                                link = '#';
                            } else if (n.type === 'mt_warning') {
                                icon = 'fa-exclamation-triangle';
                                colorClass = 'text-warning bg-warning-subtle';
                                title = 'Peringatan Expired';
                                text =
                                    `Masa berlaku <span class="fw-medium text-dark">${n.data.nama_majelis}</span> akan segera habis.`;
                                link = '#';
                            }

                            // Render Item
                            html += `
                        <li>
                            <a class="dropdown-item notification-item unread d-flex gap-3 align-items-start" 
                               href="${link}">
                                <div class="icon-box ${colorClass}">
                                    <i class="fas ${icon}"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <span class="fw-bold text-dark fs-sm">${title}</span>
                                        <span class="notification-time">${n.time_ago}</span>
                                    </div>
                                    <p class="text-muted mb-0 fs-xs line-clamp-2">${text}</p>
                                </div>
                            </a>
                        </li>
                        `;
                        });
                    }

                    notifListContainer.innerHTML = html;
                }

                // 4. Mark As Read Function (Global scope or attached to window)
                window.markAsRead = function(event, id, link) {
                    event.preventDefault(); // Stop immediate navigation

                    // Optimistic UI Update: Remove from list or visual indication
                    const item = event.currentTarget;
                    item.classList.remove('unread');
                    item.style.backgroundColor = '#fff';

                    // Send Request
                    fetch(`/notifications/${id}/read`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Content-Type': 'application/json'
                        }
                    }).then(() => {
                        // Navigate only after success (or immediately if you prefer speed)
                        if (link && link !== '#') {
                            window.location.href = link;
                        }
                    });
                };
            });
        </script>
    </div>
</div>
