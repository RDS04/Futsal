<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserRegionController extends Controller
{
    // List semua user per region
     public function __construct()
    {
        $this->middleware('auth.admin');
    }

    // List semua user per region
    public function index()
    {
        $admin = auth('admin')->user();
        
        if ($admin->role === 'master') {
            $users = User::orderBy('created_at', 'desc')->paginate(15);
        } else {
            $users = User::where('region', $admin->region)
                ->orderBy('created_at', 'desc')
                ->paginate(15);
        }
        
        return view('dashboardAdm.user-region-list', compact('users'));
    }

    // List user yang sudah bayar
    public function paid()
    {
        $admin = auth('admin')->user();
        
        if ($admin->role === 'master') {
            $users = User::where('payment_status', 'paid')
                ->orderBy('created_at', 'desc')
                ->paginate(15);
        } else {
            $users = User::where('region', $admin->region)
                ->where('payment_status', 'paid')
                ->orderBy('created_at', 'desc')
                ->paginate(15);
        }
        
        return view('dashboardAdm.user-region-paid', compact('users'));
    }

    // List user yang pending pembayaran
    public function pending()
    {
        $admin = auth('admin')->user();
        
        if ($admin->role === 'master') {
            $users = User::where('payment_status', 'pending')
                ->orderBy('created_at', 'desc')
                ->paginate(15);
        } else {
            $users = User::where('region', $admin->region)
                ->where('payment_status', 'pending')
                ->orderBy('created_at', 'desc')
                ->paginate(15);
        }
        
        return view('dashboardAdm.user-region-pending', compact('users'));
    }

    // Detail user
    public function detail($id)
    {
        $user = User::findOrFail($id);
        
        // Cek akses - admin hanya bisa lihat user di region mereka
        $admin = auth('admin')->user();
        if ($admin->role !== 'master' && $user->region !== $admin->region) {
            abort(403, 'Unauthorized');
        }
        
        return view('dashboardAdm.user-region-detail', compact('user'));
    }
}
