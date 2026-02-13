@include('layout.layout')
<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 px-4 py-8">
    <div class="w-full max-w-md bg-white/10 backdrop-blur-md shadow-xl border border-white/20 rounded-2xl p-8">
        <h2 class="text-3xl font-semibold text-white text-center mb-2">
            Registrasi Admin
        </h2>
        <p class="text-white/70 text-center mb-6 text-sm">
            Hanya untuk Master Admin
        </p>

        @if (session('error'))
            <div class="mb-4 p-4 rounded-lg bg-red-500/20 text-red-300 text-sm border border-red-400/30">
                {{ session('error') }}
            </div>
        @endif

        @if (session('success'))
            <div class="mb-4 p-4 rounded-lg bg-green-500/20 text-green-300 text-sm border border-green-400/30">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-4 p-4 rounded-lg bg-red-500/20 text-red-300 text-sm border border-red-400/30">
                <p class="font-semibold mb-2">❌ Validasi Gagal:</p>
                <ul class="list-disc list-inside space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.register.store') }}" method="POST" class="space-y-5">
            @csrf

            {{-- NAME --}}
            <div>
                <label class="block mb-2 text-white/90 font-medium text-sm">Nama Admin <span class="text-red-400">*</span></label>
                <input type="text" name="name" value="{{ old('name') }}"
                    class="w-full p-3 rounded-lg bg-white/10 text-white placeholder-white/70 border @error('name') border-red-500 @else border-white/20 @enderror focus:border-blue-400 focus:ring-2 focus:ring-blue-500 focus:outline-none"
                    placeholder="Masukkan nama admin" required>
                @error('name')
                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- PASSWORD --}}
            <div>
                <label class="block mb-2 text-white/90 font-medium text-sm">Password <span class="text-red-400">*</span></label>
                <input type="password" name="password"
                    class="w-full p-3 rounded-lg bg-white/10 text-white placeholder-white/70 border @error('password') border-red-500 @else border-white/20 @enderror focus:border-blue-400 focus:ring-2 focus:ring-blue-500 focus:outline-none"
                    placeholder="Min. 8 karakter" required>
                @error('password')
                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- PASSWORD CONFIRM --}}
            <div>
                <label class="block mb-2 text-white/90 font-medium text-sm">Konfirmasi Password <span class="text-red-400">*</span></label>
                <input type="password" name="password_confirmation"
                    class="w-full p-3 rounded-lg bg-white/10 text-white placeholder-white/70 border @error('password_confirmation') border-red-500 @else border-white/20 @enderror focus:border-blue-400 focus:ring-2 focus:ring-blue-500 focus:outline-none"
                    placeholder="Ulangi password" required>
                @error('password_confirmation')
                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- REGION --}}
            <div>
                <label class="block mb-2 text-white/90 font-medium text-sm">Region <span class="text-red-400">*</span></label>
                <select name="region"
                    class="w-full p-3 rounded-lg bg-white/10 text-dark border @error('region') border-red-500 @else border-white/20 @enderror focus:border-blue-400 focus:ring-2 focus:ring-blue-500 focus:outline-none cursor-pointer"
                    required>
                    <option value="defaul">-- Pilih Region Admin --</option>
                    <option value="bypass" {{ old('region') === 'Bypass' ? 'selected' : '' }}>Bypass</option>
                    <option value="ulakkarang" {{ old('region') === 'UlakKarang' ? 'selected' : '' }}>Ulak Karang</option>
                    <option value="batusangkar" {{ old('region') === 'BatuSangkar' ? 'selected' : '' }}>BatuSangkar</option>
                </select>
                @error('region')
                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- ROLE --}}
            <div>
                <label class="block mb-2 text-black font-medium text-sm">Role <span class="text-red-400">*</span></label>
                <select name="role"
                    class="w-full p-3 rounded-lg bg-white/10 text-black border @error('role') border-red-500 @else border-white/20 @enderror focus:border-blue-400 focus:ring-2 focus:ring-blue-500 focus:outline-none cursor-pointer"
                    required>
                    <option value="">-- Pilih Role Admin --</option>
                    <option value="master" {{ old('role') === 'master' ? 'selected' : '' }}>Master</option>
                    <option value="regional" {{ old('role') === 'regional' ? 'selected' : '' }}>Admin</option>
                    
                </select>
                @error('role')
                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <p class="text-white/60 text-xs text-center">
                <span class="text-red-400">*</span> Wajib diisi
            </p>

            <button type="submit"
                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-3 rounded-lg transition duration-200 transform hover:scale-105">
                Daftar Admin
            </button>

            {{-- LOGIN LINK --}}
            <p class="text-center text-white/70 text-sm">
                Sudah punya akun?
                <a href="{{ route('loginAdmin') }}" class="text-blue-400 hover:underline font-medium">
                    Login di sini
                </a>
            </p>
        </form>
    </div>
</div>
