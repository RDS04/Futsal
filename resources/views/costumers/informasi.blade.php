<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Booking Futsal</title>
    <style>
        @keyframes fadeLeft {
            from {
                opacity: 0;
                transform: translateX(-40px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes fadeRight {
            from {
                opacity: 0;
                transform: translateX(40px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .animate-fade-left {
            animation: fadeLeft 1s ease-out forwards;
        }

        .animate-fade-right {
            animation: fadeRight 1s ease-out forwards;
        }

        #midtrans img {
            width: 900px;

        }
    </style>

</head>

<body class="bg-black text-white">

    <!-- NAVBAR -->
    <!-- NAVBAR -->
    <nav class="absolute top-6 left-0 w-full z-50">
        <div class="max-w-6xl mx-auto px-6">
            <div class="bg-white text-black rounded-2xl shadow-lg px-6 py-4 flex items-center justify-between">

                <!-- Logo -->
                <h1 class="text-lg font-semibold tracking-wide">
                    Booking Futsal
                </h1>

                <!-- Right Menu -->
                <div class="flex items-center gap-4 text-sm">
                    <button class="text-gray-600 hover:text-black">
                        English
                    </button>

                    <a href="{{ route('login') }}" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                        Booking
                    </a>
                </div>

            </div>
        </div>
    </nav>


    <!-- HERO SECTION -->
    <section class="relative min-h-screen flex items-center">

        <!-- Background Image -->
        <div class="absolute inset-0">
            <img src="{{ asset('static/img/futsal.jpg') }}" alt="Futsal" class="w-full h-full object-cover">
            <!-- Gradient Overlay -->
            <div class="absolute inset-0 bg-gradient-to-t from-black via-black/60 to-black/30"></div>
        </div>

        <!-- Content -->
        <div class="relative z-10 max-w-4xl px-6 md:px-12">
            <h1 class="text-4xl md:text-6xl font-bold leading-tight">
                Life is like Soccer<br>
                <span class="text-green-500">You need Goals</span>
            </h1>

            <p class="mt-6 text-gray-300 max-w-xl">
                Whether you’re a beginner or a pro player, discover the best
                futsal courts and book your game instantly.
            </p>

            <div class="mt-10 flex gap-4">
                <a href="#" class="px-6 py-3 border border-white rounded-md hover:bg-white hover:text-black transition">
                    Explore
                </a>

                <a href="{{ route('login') }}" class="px-6 py-3 bg-green-600 rounded-md hover:bg-green-700 transition">
                    Booking Now
                </a>
            </div>
        </div>

    </section>

    <!-- PILIH LAPANGAN -->
    <!-- PILIH LAPANGAN -->
    <section class="bg-gray-100 py-24 text-black">
        <div class="max-w-6xl mx-auto px-6">

            {{-- SECTION HEADER --}}
            <div class="text-center mb-14">
                <h2 class="text-3xl font-bold mb-2">Pilih Lapangan</h2>
                <p class="text-gray-500 max-w-xl mx-auto">
                    Pilih lapangan yang tersedia sesuai kebutuhan dan jadwal bermain Anda
                </p>
            </div>

            @if ($lapangan->isEmpty())
                {{-- EMPTY STATE --}}
                <div class="flex justify-center">
                    <div class="bg-white rounded-2xl shadow-lg p-10 max-w-md text-center">
                        <div class="text-5xl mb-4">⚽</div>
                        <h3 class="text-xl font-semibold mb-2">
                            Lapangan Belum Tersedia
                        </h3>
                        <p class="text-gray-500 mb-6">
                            Saat ini belum ada lapangan yang bisa dipesan.
                        </p>
                        <a href="/" class="inline-block px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                            Kembali
                        </a>
                    </div>
                </div>
            @else
                {{-- CARD GRID --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach ($lapangan as $lp)
                        <div class="group bg-white rounded-2xl overflow-hidden border border-gray-100 transition-all duration-300 hover:-translate-y-1 hover:shadow-2xl">

                            {{-- IMAGE --}}
                            <div class="relative overflow-hidden">
                                <img src="{{ asset("storage/{$lp->gambar}") }}" alt="{{ $lp->nama_lapangan }}"
                                    class="w-full h-56 object-cover transition-transform duration-500 group-hover:scale-105">

                                {{-- STATUS BADGE --}}
                                <span class="absolute top-4 left-4 text-xs font-semibold px-4 py-1 rounded-full {{ $lp->status === 'tersedia' ? 'bg-green-600 text-white' : 'bg-red-600 text-white' }}">
                                    {{ ucfirst($lp->status) }}
                                </span>
                            </div>

                            {{-- CONTENT --}}
                            <div class="p-5 flex flex-col h-full">

                                <h3 class="text-lg font-semibold text-gray-900 mb-1">
                                    {{ $lp->nama_lapangan }}
                                </h3>

                                <p class="text-sm text-gray-500 mb-3">
                                    Jenis Lapangan • {{ $lp->jenisLapangan }}
                                </p>

                                <p class="text-blue-600 font-bold text-xl mb-3">
                                    Rp {{ number_format($lp->harga, 0, ',', '.') }}
                                    <span class="text-sm font-medium text-gray-500">/ jam</span>
                                </p>

                                <p class="text-sm text-gray-600 mb-5 line-clamp-3">
                                    {{ $lp->deskripsi }}
                                </p>

                                {{-- BUTTON --}}
                                <div class="mt-auto">
                                    @if ($lp->status === 'tersedia')
                                        <a href="{{ route('login') }}?region={{ $lp->region }}" class="block">
                                            <button class="w-full bg-blue-600 text-white py-2.5 rounded-xl font-medium transition hover:bg-blue-700">
                                                Pesan Sekarang
                                            </button>
                                        </a>
                                    @else
                                        <button class="w-full bg-gray-200 text-gray-500 py-2.5 rounded-xl cursor-not-allowed">
                                            Tidak Bisa Dipesan
                                        </button>
                                    @endif
                                </div>

                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

        </div>
    </section>


    <!-- PAYMENT & TRUST -->
    <section class="bg-white py-28 text-black overflow-hidden">
        <div class="max-w-6xl mx-auto px-6">
            <div class="grid md:grid-cols-2 gap-16 items-center">

                <!-- TEXT -->
                <div class="animate-fade-left">
                    <h2 class="text-3xl font-bold mb-6">
                        Pembayaran Aman & Terverifikasi
                    </h2>

                    <p class="text-gray-600 mb-8 leading-relaxed max-w-lg">
                        Semua transaksi booking lapangan diproses secara otomatis
                        menggunakan sistem pembayaran resmi yang telah digunakan
                        oleh ribuan bisnis di Indonesia.
                    </p>

                    <div class="space-y-4 text-gray-600 text-sm">
                        <div class="flex items-center gap-3">
                            <span class="text-green-600 font-bold">✓</span>
                            Mendukung transfer bank, e-wallet, dan QRIS
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="text-green-600 font-bold">✓</span>
                            Konfirmasi pembayaran instan & real-time
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="text-green-600 font-bold">✓</span>
                            Sistem keamanan berstandar industri
                        </div>
                    </div>
                </div>

                <!-- LOGO -->
                <div class="flex justify-center md:justify-end animate-fade-right" id="midtrans">
                    <img src="{{ asset('static/img/midtrans.png') }}" alt="Midtrans" class="w-[820px] md:w-[820px] object-contain
                transition-transform duration-700 ease-out
                hover:scale-105 blue-shadow">
                </div>


            </div>
        </div>
    </section>


    <footer class="bg-black border-t border-white/10 text-gray-400 py-8">
        <div class="max-w-6xl mx-auto px-6 flex flex-col md:flex-row justify-between items-center gap-4 text-sm">
            <p>© {{ date('Y') }} Booking Futsal. All rights reserved.</p>

            <div class="flex gap-6">
                <a href="#" class="hover:text-white transition">Tentang</a>
                <a href="#" class="hover:text-white transition">Kontak</a>
                <a href="#" class="hover:text-white transition">Kebijakan</a>
            </div>
        </div>
    </footer>



</body>

</html>