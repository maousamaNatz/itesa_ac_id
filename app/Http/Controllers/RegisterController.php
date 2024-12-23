<?php

/**
 * Controller Register
 *
 * @package App\Http\Controllers
 * @description Controller untuk mengelola proses registrasi pengguna baru
 */

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

/**
 * Class RegisterController
 * Mengelola proses registrasi pengguna baru di aplikasi
 *
 * @package App\Http\Controllers
 */
class RegisterController extends Controller
{
    /**
     * Constructor
     * Menerapkan middleware guest untuk memastikan hanya user yang belum login yang dapat mengakses
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Menampilkan halaman form registrasi
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('register');
    }

    /**
     * Memproses registrasi pengguna baru
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     *
     * @bodyParam username string required Username pengguna. Min:3, Max:20, Hanya huruf, angka, dan underscore
     * @bodyParam email string required Alamat email yang valid dan unik
     * @bodyParam password string required Password minimal 8 karakter
     * @bodyParam password_confirmation string required Konfirmasi password harus sama
     * @bodyParam terms boolean required Persetujuan syarat dan ketentuan
     *
     * @response 302 {
     *   "redirect": "/login",
     *   "with": {
     *     "notification": {
     *       "type": "success",
     *       "title": "Registrasi Berhasil!",
     *       "message": "Silakan login dengan akun yang telah dibuat"
     *     }
     *   }
     * }
     *
     * @response 302 {
     *   "redirect": "back",
     *   "with": {
     *     "errors": {
     *       "username": ["Username harus diisi"],
     *       "email": ["Email harus diisi"],
     *       "password": ["Password harus diisi"],
     *       "terms": ["Anda harus menyetujui syarat dan ketentuan"]
     *     }
     *   }
     * }
     */
    public function store(Request $request)
    {
        try {
            // Validasi input form
            $validator = Validator::make($request->all(), [
                'username' => 'required|string|min:3|max:20|unique:users|regex:/^[a-zA-Z0-9_]+$/',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8|confirmed',
                'terms' => 'required|accepted'
            ], [
                // Pesan error kustom dalam bahasa Indonesia
                'username.required' => 'Username harus diisi',
                'username.min' => 'Username minimal 3 karakter',
                'username.max' => 'Username maksimal 20 karakter',
                'username.unique' => 'Username sudah digunakan',
                'username.regex' => 'Username hanya boleh mengandung huruf, angka, dan underscore',
                'email.required' => 'Email harus diisi',
                'email.email' => 'Format email tidak valid',
                'email.unique' => 'Email sudah digunakan',
                'password.required' => 'Password harus diisi',
                'password.min' => 'Password minimal 8 karakter',
                'password.confirmed' => 'Konfirmasi password tidak cocok',
                'terms.required' => 'Anda harus menyetujui syarat dan ketentuan',
                'terms.accepted' => 'Anda harus menyetujui syarat dan ketentuan'
            ]);

            // Jika validasi gagal, kembalikan ke halaman registrasi dengan error
            if ($validator->fails()) {
                return back()
                    ->withErrors($validator)
                    ->withInput();
            }

            // Buat user baru dengan role default 'user'
            $user = User::create([
                'username' => $request->username,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'user',
                'profile_photo' => null,
                'bio' => null
            ]);

            // Redirect ke halaman login dengan notifikasi sukses
            return redirect()
                ->route('login')
                ->with('notification', [
                    'type' => 'success',
                    'title' => 'Registrasi Berhasil!',
                    'message' => 'Silakan login dengan akun yang telah dibuat'
                ]);

        } catch (\Exception $e) {
            // Log error dan kembalikan ke halaman registrasi dengan pesan error
            \Log::error('Error in register: ' . $e->getMessage());
            return back()
                ->with('notification', [
                    'type' => 'error',
                    'title' => 'Registrasi Gagal!',
                    'message' => 'Terjadi kesalahan saat mendaftar'
                ])
                ->withInput();
        }
    }
}
