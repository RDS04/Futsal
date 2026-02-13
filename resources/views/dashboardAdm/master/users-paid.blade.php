@extends('layouts.app')

@include('dashboardAdm.components.styles')

@section('content')
<div class="content">
  <div class="container-fluid px-4">

    {{-- SUCCESS MESSAGE --}}
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show mt-4 animate-in shadow-lg rounded-lg border-2 border-green-400" role="alert">
      <i class="fas fa-check-circle mr-2"></i>
      <strong>Sukses!</strong> {{ session('success') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    {{-- STATS CARDS --}}
    <div class="row mt-6 mb-6">
      <div class="col-md-12">
        <div class="card shadow-xl border-0 rounded-xl overflow-hidden">
          <div class="card-body p-6">
            <div class="row">
              <div class="col-md-12">
                <div class="stat-card bg-gradient-to-br from-green-50 to-emerald-100 p-6 rounded-xl shadow-lg border-l-4 border-green-600">
                  <div class="flex items-center justify-between">
                    <div>
                      <h3 class="text-sm font-semibold text-gray-600 mb-2">Total User Sudah Bayar</h3>
                      <p class="text-5xl font-bold text-green-600">{{ $totalPaid }}</p>
                    </div>
                    <i class="fas fa-money-bill-wave text-green-300 text-6xl opacity-20"></i>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    {{-- PAID USERS TABLE --}}
    <div class="row mb-6">
      <div class="col-md-12">
        <div class="card shadow-xl border-0 rounded-xl overflow-hidden">
          <div class="card-header bg-gradient-to-r from-green-600 to-emerald-600 text-white p-6 border-0">
            <h3 class="card-title mb-0 flex items-center font-bold text-lg">
              <i class="fas fa-list mr-3"></i>Daftar User Pembayaran Berhasil
            </h3>
          </div>
          <div class="card-body p-6">
            <div class="overflow-x-auto">
              <table class="table table-striped table-hover w-full">
                <thead class="bg-gradient-to-r from-green-100 to-emerald-100">
                  <tr>
                    <th class="px-4 py-4 font-bold text-slate-800">No</th>
                    <th class="px-4 py-4 font-bold text-slate-800"><i class="fas fa-user mr-2"></i>Nama</th>
                    <th class="px-4 py-4 font-bold text-slate-800"><i class="fas fa-envelope mr-2"></i>Email</th>
                    <th class="px-4 py-4 font-bold text-slate-800"><i class="fas fa-phone mr-2"></i>Phone</th>
                    <th class="px-4 py-4 font-bold text-slate-800"><i class="fas fa-map-pin mr-2"></i>Region</th>
                    <th class="px-4 py-4 font-bold text-slate-800"><i class="fas fa-calendar mr-2"></i>Join Date</th>
                    <th class="px-4 py-4 font-bold text-slate-800"><i class="fas fa-credit-card mr-2"></i>Status</th>
                  </tr>
                </thead>
                <tbody>
                  @forelse($users as $index => $user)
                  <tr class="hover:bg-green-50 transition-colors duration-200 border-b border-slate-200">
                    <td class="px-4 py-4 font-semibold text-slate-800">{{ $loop->iteration }}</td>
                    <td class="px-4 py-4">
                      <span class="font-semibold text-slate-800">{{ $user->name }}</span>
                    </td>
                    <td class="px-4 py-4">
                      <span class="text-slate-600">{{ $user->email }}</span>
                    </td>
                    <td class="px-4 py-4">
                      <span class="badge bg-info">{{ $user->phone ?? '-' }}</span>
                    </td>
                    <td class="px-4 py-4 text-center">
                      <span class="badge bg-primary">{{ ucfirst($user->region) }}</span>
                    </td>
                    <td class="px-4 py-4">
                      <small class="text-slate-600">{{ $user->created_at?->format('d M Y') ?? '-' }}</small>
                    </td>
                    <td class="px-4 py-4 text-center">
                      <span class="badge bg-success">
                        <i class="fas fa-check-circle mr-1"></i>Sudah Bayar
                      </span>
                    </td>
                  </tr>
                  @empty
                  <tr>
                    <td colspan="7" class="px-4 py-3 text-center text-gray-500">
                      <i class="fas fa-inbox mr-2"></i>Tidak ada user yang sudah bayar
                    </td>
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

@include('dashboardAdm.components.styles')
@endsection
