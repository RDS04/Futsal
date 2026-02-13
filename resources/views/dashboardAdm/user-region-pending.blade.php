@extends('layouts.app')

@section('content')
<div class="container-fluid mt-4">

    <div class="card shadow-sm card-outline card-primary">
        {{-- HEADER --}}
        <div class="card-header d-flex justify-content-between align-items-center">
            <div>
                <h3 class="card-title font-weight-bold">
                    <i class="fas fa-users mr-2"></i> Manajemen User Per Region
                </h3>
                <p class="text-muted mb-0 text-sm">
                    Daftar akun user yang terdaftar di sistem
                </p>
            </div>

            <span class="badge badge-primary px-3 py-2">
                Total: {{ $users->total() }} User
            </span>
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
            <div class="table-responsive">
                <table class="table table-hover table-bordered align-middle">
                    <thead class="thead-light">
                        <tr>
                            <th width="50">#</th>
                            <th>Nama User</th>
                            <th>Email</th>
                            <th>Region</th>
                            <th>Tanggal Daftar</th>
                            <th width="120" class="text-center">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($users as $user)
                        <tr>
                            <td class="text-center font-weight-bold">
                                {{ ($users->currentPage() - 1) * $users->perPage() + $loop->iteration }}
                            </td>

                            <td>
                                <div class="font-weight-semibold">{{ $user->name }}</div>
                                <small class="text-muted">ID: {{ $user->id }}</small>
                            </td>

                            <td>
                                <span class="text-muted">{{ $user->email }}</span>
                            </td>

                            <td>
                                @if($user->region)
                                    <span class="badge badge-info px-3 py-1">
                                        {{ $user->region }}
                                    </span>
                                @else
                                    <span class="badge badge-secondary">Tidak Ada</span>
                                @endif
                            </td>

                            <td>
                                <i class="far fa-clock text-muted mr-1"></i>
                                {{ $user->created_at->format('d M Y, H:i') }}
                            </td>

                            <td class="text-center">
                                <a href="{{ route('user.region.detail', $user->id) }}"
                                   class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-eye"></i> Detail
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">
                                <i class="fas fa-inbox fa-lg mb-2 d-block"></i>
                                Tidak ada data user
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- PAGINATION --}}
            <div class="d-flex justify-content-between align-items-center mt-4">
                <small class="text-muted">
                    Menampilkan {{ $users->count() }} dari {{ $users->total() }} user
                </small>
                {{ $users->links() }}
            </div>

        </div>
    </div>

</div>
@endsection
