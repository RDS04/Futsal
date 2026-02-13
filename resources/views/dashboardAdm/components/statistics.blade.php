{{-- GLOBAL STATISTICS --}}
<div class="row mt-6 mb-6">
  <!-- Total Lapangan -->
  <div class="col-lg-3 col-md-6 col-sm-6 mb-4">
    <div class="card stat-card shadow-xl border-0 rounded-xl overflow-hidden hover:shadow-2xl transition-all duration-300 transform hover:scale-105">
      <div class="card-body p-0">
        <div class="bg-gradient-to-br from-blue-400 to-blue-600 text-white p-6 flex items-center gap-4">
          <div>
            <h3 class="text-3xl font-bold">{{ $totalLapangan ?? 0 }}</h3>
            <p class="text-blue-100 text-sm mt-1">Total Lapangan</p>
          </div>
          <div class="text-5xl opacity-20 ml-auto">
            <i class="fas fa-futbol"></i>
          </div>
        </div>
        <div class="bg-slate-50 p-4 text-center border-t">
          <span class="text-xs text-slate-600 font-semibold">Dari semua region</span>
        </div>
      </div>
    </div>
  </div>

  <!-- Total Booking -->
  <div class="col-lg-3 col-md-6 col-sm-6 mb-4">
    <div class="card stat-card shadow-xl border-0 rounded-xl overflow-hidden hover:shadow-2xl transition-all duration-300 transform hover:scale-105">
      <div class="card-body p-0">
        <div class="bg-gradient-to-br from-green-400 to-green-600 text-white p-6 flex items-center gap-4">
          <div>
            <h3 class="text-3xl font-bold">{{ $totalBoking }}</h3>
            <p class="text-green-100 text-sm mt-1">Total Booking</p>
          </div>
          <div class="text-5xl opacity-20 ml-auto">
            <i class="fas fa-calendar-check"></i>
          </div>
        </div>
        <div class="bg-slate-50 p-4 text-center border-t">
          <span class="text-xs text-slate-600 font-semibold">Dari semua region</span>
        </div>
      </div>
    </div>
  </div>

  <!-- Total Event -->
  <div class="col-lg-3 col-md-6 col-sm-6 mb-4">
    <div class="card stat-card shadow-xl border-0 rounded-xl overflow-hidden hover:shadow-2xl transition-all duration-300 transform hover:scale-105">
      <div class="card-body p-0">
        <div class="bg-gradient-to-br from-yellow-400 to-yellow-600 text-white p-6 flex items-center gap-4">
          <div>
            <h3 class="text-3xl font-bold">{{ $totalEvents }}</h3>
            <p class="text-yellow-100 text-sm mt-1">Total Event</p>
          </div>
          <div class="text-5xl opacity-20 ml-auto">
            <i class="fas fa-calendar-star"></i>
          </div>
        </div>
        <div class="bg-slate-50 p-4 text-center border-t">
          <span class="text-xs text-slate-600 font-semibold">Dari semua region</span>
        </div>
      </div>
    </div>
  </div>

  <!-- Total Admin -->
  <div class="col-lg-3 col-md-6 col-sm-6 mb-4">
    <div class="card stat-card shadow-xl border-0 rounded-xl overflow-hidden hover:shadow-2xl transition-all duration-300 transform hover:scale-105">
      <div class="card-body p-0">
        <div class="bg-gradient-to-br from-red-400 to-red-600 text-white p-6 flex items-center gap-4">
          <div>
            <h3 class="text-3xl font-bold">{{ $totalAdmins }}</h3>
            <p class="text-red-100 text-sm mt-1">Total Admin</p>
            <small class="text-red-100">
              <i class="fas fa-crown mr-1"></i>{{ $totalAdminMaster }} Master
              <i class="fas fa-map-pin mr-1 ml-2"></i>{{ $totalAdminRegional }} Regional
            </small>
          </div>
          <div class="text-5xl opacity-20 ml-auto">
            <i class="fas fa-users"></i>
          </div>
        </div>
        <div class="bg-slate-50 p-4 text-center border-t">
          <span class="text-xs text-slate-600 font-semibold">Admin aktif</span>
        </div>
      </div>
    </div>
  </div>
</div>
