@include('layout.layout')
<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 px-4">
    <div class="w-full max-w-md bg-white/10 backdrop-blur-md shadow-xl border border-white/20 rounded-2xl p-8">

        <h2 class="text-3xl font-semibold text-white text-center mb-6">
            Login Member
        </h2>

        @if(session('success'))
            <div class="mb-4 p-3 rounded-lg bg-green-500/20 text-green-300 text-sm border border-green-400/30">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-4 p-3 rounded-lg bg-red-500/20 text-red-300 text-sm border border-red-400/30">
                {{ session('error') }}
            </div>
        @endif

        @if($errors->has('loginError'))
            <div class="mb-4 p-3 rounded-lg bg-red-500/20 text-red-300 text-sm border border-red-400/30">
                {{ $errors->first('loginError') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login.post') }}">
            @csrf

            {{-- Email --}}
            <label class="block text-white/90 mb-1 font-medium">Email Member</label>
            <input type="email" name="email" value="{{ old('email') }}"
                class="w-full mb-4 p-3 rounded-lg bg-white/10 text-white placeholder-white/70 border @error('email') border-red-500 @else border-white/20 @enderror focus:border-blue-400 focus:ring-2 focus:ring-blue-500 focus:outline-none"
                placeholder="Masukkan email..." required>
            @error('email')
                <p class="text-red-400 text-sm mb-4">{{ $message }}</p>
            @enderror

            {{-- Password --}}
            <label class="block text-white/90 mb-1 font-medium">Password</label>

            <div class="relative mb-6">
                <input
                    type="password"
                    name="password"
                    id="password"
                    class="w-full p-3 pr-12 rounded-lg bg-white/10 text-white placeholder-white/70 border @error('password') border-red-500 @else border-white/20 @enderror focus:border-blue-400 focus:ring-2 focus:ring-blue-500 focus:outline-none"
                    placeholder="Masukkan password..."
                    required>

                <!-- Eye Button -->
                <button
                    type="button"
                    onclick="togglePassword()"
                    class="absolute inset-y-0 right-3 flex items-center text-white/70 hover:text-white transition">
                    <svg id="eyeOpen" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 hidden" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M2.458 12C3.732 7.943 7.523 5 12 5
                     c4.478 0 8.268 2.943 9.542 7
                     -1.274 4.057-5.064 7-9.542 7
                     -4.477 0-8.268-2.943-9.542-7z" />
                    </svg>

                    <svg id="eyeClosed" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13.875 18.825A10.05 10.05 0 0112 19
                         c-4.478 0-8.268-2.943-9.543-7
                         a9.97 9.97 0 012.478-4.132M6.223 6.223
                         A9.956 9.956 0 0112 5
                         c4.478 0 8.268 2.943 9.543 7
                         a9.97 9.97 0 01-2.478 4.132m0 0a3 3 0 01-4.255 4.255m7.07-7.07a3 3 0 111.414-1.414M9.88 9.88a3 3 0 111.414 1.414" />
                    </svg>
                </button>
            </div>
            @error('password')
                <p class="text-red-400 text-sm mb-6">{{ $message }}</p>
            @enderror


            {{-- Button --}}
            <button type="submit"
                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-3 rounded-lg transition duration-200 transform hover:scale-105">
                Login
            </button>

            {{-- Register Link --}}
            <p class="text-center text-white/70 text-sm mt-4">
                Belum punya akun?
                <a href="{{ route('register') }}" class="text-blue-400 hover:underline font-medium">
                    Daftar di sini
                </a>
            </p>

        </form>
    </div>
</div>
<script>
    function togglePassword() {
        const passwordInput = document.getElementById('password');
        const eyeOpen = document.getElementById('eyeOpen');
        const eyeClosed = document.getElementById('eyeClosed');

        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            eyeOpen.classList.remove('hidden');
            eyeClosed.classList.add('hidden');
        } else {
            passwordInput.type = 'password';
            eyeOpen.classList.add('hidden');
            eyeClosed.classList.remove('hidden');
        }
    }
</script>