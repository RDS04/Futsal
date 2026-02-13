@extends('layouts.app')

@section('content')

{{-- HEADER --}}
<div class="content mt-4">
    <div class="container-fluid px-4">

        {{-- SUCCESS MESSAGE --}}
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            <strong>Sukses!</strong> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        {{-- FILTER --}}
        <div class="card shadow-sm mb-4 border-0">
            <div class="card-header bg-primary bg-gradient text-white">
                <h5 class="mb-0">
                    <i class="fas fa-filter me-2"></i>Filter Booking
                </h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-2">
                        <label class="form-label fw-semibold">Status</label>
                        <select class="form-select" id="statusFilter">
                            <option value="">Semua Status</option>
                            <option value="pending">Pending</option>
                            <option value="confirmed">Confirmed</option>
                            <option value="cancelled">Cancelled</option>
                        </select>
                    </div>

                    <div class="col-md-2">
                        <label class="form-label fw-semibold">Region</label>
                        <select class="form-select" id="regionFilter">
                            <option value="">Semua Region</option>
                            <option value="bypass">Bypass</option>
                            <option value="ulakkarang">Ulak Karang</option>
                            <option value="batusangkar">Batu Sangkar</option>
                        </select>
                    </div>

                    <div class="col-md-2">
                        <label class="form-label fw-semibold">Dari Tanggal</label>
                        <input type="date" class="form-control" id="dateFrom">
                    </div>

                    <div class="col-md-2">
                        <label class="form-label fw-semibold">Sampai Tanggal</label>
                        <input type="date" class="form-control" id="dateTo">
                    </div>

                    <div class="col-md-2 d-grid">
                        <label class="form-label">&nbsp;</label>
                        <button class="btn btn-primary">
                            <i class="fas fa-search me-1"></i>Filter
                        </button>
                    </div>

                    <div class="col-md-2 d-grid">
                        <label class="form-label">&nbsp;</label>
                        <button class="btn btn-secondary" type="button" id="exportBtn">
                            <i class="fas fa-download me-1"></i>Export Excel
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- TABLE --}}
        <div class="card shadow-sm border-0">
            <div class="card-header bg-success bg-gradient text-white">
                <h5 class="mb-0">
                    <i class="fas fa-list me-2"></i>Daftar Semua Booking
                </h5>
            </div>
            <div class="card-body">

                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle">
                        <thead class="table-success">
                            <tr>
                                <th>ID</th>
                                <th>Nama Customer</th>
                                <th>Lapangan</th>
                                <th>Tanggal</th>
                                <th>Jam</th>
                                <th>Region</th>
                                <th>Harga</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($bookings as $booking)
                            <tr>
                                <td>{{ $booking->id }}</td>
                                <td>{{ $booking->customer->nama ?? $booking->nama ?? 'N/A' }}</td>
                                <td>{{ $booking->lapanganData->namaLapangan ?? 'N/A' }}</td>
                                <td>{{ $booking->tanggal }}</td>
                                <td>{{ $booking->jam_mulai ?? 'N/A' }} - {{ $booking->jam_selesai ?? 'N/A' }}</td>
                                <td>
                                    <span class="badge bg-info">
                                        {{ $booking->region ?? 'N/A' }}
                                    </span>
                                </td>
                                <td class="fw-bold text-success">
                                    Rp {{ number_format($booking->total_harga, 0, ',', '.') ?? 'N/A' }}
                                </td>
                                <td>
                                    @if($booking->status === 'confirmed')
                                        <span class="badge bg-success">Confirmed</span>
                                    @elseif($booking->status === 'pending')
                                        <span class="badge bg-warning text-dark">Pending</span>
                                    @else
                                        <span class="badge bg-danger">Paid</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="9" class="text-center text-muted">
                                    <i class="fas fa-inbox me-1"></i> Tidak ada booking
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- PAGINATION --}}
                <div class="d-flex justify-content-center mt-3">
                    {{ $bookings->links() }}
                </div>

            </div>
        </div>

    </div>
</div>

@endsection

@push('scripts')
<script>
    document.getElementById('exportBtn').addEventListener('click', function() {
        // Get filter values
        const status = document.getElementById('statusFilter').value;
        const region = document.getElementById('regionFilter').value;
        const dateFrom = document.getElementById('dateFrom').value;
        const dateTo = document.getElementById('dateTo').value;
        
        // Build query string
        let query = '?';
        if(status) query += 'status=' + status + '&';
        if(region) query += 'region=' + region + '&';
        if(dateFrom) query += 'date_from=' + dateFrom + '&';
        if(dateTo) query += 'date_to=' + dateTo;
        
        // Redirect to export endpoint
        window.location.href = '{{ route("admin.booking.export") }}' + query;
    });
</script>
@endpush
