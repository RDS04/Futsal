@extends('layouts.app')

@section('content')
  <div class="content">
    <div class="container-fluid px-4">

      {{-- SUCCESS MESSAGE --}}
      @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mt-4 shadow-sm" role="alert">
          <i class="fas fa-check-circle me-2"></i>
          <strong>Sukses!</strong> {{ session('success') }}
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      @endif

      {{-- STATS CARDS --}}
      <div class="row mt-4 mb-4">
        <div class="col-md-3">
          <div class="card border-0 shadow-sm h-100"
            style="border-left: 4px solid #3498db; background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);">
            <div class="card-body p-4">
              <div class="d-flex justify-content-between align-items-center">
                <div>
                  <p class="text-white text-sm mb-2"><i class="fas fa-users me-2"></i>Total Customer</p>
                  <h3 class="mb-0 text-white" style="font-weight: 700;">{{ $totalCustomers }}</h3>
                </div>
                <div style="font-size: 2.5rem; color: rgba(255, 255, 255, 0.3);">
                  <i class="fas fa-users"></i>
                </div>
              </div>
            </div>
          </div>
        </div>

        @php
          $colors = ['#e74c3c', '#9b59b6', '#1abc9c'];
          $colorIndex = 0;
        @endphp
      </div>

      {{-- CUSTOMER TABLE --}}
      <div class="row mb-4">
        <div class="col-md-12">
          <div class="card border-0 shadow-sm">
            <div class="card-header p-4"
              style="background: linear-gradient(135deg, #3498db 0%, #2980b9 100%); border: none;">
              <h5 class="mb-0 text-white" style="font-weight: 600;">
                <i class="fas fa-list me-2"></i>Daftar Customer
              </h5>
            </div>
            <div class="card-body p-0">
              <div class="table-responsive">
                <table class="table table-hover mb-0">
                  <thead
                    style="background: linear-gradient(135deg, #ecf0f1 0%, #f5f7fa 100%); border-top: 1px solid #e8eef2; border-bottom: 2px solid #3498db;">
                    <tr>
                      <th class="px-4 py-3 font-weight-600" style="color: #3498db;">No</th>
                      <th class="px-4 py-3 font-weight-600" style="color: #2c3e50;"><i class="fas fa-user me-2"></i>Nama
                      </th>
                      <th class="px-4 py-3 font-weight-600" style="color: #2c3e50;"><i
                          class="fas fa-envelope me-2"></i>Email</th>
                      <th class="px-4 py-3 font-weight-600" style="color: #2c3e50;"><i
                          class="fas fa-phone me-2"></i>Telepon</th>
                      <th class="px-4 py-3 font-weight-600 text-center" style="color: #2c3e50;">Gender</th>
                      <th class="px-4 py-3 font-weight-600" style="color: #2c3e50;"><i
                          class="fas fa-calendar me-2"></i>Tanggal Bergabung</th>
                    </tr>
                  </thead>
                  <tbody>
                    @forelse($customers as $index => $customer)
                      <tr style="border-bottom: 1px solid #e8eef2; transition: background-color 0.2s;">
                        <td class="px-4 py-3" style="color: #3498db; font-weight: 600;">{{ $loop->iteration }}</td>
                        <td class="px-4 py-3" style="color: #2c3e50; font-weight: 500;">{{ $customer->name }}</td>
                        <td class="px-4 py-3" style="color: #7f8c8d;">{{ $customer->email }}</td>
                        <td class="px-4 py-3">
                          <span class="badge"
                            style="background-color: #ecf0f1; color: #2c3e50;">{{ $customer->phone ?? '-' }}</span>
                        </td>
                        <td class="px-4 py-3 text-center">
                          <span class="badge {{ $customer->gender === 'male' ? 'bg-primary' : 'bg-danger' }} text-white">
                            <i class="fas {{ $customer->gender === 'male' ? 'fa-mars' : 'fa-venus' }} me-1"></i>
                            {{ ucfirst($customer->gender) }}
                          </span>
                        </td>
                        <td class="px-4 py-3" style="color: #7f8c8d; font-size: 0.9rem;">
                          {{ $customer->created_at?->format('d M Y') ?? '-' }}</td>
                      </tr>
                    @empty
                      <tr>
                        <td colspan="6" class="px-4 py-4 text-center text-muted">
                          <i class="fas fa-inbox me-2"></i>Tidak ada customer
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

@endsection