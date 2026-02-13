<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use App\Models\Costumers;

class CostumerMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Cek apakah user sudah login
        if (!Auth::check()) {
            return redirect()->route('register')
                ->with('error', 'Anda harus login terlebih dahulu');
        }

        // Cek apakah user adalah costumer
        $user = Auth::user();
        if (!$user instanceof Costumers) {
            return redirect()->route('login')
                ->with('error', 'Anda tidak memiliki akses sebagai user');
        }
        elseif ($user->region !== $request->route('region')) {
            return redirect()->route('dashboard.' . $user->region)
                ->with('error', 'Anda tidak memiliki akses ke dashboard ini');
        }

        return $next($request);
    }
}
