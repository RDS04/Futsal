@extends('layouts.app')

@section('content')
<style>/* GLOBAL CARD IMPROVEMENT */
.card {
    border-radius: 16px !important;
    overflow: hidden;
}

.card-header {
    border-top-left-radius: 16px !important;
    border-top-right-radius: 16px !important;
    font-weight: 600;
}

/* SMALL BOX (STATISTIK) */
.small-box {
    border-radius: 18px !important;
    overflow: hidden;
    box-shadow: 0 10px 25px rgba(0,0,0,0.08);
}

.small-box .inner {
    padding: 20px;
}

.small-box h3 {
    font-size: 32px;
    font-weight: 700;
}

/* TABLE */
.table {
    border-radius: 12px;
    overflow: hidden;
}

.table thead th {
    background-color: #f8f9fa;
}

/* AVATAR */
.img-circle {
    border-radius: 50%;
    border: 4px solid rgba(13,110,253,0.2);
}

/* BADGE */
.badge {
    padding: 6px 10px;
    border-radius: 10px;
    font-size: 12px;
}

/* BUTTON */
.btn {
    border-radius: 12px;
}
</style>
<div class="container-fluid mt-4">

    {{-- USER SUMMARY --}}
    <div class="row">
        <div class="col-md-4">
            <div class="card card-outline card-info">
                <div class="card-body text-center">
                    <img src="https://ui-avatars.com/api/?name={{ $user->name }}&background=0D6EFD&color=fff&size=128"
                         class="img-circle mb-3" alt="User Avatar">

                    <h4 class="font-weight-bold mb-0">{{ $user->name }}</h4>
                    <p class="text-muted">{{ $user->email }}</p>

                    <span class="badge badge-info mb-2">{{ $user->region }}</span>

                    <div class="mt-3 text-left">
                        <p class="mb-1">
                            <strong>Telepon:</strong> {{ $user->phone ?? '-' }}
                        </p>
                        <p class="mb-1">
                            <strong>Gender:</strong>
                            @if($user->gender == 'L')
                                Laki-laki
                            @elseif($user->gender == 'P')
                                Perempuan
                            @else
                                -
                            @endif
                        </p>
                        <p class="mb-0">
                            <strong>Daftar:</strong>
                            {{ $user->created_at->format('d M Y') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        {{-- STATISTIK --}}
        <div class="col-md-8">
            <div class="row">
                <div class="col-md-3">
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3>{{ $bookings->count() }}</h3>
                            <p>Total Booking</p>
                        </div>
                        <div class="icon"><i class="fas fa-calendar-check"></i></div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3>{{ $bookings->where('status','paid')->count() }}</h3>
                            <p>Sudah Bayar</p>
                        </div>
                        <div class="icon"><i class="fas fa-check-circle"></i></div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3>{{ $bookings->where('status','pending')->count() }}</h3>
                            <p>Pending</p>
                        </div>
                        <div class="icon"><i class="fas fa-clock"></i></div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="small-box bg-danger">
                        <div class="inner">
                            <h5 class="font-weight-bold font-size-16">
                                Rp {{ number_format($bookings->sum('total_harga'),0,',','.') }}
                            </h5>
                            <p>Total Revenue</p>
                        </div>
                        <div class="icon"><i class="fas fa-money-bill-wave"></i></div>
                    </div>
                </div>
            </div>

            {{-- STATUS PEMBAYARAN --}}
            <div class="card card-outline card-secondary">
                <div class="card-body">
                    <strong>Status Pembayaran:</strong>
                    @if($user->payment_status == 'paid')
                        <span class="badge badge-success ml-2" style="color: #000;">Sudah Bayar</span>
                    @elseif($user->payment_status == 'pending')
                        <span class="badge badge-warning ml-2" style="color: #000;">Pending</span>
                    @else
                        <span class="badge badge-secondary ml-2" style="color: #000;">-</span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- HISTORY BOOKING --}}
    <div class="card card-outline card-primary mt-3">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-history mr-2"></i>
                History Booking (Sudah Terbayar)
            </h3>
        </div>

        <div class="card-body">
            @if($bookings->count())
            <div class="table-responsive">
                <table class="table table-hover table-bordered">
                    <thead class="thead-light">
                        <tr>
                            <th>#</th>
                            <th>Lapangan</th>
                            <th>Tanggal</th>
                            <th>Jam</th>
                            <th>Durasi</th>
                            <th>Total</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($bookings as $i => $booking)
                        <tr>
                            <td>{{ $i+1 }}</td>
                            <td><strong>{{ $booking->lapangan }}</strong></td>
                            <td>{{ $booking->tanggal->format('d M Y') }}</td>
                            <td>{{ $booking->jam_mulai }} - {{ $booking->jam_selesai }}</td>
                            <td>{{ $booking->durasi }} jam</td>
                            <td>Rp {{ number_format($booking->total_harga,0,',','.') }}</td>
                            <td>
                                <span class="badge badge-{{ 
                                    $booking->status == 'paid' ? 'success' :
                                    ($booking->status == 'pending' ? 'warning' :
                                    ($booking->status == 'confirmed' ? 'info' : 'danger'))
                                }}" style="color: #000;">
                                    {{ ucfirst($booking->status) }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
                <div class="alert alert-info">
                    <i class="fas fa-info-circle mr-2"></i>
                    Belum ada booking yang sudah terbayar.
                </div>
            @endif
        </div>
    </div>

    <a href="{{ url()->previous() }}" class="btn btn-secondary mt-3">
        <i class="fas fa-arrow-left"></i> Kembali
    </a>
</div>
@endsection
