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
        <div class="card border-0 shadow-sm h-100" style="border-left: 4px solid #27ae60; background: linear-gradient(135deg, #27ae60 0%, #229954 100%);">
          <div class="card-body p-4">
            <div class="d-flex justify-content-between align-items-center">
              <div>
                <p class="text-white text-sm mb-2"><i class="fas fa-credit-card me-2"></i>Total Pembayaran</p>
                <h3 class="mb-0 text-white" style="font-weight: 700;">{{ $totalPaid }}</h3>
              </div>
              <div style="font-size: 2.5rem; color: rgba(255, 255, 255, 0.3);">
                <i class="fas fa-credit-card"></i>
              </div>
            </div>
          </div>
        </div>
      </div>
      
      @php
        $colors = [
          ['border' => '#e74c3c', 'gradient' => 'linear-gradient(135deg, #e74c3c 0%, #c0392b 100%)'],
          ['border' => '#9b59b6', 'gradient' => 'linear-gradient(135deg, #9b59b6 0%, #8e44ad 100%)'],
          ['border' => '#1abc9c', 'gradient' => 'linear-gradient(135deg, #1abc9c 0%, #16a085 100%)']
        ];
        $regionNames = ['bypass' => 'Bypass', 'ulakkarang' => 'Ulak Karang', 'batusangkar' => 'Batu Sangkar'];
        $colorIndex = 0;
      @endphp
      
      @foreach($paidUsersByRegion as $region => $count)
      @php
        $color = $colors[$colorIndex % count($colors)];
        $colorIndex++;
      @endphp
      <div class="col-md-3">
        <div class="card border-0 shadow-sm h-100" style="border-left: 4px solid {{ $color['border'] }}; background: {{ $color['gradient'] }};">
          <div class="card-body p-4">
            <div class="d-flex justify-content-between align-items-center">
              <div>
                <p class="text-white text-sm mb-2" style="text-transform: capitalize;">
                  <i class="fas fa-map-pin me-2"></i>{{ $regionNames[$region] ?? ucfirst($region) }}
                </p>
                <h3 class="mb-0 text-white" style="font-weight: 700;">{{ $count }}</h3>
              </div>
              <div style="font-size: 2.5rem; color: rgba(255, 255, 255, 0.3);">
                <i class="fas fa-map-pin"></i>
              </div>
            </div>
          </div>
        </div>
      </div>
      @endforeach
    </div>

    {{-- PAID USERS TABLE --}}
    <div class="row mb-4">
      <div class="col-md-12">
        <div class="card border-0 shadow-sm">
          <div class="card-header p-4" style="background: linear-gradient(135deg, #27ae60 0%, #229954 100%); border: none;">
            <h5 class="mb-0 text-white" style="font-weight: 600;">
              <i class="fas fa-list me-2"></i>Daftar User Pembayaran Berhasil
            </h5>
          </div>
          <div class="card-body p-0">
            <div class="table-responsive">
              <table class="table table-hover mb-0">
                <thead style="background: linear-gradient(135deg, #ecf0f1 0%, #f5f7fa 100%); border-top: 1px solid #e8eef2; border-bottom: 2px solid #27ae60;">
                  <tr>
                    <th class="px-4 py-3 font-weight-600" style="color: #27ae60;">No</th>
                    <th class="px-4 py-3 font-weight-600" style="color: #2c3e50;"><i class="fas fa-user me-2"></i>Nama</th>
                    <th class="px-4 py-3 font-weight-600" style="color: #2c3e50;"><i class="fas fa-envelope me-2"></i>Email</th>
                    <th class="px-4 py-3 font-weight-600" style="color: #2c3e50;"><i class="fas fa-phone me-2"></i>Telepon</th>
                    <th class="px-4 py-3 font-weight-600" style="color: #2c3e50;"><i class="fas fa-map-pin me-2"></i>Region</th>
                    <th class="px-4 py-3 font-weight-600" style="color: #2c3e50;"><i class="fas fa-calendar me-2"></i>Terdaftar</th>
                    <th class="px-4 py-3 font-weight-600 text-center" style="color: #2c3e50;">Status</th>
                    <th class="px-4 py-3 font-weight-600 text-center" style="color: #2c3e50;">Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  @forelse($users as $index => $user)
                  <tr style="border-bottom: 1px solid #e8eef2;">
                    <td class="px-4 py-3" style="color: #27ae60; font-weight: 600;">{{ $loop->iteration }}</td>
                    <td class="px-4 py-3" style="color: #2c3e50; font-weight: 500;">{{ $user->name }}</td>
                    <td class="px-4 py-3" style="color: #7f8c8d;">{{ $user->email }}</td>
                    <td class="px-4 py-3">
                      <span class="badge" style="background-color: #ecf0f1; color: #2c3e50;">{{ $user->phone ?? '-' }}</span>
                    </td>
                    <td class="px-4 py-3">
                      @php
                        $userRegions = $user->bokings->pluck('region')->unique()->implode(', ');
                        $regionCount = $user->bokings->pluck('region')->unique()->count();
                      @endphp
                      @if($regionCount > 0)
                        <span class="badge bg-light text-dark">
                          <i class="fas fa-map-pin me-1"></i>{{ str_replace(['bypass', 'ulakkarang', 'batusangkar'], ['Bypass', 'Ulak Karang', 'Batu Sangkar'], $userRegions) }}
                        </span>
                      @else
                        <span class="text-muted">-</span>
                      @endif
                    </td>
                    <td class="px-4 py-3" style="color: #7f8c8d; font-size: 0.9rem;">{{ $user->created_at?->format('d M Y') ?? '-' }}</td>
                    <td class="px-4 py-3 text-center">
                      <span class="badge bg-success text-white">
                        <i class="fas fa-check-circle me-1"></i>Sudah Bayar
                      </span>
                    </td>
                    <td class="px-4 py-3 text-center">
                      <div class="btn-group btn-group-sm" role="group">
                        <button class="btn btn-outline-primary btn-sm" title="Lihat Detail" data-bs-toggle="modal" data-bs-target="#detailModal{{ $user->id }}">
                          <i class="fas fa-eye"></i>
                        </button>
                      </div>
                    </td>
                  </tr>
                  @empty
                  <tr>
                    <td colspan="8" class="px-4 py-4 text-center text-muted">
                      <i class="fas fa-inbox me-2"></i>Tidak ada user yang sudah bayar
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

