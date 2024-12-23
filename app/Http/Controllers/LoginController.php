<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function __construct()
    {
        $this->middleware('prevent.lgn')->only('index');
    }

    public function index()
    {
        if (Auth::check()) {
            if (Auth::user()->role === 'admin') {
                return redirect()->route('admin.dashboard');
            }
            return redirect('/');
        }

        return view('login');
    }

    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // Cek role user
            if (Auth::user()->role === 'admin') {
                // Set session untuk admin
                session([
                    'is_admin' => true,
                    'role' => 'admin',
                    'user_id' => Auth::id()
                ]);

                return redirect()->route('admin.dashboard')->with('notification', [
                    'type' => 'success',
                    'title' => 'Login Berhasil!',
                    'message' => 'Selamat datang di Dashboard Admin'
                ]);
            } else {
                // Set session untuk user biasa
                session([
                    'is_admin' => false,
                    'role' => 'user',
                    'user_id' => Auth::id()
                ]);

                return redirect()->intended('/')->with('notification', [
                    'type' => 'success',
                    'title' => 'Login Berhasil!',
                    'message' => 'Selamat datang kembali'
                ]);
            }
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->withInput();
    }

    public function logout(Request $request)
    {
        try {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('home')->with('success', [
                'title' => 'Logout Berhasil!',
                'message' => 'Anda telah keluar dari sistem'
            ]);
        } catch (\Exception $e) {
            \Log::error('Error during logout: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat logout');
        }
    }
}
