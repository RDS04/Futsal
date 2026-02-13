{{-- Regional Comparison Charts Component --}}

<div class="row mb-6 mt-4">
    <div class="col-md-12">
        <h4 class="mb-4">
            <i class="fas fa-chart-area mr-2"></i>
            Perbandingan Booking & Pendapatan Antar Region
        </h4>
    </div>
</div>

<div class="row mb-6">
    {{-- Combined Booking Comparison --}}
    <div class="col-lg-12 mb-4">
        <div class="card card-info card-outline shadow-sm">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-chart-bar mr-2"></i>
                    Perbandingan Total Booking Per Region (Tahun Ini)
                </h5>
            </div>
            <div class="card-body">
                <canvas id="comparisonBookingChart" height="80"></canvas>
            </div>
        </div>
    </div>

    {{-- Combined Revenue Comparison --}}
    <div class="col-lg-12 mb-4">
        <div class="card card-warning card-outline shadow-sm">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-chart-line mr-2"></i>
                    Perbandingan Total Pendapatan Per Region (Tahun Ini)
                </h5>
            </div>
            <div class="card-body">
                <canvas id="comparisonRevenueChart" height="80"></canvas>
            </div>
        </div>
    </div>
</div>

<script>
    // Get data from controller
    const bookingChartPerRegion = {!! $bookingChartPerRegion ?? '{}' !!};
    const revenueChartPerRegion = {!! $revenueChartPerRegion ?? '{}' !!};

    // Region labels
    const regionLabels = {
        'bypass': 'Bypass',
        'ulakkarang': 'Ulak Karang',
        'batusangkar': 'Batu Sangkar'
    };

    const regionCodes = ['bypass', 'ulakkarang', 'batusangkar'];

    // Chart colors for regions
    const regionColors = [
        {
            background: 'rgba(54, 162, 235, 0.7)',
            border: 'rgba(54, 162, 235, 1)'
        },
        {
            background: 'rgba(75, 192, 75, 0.7)',
            border: 'rgba(75, 192, 75, 1)'
        },
        {
            background: 'rgba(255, 159, 64, 0.7)',
            border: 'rgba(255, 159, 64, 1)'
        }
    ];

    // Function to sum array values
    function sumArray(arr) {
        return arr.reduce((a, b) => a + b, 0);
    }

    // Initialize comparison charts when document is ready
    document.addEventListener('DOMContentLoaded', function() {
        // Calculate total bookings per region
        const totalBookings = regionCodes.map(region => {
            return bookingChartPerRegion[region] ? sumArray(bookingChartPerRegion[region]) : 0;
        });

        // Calculate total revenue per region
        const totalRevenue = regionCodes.map(region => {
            return revenueChartPerRegion[region] ? sumArray(revenueChartPerRegion[region]) : 0;
        });

        // Booking Comparison Chart
        const comparisonBookingCanvas = document.getElementById('comparisonBookingChart');
        if (comparisonBookingCanvas) {
            const ctx = comparisonBookingCanvas.getContext('2d');
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: regionCodes.map(r => regionLabels[r]),
                    datasets: [{
                        label: 'Total Booking',
                        data: totalBookings,
                        backgroundColor: regionColors.map(c => c.background),
                        borderColor: regionColors.map(c => c.border),
                        borderWidth: 2,
                    }]
                },
                options: {
                    indexAxis: 'x',
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: {
                        legend: {
                            display: true,
                            labels: {
                                font: { size: 12 }
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return 'Total Booking: ' + context.parsed.y;
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: { stepSize: 1 },
                            title: {
                                display: true,
                                text: 'Jumlah Booking'
                            }
                        }
                    }
                }
            });
        }

        // Revenue Comparison Chart
        const comparisonRevenueCanvas = document.getElementById('comparisonRevenueChart');
        if (comparisonRevenueCanvas) {
            const ctx = comparisonRevenueCanvas.getContext('2d');
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: regionCodes.map(r => regionLabels[r]),
                    datasets: [{
                        label: 'Total Pendapatan (Rp)',
                        data: totalRevenue,
                        backgroundColor: regionColors.map(c => c.background),
                        borderColor: regionColors.map(c => c.border),
                        borderWidth: 2,
                    }]
                },
                options: {
                    indexAxis: 'x',
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: {
                        legend: {
                            display: true,
                            labels: {
                                font: { size: 12 }
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
                                text: 'Total Pendapatan (Rp)'
                            }
                        }
                    }
                }
            });
        }
    });
</script>
