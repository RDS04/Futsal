@extends('layout.layout')
@section('content')

<div class="min-h-screen bg-gray-50 py-12 px-4">
    <div class="max-w-3xl mx-auto">
        <!-- Back Button -->
        <a href="javascript:history.back()" class="text-gray-600 hover:text-gray-900 mb-6 inline-flex items-center gap-2 font-medium">
            ← Kembali
        </a>

        <!-- Invoice Card -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
            <!-- Header -->
            <div class="bg-white border-b border-gray-200 p-8">
                <div class="flex justify-between items-start mb-6">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900 mb-1">INVOICE</h1>
                        <p class="text-gray-600 text-sm">{{ config('app.name', 'Futsal Booking') }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-gray-600 text-sm mb-1">Invoice #</p>
                        <p class="text-xl font-bold text-gray-900 font-mono">{{ $booking->order_id }}</p>
                    </div>
                </div>

                <!-- Status Badge -->
                <div class="inline-flex items-center gap-2 bg-green-50 border border-green-200 text-green-800 px-3 py-1.5 rounded-lg text-sm font-semibold">
                    ✓ PAYMENT CONFIRMED
                </div>
            </div>

            <!-- Body -->
            <div class="p-8 space-y-8">
                <!-- Customer & Payment Info -->
                <div class="grid grid-cols-2 gap-8">
                    <!-- Customer Info -->
                    <div>
                        <h3 class="text-xs font-semibold text-gray-500 mb-3 uppercase tracking-wide">Customer Information</h3>
                        <div class="space-y-1">
                            <p class="font-semibold text-gray-900">{{ $booking->nama }}</p>
                            <p class="text-gray-600 text-sm">{{ Auth::user()->email ?? '-' }}</p>
                            <p class="text-gray-600 text-sm">{{ Auth::user()->phone ?? '-' }}</p>
                            <p class="text-gray-600 text-sm capitalize">Region: {{ $booking->region }}</p>
                        </div>
                    </div>

                    <!-- Payment Info -->
                    <div>
                        <h3 class="text-xs font-semibold text-gray-500 mb-3 uppercase tracking-wide">Payment Information</h3>
                        <div class="space-y-1 text-sm">
                            @if($booking->payment)
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Date:</span>
                                    <span class="font-semibold text-gray-900">{{ $booking->payment->payment_at ? \Carbon\Carbon::parse($booking->payment->payment_at)->format('d M Y H:i') : '-' }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Method:</span>
                                    <span class="font-semibold text-gray-900 capitalize">{{ $booking->payment->payment_method ?? '-' }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Transaction ID:</span>
                                    <span class="font-mono text-xs font-semibold text-gray-900">{{ $booking->payment->transaction_id ?? '-' }}</span>
                                </div>
                            @else
                                <p class="text-gray-500">No payment data found</p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Booking Details -->
                <div class="border-t pt-8">
                    <h3 class="text-xs font-semibold text-gray-500 mb-4 uppercase tracking-wide">Booking Details</h3>
                    <div class="space-y-2">
                        <div class="flex justify-between py-2.5 border-b border-gray-200">
                            <span class="text-gray-600">Field</span>
                            <span class="font-semibold text-gray-900">{{ $booking->lapangan }}</span>
                        </div>
                        <div class="flex justify-between py-2.5 border-b border-gray-200">
                            <span class="text-gray-600">Date</span>
                            <span class="font-semibold text-gray-900">{{ \Carbon\Carbon::parse($booking->tanggal)->format('d M Y') }}</span>
                        </div>
                        <div class="flex justify-between py-2.5 border-b border-gray-200">
                            <span class="text-gray-600">Time</span>
                            <span class="font-semibold text-gray-900">{{ $booking->jam_mulai }} - {{ $booking->jam_selesai }}</span>
                        </div>
                        <div class="flex justify-between py-2.5 border-b border-gray-200">
                            <span class="text-gray-600">Duration</span>
                            <span class="font-semibold text-gray-900">{{ $booking->durasi }} hours</span>
                        </div>
                        @if($booking->catatan)
                        <div class="flex justify-between py-2.5">
                            <span class="text-gray-600">Notes</span>
                            <span class="font-semibold text-gray-900">{{ $booking->catatan }}</span>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Pricing Breakdown -->
                <div class="border-t pt-8">
                    <div class="space-y-2 mb-4">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Price per Hour</span>
                            <span class="text-gray-900">Rp {{ number_format($booking->harga_per_jam, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Duration</span>
                            <span class="text-gray-900">{{ $booking->durasi }} hours</span>
                        </div>
                    </div>
                    <div class="flex justify-between py-4 border-t-2 border-b-2 border-gray-900">
                        <span class="text-lg font-bold text-gray-900">Total Payment</span>
                        <span class="text-2xl font-bold text-gray-900">Rp {{ number_format($booking->total_harga, 0, ',', '.') }}</span>
                    </div>
                </div>

                <!-- Status Info -->
                <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 space-y-2">
                    <p class="text-sm">
                        <span class="text-gray-600">Booking Status:</span>
                        <span class="font-semibold text-green-700 capitalize ml-2">{{ str_replace('_', ' ', $booking->status) }}</span>
                    </p>
                    <p class="text-sm">
                        <span class="text-gray-600">Invoice Date:</span>
                        <span class="font-semibold text-gray-900 ml-2">{{ $booking->created_at->format('d M Y H:i') }}</span>
                    </p>
                </div>
            </div>

            <!-- Footer Actions -->
            <div class="bg-gray-50 border-t border-gray-200 px-8 py-6 flex gap-3">
                <button onclick="window.print()" class="flex-1 bg-gray-900 hover:bg-gray-800 text-white font-semibold py-3 rounded-lg transition">
                    🖨️ Print Invoice
                </button>
                <button onclick="downloadInvoicePDF('{{ $booking->id }}')" class="flex-1 bg-gray-700 hover:bg-gray-600 text-white font-semibold py-3 rounded-lg transition">
                    📥 Download PDF
                </button>
                <a href="javascript:history.back()" class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-900 font-semibold py-3 rounded-lg transition text-center">
                    ← Back
                </a>
            </div>
        </div>
    </div>
</div>

<script>
    function downloadInvoicePDF(bookingId) {
        window.location.href = `/payment/invoice/download/${bookingId}`;
    }

    window.addEventListener('beforeprint', function() {
        document.body.style.background = 'white';
    });
</script>

<!-- Print Styles -->
<style media="print">
    body {
        background: white !important;
    }
    button, a.btn {
        display: none !important;
    }
</style>

@endsection
