@extends('layouts.app')

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

    {{-- ACTION BUTTONS --}}
    <div class="row mt-6 mb-6">
      <div class="col-md-12">
        <div class="card shadow-xl border-0 rounded-xl overflow-hidden">
          <div class="card-body p-4">
            <div class="d-flex gap-2 flex-wrap">
              <a href="{{ route('admin.register.show') }}" class="btn btn-primary">
                <i class="fas fa-plus mr-2"></i>Tambah Admin Baru
              </a>
              <button class="btn btn-secondary">
                <i class="fas fa-download mr-2"></i>Export
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>

    {{-- ADMIN STATISTICS --}}
    <div class="row mb-6">
      <div class="col-lg-4 col-md-6 mb-4">
        <div class="card stat-card shadow-xl border-0 rounded-xl overflow-hidden">
          <div class="card-body p-0">
            <div class="bg-gradient-to-br from-yellow-400 to-yellow-600 text-white p-6">
              <h3 class="text-3xl font-bold">{{ $totalAdminMaster ?? 0 }}</h3>
              <p class="text-yellow-100 text-sm mt-1">Master Admin</p>
            </div>
          </div>
        </div>
      </div>

      <div class="col-lg-4 col-md-6 mb-4">
        <div class="card stat-card shadow-xl border-0 rounded-xl overflow-hidden">
          <div class="card-body p-0">
            <div class="bg-gradient-to-br from-blue-400 to-blue-600 text-white p-6">
              <h3 class="text-3xl font-bold">{{ $totalAdminRegional ?? 0 }}</h3>
              <p class="text-blue-100 text-sm mt-1">Regional Admin</p>
            </div>
          </div>
        </div>
      </div>

      <div class="col-lg-4 col-md-6 mb-4">
        <div class="card stat-card shadow-xl border-0 rounded-xl overflow-hidden">
          <div class="card-body p-0">
            <div class="bg-gradient-to-br from-green-400 to-green-600 text-white p-6">
              <h3 class="text-3xl font-bold">{{ $totalAdmins ?? 0 }}</h3>
              <p class="text-green-100 text-sm mt-1">Total Admin</p>
            </div>
          </div>
        </div>
      </div>
    </div>

    {{-- ADMIN MANAGEMENT TABLE --}}
    @include('dashboardAdm.components.admin-management')

  </div>
</div>

@include('dashboardAdm.components.styles')
@endsection
