<?php

namespace App\Http\Controllers;

use App\Models\Boking;
use App\Models\Costumers;
use App\Models\Lapangan;
use App\Models\User;
use App\Models\Event;
use App\Models\Slider;
use App\Models\Admin;
use App\Models\Payment;
use App\Exports\BookingsExport;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class DashboardController extends Controller
{
    // Daftar region yang valid - harus sesuai dengan enum di migration
    private $validRegions = ['bypass', 'ulakkarang', 'batusangkar'];

    public function index()
    {
        return view('costumers.index');
    }

    /**
     * Dashboard admin dinamis - menampilkan data berdasarkan region yang dipilih
     * Route: /admin/dashboard/{region}
     * 
     * Features:
     * - Multi-region support
     * - Real-time statistics
     * - Access control (regional vs master)
     * - Performance optimization dengan caching
     * 
     * Note:
     * - Master admin bisa view semua region
     * - Regional admin hanya bisa view region mereka sendiri
     */
    public function adminDashboard($region)
    {
        
        $admin = Auth::guard('admin')->user();
        
        // Validasi region
        if (!in_array($region, $this->validRegions)) {
            abort(404, 'Region tidak ditemukan');
        }
        
        // Access control: Regional admin hanya bisa akses region mereka
        if (!$admin->canAccessRegion($region)) {
            abort(403, "Anda tidak memiliki akses ke region {$region}");
        }

        // Ambil data berdasarkan region (tanpa caching untuk memastikan data selalu fresh)
        $dashboardData = [
            'lapangan' => Lapangan::where('region', $region)
                ->select('id', 'namaLapangan', 'jenisLapangan', 'harga', 'status', 'deskripsi')
                ->get(),
            'boking' => Boking::with('lapanganData')
                ->where('region', $region)
                ->select('id', 'nama', 'lapangan', 'lapangan_id', 'customer_id', 'tanggal', 'jam_mulai', 'jam_selesai', 'status')
                ->latest()
                ->get(),
            'events' => Event::where('region', $region)
                ->select('id', 'judul', 'deskripsi', 'tanggal_mulai', 'status')
                ->latest()
                ->limit(10)
                ->get(),
            'sliders' => Slider::where('region', $region)
                ->select('id', 'gambar')
                ->get(),
        ];

        // Hitung statistik
        $lapangan = $dashboardData['lapangan'];
        $boking = $dashboardData['boking'];
        $events = $dashboardData['events'];
        $sliders = $dashboardData['sliders'];
        
        $stats = $this->calculateStats($lapangan, $boking, $events, $sliders);
        
        $regionLabel = $this->getRegionLabel($region);
        $isOwner = $admin->region === $region;

        // Persiapkan data untuk view
        $data = [
            'admin' => $admin,
            'region' => $region,
            'regionLabel' => $regionLabel,
            'isOwner' => $isOwner,
            'lapangan' => $lapangan,
            'boking' => $boking,
            'events' => $events,
            'sliders' => $sliders,
            'bookingChartData' => json_encode($this->getBookingChartData($region)),
            'revenueChartData' => json_encode($this->getRevenueChartData($region)),
        ] + $stats;

        return view('dashboardAdm.adm-satu', $data);
    }

    /**
     * Master Admin Dashboard - menampilkan ringkasan semua region
     * Route: /admin/dashboard
     * 
     * Features:
     * - Overview semua region
     * - Comparison per region
     * - Trend dan insights
     * - Hanya accessible oleh Master Admin
     */
    public function homeAdmin()
    {
        $admin = Auth::guard('admin')->user();
        
        // Ambil data dengan caching (performa optimization)
        $masterData = Cache::remember('master_dashboard_data', now()->addHours(2), function () {
            return [
                'totalLapangan' => Lapangan::count(),
                'totalBoking' => Boking::count(),
                'totalEvents' => Event::count(),
                'totalSliders' => Slider::count(),
                'totalAdmins' => Admin::where('is_active', true)->count(),
                'totalAdminMaster' => Admin::where('role', 'master')->where('is_active', true)->count(),
                'totalAdminRegional' => Admin::where('role', 'regional')->where('is_active', true)->count(),
            ];
        });

        // Data per region dengan detail
        $regionStats = $this->getRegionStats();
        
        // Chart data per region
        $bookingChartPerRegion = [];
        $revenueChartPerRegion = [];
        foreach ($this->validRegions as $region) {
            $bookingChartPerRegion[$region] = $this->getBookingChartData($region);
            $revenueChartPerRegion[$region] = $this->getRevenueChartData($region);
        }
        
        // Recent bookings dari semua region (dengan limit 10)
        $recentBookings = Boking::with(['lapanganData', 'customer'])
            ->latest()
            ->select('id', 'nama', 'lapangan', 'lapangan_id', 'customer_id', 'tanggal', 'region', 'status')
            ->limit(10)
            ->get();
        
        // Top lapangan by bookings (5 teratas)
        $topLapangan = Boking::with('lapanganData')
            ->selectRaw('lapangan_id, lapangan, COUNT(*) as booking_count, region')
            ->groupBy('lapangan_id', 'lapangan', 'region')
            ->orderByDesc('booking_count')
            ->limit(5)
            ->get();

        // List admin dengan status
        $allAdmins = Admin::where('is_active', true)
            ->select('id', 'name', 'email', 'role', 'region', 'created_at')
            ->orderBy('role', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        $data = [
            'admin' => $admin,
            'totalLapangan' => $masterData['totalLapangan'],
            'totalBoking' => $masterData['totalBoking'],
            'totalEvents' => $masterData['totalEvents'],
            'totalSliders' => $masterData['totalSliders'],
            'totalAdmins' => $masterData['totalAdmins'],
            'totalAdminMaster' => $masterData['totalAdminMaster'],
            'totalAdminRegional' => $masterData['totalAdminRegional'],
            'regionStats' => $regionStats,
            'recentBookings' => $recentBookings,
            'topLapangan' => $topLapangan,
            'allAdmins' => $allAdmins,
            'bookingChartPerRegion' => json_encode($bookingChartPerRegion),
            'revenueChartPerRegion' => json_encode($revenueChartPerRegion),
        ];

        return view('dashboardAdm.master-dashboard', $data);
    }

    /**
     * Helper: Hitung statistik dashboard
     * 
     * @param $lapangan Collection
     * @param $boking Collection
     * @param $events Collection
     * @param $sliders Collection
     * @return array
     */
    private function calculateStats($lapangan, $boking, $events, $sliders)
    {
        // Count unique customers from bookings
        $totalUsers = $boking->pluck('customer_id')->unique()->count();
        
        return [
            'totalUsers' => $totalUsers,
            'totalLapangan' => $lapangan->count(),
            'totalBoking' => $boking->count(),
            'totalEvents' => $events->count(),
            'totalSliders' => $sliders->count(),
            'lapanganAktif' => $lapangan->where('status', 'aktif')->count(),
            'lapanganNonaktif' => $lapangan->where('status', 'nonaktif')->count(),
            'bokingConfirmed' => $boking->where('status', 'confirmed')->count(),
            'bokingPending' => $boking->where('status', 'pending')->count(),
            'bokingCancelled' => $boking->where('status', 'cancelled')->count(),
            'recentBookings' => $boking->take(5),
            'recentEvents' => $events->take(5),
        ];
    }

    /**
     * Helper: Ambil statistik per region
     * 
     * @return array
     */
    private function getRegionStats()
    {
        $stats = [];
        foreach ($this->validRegions as $region) {
            $stats[$region] = [
                'lapangan' => Lapangan::where('region', $region)->count(),
                'lapanganAktif' => Lapangan::where('region', $region)->where('status', 'aktif')->count(),
                'boking' => Boking::where('region', $region)->count(),
                'bokingConfirmed' => Boking::where('region', $region)->where('status', 'confirmed')->count(),
                'events' => Event::where('region', $region)->count(),
                'sliders' => Slider::where('region', $region)->count(),
            ];
        }
        return $stats;
    }

    /**
     * Admin Profile - menampilkan profil admin yang login
     */
    public function profile()
    {
        $admin = Auth::guard('admin')->user();
        return view('admin.profile', compact('admin'));
    }

    /**
     * Dashboard Region Dinamis - API endpoint untuk AJAX
     * Gunakan untuk menampilkan lapangan berdasarkan region
     */
    public function getRegionData($region)
    {
        $admin = Auth::guard('admin')->user();
        
        // Validasi region
        if (!in_array($region, $this->validRegions)) {
            return response()->json(['error' => 'Region tidak ditemukan'], 404);
        }
        
        // Regional admin hanya bisa akses region mereka
        if ($admin->region !== $region && !$this->isMasterAdmin($admin)) {
            return response()->json(['error' => 'Akses ditolak'], 403);
        }

        $lapangan = Lapangan::where('region', $region)->get();
        $boking = Boking::where('region', $region)->get();

        return response()->json([
            'region' => $region,
            'regionLabel' => $this->getRegionLabel($region),
            'lapangan' => $lapangan,
            'boking' => $boking,
            'total_lapangan' => $lapangan->count(),
            'total_boking' => $boking->count(),
        ]);
    }

    /**
     * Helper: Cek apakah admin adalah master admin
     * Sekarang menggunakan role dari kolom admin.role
     * 
     * @param $admin Admin model
     * @return bool
     */
    private function isMasterAdmin($admin)
    {
        // Gunakan method dari Model Admin
        return $admin->isMaster();
    }

    /**     * Get Booking Chart Data Per Bulan Per Region
     * 
     * @param string $region
     * @param int $year (optional, default tahun sekarang)
     * @return array
     */
    private function getBookingChartData($region, $year = null)
    {
        if (!$year) {
            $year = date('Y');
        }

        $monthlyData = [];
        for ($month = 1; $month <= 12; $month++) {
            $count = Boking::where('region', $region)
                ->whereYear('tanggal', $year)
                ->whereMonth('tanggal', $month)
                ->count();
            $monthlyData[] = $count;
        }

        return $monthlyData;
    }

    /**
     * Get Revenue Chart Data Per Bulan Per Region
     * 
     * @param string $region
     * @param int $year (optional, default tahun sekarang)
     * @return array
     */
    private function getRevenueChartData($region, $year = null)
    {
        if (!$year) {
            $year = date('Y');
        }

        $monthlyRevenue = [];
        for ($month = 1; $month <= 12; $month++) {
            // Get revenue from Payment table - hanya yang payment_status settlement/capture (approved dari Midtrans)
            $revenue = DB::table('payments')
                ->join('bokings', 'payments.booking_id', '=', 'bokings.id')
                ->where('bokings.region', $region)
                ->whereYear('payments.payment_at', $year)
                ->whereMonth('payments.payment_at', $month)
                ->whereIn('payments.payment_status', ['settlement', 'capture'])
                ->sum('payments.amount');
            
            // Fallback: jika tidak ada di Payment, coba dari Boking (untuk backward compatibility)
            if (!$revenue || $revenue == 0) {
                $revenue = Boking::where('region', $region)
                    ->whereYear('tanggal', $year)
                    ->whereMonth('tanggal', $month)
                    ->where('status', 'confirmed')
                    ->sum('total_harga') ?? 0;
            }
            
            $monthlyRevenue[] = floatval($revenue);
        }

        return $monthlyRevenue;
    }

    /**     * Helper: Convert region code ke label yang readable
     * 
     * @param string $region
     * @return string
     */
    private function getRegionLabel($region)
    {
        $labels = [
            'bypass' => 'Bypass',
            'ulakkarang' => 'Ulak Karang',
            'batusangkar' => 'Batu Sangkar',
        ];
        
        return $labels[$region] ?? ucfirst($region);
    }

    /**
     * Get semua region yang tersedia
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function getRegions()
    {
        $regions = [];
        foreach ($this->validRegions as $region) {
            $regions[] = [
                'code' => $region,
                'label' => $this->getRegionLabel($region),
            ];
        }
        
        return response()->json($regions);
    }

    public function app()
    {
        return view('layouts.app');
    }
    
    public function footer()
    {
        return view('layouts.footer');
    }
    
    public function header()
    {
        return view('layouts.header');
    }
    
    public function sidebar()
    {
        return view('layouts.sidebar');
    }

    /**
     * Dashboard Statistik Per Region
     * Menampilkan perbandingan statistik dari semua region
     */
    public function statistics()
    {
        $admin = Auth::guard('admin')->user();
        
        // Ambil statistik dari semua region
        $regionStats = [];
        foreach ($this->validRegions as $region) {
            $regionStats[$region] = [
                'lapangan' => Lapangan::where('region', $region)->count(),
                'lapanganAktif' => Lapangan::where('region', $region)->where('status', 'aktif')->count(),
                'boking' => Boking::where('region', $region)->count(),
                'bokingConfirmed' => Boking::where('region', $region)->where('status', 'confirmed')->count(),
                'events' => Event::where('region', $region)->count(),
                'sliders' => Slider::where('region', $region)->count(),
            ];
        }

        return view('dashboardAdm.statistics', [
            'admin' => $admin,
            'regionStats' => $regionStats,
        ]);
    }

    /**
     * Booking Report - Laporan Booking dari Semua Region
     */
    public function bookingReport()
    {
        $admin = Auth::guard('admin')->user();
        
        $allBookings = Boking::orderBy('created_at', 'desc')
            ->with(['customer', 'lapanganData'])
            ->paginate(20);

        return view('dashboardAdm.booking-report', [
            'admin' => $admin,
            'bookings' => $allBookings,
        ]);
    }

    /**
     * Admin List - Daftar Semua Admin
     */
    public function adminList()
    {
        $admin = Auth::guard('admin')->user();
        
        $allAdmins = Admin::orderBy('created_at', 'desc')->get();
        $totalAdmins = $allAdmins->count();
        $totalAdminMaster = $allAdmins->where('role', 'master')->count();
        $totalAdminRegional = $allAdmins->where('role', 'regional')->count();

        return view('dashboardAdm.admin-list', [
            'admin' => $admin,
            'allAdmins' => $allAdmins,
            'totalAdmins' => $totalAdmins,
            'totalAdminMaster' => $totalAdminMaster,
            'totalAdminRegional' => $totalAdminRegional,
        ]);
    }

    /**
     * Master: Tampilkan semua Customer dari semua Region
     */
    public function customersList()
    {
        $admin = Auth::guard('admin')->user();
        
        if (!$admin || $admin->role !== 'master') {
            abort(403, 'Unauthorized');
        }

        $customers = Costumers::all()
            ->sortByDesc('created_at');
        
        $totalCustomers = $customers->count();
        $customersByRegion = $customers->groupBy('region');

        return view('dashboardAdm.master.customers-list', [
            'customers' => $customers,
            'totalCustomers' => $totalCustomers,
            'customersByRegion' => $customersByRegion,
        ]);
    }

    /**
     * Master: Tampilkan semua User dari semua Region
     */
    public function usersList()
    {
        $admin = Auth::guard('admin')->user();
        
        if (!$admin || $admin->role !== 'master') {
            abort(403, 'Unauthorized');
        }

        $users = User::all()
            ->sortByDesc('created_at');
        
        $totalUsers = $users->count();

        return view('dashboardAdm.master.users-list', [
            'users' => $users,
            'totalUsers' => $totalUsers,
        ]);
    }

    /**
     * Master: Tampilkan User yang sudah bayar dari semua Region
     */
    public function usersPaid()
    {
        $admin = Auth::guard('admin')->user();
        
        if (!$admin || $admin->role !== 'master') {
            abort(403, 'Unauthorized');
        }

        // Ambil customer dengan status pembayaran 'paid' beserta relasi bokings
        $paidUsers = Costumers::where('payment_status', 'paid')
            ->with('bokings')
            ->orderByDesc('created_at')
            ->get();

        $totalPaid = $paidUsers->count();
        
        // Group paid users by region untuk stats
        $paidUsersByRegion = [];
        foreach ($paidUsers as $user) {
            $userRegions = $user->bokings->pluck('region')->unique();
            foreach ($userRegions as $region) {
                if (!isset($paidUsersByRegion[$region])) {
                    $paidUsersByRegion[$region] = 0;
                }
                $paidUsersByRegion[$region]++;
            }
        }

        return view('dashboardAdm.master.users-paid', [
            'users' => $paidUsers,
            'totalPaid' => $totalPaid,
            'paidUsersByRegion' => $paidUsersByRegion,
        ]);
    }

    /**
     * Master: Tampilkan User dengan Pending Pembayaran dari semua Region
     */
    public function usersPending()
    {
        $admin = Auth::guard('admin')->user();
        
        if (!$admin || $admin->role !== 'master') {
            abort(403, 'Unauthorized');
        }

        // Ambil customer yang memiliki booking dengan status 'pending'
        $pendingUsers = Costumers::whereHas('bokings', function($query) {
            $query->where('status', 'pending');
        })
            ->distinct()
            ->orderByDesc('created_at')
            ->get();

        $totalPending = $pendingUsers->count();

        return view('dashboardAdm.master.users-pending', [
            'users' => $pendingUsers,
            'totalPending' => $totalPending,
        ]);
    }

    /**
     * Regional: Tampilkan semua User di region tertentu
     */
    public function regionUsersList($region)
    {
        $admin = Auth::guard('admin')->user();
        
        // Validasi region
        if (!in_array($region, $this->validRegions)) {
            abort(404, 'Region tidak ditemukan');
        }
        
        // Access control: Regional admin hanya bisa akses region mereka
        if (!$admin->canAccessRegion($region)) {
            abort(403, "Anda tidak memiliki akses ke region {$region}");
        }

        $regionLabel = $this->getRegionLabel($region);
        // Get users yang memiliki booking di region tertentu
        $userIds = Boking::where('region', $region)
            ->distinct()
            ->pluck('customer_id');
        
        $users = Costumers::whereIn('id', $userIds)
            ->with(['bokings' => function($query) use($region) {
                $query->where('region', $region);
            }])
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('dashboardAdm.region.users-list', [
            'users' => $users,
            'region' => $region,
            'regionLabel' => $regionLabel,
            'totalUsers' => $users->total(),
        ]);
    }

    /**
     * Regional: Tampilkan User yang sudah bayar di region tertentu
     */
    public function regionUsersPaid($region)
    {
        $admin = Auth::guard('admin')->user();
        
        // Validasi region
        if (!in_array($region, $this->validRegions)) {
            abort(404, 'Region tidak ditemukan');
        }
        
        // Access control: Regional admin hanya bisa akses region mereka
        if (!$admin->canAccessRegion($region)) {
            abort(403, "Anda tidak memiliki akses ke region {$region}");
        }

        $regionLabel = $this->getRegionLabel($region);
        // Get users yang memiliki booking PAID di region tertentu
        $users = Costumers::distinct()
            ->join('bokings', 'costumers.id', '=', 'bokings.customer_id')
            ->where('bokings.region', $region)
            ->where('bokings.status', 'paid')
            ->select('costumers.*')
            ->orderByDesc('costumers.created_at')
            ->paginate(15);

        return view('dashboardAdm.region.users-paid', [
            'users' => $users,
            'region' => $region,
            'regionLabel' => $regionLabel,
            'totalUsers' => $users->total(),
        ]);
    }

    /**
     * Regional: Tampilkan User dengan pending pembayaran di region tertentu
     */
    public function regionUsersPending($region)
    {
        $admin = Auth::guard('admin')->user();
        
        // Validasi region
        if (!in_array($region, $this->validRegions)) {
            abort(404, 'Region tidak ditemukan');
        }
        
        // Access control: Regional admin hanya bisa akses region mereka
        if (!$admin->canAccessRegion($region)) {
            abort(403, "Anda tidak memiliki akses ke region {$region}");
        }

        $regionLabel = $this->getRegionLabel($region);
        // Get users yang memiliki booking PENDING di region tertentu
        $users = Costumers::distinct()
            ->join('bokings', 'costumers.id', '=', 'bokings.customer_id')
            ->where('bokings.region', $region)
            ->where('bokings.status', 'pending')
            ->select('costumers.*')
            ->orderByDesc('costumers.created_at')
            ->paginate(15);

        return view('dashboardAdm.region.users-pending', [
            'users' => $users,
            'region' => $region,
            'regionLabel' => $regionLabel,
            'totalUsers' => $users->total(),
        ]);
    }

    /**
     * Regional: Tampilkan detail user beserta booking mereka
     */
    public function regionUserDetail($region, $userId)
    {
        $admin = Auth::guard('admin')->user();
        
        // Validasi region
        if (!in_array($region, $this->validRegions)) {
            abort(404, 'Region tidak ditemukan');
        }
        
        // Access control
        if (!$admin->canAccessRegion($region)) {
            abort(403, "Anda tidak memiliki akses ke region {$region}");
        }

        // Ambil user
        $user = Costumers::findOrFail($userId);
        
        // Validasi bahwa user memiliki booking di region ini
        $hasBookingInRegion = Boking::where('customer_id', $userId)
            ->where('region', $region)
            ->exists();
        
        if (!$hasBookingInRegion) {
            abort(403, "User tidak memiliki booking di region {$region}");
        }

        // Ambil semua booking user di region ini
        $bookings = Boking::where('customer_id', $userId)
            ->where('region', $region)
            ->orderByDesc('created_at')
            ->get();

        $regionLabel = $this->getRegionLabel($region);

        return view('dashboardAdm.user-region-detail', [
            'user' => $user,
            'bookings' => $bookings,
            'region' => $region,
            'regionLabel' => $regionLabel,
            'totalBookings' => $bookings->count(),
        ]);
    }

    /**
     * Export Bookings to Excel
     */
    public function exportBookings()
    {
        // Check if user is authenticated as admin
        if (!Auth::guard('admin')->check()) {
            return redirect()->route('admin.login');
        }

        // Get filters from request
        $filters = [
            'status' => request('status', ''),
            'region' => request('region', ''),
            'date_from' => request('date_from', ''),
            'date_to' => request('date_to', ''),
        ];

        // Generate filename with timestamp
        $filename = 'booking-lapangan-' . date('d-m-Y-H-i-s') . '.xlsx';

        return Excel::download(new BookingsExport($filters), $filename);
    }
}
