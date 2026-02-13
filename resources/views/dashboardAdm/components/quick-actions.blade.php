{{-- QUICK ACTIONS --}}
<div class="row mb-6">
  <div class="col-md-12">
    <div class="card shadow-xl border-0 rounded-xl overflow-hidden">
      <div class="card-header bg-gradient-to-r from-pink-500 to-rose-500 text-white p-6 border-0">
        <h3 class="card-title mb-0 flex items-center font-bold text-lg">
          <i class="fas fa-lightning-bolt mr-3"></i> Aksi Cepat
        </h3>
      </div>
      <div class="card-body p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
          <!-- Bypass Dashboard -->
          <a href="{{ route('admin.dashboard.region', 'bypass') }}" class="group block p-6 bg-gradient-to-br from-blue-50 to-blue-100 rounded-lg border-2 border-blue-200 hover:border-blue-500 hover:shadow-lg transition-all duration-300 text-center">
            <i class="fas fa-city text-4xl text-blue-600 mb-3 group-hover:scale-110 transition-transform"></i>
            <h5 class="font-bold text-slate-800">Dashboard Bypass</h5>
            <p class="text-sm text-slate-600 mt-1">Kelola region Bypass</p>
          </a>

          <!-- Ulak Karang Dashboard -->
          <a href="{{ route('admin.dashboard.region', 'ulakkarang') }}" class="group block p-6 bg-gradient-to-br from-green-50 to-green-100 rounded-lg border-2 border-green-200 hover:border-green-500 hover:shadow-lg transition-all duration-300 text-center">
            <i class="fas fa-leaf text-4xl text-green-600 mb-3 group-hover:scale-110 transition-transform"></i>
            <h5 class="font-bold text-slate-800">Dashboard Ulak Karang</h5>
            <p class="text-sm text-slate-600 mt-1">Kelola region Ulak Karang</p>
          </a>

          <!-- Bukittinggi Dashboard -->
          <a href="{{ route('admin.dashboard.region', 'batusangkar') }}" class="group block p-6 bg-gradient-to-br from-orange-50 to-orange-100 rounded-lg border-2 border-orange-200 hover:border-orange-500 hover:shadow-lg transition-all duration-300 text-center">
            <i class="fas fa-mountain text-4xl text-orange-600 mb-3 group-hover:scale-110 transition-transform"></i>
            <h5 class="font-bold text-slate-800">Dashboard BatuSangkar</h5>
            <p class="text-sm text-slate-600 mt-1">Kelola region BatuSangkar</p>
          </a>

          <!-- Logout -->
          <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="group block p-6 bg-gradient-to-br from-red-50 to-red-100 rounded-lg border-2 border-red-200 hover:border-red-500 hover:shadow-lg transition-all duration-300 text-center">
            <i class="fas fa-sign-out-alt text-4xl text-red-600 mb-3 group-hover:scale-110 transition-transform"></i>
            <h5 class="font-bold text-slate-800">Logout</h5>
            <p class="text-sm text-slate-600 mt-1">Keluar dari sistem</p>
          </a>
        </div>

        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
          @csrf
        </form>
      </div>
    </div>
  </div>
</div>
