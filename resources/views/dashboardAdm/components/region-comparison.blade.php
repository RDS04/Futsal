{{-- REGION COMPARISON TABLE --}}
<div class="row mb-6">
  <div class="col-md-12">
    <div class="card shadow-xl border-0 rounded-xl overflow-hidden">
      <div class="card-header bg-gradient-to-r from-blue-600 to-blue-800 text-white p-6 border-0 flex items-center">
        <h3 class="card-title mb-0 flex items-center font-bold text-xl">
          <i class="fas fa-chart-bar mr-3"></i> Perbandingan Statistik Per Region
        </h3>
      </div>
      <div class="card-body p-6">
        <div class="overflow-x-auto">
          <table class="table table-striped table-hover w-full rounded-lg overflow-hidden">
            <thead class="bg-gradient-to-r from-blue-50 to-cyan-50">
              <tr>
                <th class="px-4 py-4 font-bold text-slate-800"><i class="fas fa-map-pin mr-2 text-blue-600"></i>Region</th>
                <th class="px-4 py-4 text-center font-bold text-slate-800"><i class="fas fa-futbol mr-2 text-orange-600"></i>Lapangan</th>
                <th class="px-4 py-4 text-center font-bold text-slate-800"><i class="fas fa-check-circle mr-2 text-green-600"></i>Aktif</th>
                <th class="px-4 py-4 text-center font-bold text-slate-800"><i class="fas fa-calendar-check mr-2 text-indigo-600"></i>Booking</th>
                <th class="px-4 py-4 text-center font-bold text-slate-800"><i class="fas fa-thumbs-up mr-2 text-emerald-600"></i>Confirmed</th>
                <th class="px-4 py-4 text-center font-bold text-slate-800"><i class="fas fa-calendar-star mr-2 text-pink-600"></i>Event</th>
                <th class="px-4 py-4 text-center font-bold text-slate-800"><i class="fas fa-images mr-2 text-purple-600"></i>Slider</th>
                <th class="px-4 py-4 text-center font-bold text-slate-800">Action</th>
              </tr>
            </thead>
            <tbody>
              @forelse($regionStats as $regionCode => $stats)
              <tr class="hover:bg-blue-50 transition-colors duration-200 border-b border-slate-200">
                <td class="px-4 py-4 font-bold text-slate-800">
                  <i class="fas fa-map-marker-alt mr-2 text-blue-600"></i>
                  {{ ucfirst($regionCode) }}
                </td>
                <td class="px-4 py-4 text-center">
                  <span class="badge bg-primary">
                    <i class="fas fa-futbol mr-1"></i>{{ $stats['lapangan'] }}
                  </span>
                </td>
                <td class="px-4 py-4 text-center">
                  <span class="badge bg-success">
                    <i class="fas fa-check mr-1"></i>{{ $stats['lapanganAktif'] }}
                  </span>
                </td>
                <td class="px-4 py-4 text-center">
                  <span class="badge bg-info">
                    <i class="fas fa-calendar mr-1"></i>{{ $stats['boking'] }}
                  </span>
                </td>
                <td class="px-4 py-4 text-center">
                  <span class="badge bg-success" style="background: linear-gradient(135deg, #10b981, #059669) !important;">
                    <i class="fas fa-check-double mr-1"></i>{{ $stats['bokingConfirmed'] }}
                  </span>
                </td>
                <td class="px-4 py-4 text-center">
                  <span class="badge bg-warning">
                    <i class="fas fa-star mr-1"></i>{{ $stats['events'] }}
                  </span>
                </td>
                <td class="px-4 py-4 text-center">
                  <span class="badge bg-secondary">
                    <i class="fas fa-images mr-1"></i>{{ $stats['sliders'] }}
                  </span>
                </td>
                <td class="px-4 py-4 text-center">
                  <a href="{{ route('admin.dashboard.region', ['region' => $regionCode]) }}" class="btn btn-sm btn-outline-primary transition hover:shadow-md">
                    <i class="fas fa-eye mr-1"></i>Lihat
                  </a>
                </td>
              </tr>
              @empty
              <tr>
                <td colspan="8" class="text-center py-6 text-slate-500">
                  <i class="fas fa-inbox text-3xl mb-3 block opacity-50"></i>
                  Belum ada data region
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
