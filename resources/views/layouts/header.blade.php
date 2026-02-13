@php
    $admin = Auth::guard('admin')->user();
    $isMaster = $admin && $admin->role === 'master';
    $region = $admin ? $admin->region : null;
    
    // Get booking notifications
    $bookingCount = 0;
    $recentBookings = [];
    if ($isMaster) {
        $bookingCount = \App\Models\Boking::where('status', 'pending')->count();
        $recentBookings = \App\Models\Boking::where('status', 'pending')
            ->orderByDesc('created_at')
            ->take(3)
            ->get();
    } else if ($admin && $region) {
        $bookingCount = \App\Models\Boking::where('region', $region)
            ->where('status', 'pending')
            ->count();
        $recentBookings = \App\Models\Boking::where('region', $region)
            ->where('status', 'pending')
            ->orderByDesc('created_at')
            ->take(3)
            ->get();
    }

    // Get user registration notifications
    $registrationCount = 0;
    $recentRegistrations = [];
    if ($isMaster) {
        $registrationCount = \App\Models\Costumers::orderByDesc('created_at')
            ->whereDate('created_at', '>=', now()->subDays(7))
            ->count();
        $recentRegistrations = \App\Models\Costumers::orderByDesc('created_at')
            ->whereDate('created_at', '>=', now()->subDays(7))
            ->take(3)
            ->get();
    } else if ($admin && $region) {
        // Get users dengan booking di region admin dalam 7 hari terakhir
        $registrationCount = \App\Models\Costumers::distinct()
            ->join('bokings', 'costumers.id', '=', 'bokings.customer_id')
            ->where('bokings.region', $region)
            ->whereDate('costumers.created_at', '>=', now()->subDays(7))
            ->count();
        $recentRegistrations = \App\Models\Costumers::distinct()
            ->join('bokings', 'costumers.id', '=', 'bokings.customer_id')
            ->where('bokings.region', $region)
            ->whereDate('costumers.created_at', '>=', now()->subDays(7))
            ->select('costumers.*')
            ->orderByDesc('costumers.created_at')
            ->take(3)
            ->get();
    }
