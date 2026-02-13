<div class="content-header bg-gradient-to-r from-purple-700 via-purple-600 to-indigo-600 text-white py-8 shadow-2xl rounded-b-3xl">
  <div class="container-fluid px-4">
    <div class="row mb-4 items-center">
      <div class="col-sm-6 flex items-center">
        <h1 class="m-0 text-4xl font-bold flex items-center">
          <i class="fas fa-crown mr-3 text-yellow-300 animate-pulse"></i>Master Dashboard
        </h1>
        <p class="text-purple-200 mt-2 ml-4">
          <i class="fas fa-laptop-house mr-2"></i>Kelola semua region dari satu tempat
        </p>
      </div>
      <div class="col-sm-6 text-end flex justify-end items-center">
        <div class="flex items-center gap-3">
          <img src="https://ui-avatars.com/api/?name={{ urlencode($admin->name) }}&background=6D28D9&color=fff&size=64" alt="Admin Avatar" class="rounded-full shadow-lg border-2 border-yellow-400 w-12 h-12">
          <div>
            <p class="text-purple-100 mb-1 font-semibold">
              <i class="fas fa-user-shield mr-2"></i>{{ $admin->name }}
            </p>
            <span class="badge bg-yellow-400 text-yellow-900 px-4 py-2 text-sm font-bold shadow">
              <i class="fas fa-star mr-2"></i>Master Admin (CEO)
            </span>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
