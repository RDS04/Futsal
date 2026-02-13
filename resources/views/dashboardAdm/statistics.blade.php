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

    {{-- REGION STATISTICS TABLE --}}
    @include('dashboardAdm.components.region-comparison')

  </div>
</div>

@include('dashboardAdm.components.styles')
@endsection
