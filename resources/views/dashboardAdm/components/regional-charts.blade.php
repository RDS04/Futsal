{{-- Regional Charts Component --}}

<div class="row mb-6 mt-4">
    <div class="col-md-12">
        <h4 class="mb-4 flex items-center">
            <i class="fas fa-chart-bar mr-2 text-primary" style="font-size: 1.3rem;"></i>
            <span class="font-bold text-lg">Grafik Analytics Per Region</span>
        </h4>
    </div>
</div>

{{-- Tabs untuk switching antar region --}}
<div class="row mb-4">
    <div class="col-md-12">
        <div class="card shadow-lg border-0 rounded-lg overflow-hidden">
            <ul class="nav nav-tabs custom-tabs" id="regionTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="bypass-tab" data-bs-toggle="tab" data-bs-target="#bypass-content" type="button" role="tab" aria-controls="bypass-content" aria-selected="true">
                        <i class="fas fa-map-marker-alt mr-2"></i>
                        <span class="font-semibold">Bypass</span>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="ulakkarang-tab" data-bs-toggle="tab" data-bs-target="#ulakkarang-content" type="button" role="tab" aria-controls="ulakkarang-content" aria-selected="false">
                        <i class="fas fa-map-marker-alt mr-2"></i>
                        <span class="font-semibold">Ulak Karang</span>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="batusangkar-tab" data-bs-toggle="tab" data-bs-target="#batusangkar-content" type="button" role="tab" aria-controls="batusangkar-content" aria-selected="false">
                        <i class="fas fa-map-marker-alt mr-2"></i>
                        <span class="font-semibold">Batu Sangkar</span>
                    </button>
                </li>
            </ul>

            <div class="tab-content mt-0" id="regionTabsContent">
                {{-- Bypass Tab --}}
                <div class="tab-pane fade show active" id="bypass-content" role="tabpanel" aria-labelledby="bypass-tab">
                    <div class="row p-4">
                        <div class="col-lg-6 mb-4">
                            <div class="card shadow-xl border-0 rounded-xl overflow-hidden h-100">
                                <div class="card-header bg-gradient-to-r from-blue-600 to-blue-800 text-white p-4 border-0">
                                    <h5 class="card-title mb-0 flex items-center font-bold">
                                        <i class="fas fa-chart-bar mr-2 text-blue-300"></i>
                                        📊 Booking Per Bulan - Bypass
                                    </h5>
                                </div>
                                <div class="card-body p-4" style="min-height: 350px;">
                                    <canvas id="bookingChart_bypass" height="90"></canvas>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 mb-4">
                            <div class="card shadow-xl border-0 rounded-xl overflow-hidden h-100">
                                <div class="card-header bg-gradient-to-r from-green-600 to-emerald-600 text-white p-4 border-0">
                                    <h5 class="card-title mb-0 flex items-center font-bold">
                                        <i class="fas fa-chart-line mr-2 text-green-300"></i>
                                        💰 Pendapatan Per Bulan - Bypass
                                    </h5>
                                </div>
                                <div class="card-body p-4" style="min-height: 350px;">
                                    <canvas id="revenueChart_bypass" height="90"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Ulak Karang Tab --}}
                <div class="tab-pane fade" id="ulakkarang-content" role="tabpanel" aria-labelledby="ulakkarang-tab">
                    <div class="row p-4">
                        <div class="col-lg-6 mb-4">
                            <div class="card shadow-xl border-0 rounded-xl overflow-hidden h-100">
                                <div class="card-header bg-gradient-to-r from-purple-600 to-pink-600 text-white p-4 border-0">
                                    <h5 class="card-title mb-0 flex items-center font-bold">
                                        <i class="fas fa-chart-bar mr-2 text-purple-300"></i>
                                        📊 Booking Per Bulan - Ulak Karang
                                    </h5>
                                </div>
                                <div class="card-body p-4" style="min-height: 350px;">
                                    <canvas id="bookingChart_ulakkarang" height="90"></canvas>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 mb-4">
                            <div class="card shadow-xl border-0 rounded-xl overflow-hidden h-100">
                                <div class="card-header bg-gradient-to-r from-orange-600 to-red-600 text-white p-4 border-0">
                                    <h5 class="card-title mb-0 flex items-center font-bold">
                                        <i class="fas fa-chart-line mr-2 text-orange-300"></i>
                                        💰 Pendapatan Per Bulan - Ulak Karang
                                    </h5>
                                </div>
                                <div class="card-body p-4" style="min-height: 350px;">
                                    <canvas id="revenueChart_ulakkarang" height="90"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Batu Sangkar Tab --}}
                <div class="tab-pane fade" id="batusangkar-content" role="tabpanel" aria-labelledby="batusangkar-tab">
                    <div class="row p-4">
                        <div class="col-lg-6 mb-4">
                            <div class="card shadow-xl border-0 rounded-xl overflow-hidden h-100">
                                <div class="card-header bg-gradient-to-r from-indigo-600 to-blue-600 text-white p-4 border-0">
                                    <h5 class="card-title mb-0 flex items-center font-bold">
                                        <i class="fas fa-chart-bar mr-2 text-indigo-300"></i>
                                        📊 Booking Per Bulan - Batu Sangkar
                                    </h5>
                                </div>
                                <div class="card-body p-4" style="min-height: 350px;">
                                    <canvas id="bookingChart_batusangkar" height="90"></canvas>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 mb-4">
                            <div class="card shadow-xl border-0 rounded-xl overflow-hidden h-100">
                                <div class="card-header bg-gradient-to-r from-teal-600 to-cyan-600 text-white p-4 border-0">
                                    <h5 class="card-title mb-0 flex items-center font-bold">
                                        <i class="fas fa-chart-line mr-2 text-teal-300"></i>
                                        💰 Pendapatan Per Bulan - Batu Sangkar
                                    </h5>
                                </div>
                                <div class="card-body p-4" style="min-height: 350px;">
                                    <canvas id="revenueChart_batusangkar" height="90"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Chart.js Library & Scripts --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
