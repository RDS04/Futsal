@extends('layouts.app')
@section('content')

<div class="content">
  <div class="container-fluid">

    {{-- INFO BOX --}}
    <div class="row">
      <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
          <div class="inner">
            <h3>150</h3>
            <p>New Orders</p>
          </div>
          <div class="icon">
            <i class="fas fa-shopping-cart"></i>
          </div>
          <a href="#" class="small-box-footer">
            More info <i class="fas fa-arrow-circle-right"></i>
          </a>
        </div>
      </div>

      <div class="col-lg-3 col-6">
        <div class="small-box bg-success">
          <div class="inner">
            <h3>53<sup style="font-size: 20px">%</sup></h3>
            <p>Bounce Rate</p>
          </div>
          <div class="icon">
            <i class="fas fa-chart-line"></i>
          </div>
          <a href="#" class="small-box-footer">
            More info <i class="fas fa-arrow-circle-right"></i>
          </a>
        </div>
      </div>

      <div class="col-lg-3 col-6">
        <div class="small-box bg-warning">
          <div class="inner">
            <h3>44</h3>
            <p>User Registrations</p>
          </div>
          <div class="icon">
            <i class="fas fa-user-plus"></i>
          </div>
          <a href="#" class="small-box-footer">
            More info <i class="fas fa-arrow-circle-right"></i>
          </a>
        </div>
      </div>

      <div class="col-lg-3 col-6">
        <div class="small-box bg-danger">
          <div class="inner">
            <h3>65</h3>
            <p>Unique Visitors</p>
          </div>
          <div class="icon">
            <i class="fas fa-chart-pie"></i>
          </div>
          <a href="#" class="small-box-footer">
            More info <i class="fas fa-arrow-circle-right"></i>
          </a>
        </div>
      </div>
    </div>

    {{-- CHART & MAP --}}
    <div class="row">
      <div class="col-lg-8">
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">
              <i class="fas fa-chart-area"></i>
              Sales
            </h3>
            <div class="card-tools">
              <button class="btn btn-sm btn-primary">Area</button>
              <button class="btn btn-sm btn-secondary">Donut</button>
            </div>
          </div>
          <div class="card-body">
            <canvas id="salesChart" height="150"></canvas>
          </div>
        </div>
      </div>

      <div class="col-lg-4">
        <div class="card bg-primary">
          <div class="card-header border-0">
            <h3 class="card-title">
              <i class="fas fa-map-marker-alt"></i>
              Visitors
            </h3>
          </div>
          <div class="card-body p-0">
            <div style="height: 250px; background:#1e90ff;"
              class="d-flex align-items-center justify-content-center text-white">
              MAP AREA
            </div>
          </div>
        </div>
      </div>
    </div>

    {{-- CHAT --}}
    <div class="row">
      <div class="col-lg-8">
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">
              <i class="fas fa-comments"></i>
              Direct Chat
            </h3>
          </div>
          <div class="card-body">
            <div class="direct-chat-messages">
              <div class="direct-chat-msg">
                <div class="direct-chat-text">
                  Is this template really for free? That's unbelievable!
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

  </div>
</div>
@push('scripts')
<script src="{{ asset('js/script.js') }}"></script>
@endpush
@endsection