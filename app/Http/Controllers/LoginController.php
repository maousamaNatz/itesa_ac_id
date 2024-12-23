<?php

/**
 * Controller Login
 *
 * @package App\Http\Controllers
 * @description Controller untuk mengelola proses autentikasi pengguna
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Class LoginController
 * Mengelola proses login, logout dan autentikasi pengguna
 *
 * @package App\Http\Controllers
 */
class LoginController extends Controller
{
    /**
     * Constructor
     * Menerapkan middleware prevent.lgn untuk mencegah user yang sudah login mengakses halaman login
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('prevent.lgn')->only('index');
    }

    /**
     * Menampilkan halaman login
     * Jika user sudah login, redirect ke halaman yang sesuai dengan rolenya
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
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

    /**
     * Memproses autentikasi pengguna
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @bodyParam email string required Alamat email pengguna
     * @bodyParam password string required Password pengguna
     *
     * @response 302 {
     *   "redirect": "/admin/dashboard",
     *   "with": {
     *     "notification": {
     *       "type": "success",
     *       "title": "Login Berhasil!",
     *       "message": "Selamat datang di Dashboard Admin"
     *     }
     *   }
     * }
     *
     * @response 302 {
     *   "redirect": "/",
     *   "with": {
     *     "notification": {
     *       "type": "success",
     *       "title": "Login Berhasil!",
     *       "message": "Selamat datang kembali"
     *     }
     *   }
     * }
     *
     * @response 302 {
     *   "redirect": "back",
     *   "with": {
     *     "errors": {
     *       "email": ["Email atau password salah."]
     *     }
     *   }
     * }
     */
    public function authenticate(Request $request)
    {
        // Validasi input
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Coba melakukan login
        if (Auth::attempt($credentials)) {
            // Regenerasi session untuk keamanan
            $request->session()->regenerate();

            // Cek role user dan set session yang sesuai
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

        // Jika login gagal
        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->withInput();
    }

    /**
     * Memproses logout pengguna
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception Ketika terjadi kesalahan dalam proses logout
     *
     * @response 302 {
     *   "redirect": "/",
     *   "with": {
     *     "success": {
     *       "title": "Logout Berhasil!",
     *       "message": "Anda telah keluar dari sistem"
     *     }
     *   }
     * }
     */
    public function logout(Request $request)
    {
        try {
            // Logout user
            Auth::logout();

            // Invalidate session dan regenerate CSRF token
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('home')->with('success', [
                'title' => 'Logout Berhasil!',
                'message' => 'Anda telah keluar dari sistem'
            ]);
        } catch (\Exception $e) {
            // Log error jika terjadi masalah
            \Log::error('Error during logout: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat logout');
        }
    }
}