@endphp
<style>
    .app-header {
        background-color: #fff;
        border-bottom: 1px solid #dee2e6;
        padding: 0.5rem 0;
        position: sticky;
        top: 0;
        z-index: 100;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    }

    .app-header .navbar-nav .nav-link {
        padding: 0.5rem 0.75rem;
        color: #495057;
        transition: all 0.3s ease;
        border-radius: 4px;
    }

    .app-header .navbar-nav .nav-link:hover {
        background-color: #f8f9fa;
        color: #0d6efd;
    }

    .hamburger-toggle {
        font-size: 1.5rem;
        padding: 0.25rem 0.5rem !important;
        cursor: pointer;
    }

    .hamburger-toggle:hover {
        color: #0d6efd !important;
    }

    .user-image {
        width: 32px;
        height: 32px;
        border: 2px solid #0d6efd;
    }

    /* Notification Dropdown Styles */
    .notification-dropdown {
        width: 380px !important;
        max-height: 500px;
        overflow-y: auto;
        padding: 0;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    .notification-dropdown .dropdown-header {
        padding: 0.75rem 1rem;
        background-color: #f8f9fa;
        border-bottom: 1px solid #dee2e6;
        display: flex;
        align-items: center;
        justify-content: space-between;
        font-weight: 600;
        color: #333;
    }

    .notification-item {
        display: flex;
        align-items: center;
        padding: 0.75rem 1rem;
        color: inherit;
        text-decoration: none;
        border-bottom: 1px solid #f0f0f0;
        transition: all 0.2s ease;
        gap: 0.75rem;
    }

    .notification-item:hover {
        background-color: #f8f9fa;
        text-decoration: none;
    }

    .notification-item:last-of-type {
        border-bottom: none;
    }

    .notification-avatar {
        flex-shrink: 0;
    }

    .notification-avatar img {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        object-fit: cover;
    }

    .notification-icon {
        flex-shrink: 0;
        font-size: 1.5rem;
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: #f0f7ff;
        border-radius: 50%;
    }

    .notification-content {
        flex-grow: 1;
        min-width: 0;
    }

    .notification-title {
        font-weight: 600;
        color: #333;
        font-size: 0.9rem;
        margin-bottom: 0.25rem;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .notification-email {
        font-size: 0.8rem;
        color: #666;
        margin-bottom: 0.25rem;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .notification-details {
        font-size: 0.8rem;
        color: #999;
        margin-bottom: 0.25rem;
        display: flex;
        gap: 0.5rem;
    }

    .notification-details span {
        display: flex;
        align-items: center;
        gap: 0.25rem;
    }

    .notification-time {
        font-size: 0.75rem;
        color: #999;
        display: flex;
        align-items: center;
        gap: 0.25rem;
    }

    .notification-empty {
        padding: 2rem 1rem;
        text-align: center;
        color: #999;
    }

    .notification-empty i {
        font-size: 2rem;
        color: #28a745;
        margin-bottom: 0.5rem;
        display: block;
    }

    .notification-empty p {
        margin: 0;
        font-size: 0.9rem;
    }

    .dropdown-footer {
        padding: 0.5rem 1rem !important;
        background-color: #f8f9fa;
        color: #0d6efd;
        font-size: 0.85rem;
    }

    .dropdown-footer:hover {
        background-color: #e9ecef;
        text-decoration: none;
    }

    /* User Header Styles */
    .user-header {
        padding: 1rem;
        text-align: center;
    }

    .user-header img {
        width: 90px;
        height: 90px;
        margin-bottom: 0.5rem;
    }

    .user-header p {
        margin: 0;
        color: inherit;
    }

    .user-header small {
        display: block;
        opacity: 0.8;
        font-size: 0.8rem;
        margin-top: 0.25rem;
    }

    .user-body {
        padding: 0;
    }

    .user-body .row {
        margin: 0;
    }

    .user-body .col-12 {
        padding: 0.5rem 1rem;
    }

    .user-footer {
        padding: 0.75rem;
        background-color: #f8f9fa;
        display: flex;
        gap: 0.5rem;
    }

    .user-footer .btn {
        flex: 1;
        padding: 0.4rem 0.6rem;
        font-size: 0.85rem;
    }

    /* Badge Positioning */
    .badge.rounded-pill {
        font-size: 0.65rem;
        padding: 0.35rem 0.55rem;
    }

    /* Mobile Responsive */
    @media (max-width: 768px) {
        .notification-dropdown {
            width: 320px !important;
        }

        .notification-item {
            padding: 0.65rem 0.75rem;
            gap: 0.5rem;
        }

        .notification-avatar img,
        .notification-icon {
            width: 35px;
            height: 35px;
        }

        .notification-title {
            font-size: 0.85rem;
        }

        .notification-email,
        .notification-details,
        .notification-time {
            font-size: 0.75rem;
        }
    }

    @media (max-width: 576px) {
        .notification-dropdown {
            width: 280px !important;
        }

        .notification-dropdown .dropdown-header {
            padding: 0.6rem 0.75rem;
            font-size: 0.9rem;
        }

        .notification-item {
            padding: 0.6rem 0.75rem;
        }

        .notification-avatar img,
        .notification-icon {
            width: 32px;
            height: 32px;
        }

        .notification-title {
            font-size: 0.8rem;
        }

        .badge.rounded-pill {
            font-size: 0.6rem;
            padding: 0.3rem 0.5rem;
        }
    }
</style>
<nav class="app-header navbar navbar-expand bg-body">
    <div class="container-fluid">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link hamburger-toggle" data-lte-toggle="sidebar" href="#" role="button" title="Toggle Sidebar">
                    <i class="bi bi-list"></i>
                </a>
            </li>
            <li class="nav-item d-none d-md-block">
                <a href="#" class="nav-link">Home</a>
            </li>
            <li class="nav-item d-none d-md-block">
                <a href="#" class="nav-link">Contact</a>
            </li>
        </ul>

        <ul class="navbar-nav ms-auto">
            <li class="nav-item">
                <a class="nav-link" data-widget="navbar-search" href="#" role="button" title="Search">
                    <i class="bi bi-search"></i>
                </a>
            </li>

            <!-- Registration Notifications -->
            @if($admin)
            <li class="nav-item dropdown d-none d-md-block">
                <a class="nav-link position-relative" data-bs-toggle="dropdown" href="#" title="Registration Notifications">
                    <i class="bi bi-person-plus"></i>
                    @if($registrationCount > 0)
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-success">
                        {{ $registrationCount }}
                    </span>
                    @endif
                </a>

                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end notification-dropdown">
                    <div class="dropdown-header">
                        <i class="bi bi-person-plus me-2"></i>
                        <strong>Pendaftaran Baru</strong>
                        @if($registrationCount > 0)
                        <span class="badge bg-success ms-2">{{ $registrationCount }}</span>
                        @endif
                    </div>
                    <div class="dropdown-divider m-0"></div>

                    @forelse($recentRegistrations as $user)
                    <a href="#" class="notification-item">
                        <div class="notification-avatar">
                            <img src="{{ asset('static/img/logo.png') }}" alt="Avatar" class="rounded-circle" />
                        </div>
                        <div class="notification-content">
                            <div class="notification-title">{{ $user->nama ?? $user->name }}</div>
                            <div class="notification-email">{{ $user->email }}</div>
                            <div class="notification-time">
                                <i class="bi bi-clock-fill"></i> {{ $user->created_at->diffForHumans() }}
                            </div>
                        </div>
                    </a>
                    @empty
                    <div class="notification-empty">
                        <i class="bi bi-check-circle"></i>
                        <p>Tidak ada pendaftaran baru</p>
                    </div>
                    @endforelse

                    @if($registrationCount > 0)
                    <div class="dropdown-divider m-0"></div>
                    <a href="#" class="dropdown-item dropdown-footer text-center">
                        <i class="bi bi-arrow-right me-1"></i>Lihat Semua
                    </a>
                    @endif
                </div>
            </li>
            @endif

            <!-- Booking Notifications -->
            @if($admin)
            <li class="nav-item dropdown d-none d-lg-block">
                <a class="nav-link position-relative" data-bs-toggle="dropdown" href="#" title="Booking Notifications">
                    <i class="bi bi-calendar-check"></i>
                    @if($bookingCount > 0)
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                        {{ $bookingCount }}
                    </span>
                    @endif
                </a>

                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end notification-dropdown">
                    <div class="dropdown-header">
                        <i class="bi bi-calendar-check me-2"></i>
                        <strong>Booking Pending</strong>
                        @if($bookingCount > 0)
                        <span class="badge bg-danger ms-2">{{ $bookingCount }}</span>
                        @endif
                    </div>
                    <div class="dropdown-divider m-0"></div>

                    @forelse($recentBookings as $booking)
                    <a href="#" class="notification-item">
                        <div class="notification-icon">
                            <i class="bi bi-futbol text-primary"></i>
                        </div>
                        <div class="notification-content">
                            <div class="notification-title">{{ $booking->lapanganData->namaLapangan ?? 'Lapangan' }}</div>
                            <div class="notification-details">
                                <span><i class="bi bi-calendar-event"></i> {{ $booking->tanggal }}</span>
                                <span><i class="bi bi-clock"></i> {{ $booking->jam_mulai }}</span>
                            </div>
                            <div class="notification-time">
                                <i class="bi bi-clock-fill"></i> {{ $booking->created_at->diffForHumans() }}
                            </div>
                        </div>
                        <span class="badge bg-warning text-dark ms-auto">Pending</span>
                    </a>
                    @empty
                    <div class="notification-empty">
                        <i class="bi bi-check-circle"></i>
                        <p>Tidak ada booking pending</p>
                    </div>
                    @endforelse

                    @if($bookingCount > 0)
                    <div class="dropdown-divider m-0"></div>
                    <a href="{{ route('admin.booking.report') }}" class="dropdown-item dropdown-footer text-center">
                        <i class="bi bi-arrow-right me-1"></i>Lihat Semua
                    </a>
                    @endif
                </div>
            </li>
            @endif

            <!-- Fullscreen Toggle -->
            <li class="nav-item d-none d-sm-block">
                <a class="nav-link" href="#" data-lte-toggle="fullscreen" title="Toggle Fullscreen">
                    <i data-lte-icon="maximize" class="bi bi-arrows-fullscreen"></i>
                    <i data-lte-icon="minimize" class="bi bi-fullscreen-exit" style="display: none"></i>
                </a>
            </li>

            <!-- User Menu -->
            <li class="nav-item dropdown user-menu">
                <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown" title="User Menu">
                    <img src="{{ asset('static/img/logo.png') }}" class="user-image rounded-circle shadow" alt="User Image" />
                    @if($admin)
                        <span class="d-none d-md-inline ms-2">
                            {{ $admin->name ?? 'Admin' }}
                        </span>
                    @endif
                </a>

                <ul class="dropdown-menu dropdown-menu-end" style="width: 300px; border-radius: 10px; box-shadow: 0 4px 12px rgba(0,0,0,0.15);">
                    <li style="padding: 1rem; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 10px 10px 0 0; color: white;">
                        <div style="display: flex; align-items: center; gap: 1rem;">
                            <img src="{{ asset('static/img/logo.png') }}" style="width: 50px; height: 50px; border-radius: 50%; border: 3px solid white;" alt="User Image" />
                            <div>
                                <p style="margin: 0; font-weight: 600;">{{ $admin->name ?? 'Admin' }}</p>
                                <small style="opacity: 0.9;">
                                    @if($isMaster)
                                        Master Admin
                                    @else
                                        Regional Admin
                                    @endif
                                </small>
                            </div>
                        </div>
                    </li>

                    @if($admin)
                    @endif

                    <li style="padding: 0.5rem;">
                        <a href="{{ route('logout') }}" class="dropdown-item" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" style="padding: 0.6rem 1rem; border-radius: 6px; transition: all 0.2s;">
                            <i class="bi bi-box-arrow-right me-2" style="color: #dc3545;"></i>Sign out
                        </a>
                    </li>
                </ul>
                
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </li>
        </ul>
    </div>
</nav>

