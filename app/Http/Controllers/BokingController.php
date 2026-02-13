<?php

namespace App\Http\Controllers;

use App\Models\Boking;
use App\Models\Lapangan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BokingController extends Controller
{
    public function bookingForm()
    {
        // ✅ Pastikan user sudah login
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu untuk booking');
        }

        // Get customer's region from query parameter or session
        $region = request()->query('region') ?? session('region') ?? null;
        
        // If no region provided, try to get from authenticated user
        if (!$region && Auth::check()) {
            $region = Auth::user()->region;
        }
        if (!$region) {
            return redirect()->route('informasi')->with('error', 'Pilih region terlebih dahulu');
        }

        // Get available lapangan for customer's region
        $lapangan = Lapangan::where('region', $region)
            ->where('status', 'tersedia')
            ->select('id', 'namaLapangan', 'harga', 'deskripsi')
            ->orderBy('namaLapangan')
            ->get();

        // If no lapangan found, provide helpful error
        if ($lapangan->isEmpty()) {
            return redirect()->back()
                ->with('info', 'Maaf, tidak ada lapangan yang tersedia di region ' . $region);
        }

        // Get lapangan_id dari query parameter (jika user click dari dashboard)
        $selectedLapanganId = request()->query('lapangan_id') ?? null;

        return view('boking.boking', compact('lapangan', 'region', 'selectedLapanganId'));
    }

    public function store(Request $request)
    {
        // ✅ Pastikan user sudah login
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu untuk booking');
        }

        \Log::info('Booking form submitted', [
            'user_id' => Auth::id(),
            'user_email' => Auth::user()->email ?? 'N/A',
            'request_data' => $request->except('_token')
        ]);
        
        // Validasi input
        try {
            $today = now()->format('Y-m-d');
            
            $validatedData = $request->validate([
                'nama' => 'required|string|max:255',
                'tanggal' => 'required|date_format:Y-m-d|date',
                'jam_mulai' => 'required|date_format:H:i',
                'jam_selesai' => 'required|date_format:H:i',
                'region' => 'required|string|max:100',
                'lapangan_id' => 'required|integer|exists:lapangans,id',
                'lapangan' => 'required|string|max:100',
                'catatan' => 'nullable|string',
            ], [
                'tanggal.date_format' => 'Format tanggal tidak valid',
                'lapangan_id.exists' => 'Lapangan yang dipilih tidak valid atau sudah dihapus'
            ]);
            
            // Validasi tanggal minimal hari ini (setelah validasi format berhasil)
            if ($validatedData['tanggal'] < $today) {
                return redirect()->back()
                    ->withErrors(['tanggal' => 'Tanggal booking harus hari ini atau lebih kemudian'])
                    ->withInput();
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::warning('Booking validation failed', ['errors' => $e->errors()]);
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        }

        // Validasi jam selesai > jam mulai
        if ($validatedData['jam_selesai'] <= $validatedData['jam_mulai']) {
            \Log::warning('Invalid time range', [
                'jam_mulai' => $validatedData['jam_mulai'],
                'jam_selesai' => $validatedData['jam_selesai']
            ]);
            return redirect()->back()
                ->withErrors(['jam_selesai' => 'Jam selesai harus lebih besar dari jam mulai. Periksa kembali jam Anda.'])
                ->withInput();
        }

        // Check availability - apakah lapangan sudah terboking pada jam tersebut
        $existingBooking = Boking::where('lapangan_id', $validatedData['lapangan_id'])
            ->where('tanggal', $validatedData['tanggal'])
            ->whereIn('status', ['pending', 'paid'])
            ->whereRaw("jam_selesai > ? AND jam_mulai < ?", [
                $validatedData['jam_mulai'],
                $validatedData['jam_selesai']
            ])
            ->first();

        if ($existingBooking) {
            \Log::info('Booking slot already taken', [
                'lapangan_id' => $validatedData['lapangan_id'],
                'tanggal' => $validatedData['tanggal'],
                'jam_mulai' => $validatedData['jam_mulai'],
                'jam_selesai' => $validatedData['jam_selesai']
            ]);
            return redirect()->back()
                ->withErrors(['lapangan' => 'Lapangan sudah terboking pada jam tersebut. Silakan pilih jam atau lapangan lain.'])
                ->withInput();
        }

        // Get lapangan price
        $lapangan = Lapangan::find($validatedData['lapangan_id']);
        if (!$lapangan) {
            \Log::error('Lapangan not found', ['lapangan_id' => $validatedData['lapangan_id']]);
            return redirect()->back()
                ->withErrors(['lapangan_id' => 'Lapangan tidak ditemukan. Silakan pilih lapangan yang valid.'])
                ->withInput();
        }

        $jamMulai = strtotime($validatedData['jam_mulai']);
        $jamSelesai = strtotime($validatedData['jam_selesai']);
        $durasi = ($jamSelesai - $jamMulai) / 3600; // durasi dalam jam
        $totalHarga = $lapangan->harga * $durasi;

        // Generate unique order ID for Midtrans
        $orderId = 'BOOKING-' . $validatedData['lapangan_id'] . '-' . time() . '-' . random_int(10000, 99999);

        // ✅ Ensure customer_id is set
        $validatedData['customer_id'] = Auth::id();
        $validatedData['total_harga'] = $totalHarga;
        $validatedData['harga_per_jam'] = $lapangan->harga;
        $validatedData['durasi'] = $durasi;
        $validatedData['order_id'] = $orderId;
        $validatedData['status'] = 'pending';

        try {
            $booking = Boking::create($validatedData);
            
            \Log::info('Booking created successfully', [
                'booking_id' => $booking->id,
                'customer_id' => $booking->customer_id,
                'order_id' => $booking->order_id,
                'total_harga' => $booking->total_harga
            ]);
        } catch (\Exception $e) {
            \Log::error('Booking creation failed', [
                'error' => $e->getMessage(),
                'data' => $validatedData
            ]);
            return redirect()->back()
                ->withErrors(['error' => 'Gagal menyimpan booking. Silakan coba lagi atau hubungi support.'])
                ->withInput();
        }

        // ✅ Simpan ke session untuk ditampilkan di payment page
        session(['booking_data' => $validatedData]);
        session(['booking_id' => $booking->id]);
        session(['order_id' => $orderId]);

        \Log::info('Session saved for booking', [
            'session_booking_id' => session('booking_id'),
            'session_order_id' => session('order_id')
        ]);

        return redirect()->route('boking.payment')->with('success', 'Lanjut ke pembayaran!');
    }

    public function payment()
    {
        // ✅ Pastikan user sudah login
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu');
        }

        \Log::info('Payment page accessed', [
            'user_id' => Auth::id(),
            'user_email' => Auth::user()->email ?? 'N/A'
        ]);

        // Ambil data booking dari session
        $bookingData = session('booking_data');
        $bookingId = session('booking_id');
        
        \Log::debug('Session check', [
            'has_booking_data' => !empty($bookingData),
            'booking_id' => $bookingId
        ]);

        // Jika session tidak ada tapi ada booking_id, coba ambil dari database
        if (!$bookingData && $bookingId) {
            $booking = Boking::find($bookingId);
            if ($booking && $booking->customer_id === Auth::id()) {
                $bookingData = [
                    'nama' => $booking->nama,
                    'tanggal' => $booking->tanggal,
                    'jam_mulai' => $booking->jam_mulai,
                    'jam_selesai' => $booking->jam_selesai,
                    'lapangan' => $booking->lapangan,
                    'region' => $booking->region,
                    'lapangan_id' => $booking->lapangan_id,
                    'total_harga' => $booking->total_harga,
                    'harga_per_jam' => $booking->harga_per_jam,
                    'durasi' => $booking->durasi,
                    'order_id' => $booking->order_id,
                    'status' => $booking->status,
                ];
                
                \Log::info('Booking data restored from database', [
                    'booking_id' => $bookingId,
                    'order_id' => $booking->order_id
                ]);
            }
        }
        
        // ✅ Jika session hilang (setelah logout/login), ambil booking pending user terbaru
        if (!$bookingData) {
            $latestBooking = Boking::where('customer_id', Auth::id())
                ->where('status', 'pending')
                ->orderBy('created_at', 'desc')
                ->first();
            
            if ($latestBooking) {
                $bookingData = [
                    'nama' => $latestBooking->nama,
                    'tanggal' => $latestBooking->tanggal,
                    'jam_mulai' => $latestBooking->jam_mulai,
                    'jam_selesai' => $latestBooking->jam_selesai,
                    'lapangan' => $latestBooking->lapangan,
                    'region' => $latestBooking->region,
                    'lapangan_id' => $latestBooking->lapangan_id,
                    'total_harga' => $latestBooking->total_harga,
                    'harga_per_jam' => $latestBooking->harga_per_jam,
                    'durasi' => $latestBooking->durasi,
                    'order_id' => $latestBooking->order_id,
                    'status' => $latestBooking->status,
                ];
                
                // Update session untuk consistency
                session(['booking_data' => $bookingData]);
                session(['booking_id' => $latestBooking->id]);
                
                \Log::info('Latest pending booking found and restored', [
                    'booking_id' => $latestBooking->id,
                    'order_id' => $latestBooking->order_id
                ]);
            }
        }

        // ✅ Jika masih tidak ada data, redirect ke form booking
        if (!$bookingData) {
            \Log::warning('No booking data found for payment', [
                'user_id' => Auth::id()
            ]);
            return redirect()->route('boking.form')
                ->withErrors(['error' => 'Tidak ada booking yang ditemukan. Silakan lakukan booking baru untuk melanjutkan proses pembayaran.']);
        }

        // Get all bookings untuk di-display (pending & paid)
        $allBookings = Boking::where('customer_id', Auth::id())
            ->orderByDesc('created_at')
            ->with('payment')
            ->get();

        \Log::debug('Payment page loaded', [
            'current_booking' => $bookingData['order_id'] ?? 'N/A',
            'total_bookings' => $allBookings->count()
        ]);

        return view('payment.index', compact('bookingData', 'allBookings'));
    }
}
