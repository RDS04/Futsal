@extends('layout.layout')
@section('content')

<!-- Invoice & Riwayat Booking – Modern Professional Redesign -->
<div class="min-h-screen bg-slate-50 py-20 px-4">
    <div class="max-w-7xl mx-auto">

        <!-- Header -->
        <div class="text-center mb-16">
            <h2 class="text-4xl font-extrabold text-slate-900 mb-3">
                Riwayat Booking
            </h2>
            <p class="text-slate-500 text-lg">
                Pantau dan kelola seluruh transaksi lapangan futsal Anda
            </p>
            <div class="w-20 h-1 bg-slate-900 mx-auto mt-6 rounded-full"></div>
        </div>

        @if($allBookings && $allBookings->count() > 0)

        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-8">
            @foreach($allBookings as $booking)

            <div class="relative bg-white rounded-2xl shadow-sm hover:shadow-lg transition-all duration-300 border border-slate-200 overflow-hidden">

                <!-- Status Badge -->
                <div class="absolute top-4 right-4 z-20">
                    @if($booking->status === 'paid')
                        <span class="px-3 py-1 text-xs font-semibold rounded-full bg-emerald-100 text-emerald-700">
                            ✔ Terbayar
                        </span>
                    @else
                        <span class="px-3 py-1 text-xs font-semibold rounded-full bg-amber-100 text-amber-700">
                            ⏳ Pending
                        </span>
                    @endif
                </div>

                <!-- Watermark -->
                <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
                    <span class="text-7xl font-black text-slate-100 rotate-[-30deg] select-none">
                        RAFHELY
                    </span>
                </div>

                <!-- Content -->
                <div class="relative z-10 p-7 space-y-6">

                    <!-- Order -->
                    <div>
                        <p class="text-xs text-slate-400 uppercase tracking-wider">Order ID</p>
                        <p class="font-mono text-sm text-slate-700">
                            {{ substr($booking->order_id, 0, 20) }}
                        </p>
                    </div>

                    <!-- Lapangan -->
                    <div>
                        <p class="text-xs text-slate-400 uppercase tracking-wider mb-1">Lapangan</p>
                        <p class="text-xl font-bold text-slate-900">
                            {{ $booking->lapangan }}
                        </p>
                    </div>

                    <!-- Detail -->
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <p class="text-slate-400">Tanggal</p>
                            <p class="font-semibold text-slate-800">
                                {{ \Carbon\Carbon::parse($booking->tanggal)->format('d M Y') }}
                            </p>
                        </div>
                        <div>
                            <p class="text-slate-400">Waktu</p>
                            <p class="font-semibold text-slate-800">
                                {{ $booking->jam_mulai }} - {{ $booking->jam_selesai }}
                            </p>
                        </div>
                        <div>
                            <p class="text-slate-400">Durasi</p>
                            <p class="font-semibold text-slate-800">
                                {{ (int)$booking->durasi }} jam
                            </p>
                        </div>
                        <div>
                            <p class="text-slate-400">Region</p>
                            <p class="font-semibold text-slate-800 capitalize">
                                @if($booking->region === 'bypass')
                                    Bypass
                                @elseif($booking->region === 'ulakkarang')
                                    Ulak Karang
                                @elseif($booking->region === 'batusangkar')
                                    Batu Sangkar
                                @else
                                    {{ $booking->region }}
                                @endif
                            </p>
                        </div>
                    </div>

                    <!-- Total -->
                    <div class="bg-slate-100 rounded-xl p-4">
                        <p class="text-xs text-slate-500 uppercase tracking-wider">
                            Total Pembayaran
                        </p>
                        <p class="text-2xl font-extrabold text-slate-900">
                            Rp {{ number_format($booking->total_harga, 0, ',', '.') }}
                        </p>
                    </div>

                    <!-- Payment Info -->
                    @if($booking->payment)
                    <div class="pt-4 border-t border-slate-200 space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-slate-500">Status</span>
                            <span class="font-semibold">
                                {{ ucfirst(str_replace('_', ' ', $booking->payment->payment_status)) }}
                            </span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-500">Metode</span>
                            <span class="font-semibold capitalize">
                                {{ $booking->payment->payment_method ?? '-' }}
                            </span>
                        </div>
                        @if($booking->payment->payment_at)
                        <div class="flex justify-between">
                            <span class="text-slate-500">Tanggal Bayar</span>
                            <span class="font-semibold">
                                {{ \Carbon\Carbon::parse($booking->payment->payment_at)->format('d M Y') }}
                            </span>
                        </div>
                        @endif
                    </div>
                    @endif

                    <!-- Catatan -->
                    @if($booking->catatan)
                    <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 text-sm">
                        <p class="font-semibold text-blue-700 mb-1">Catatan</p>
                        <p class="text-blue-900">{{ $booking->catatan }}</p>
                    </div>
                    @endif
                </div>

                <!-- Actions -->
                <div class="relative z-10 bg-slate-100 p-5 border-t border-slate-200">
                    @if($booking->status === 'paid')
                        <div class="grid grid-cols-2 gap-3">
                            <a href="{{ route('payment.invoice.show', $booking->id) }}" target="_blank"
                               class="py-2.5 text-center bg-slate-900 text-white rounded-lg font-semibold hover:bg-slate-800">
                                Lihat Invoice
                            </a>
                            <a href="{{ route('payment.invoice.download', $booking->id) }}" download
                               class="py-2.5 text-center bg-white border border-slate-300 rounded-lg font-semibold hover:bg-slate-50">
                                Unduh
                            </a>
                        </div>
                    @else
                        <button onclick="openPaymentModal({
                            order_id: '{{ $booking->order_id }}',
                            lapangan: '{{ $booking->lapangan }}',
                            tanggal: '{{ $booking->tanggal }}',
                            jam_mulai: '{{ $booking->jam_mulai }}',
                            jam_selesai: '{{ $booking->jam_selesai }}',
                            durasi: {{ $booking->durasi }},
                            harga_per_jam: {{ $booking->harga_per_jam }},
                            total_harga: {{ $booking->total_harga }}
                        })"
                        class="w-full py-3 bg-slate-900 text-white rounded-lg font-semibold hover:bg-slate-800">
                            Lanjutkan Pembayaran
                        </button>
                    @endif
                </div>
            </div>

            @endforeach
        </div>

        @endif
    </div>
</div>
@endsection