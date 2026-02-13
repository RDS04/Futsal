@extends('layouts.app')

@section('content')
<div class="container-fluid mt-4">

    <div class="card shadow-sm card-outline card-success">
        {{-- HEADER --}}
        <div class="card-header bg-success">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="card-title font-weight-bold mb-0">
                        <i class="fas fa-money-check-alt mr-2"></i> User Sudah Bayar - {{ $regionLabel }}
                    </h3>
                    <p class="text-muted mb-0 text-sm">
                        Daftar user yang telah menyelesaikan proses pembayaran
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
                                <th>Alamat</th>
                                <th width="130" class="text-center">Status Pembayaran</th>
                                <th width="110" class="text-center">Tanggal Pembayaran</th>
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
                                    <small class="text-muted">
                                        @if($user->address)
                                            {{ substr($user->address, 0, 50) }}{{ strlen($user->address) > 50 ? '...' : '' }}
                                        @else
                                            -
                                        @endif
                                    </small>
                                </td>

                                <td class="text-center">
                                    <span class="badge badge-success px-3 py-2" style="color: black;">
                                        <i class="fas fa-check-circle mr-1"></i> Sudah Bayar
                                    </span>
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
                    <strong>Info:</strong> Tidak ada user yang sudah melakukan pembayaran di region ini
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif
        </div>
    </div>

</div>
@endsection
