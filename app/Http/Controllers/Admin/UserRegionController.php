<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Costumers;
use Illuminate\Http\Request;

class UserRegionController extends Controller
{
    // List semua user per region
    public function index()
    {
        $admin = auth('admin')->user();
        
        if ($admin->role === 'master') {
            $users = Costumers::orderBy('created_at', 'desc')->paginate(15);
        } else {
            // Get users yang memiliki booking di region admin
            $users = Costumers::distinct()
                ->join('bokings', 'costumers.id', '=', 'bokings.customer_id')
                ->where('bokings.region', $admin->region)
                ->select('costumers.*')
                ->orderBy('costumers.created_at', 'desc')
                ->paginate(15);
        }
        
        return view('dashboardAdm.user-region-list', compact('users'));
    }

    // List user yang sudah bayar
    public function paid()
    {
        $admin = auth('admin')->user();
        
        if ($admin->role === 'master') {
            $users = Costumers::orderBy('created_at', 'desc')->paginate(15);
        } else {
            // Get users dengan booking PAID di region admin
            $users = Costumers::distinct()
                ->join('bokings', 'costumers.id', '=', 'bokings.customer_id')
                ->where('bokings.region', $admin->region)
                ->where('bokings.status', 'paid')
                ->select('costumers.*')
                ->orderBy('costumers.created_at', 'desc')
                ->paginate(15);
        }
        
        return view('dashboardAdm.user-region-paid', compact('users'));
    }

    // List user yang pending pembayaran
    public function pending()
    {
        $admin = auth('admin')->user();
        
        if ($admin->role === 'master') {
            $users = Costumers::orderBy('created_at', 'desc')->paginate(15);
        } else {
            // Get users dengan booking PENDING di region admin
            $users = Costumers::distinct()
                ->join('bokings', 'costumers.id', '=', 'bokings.customer_id')
                ->where('bokings.region', $admin->region)
                ->where('bokings.status', 'pending')
                ->select('costumers.*')
                ->orderBy('costumers.created_at', 'desc')
                ->paginate(15);
        }
        
        return view('dashboardAdm.user-region-pending', compact('users'));
    }

    // Detail user
    public function detail($id)
    {
        $user = Costumers::findOrFail($id);
        
        // Cek akses - admin hanya bisa lihat user di region mereka
        $admin = auth('admin')->user();
        
        if ($admin->role !== 'master') {
            // Check jika user punya booking di region admin
            $hasAccessToUser = $user->bokings()
                ->where('region', $admin->region)
                ->exists();
            
            if (!$hasAccessToUser) {
                abort(403, 'Anda tidak memiliki akses ke user ini');
            }
        }
        
        // Ambil booking dari customer ini di region (jika admin regional)
        $bookingsQuery = $user->bokings();
        if ($admin->role !== 'master') {
            $bookingsQuery = $bookingsQuery->where('region', $admin->region);
        }
        
        $bookings = $bookingsQuery->orderBy('created_at', 'desc')->get();
        
        return view('dashboardAdm.user-region-detail', compact('user', 'bookings'));
    }
}
