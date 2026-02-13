<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Costumers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function ShowLogin()
    {
        // Jika sudah login, redirect ke dashboard
        if (Auth::check()) {
            return redirect()->route('costumers.dashboard.bypass');
        }
        
        return view('auth.login');
    }
    public function login(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:6'
        ], [
            'email.required' => 'Email harus diisi',
            'email.email' => 'Format email tidak valid',
            'password.required' => 'Password harus diisi',
            'password.min' => 'Password minimal 6 karakter'
        ]);

        // Cari customer berdasarkan email
        $costumer = Costumers::where('email', $validated['email'])->first();

        // Verifikasi customer ada dan password benar
        if (!$costumer || $validated['password'] !== $costumer->password) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors(['loginError' => 'Email atau password salah']);
        }

        // Login customer dengan guard default 'web'
        Auth::login($costumer);

        // Redirect ke dashboard pertama (bypass) - user bisa pindah region dari dashboard
        return redirect()
            ->route('costumers.dashboard.bypass')
            ->with('success', "Selamat datang {$costumer->name}!");
    }

    public function register()
    {
        // Jika sudah login, redirect ke dashboard
        if (Auth::check()) {
            return redirect()->route('costumers.dashboard.bypass');
        }
        
        return view('auth.register');
    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:costumers,email',
            'gender' => 'required|in:L,P',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'required|string',
            'address' => 'required|string|max:500',
        ], [
            'name.required' => 'Nama customer harus diisi',
            'email.required' => 'Email harus diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah terdaftar',
            'password.required' => 'Password harus diisi',
            'password.min' => 'Password minimal 8 karakter',
            'password.confirmed' => 'Konfirmasi password tidak cocok',
            'gender.required' => 'Jenis kelamin harus dipilih',
            'gender.in' => 'Jenis kelamin tidak valid',
            'phone.required' => 'Nomor telepon harus diisi',
            'address.required' => 'Alamat harus diisi',
        ]);

        // Jangan hash password - simpan plain text
        // Create customer baru
        Costumers::create($validated);

        return redirect()
            ->route('login')
            ->with('success', 'Registrasi berhasil! Silakan login dengan akun Anda.');
    }





    /**
     * Tampilkan halaman login admin
     */
    public function ShowLoginAdmin()
    {
        // Jika sudah login, redirect ke dashboard
        if (Auth::guard('admin')->check()) {
            return redirect()->route('admin.dashboard');
        }
        
        return view('auth.loginAdmin');
    }

    public function adminLogin(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'credential' => 'required|string|min:3', // bisa name atau email
            'password' => 'required|string|min:6'
        ], [
            'credential.required' => 'Nama atau email admin harus diisi',
            'credential.min' => 'Nama atau email minimal 3 karakter',
            'password.required' => 'Password harus diisi',
            'password.min' => 'Password minimal 6 karakter'
        ]);

        // Cari admin berdasarkan name atau email
        $admin = Admin::where('name', $validated['credential'])
            ->orWhere('email', $validated['credential'])
            ->first();

        // Verifikasi admin ada dan password benar
        if (!$admin || $validated['password'] !== $admin->password) {
            return back()
                ->withInput($request->only('credential'))
                ->withErrors(['loginError' => 'Nama/email atau password salah']);
        }

        // Login admin dengan guard 'admin'
        Auth::guard('admin')->login($admin, $request->boolean('remember'));

        // Redirect ke dashboard sesuai role
        if ($admin->isMaster()) {
            // Master admin -> Master Dashboard
            return redirect()
                ->route('admin.dashboard')
                ->with('success', "Selamat datang Master Admin {$admin->name}! 🎉");
        } else {
            // Regional admin -> Dashboard region mereka
            return redirect()
                ->route('admin.dashboard.region', ['region' => $admin->region])
                ->with('success', "Selamat datang {$admin->name}, Region {$admin->region_label}! 👋");
        }
    }

    /**
     * Tampilkan halaman register admin
     * Note: Sebaiknya register admin hanya bisa dilakukan oleh master admin
     * Untuk sekarang, kita matikan fitur ini dan gunakan seeder saja
     */
    public function adminRegister()
    {
        // Optional: Tambahkan middleware untuk cek master admin saja
        // if (!Auth::guard('admin')->check() || !$this->isMasterAdmin()) {
        //     return redirect()->route('loginAdmin')->with('error', 'Anda tidak memiliki akses');
        // }
        
        return view('auth.RegisterAdmin');
    }

    /**
     * Store admin baru dengan default is_active = true
     * 
     * Admin otomatis aktif saat dibuat, tidak perlu approval master
     * Default nilai is_active = true untuk semua admin baru
     */
    public function storeAdmin(Request $request)
    {
      
        // Validasi input
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:admins,name',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:master,regional',
            'region' => 'required_if:role,regional|in:bypass,ulakkarang,batusangkar',
            'is_active' => 'boolean',
        ], [
            'name.required' => 'Nama admin harus diisi',
            'name.unique' => 'Nama admin sudah terdaftar',
            'name.max' => 'Nama admin maksimal 255 karakter',
            'email.required' => 'Email admin harus diisi',
            'email.unique' => 'Email admin sudah terdaftar',
            'email.email' => 'Format email tidak valid',
            'password.required' => 'Password harus diisi',
            'password.min' => 'Password minimal 8 karakter',
            'password.confirmed' => 'Konfirmasi password tidak cocok',
            'role.required' => 'Role harus dipilih',
            'role.in' => 'Role hanya boleh master atau regional',
            'region.required_if' => 'Region harus dipilih untuk admin regional',
            'region.in' => 'Region tidak valid',
        ]);

        // Jika role adalah regional, pastikan region dipilih
        if ($validated['role'] === 'regional' && !isset($validated['region'])) {
            return back()
                ->withErrors(['region' => 'Region harus dipilih untuk admin regional']);
        }

        // Jangan hash password - simpan plain text
        $validated['is_active'] = $validated['is_active'] ?? true;

        // Create admin baru
        $newAdmin = Admin::create($validated);

        return back()
            ->with('success', "Admin '{$newAdmin->name}' berhasil dibuat! Role: {$newAdmin->role_label}");
    }

    /**
     * Logout user dari semua guard
     */
    public function logout(Request $request)
    {
        // Tentukan pesan dan route SEBELUM invalidate session
        $isAdmin = Auth::guard('admin')->check();
        
        if ($isAdmin) {
            // Admin logout
            Auth::guard('admin')->logout();
            $redirectRoute = 'loginAdmin';
            $message = 'Logout sebagai admin berhasil';
        } else {
            // Customer logout
            Auth::guard('web')->logout();
            $redirectRoute = 'login';
            $message = 'Logout berhasil';
        }
        
        // Invalidate session dan regenerate token SETELAH check
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()
            ->route($redirectRoute)
            ->with('success', $message);
    }
}
