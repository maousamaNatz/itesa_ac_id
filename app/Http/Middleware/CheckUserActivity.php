<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class CheckUserActivity
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            // Ambil waktu terakhir aktivitas
            $lastActivity = session('last_activity');

            // Jika tidak ada aktivitas dalam 5 jam
            if ($lastActivity && Carbon::parse($lastActivity)->addHours(5)->isPast()) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return redirect()->route('login')->with('notification', [
                    'type' => 'info',
                    'title' => 'Session Expired',
                    'message' => 'Sesi Anda telah berakhir. Silakan login kembali.'
                ]);
            }

            // Update waktu aktivitas terakhir
            session(['last_activity' => Carbon::now()]);
        }

        return $next($request);
    }
}
