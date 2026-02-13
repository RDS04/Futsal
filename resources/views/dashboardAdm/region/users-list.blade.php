@extends('layouts.app')

@section('content')
<div class="container-fluid mt-4">

    <div class="card shadow-sm card-outline card-primary">
        {{-- HEADER --}}
        <div class="card-header bg-primary">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="card-title font-weight-bold mb-0">
                        <i class="fas fa-users mr-2"></i> Semua User - {{ $regionLabel }}
                    </h3>
                    <p class="text-muted mb-0 text-sm">
                        Daftar lengkap semua user yang terdaftar di region ini
                    </p>
                </div>

                <span class="badge badge-light px-3 py-2" style="font-size: 16px;">
                    Total: {{ $totalUsers }} User
                </span>
            </div>
        </div>

        <div class="card-body">

            {{-- ALERT --}}
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                </div>
            @endif

            {{-- STATISTICS --}}
            @php
                // Compute payment status dari booking relationship
                $paidCount = 0;
                $pendingCount = 0;
                $failedCount = 0;
                
                foreach($users as $user) {
                    // Get first booking status dari user
                    if($user->bokings && $user->bokings->count() > 0) {
                        $bookingStatus = $user->bokings->first()->status;
                        if($bookingStatus === 'paid') {
                            $paidCount++;
                        } elseif($bookingStatus === 'pending') {
                            $pendingCount++;
                        } elseif($bookingStatus === 'canceled') {
                            $failedCount++;
                        }
                    }
                }
            @endphp

            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="info-box bg-light">
                        <span class="info-box-icon text-primary">
                            <i class="fas fa-users"></i>
                        </span>
                        <div class="info-box-content">
                            <span class="info-box-text text-dark">Total User</span>
                            <span class="info-box-number text-primary">{{ $totalUsers }}</span>
                        </div>
                    </div>
                </div>

            {{-- TABLE --}}
            @if($users->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover table-striped table-bordered align-middle">
                        <thead class="thead-light">
                            <tr>
                                <th width="50">#</th>
                                <th>Nama User</th>
                                <th>Email</th>
                                <th>Telepon</th>
                                <th>Jenis Kelamin</th>
                                <th>Password</th>
                                <th width="130" class="text-center">Status Pembayaran</th>
                                <th width="110" class="text-center">Tanggal Daftar</th>
                                <th width="100" class="text-center">Aksi</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($users as $user)
                            <tr>
                                <td class="text-center font-weight-bold text-muted">
                                    {{ ($users->currentPage() - 1) * $users->perPage() + $loop->iteration }}
                                </td>

                                <td>
                                    <div class="font-weight-semibold">{{ $user->name }}</div>
                                    <small class="text-muted">ID: {{ $user->id }}</small>
                                </td>

                                <td>
                                    <i class="fas fa-envelope text-muted mr-1"></i>
                                    {{ $user->email }}
                                </td>

                                <td>
                                    <i class="fas fa-phone text-muted mr-1"></i>
                                    {{ $user->phone ?? '-' }}
                                </td>

                                <td>
                                    <span class="badge badge-info px-3 py-1" style="color: black;">
                                        {{ $user->gender ?? 'Tidak Ada' }}
                                    </span>
                                </td>

                                <td>
                                    <div class="input-group input-group-sm">
                                        <input type="text" class="form-control form-control-sm" value="{{ $user->password }}" readonly style="letter-spacing: 1px; font-family: monospace;">
                                        <div class="input-group-append">
                                            <button class="btn btn-outline-secondary btn-sm copy-password" type="button" title="Copy Password">
                                                <i class="fas fa-copy"></i>
                                            </button>
                                        </div>
                                    </div>
                                </td>

                                <td class="text-center">
                                    @php
                                        $Status = $user->bokings && $user->bokings->count() > 0 ? $user->bokings->first()->status : 'unknown';
                                    @endphp
                                    @if($bookingStatus === 'paid')
                                        <span class="badge badge-success px-3 py-2" style="color: black;">
                                            <i class="fas fa-check-circle mr-1"></i> Sudah Bayar
                                        </span>
                                    @elseif($bookingStatus === 'pending')
                                        <span class="badge badge-warning px-3 py-2" style="color: black;">
                                            <i class="fas fa-clock mr-1"></i> Pending
                                        </span>
                                    @elseif($bookingStatus === 'canceled')
                                        <span class="badge badge-danger px-3 py-2" style="color: black;">
                                            <i class="fas fa-times-circle mr-1"></i> Dibatalkan
                                        </span>
                                    @elseif($bookingStatus === 'confirmed')
                                        <span class="badge badge-info px-3 py-2" style="color: black;">
                                            <i class="fas fa-check mr-1"></i> Dikonfirmasi
                                        </span>
                                    @else
                                        <span class="badge badge-secondary px-3 py-2" style="color: black;">
                                            <i class="fas fa-question-circle mr-1"></i> Tidak Diketahui
                                        </span>
                                    @endif
                                </td>

                                <td class="text-center">
                                    <small class="text-muted">
                                        <i class="far fa-calendar mr-1"></i>
                                        {{ $user->created_at->format('d M Y') }}
                                    </small>
                                </td>

                                <td class="text-center">
                                    <a href="{{ route('admin.region.user.detail', ['region' => $region, 'userId' => $user->id]) }}" class="btn btn-sm btn-info" title="Lihat Detail">
                                        <i class="fas fa-eye mr-1"></i> Detail
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- PAGINATION --}}
                <div class="d-flex justify-content-center">
                    {{ $users->links('pagination::bootstrap-4') }}
                </div>
            @else
                <div class="alert alert-info alert-dismissible fade show" role="alert">
                    <i class="fas fa-info-circle mr-2"></i>
                    <strong>Info:</strong> Tidak ada user terdaftar di region ini
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif
        </div>
    </div>

</div>

<script>
document.querySelectorAll('.copy-password').forEach(btn => {
    btn.addEventListener('click', function() {
        const passwordField = this.closest('.input-group').querySelector('input');
        
        // Copy to clipboard
        navigator.clipboard.writeText(passwordField.value).then(() => {
            // Show toast notification
            const originalHTML = this.innerHTML;
            this.innerHTML = '<i class="fas fa-check"></i>';
            this.setAttribute('title', 'Copied!');
            
            setTimeout(() => {
                this.innerHTML = originalHTML;
                this.setAttribute('title', 'Copy Password');
            }, 2000);
        }).catch(() => {
            alert('Gagal copy password');
        });
    });
});
</script>

@endsection
