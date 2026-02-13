@php
    $admin = Auth::guard('admin')->user();
    $isMaster = $admin && $admin->role === 'master';
    $region = $admin ? $admin->region : null;
@endphp

<aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
    <!-- BRAND -->
    <div class="sidebar-brand px-3 py-3">
        <a href="{{ auth('admin')->check() ? route('web.region', ['region' => strtolower($region)]) : '/' }}"
           class="brand-link d-flex align-items-center gap-3">
            <img src="{{ asset('static/img/logo.png') }}"
                 class="rounded-circle shadow"
                 style="width:40px;height:40px">
            <div>
                <span class="brand-text fw-semibold text-white">Futsal Rafhely</span>
                @auth('admin')
                    <small class="d-block text-secondary">
                        {{ $isMaster ? 'Master Admin' : $admin->region_label }}
                    </small>
                @endauth
            </div>
        </a>
    </div>

    <!-- SIDEBAR -->
    <div class="sidebar-wrapper">
        <nav class="mt-2">
            <ul class="nav sidebar-menu flex-column"
                data-lte-toggle="treeview"
                role="menu"
                data-accordion="false">

                <!-- DASHBOARD -->
                <li class="nav-header">MENU UTAMA</li>
                <li class="nav-item">
                    <a href="{{ $isMaster ? route('admin.dashboard') : route('admin.dashboard.region', ['region'=>$region]) }}"
                       class="nav-link">
                        <i class="nav-icon bi bi-speedometer2"></i>
                        <p>{{ $isMaster ? 'Master Dashboard' : 'Dashboard' }}</p>
                    </a>
                </li>

                <!-- DATA LAPANGAN -->
                @if(!$isMaster)
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon bi bi-box-seam-fill"></i>
                        <p>
                            Data Lapangan
                            <i class="nav-arrow bi bi-chevron-right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('inputLapangan.Lapangan') }}" class="nav-link">
                                <i class="nav-icon bi bi-plus-circle-fill"></i>
                                <p>Input Lapangan Baru</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('lapangan.daftar.Lapangan') }}" class="nav-link">
                                <i class="nav-icon bi bi-list-ul"></i>
                                <p>List Lapangan</p>
                            </a>
                        </li>
                    </ul>
                </li>
                @endif

                <!-- INFORMASI -->
                @if(!$isMaster)
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon bi bi-tree-fill"></i>
                        <p>
                            Informasi Lainnya
                            <i class="nav-arrow bi bi-chevron-right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('lapangan.slider') }}" class="nav-link">
                                <i class="nav-icon bi bi-images"></i>
                                <p>Slider</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('lapangan.event') }}" class="nav-link">
                                <i class="nav-icon bi bi-calendar-event"></i>
                                <p>Event</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- MANAJEMEN USER PER REGION -->
                <li class="nav-header">MANAJEMEN USER</li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon bi bi-people-fill"></i>
                        <p>
                            Data User
                            <i class="nav-arrow bi bi-chevron-right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('admin.region.users.list', ['region' => $region]) }}" class="nav-link">
                                <i class="nav-icon bi bi-people"></i>
                                <p>Semua User</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.region.users.paid', ['region' => $region]) }}" class="nav-link">
                                <i class="nav-icon bi bi-cash-coin"></i>
                                <p>User Sudah Bayar</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.region.users.pending', ['region' => $region]) }}" class="nav-link">
                                <i class="nav-icon bi bi-clock-history"></i>
                                <p>User Pending</p>
                            </a>
                        </li>
                    </ul>
                </li>
                @endif

                <!-- MASTER ONLY -->
                @if($isMaster)
                <li class="nav-header">MANAJEMEN MASTER</li>

                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon bi bi-people-fill"></i>
                        <p>
                            Manajemen Admin
                            <i class="nav-arrow bi bi-chevron-right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('admin.register.show') }}" class="nav-link">
                                <i class="nav-icon bi bi-person-plus-fill"></i>
                                <p>Tambah Admin</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.list') }}" class="nav-link">
                                <i class="nav-icon bi bi-people"></i>
                                <p>Daftar Admin</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon bi bi-graph-up"></i>
                        <p>
                            Laporan & Analytics
                            <i class="nav-arrow bi bi-chevron-right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('admin.statistics') }}" class="nav-link">
                                <i class="nav-icon bi bi-bar-chart-fill"></i>
                                <p>Statistik Per Region</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.booking.report') }}" class="nav-link">
                                <i class="nav-icon bi bi-calendar-check-fill"></i>
                                <p>Booking Report</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon bi bi-database-fill"></i>
                        <p>
                            Master Data
                            <i class="nav-arrow bi bi-chevron-right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('admin.customers.list') }}" class="nav-link">
                                <i class="nav-icon bi bi-person-vcard"></i>
                                <p>Customer List</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.users.paid') }}" class="nav-link">
                                <i class="nav-icon bi bi-cash-coin"></i>
                                <p>User Sudah Bayar</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.users.pending') }}" class="nav-link">
                                <i class="nav-icon bi bi-clock-history"></i>
                                <p>User Pending Pembayaran</p>
                            </a>
                        </li>
                    </ul>
                </li>
                @endif

                <!-- LOGOUT -->
                <li class="nav-item">
                    <a href="{{ route('logout') }}"
                       class="nav-link"
                       onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                        <i class="nav-icon bi bi-box-arrow-right"></i>
                        <p>Logout</p>
                    </a>
                </li>

            </ul>

            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
            </form>
        </nav>
    </div>
</aside>
