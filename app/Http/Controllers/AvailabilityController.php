<?php

namespace App\Http\Controllers;

use App\Models\Lapangan;
use App\Models\Boking;
use Illuminate\Http\Request;

class AvailabilityController extends Controller
{
    /**
     * Get available hours for a specific field on a specific date
     * Used for real-time availability in booking form
     */
    public function getAvailableHours(Request $request)
    {
        $lapanganId = $request->input('lapangan_id');
        $tanggal = $request->input('tanggal');

        if (!$lapanganId || !$tanggal) {
            return response()->json(['error' => 'lapangan_id dan tanggal diperlukan'], 400);
        }

        // Verify lapangan exists
        $lapangan = Lapangan::find($lapanganId);
        if (!$lapangan) {
            return response()->json(['error' => 'Lapangan tidak ditemukan'], 404);
        }

        // Get all bookings for this field on this date (only pending and paid)
        $bookings = Boking::where('lapangan_id', $lapanganId)
            ->where('tanggal', $tanggal)
            ->whereIn('status', ['pending', 'paid'])
            ->get(['jam_mulai', 'jam_selesai']);

        // Generate time slots (jam per jam, 08:00 - 22:00)
        $allHours = $this->generateTimeSlots();
        $availableHours = [];
        
        // Check if the selected date is today
        $today = now()->format('Y-m-d');
        $isToday = ($tanggal === $today);
        $currentHour = $isToday ? (int)now()->format('H') : 0;
        $currentMinute = $isToday ? (int)now()->format('i') : 0;

        foreach ($allHours as $hour) {
            $isAvailable = true;
            
            // Convert hour to comparable format (HH:00 as integer minutes)
            list($hourPart, $minPart) = explode(':', $hour);
            $hourMinutes = intval($hourPart) * 60 + intval($minPart);

            // For today: check if hour has already passed
            if ($isToday) {
                $currentMinutes = $currentHour * 60 + $currentMinute;
                // Hour must be at least 1 hour from now
                // Example: if current time is 14:30, available hours start from 15:00
                if ($hourMinutes <= $currentMinutes) {
                    $isAvailable = false;
                }
            }

            // Check if this hour is booked
            if ($isAvailable) {
                foreach ($bookings as $booking) {
                    // Handle both string and DateTime object formats
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

                    // If hour is between booking time, it's not available
                    // Booked from 08:00 - 10:00 means hours 08:00 and 09:00 are not available
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
            'lapangan_nama' => $lapangan->namaLapangan,
            'tanggal' => $tanggal,
            'hours' => $availableHours,
        ]);
    }

    /**
     * Get booked slots for calendar visualization
     * Used to show bookings in customer dashboard calendar
     */
    public function getBookedSlots(Request $request)
    {
        $lapanganId = $request->input('lapangan_id');
        $tahun = $request->input('tahun');
        $bulan = $request->input('bulan');
        $region = $request->input('region');

        $query = Boking::where('status', '!=', 'canceled');

        if ($lapanganId) {
            $query->where('lapangan_id', $lapanganId);
        }

        if ($region) {
            $query->where('region', $region);
        }

        if ($tahun && $bulan) {
            $query->whereYear('tanggal', $tahun)
                ->whereMonth('tanggal', $bulan);
        }

        $bookings = $query->get(['id', 'lapangan_id', 'tanggal', 'jam_mulai', 'jam_selesai', 'nama', 'lapangan']);

        // Format untuk calendar
        $formattedBookings = [];
        foreach ($bookings as $booking) {
            $jamMulai = is_string($booking->jam_mulai) 
                ? $booking->jam_mulai 
                : $booking->jam_mulai->format('H:i');
            $jamSelesai = is_string($booking->jam_selesai) 
                ? $booking->jam_selesai 
                : $booking->jam_selesai->format('H:i');
            
            // Format tanggal ke YYYY-MM-DD string
            $tanggalFormatted = is_string($booking->tanggal)
                ? $booking->tanggal
                : $booking->tanggal->format('Y-m-d');
                
            $formattedBookings[] = [
                'id' => $booking->id,
                'lapangan_id' => $booking->lapangan_id,
                'lapangan_nama' => $booking->lapangan,
                'tanggal' => $tanggalFormatted,
                'jam_mulai' => $jamMulai,
                'jam_selesai' => $jamSelesai,
                'nama_pemesan' => $booking->nama,
            ];
        }

        return response()->json($formattedBookings);
    }

    /**
     * Get booked dates for a specific field
     * Shows which dates have any bookings
     */
    public function getBookedDates(Request $request)
    {
        $lapanganId = $request->input('lapangan_id');
        $tahun = $request->input('tahun');
        $bulan = $request->input('bulan');

        $query = Boking::where('status', '!=', 'canceled');

        if ($lapanganId) {
            $query->where('lapangan_id', $lapanganId);
        }

        if ($tahun && $bulan) {
            $query->whereYear('tanggal', $tahun)
                ->whereMonth('tanggal', $bulan);
        }

        $bookedDates = $query->distinct('tanggal')
            ->pluck('tanggal')
            ->map(function ($date) {
                return is_string($date) ? $date : $date->format('Y-m-d');
            });

        return response()->json([
            'booked_dates' => $bookedDates->toArray(),
        ]);
    }

    /**
     * Generate time slots from 08:00 to 22:00 (hourly)
     */
    private function generateTimeSlots($startHour = 8, $endHour = 23)
    {
        $slots = [];
        for ($hour = $startHour; $hour < $endHour; $hour++) {
            $slots[] = sprintf('%02d:00', $hour);
        }
        return $slots;
    }
}
