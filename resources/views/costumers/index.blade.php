@include('layout.layout')

<div class="min-h-screen bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 p-6">
    <div class="max-w-6xl mx-auto">
        {{-- Header --}}
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-4xl font-bold text-white mb-2">Dashboard</h1>
                <p class="text-slate-300">Selamat datang, {{ Auth::user()->name ?? 'Member' }}</p>
            </div>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-6 py-2 rounded-lg transition">
                    Logout
                </button>
            </form>
        </div>

        {{-- Stats Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white/10 backdrop-blur-md border border-white/20 rounded-lg p-6">
                <h3 class="text-slate-300 text-sm font-medium mb-2">Email</h3>
                <p class="text-white text-lg font-semibold">{{ Auth::user()->email ?? '-' }}</p>
            </div>
            <div class="bg-white/10 backdrop-blur-md border border-white/20 rounded-lg p-6">
                <h3 class="text-slate-300 text-sm font-medium mb-2">No HP</h3>
                <p class="text-white text-lg font-semibold">{{ Auth::user()->phone ?? '-' }}</p>
            </div>
            <div class="bg-white/10 backdrop-blur-md border border-white/20 rounded-lg p-6">
                <h3 class="text-slate-300 text-sm font-medium mb-2">Region</h3>
                <p class="text-white text-lg font-semibold">{{ Auth::user()->region ?? '-' }}</p>
            </div>
        </div>

        {{-- Profile Info --}}
        <div class="bg-white/10 backdrop-blur-md border border-white/20 rounded-lg p-8">
            <h2 class="text-2xl font-bold text-white mb-6">Informasi Profil</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="text-slate-300 text-sm font-medium">Nama Lengkap</label>
                    <p class="text-white text-lg mt-2">{{ Auth::user()->name ?? '-' }}</p>
                </div>
                <div>
                    <label class="text-slate-300 text-sm font-medium">Jenis Kelamin</label>
                    <p class="text-white text-lg mt-2">
                        {{ Auth::user()->gender == 'L' ? 'Laki-Laki' : (Auth::user()->gender == 'P' ? 'Perempuan' : '-') }}
                    </p>
                </div>
                <div>
                    <label class="text-slate-300 text-sm font-medium">Email</label>
                    <p class="text-white text-lg mt-2">{{ Auth::user()->email ?? '-' }}</p>
                </div>
                <div>
                    <label class="text-slate-300 text-sm font-medium">Alamat</label>
                    <p class="text-white text-lg mt-2">{{ Auth::user()->address ?? '-' }}</p>
                </div>
            </div>
        </div>
    </div>
</div>