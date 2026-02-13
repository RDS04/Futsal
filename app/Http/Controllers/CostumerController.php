<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Lapangan;
use App\Models\Slider;
use App\Models\Boking;
use Illuminate\Http\Request;

class CostumerController extends Controller
{
    /**
     * List region yang valid
     */
    private $validRegions = ['bypass', 'ulakkarang', 'batusangkar'];

    /**
     * Helper: Validasi dan return region label
     */
    private function getRegionLabel($region)
    {
        $labels = [
            'bypass' => 'Bypass',
            'ulakkarang' => 'Ulak Karang',
            'batusangkar' => 'BatuSangkar',
        ];
        return $labels[$region] ?? ucfirst($region);
    }
    public function bypass()
    {
        $region = 'bypass';

        // ✅ Filter berdasarkan region
        $sliders = Slider::where('region', $region)
            ->select('id', 'gambar', 'region')
            ->get();

        $lapangan = Lapangan::where('region', $region)
            ->where('status', 'tersedia')
            ->select('id', 'namaLapangan', 'jenisLapangan', 'harga', 'deskripsi', 'gambar', 'status', 'region')
            ->get();

        // Event yang belum selesai, urutkan berdasarkan tanggal_mulai terbaru
        $event = Event::where('region', $region)
            ->whereIn('status', ['akan_datang', 'berlangsung'])
            ->select('id', 'judul', 'tanggal_mulai', 'tanggal_selesai', 'deskripsi', 'gambar', 'status', 'region')
            ->orderBy('tanggal_mulai', 'desc')
            ->get();

        // ✅ Ambil data booking untuk sinkronisasi kalender
        $bokings = Boking::where('region', $region)
            ->where('status', '!=', 'canceled')
            ->select('tanggal', 'jam_mulai', 'jam_selesai', 'lapangan_id')
            ->get();

        return view('costumers.dashboard-bypass', compact('sliders', 'lapangan', 'event', 'bokings', 'region'));
    }

    public function batusangkar()
    {
        $region = 'batusangkar';

        // ✅ Filter berdasarkan region
        $sliders = Slider::where('region', $region)
            ->select('id', 'gambar', 'region')
            ->get();

        $lapangan = Lapangan::where('region', $region)
            ->where('status', 'tersedia')
            ->select('id', 'namaLapangan', 'jenisLapangan', 'harga', 'deskripsi', 'gambar', 'status', 'region')
            ->get();

        // Event yang belum selesai, urutkan berdasarkan tanggal_mulai terbaru
        $event = Event::where('region', $region)
            ->whereIn('status', ['akan_datang', 'berlangsung'])
            ->select('id', 'judul', 'tanggal_mulai', 'tanggal_selesai', 'deskripsi', 'gambar', 'status', 'region')
            ->orderBy('tanggal_mulai', 'desc')
            ->get();

        // ✅ Ambil data booking untuk sinkronisasi kalender
        $bokings = Boking::where('region', $region)
            ->where('status', '!=', 'canceled')
            ->select('tanggal', 'jam_mulai', 'jam_selesai', 'lapangan_id')
            ->get();

        return view('costumers.dashboard-batusangkar', compact('sliders', 'lapangan', 'event', 'bokings', 'region'));
    }

    /**
     * Dashboard Bukit Tinggi - Menampilkan data lapangan, event, slider HANYA region Bukit Tinggi
     */
    public function ulakKarang()
    {
        $region = 'ulakkarang';

        // ✅ Filter berdasarkan region
        $sliders = Slider::where('region', $region)
            ->select('id', 'gambar', 'region')
            ->get();

        $lapangan = Lapangan::where('region', $region)
            ->where('status', 'tersedia')
            ->select('id', 'namaLapangan', 'jenisLapangan', 'harga', 'deskripsi', 'gambar', 'status', 'region')
            ->get();

        // Event yang belum selesai, urutkan berdasarkan tanggal_mulai terbaru
        $event = Event::where('region', $region)
            ->whereIn('status', ['akan_datang', 'berlangsung'])
            ->select('id', 'judul', 'tanggal_mulai', 'tanggal_selesai', 'deskripsi', 'gambar', 'status', 'region')
            ->orderBy('tanggal_mulai', 'desc')
            ->get();

        // ✅ Ambil data booking untuk sinkronisasi kalender
        $bokings = Boking::where('region', $region)
            ->where('status', '!=', 'canceled')
            ->select('tanggal', 'jam_mulai', 'jam_selesai', 'lapangan_id')
            ->get();

        return view('costumers.dashboard-ulakkarang', compact('sliders', 'lapangan', 'event', 'bokings', 'region'));
    }
    public function informasi()
    {
        $lapangan = Lapangan::where('region','bypass')
            ->where('status', 'tersedia')
            ->select('id', 'namaLapangan', 'jenisLapangan', 'harga', 'deskripsi', 'gambar', 'status', 'region')
            ->get();
        return view('costumers.informasi', compact('lapangan'));
    }
}
