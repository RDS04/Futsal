<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     * Middleware ini memastikan user sudah login sebagai admin dan akun aktif
    *
    * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
    */
    public function handle(Request $request, Closure $next): Response
    {
        // Cek apakah user admin sudah login menggunakan guard admin
        if (!Auth::guard('admin')->check()) {
            return redirect()->route('loginAdmin')
            ->with('error', 'Anda harus login sebagai admin terlebih dahulu');
        }
        
        // Cek apakah user adalah admin
        $user = Auth::guard('admin')->user();
        if (!$user instanceof Admin) {
            return redirect()->route('loginAdmin')
            ->with('error', 'Anda tidak memiliki akses sebagai admin');
        }
        
        return $next($request);
    }
}
?>