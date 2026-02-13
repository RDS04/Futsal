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
              <div class="col-md-4">
                <div class="stat-card bg-gradient-to-br from-purple-50 to-purple-100 p-6 rounded-xl shadow-lg border-l-4 border-purple-600">
                  <div class="flex items-center justify-between">
                    <div>
                      <h3 class="text-sm font-semibold text-gray-600 mb-2">Total Customer</h3>
                      <p class="text-4xl font-bold text-purple-600">{{ $totalCustomers }}</p>
                    </div>
                    <i class="fas fa-users text-purple-300 text-5xl opacity-20"></i>
                  </div>
                </div>
              </div>
              @foreach($customersByRegion as $region => $regionCustomers)
              <div class="col-md-4">
                <div class="stat-card bg-gradient-to-br from-blue-50 to-blue-100 p-6 rounded-xl shadow-lg border-l-4 border-blue-600">
                  <div class="flex items-center justify-between">
                    <div>
                      <h3 class="text-sm font-semibold text-gray-600 mb-2">{{ ucfirst($region) }}</h3>
                      <p class="text-4xl font-bold text-blue-600">{{ $regionCustomers->count() }}</p>
                    </div>
                    <i class="fas fa-map-pin text-blue-300 text-5xl opacity-20"></i>
                  </div>
                </div>
              </div>
              @endforeach
            </div>
          </div>
        </div>
      </div>
    </div>

    {{-- CUSTOMER TABLE --}}
    <div class="row mb-6">
      <div class="col-md-12">
        <div class="card shadow-xl border-0 rounded-xl overflow-hidden">
          <div class="card-header bg-gradient-to-r from-purple-600 to-pink-600 text-white p-6 border-0">
            <h3 class="card-title mb-0 flex items-center font-bold text-lg">
              <i class="fas fa-list mr-3"></i>Daftar Customer
            </h3>
          </div>
          <div class="card-body p-6">
            <div class="overflow-x-auto">
              <table class="table table-striped table-hover w-full">
                <thead class="bg-gradient-to-r from-purple-100 to-pink-100">
                  <tr>
                    <th class="px-4 py-4 font-bold text-slate-800">No</th>
                    <th class="px-4 py-4 font-bold text-slate-800"><i class="fas fa-user mr-2"></i>Nama</th>
                    <th class="px-4 py-4 font-bold text-slate-800"><i class="fas fa-envelope mr-2"></i>Email</th>
                    <th class="px-4 py-4 font-bold text-slate-800"><i class="fas fa-phone mr-2"></i>Phone</th>
                    <th class="px-4 py-4 font-bold text-slate-800"><i class="fas fa-map-pin mr-2"></i>Region</th>
                    <th class="px-4 py-4 font-bold text-slate-800"><i class="fas fa-venus-mars mr-2"></i>Gender</th>
                    <th class="px-4 py-4 font-bold text-slate-800"><i class="fas fa-calendar mr-2"></i>Join Date</th>
                  </tr>
                </thead>
                <tbody>
                  @forelse($customers as $index => $customer)
                  <tr class="hover:bg-purple-50 transition-colors duration-200 border-b border-slate-200">
                    <td class="px-4 py-4 font-semibold text-slate-800">{{ $loop->iteration }}</td>
                    <td class="px-4 py-4">
                      <span class="font-semibold text-slate-800">{{ $customer->name }}</span>
                    </td>
                    <td class="px-4 py-4">
                      <span class="text-slate-600">{{ $customer->email }}</span>
                    </td>
                    <td class="px-4 py-4">
                      <span class="badge bg-info">{{ $customer->phone ?? '-' }}</span>
                    </td>
                    <td class="px-4 py-4 text-center">
                      <span class="badge bg-primary">{{ ucfirst($customer->region) }}</span>
                    </td>
                    <td class="px-4 py-4 text-center">
                      <span class="badge {{ $customer->gender === 'male' ? 'bg-success' : 'bg-warning' }}">
                        <i class="fas {{ $customer->gender === 'male' ? 'fa-mars' : 'fa-venus' }} mr-1"></i>
                        {{ ucfirst($customer->gender) }}
                      </span>
                    </td>
                    <td class="px-4 py-4">
                      <small class="text-slate-600">{{ $customer->created_at?->format('d M Y') ?? '-' }}</small>
                    </td>
                  </tr>
                  @empty
                  <tr>
                    <td colspan="7" class="px-4 py-3 text-center text-gray-500">
                      <i class="fas fa-inbox mr-2"></i>Tidak ada customer
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