<script>
    // Months array for chart labels
    const monthLabels = [
        'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
    ];

    // Get data from controller
    const bookingChartPerRegion = {!! $bookingChartPerRegion ?? '{}' !!};
    const revenueChartPerRegion = {!! $revenueChartPerRegion ?? '{}' !!};

    // Chart configuration
    const chartColors = {
        booking: {
            background: 'rgba(54, 162, 235, 0.8)',
            border: 'rgba(54, 162, 235, 1)'
        },
        revenue: {
            background: 'rgba(75, 192, 75, 0.1)',
            border: 'rgba(75, 192, 75, 1)',
            point: 'rgba(75, 192, 75, 1)'
        }
    };

    // Function to create booking chart
    function createBookingChart(regionCode, regionLabel) {
        const canvasId = 'bookingChart_' + regionCode;
        const canvas = document.getElementById(canvasId);
        
        if (!canvas || !bookingChartPerRegion[regionCode]) return;

        const ctx = canvas.getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: monthLabels,
                datasets: [{
                    label: 'Jumlah Booking',
                    data: bookingChartPerRegion[regionCode],
                    backgroundColor: 'rgba(59, 130, 246, 0.8)',
                    borderColor: 'rgba(29, 78, 216, 1)',
                    borderWidth: 2,
                    borderRadius: 8,
                    fill: true,
                    tension: 0.4,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        labels: {
                            font: { size: 13, weight: 'bold' },
                            padding: 15,
                            usePointStyle: true,
                            pointStyle: 'circle'
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0,0,0,0.8)',
                        padding: 12,
                        titleFont: { size: 13, weight: 'bold' },
                        bodyFont: { size: 12 },
                        cornerRadius: 6,
                        callbacks: {
                            label: function(context) {
                                return 'Booking: ' + context.parsed.y + ' order';
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { 
                            stepSize: 1,
                            font: { size: 11, weight: '500' }
                        },
                        grid: {
                            color: 'rgba(0,0,0,0.05)',
                            drawBorder: false
                        },
                        title: {
                            display: true,
                            text: 'Jumlah Booking',
                            font: { size: 12, weight: 'bold' }
                        }
                    },
                    x: {
                        grid: {
                            display: false,
                            drawBorder: false
                        },
                        ticks: {
                            font: { size: 11, weight: '500' }
                        },
                        title: {
                            display: true,
                            text: 'Bulan (12 Bulan)',
                            font: { size: 12, weight: 'bold' }
                        }
                    }
                }
            }
        });
    }

    // Function to create revenue chart
    function createRevenueChart(regionCode, regionLabel) {
        const canvasId = 'revenueChart_' + regionCode;
        const canvas = document.getElementById(canvasId);
        
        if (!canvas || !revenueChartPerRegion[regionCode]) return;

        const ctx = canvas.getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: monthLabels,
                datasets: [{
                    label: 'Pendapatan (Rp)',
                    data: revenueChartPerRegion[regionCode],
                    backgroundColor: 'rgba(34, 197, 94, 0.1)',
                    borderColor: 'rgba(22, 163, 74, 1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointRadius: 6,
                    pointBackgroundColor: 'rgba(22, 163, 74, 1)',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 3,
                    pointHoverRadius: 8,
                    pointHoverBackgroundColor: 'rgba(22, 163, 74, 1)',
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        labels: {
                            font: { size: 13, weight: 'bold' },
                            padding: 15,
                            usePointStyle: true,
                            pointStyle: 'circle'
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0,0,0,0.8)',
                        padding: 12,
                        titleFont: { size: 13, weight: 'bold' },
                        bodyFont: { size: 12 },
                        cornerRadius: 6,
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
                            font: { size: 11, weight: '500' },
                            callback: function(value) {
                                return 'Rp ' + (value / 1000000).toFixed(1) + 'M';
                            }
                        },
                        grid: {
                            color: 'rgba(0,0,0,0.05)',
                            drawBorder: false
                        },
                        title: {
                            display: true,
                            text: 'Pendapatan (Rp)',
                            font: { size: 12, weight: 'bold' }
                        }
                    },
                    x: {
                        grid: {
                            display: false,
                            drawBorder: false
                        },
                        ticks: {
                            font: { size: 11, weight: '500' }
                        },
                        title: {
                            display: true,
                            text: 'Bulan (12 Bulan)',
                            font: { size: 12, weight: 'bold' }
                        }
                    }
                }
            }
        });
    }

    // Initialize all charts when document is ready
    document.addEventListener('DOMContentLoaded', function() {
        const regions = ['bypass', 'ulakkarang', 'batusangkar'];
        const regionLabels = {
            'bypass': 'Bypass',
            'ulakkarang': 'Ulak Karang',
            'batusangkar': 'Batu Sangkar'
        };

        regions.forEach(region => {
            createBookingChart(region, regionLabels[region]);
            createRevenueChart(region, regionLabels[region]);
        });
    });
