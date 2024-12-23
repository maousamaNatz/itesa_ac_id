<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check() && Auth::user()->role === 'admin') {
            // Simpan status admin ke session
            session(['is_admin' => true]);
            return $next($request);
        }

        // Jika bukan admin, redirect ke halaman login
        return redirect('/login')->with('error', 'Akses ditolak. Anda harus login sebagai admin.');
    }
}
