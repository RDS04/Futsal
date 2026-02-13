{{-- ADMIN MANAGEMENT --}}
<div class="row mb-6">
  <div class="col-md-12">
    <div class="card shadow-xl border-0 rounded-xl overflow-hidden">
      <div class="card-header bg-gradient-to-r from-indigo-600 to-indigo-800 text-white p-6 border-0 flex items-center justify-between">
        <h3 class="card-title mb-0 flex items-center font-bold text-lg">
          <i class="fas fa-users-cog mr-3"></i> Manajemen Admin
        </h3>
        <a href="{{ route('admin.register.show') }}" class="btn btn-light btn-sm shadow hover:bg-indigo-100 transition">
          <i class="fas fa-plus mr-1"></i>Tambah Admin
        </a>
      </div>
      <div class="card-body p-6">
        <div class="overflow-x-auto">
          <table class="table table-striped table-hover w-full rounded-lg overflow-hidden">
            <thead class="bg-gradient-to-r from-indigo-100 to-indigo-50">
              <tr>
                <th class="px-4 py-3"><i class="fas fa-user mr-2"></i>Nama Admin</th>
                <th class="px-4 py-3"><i class="fas fa-envelope mr-2"></i>Email</th>
                <th class="px-4 py-3"><i class="fas fa-shield-alt mr-2"></i>Role</th>
                <th class="px-4 py-3"><i class="fas fa-map-pin mr-2"></i>Region</th>
                <th class="px-4 py-3"><i class="fas fa-clock mr-2"></i>Dibuat</th>
                <th class="px-4 py-3">Action</th>
              </tr>
            </thead>
            <tbody>
              @forelse($allAdmins as $adm)
              <tr class="hover:bg-indigo-100 transition-colors duration-200">
                <td class="px-4 py-3 font-semibold flex items-center gap-2">
                  <img src="https://ui-avatars.com/api/?name={{ urlencode($adm->name) }}&background=4F46E5&color=fff&size=36" alt="Avatar" class="rounded-full w-8 h-8">
                  {{ $adm->name }}
                </td>
                <td class="px-4 py-3 text-sm">{{ $adm->email }}</td>
                <td class="px-4 py-3">
                  @if($adm->role === 'master')
                    <span class="badge bg-yellow-400 text-yellow-900">
                      <i class="fas fa-crown mr-1"></i>Master Admin
                    </span>
                  @else
                    <span class="badge bg-blue-400 text-blue-900">
                      <i class="fas fa-map-pin mr-1"></i>Regional
                    </span>
                  @endif
                </td>
                <td class="px-4 py-3">
                  <span class="badge bg-secondary">{{ ucfirst($adm->region ?? 'All') }}</span>
                </td>
                <td class="px-4 py-3 text-sm">
                  {{ $adm->created_at->format('d M Y') }}
                </td>
                <td class="px-4 py-3">
                  <a href="#" class="btn btn-sm btn-outline-warning">
                    <i class="fas fa-edit"></i>
                  </a>
                  <a href="#" class="btn btn-sm btn-outline-danger">
                    <i class="fas fa-trash"></i>
                  </a>
                </td>
              </tr>
              @empty
              <tr>
                <td colspan="6" class="text-center py-6 text-slate-500">
                  <i class="fas fa-inbox text-3xl mb-3 block opacity-50"></i>
                  Belum ada admin
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