{{-- DETAIL MODALS --}}
@foreach($users as $user)
<div class="modal fade" id="detailModal{{ $user->id }}" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content border-0 shadow">
      <div class="modal-header p-4" style="background: linear-gradient(135deg, #27ae60 0%, #229954 100%); border: none;">
        <h5 class="modal-title text-white" style="font-weight: 600;">
          <i class="fas fa-user-circle me-2"></i>Detail User - {{ $user->name }}
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body p-4">
        <div class="row">
          <div class="col-md-6 mb-3">
            <label class="text-muted text-sm">Nama Lengkap</label>
            <p class="mb-0" style="color: #2c3e50; font-weight: 500;">{{ $user->name }}</p>
          </div>
          <div class="col-md-6 mb-3">
            <label class="text-muted text-sm">Gender</label>
            <p class="mb-0">
              <span class="badge {{ $user->gender === 'male' ? 'bg-info' : 'bg-warning' }} text-white">
                <i class="fas {{ $user->gender === 'male' ? 'fa-mars' : 'fa-venus' }} me-1"></i>
                {{ ucfirst($user->gender) }}
              </span>
            </p>
          </div>
          <div class="col-md-6 mb-3">
            <label class="text-muted text-sm">Email</label>
            <p class="mb-0" style="color: #7f8c8d;">{{ $user->email }}</p>
          </div>
          <div class="col-md-6 mb-3">
            <label class="text-muted text-sm">Telepon</label>
            <p class="mb-0" style="color: #7f8c8d;">{{ $user->phone ?? '-' }}</p>
          </div>
          <div class="col-md-6 mb-3">
            <label class="text-muted text-sm">Alamat</label>
            <p class="mb-0" style="color: #7f8c8d;">{{ $user->address ?? '-' }}</p>
          </div>
          <div class="col-md-6 mb-3">
            <label class="text-muted text-sm">Terdaftar Sejak</label>
            <p class="mb-0" style="color: #7f8c8d;">{{ $user->created_at?->format('d M Y H:i') ?? '-' }}</p>
          </div>
          <div class="col-md-12 mb-3">
            <label class="text-muted text-sm">Region Booking</label>
            <p class="mb-0">
              @php
                $regions = $user->bokings->pluck('region')->unique();
              @endphp
              @forelse($regions as $region)
                <span class="badge bg-light text-dark me-2" style="margin-bottom: 4px;">
                  {{ str_replace(['bypass', 'ulakkarang', 'batusangkar'], ['Bypass', 'Ulak Karang', 'Batu Sangkar'], $region) }}
                </span>
              @empty
                <span class="text-muted">-</span>
              @endforelse
            </p>
          </div>
          <div class="col-md-12">
            <label class="text-muted text-sm">Booking Terbaru</label>
            <div class="table-responsive">
              <table class="table table-sm table-hover">
                <thead style="background-color: #f5f7fa;">
                  <tr>
                    <th style="color: #34495e; font-size: 0.85rem;">Tanggal</th>
                    <th style="color: #34495e; font-size: 0.85rem;">Lapangan</th>
                    <th style="color: #34495e; font-size: 0.85rem;">Region</th>
                    <th style="color: #34495e; font-size: 0.85rem;">Status</th>
                  </tr>
                </thead>
                <tbody>
                  @forelse($user->bokings->take(5) as $booking)
                  <tr style="border-bottom: 1px solid #e8eef2;">
                    <td style="color: #7f8c8d; font-size: 0.85rem;">{{ $booking->tanggal?->format('d M Y') ?? '-' }}</td>
                    <td style="color: #7f8c8d; font-size: 0.85rem;">{{ $booking->lapanganData->namaLapangan ?? '-' }}</td>
                    <td style="color: #7f8c8d; font-size: 0.85rem;">{{ ucfirst($booking->region) }}</td>
                    <td style="font-size: 0.85rem;">
                      <span class="badge {{ $booking->status === 'confirmed' ? 'bg-success' : ($booking->status === 'pending' ? 'bg-warning' : 'bg-danger') }} text-white">
                        {{ ucfirst($booking->status) }}
                      </span>
                    </td>
                  </tr>
                  @empty
                  <tr>
                    <td colspan="4" class="text-center text-muted py-2" style="font-size: 0.85rem;">Belum ada booking</td>
                  </tr>
                  @endforelse
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer p-4 border-top">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
      </div>
    </div>
  </div>
</div>
@endforeach

@endsection
