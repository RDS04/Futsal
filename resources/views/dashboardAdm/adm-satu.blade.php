@extends('layouts.app')

@section('content')

    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">
                        <i class="fas fa-tachometer-alt mr-2"></i>
                        Regional Dashboard
                    </h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Dashboard</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <div class="content">
        <div class="container-fluid">

            {{-- SUCCESS MESSAGE --}}
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle mr-2"></i>
                    <strong>Sukses!</strong> {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            {{-- STATISTIK CARDS --}}
            <div class="row mb-4">
                {{-- Total Lapangan --}}
                <div class="col-lg-3 col-md-6 col-sm-6 mb-3">
                    <div class="small-box bg-primary">
                        <div class="inner">
                            <h3>{{ $totalUsers }}</h3>
                            <p>Total User</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <a href="{{ route('admin.region.users.list', ['region' => $region]) }}" class="small-box-footer">Lihat Detail <i
                                class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6 mb-3">
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3>{{ $totalLapangan ?? 0 }}</h3>
                            <p>Total Lapangan</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-futbol"></i>
                        </div>
                        <a href="{{ route('lapangan.daftar.Lapangan') }}" class="small-box-footer">Lihat Detail <i
                                class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                {{-- Total Booking --}}
                <div class="col-lg-3 col-md-6 col-sm-6 mb-3">
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3>{{ $totalBoking ?? 0 }}</h3>
                            <p>Total Booking</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-calendar-check"></i>
                        </div>
                        <a href="#" class="small-box-footer">Lihat Detail <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                {{-- Total Events --}}
                <div class="col-lg-3 col-md-6 col-sm-6 mb-3">
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3>{{ $totalEvents ?? 0 }}</h3>
                            <p>Total Event</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                        <a href="#" class="small-box-footer">Lihat Detail <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                {{-- Total Sliders --}}
                <div class="col-lg-3 col-md-6 col-sm-6 mb-3">
                    <div class="small-box bg-danger">
                        <div class="inner">
                            <h3>{{ $totalSliders ?? 0 }}</h3>
                            <p>Total Slider</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-images"></i>
                        </div>
                        <a href="#" class="small-box-footer">Lihat Detail <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
            </div>

            {{-- CHARTS ROW --}}
            <div class="row">
                <!-- Booking Chart -->
                <div class="col-lg-6">
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-chart-bar mr-2"></i>
                                Booking Per Bulan - {{ $regionLabel ?? 'Region' }}
                            </h3>
                        </div>
                        <div class="card-body">
                            <canvas id="bookingChart" height="80"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Revenue Chart -->
                <div class="col-lg-6">
                    <div class="card card-success card-outline">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-chart-line mr-2"></i>
                                Pendapatan Per Bulan - {{ $regionLabel ?? 'Region' }}
                            </h3>
                        </div>
                        <div class="card-body">
                            <canvas id="revenueChart" height="80"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            {{-- RECENT BOOKINGS TABLE --}}
            <div class="row mt-4">
                <div class="col-md-12">
                    <div class="card card-outline card-primary">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-list mr-2"></i>
                                Booking Terbaru
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead class="bg-light">
                                        <tr>
                                            <th style="width: 10px">#</th>
                                            <th>Lapangan</th>
                                            <th>Tanggal</th>
                                            <th>Status</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        @forelse($recentBookings ?? [] as $booking)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $booking->lapanganData->namaLapangan ?? $booking->lapangan ?? 'N/A' }}</td>
                                                <td>{{ $booking->tanggal }}</td>
                                                <td><span
                                                        class="badge badge-{{ $booking->status == 'Selesai' ? 'success' : 'warning' }}" style="color: black;">{{ $booking->status }}</span>
                                                </td>
                                                <td><a href="{{ route('user.region.detail', $booking->customer_id) }}"
                                                        class="btn btn-sm btn-info">Detail</a></td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center">Tidak ada data booking</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <style>
        .content-header {
            padding: 15px;
            position: relative;
            border-bottom: 1px solid #dee2e6;
        }

        .small-box {
            border-radius: 0.25rem;
            box-shadow: 0 0 1px rgba(0, 0, 0, 0.125), 0 1px 3px rgba(0, 0, 0, 0.2);
            margin-bottom: 20px;
            position: relative;
        }

        .small-box>.inner {
            padding: 10px 15px;
        }

        .small-box>.inner>h3 {
            font-size: 2.2rem;
            font-weight: 700;
            margin: 0 0 10px 0;
        }

        .small-box>.inner>p {
            font-size: 0.875rem;
            margin: 0;
        }

        .small-box>.icon {
            position: absolute;
            top: -10px;
            right: 10px;
            z-index: 0;
            font-size: 90px;
            color: rgba(255, 255, 255, 0.1);
        }

        .small-box-footer {
            display: block;
            z-index: 10;
            padding: 10px 15px;
            background: rgba(0, 0, 0, 0.1);
            color: #fff;
            text-align: center;
            text-decoration: none;
            border-radius: 0 0 0.25rem 0.25rem;
        }

        .small-box-footer:hover {
            background: rgba(0, 0, 0, 0.15);
            color: #fff;
            text-decoration: none;
        }

        .bg-info {
            background-color: #17a2b8 !important;
        }

        .bg-success {
            background-color: #28a745 !important;
        }

        .bg-warning {
            background-color: #ffc107 !important;
            color: #333;
        }

        .bg-danger {
            background-color: #dc3545 !important;
        }
    </style>

    {{-- Chart.js Library --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>

    <script>
        // Months array for chart labels
        const monthLabels = [
            'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
            'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
        ];

        // Get data from controller
        let bookingData = {!! $bookingChartData ?? '[]' !!};
        let revenueData = {!! $revenueChartData ?? '[]' !!};

        // Fungsi untuk memastikan data selalu memiliki 12 elemen (satu untuk setiap bulan)
        const ensureFullYearData = (data) => {
            const fullYear = new Array(12).fill(0);
            if (Array.isArray(data) && data.length > 0) {
                data.forEach((value, index) => {
                    if (index < 12) {
                        fullYear[index] = value || 0;
                    }
                });
            }
            return fullYear;
        };

        // Normalisasi data untuk memastikan 12 bulan
        bookingData = ensureFullYearData(bookingData);
        revenueData = ensureFullYearData(revenueData);

        // Booking Chart
        const bookingCtx = document.getElementById('bookingChart').getContext('2d');
        const bookingChart = new Chart(bookingCtx, {
            type: 'bar',
            data: {
                labels: monthLabels,
                datasets: [{
                    label: 'Jumlah Booking',
                    data: bookingData,
                    backgroundColor: 'rgba(54, 162, 235, 0.8)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        display: true,
                        labels: {
                            font: {
                                size: 12
                            }
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return 'Booking: ' + context.parsed.y;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        },
                        title: {
                            display: true,
                            text: 'Jumlah Booking'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Bulan'
                        }
                    }
                }
            }
        });

        // Revenue Chart
        const revenueCtx = document.getElementById('revenueChart').getContext('2d');
        const revenueChart = new Chart(revenueCtx, {
            type: 'line',
            data: {
                labels: monthLabels,
                datasets: [{
                    label: 'Pendapatan (Rp)',
                    data: revenueData,
                    backgroundColor: 'rgba(75, 192, 75, 0.1)',
                    borderColor: 'rgba(75, 192, 75, 1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointRadius: 5,
                    pointBackgroundColor: 'rgba(75, 192, 75, 1)',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointHoverRadius: 7
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        display: true,
                        labels: {
                            font: {
                                size: 12
                            }
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const value = context.parsed.y;
                                return 'Pendapatan: Rp ' + value.toLocaleString('id-ID', {
                                    minimumFractionDigits: 0,
                                    maximumFractionDigits: 0
                                });
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return 'Rp ' + value.toLocaleString('id-ID');
                            }
                        },
                        title: {
                            display: true,
                            text: 'Pendapatan (Rp)'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Bulan'
                        }
                    }
                }
            }
        });
    </script>


@endsection