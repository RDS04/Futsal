{{-- RECENT BOOKINGS --}}
<div class="mb-4">
  <div class="card shadow-xl border-0 rounded-xl overflow-hidden">
    <div class="card-header bg-gradient-to-r from-emerald-500 to-emerald-700 text-white p-6 border-0">
      <h3 class="card-title mb-0 flex items-center font-bold text-lg">
        <i class="fas fa-history mr-3"></i> Booking Terbaru (10 Terakhir)
      </h3>
    </div>
    <div class="card-body p-4">
      @if($recentBookings->count() > 0)
        <div class="overflow-x-auto">
          <table class="table table-striped table-hover table-sm w-full">
            <thead class="bg-emerald-100">
              <tr>
                <th class="px-3 py-2">ID</th>
                <th class="px-3 py-2">Customer</th>
                <th class="px-3 py-2">Lapangan</th>
                <th class="px-3 py-2">Tanggal</th>
                <th class="px-3 py-2">Region</th>
                <th class="px-3 py-2">Status</th>
              </tr>
            </thead>
            <tbody>
              @foreach($recentBookings as $booking)
              <tr class="hover:bg-emerald-50">
                <td class="px-3 py-2 text-sm">{{ $booking->id }}</td>
                <td class="px-3 py-2 text-sm font-semibold text-slate-800">{{ $booking->nama ?? 'N/A' }}</td>
                <td class="px-3 py-2 text-sm"><i class="fas fa-futbol mr-1"></i>{{ $booking->lapanganData->namaLapangan ?? $booking->lapangan ?? 'N/A' }}</td>
                <td class="px-3 py-2 text-sm"><i class="fas fa-calendar mr-1"></i>{{ $booking->tanggal }}</td>
                <td class="px-3 py-2 text-sm"><span class="badge bg-primary">{{ ucfirst($booking->region) }}</span></td>
                <td class="px-3 py-2 text-sm">
                  <span class="badge bg-{{ $booking->status === 'confirmed' ? 'success' : ($booking->status === 'pending' ? 'warning' : 'danger') }}">
                    {{ ucfirst($booking->status) }}
                  </span>
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      @else
        <div class="text-center py-8">
          <i class="fas fa-inbox text-4xl text-slate-300 mb-3 block"></i>
          <p class="text-slate-500">Belum ada booking</p>
        </div>
      @endif
    </div>
  </div>
</div>
