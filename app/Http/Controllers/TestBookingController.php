<?php

namespace App\Http\Controllers;

use App\Models\Boking;
use Illuminate\Http\Request;

/**
 * Testing Controller untuk debug booking system
 * Endpoint: /test-booking
 */
class TestBookingController extends Controller
{
    /**
     * Tampilkan info waktu saat ini dan timezone
     */
    public function checkTimezone()
    {
        $config_timezone = config('app.timezone');
        $now = now();
        $now_format = $now->format('Y-m-d H:i:s');
        $today = $now->format('Y-m-d');
        $hour = $now->format('H');
        $minute = $now->format('i');

        return response()->json([
            'message' => 'Timezone and Time Information',
            'config_timezone' => $config_timezone,
            'current_datetime' => $now_format,
            'current_date' => $today,
            'current_hour' => $hour,
            'current_minute' => $minute,
            'php_timezone' => date_default_timezone_get(),
        ]);
    }

    /**
     * Tampilkan bookings untuk lapangan tertentu pada tanggal tertentu
     */
    public function getBookingsForField(Request $request)
    {
        $lapanganId = $request->input('lapangan_id', 1);
        $tanggal = $request->input('tanggal', now()->format('Y-m-d'));

        $bookings = Boking::where('lapangan_id', $lapanganId)
            ->where('tanggal', $tanggal)
            ->whereIn('status', ['pending', 'paid'])
            ->get(['id', 'jam_mulai', 'jam_selesai', 'nama', 'status']);

        return response()->json([
            'lapangan_id' => $lapanganId,
            'tanggal' => $tanggal,
            'total_bookings' => $bookings->count(),
            'bookings' => $bookings,
        ]);
    }

    /**
     * Cek ketersediaan jam untuk lapangan tertentu
     */
    public function checkAvailability(Request $request)
    {
        $lapanganId = $request->input('lapangan_id', 1);
        $tanggal = $request->input('tanggal', now()->format('Y-m-d'));

        $today = now()->format('Y-m-d');
        $isToday = ($tanggal === $today);
        $currentHour = $isToday ? (int)now()->format('H') : 0;
        $currentMinute = $isToday ? (int)now()->format('i') : 0;

        // Get bookings
        $bookings = Boking::where('lapangan_id', $lapanganId)
            ->where('tanggal', $tanggal)
            ->whereIn('status', ['pending', 'paid'])
            ->get(['jam_mulai', 'jam_selesai']);

        // Generate time slots
        $allHours = [];
        for ($hour = 8; $hour < 23; $hour++) {
            $allHours[] = sprintf('%02d:00', $hour);
        }

        $availableHours = [];
        foreach ($allHours as $hour) {
            $isAvailable = true;

            // Parse hour
            list($hourPart, $minPart) = explode(':', $hour);
            $hourMinutes = intval($hourPart) * 60 + intval($minPart);

            // For today: check if hour has already passed
            if ($isToday) {
                $currentMinutes = $currentHour * 60 + $currentMinute;
                if ($hourMinutes <= $currentMinutes) {
                    $isAvailable = false;
                }
            }

            // Check if booked
            if ($isAvailable) {
                foreach ($bookings as $booking) {
                    $jamMulaiStr = is_string($booking->jam_mulai) 
                        ? $booking->jam_mulai 
                        : $booking->jam_mulai->format('H:i');
                    $jamSelesaiStr = is_string($booking->jam_selesai) 
                        ? $booking->jam_selesai 
                        : $booking->jam_selesai->format('H:i');

                    list($mulaiHour, $mulaiMin) = explode(':', $jamMulaiStr);
                    list($selesaiHour, $selesaiMin) = explode(':', $jamSelesaiStr);

                    $mulaiMinutes = intval($mulaiHour) * 60 + intval($mulaiMin);
                    $selesaiMinutes = intval($selesaiHour) * 60 + intval($selesaiMin);

                    if ($hourMinutes >= $mulaiMinutes && $hourMinutes < $selesaiMinutes) {
                        $isAvailable = false;
                        break;
                    }
                }
            }

            $availableHours[] = [
                'hour' => $hour,
                'available' => $isAvailable,
            ];
        }

        return response()->json([
            'lapangan_id' => $lapanganId,
            'tanggal' => $tanggal,
            'is_today' => $isToday,
            'current_time' => now()->format('H:i'),
            'total_hours' => count($allHours),
            'available_count' => collect($availableHours)->where('available', true)->count(),
            'booked_count' => $bookings->count(),
            'hours' => $availableHours,
        ]);
    }
}
