<?php

namespace Database\Seeders;

use App\Models\Boking;
use App\Models\Lapangan;
use Illuminate\Database\Seeder;

class BookingTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil lapangan dari setiap region
        $lapanganBypass = Lapangan::where('region', 'bypass')->first();
        $lapanganUlak = Lapangan::where('region', 'ulakkarang')->first();
        $lapanganBatu = Lapangan::where('region', 'batusangkar')->first();

        // Buat booking untuk Bypass
        if ($lapanganBypass) {
            Boking::create([
                'nama' => 'Test Booking Bypass',
                'tanggal' => '2026-02-06',
                'jam_mulai' => '10:00',
                'jam_selesai' => '12:00',
                'lapangan' => $lapanganBypass->namaLapangan,
                'region' => 'bypass',
                'lapangan_id' => $lapanganBypass->id,
                'status' => 'pending',
            ]);
            echo "✓ Booking Bypass created on 2026-02-06 (10:00 - 12:00)\n";
        }

        // Buat booking untuk Ulak Karang
        if ($lapanganUlak) {
            Boking::create([
                'nama' => 'Test Booking Ulak Karang',
                'tanggal' => '2026-02-06',
                'jam_mulai' => '14:00',
                'jam_selesai' => '16:00',
                'lapangan' => $lapanganUlak->namaLapangan,
                'region' => 'ulakkarang',
                'lapangan_id' => $lapanganUlak->id,
                'status' => 'confirmed',
            ]);
            echo "✓ Booking Ulak Karang created on 2026-02-06 (14:00 - 16:00)\n";
        }

        // Buat booking untuk Batu Sangkar
        if ($lapanganBatu) {
            Boking::create([
                'nama' => 'Test Booking Batu Sangkar',
                'tanggal' => '2026-02-06',
                'jam_mulai' => '16:00',
                'jam_selesai' => '18:00',
                'lapangan' => $lapanganBatu->namaLapangan,
                'region' => 'batusangkar',
                'lapangan_id' => $lapanganBatu->id,
                'status' => 'paid',
            ]);
            echo "✓ Booking Batu Sangkar created on 2026-02-06 (16:00 - 18:00)\n";
        }

        echo "\n✓ All test bookings created successfully!\n";
    }
}
