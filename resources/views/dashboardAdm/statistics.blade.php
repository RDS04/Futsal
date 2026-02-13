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

    {{-- STATISTICS FILTERS --}}
    <div class="row mt-6 mb-6">
      <div class="col-md-12">
        <div class="card shadow-xl border-0 rounded-xl overflow-hidden">
          <div class="card-header bg-gradient-to-r from-blue-600 to-blue-800 text-white p-4 border-0">
            <h3 class="card-title mb-0 flex items-center font-bold">
              <i class="fas fa-filter mr-2"></i>Filter Data
            </h3>
          </div>
          <div class="card-body p-4">
            <div class="row g-3">
              <div class="col-md-3">
                <label class="form-label fw-semibold">Pilih Region</label>
                <select class="form-select" id="regionFilter">
                  <option value="">Semua Region</option>
                  <option value="bypass">Bypass</option>
                  <option value="ulakkarang">Ulak Karang</option>
                  <option value="batusangkar">Batu Sangkar</option>
                </select>
              </div>
              <div class="col-md-3">
                <label class="form-label fw-semibold">Bulan</label>
                <input type="month" class="form-control" id="monthFilter">
              </div>
              <div class="col-md-3">
                <label class="form-label fw-semibold">&nbsp;</label>
                <button class="btn btn-primary w-100">
                  <i class="fas fa-search mr-2"></i>Filter
                </button>
              </div>
              <div class="col-md-3">
                <label class="form-label fw-semibold">&nbsp;</label>
                <button class="btn btn-secondary w-100">
                  <i class="fas fa-download mr-2"></i>Export
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    {{-- REGION STATISTICS TABLE --}}
    @include('dashboardAdm.components.region-comparison')

  </div>
</div>

@include('dashboardAdm.components.styles')
@endsection
