@include('layout.layout')
<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 px-4 py-8">
    <div class="w-full max-w-2xl bg-white/10 backdrop-blur-md shadow-xl border border-white/20 rounded-2xl p-8">
        <h2 class="text-3xl font-semibold text-white text-center mb-2">
            Daftar Member Baru
        </h2>
        <p class="text-white/70 text-center mb-6 text-sm">
            Isi formulir di bawah untuk membuat akun baru
        </p>

        @if (session('error'))
            <div class="mb-4 p-4 rounded-lg bg-red-500/20 text-red-300 text-sm border border-red-400/30">
                {{ session('error') }}
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

        <form action="{{ route('register.store') }}" method="POST" class="space-y-5">
            @csrf

            <!-- Row 1: Name & Gender -->
            <div class="grid grid-cols-2 gap-4">
                {{-- NAMA --}}
                <div>
                    <label class="block mb-2 text-white/90 font-medium text-sm">Nama Lengkap <span class="text-red-400">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}"
                        class="w-full p-3 rounded-lg bg-white/10 text-white placeholder-white/70 border @error('name') border-red-500 @else border-white/20 @enderror focus:border-blue-400 focus:ring-2 focus:ring-blue-500 focus:outline-none"
                        placeholder="Masukkan nama lengkap" required>
                    @error('name')
                        <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- JENIS KELAMIN --}}
                <div>
                    <label class="block mb-2 text-white/90 font-medium text-sm">Jenis Kelamin <span class="text-red-400">*</span></label>
                    <select name="gender"
                        class="w-full p-3 rounded-lg bg-white/10 text-dark border @error('gender') border-red-500 @else border-white/20 @enderror focus:border-blue-400 focus:ring-2 focus:ring-blue-500 focus:outline-none cursor-pointer"
                        required>
                        <option value="">-- Pilih Jenis Kelamin --</option>
                        <option value="L" {{ old('gender') === 'L' ? 'selected' : '' }}>Laki-Laki</option>
                        <option value="P" {{ old('gender') === 'P' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                    @error('gender')
                        <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- EMAIL --}}
            <div>
                <label class="block mb-2 text-white/90 font-medium text-sm">Email <span class="text-red-400">*</span></label>
                <input type="email" name="email" value="{{ old('email') }}"
                    class="w-full p-3 rounded-lg bg-white/10 text-white placeholder-white/70 border @error('email') border-red-500 @else border-white/20 @enderror focus:border-blue-400 focus:ring-2 focus:ring-blue-500 focus:outline-none"
                    placeholder="Masukkan email Anda" required>
                @error('email')
                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Row 2: Password & Password Confirm -->
            <div class="grid grid-cols-2 gap-4">
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
            </div>

            {{-- NOMOR HP --}}
            <div>
                <label class="block mb-2 text-white/90 font-medium text-sm">No. Telepon <span class="text-red-400">*</span></label>
                <input type="tel" name="phone" value="{{ old('phone') }}"
                    class="w-full p-3 rounded-lg bg-white/10 text-white placeholder-white/70 border @error('phone') border-red-500 @else border-white/20 @enderror focus:border-blue-400 focus:ring-2 focus:ring-blue-500 focus:outline-none"
                    placeholder="08xx atau +62xx" required>
                <p class="text-white/60 text-xs mt-1">Format: 08xx atau +62xx</p>
                @error('phone')
                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- ALAMAT --}}
            <div>
                <label class="block mb-2 text-white/90 font-medium text-sm">Alamat <span class="text-red-400">*</span></label>
                <textarea name="address"
                    class="w-full p-3 rounded-lg bg-white/10 text-white placeholder-white/70 border @error('address') border-red-500 @else border-white/20 @enderror focus:border-blue-400 focus:ring-2 focus:ring-blue-500 focus:outline-none"
                    placeholder="Jl. ... No. ..." rows="3" required>{{ old('address') }}</textarea>
                @error('address')
                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <p class="text-white/60 text-xs text-center">
                <span class="text-red-400">*</span> Wajib diisi
            </p>

            {{-- BUTTON --}}
            <button type="submit"
                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-3 rounded-lg transition duration-200 transform hover:scale-105">
                Daftar Sekarang
            </button>

            {{-- LOGIN LINK --}}
            <p class="text-center text-white/70 text-sm">
                Sudah punya akun?
                <a href="{{ route('login') }}" class="text-blue-400 hover:underline font-medium">
                    Login di sini
                </a>
            </p>
        </form>
    </div>
</div>
