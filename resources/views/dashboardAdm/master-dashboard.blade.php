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

    {{-- GLOBAL STATISTICS --}}
    @include('dashboardAdm.components.statistics')

    {{-- REGION COMPARISON --}}

    {{-- REGIONAL CHARTS --}}
    @include('dashboardAdm.components.regional-charts')

    {{-- RECENT BOOKINGS & TOP LAPANGAN --}}
    @include('dashboardAdm.components.recent-bookings')
    
    @include('dashboardAdm.components.top-lapangan')

    {{-- QUICK ACTIONS --}}
    @include('dashboardAdm.components.quick-actions')

  </div>
</div>

@include('dashboardAdm.components.styles')
@endsection
