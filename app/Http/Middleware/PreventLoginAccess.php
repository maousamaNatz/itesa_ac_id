<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PreventLoginAccess
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            // Jika user adalah admin, redirect ke dashboard admin
            if (Auth::user()->role === 'admin') {
                return redirect()->route('admin.dashboard')->with('notification', [
                    'type' => 'info',
                    'title' => 'Akses Ditolak',
                    'message' => 'Anda sudah login sebagai admin'
                ]);
            }

            // Jika user biasa, redirect ke halaman utama
            return redirect('/')->with('notification', [
                'type' => 'info',
                'title' => 'Akses Ditolak',
                'message' => 'Anda sudah login'
            ]);
        }

        return $next($request);
    }
}
