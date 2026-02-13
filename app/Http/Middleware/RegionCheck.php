<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class RegionCheck
{
    /**
     * Handle an incoming request.
     * Middleware ini memastikan admin hanya bisa akses data sesuai region mereka
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $admin = Auth::guard('admin')->user();
        
        // Jika tidak ada admin, tidak perlu check region
        if (!$admin) {
            return $next($request);
        }

        // Store region di request untuk diakses di controller
        $request->attributes->add(['admin_region' => $admin->region]);

        return $next($request);
    }
}
