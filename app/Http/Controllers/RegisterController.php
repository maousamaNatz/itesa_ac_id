<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function index()
    {
        return view('register');
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'username' => 'required|string|min:3|max:20|unique:users|regex:/^[a-zA-Z0-9_]+$/',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8|confirmed',
                'terms' => 'required|accepted'
            ], [
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

            if ($validator->fails()) {
                return back()
                    ->withErrors($validator)
                    ->withInput();
            }

            $user = User::create([
                'username' => $request->username,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'user',
                'profile_photo' => null,
                'bio' => null
            ]);

            return redirect()
                ->route('login')
                ->with('notification', [
                    'type' => 'success',
                    'title' => 'Registrasi Berhasil!',
                    'message' => 'Silakan login dengan akun yang telah dibuat'
                ]);

        } catch (\Exception $e) {
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
