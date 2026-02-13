<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMasterMiddleware
{
    /**
     * Handle an incoming request - Only allow master admins
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $admin = Auth::guard('admin')->user();

        if (!$admin || $admin->role !== 'master') {
            abort(403, 'Hanya master admin yang dapat mengakses halaman ini');
        }

        return $next($request);
    }
}
