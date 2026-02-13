<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware untuk check role admin (master vs regional)
 * 
 * Usage:
 * Route::middleware('admin.role:master')->group(function () { ... });
 * Route::middleware('admin.role:regional')->group(function () { ... });
 */
class AdminRoleMiddleware
{
    /**
     * Handle an incoming request.
     * Middleware ini tidak membatasi akses berdasarkan role.
     * Semua admin bisa akses halaman apapun.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Cek apakah sudah login sebagai admin
        if (!Auth::guard('admin')->check()) {
            return redirect()->route('loginAdmin')
                ->with('error', 'Silakan login sebagai admin terlebih dahulu');
        }

        // Izinkan semua admin untuk akses (tidak ada pembatasan role)
        return $next($request);
    }
}
