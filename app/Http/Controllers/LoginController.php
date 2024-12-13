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

            if (Auth::user()->role === 'admin') {
                return redirect()->route('admin.dashboard')->with('notification', [
                    'type' => 'success',
                    'title' => 'Login Berhasil!',
                    'message' => 'Selamat datang di Dashboard Admin'
                ]);
            }

            return redirect()->intended('/')->with('notification', [
                'type' => 'success',
                'title' => 'Login Berhasil!',
                'message' => 'Selamat datang kembali'
            ]);
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->withInput()->with('notification', [
            'type' => 'error',
            'title' => 'Login Gagal!',
            'message' => 'Email atau password yang Anda masukkan salah'
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->with('notification', [
            'type' => 'success',
            'title' => 'Logout Berhasil!',
            'message' => 'Anda telah keluar dari sistem'
        ]);
    }
}