</script>

<style>
    .card {
        border: none;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    }

    .nav-tabs {
        border-bottom: 2px solid #e9ecef;
        gap: 0;
    }

    .nav-tabs .nav-link {
        color: #6c757d;
        border: none;
        border-bottom: 3px solid transparent;
        border-radius: 0;
        padding: 12px 20px;
        font-weight: 600;
        transition: all 0.3s ease;
        background: transparent;
        margin: 0;
    }

    .nav-tabs .nav-link:hover {
        border-bottom-color: #dee2e6;
        color: #495057;
        background: rgba(0, 0, 0, 0.02);
    }

    .nav-tabs .nav-link.active {
        color: #fff;
        border-bottom-color: #fff;
        background-color: transparent;
    }

    /* Bypass Active State */
    .nav-tabs .nav-link.active[data-region="bypass"] {
        background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%);
    }

    /* Ulak Karang Active State */
    .nav-tabs .nav-link.active[data-region="ulakkarang"] {
        background: linear-gradient(135deg, #9333ea 0%, #ec4899 100%);
    }

    /* Batu Sangkar Active State */
    .nav-tabs .nav-link.active[data-region="batusangkar"] {
        background: linear-gradient(135deg, #4f46e5 0%, #0891b2 100%);
    }

    .card-header {
        border-bottom: 2px solid rgba(255, 255, 255, 0.2);
    }

    .card-body {
        padding: 1.5rem;
        position: relative;
    }

    canvas {
        max-width: 100%;
        height: auto !important;
    }

    .tab-pane {
        animation: fadeIn 0.3s ease-in;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(5px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* Chart Label Styling */
    .chart-label {
        font-size: 13px;
        font-weight: 600;
        margin-bottom: 10px;
        color: #212529;
    }
</style>
