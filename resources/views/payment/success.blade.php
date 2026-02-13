
@extends('layout.layout')
@section("content")
<div class="min-h-screen bg-slate-50 py-14 px-4">
  <div class="max-w-2xl mx-auto">

    <!-- Success Card -->
    <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-slate-200">

      <!-- Header -->
      <div class="px-8 py-12 text-center bg-slate-900">
        <div class="mx-auto mb-5 flex items-center justify-center w-16 h-16 rounded-full bg-emerald-500">
          <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd"
              d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
              clip-rule="evenodd" />
          </svg>
        </div>
        <h1 class="text-3xl font-extrabold text-white mb-2">
          Pembayaran Berhasil
        </h1>
        <p class="text-slate-300 text-lg">
          Booking futsal Anda telah dikonfirmasi
        </p>
      </div>

      <!-- Content -->
      <div class="px-8 py-10 space-y-10">

        <!-- Info -->
        <div class="bg-slate-100 border border-slate-200 rounded-xl p-5 text-sm text-slate-700">
          <strong class="text-slate-900">Terima kasih.</strong>
          Pembayaran Anda telah diterima. Detail booking dapat dilihat di bawah atau melalui dashboard Anda.
        </div>

        <!-- Order Detail -->
        <div>
          <h2 class="text-lg font-bold text-slate-900 mb-4">Detail Pesanan</h2>

          <div class="bg-slate-50 rounded-xl border border-slate-200 divide-y">
            <div class="flex justify-between px-5 py-3">
              <span class="text-slate-500">Order ID</span>
              <span class="font-semibold text-slate-900">{{ $orderId ?? '-' }}</span>
            </div>

            @if($booking)
            <div class="flex justify-between px-5 py-3">
              <span class="text-slate-500">Status</span>
              <span class="inline-flex items-center gap-1 text-emerald-700 font-semibold">
                ✔ {{ ucfirst($booking->status) }}
              </span>
            </div>
            <div class="flex justify-between px-5 py-3">
              <span class="text-slate-500">Lapangan</span>
              <span class="font-semibold">{{ $booking->lapangan }}</span>
            </div>
            <div class="flex justify-between px-5 py-3">
              <span class="text-slate-500">Tanggal</span>
              <span class="font-semibold">
                {{ \Carbon\Carbon::parse($booking->tanggal)->format('d M Y') }}
              </span>
            </div>
            <div class="flex justify-between px-5 py-3">
              <span class="text-slate-500">Jam</span>
              <span class="font-semibold">
                {{ $booking->jam_mulai }} - {{ $booking->jam_selesai }}
              </span>
            </div>
            @endif
          </div>
        </div>

        <!-- Payment History -->
        @if($payment)
        <div>
          <h2 class="text-lg font-bold text-slate-900 mb-4">Informasi Pembayaran</h2>

          <div class="bg-slate-50 rounded-xl border border-slate-200 divide-y">
            <div class="flex justify-between px-5 py-3 text-sm">
              <span class="text-slate-500">Transaction ID</span>
              <span class="font-mono text-slate-800">{{ $payment->transaction_id ?? 'N/A' }}</span>
            </div>
            <div class="flex justify-between px-5 py-3 text-sm">
              <span class="text-slate-500">Status</span>
              <span class="font-semibold text-blue-700">
                {{ ucfirst($payment->payment_status) }}
              </span>
            </div>
            <div class="flex justify-between px-5 py-3 text-sm">
              <span class="text-slate-500">Jumlah</span>
              <span class="font-semibold">
                Rp {{ number_format($payment->amount, 0, ',', '.') }}
              </span>
            </div>
            <div class="flex justify-between px-5 py-3 text-sm">
              <span class="text-slate-500">Metode</span>
              <span class="font-semibold">
                {{ ucfirst(str_replace('_', ' ', $payment->payment_method ?? 'N/A')) }}
              </span>
            </div>
            @if($payment->payment_at)
            <div class="flex justify-between px-5 py-3 text-sm">
              <span class="text-slate-500">Waktu</span>
              <span class="font-semibold">
                {{ $payment->payment_at->format('d M Y H:i') }}
              </span>
            </div>
            @endif
          </div>
        </div>
        @endif

        <!-- Next Steps -->
        <div>
          <h2 class="text-lg font-bold text-slate-900 mb-4">Langkah Selanjutnya</h2>
          <ul class="space-y-3 text-sm text-slate-700">
            <li>✔ Email konfirmasi akan dikirim ke akun Anda</li>
            <li>✔ Datang 15 menit sebelum jadwal</li>
            <li>✔ Tunjukkan invoice saat check-in</li>
          </ul>
        </div>

        <!-- Actions -->
        <div class="flex gap-4 pt-4">
          <a href="{{ route('boking.form') }}"
            class="flex-1 bg-slate-900 hover:bg-slate-800 text-white font-semibold py-3 rounded-lg text-center">
            Pesan Lagi
          </a>

          <a
            @php
              $region = strtolower(auth()->user()->region ?? '');
              $regionDashboardRoutes = [
                'bypass' => 'costumers.dashboard.bypass',
                'ulakkarang' => 'costumers.dashboard.ulakkarang',
                'batusangkar' => 'costumers.dashboard.batusangkar',
              ];
              $dashboardRoute = $regionDashboardRoutes[$region] ?? 'costumers.dashboard.bypass';
            @endphp
            href="{{ route($dashboardRoute) }}"
            class="flex-1 border border-slate-300 hover:bg-slate-100 font-semibold py-3 rounded-lg text-center">
            Kembali ke Beranda
          </a>
        </div>
      </div>
    </div>

    <!-- Contact -->
    <div class="mt-10 text-center text-sm text-slate-600">
      Butuh bantuan? <br>
      <span class="font-semibold text-slate-900">
        0812-3456-7890 · support@futsal.com
      </span>
    </div>
  </div>
</div>
@endsection
