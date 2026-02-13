@extends('layout.layout')
@section('content')

<style>
    .slider-track {
        transition: transform 0.6s ease-in-out;
    }

    .dot.active {
        background-color: #0ea5e9;
        border-color: #0284c7;
        transform: scale(1.3);
        box-shadow: 0 0 10px rgba(14, 165, 233, 0.6);
    }

    .slider-btn {
        opacity: 0;
        transition: all 0.3s ease;
    }

    .slider-container:hover .slider-btn {
        opacity: 1;
    }

    .slider-btn:hover {
        background-color: #0ea5e9;
        color: white;
    }
</style>
</head>

<body class="bg-gray-50 font-sans">
    <nav
        class="bg-gradient-to-r from-gray-800 to-gray-900 p-4 sticky top-0 z-50 shadow-md flex justify-between items-center text-white">
        <div class="flex items-center gap-2">
            <img src="{{ asset('static/img/logo.png') }}"
                alt="Logo" class="w-12 h-12 rounded-full">
            <span class="font-semibold text-lg">Futsal Booking</span>
        </div>

        <ul class="flex space-x-8">
            <li><a href="#" class="text-gray-300 hover:text-blue-400">Beranda</a></li>
            <li><a href="#scheduleSection" class="text-gray-300 hover:text-blue-400">Jadwal</a></li>
            <li><a href="#lapanganUnggulan" class="text-gray-300 hover:text-blue-400">Pesanan</a></li>
            <li><a href="#event" class="text-gray-300 hover:text-blue-400">Event</a></li>
            <li><a href="#footer" class="text-gray-300 hover:text-blue-400">Kontak</a></li>
        </ul>

        <!-- Navbar User Section -->
        <div class="flex items-center gap-4">
            @guest
            <!-- KALAU BELUM LOGIN -->
            <div class="flex gap-3">
                <a href="{{ route('login') }}"
                    class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition">
                    Login
                </a>

                <a href="{{ route('register') }}"
                    class="px-4 py-2 bg-gray-700 text-white rounded-lg hover:bg-gray-800 transition">
                    Register
                </a>
            </div>
            @endguest

            @auth
            <!-- KALAU SUDAH LOGIN -->
            <div class="relative" id="profileDropdown">
                <!-- PROFILE BUTTON -->
                <button id="profileBtn"
                    class="flex items-center gap-2 bg-gray-800 bg-opacity-50 p-2 rounded-lg backdrop-blur hover:bg-gray-700 transition">

                    <img src="{{ Auth::user()->profile ?? 'https://ui-avatars.com/api/?name=' . Auth::user()->name }}"
                        alt="User" class="w-10 h-10 rounded-full border-2 border-blue-400">

                    <div class="flex flex-col text-left">
                        <p class="font-semibold text-sm">{{ Auth::user()->name }}</p>
                        <p class="text-xs text-gray-400">Pengguna</p>
                    </div>
                </button>

                <!-- DROPDOWN MENU -->
                <div id="dropdownMenu"
                    class="hidden absolute right-0 mt-2 w-48 bg-white shadow-lg rounded-lg overflow-hidden z-50">

                    <a href="{{ route('show.payment') }}"
                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition">
                        Riwayat Booking
                    </a>

                    <a href="/edit-profile"
                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition">
                        Edit Profil
                    </a>

                    <hr class="my-2">

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="w-full text-left px-4 py-2 text-sm text-red-500 hover:bg-red-100 transition">
                            Logout
                        </button>
                    </form>
                </div>
            </div>

            @endauth
        </div>

    </nav>

    <!-- Region Selector Bar -->
    <div class="bg-gradient-to-r from-blue-600 to-blue-800 px-6 py-3 shadow-md sticky top-16 z-40">
        <div class="max-w-7xl mx-auto flex items-center justify-between">
            <span class="text-white font-semibold">Pilih Region:</span>
            <div class="flex gap-2">
                <a href="{{ route('costumers.dashboard.bypass') }}" 
                    class="px-4 py-2 rounded-lg font-medium transition {{ strpos(Route::currentRouteName(), 'bypass') !== false ? 'bg-white text-blue-600' : 'bg-blue-700 text-white hover:bg-blue-600' }}">
                    Bypass
                </a>
                <a href="{{ route('costumers.dashboard.ulakkarang') }}" 
                    class="px-4 py-2 rounded-lg font-medium transition {{ strpos(Route::currentRouteName(), 'ulakkarang') !== false ? 'bg-white text-blue-600' : 'bg-blue-700 text-white hover:bg-blue-600' }}">
                    Ulak Karang
                </a>
                <a href="{{ route('costumers.dashboard.batusangkar') }}" 
                    class="px-4 py-2 rounded-lg font-medium transition {{ strpos(Route::currentRouteName(), 'batusangkar') !== false ? 'bg-white text-blue-600' : 'bg-blue-700 text-white hover:bg-blue-600' }}">
                    Batu Sangkar
                </a>
            </div>
        </div>
    </div>

    <main class="min-h-screen bg-gray-100">
        <section class="mt-8 mb-8 px-4">
            <div class="relative w-11/12 mx-auto overflow-hidden rounded-lg shadow-lg slider-container mt-8">
                <button onclick="moveSlide(-1)" class="slider-btn absolute left-4 top-1/2  bg-white text-gray-800 rounded-full w-12 h-12 flex items-center justify-center font-bold text-xl z-10">❮</button>
                <ul class="flex slider-track" id="sliderTrack">
                    @foreach ($sliders as $slide)
                    <li class="min-w-full">
                        <img src="{{ asset('storage/' . $slide->gambar) }}"
                            alt="Futsal Banner 1" class="w-full h-[600px] object-cover">
                    </li>
                    @endforeach
                </ul>
                <button onclick="moveSlide(1)" class="slider-btn absolute right-4 top-1/2  bg-white text-gray-800 rounded-full w-12 h-12 flex items-center justify-center font-bold text-xl z-10">❯</button>
            </div>

            <div class="flex justify-center gap-3 mt-6">
                <span
                    class="dot active w-3 h-3 rounded-full bg-gray-600 border-2 border-gray-800 cursor-pointer transition-all"
                    onclick="goToSlide(0)"></span>
                <span
                    class="dot w-3 h-3 rounded-full bg-gray-600 border-2 border-gray-800 cursor-pointer transition-all"
                    onclick="goToSlide(1)"></span>
            </div>
        </section>


        <!-- Schedule Section -->
        <section class="max-w-5xl mx-auto py-12 px-4" id="scheduleSection">
            <h2 class="text-3xl text-gray-800 font-bold text-center mb-8">Jadwal Lapangan</h2>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Calendar -->
                <div class="lg:col-span-2 bg-white rounded-lg shadow-lg p-6">
                    <div class="flex justify-between items-center mb-6">
                        <button onclick="prevMonth()"
                            class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">← Sebelumnya</button>
                        <h3 class="text-2xl font-bold text-gray-800" id="monthYear"></h3>
                        <button onclick="nextMonth()"
                            class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">Selanjutnya →</button>
                    </div>

                    <div class="grid grid-cols-7 gap-2 mb-4">
                        <div class="text-center font-bold text-gray-700">Min</div>
                        <div class="text-center font-bold text-gray-700">Sen</div>
                        <div class="text-center font-bold text-gray-700">Sel</div>
                        <div class="text-center font-bold text-gray-700">Rab</div>
                        <div class="text-center font-bold text-gray-700">Kam</div>
                        <div class="text-center font-bold text-gray-700">Jum</div>
                        <div class="text-center font-bold text-gray-700">Sab</div>
                    </div>

                    <div class="grid grid-cols-7 gap-2" id="calendarGrid"></div>
                </div>

                <!-- Legend & Details -->
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <h3 class="text-xl font-bold text-gray-800 mb-4">Keterangan</h3>
                    <div class="space-y-4">
                        <div class="flex items-center gap-3">
                            <div class="w-6 h-6 bg-green-500 rounded"></div>
                            <p class="text-gray-700">Lapangan Kosong</p>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="w-6 h-6 bg-red-500 rounded"></div>
                            <p class="text-gray-700">Lapangan Terpakai</p>
                        </div>
                    </div>

                    <div class="mt-6 border-t pt-4">
                        <h4 class="font-bold text-gray-800 mb-3">Detail Tanggal</h4>
                        <div id="dateDetail" class="text-sm text-gray-600 space-y-2">
                            <p>Pilih tanggal untuk melihat detail</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- Featured Venues Section -->
        <section class="max-w-6xl mx-auto py-12" id="lapanganUnggulan">
            <h2 class="text-3xl text-gray-800 font-bold text-center mb-8">
                Lapangan Unggulan
            </h2>

            @if ($lapangan->isEmpty())
            {{-- EMPTY STATE --}}
            <div class="min-h-[50vh] flex items-center justify-center px-4">
                <div class="max-w-md w-full text-center bg-white rounded-2xl shadow-lg p-8 border">
                    <div class="flex justify-center mb-4">
                        <div class="w-16 h-16 flex items-center justify-center rounded-full bg-blue-100 text-blue-600">
                            ⚽
                        </div>
                    </div>

                    <h1 class="text-2xl font-semibold text-gray-800 mb-2">
                        Lapangan Belum Tersedia
                    </h1>

                    <p class="text-gray-500 mb-6">
                        Admin belum menambahkan data lapangan untuk region ini.
                    </p>

                    <a href="/" class="px-5 py-2 rounded-lg bg-blue-600 text-white text-sm hover:bg-blue-700">
                        Kembali
                    </a>
                </div>
            </div>
            @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($lapangan as $lp)
                <div class="bg-white rounded-xl overflow-hidden shadow-md hover:shadow-xl transition">

                    {{-- IMAGE --}}
                    <div class="relative">
                        <img
                            src="{{ asset('storage/' . $lp->gambar) }}"
                            class="w-full h-52 object-cover"
                            alt="{{ $lp->namaLapangan }}">

                        {{-- STATUS BADGE --}}
                        @if ($lp->status === 'tersedia')
                        <span class="absolute top-3 left-3 bg-green-600 text-white text-xs px-3 py-1 rounded-full">
                            Tersedia
                        </span>
                        @else
                        <span class="absolute top-3 left-3 bg-red-600 text-white text-xs px-3 py-1 rounded-full">
                            Tidak Tersedia
                        </span>
                        @endif
                    </div>

                    {{-- CONTENT --}}
                    <div class="p-4">
                        <h3 class="text-lg font-semibold text-gray-800">
                            {{ $lp->namaLapangan }}
                        </h3>

                        <p class="text-gray-500 text-sm mb-2">
                            Jenis: {{ $lp->jenisLapangan }}
                        </p>

                        <p class="text-blue-600 font-bold mb-4">
                            Rp {{ number_format($lp->harga, 0, ',', '.') }}/jam
                        </p>
                        <i>
                            <p class="text-gray-600 text-sm mb-4 h-16 overflow-hidden text-ellipsis">
                                {{ Str::limit($lp->deskripsi, 100, '...') }}
                            </p>
                        </i>

                        {{-- BUTTON --}}
                        @if ($lp->status === 'tersedia')
                        <a href="{{ route('boking.form') }}?region=bypass&lapangan_id={{ $lp->id }}">
                            <button
                                class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition">
                                Pesan Sekarang
                            </button>
                        </a>
                        @else
                        <button
                            class="w-full bg-gray-300 text-gray-600 py-2 rounded-lg cursor-not-allowed">
                            Tidak Bisa Dipesan
                        </button>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
            @endif
        </section>



        <!-- Latest Events Section -->
        <section class="max-w-5xl mx-auto py-12 px-4" id="event">
            <h2 class="text-3xl text-gray-800 font-bold text-center ">Event Terbaru</h2>
            <div class="space-y-6 ">
                @foreach ($event as $ev)
                <div
                    class="bg-white shadow-md hover:shadow-xl transition-shadow rounded-lg overflow-hidden flex w-full">
                    <!-- FOTO (KIRI) -->
                    <div class="relative w-[400px] h-[200px]">
                        <img src="{{ asset('storage/' . $ev->gambar) }}" alt="{{ $ev->judul }}"
                            class="w-full h-full object-cover">

                        <span
                            class="absolute top-3 right-3 bg-yellow-500 text-white px-3 py-1 rounded-full text-sm font-semibold">
                            Berlangsung
                        </span>
                    </div>

                    <!-- KETERANGAN (KANAN) -->
                    <div class="p-6 w-full flex flex-col justify-between">
                        <div class="flex flex-col w-100%">
                            <h3 class="text-2xl font-bold text-teal-500 mb-3">{{ $ev->judul }} </h3>
                            <div class="space-y-2 text-sm text-gray-600 mb-4">
                                <div class="flex items-center gap-2">📅 <span>{{ $ev->tanggal_mulai }} - {{ $ev->tanggal_selesai }}</span></div>
                            </div>
                            <p class="text-gray-600 text-sm text-justify leading-relaxed">
                                {{ Str::limit($ev->deskripsi, 150, '...') }}
                            </p>
                            <a href="#" class="text-teal-500 hover:underline">Selengkapnya →</a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </section>
    </main>


    <!-- Contact Form Section -->
    <section class="max-w-6xl mx-auto py-16 px-5 sm:px-6 lg:px-8 bg-gray-50" id="contact">
        <h2 class="text-4xl md:text-5xl font-extrabold text-gray-900 text-center mb-12 md:mb-16 tracking-tight">
            Hubungi Kami
        </h2>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 xl:gap-12">

            <!-- KIRI: Informasi Kontak -->
            <div class="space-y-10">

                <div class="flex items-start gap-6">
                    <div class="flex-shrink-0 w-14 h-14 bg-gradient-to-br from-blue-600 to-blue-700 shadow-lg rounded-2xl flex items-center justify-center text-white text-3xl">
                        📍
                    </div>
                    <div>
                        <h3 class="text-xl font-semibold text-gray-800">Lokasi</h3>
                        <p class="mt-2 text-gray-600 leading-relaxed">
                            Jl. Merdeka No. 45<br>
                            Jakarta Pusat, Indonesia 12190
                        </p>
                    </div>
                </div>

                <div class="flex items-start gap-6">
                    <div class="flex-shrink-0 w-14 h-14 bg-gradient-to-br from-blue-600 to-blue-700 shadow-lg rounded-2xl flex items-center justify-center text-white text-3xl">
                        📞
                    </div>
                    <div>
                        <h3 class="text-xl font-semibold text-gray-800">Telepon & WhatsApp</h3>
                        <p class="mt-2 text-gray-600">
                            +62 (21) 234-5678<br>
                            WhatsApp: +62 812-3456-7890
                        </p>
                    </div>
                </div>

                <div class="flex items-start gap-6">
                    <div class="flex-shrink-0 w-14 h-14 bg-gradient-to-br from-blue-600 to-blue-700 shadow-lg rounded-2xl flex items-center justify-center text-white text-3xl">
                        ✉️
                    </div>
                    <div>
                        <h3 class="text-xl font-semibold text-gray-800">Email</h3>
                        <p class="mt-2 text-gray-600">
                            info@futsalbooking.com<br>
                            support@futsalbooking.com
                        </p>
                    </div>
                </div>

                <div class="flex items-start gap-6">
                    <div class="flex-shrink-0 w-14 h-14 bg-gradient-to-br from-blue-600 to-blue-700 shadow-lg rounded-2xl flex items-center justify-center text-white text-3xl">
                        🕐
                    </div>
                    <div>
                        <h3 class="text-xl font-semibold text-gray-800">Jam Operasional</h3>
                        <p class="mt-2 text-gray-600">
                            Senin – Jumat: 08:00 – 22:00 WIB<br>
                            Sabtu – Minggu: 07:00 – 23:00 WIB
                        </p>
                    </div>
                </div>

            </div>

            <!-- KANAN: Google Maps -->
            <div class="bg-white/80 backdrop-blur-md shadow-2xl p-6 md:p-8 rounded-3xl border border-gray-100 overflow-hidden">
                <div class="aspect-video w-full h-96 rounded-xl overflow-hidden shadow-inner">
                    <iframe
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3989.328056941176!2d100.39224567496507!3d-0.8982148990930895!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2fd4b88786b63523%3A0x592d1b37d6ceb9b!2sRafhely%20Futsal%20Bypass!5e0!3m2!1sid!2sid!4v1769849530687!5m2!1sid!2sid"
                        width="100%"
                        height="100%"
                        style="border:0;"
                        allowfullscreen=""
                        loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade">
                    </iframe>
                </div>
            </div>

        </div>
    </section>



    <div class="fixed bottom-6 right-6 z-50 flex flex-col items-end">

        <!-- MENU (ABSOLUTE, TIDAK MENGGESER ICON) -->
        <div id="wa-menu"
            class="hidden absolute bottom-20 right-0 w-72 rounded-2xl shadow-2xl bg-white overflow-hidden border border-gray-200">

            <!-- Header -->
            <div class="bg-emerald-600 text-white px-5 py-4">
                <h4 class="font-semibold text-lg">Customer Service</h4>
                <p class="text-sm opacity-90">Silakan hubungi kami</p>
            </div>

            <!-- List CS -->
            <div class="p-4 space-y-3">

                <a href="https://wa.me/6281234567890" target="_blank"
                    class="flex items-center gap-3 p-3 rounded-xl hover:bg-gray-100 transition">
                    <div class="w-10 h-10 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center font-bold">
                        CS
                    </div>
                    <div>
                        <p class="font-semibold text-gray-800">Admin Booking</p>
                        <p class="text-sm text-gray-500">Online</p>
                    </div>
                </a>
            </div>
        </div>

        <!-- BUTTON (POSISI TETAP) -->
        <button onclick="toggleWhatsApp()"
            class="w-16 h-16 rounded-full bg-emerald-600 hover:bg-emerald-700 shadow-2xl flex items-center justify-center text-white text-3xl transition">

            <!-- Icon WA -->
            <svg class="w-9 h-9" viewBox="0 0 24 24" fill="currentColor">
                <path d="M12.04 2C6.58 2 2.14 6.44 2.14 11.9c0 1.7.44 3.36 1.27 4.82L2 22l5.38-1.4a9.84 9.84 0 0 0 4.66 1.2h.01c5.46 0 9.9-4.44 9.9-9.9S17.5 2 12.04 2z" />
            </svg>
        </button>

    </div>
    </div>



    <!-- Footer -->
    <footer class="bg-gradient-to-b from-gray-950 to-black text-gray-400">
        <div class="max-w-7xl mx-auto px-6 sm:px-8 lg:px-10 py-16 lg:py-20">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-12 gap-10 lg:gap-12">

                <!-- Kolom 1: Brand & About (lg:col-span-4) -->
                <div class="lg:col-span-4 space-y-6">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl flex items-center justify-center text-white font-bold text-xl shadow-lg">
                            FB
                        </div>
                        <h3 class="text-2xl font-bold text-white tracking-tight">Futsal Booking</h3>
                    </div>
                    <p class="text-gray-400 leading-relaxed">
                        Platform terpercaya untuk reservasi lapangan futsal berkualitas di seluruh Indonesia. Mudah, cepat, dan aman.
                    </p>
                    <div class="flex gap-5">
                        <a href="#" class="text-gray-400 hover:text-white transition-colors">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M22.675 0h-21.35c-.732 0-1.325.593-1.325 1.325v21.351c0 .731.593 1.324 1.325 1.324h11.495v-9.294h-3.128v-3.622h3.128v-2.671c0-3.1 1.893-4.788 4.659-4.788 1.325 0 2.463.099 2.795.143v3.24l-1.918.001c-1.504 0-1.795.715-1.795 1.763v2.313h3.587l-.467 3.622h-3.12v9.293h6.116c.73 0 1.323-.593 1.323-1.325v-21.35c0-.732-.593-1.325-1.325-1.325z" />
                            </svg>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white transition-colors">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z" />
                            </svg>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white transition-colors">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z" />
                            </svg>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white transition-colors">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12.017 0c-6.338 0-11.667 4.923-11.667 11.691 0 6.768 5.329 11.691 11.667 11.691 6.338 0 11.668-4.923 11.668-11.691 0-6.768-5.33-11.691-11.668-11.691zm3.625 8.931c-2.671 5.243-8.5 4.06-8.5 4.06v-3.5c1.5 0 2.5-1 2.5-2.5 0-1.5-1-2.5-2.5-2.5h-3.5v11.5h3.5c1.5 0 2.5-1 2.5-2.5 0-1.5-1-2.5-2.5-2.5v-3.5c4.5 0 7.5 3.5 7.5 7.5s-3 7.5-7.5 7.5h-3.5v-3.5h3.5c2.5 0 4.5-2 4.5-4.5s-2-4.5-4.5-4.5z" />
                            </svg>
                        </a>
                    </div>
                </div>

                <!-- Kolom 2: Navigasi -->
                <div class="lg:col-span-2">
                    <h4 class="text-lg font-semibold text-white mb-5">Navigasi</h4>
                    <ul class="space-y-3">
                        <li><a href="#beranda" class="hover:text-emerald-400 transition-colors">Beranda</a></li>
                        <li><a href="#jadwal" class="hover:text-emerald-400 transition-colors">Jadwal Lapangan</a></li>
                        <li><a href="#pesanan" class="hover:text-emerald-400 transition-colors">Pesanan Saya</a></li>
                        <li><a href="#event" class="hover:text-emerald-400 transition-colors">Event & Turnamen</a></li>
                    </ul>
                </div>

                <!-- Kolom 3: Hubungi Kami -->
                <div class="lg:col-span-3">
                    <h4 class="text-lg font-semibold text-white mb-5">Hubungi Kami</h4>
                    <ul class="space-y-3">
                        <li class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            </svg>
                            <span>Jl. Merdeka No. 45, Jakarta Pusat</span>
                        </li>
                        <li><a href="tel:+62212345678" class="hover:text-emerald-400 transition-colors">+62 (21) 234-5678</a></li>
                        <li><a href="https://wa.me/6289508839313" class="hover:text-emerald-400 transition-colors">WhatsApp: +62 895-0883-9313</a></li>
                        <li><a href="mailto:info@futsalbooking.com" class="hover:text-emerald-400 transition-colors">info@futsalbooking.com</a></li>
                    </ul>
                </div>

                <!-- Kolom 4: Newsletter (elegan & optional) -->
                <div class="lg:col-span-3">
                    <h4 class="text-lg font-semibold text-white mb-5">Newsletter</h4>
                    <p class="text-gray-400 mb-4">Dapatkan update jadwal & promo terbaru langsung ke emailmu.</p>
                    <form class="flex flex-col sm:flex-row gap-3">
                        <input
                            type="email"
                            placeholder="Email kamu"
                            class="flex-1 px-4 py-3 bg-gray-800 border border-gray-700 rounded-lg focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 text-white placeholder-gray-500">
                        <button
                            type="submit"
                            class="px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-medium rounded-lg transition-colors shadow-md">
                            Subscribe
                        </button>
                    </form>
                </div>
            </div>

            <!-- Bottom bar -->
            <div class="mt-16 pt-8 border-t border-gray-800 text-center text-sm text-gray-500">
                <p>© 2025 Futsal Booking. Semua hak dilindungi.</p>
                <div class="mt-3 flex justify-center gap-6">
                    <a href="#" class="hover:text-gray-300 transition-colors">Kebijakan Privasi</a>
                    <a href="#" class="hover:text-gray-300 transition-colors">Syarat & Ketentuan</a>
                </div>
            </div>
        </div>
    </footer>
</body>

<script>
    // ✅ Pass region info untuk API fetch data real-time
    const region = '{{ $region ?? "bypass" }}';
</script>

<script src="{{ asset('static/js/index.js') }}"></script>
<script>
    function toggleWhatsApp() {
        const menu = document.getElementById('wa-menu');
        menu.classList.toggle('hidden');
    }

    // Tutup menu jika klik di luar area WA
    document.addEventListener('click', function(e) {
        const menu = document.getElementById('wa-menu');
        const waWrapper = document.querySelector('.fixed.bottom-6.right-6');

        if (!waWrapper.contains(e.target)) {
            menu.classList.add('hidden');
        }
    });
</script>



@endsection