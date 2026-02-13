{{-- TOP LAPANGAN --}}
<div class="mb-4">
  <div class="card shadow-xl border-0 rounded-xl overflow-hidden">
    <div class="card-header bg-gradient-to-r from-orange-500 to-orange-700 text-white p-6 border-0">
      <h3 class="card-title mb-0 flex items-center font-bold text-lg">
        <i class="fas fa-trophy mr-3"></i> Lapangan Terpopuler
      </h3>
    </div>
    <div class="card-body p-4">
      @if($topLapangan->count() > 0)
        <div class="overflow-x-auto">
          <table class="table table-striped table-hover table-sm w-full">
            <thead class="bg-orange-100">
              <tr>
                <th class="px-3 py-2">Ranking</th>
                <th class="px-3 py-2">Lapangan</th>
                <th class="px-3 py-2">Region</th>
                <th class="px-3 py-2">Total Booking</th>
              </tr>
            </thead>
            <tbody>
              @foreach($topLapangan as $index => $lapangan)
              <tr class="hover:bg-orange-50">
                <td class="px-3 py-2 text-sm">
                  <span class="badge bg-orange-500 rounded-circle w-8 h-8 d-flex align-items-center justify-content-center text-black">#{{ $index + 1 }}</span>
                </td>
                <td class="px-3 py-2 text-sm font-semibold text-slate-800">{{ $lapangan->lapanganData->namaLapangan ?? $lapangan->lapangan ?? 'N/A' }}</td>
                <td class="px-3 py-2 text-sm">
                  <span class="badge bg-primary">{{ ucfirst($lapangan->region ?? 'N/A') }}</span>
                </td>
                <td class="px-3 py-2 text-sm">
                  <span class="badge bg-info">{{ $lapangan->booking_count ?? 0 }} Booking</span>
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      @else
        <div class="text-center py-8">
          <i class="fas fa-chart-line text-4xl text-slate-300 mb-3 block"></i>
          <p class="text-slate-500">Belum ada data</p>
        </div>
      @endif
    </div>
  </div>
</div>
