@include('layout.layout')
<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 px-4">
    <div class="w-full max-w-md bg-white/10 backdrop-blur-md shadow-xl border border-white/20 rounded-2xl p-8">

        <h2 class="text-3xl font-semibold text-white text-center mb-2">
            <i class="fas fa-user-shield mr-2 text-amber-400"></i>Admin Login
        </h2>
        <p class="text-center text-white/60 text-sm mb-6">
            Selamat datang kembali!
        </p>

        @if(session('success'))
            <div class="mb-4 p-3 rounded-lg bg-green-500/20 text-green-300 text-sm border border-green-400/30">
                <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-4 p-3 rounded-lg bg-red-500/20 text-red-300 text-sm border border-red-400/30">
                <i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}
            </div>
        @endif

        @if($errors->has('loginError'))
            <div class="mb-4 p-3 rounded-lg bg-red-500/20 text-red-300 text-sm border border-red-400/30">
                <i class="fas fa-times-circle mr-2"></i>{{ $errors->first('loginError') }}
            </div>
        @endif

        @if($errors->any())
            <div class="mb-4 p-3 rounded-lg bg-red-500/20 text-red-300 text-sm border border-red-400/30">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.login.post') }}">
            @csrf
            {{-- Credential (Name atau Email) --}}
            <label class="block text-white/90 mb-1 font-medium">
                <i class="fas fa-user mr-1"></i>Nama Admin atau Email
            </label>
            <input type="text" name="credential" value="{{ old('credential') }}"
                class="w-full mb-4 p-3 rounded-lg bg-white/10 text-white placeholder-white/70 border @error('credential') border-red-500 @else border-white/20 @enderror focus:border-blue-400 focus:ring-2 focus:ring-blue-500 focus:outline-none"
                placeholder="Admin name atau email..." required>
            @error('credential')
                <p class="text-red-400 text-sm mb-4">{{ $message }}</p>
            @enderror

            {{-- Password --}}
            <label class="block text-white/90 mb-1 font-medium">
                <i class="fas fa-lock mr-1"></i>Password
            </label>
            <input type="password" name="password"
                class="w-full mb-2 p-3 rounded-lg bg-white/10 text-white placeholder-white/70 border @error('password') border-red-500 @else border-white/20 @enderror focus:border-blue-400 focus:ring-2 focus:ring-blue-500 focus:outline-none"
                placeholder="Masukkan password..." required>
            @error('password')
                <p class="text-red-400 text-sm mb-4">{{ $message }}</p>
            @enderror

            {{-- Remember Me --}}
            <div class="mb-6 flex items-center">
                <input type="checkbox" name="remember" id="remember" class="rounded bg-white/20 border-white/30 text-blue-500 focus:ring-0" {{ old('remember') ? 'checked' : '' }}>
                <label for="remember" class="ml-2 text-white/70 text-sm cursor-pointer">
                    Ingat saya
                </label>
            </div>

            {{-- Button --}}
            <button type="submit"
                class="w-full bg-gradient-to-r from-amber-600 to-amber-700 hover:from-amber-700 hover:to-amber-800 text-white font-medium py-3 rounded-lg transition duration-200 transform hover:scale-105 shadow-lg">
                <i class="fas fa-sign-in-alt mr-2"></i>Login Admin
            </button>

            {{-- Help Text --}}
            <p class="text-center text-white/60 text-xs mt-4">
                <i class="fas fa-info-circle mr-1"></i>Hubungi Master Admin jika lupa password
            </p>

        </form>
    </div>
</div>
