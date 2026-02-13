<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware untuk check akses region
 * 
 * Memastikan:
 * - Master admin bisa akses semua region
 * - Regional admin hanya bisa akses region mereka sendiri
 * 
 * Usage dalam route:
 * Route::middleware(['auth.admin', 'region.access'])->prefix('admin')->group(...)
 */
class RegionAccessMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $admin = Auth::guard('admin')->user();
        $region = $request->route('region');

        // Jika tidak ada region parameter, lanjutkan
        if (!$region) {
            return $next($request);
        }

        // Master admin bisa akses semua region
        if ($admin->role === 'master') {
            return $next($request);
        }

        // Regional admin hanya bisa akses region mereka
        if ($admin->region !== $region) {
            abort(403, "Anda tidak memiliki akses ke region {$region}");
        }

        return $next($request);
    }
}
