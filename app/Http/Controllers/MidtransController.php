<?php

namespace App\Http\Controllers;

use App\Models\Snap;
use App\Models\Boking;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class MidtransController extends Controller
{
    public function checkout($orderId)
    {
        // ambil order dari DB
        // return view('checkout', compact('order'));
        return view('checkout', ['orderId' => $orderId]);
    }

    /**
     * Debug endpoint - check if Midtrans config is loaded
     * Test endpoint: /admin/debug/midtrans-config atau /debug-midtrans
     */
    public function debugConfig()
    {
        $serverKey = config('midtrans.server_key');
        $clientKey = config('midtrans.client_key');
        $merchantId = config('midtrans.merchant_id');
        $isProduction = config('midtrans.is_production');
        
        return response()->json([
            'status' => 'debug',
            'credentials_loaded' => [
                'merchant_id' => !empty($merchantId),
                'server_key' => !empty($serverKey),
                'client_key' => !empty($clientKey),
            ],
            'values' => [
                'merchant_id' => $merchantId,
                'server_key_preview' => $serverKey ? substr($serverKey, 0, 20) . '...' : null,
                'client_key_preview' => $clientKey ? substr($clientKey, 0, 20) . '...' : null,
                'is_production' => $isProduction,
            ],
            'environment' => app()->environment(),
            'app_debug' => config('app.debug'),
            'env_vars' => [
                'MIDTRANS_MERCHANT_ID' => env('MIDTRANS_MERCHANT_ID'),
                'MIDTRANS_IS_PRODUCTION' => env('MIDTRANS_IS_PRODUCTION'),
                'APP_ENV' => env('APP_ENV'),
                'APP_DEBUG' => env('APP_DEBUG'),
            ],
            'issue_help' => [
                '401_error' => 'Jika mendapat error 401, kemungkinan: (1) Server key salah, (2) Client key salah, (3) Mode production/sandbox mismatch',
                'solution_1' => 'Login ke dashboard.midtrans.com dan pastikan Anda menggunakan API Keys dari environment yang benar (Sandbox untuk development)',
                'solution_2' => 'Copy-paste kembali keys dari Midtrans Dashboard ke .env file, pastikan tidak ada spasi atau karakter tersembunyi',
                'solution_3' => 'Pastikan MIDTRANS_IS_PRODUCTION=false untuk sandbox atau true untuk production',
            ]
        ]);
    }

    public function token(Request $request, $orderId = null)
    {
        try {
            // Jika orderId tidak diberikan di URL, ambil dari request body
            $orderId = $orderId ?? $request->input('order_id');
            $grossAmount = $request->input('gross_amount');
            $bookingData = $request->input('booking_data');

            // Validate input
            if (!$orderId) {
                Log::error('Token Request - Missing order_id');
                return response()->json([
                    'error' => 'order_id tidak diberikan. Parameter wajib.'
                ], 400);
            }

            if (!$grossAmount || $grossAmount <= 0) {
                Log::error('Token Request - Invalid gross_amount', ['gross_amount' => $grossAmount]);
                return response()->json([
                    'error' => 'gross_amount harus > 0. Nilai saat ini: ' . $grossAmount
                ], 400);
            }

            // Ensure booking data is an array
            $bookingData = is_array($bookingData) ? $bookingData : [];

            Log::info('Token Request Starting', [
                'order_id' => $orderId,
                'gross_amount' => $grossAmount,
                'has_booking_data' => !empty($bookingData),
            ]);

            // Check Midtrans credentials
            $serverKey = config('midtrans.server_key');
            $clientKey = config('midtrans.client_key');
            $merchantId = config('midtrans.merchant_id');

            if (!$serverKey || !$clientKey || !$merchantId) {
                Log::error('Midtrans Credentials Missing', [
                    'server_key_exists' => !empty($serverKey),
                    'client_key_exists' => !empty($clientKey),
                    'merchant_id_exists' => !empty($merchantId),
                ]);

                return response()->json([
                    'error' => 'Konfigurasi Midtrans tidak lengkap. Hubungi administrator.',
                    'debug' => 'Missing credentials in .env (development only)'
                ], 500);
            }

            // Siapkan parameter untuk Midtrans dengan validation ketat
            $transactionDetails = [
                'order_id' => (string)$orderId,
                'gross_amount' => intval($grossAmount),
            ];

            $customerDetails = [
                'first_name' => isset($bookingData['nama']) ? substr($bookingData['nama'], 0, 50) : 'Customer',
                'email' => Auth::check() ? Auth::user()->email : 'customer@futsal.com',
                'phone' => Auth::check() ? (Auth::user()->phone ?? '08000000000') : '08000000000',
            ];

            $itemDetails = [];
            if (!empty($bookingData['lapangan']) && !empty($bookingData['jam_mulai'])) {
                $itemDetails[] = [
                    'id' => (string)(isset($bookingData['lapangan_id']) ? $bookingData['lapangan_id'] : 'item-1'),
                    'price' => intval($grossAmount),
                    'quantity' => 1,
                    'name' => substr($bookingData['lapangan'] . ' - ' . $bookingData['jam_mulai'] . ' s/d ' . $bookingData['jam_selesai'], 0, 50),
                ];
            } else {
                // Fallback jika data tidak lengkap
                $itemDetails[] = [
                    'id' => 'booking-1',
                    'price' => intval($grossAmount),
                    'quantity' => 1,
                    'name' => 'Booking Futsal',
                ];
            }

            $params = [
                'transaction_details' => $transactionDetails,
                'customer_details' => $customerDetails,
                'item_details' => $itemDetails,
                'credit_card' => [
                    'secure' => true,
                ],
            ];

            Log::info('Calling Midtrans Snap API', [
                'order_id' => $orderId,
                'gross_amount' => $grossAmount,
                'customer_email' => $customerDetails['email'],
                'items_count' => count($itemDetails),
            ]);

            // Generate Snap Token
            $snapToken = Snap::getSnapToken($params);

            if (!$snapToken) {
                Log::error('Midtrans Failed - No token returned', ['order_id' => $orderId]);
                return response()->json([
                    'error' => 'Midtrans tidak mengembalikan token. Silakan coba lagi.'
                ], 500);
            }

            Log::info('Snap Token Generated Successfully', [
                'order_id' => $orderId,
                'token_length' => strlen($snapToken),
            ]);

            // Simpan data temporary untuk verifikasi nanti
            session(['payment_data' => [
                'order_id' => $orderId,
                'booking_data' => $bookingData,
                'gross_amount' => $grossAmount,
                'timestamp' => now()
            ]]);

            return response()->json([
                'token' => $snapToken,
                'client_key' => $clientKey,
            ]);

        } catch (\Exception $e) {
            Log::error('Midtrans Token Error - Exception', [
                'order_id' => $orderId ?? null,
                'gross_amount' => $grossAmount ?? null,
                'error_message' => $e->getMessage(),
                'error_code' => $e->getCode(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            // Determine error message untuk user
            $errorMsg = $e->getMessage();
            
            // If it's an HTTP error from Midtrans (401, 403, etc)
            if (strpos($errorMsg, '401') !== false || strpos($errorMsg, 'Unauthorized') !== false) {
                $errorMsg = 'Kredensial Midtrans tidak valid. Periksa MIDTRANS_SERVER_KEY dan MIDTRANS_CLIENT_KEY di .env';
            } elseif (strpos($errorMsg, '403') !== false) {
                $errorMsg = 'Akses Midtrans ditolak. Pastikan merchant ID benar dan account aktif.';
            } elseif (strpos($errorMsg, '500') !== false || strpos($errorMsg, '502') !== false) {
                $errorMsg = 'Server Midtrans sedang bermasalah. Silakan coba beberapa saat lagi.';
            }

            return response()->json([
                'error' => $errorMsg,
                'debug_msg' => env('APP_DEBUG') ? $e->getMessage() : null,
            ], 500);
        }
    }

    public function notification(Request $request)
    {
        $payload = $request->all();

        // Wajib verifikasi signature_key:
        // SHA512(order_id + status_code + gross_amount + ServerKey)
        // Cocokkan hasilnya dengan $payload['signature_key']
        $expected = hash(
            'sha512',
            ($payload['order_id'] ?? '') .
            ($payload['status_code'] ?? '') .
            ($payload['gross_amount'] ?? '') .
            config('midtrans.server_key')
        );

        if (($payload['signature_key'] ?? '') !== $expected) {
            return response('invalid signature', 401);
        }

        $orderId = $payload['order_id'];
        $transactionStatus = $payload['transaction_status'];

        // Status mapping Midtrans ke Boking status:
        // settlement/capture = paid (pembayaran berhasil)
        // pending = pending (menunggu)
        // deny/cancel/expire = canceled (dibatalkan)
        $bokingStatus = 'pending';
        
        if (in_array($transactionStatus, ['settlement', 'capture'])) {
            $bokingStatus = 'paid';
        } elseif ($transactionStatus === 'pending') {
            $bokingStatus = 'pending';
        } elseif (in_array($transactionStatus, ['deny', 'cancel', 'expire'])) {
            $bokingStatus = 'canceled';
        }

        // Cari booking dari database berdasarkan order_id
        // Metode 1: Cari berdasarkan exact match order_id
        $booking = Boking::where('order_id', $orderId)->first();
        
        // Metode 2: Jika tidak ketemu, cari payment record untuk order_id ini
        if (!$booking) {
            $payment = Payment::where('order_id', $orderId)->first();
            $booking = $payment ? $payment->booking : null;
        }
        
        if ($booking) {
            // Update booking status
            $booking->update(['status' => $bokingStatus]);
            
            // Update customer payment status jika pembayaran berhasil
            if ($bokingStatus === 'paid' && $booking->customer_id) {
                $booking->customer()->update(['payment_status' => 'paid']);
            } elseif ($bokingStatus === 'canceled' && $booking->customer_id) {
                $booking->customer()->update(['payment_status' => 'pending']);
            }

            // Simpan payment record untuk tracking dan auditing
            $paymentAt = null;
            if (in_array($transactionStatus, ['settlement', 'capture'])) {
                $paymentAt = now();
            }

            // Create or update payment record
            $paymentData = [
                'booking_id' => $booking->id,
                'order_id' => $orderId,
                'transaction_id' => $payload['transaction_id'] ?? null,
                'payment_status' => $transactionStatus,
                'amount' => $payload['gross_amount'] ?? $booking->total_harga,
                'payment_method' => $payload['payment_type'] ?? null,
                'payment_reference' => $payload['reference_id'] ?? null,
                'midtrans_response' => $payload,
                'signature_key' => $payload['signature_key'] ?? null,
                'payment_at' => $paymentAt,
            ];

            Payment::updateOrCreate(
                ['order_id' => $orderId],
                $paymentData
            );

            Log::info('Booking Status Updated via Webhook', [
                'booking_id' => $booking->id,
                'order_id' => $orderId,
                'transaction_status' => $transactionStatus,
                'booking_status' => $bokingStatus,
            ]);
        } else {
            Log::warning('Booking not found for order_id', [
                'order_id' => $orderId,
                'transaction_status' => $transactionStatus,
            ]);
        }

        // Midtrans minta endpoint balas HTTP 200 agar dianggap sukses menerima notifikasi
        return response('OK', 200);
    }

    /**
     * Manual webhook trigger untuk testing (development only)
     * Test dengan: GET /midtrans/test-webhook?order_id=BOOKING-1-1705708800-12345
     */
    public function testWebhook(Request $request)
    {
        if (app()->environment('production')) {
            return response('Forbidden in production', 403);
        }

        $orderId = $request->input('order_id');
        if (!$orderId) {
            return response()->json([
                'error' => 'order_id parameter diperlukan',
                'example' => '/midtrans/test-webhook?order_id=BOOKING-1-1705708800-12345'
            ], 400);
        }

        // Find booking dengan order_id ini
        $booking = Boking::where('order_id', $orderId)->first();
        if (!$booking) {
            return response()->json([
                'error' => 'Booking tidak ditemukan dengan order_id: ' . $orderId
            ], 404);
        }

        $grossAmount = intval($booking->total_harga);

        // Create manual webhook payload
        $mockPayload = [
            'order_id' => $orderId,
            'status_code' => '200',
            'transaction_status' => 'settlement', // Simulate successful payment
            'transaction_id' => 'mid-' . uniqid(),
            'gross_amount' => $grossAmount,
            'payment_type' => 'credit_card',
            'signature_key' => hash(
                'sha512',
                $orderId . '200' . $grossAmount . config('midtrans.server_key')
            ),
        ];

        // Call notification handler directly
        $mockRequest = new Request($mockPayload);
        $response = $this->notification($mockRequest);

        return response()->json([
            'message' => 'Webhook test executed',
            'order_id' => $orderId,
            'status_code' => $response->getStatusCode(),
            'instruction' => 'Check database to verify booking status changed to "paid"'
        ]);
    }

    /**
     * Show manual payment confirmation page (for testing without ngrok)
     * GET /midtrans/manual-confirm?order_id=BOOKING-...
     */
    public function manualConfirm(Request $request)
    {
        if (app()->environment('production')) {
            abort(403, 'Not available in production');
        }

        $orderId = $request->input('order_id');
        if (!$orderId) {
            return response()->json(['error' => 'order_id required'], 400);
        }

        $booking = Boking::where('order_id', $orderId)->first();
        if (!$booking) {
            return response()->json(['error' => 'Booking not found'], 404);
        }

        return view('payment.manual-confirm', compact('booking', 'orderId'));
    }

    /**
     * Verify payment status dari Midtrans
     * Called after successful payment from frontend snap.js onSuccess callback
     */
    public function verifyPayment(Request $request)
    {
        try {
            $orderId = $request->input('order_id');  // Unique order_id dari payment attempt
            $baseOrderId = $request->input('base_order_id');  // Base order_id dari booking
            $transactionId = $request->input('transaction_id');
            $transactionStatus = $request->input('transaction_status');

            if (!$orderId || !$baseOrderId) {
                return response()->json(['error' => 'order_id dan base_order_id diperlukan'], 400);
            }

            // Cari booking menggunakan base_order_id
            $booking = Boking::where('order_id', $baseOrderId)->first();
            if (!$booking) {
                return response()->json([
                    'error' => 'Booking tidak ditemukan',
                    'base_order_id' => $baseOrderId
                ], 404);
            }

            // Jika transaction_status adalah settlement atau capture, update booking status
            if (in_array($transactionStatus, ['settlement', 'capture'])) {
                $booking->update(['status' => 'paid']);
                
                // Update customer payment status
                if ($booking->customer_id) {
                    $booking->customer()->update(['payment_status' => 'paid']);
                }

                // Create or update payment record dengan unique order_id dari attempt ini
                Payment::updateOrCreate(
                    ['order_id' => $orderId],  // Unique order_id
                    [
                        'booking_id' => $booking->id,
                        'order_id' => $orderId,  // Unique order_id dari attempt
                        'transaction_id' => $transactionId,
                        'payment_status' => $transactionStatus,
                        'amount' => $booking->total_harga,
                        'payment_at' => now(),
                    ]
                );

                Log::info('Payment Verified and Updated', [
                    'booking_id' => $booking->id,
                    'base_order_id' => $baseOrderId,
                    'unique_order_id' => $orderId,
                    'transaction_id' => $transactionId,
                    'transaction_status' => $transactionStatus,
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Pembayaran berhasil diverifikasi',
                    'booking_status' => 'paid',
                    'booking_id' => $booking->id
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Status pembayaran: ' . $transactionStatus,
                'transaction_status' => $transactionStatus
            ]);

        } catch (\Exception $e) {
            Log::error('Payment Verification Error: ' . $e->getMessage(), [
                'order_id' => $orderId ?? null,
                'base_order_id' => $baseOrderId ?? null,
                'transaction_id' => $transactionId ?? null,
            ]);

            return response()->json([
                'error' => 'Gagal memverifikasi pembayaran: ' . $e->getMessage()
            ], 500);
        }
    }
}

