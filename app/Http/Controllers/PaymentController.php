<?php

namespace App\Http\Controllers;

use App\Models\Boking;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class PaymentController extends Controller
{
    /**
     * Show payment success page with order/booking/payment details
     */
    public function success(Request $request)
    {
        $orderId = $request->query('order_id');
        
        $booking = null;
        $payment = null;
        
        if ($orderId) {
            // Fetch booking by order_id
            $booking = Boking::where('order_id', $orderId)->first();
            
            // Fetch payment if booking exists
            if ($booking) {
                $payment = $booking->payment;
            }
        }
        
        return view('payment.success', compact('orderId', 'booking', 'payment'));
    }

    /**
     * Show invoice for a booking
     */
    public function showInvoice($bookingId)
    {
        $booking = Boking::with('payment', 'lapanganData')
            ->where('id', $bookingId)
            ->where('status', 'paid')
            ->firstOrFail();

        // Authorize - hanya customer atau admin yang bisa lihat
        if (Auth::check() && (Auth::id() == $booking->customer_id || Auth::user()->role === 'admin')) {
            return view('payment.invoice', compact('booking'));
        }

        abort(403, 'Unauthorized access');
    }

    /**
     * Download invoice as PDF
     */
    public function downloadInvoice($bookingId)
    {
        $booking = Boking::with('payment', 'lapanganData')
            ->where('id', $bookingId)
            ->where('status', 'paid')
            ->firstOrFail();

        // Authorize
        if (Auth::check() && (Auth::id() == $booking->customer_id || Auth::user()->role === 'admin')) {
            // Generate PDF menggunakan dompdf
            return $this->generatePDF($booking);
        }

        abort(403, 'Unauthorized access');
    }

    /**
     * Generate PDF untuk invoice
     */
    private function generatePDF($booking)
    {
        // Generate PDF dari view
        $pdf = Pdf::loadView('payment.invoice-pdf', ['booking' => $booking])
            ->setOption('defaultFont', 'DejaVu Sans')
            ->setOption('dpi', 96);

        // Download dengan nama file yang descriptive
        $filename = 'Invoice-' . $booking->order_id . '.pdf';
        return $pdf->download($filename);
    }

    /**
     * Show booking history / riwayat booking untuk customer yang login
     */
    public function history()
    {
        // Pastikan user sudah login
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Silakan login terlebih dahulu untuk melihat riwayat booking');
        }

        // Ambil semua booking user (pending & paid) dengan relasi payment
        $allBookings = Boking::where('customer_id', Auth::id())
            ->orderByDesc('created_at')
            ->with('payment')
            ->get();

        return view('payment.invoice-list', compact('allBookings'));
    }
}

